<?php

class MensajesController extends Controller
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // Permitir el acceso si el usuario es admin
				'users' => array('@'),
			),
			array('deny',  // Denegar todo lo demás
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex()
	{
		$recibidos = array();
		$enviados = array();
		try {
			$usuario = Yii::app()->user->getUser();

			if (!$usuario) {
				throw new CHttpException(500,'Ha ocurrido un error grave, por favor intentalo de nuevo');	
			}

			$recibidos = $usuario->mensajesRecibidosNoBorrados;
			$enviados = $usuario->mensajesEnviados;
		} catch (Exception $e) {
			Yii::log(print_r($e->getMessage(), 1), 'trace', 'devel');
		}

		$this->render('index', array(
			'mensajesRecibidos' => $recibidos,
			'mensajesEnviados' => $enviados
		));		
	}

	public function actionView($id)
	{
		try {
			// Comprobar que el mensaje es de o para el usuario
			$usuario = Yii::app()->user->getUser();

			if (!$usuario) {
				throw new CHttpException(500,'Ha ocurrido un error grave, por favor intentalo de nuevo');	
			}

			$mensaje = Mensaje::model()->findByAttributes(array(
				'id' => $id,
				'id_usuario' => $usuario->id
			));

			$enviado = true;

			if (!$mensaje) {
				$mensaje = Mensaje::model()->findByAttributes(array(
					'id' => $id,
					'id_destinatario' => $usuario->id
				));

				if ($mensaje) {
					$mensaje->estado = Mensaje::STATUS_LEIDO;
					$mensaje->save();

					$enviado = false;
				} else {
					throw new CHttpException(403, 'No estas autorizado para ver este mensaje, por favor no lo intentes de nuevo');	
				}
			}
			
		} catch (Exception $e) {
			Yii::log(print_r($e->getMessage(), 1), 'trace', 'devel');
		}

		$this->render('view', array(
			'mensaje' => $mensaje,
			'enviado' => $enviado
		));	
	}

	public function actionNuevo() {

		if (Yii::app()->user->isAdoptante()) {
			throw new CHttpException(403,'No estas autorizado para hacer esto, por favor no lo intentes de nuevo');
		}

		$usuario = Yii::app()->user->getUser();

		if (!$usuario) {
			throw new CHttpException(500,'Ha ocurrido un error grave, por favor intentalo de nuevo');	
		}

        $model = new Mensaje();

        $destinatario = null;

        // Recoger datos
        if (Yii::app()->request->isPostRequest) {
	        $idDestinatario = Yii::app()->request->getPost('usuario');
	        $destinatario = Usuario::model()->findByPk($idDestinatario);
	    }

	    $usuariosDisponibles = $usuario->getUsersNames();

        // Mostrar formulario
        $this->render('nuevo', array(
        	'model' => $model, 
        	'usuario' => $usuario,
        	'destinatario' => $destinatario,
        	'usuariosDisponibles' => $usuariosDisponibles
    	));
    }

    public function actionEnviar() {

		if (Yii::app()->request->isPostRequest) {
			$usuario = Yii::app()->user->getUser();

			if (!$usuario) {
				throw new CHttpException(500,'Ha ocurrido un error grave, por favor intentalo de nuevo');	
			}

			if (Yii::app()->user->isAdoptante()) {
				throw new CHttpException(403,'No estas autorizado para hacer esto, por favor no lo intentes de nuevo');
			}

	        $idDestinatario = Yii::app()->request->getPost('destinatario');
	        $destinatario = Usuario::model()->findByPk($idDestinatario);

	        $mensaje = new Mensaje();

	        $titulo = Yii::app()->request->getPost('titulo');
	        $contenido = Yii::app()->request->getPost('mensaje');

	        $mensaje->titulo = $titulo;
	        $mensaje->contenido = $contenido;
	        $mensaje->id_usuario = $usuario->id;
    		$mensaje->id_destinatario = $destinatario->id;

	        // Validar y crear Mensaje
	        if ($mensaje->validate()) {
		        try {
	        		if (!$destinatario) {
	        			throw new Exception("El destinatario no existe");
	        		}

	        		$mensaje->save();

	        		Yii::app()->user->setFlash('success', 'Mensaje enviado con éxito');

	        		$this->redirect($this->redirect(Yii::app()->createUrl('mensajes')));
			        
		        } catch(Exception $e) {
		        	Yii::app()->user->setFlash('danger', $e->getMessage());
		        }
	        } else {
	        	foreach ($mensaje->getErrors() as $value) {
	        		Yii::app()->user->setFlash('danger', (is_array($value))?$value[0]:$value);
	        	}
	        }

	        $usuariosDisponibles = $usuario->getUsersNames();

	        // Mostrar formulario
	        $this->render('nuevo', array(
	        	'model' => $mensaje, 
	        	'usuario' => $usuario,
	        	'destinatario' => $destinatario,
	        	'usuariosDisponibles' => $usuariosDisponibles
	    	));
	    } else {
	    	throw new CHttpException(403,'No estas autorizado para hacer esto, por favor no lo intentes de nuevo');
	    }
    }
	
}
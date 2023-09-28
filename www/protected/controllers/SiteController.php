<?php

class SiteController extends Controller
{
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		/*
         * El usuario ha accedido ya al sistema?
         */
        if (!Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->user->dashboard());
        }
    	else {
			$animales = Animal::model()->disponibles()->findAll();
			$this->render('index', array('animales'=>$animales));
		}
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
     * Displays the login page
     */
    public function actionLogin() {
        /*
         * El usuario ha accedido ya al sistema?
         */
        if (!Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->user->dashboard());
        }

        $user = new Usuario();
        $user->scenario = 'registro';

        $model = new LoginForm;
        
        // Recoger datos
        if (Yii::app()->request->isPostRequest) {
	        $model->attributes = $_POST;
	        
	        // Validar y redirigir
	        if ($model->validate() && $model->login()) {
	            $this->redirect(Yii::app()->user->returnUrl);
	        } else {
	        	foreach ($model->getErrors() as $value) {
	        		Yii::app()->user->setFlash('danger', (is_array($value))?$value[0]:$value);
	        	}
	        }
	    }

        // Mostrar formulario
        $this->render('login', array('model' => $model, 'user' => $user));
    }

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	/**
     * Displays the register page
     */
    public function actionRegistro() {
        /*
         * El usuario ha accedido ya al sistema?
         */
        if (!Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->user->dashboard());
        }

        $user = new Usuario();
        $user->scenario = 'registro';
        $user->tipo = Usuario::TYPE_ADOPTANTE;
        $user->estado = Usuario::STATUS_NEW;
        
        // Recoger datos
        if (Yii::app()->request->isPostRequest) {
	        $user->attributes = $_POST;

	        $fNac = Yii::app()->request->getPost('f_nacimiento', NULL);
	        $dni = Yii::app()->request->getPost('dni', NULL);
	        if (empty($fNac)) {
	        	$user->f_nacimiento = NULL;
	        } else {
	        	$user->f_nacimiento = $fNac;
	        }
	      	if (empty($dni)) {
	        	$user->dni = NULL;
	        }
	        
	        // Validar y crear Usuario
	        if ($user->validate()) {
	            $transaction = Yii::app()->db->beginTransaction();
                try {
                    if (!$user->save()) {
                        throw new Exception(serialize($user->getErrors()), 1);
                    }
                    
            		$profile = new Adoptante;
                    $profile->id_usuario = $user->id;
                    if (!$profile->save()) {
                        throw new Exception(serialize($profile->getErrors()), 2);
                    }

                    // Enviar email de confirmación
                    try {
	                    $subject = 'Te has registrado en Asoka';
	        			$body = Yii::app()->controller->renderPartial('//email/ConfirmarRegistro', array('user'=>$user), true);
	                    Mailer::send(array(
	                    	'to'=>$user->email,
	                    	'subject'=>$subject,
	                    	'body'=>$body
	                	));
	                } catch (Exception $e) {
	                	throw new Exception("Ha habido un problema, por favor intentalo de nuevo");
	                }

                    $transaction->commit();

                    Yii::app()->user->setFlash(
                    	'success',
                    	'Enhorabuena tu cuenta se ha creado correctamente.<br/>
                    	En los próximos minutos recibirás un correo de confirmación y podrás acceder al sistema'
                	);

                	$this->redirect(Yii::app()->createUrl('login'));

                } catch (Exception $e) {
                    // Error, rollback transaction
                    foreach ($e as $value) {
		        		Yii::app()->user->setFlash('danger', (is_array($value))?$value[0]:$value);
		        	}
                    $transaction->rollback();
                }
	        } else {
	        	foreach ($user->getErrors() as $value) {
	        		Yii::app()->user->setFlash('danger', (is_array($value))?$value[0]:$value);
	        	}
	        }
	    }

        // Mostrar formulario
        $this->render('registro', array('model' => $user));
    }

    public function actionConfirmar($id, $key) {
    	/*
         * El usuario ha accedido ya al sistema?
         */
        if (!Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->user->dashboard());
        }

        // Buscar el usuario
    	$user = Usuario::model()->findByAttributes(array(
            'id' => $id,
            'registration_key' => $key
        ));

        if (!$user) {
        	throw new CHttpException(404, "Este enlace no es válido");
        }

        if ($user->isVerified()) {
        	throw new CHttpException(404, "Esta cuenta ya ha sido verificado");
        }

        try {
        	$user->estado = Usuario::STATUS_ACTIVE;
        	$user->save();
        } catch (Exception $e) {
        	Yii::log($e, 'trace', 'devel');
        	throw new CHttpException(500, "Ha ocurrido un error, por favor, intentalo de nuevo");
        }

        Yii::app()->user->setFlash('success', "Tu cuenta ha sido verificada");
        $this->redirect('/login');
    }
}
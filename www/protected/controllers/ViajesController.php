<?php

class ViajesController extends Controller
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
				'expression' => '$user->isAdmin() || $user->isVoluntario()',
			),
			array('deny',  // Denegar todo lo demás
				'users'=>array('*'),
			),
		);
	}

	public function actionListarViajes()
	{
		$model = new Viaje();
		$model->unsetAttributes();
		$this->render('index', array(
			'model'=>$model
		));		
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreateViaje()
	{
		$model=new Viaje();
		$animales = Animal::model()->disponibles()->findAll();

		// Recoger datos
		if(Yii::app()->request->isPostRequest) {

			$viaje = new Viaje();
			$viaje->attributes =$_POST;

			$idAnimal = Yii::app()->request->getPost('animal',NULL);
			$animal = Animal::model()->findByPk($idAnimal);

			if (!$animal) {
				throw new CHttpException(500,'Ha ocurrido un error grave, por favor intentalo de nuevo');	
			}

			$viaje->id_animal = $animal->id;

	        // Validar y crear Mensaje
	        if ($viaje->validate()) {
		        try {
		        	$transaction = Yii::app()->db->beginTransaction();

	        		if (!$viaje->save()) {
	        			throw new Exception("Ha habido un error, por favor intentalo de nuevo");
	        		}

	        		if (!$animal->canTravel()) {
	        			throw new Exception("Este animal no puede viajar");
	        		}

	        		$animal->estado = Animal::STATUS_DE_CAMINO;

	        		if (!$animal->save()) {
	        			throw new Exception("Ha habido un error, por favor intentalo de nuevo");
	        		}

	        		$transaction->commit();

	        		Yii::app()->user->setFlash('success', 'Viaje creado con éxito');

	        		$this->redirect($this->redirect(Yii::app()->createUrl('viajes')));
			        
		        } catch(Exception $e) {
		        	Yii::app()->user->setFlash('danger', $e->getMessage());
		        	$transaction->rollback();
		        }
	        } else {
	        	foreach ($viaje->getErrors() as $value) {
	        		Yii::app()->user->setFlash('danger', (is_array($value))?$value[0]:$value);
	        	}
	        }
		}

		$this->render('create',array(
			'model'=>$model,
			'animales'=>$animales
		));
	}

	public function actionCambiarEstado($id) {

    	if (Yii::app()->request->isAjaxRequest) {

    		try {
        		$animal = Animal::model()->findByPk($id);

        		if (!$animal) {
        			throw new Exception("No existe el animal");
        		}

        		$estado = Yii::app()->request->getPost('estado', NULL);

        		if ($estado == NULL) {
        			throw new Exception("Tienes que especificar un estado");
        		}

        		$animal->estado = $estado;

        		if (!$animal->save()) {
        			throw new Exception("Error");
        		}

        		$data['estado'] = $animal->estado;
    		} catch (Exception $e) {
    			$data['error'] = $e->getMessage();
    		}
			
			echo CJavaScript::jsonEncode($data);
			Yii::app()->end();
		} else {
			throw new CHttpException(403, "No tienes permiso para hacer eso, por favor, no lo intentes de nuevo");
		}
    }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Viaje the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Viaje::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}

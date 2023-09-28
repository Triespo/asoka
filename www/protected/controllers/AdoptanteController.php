<?php

class AdoptanteController extends Controller
{
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	public function accessRules(){
		return array(
			array(
				'allow',
				'users' => array('@'),
				'expression' => '$user->isAdoptante()',
			),
			array('deny'),
		);
	}

	public function actionIndex(){
		$this->redirect('/adoptante/dashboard');
	}

	public function actionDashboard(){
		$user = Yii::app()->user->getUser();

		$dataProvider =  new CArrayDataProvider('Animal');
		$dataProvider->setData($user->adoptante->animales);
		$this->render('dashboard', array('dataProvider'=>$dataProvider));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Adoptante the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Adoptante::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Adoptante $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='adoptante-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

<?php

class VoluntarioController extends Controller
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
				'expression' => '$user->isVoluntario()',
			),
			array('deny'),
		);
	}

	public function actionIndex(){
		$this->redirect('/voluntario/dashboard');
	}

	public function actionDashboard(){
		$this->render('dashboard');
	}

	public function actionAnimales()
	{
		$model = new Animal('search');
		$model->unsetAttributes();  // limpia valores por defecto
 
		$this->render('/animal/index', array('model'=>$model));
	}

	public function actionCreateAnimal() {

        $animal = new Animal();
        $animal->scenario = 'registro';
        
        // Recoger datos
        if (Yii::app()->request->isPostRequest) {
	        $animal->attributes = $_POST;

	        $fNac = Yii::app()->request->getPost('f_nacimiento', NULL);
	        if (empty($fNac)) {
	        	$animal->f_nacimiento = NULL;
	        } else {
	        	$animal->f_nacimiento = $fNac;
	        }
	        
	        // Validar y crear Usuario
	        if ($animal->validate()) {
                try {
                    if (!$animal->save()) {
                        throw new Exception(serialize($animal->getErrors()), 1);
                    }

                    Yii::app()->user->setFlash('success', 'Animal creado con éxito');

                	$this->redirect(Yii::app()->createUrl('voluntario/animales'));

                } catch (Exception $e) {
                    // Error, rollback transaction
                    foreach ($e as $value) {
		        		Yii::app()->user->setFlash('danger', (is_array($value))?$value[0]:$value);
		        	}
                }
	        } else {
	        	foreach ($animal->getErrors() as $value) {
	        		Yii::app()->user->setFlash('danger', (is_array($value))?$value[0]:$value);
	        	}
	        }
	    }

        // Mostrar formulario
        $this->render('/animal/create', array('model' => $animal));
    }

    public function actionEditAnimal($id) {

        $animal = $this->loadAnimal($id);
        
        // Recoger datos
        if (Yii::app()->request->isPostRequest) {
	        $animal->attributes = $_POST;

	        $fNac = Yii::app()->request->getPost('f_nacimiento', NULL);
	        if (empty($fNac)) {
	        	$animal->f_nacimiento = NULL;
	        } else {
	        	$animal->f_nacimiento = $fNac;
	        }

	        $file = CUploadedFile::getInstanceByName("imagen");

            if ($file && (strtolower($file->getExtensionName()) == 'png' || strtolower($file->getExtensionName()) == 'jpg' || strtolower($file->getExtensionName()) == 'jpeg' || strtolower($file->getExtensionName()) == 'gif')) {
                $newname = time() . '.' . strtolower($file->getExtensionName());
                $animal->setAttribute('imagen', $newname);
            }
	        
	        // Validar y crear Animal
	        if ($animal->validate()) {
                try {
                    if (!$animal->save()) {
                        throw new Exception(serialize($animal->getErrors()), 1);
                    }

                    // Guardar la imagen
                    if ($file) {
                    	try {
		                    $folder = Yii::getPathOfAlias('webroot').Animal::IMG_DIR;
		                    if (!is_dir($folder)) {
		                        mkdir($folder, 0777, true);
		                    }
		                    $folder = Yii::getPathOfAlias('webroot').Animal::IMG_DIR.'/'.$animal->id;
		                    if (!is_dir($folder)) {
		                        mkdir($folder, 0777, true);
		                    }

		                    // Guardar original
		                    Common::saveImage($folder,'','', $newname,array('file'=>$file));
		                    
		                    // Resize
		                    Common::resizeOrCrop($animal->imagen,$newname,$folder,340);
		                    
		                    // Crop
		                    Common::resizeOrCrop($animal->imagen,$newname,$folder,340,'crop');

	                    } catch (Exception $e) {
	                    	throw new Exception("Ha habido un problema, por favor, intentalo de nuevo");
                    	}
	                }

                    Yii::app()->user->setFlash('success', 'Animal editado con éxito');

                	$this->redirect(Yii::app()->createUrl('voluntario/animales'));

                } catch (Exception $e) {
                    // Error
                	Yii::app()->user->setFlash('danger', $e->getMessage());
                }
	        } else {
	        	foreach ($animal->getErrors() as $value) {
	        		Yii::app()->user->setFlash('danger', (is_array($value))?$value[0]:$value);
	        	}
	        }
	    }
		// Re convertir la fecha 
	   	$animal->f_nacimiento = strtotime(str_replace("/", "-", $animal->f_nacimiento));

        // Mostrar formulario
        $this->render('/animal/create', array('model' => $animal));
    }

    public function actionDeleteAnimal($id){

    	Animal::model()->deleteByPk($id);
    	Yii::app()->user->setFlash('success', 'Animal borrado con éxito');
    	$this->redirect(Yii::app()->createUrl('voluntario/animales'));

    }

    public function loadAnimal($id){
		$model = Animal::model()->findByPk($id);

		if(!$model){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	public function loadModel($id){
		$model = Voluntario::model()->findByPk($id);

		if(!$model){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}
}
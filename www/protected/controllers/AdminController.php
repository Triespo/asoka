<?php

class AdminController extends Controller
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
                'expression' => '$user->isAdmin()',
			),
			array('deny',  // Denegar todo lo demás
				'users'=>array('*'),
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$this->redirect('/admin/dashboard');
	}

	public function actionDashboard()
	{ 
		$model = new Admin('search');
		$model->unsetAttributes();  // limpia valores por defecto
 
		$this->render('dashboard', array('model'=>$model));
	}

	public function actionUsuarios()
	{
		$model = new Usuario('search');
		$model->unsetAttributes();  // limpia valores por defecto
 
		$this->render('users', array('model'=>$model));
	}

	public function actionNewUser() {
        
        $type = Usuario::TYPE_VOLUNTARIO;

        $model = new Usuario();

        // Recoger datos
        if (Yii::app()->request->isPostRequest) {
	        $type = Yii::app()->request->getPost('user-type');
	    }

	    $model->tipo = $type;

        // Mostrar formulario
        $this->render('new', array('model' => $model, 'type' => $type));
    }

    public function actionNewAdoptante() {
        
        $type = Usuario::TYPE_ADOPTANTE;

        $model = new Usuario();

        // Recoger datos
        if (Yii::app()->request->isPostRequest) {
	        $type = Yii::app()->request->getPost('user-type');
	    }

	    $model->tipo = $type;

        // Mostrar formulario
        $this->render('new', array('model' => $model, 'type' => $type));
    }

    public function actionCreateUser() {

        $user = new Usuario();
        $user->scenario = 'registro';
        
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
                    switch ($user->tipo) {
                    	case Usuario::TYPE_ADMIN:
                    		$profile = new Admin;
                    		break;
                    	case Usuario::TYPE_VOLUNTARIO:
                    		$profile = new Voluntario;
                    		break;
                    	case Usuario::TYPE_ADOPTANTE:
                    		$profile = new Adoptante;
                    		break;
                    	default:
                    		$profile = new Adoptante;
                    		break;
                    }
                    $profile->id_usuario = $user->id;
                    if (!$profile->save()) {
                        throw new Exception(serialize($profile->getErrors()), 2);
                    }
                    $transaction->commit();

                    Yii::app()->user->setFlash('success', 'Usuario creado con éxito');

                	$this->redirect(Yii::app()->createUrl('admin/usuarios'));

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
        $this->render('new', array('type' => $user->tipo, 'model' => $user));
    }

    public function actionEditUser($id) {

        $user = $this->loadUser($id);

        if (!Yii::app()->user->getUser()->canEdit($user)) {
	    	throw new CHttpException(403, 'No tiene permisos para realizar esta acción, por favor no lo intente de nuevo.');
	    }

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
                try {
                    if (!$user->save()) {
                        throw new Exception(serialize($user->getErrors()), 1);
                    }

                    Yii::app()->user->setFlash('success', 'Usuario editado con éxito');

                	$this->redirect(Yii::app()->createUrl('admin/usuarios'));

                } catch (Exception $e) {
                    // Error
                    foreach ($e as $value) {
		        		Yii::app()->user->setFlash('danger', (is_array($value))?$value[0]:$value);
		        	}
                }
	        } else {
	        	foreach ($user->getErrors() as $value) {
	        		Yii::app()->user->setFlash('danger', (is_array($value))?$value[0]:$value);
	        	}
	        }
	    }

        // Mostrar formulario
        $this->render('new', array('type' => $user->tipo, 'model' => $user));
    }

    public function actionAnimales() {

		$model = new Animal('search');
		$model->unsetAttributes();  // limpia valores por defecto
 
		$this->render('/animal/index', array('model'=>$model));
	}

    public function actionDeleteUser($id){

    	$user = Usuario::model()->findByPk($id);

    	switch ($user->tipo) {
    		case Usuario::TYPE_ADMIN:
    			$userAdmin = Admin::model()->find('id_usuario=:id', array(':id'=>$id));
            	$userAdmin->delete();
            	break;
            case Usuario::TYPE_VOLUNTARIO:
            	$userVoluntario = Voluntario::model()->find('id_usuario=:id', array(':id'=>$id));
            	$userVoluntario->delete();
            	break;
            case Usuario::TYPE_ADOPTANTE:
            	$userAdoptante = Adoptante::model()->find('id_usuario=:id', array(':id'=>$id));
            	$userAdoptante->delete();
            	break;
            default:
            	break;
        }

    	$user->delete();
    	
    	Yii::app()->user->setFlash('success', 'Usuario borrado con éxito');

    	//Vista por defecto
    	$view_direction = 'user';

    	//Obtenemos la URL que ha referenciado al controlador
    	$url = Yii::app()->request->getUrlReferrer();

    	if($url){
    		$string_array = preg_split('[/]', $url);
    		$view_direction = array_pop($string_array);
    	}

    	$this->redirect(Yii::app()->createUrl('admin') . '/' . $view_direction);
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

	        Yii::log(print_r($_FILES, 1), 'trace', 'devel');

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

                    Yii::app()->user->setFlash('success', 'Animal creado con éxito');

                	$this->redirect(Yii::app()->createUrl('admin/animales'));

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

    public function actionDeleteAnimal($id){

    	Animal::model()->deleteByPk($id);
    	Yii::app()->user->setFlash('success', 'Animal borrado con éxito');
    	$this->redirect(Yii::app()->createUrl('admin/animales'));

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

                	$this->redirect(Yii::app()->createUrl('admin/animales'));

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

    public function actionVoluntarios()
	{
		$model = new Voluntario('search');
		$model->unsetAttributes();  // limpia valores por defecto
 
		$this->render('voluntarios', array('model'=>$model));
	}

	public function actionAdoptantes()
	{
		$model = new Adoptante();
		$model->unsetAttributes();  // limpia valores por defecto
 
		$this->render('adoptantes', array('model'=>$model));
	}

	public function actionEditAdoptantes()
	{
		$adoptante = $this->loadUser($id);

        if (!Yii::app()->user->getUser()->canEdit($user)) {
	    	throw new CHttpException(403, 'No tiene permisos para realizar esta acción, por favor no lo intente de nuevo.');
	    }

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
                try {
                    if (!$user->save()) {
                        throw new Exception(serialize($user->getErrors()), 1);
                    }

                    Yii::app()->user->setFlash('success', 'Usuario editado con éxito');

                	$this->redirect(Yii::app()->createUrl('admin/usuarios'));

                } catch (Exception $e) {
                    // Error
                    foreach ($e as $value) {
		        		Yii::app()->user->setFlash('danger', (is_array($value))?$value[0]:$value);
		        	}
                }
	        } else {
	        	foreach ($user->getErrors() as $value) {
	        		Yii::app()->user->setFlash('danger', (is_array($value))?$value[0]:$value);
	        	}
	        }
	    }

        // Mostrar formulario
        $this->render('new', array('type' => $user->tipo, 'model' => $user));
    }
	
    public function actionCambiarEstado($id) {

    	if (Yii::app()->request->isAjaxRequest) {

    		try {
        		$usuario = Usuario::model()->findByPk($id);

        		if (!$usuario) {
        			throw new Exception("No existe el usuario");
        		}

        		$estado = Yii::app()->request->getPost('estado', NULL);

        		if ($estado == NULL) {
        			throw new Exception("Tienes que especificar un estado");
        		}

        		$usuario->estado = $estado;

        		if (!$usuario->save()) {
        			throw new Exception("Error");
        		}

        		$data['estado'] = $usuario->estado;
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
	 * @return Admin the loaded model
	 * @throws CHttpException
	 */
	public function loadUser($id)
	{
		$model=Usuario::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	public function loadAnimal($id)
	{
		$model=Animal::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Admin $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='admin-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

<?php 
class WebUser extends CWebUser {
 
	// Store model to not repeat query.
	private $_model;

    public function isSuperAdmin() {
        $user = $this->loadUser(Yii::app()->user->id);
        return ($user)?$user->isSuperAdmin():false;
    }
    
    public function isAdmin() {
        $user = $this->loadUser(Yii::app()->user->id);
        return ($user)?$user->isAdmin():false;
    }
    
    public function isVoluntario() {
        $user = $this->loadUser(Yii::app()->user->id);
        return ($user)?$user->isVoluntario():false;
    }
    
    public function isAdoptante() {
        $user = $this->loadUser(Yii::app()->user->id);
        return ($user)?$user->isAdoptante():false;
    }

    public function getEmail() {
        $user = $this->loadUser(Yii::app()->user->id);
        return ($user)?$user->email:'';
    }

    public function getUser() {
        return $this->loadUser(Yii::app()->user->id);
    }

    public function dashboard() {
        $user = $this->loadUser(Yii::app()->user->id);
        $url = Yii::app()->homeUrl;
        if ($user) {
            if ($user->isAdmin()) {
                $url = '/admin/dashboard';
            } else if ($user->isVoluntario()) {
                $url = '/voluntario/dashboard';
            } else if ($user->isAdoptante()) {
                $url = '/adoptante/dashboard';
            }
        }
        return $url;
    }
        
	// Load user model.
	protected function loadUser($id=null) {
        if($this->_model===null) {
	    	if($id!==null) {
	           $this->_model=Usuario::model()->findByPk($id);
           }
        }
	    return $this->_model;
	}
}
?>
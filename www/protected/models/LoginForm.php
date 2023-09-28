<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $username;
	public $password;
	public $rememberMe;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username, password', 'required','message'=>'El campo {attribute} no puede estar vacío'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
			// email tiene que ser valido
			array('username', 'check_email'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
            'username'=>'Email',
            'rememberMe'=>'Recuerdame',
            'password'=>'Contraseña'
        );
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->username,$this->password);

			$authenticate = $this->_identity->authenticate();

			if (!$authenticate['result']) {
				switch ($authenticate['error']) {
					case UserIdentity::ERROR_USER_NEW:
						$this->addError('','Aún no has verificado tu cuenta, por favor, revisa tu correo electrónico');
						break;
					case UserIdentity::ERROR_USER_INACTIVE:
						$this->addError('','No puedes acceder con esta cuenta, contacta con el administrador');
						break;
					default:
						$this->addError('password','Email o contraseña incorrectos');
						break;
				}
			}
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null) {
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
        if($this->_identity->errorCode===UserIdentity::ERROR_NONE) {
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		} else {
			return false;
		}
	}

	public function check_email($attribute,$params)
	{
		if(!Common::check_email_address($this->username)){
            $this->addError('email','Email no válido');
        }
	}
}

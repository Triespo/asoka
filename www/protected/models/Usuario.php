<?php

/**
 * This is the model class for table "Usuario".
 *
 * The followings are the available columns in table 'Usuario':
 * @property integer $id
 * @property string $email
 * @property string $password
 * @property string $nombre
 * @property string $apellidos
 * @property string $direccion
 * @property string $telefono
 * @property string $dni
 * @property integer $tipo
 * @property integer $estado
 * @property string $f_nacimiento
 * @property integer $borrado
 * @property string $f_creado
 * @property string $f_modificado
 * @property string $f_borrado
 *
 * The followings are the available model relations:
 * @property Admin[] $admins
 * @property Adoptante[] $adoptantes
 * @property Mensaje[] $mensajesRecibidos
 * @property Mensaje[] $mensajesEnviados
 * @property Voluntario[] $voluntarios
 */
class Usuario extends CActiveRecord
{
	// Tipos
	const TYPE_ADMIN = 1;
	const TYPE_VOLUNTARIO = 2;
    const TYPE_ADOPTANTE = 3;

    // Estados
    const STATUS_BANNED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_NEW = 3;

    public $password_confirm;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Usuario';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email, password', 'required'),
			array('id, tipo, estado, borrado', 'numerical', 'integerOnly'=>true),
			array('email, direccion', 'length', 'max'=>120),
			array('password', 'length', 'max'=>256),
			array('nombre', 'length', 'max'=>45),
			array('apellidos', 'length', 'max'=>90),
			array('telefono, dni', 'length', 'max'=>9),
			array('f_nacimiento, f_creado, f_modificado, f_borrado', 'safe'),
			array('id, email, password, nombre, apellidos, direccion, telefono, dni, tipo, estado, f_nacimiento, borrado, f_creado, f_modificado, f_borrado', 'safe', 'on'=>'search'),
			array('password_confirm', 'safe', 'on'=>'registro'),
            array('password', 'check_password','on'=>'registro'),
            array('email', 'check_email','on'=>'registro'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'admin' => array(self::HAS_ONE, 'Admin', 'id_usuario'),
			'adoptante' => array(self::HAS_ONE, 'Adoptante', 'id_usuario'),
			'mensajesRecibidos' => array(self::HAS_MANY, 'Mensaje', 'id_destinatario'),
            'mensajesRecibidosNoBorrados' => array(self::HAS_MANY, 'Mensaje', 'id_destinatario',
                'on'=>'mensajesRecibidosNoBorrados.borrado='.Mensaje::NO_BORRADO,
                'order'=>'estado ASC, f_envio DESC'),
			'mensajesEnviados' => array(self::HAS_MANY, 'Mensaje', 'id_usuario'),
            'mensajesNuevos' => array(self::HAS_MANY, 'Mensaje', 'id_destinatario',
                'on'=>'mensajesNuevos.borrado='.Mensaje::NO_BORRADO
                    .' and mensajesNuevos.estado='.Mensaje::STATUS_ENVIADO),
			'voluntario' => array(self::HAS_ONE, 'Voluntario', 'id_usuario'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'email' => 'Email',
			'password' => 'Password',
			'nombre' => 'Nombre',
			'apellidos' => 'Apellidos',
			'direccion' => 'Direccion',
			'telefono' => 'Telefono',
			'dni' => 'Dni',
			'tipo' => 'Tipo',
			'estado' => 'Estado',
			'f_nacimiento' => 'F Nacimiento',
			'borrado' => 'Borrado',
			'f_creado' => 'F Creado',
			'f_modificado' => 'F Modificado',
			'f_borrado' => 'F Borrado',
		);
	}

	public function validatePassword($password) {
		return PasswordHasher::validate_password($password,$this->password);
	}

	public function beforeValidate(){
        if($this->isNewRecord){
            $this->f_creado = new CDbExpression('NOW()');
        }
        else {
            $this->f_modificado = new CDbExpression('NOW()');
        }
        return parent::beforeValidate();
    }

	public function beforeSave(){
        if($this->isNewRecord){
            $this->registration_key = preg_replace('/[^A-Za-z0-9]+/i','',base64_encode(mcrypt_create_iv(PasswordHasher::PBKDF2_SALT_BYTE_SIZE, MCRYPT_DEV_URANDOM)));
            $this->password = PasswordHasher::create_hash($this->password);
        }
        if (!empty($this->f_nacimiento)) {
        	$this->setAttribute(
        		'f_nacimiento', 
        		date('Y-m-d H:i:s',  strtotime(str_replace('/', '-', $this->f_nacimiento)))
    		);
        }
        return parent::beforeSave();
    }

	public function check_email($attribute,$params)
	{
		if(!Common::check_email_address($this->email)){
            $this->addError('email','Email no válido');
        }
		else{   
            $user = Usuario::model()->findByAttributes(array('email'=>$this->email));
            if($user && ($this->isNewRecord || ($user->id != $this->id))) {
                $this->addError('email','Email en uso');
            }
        }
	}

	public function check_password($attribute,$params)
	{
		if($this->password != $this->password_confirm){
			$this->addError('password','Las contraseñas no coinciden');
        }
	}

	public function isSuperAdmin() {
        return ($this->tipo == Usuario::TYPE_ADMIN
        	&& $this->admin && $this->admin->isSuperAdmin());
    }
        
    public function isAdmin() {
        return $this->tipo == Usuario::TYPE_ADMIN;
    }
    
    public function isVoluntario() {
        return $this->tipo == Usuario::TYPE_VOLUNTARIO;
    }

    public function isAdoptante() {
        return $this->tipo == Usuario::TYPE_ADOPTANTE;
    }

    public function typeName() {
    	switch ($this->tipo) {
    		case Usuario::TYPE_ADMIN:
    			return ($this->isSuperAdmin())?'SuperAdministrador':'Administrador';
    			break;
    		case Usuario::TYPE_VOLUNTARIO:
    			return 'Voluntario';
    			break;
			case Usuario::TYPE_ADOPTANTE:
    			return 'Adoptante';
    			break;
    		default:
    			return 'Desconocido';
    			break;
    	}
    }

    public function statusName() {
    	switch ($this->estado) {
    		case Usuario::STATUS_BANNED:
    			return 'Baneado';
    			break;
    		case Usuario::STATUS_ACTIVE:
    			return 'Activo';
    			break;
    		case Usuario::STATUS_INACTIVE:
    			return 'Inactivo';
    			break;
    		default:
    			return 'Desconocido';
    			break;
    	}
    }

    public function getFullName() {
    	return $this->nombre . ' ' . $this->apellidos;
    }

    public function canEdit($user) {
    	switch ($this->tipo) {
    		case Usuario::TYPE_ADMIN:
    			if ($this->isSuperAdmin()) {
    				return true;
    			} else {
    				return (!$user->isAdmin());
    			}
    			break;
    		default:
    			return false;
    			break;
    	}
    }

    public function getUsersNames() {
        switch ($this->tipo) {
            case Usuario::TYPE_ADMIN:
                $usuarios = Usuario::model()->findAll();
                break;
            case Usuario::TYPE_VOLUNTARIO:
                $usuarios = Usuario::model()->findAll(
                    'tipo=:v OR tipo=:a', 
                    array(
                        ':v'=>self::TYPE_VOLUNTARIO,
                        ':a'=>self::TYPE_ADOPTANTE
                    )
                );
                break;
            default:
                $usuarios = array();
        }
        $names = array();
        foreach ($usuarios as $usuario) {
            if ($usuario->id != $this->id) {
                $names[$usuario->id] = $usuario->getFullName() . ' [' . $usuario->typeName() . ']';
            }
        }
        return $names;       
    }

    public function canEditAnimals() {
        switch ($this->tipo) {
            case Usuario::TYPE_ADMIN:
                return true;
                break;
            case Usuario::TYPE_VOLUNTARIO:
                return true;
                break;
            default:
                return false;
                break;
        }
    }

    public static function getTypeName($type) {
    	switch ($type) {
    		case Usuario::TYPE_ADMIN:
    			return 'Administrador';
    			break;
    		case Usuario::TYPE_VOLUNTARIO:
    			return 'Voluntario';
    			break;
			case Usuario::TYPE_ADOPTANTE:
    			return 'Adoptante';
    			break;
    		default:
    			return 'Desconocido';
    			break;
    	}
    }

    public function isActive() {
        return ($this->estado == Usuario::STATUS_ACTIVE);
    }

    public function isNew() {
        return ($this->estado == Usuario::STATUS_NEW);
    }

    public function isVerified() {
        return ($this->estado != Usuario::STATUS_NEW);
    }

    public function confirmationLink(){
        return Yii::app()->createAbsoluteUrl('confirmar') . '/' . $this->id . '/' . $this->registration_key;
    }

    public function getEstadoDropDown(){
        $estados = array(
            Usuario::STATUS_BANNED=>'Baneado', 
            Usuario::STATUS_ACTIVE=>'Activo',
            Usuario::STATUS_INACTIVE=>'Inactivo',
        );

        return Chtml::dropDownList('estado', $this->estado, $estados, array('class'=>'estado-usuario form-control'));
    }

    public function afterFind()
    {
    	// Convertir fechas MYSQL a timestamp PHP
    	if ($this->f_nacimiento) {
	        $this->f_nacimiento = strtotime($this->f_nacimiento);
        }

        if ($this->f_creado) {
	        $this->f_creado = strtotime($this->f_creado);
        }

        if ($this->f_modificado) {
	        $this->f_modificado = strtotime($this->f_modificado);
        }

        if ($this->f_borrado) {
	        $this->f_borrado = strtotime($this->f_borrado);
        }

        parent::afterFind();
        return true;
    }

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('email',$this->email,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Usuario the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

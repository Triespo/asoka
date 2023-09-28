<?php

/**
 * This is the model class for table "Animal".
 *
 * The followings are the available columns in table 'Animal':
 * @property integer $id
 * @property string $nombre
 * @property string $imagen
 * @property string $f_nacimiento
 * @property string $descripcion
 * @property integer $sexo
 * @property string $tamanyo
 * @property double $peso
 * @property string $raza
 * @property integer $estado
 * @property integer $id_adoptante
 * @property integer $borrado
 * @property string $f_creacion
 * @property string $f_modificacion
 * @property string $f_borrado
 *
 * The followings are the available model relations:
 * @property Adoptante $idAdoptante
 * @property AnimalVoluntario[] $animalVoluntarios
 * @property Viaje[] $viajes
 */
class Animal extends CActiveRecord
{
	const IMG_DIR = '/images/animal';

	// Tipos
	const SEXO_MACHO = 0;
	const SEXO_HEMBRA = 1;

	// Estados
    const STATUS_REFUGIO = 0;
    const STATUS_SOLICITADO= 1;
    const STATUS_EN_PROCESO = 2;
    const STATUS_ADOPTADO = 3;
    const STATUS_DE_CAMINO = 4;
    const STATUS_EXTRANJERO = 5;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Animal';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre, descripcion, sexo', 'required'),
			array('sexo, estado, id_adoptante, borrado', 'numerical', 'integerOnly'=>true),
			array('peso', 'numerical'),
			array('nombre, tamanyo', 'length', 'max'=>20),
			array('imagen', 'length', 'max'=>500),
			array('descripcion', 'length', 'max'=>1000),
			array('raza', 'length', 'max'=>45),
			array('f_creacion, f_modificacion, f_borrado', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, nombre, imagen, f_nacimiento, descripcion, sexo, tamanyo, peso, raza, estado, id_adoptante, borrado, f_creacion, f_modificacion, f_borrado', 'safe', 'on'=>'search'),
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
			'adoptante' => array(self::BELONGS_TO, 'Adoptante', 'id_adoptante'),
			'voluntarios' => array(self::HAS_MANY, 'AnimalVoluntario', 'id_animal'),
			'viaje' => array(self::HAS_ONE, 'Viaje', 'id_animal'),
		);
	}

	public function scopes() {
        return array(
            'disponibles'=>array(
                'condition'=>'id_adoptante IS NULL and estado=0',
            ), 
            'enViaje'=>array(
            	'condition'=>'estado=1'//cambiar a estado de viaje!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            	)

        );
    }

	// CAMBIA COMO SE MUESTRA EL NOMBRE DE LOS ATRIBUTOS
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nombre' => 'Nombre',
			'imagen' => 'Imagen',
			'f_nacimiento' => 'Fecha de nacimiento',
			'descripcion' => 'Descripcion',
			'sexo' => 'Sexo',
			'tamanyo' => 'Tamaño',
			'peso' => 'Peso',
			'raza' => 'Raza',
			'estado' => 'Estado',
			'id_adoptante' => 'Id Adoptante',
			'borrado' => 'Borrado',
			'f_creacion' => 'F Creacion',
			'f_modificacion' => 'F Modificacion',
			'f_borrado' => 'F Borrado',
		);
	}

	public function beforeSave() {
        if (!empty($this->f_nacimiento)) {
        	$this->setAttribute(
        		'f_nacimiento', 
        		date('Y-m-d H:i:s',  strtotime(str_replace('/', '-', $this->f_nacimiento)))
    		);
        }
        return parent::beforeSave();
    }

	public function statusName() {
    	switch ($this->estado) {
    		case Animal::STATUS_REFUGIO:
    			return 'En el refugio';
    			break;
    		case Animal::STATUS_SOLICITADO:
    			return 'Solicitado';
    			break;
    		case Animal::STATUS_EN_PROCESO:
    			return 'En proceso';
    			break;
    		case Animal::STATUS_ADOPTADO:
    			return 'Adoptado';
    			break;
    		case Animal::STATUS_DE_CAMINO:
    			return 'Esperando viaje';
    			break;
    		case Animal::STATUS_EXTRANJERO:
    			return 'En el extranjero';
    			break;    		
    		default:
    			return 'Desconocido';
    			break;
    	}
    }

    public function sexoName() {
    	switch ($this->sexo) {
    		case Animal::SEXO_MACHO:
    			return 'Macho';
    			break;
    		case Animal::SEXO_HEMBRA:
    			return 'Hembra';
    			break;
    		default:
    			return 'Desconocido';
    			break;
    	}
    }

    public function imagenSrc($type="cropped", $size="340") {
		if ($this->imagen) {
			$userFolder = Yii::app()->getBaseUrl(true).self::IMG_DIR.Common::WEB_SEPARATOR.$this->id.Common::WEB_SEPARATOR;
			$folder = $userFolder.$type.Common::WEB_SEPARATOR.$size.Common::WEB_SEPARATOR;
			$img = $folder.$this->imagen;
		} else {
			$img = Yii::app()->getBaseUrl(true).self::IMG_DIR.Common::WEB_SEPARATOR."default.png";
		}
		return $img;
	}

    public static function getSexoName($sexo) {
    	switch ($sexo) {
    		case Animal::SEXO_MACHO:
    			return 'Macho';
    			break;
    		case Animal::SEXO_HEMBRA:
    			return 'Hembra';
    			break;
    		default:
    			return 'Desconocido';
    			break;
    	}
    }

    public static function getEstadoName($estado){
    	switch ($estado) {
    		case Animal::STATUS_REFUGIO:
    			return 'En el refugio';
    			break;
    		case Animal::STATUS_SOLICITADO:
    			return 'Solicitado';
    			break;
    		case Animal::STATUS_EN_PROCESO:
    			return 'En proceso';
    			break;
    		case Animal::STATUS_ADOPTADO:
    			return 'Adoptado';
    			break;
    		case Animal::STATUS_DE_CAMINO:
    			return 'Esperando viaje';
    			break;
    		case Animal::STATUS_EXTRANJERO:
    			return 'En el extranjero';
    			break;
    		default:
    			return 'Desconocido';
    			break;
    	}

    }

    public function getEstadoDropDown(){
    	$estados = array(
    		Animal::STATUS_REFUGIO=>'En el refugio', 
    		Animal::STATUS_SOLICITADO=>'Solicitado',
    		Animal::STATUS_EN_PROCESO=>'En proceso',
    		Animal::STATUS_ADOPTADO=>'Adoptado',
    		Animal::STATUS_DE_CAMINO=>'Esperando viaje',
    		Animal::STATUS_EXTRANJERO=>'En el extranjero',
    	);

    	return Chtml::dropDownList('estado', $this->estado, $estados, array('class'=>'estado-animal form-control'));
    }

    public function canTravel() {
    	return ($this->estado == self::STATUS_REFUGIO);
    }

	public function afterFind()
    {
    	// Convertir fechas MYSQL a timestamp PHP
    	if ($this->f_nacimiento) {
	        $this->f_nacimiento = strtotime($this->f_nacimiento);
        }

        if ($this->f_creacion) {
	        $this->f_creacion = strtotime($this->f_creacion);
        }

        if ($this->f_modificacion) {
	        $this->f_modificacion = strtotime($this->f_modificacion);
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

		$criteria->compare('id',$this->id);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('imagen',$this->imagen,true);
		$criteria->compare('f_nacimiento',$this->f_nacimiento,true);
		$criteria->compare('descripcion',$this->descripcion,true);
		$criteria->compare('sexo',$this->sexo);
		$criteria->compare('tamanyo',$this->tamanyo,true);
		$criteria->compare('peso',$this->peso);
		$criteria->compare('raza',$this->raza,true);
		$criteria->compare('estado',$this->estado);
		$criteria->compare('id_adoptante',$this->id_adoptante);
		$criteria->compare('borrado',$this->borrado);
		$criteria->compare('f_creacion',$this->f_creacion,true);
		$criteria->compare('f_modificacion',$this->f_modificacion,true);
		$criteria->compare('f_borrado',$this->f_borrado,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Animal the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

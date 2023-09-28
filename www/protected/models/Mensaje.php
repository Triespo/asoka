<?php

/**
 * This is the model class for table "Mensaje".
 *
 * The followings are the available columns in table 'Mensaje':
 * @property integer $id
 * @property integer $id_usuario
 * @property integer $id_destinatario
 * @property string $titulo
 * @property string $contenido
 * @property integer $estado
 * @property string $f_envio
 * @property string $f_recepcion
 *
 * The followings are the available model relations:
 * @property Usuario $idUsuario
 * @property Usuario $idDestinatario
 */
class Mensaje extends CActiveRecord
{
	const NO_BORRADO = 0;
	const BORRADO = 1;

	const STATUS_ENVIADO = 0;
	const STATUS_LEIDO = 1;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Mensaje';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_usuario, id_destinatario, titulo, contenido', 'required'),
			array('id_usuario, id_destinatario, estado', 'numerical', 'integerOnly'=>true),
			array('titulo', 'length', 'max'=>100),
			array('f_envio, f_recepcion', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_usuario, id_destinatario, titulo, contenido, estado, f_envio, f_recepcion', 'safe', 'on'=>'search'),
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
			'usuario' => array(self::BELONGS_TO, 'Usuario', 'id_usuario'),
			'destinatario' => array(self::BELONGS_TO, 'Usuario', 'id_destinatario'),
		);
	}

	public function scopes() {
        return array(
            'noBorrados'=>array(
                'condition'=>'borrado='.self::NO_BORRADO,
            ),
            'borrados'=>array(
                'condition'=>'borrado='.self::NO_BORRADO,
            ),
        );
    }

    public function beforeSave(){
        if($this->isNewRecord){
            $this->f_envio = new CDbExpression('NOW()');
        }
        return parent::beforeSave();
    }

    public function isRead() {
    	return ($this->estado == self::STATUS_LEIDO);
    }

    public function getSenderName() {
    	switch ($this->destinatario->tipo) {
    		case Usuario::TYPE_VOLUNTARIO:
    			if ($this->usuario->isAdmin()) {
    				return "Administrador";
    			} else {
    				return $this->usuario->getFullName()  . ' [' . Usuario::getTypeName($this->usuario->tipo) . ']';
    			}
    			break;
    		case Usuario::TYPE_ADOPTANTE:
    			if ($this->usuario->isAdoptante()) {
    				return $this->usuario->getFullName()  . ' [' . Usuario::getTypeName($this->usuario->tipo) . ']';
    			} else {
    				return "Equipo de Asoka";
    			}
    			break;
    		default:
    			return $this->usuario->getFullName() . ' [' . Usuario::getTypeName($this->usuario->tipo) . ']';
    			break;
    	}
    }

    public function getReceiverName() {
    	return $this->destinatario->getFullName() . ' [' . Usuario::getTypeName($this->destinatario->tipo) . ']';
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_usuario' => 'Id Usuario',
			'id_destinatario' => 'Id Destinatario',
			'titulo' => 'Titulo',
			'contenido' => 'Contenido',
			'estado' => 'Estado',
			'f_envio' => 'F Envio',
			'f_recepcion' => 'F Recepcion',
		);
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
		$criteria->compare('id_usuario',$this->id_usuario);
		$criteria->compare('id_destinatario',$this->id_destinatario);
		$criteria->compare('titulo',$this->titulo,true);
		$criteria->compare('contenido',$this->contenido,true);
		$criteria->compare('estado',$this->estado);
		$criteria->compare('f_envio',$this->f_envio,true);
		$criteria->compare('f_recepcion',$this->f_recepcion,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Mensaje the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

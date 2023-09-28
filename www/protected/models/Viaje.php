<?php

/**
 * This is the model class for table "Viaje".
 *
 * The followings are the available columns in table 'Viaje':
 * @property integer $id
 * @property integer $id_animal
 * @property string $destino
 * @property integer $estado
 * @property string $f_salida
 * @property string $f_llegada
 *
 * The followings are the available model relations:
 * @property Animal $idAnimal
 */
class Viaje extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Viaje';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_animal', 'required'),
			array('id, id_animal, estado', 'numerical', 'integerOnly'=>true),
			array('destino', 'length', 'max'=>100),
			array('f_salida, f_llegada', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_animal, destino, estado, f_salida, f_llegada', 'safe', 'on'=>'search'),
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
			'animal' => array(self::BELONGS_TO, 'Animal', 'id_animal'),
		);
	}

	public function beforeSave(){
        if (!empty($this->f_salida)) {
        	$this->setAttribute(
        		'f_salida', 
        		date('Y-m-d',  strtotime(str_replace('/', '-', $this->f_salida)))
    		);
        }
        return parent::beforeSave();
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_animal' => 'Id Animal',
			'destino' => 'Destino',
			'estado' => 'Estado',
			'f_salida' => 'F Salida',
			'f_llegada' => 'F Llegada',
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
		$criteria->compare('id_animal',$this->id_animal);
		$criteria->compare('destino',$this->destino,true);
		$criteria->compare('estado',$this->estado);
		$criteria->compare('f_salida',$this->f_salida,true);
		$criteria->compare('f_llegada',$this->f_llegada,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Viaje the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

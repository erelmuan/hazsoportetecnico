<?php

namespace app\models;

use Yii;
use app\components\behaviors\AuditoriaBehaviors;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "marca".
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $pais
 * @property string|null $sitioweb
 * @property Modelo[] $modelos
 * @property Equipo[] $equipos
 */
class Marca extends \yii\db\ActiveRecord
{


    public function behaviors()
    {

      return array(
             'AuditoriaBehaviors'=>array(
                    'class'=>AuditoriaBehaviors::className(),
                    ),
        );
   }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'marca';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pais', 'sitioweb'], 'default', 'value' => null],
            [['nombre'], 'required'],
            [['nombre'], 'validarUnicoCaseInsensitive'],
            [['nombre', 'pais', 'sitioweb'], 'string'],
        ];
    }

    public function validarUnicoCaseInsensitive($attribute)
    {
        $valor = mb_strtolower(trim((string)$this->$attribute), 'UTF-8');

        $query = self::find()
            ->where(new \yii\db\Expression('LOWER(nombre) = :n'), [':n' => $valor]);

        // Cuando es un update, excluir el propio ID
        if (!$this->isNewRecord) {
            $query->andWhere(['<>', 'id', $this->id]);
        }

        if ($query->exists()) {
            $this->addError($attribute, 'Ya existe un registro con ese nombre (ignorando mayúsculas/minúsculas).');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
          'id' => Yii::t('app', 'ID'),
         'nombre' => Yii::t('app', 'Nombre'),
         'pais' => Yii::t('app', 'Pais'),
         'sitioweb' => Yii::t('app', 'Sitioweb'),
        ];
    }

    /**
     * Gets query for [[Equipos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEquipos()
    {
        return $this->hasMany(Equipo::class, ['id_marca' => 'id']);
    }
    public function getnombreurl(){
      return Html::a( $this->nombre ,['marca/view',"id"=> $this->id]
        ,[    'class' => 'text-success','role'=>'modal-remote','title'=>'Datos del paciente','data-toggle'=>'tooltip']
       );
    }


	   /**
	    * Gets query for [[Modelos]].
	    *
	    * @return \yii\db\ActiveQuery
	    */
	   public function getModelos()
	   {
	       return $this->hasMany(Modelo::class, ['id_marca' => 'id']);
	   }


    public static function arrayMarcas(): array {
      $marcas = self::find()
            ->select(['id', 'nombre'])
            ->orderBy(['nombre' => SORT_ASC])
            ->asArray()
            ->all();

        return ArrayHelper::map($marcas, 'id', 'nombre');
    }
      public function beforeSave($insert){
      //DE FORMA INDIVIDUAL
        $this->nombre = strtoupper($this->nombre);
        return parent::beforeSave($insert);
      }

}

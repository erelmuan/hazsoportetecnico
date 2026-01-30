<?php

namespace app\models;

use Yii;
use app\components\behaviors\AuditoriaBehaviors;

/**
 * This is the model class for table "adjunto".
 *
 * @property int $id
 * @property string $nombreoriginal Nombre original con el cual se sube el archivo
 * @property string $nombreasignado Nombre asignado por sistema para identificarlo univocamente
 * @property string|null $observacion
 * @property int|null $id_equipo
 * @property string $tipocategoria
 * @property string $tipoarchivo
 * @property string|null $fechahora
 *
 * @property AdjuntoEquipo[] $adjuntoEquipos
 * @property Equipo $equipo
 * @property Equipo[] $equipos
 */
class Adjunto extends \yii\db\ActiveRecord
{

  public function behaviors()
  {

    return array(
           'AuditoriaBehaviors'=>array(
                  'class'=>AuditoriaBehaviors::className(),
                  ),
      );
 }
  public $file; // atributo virtual

    /**
     * ENUM field values
     */
    const TIPOCATEGORIA_OPERATIVO = 'operativo';
    const TIPOCATEGORIA_BIBLIOGRAFIA = 'bibliografia';
    const TIPOARCHIVO_VIDEO = 'video';
    const TIPOARCHIVO_FOTO = 'foto';
    const TIPOARCHIVO_DOCUMENTO_PDF = 'documento_pdf';
    const TIPOARCHIVO_DOCUMENTO_TEXTO = 'documento_texto';
    const TIPOARCHIVO_PLANILLA_CALCULO = 'planilla_calculo';
    const TIPOARCHIVO_OTROS = 'otros';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'adjunto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['observacion', 'id_equipo', 'fechahora'], 'default', 'value' => null],
            [['tipocategoria'], 'default', 'value' => 'operativo'],
            [['tipoarchivo'], 'default', 'value' => 'otros'],
            [['nombreoriginal', 'nombreasignado'], 'required'],
            [['nombreoriginal', 'nombreasignado', 'observacion', 'tipocategoria', 'tipoarchivo'], 'string'],
            [['id_equipo'], 'default', 'value' => null],
            [['id_equipo'], 'integer'],
            [['fechahora'], 'safe'],
            ['tipocategoria', 'in', 'range' => array_keys(self::optsTipocategoria())],
            ['tipoarchivo', 'in', 'range' => array_keys(self::optsTipoarchivo())],
            [['id_equipo'], 'exist', 'skipOnError' => true, 'targetClass' => Equipo::class, 'targetAttribute' => ['id_equipo' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nombreoriginal' => Yii::t('app', 'Nombre'),
            'nombreasignado' => Yii::t('app', 'Nombreasignado'),
            'observacion' => Yii::t('app', 'Observacion'),
            'id_equipo' => Yii::t('app', 'Id Equipo'),
            'tipocategoria' => Yii::t('app', 'Tipo de categoria'),
            'tipoarchivo' => Yii::t('app', 'Tipo de archivo'),
            'fechahora' => Yii::t('app', 'Fecha y hora'),
        ];
    }

    /**
     * Gets query for [[AdjuntoEquipos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAdjuntoEquipos()
    {
        return $this->hasMany(AdjuntoEquipo::class, ['id_adjunto' => 'id']);
    }

    /**
     * Gets query for [[Equipo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEquipo()
    {
        return $this->hasOne(Equipo::class, ['id' => 'id_equipo']);
    }

    /**
     * Gets query for [[Equipos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEquipos()
    {
        return $this->hasMany(Equipo::class, ['id' => 'id_equipo'])->viaTable('adjunto_equipo', ['id_adjunto' => 'id']);
    }


    /**
     * column tipocategoria ENUM value labels
     * @return string[]
     */
    public static function optsTipocategoria()
    {
        return [
            self::TIPOCATEGORIA_OPERATIVO => Yii::t('app', 'operativo'),
            self::TIPOCATEGORIA_BIBLIOGRAFIA => Yii::t('app', 'bibliografia'),
        ];
    }

    /**
     * column tipoarchivo ENUM value labels
     * @return string[]
     */
    public static function optsTipoarchivo()
    {
        return [
            self::TIPOARCHIVO_VIDEO => Yii::t('app', 'video'),
            self::TIPOARCHIVO_FOTO => Yii::t('app', 'foto'),
            self::TIPOARCHIVO_DOCUMENTO_PDF => Yii::t('app', 'documento_pdf'),
            self::TIPOARCHIVO_DOCUMENTO_TEXTO => Yii::t('app', 'documento_texto'),
            self::TIPOARCHIVO_PLANILLA_CALCULO => Yii::t('app', 'planilla_calculo'),
            self::TIPOARCHIVO_OTROS => Yii::t('app', 'otros'),
        ];
    }

    /**
     * @return string
     */
    public function displayTipocategoria()
    {
        return self::optsTipocategoria()[$this->tipocategoria];
    }

    /**
     * @return bool
     */
    public function isTipocategoriaOperativo()
    {
        return $this->tipocategoria === self::TIPOCATEGORIA_OPERATIVO;
    }

    public function setTipocategoriaToOperativo()
    {
        $this->tipocategoria = self::TIPOCATEGORIA_OPERATIVO;
    }

    /**
     * @return bool
     */
    public function isTipocategoriaBibliografia()
    {
        return $this->tipocategoria === self::TIPOCATEGORIA_BIBLIOGRAFIA;
    }

    public function setTipocategoriaToBibliografia()
    {
        $this->tipocategoria = self::TIPOCATEGORIA_BIBLIOGRAFIA;
    }

    /**
     * @return string
     */
    public function displayTipoarchivo()
    {
        return self::optsTipoarchivo()[$this->tipoarchivo];
    }

    /**
     * @return bool
     */
    public function isTipoarchivoVideo()
    {
        return $this->tipoarchivo === self::TIPOARCHIVO_VIDEO;
    }

    public function setTipoarchivoToVideo()
    {
        $this->tipoarchivo = self::TIPOARCHIVO_VIDEO;
    }

    /**
     * @return bool
     */
    public function isTipoarchivoFoto()
    {
        return $this->tipoarchivo === self::TIPOARCHIVO_FOTO;
    }

    public function setTipoarchivoToFoto()
    {
        $this->tipoarchivo = self::TIPOARCHIVO_FOTO;
    }

    /**
     * @return bool
     */
    public function isTipoarchivoDocumentopdf()
    {
        return $this->tipoarchivo === self::TIPOARCHIVO_DOCUMENTO_PDF;
    }

    public function setTipoarchivoToDocumentopdf()
    {
        $this->tipoarchivo = self::TIPOARCHIVO_DOCUMENTO_PDF;
    }

    /**
     * @return bool
     */
    public function isTipoarchivoDocumentotexto()
    {
        return $this->tipoarchivo === self::TIPOARCHIVO_DOCUMENTO_TEXTO;
    }

    public function setTipoarchivoToDocumentotexto()
    {
        $this->tipoarchivo = self::TIPOARCHIVO_DOCUMENTO_TEXTO;
    }

    /**
     * @return bool
     */
    public function isTipoarchivoPlanillacalculo()
    {
        return $this->tipoarchivo === self::TIPOARCHIVO_PLANILLA_CALCULO;
    }

    public function setTipoarchivoToPlanillacalculo()
    {
        $this->tipoarchivo = self::TIPOARCHIVO_PLANILLA_CALCULO;
    }

    /**
     * @return bool
     */
    public function isTipoarchivoOtros()
    {
        return $this->tipoarchivo === self::TIPOARCHIVO_OTROS;
    }

    public function setTipoarchivoToOtros()
    {
        $this->tipoarchivo = self::TIPOARCHIVO_OTROS;
    }
}

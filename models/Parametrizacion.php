<?php

namespace app\models;
use app\components\behaviors\AuditoriaBehaviors;
use Yii;

/**
 * This is the model class for table "parametrizacion".
 *
 * @property int $id
 * @property int $tiempo
 * @property string $modelo
 * @property string|null $descripcion
 * @property string $color
 * @property string $tipotiempo
 * @property string $condicion
 */
class Parametrizacion extends \yii\db\ActiveRecord
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
 * Obtiene el color según la fecha y modelo, respetando tipotiempo (dias / años)
 * @param string $fecha Fecha a evaluar
 * @param string $modelo 'equipo' o 'log'
 * @return string|null Color a aplicar o null si no cumple condiciones
 */
public static function obtenerColor($fecha, $modelo = self::MODELO_EQUIPO)
{
    if (empty($fecha)) {
        return null;
    }

    // crear objetos DateTime (usar la zona por defecto del servidor)
    try {
        $fechaObj = new \DateTime($fecha);
        $hoy = new \DateTime('now');
    } catch (\Exception $e) {
        return null; // fecha inválida
    }

    // calculamos diferencias absolutas y luego aplicamos signo si la fecha es futura
    $interval = $hoy->diff($fechaObj);

    // días completos (abs)
    $diferenciaDiasAbs = (int)$interval->days;

    // años completos (abs) — diff->y devuelve años completos
    $diferenciaAnosAbs = (int)$interval->y;

    // aplicar signo: si la fecha es futura (fechaObj > hoy) hacemos negativo
    if ($fechaObj > $hoy) {
        $diferenciaDias = -$diferenciaDiasAbs;
        $diferenciaAnos = -$diferenciaAnosAbs;
    } else {
        $diferenciaDias = $diferenciaDiasAbs;
        $diferenciaAnos = $diferenciaAnosAbs;
    }

    // Obtener todas las parametrizaciones activas para el modelo
    $parametrizaciones = self::find()
        ->where(['modelo' => $modelo])
        // ->andWhere(['activo' => 1]) // descomentar si tenés campo activo
        ->orderBy(['tiempo' => SORT_DESC]) // ojo: mezcla unidades, ver nota más abajo
        ->all();

    foreach ($parametrizaciones as $param) {
        // si tipotiempo indica años, comparamos con $diferenciaAnos, sino con dias
        if ($param->isTipotiempoAnos()) {
            $valorComparar = $diferenciaAnos;
        } else {
            // por defecto tratamos como dias
            $valorComparar = $diferenciaDias;
        }

        if (self::evaluarCondicion($valorComparar, (int)$param->tiempo, $param->condicion)) {
            return $param->color;
        }
    }

    return null;
}


  /**
  * Obtiene las opciones de modelo para dropdowns
      * @return array
      */
     public static function getModeloOptions()
     {
         return [
             self::MODELO_EQUIPO => 'Equipo',
             self::MODELO_LOG => 'Log',
         ];
     }


  /**
   * Evalúa si se cumple la condición
   * @param int $diferenciaDias Días transcurridos desde la fecha
   * @param int $tiempo Tiempo parametrizado
   * @param string $condicion Tipo de condición
   * @return bool
   */
  private static function evaluarCondicion($diferenciaDias, $tiempo, $condicion)
  {
      switch ($condicion) {
          case self::CONDICION_MAYOR:
              return $diferenciaDias > $tiempo;
          case self::CONDICION_IGUAL:
              return $diferenciaDias == $tiempo;
          default:
              return false;
      }
  }

    /**
     * ENUM field values
     */
    const MODELO_LOG = 'log';
    const MODELO_EQUIPO = 'equipo';
    const TIPOTIEMPO_DIAS = 'dia/s';
    const TIPOTIEMPO_ANOS = 'año/s';
    const CONDICION_IGUAL = 'igual';
    const CONDICION_MAYOR = 'mayor';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'parametrizacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion'], 'default', 'value' => null],
            [['tiempo', 'modelo', 'color', 'tipotiempo', 'condicion'], 'required'],
            [['tiempo'], 'default', 'value' => null],
            [['tiempo'], 'integer'],
            [['modelo', 'descripcion', 'color', 'tipotiempo', 'condicion'], 'string'],
            ['modelo', 'in', 'range' => array_keys(self::optsModelo())],
            ['tipotiempo', 'in', 'range' => array_keys(self::optsTipotiempo())],
            ['condicion', 'in', 'range' => array_keys(self::optsCondicion())],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'tiempo' => Yii::t('app', 'Tiempo'),
            'modelo' => Yii::t('app', 'Modelo'),
            'descripcion' => Yii::t('app', 'Descripcion'),
            'color' => Yii::t('app', 'Color'),
            'tipotiempo' => Yii::t('app', 'Tipotiempo'),
            'condicion' => Yii::t('app', 'Condicion'),
        ];
    }


    /**
     * column modelo ENUM value labels
     * @return string[]
     */
    public static function optsModelo()
    {
        return [
            self::MODELO_LOG => Yii::t('app', 'log'),
            self::MODELO_EQUIPO => Yii::t('app', 'equipo'),
        ];
    }

    /**
     * column tipotiempo ENUM value labels
     * @return string[]
     */
    public static function optsTipotiempo()
    {
        return [
            self::TIPOTIEMPO_DIAS => Yii::t('app', 'dia/s'),
            self::TIPOTIEMPO_ANOS => Yii::t('app', 'año/s'),
        ];
    }

    /**
     * column condicion ENUM value labels
     * @return string[]
     */
    public static function optsCondicion()
    {
        return [
            self::CONDICION_IGUAL => Yii::t('app', 'igual'),
            self::CONDICION_MAYOR => Yii::t('app', 'mayor'),
        ];
    }

    /**
     * @return string
     */
    public function displayModelo()
    {
        return self::optsModelo()[$this->modelo];
    }

    /**
     * @return bool
     */
    public function isModeloLog()
    {
        return $this->modelo === self::MODELO_LOG;
    }

    public function setModeloToLog()
    {
        $this->modelo = self::MODELO_LOG;
    }

    /**
     * @return bool
     */
    public function isModeloEquipo()
    {
        return $this->modelo === self::MODELO_EQUIPO;
    }

    public function setModeloToEquipo()
    {
        $this->modelo = self::MODELO_EQUIPO;
    }

    /**
     * @return string
     */
    public function displayTipotiempo()
    {
        return self::optsTipotiempo()[$this->tipotiempo];
    }

    /**
     * @return bool
     */
    public function isTipotiempoDias()
    {
        return $this->tipotiempo === self::TIPOTIEMPO_DIAS;
    }

    public function setTipotiempoToDias()
    {
        $this->tipotiempo = self::TIPOTIEMPO_DIAS;
    }

    /**
     * @return bool
     */
    public function isTipotiempoAnos()
    {
        return $this->tipotiempo === self::TIPOTIEMPO_ANOS;
    }

    public function setTipotiempoToAnos()
    {
        $this->tipotiempo = self::TIPOTIEMPO_ANOS;
    }

    /**
     * @return string
     */
    public function displayCondicion()
    {
        return self::optsCondicion()[$this->condicion];
    }

    /**
     * @return bool
     */
    public function isCondicionIgual()
    {
        return $this->condicion === self::CONDICION_IGUAL;
    }

    public function setCondicionToIgual()
    {
        $this->condicion = self::CONDICION_IGUAL;
    }

    /**
     * @return bool
     */
    public function isCondicionMayor()
    {
        return $this->condicion === self::CONDICION_MAYOR;
    }

    public function setCondicionToMayor()
    {
        $this->condicion = self::CONDICION_MAYOR;
    }
}

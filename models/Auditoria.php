<?php
namespace app\models;
use Yii;
/**
 * This is the model class for table "auditoria".
 *
 * @property int $id
 * @property int $id_usuario
 * @property string $tabla
 * @property string $fecha
 * @property string $hora
 * @property string $ip
 * @property string $informacion_usuario
 * @property string $cambios
 * @property string $accion
 * @property int $registro
 *
 * @property Usuario $usuario
 */
class Auditoria extends \yii\db\ActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'auditoria';
    }
    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [[['id_usuario', 'registro'], 'default', 'value' => null],
        [['id_usuario','registro'], 'integer'], [['fecha'], 'safe'],
        [['hora', 'informacion_usuario', 'cambios', 'accion'], 'string'],
        [['tabla'], 'string', 'max' => 50], [['ip'], 'string', 'max' => 15],
        [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className() , 'targetAttribute' => ['id_usuario' => 'id']], ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return ['id' => 'ID',
                'id_usuario' => 'Id Usuario',
                'tabla' => 'Tabla',
                'fecha' => 'Fecha',
                'hora' => 'Hora',
                'ip' => 'Ip',
                'informacion_usuario' => 'Informacion Usuario',
                'cambios' => 'Cambios',
                'accion' => 'Accion',
                'registro' => 'Registro', ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario(){
        return $this->hasOne(Usuario::className() , ['id' => 'id_usuario']);
    }


    public static function ultimosCreados()
    {
        // Obtenemos los últimos 3 registros desde la base de datos como array
        $ultimos = self::find()
            ->orderBy(['id' => SORT_DESC]) // últimos primero
            ->limit(3)
            ->andWhere(['accion'=>'CREACIÓN'])
            ->asArray() // <---- convierte los modelos en arrays asociativos
            ->all();

        // Transformamos los registros a tu formato personalizado
        $resultado = [];
        foreach ($ultimos as $registro) {

            $usuario = Usuario::findOne(['id'=>$registro['id_usuario'] ]);
            $resultado[] = [
                'registro' => $registro['registro'] ?? 'Desconocido',
                'tabla' => $registro['tabla'] ?? 'Desconocido',
                'usuario' => $usuario->nombreusuario?? 'Desconocido',
                'titulo' => $registro['tabla'] ?? ($registro['tabla'] ?? 'Sin título'),
                'ip' => $registro['ip'] ?? '',
                'fecha' => $registro['fecha'].' '.$registro['hora'] ?? '',
            ];
        }

        return $resultado;
    }

    public static function ultimosModificados(){
      // Obtenemos los últimos 3 registros desde la base de datos como array
      $ultimos = self::find()
          ->orderBy(['id' => SORT_DESC]) // últimos primero
          ->limit(3)
          ->andWhere(['accion'=>'MODIFICACIÓN'])
          ->asArray() // <---- convierte los modelos en arrays asociativos
          ->all();

      // Transformamos los registros a tu formato personalizado
      $resultado = [];
      foreach ($ultimos as $registro) {

          $usuario = Usuario::findOne(['id'=>$registro['id_usuario'] ]);
          $resultado[] = [
              'registro' => $registro['registro'] ?? 'Desconocido',
              'tabla' => $registro['tabla'] ?? 'Desconocido',
              'usuario' => $usuario->nombreusuario?? 'Desconocido',
              'titulo' => $registro['tabla'] ?? ($registro['tabla'] ?? 'Sin título'),
              'ip' => $registro['ip'] ?? '',
              'fecha' => $registro['fecha'].' '.$registro['hora'] ?? '',
          ];
      }

      return $resultado;
    }

  }

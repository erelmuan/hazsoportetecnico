<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use app\components\behaviors\AuditoriaBehaviors;

/**
 * This is the model class for table "usuario".
 *
 * @property int $id
 * @property string $nombreusuario
 * @property string $contrasenia
 * @property string $nombreapellido
 * @property string|null $descripcion
 * @property bool $activo
 * @property string|null $imagen
 * @property string|null $access_token
 * @property string|null $token_expire_at
 * @property string|null $auth_key
 * @property bool $must_change_password
 * @property string|null $reset_token
 * @property string|null $reset_token_expire
 */
class Usuario extends ActiveRecord implements IdentityInterface
{
    public function behaviors()
    {
        return [
            'AuditoriaBehaviors' => [
                'class' => AuditoriaBehaviors::className(),
            ],
        ];
    }

    public static function tableName()
    {
        return 'usuario';
    }

    public function rules()
    {
        return [
            [['nombreusuario','contrasenia','nombreapellido','activo'], 'required'],
            [['descripcion'], 'string'],
            [['activo','must_change_password'], 'boolean'],
            [['nombreusuario','contrasenia','nombreapellido','imagen','access_token','auth_key','reset_token'], 'string','max'=>255],
            [['token_expire_at','reset_token_expire'], 'safe'],
            [['nombreusuario'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombreusuario' => 'Nombre de usuario',
            'contrasenia' => 'Contraseña',
            'nombreapellido' => 'Nombre y apellido',
            'activo' => 'Activo',
        ];
    }

    // ---------------- IdentityInterface ----------------
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        if (empty($token)) {
            return null;
        }
        $user = static::findOne(['access_token' => $token]);
        if ($user && $user->token_expire_at && strtotime($user->token_expire_at) < time()) {
            return null; // token expirado
        }
        return $user;
    }

    public function getId()
    {
        return $this->id;
    }

    // Auth key para "remember me"
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    // ---------------- helpers ----------------
    public static function findByUsername($username)
    {
        return static::findOne(['nombreusuario' => $username, 'activo' => true]);
    }

    public function validatePassword($password)
    {
        // Si no hay hash, no validamos (evita exception)
        if (empty($this->contrasenia)) {
            return false;
        }

        try {
            return Yii::$app->security->validatePassword($password, $this->contrasenia);
        } catch (\yii\base\InvalidArgumentException $e) {
            // El hash guardado es inválido (p. ej. texto plano, truncado, corrupto)
            Yii::error("Hash inválido para usuario id={$this->id} (nombreusuario={$this->nombreusuario}): ".$e->getMessage(), __METHOD__);
            return false;
        }
    }


    public function setPassword($password)
    {
        $this->contrasenia = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAccessToken()
    {
        $this->access_token = Yii::$app->security->generateRandomString(64);
        $this->token_expire_at = date('c', time() + 60*60*24); // 24h
        return $this->access_token;
    }

    public function removeAccessToken()
    {
        $this->access_token = null;
        $this->token_expire_at = null;
    }

    public function clearResetToken()
    {
        $this->reset_token = null;
        $this->reset_token_expire = null;
        $this->must_change_password = false;
    }

    // genera y setea auth_key
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    // Antes de guardar: si es nuevo, generar auth_key si hace falta
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $this->nombreusuario = strtoupper($this->nombreusuario);

        if ($insert) {
            if (empty($this->auth_key)) {
                $this->generateAuthKey();
            }
            // si la contrasenia llega en claro (p.e. desde formulario admin),
            // asegurate de llamara setPassword() en el controlador antes de guardar.
        }
        if (!empty($this->contrasenia)) {
          $this->setPassword($this->contrasenia);
          $this->must_change_password = true; // opcional
      }
        return true;
    }

}

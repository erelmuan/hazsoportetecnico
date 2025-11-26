<?php
namespace app\controllers\api;
use Yii;
use yii\rest\Controller;
use yii\web\Response;
use app\models\Usuario;

class AuthController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        // CORS para permitir preflight desde Angular
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
        ];
        return $behaviors;
    }

    // POST /auth/login
    public function actionLogin()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $body = Yii::$app->getRequest()->getBodyParams();
        $username = $body['username'] ?? null;
        $password = $body['password'] ?? null;

        if (!$username || !$password) {
            return ['error' => 'username and password required'];
        }

        $user = Usuario::findByUsername($username);
        if (!$user) {
            return ['error' => 'invalid credentials'];
        }

        // Si el hash está bien y password coincide
        if ($user->validatePassword($password)) {
            // Si está obligado a cambiar contraseña, devolvemos indicación
            if ($user->must_change_password) {
                return [
                    'must_change_password' => true,
                    'message' => 'Debe cambiar la contraseña. Use el endpoint /auth/reset-password con el token o solicite reset.',
                ];
            }
            // generar access_token (si usas tokens de sesión)
            $token = $user->generateAccessToken();
            $user->save(false);
            return [
                'access_token' => $token,
                'user' => [
                    'id' => $user->id,
                    'nombreusuario' => $user->nombreusuario,
                ],
            ];
        }

        return ['error' => 'invalid credentials'];
    }

    // POST /auth/request-reset  { "username": "user" }  -> genera token (si no existe) y lo devuelve (idealmente enviarlo por email)
    public function actionRequestReset()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $body = Yii::$app->getRequest()->getBodyParams();
        $username = $body['username'] ?? null;
        if (!$username) return ['error' => 'username required'];

        $user = Usuario::findByUsername($username);
        if (!$user) return ['error' => 'user not found'];

        $token = Yii::$app->security->generateRandomString(48);
        $user->reset_token = $token;
        $user->reset_token_expire = date('c', time() + 60*60*24); // 24h
        $user->must_change_password = true;
        $user->save(false);

        // Aquí deberías enviar el token por email. Para desarrollo lo devolvemos:
        return ['message' => 'reset token generated', 'reset_token' => $token];
    }

    // POST /auth/reset-password  { "token": "...", "password": "nuevo" }
    public function actionResetPassword()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $body = Yii::$app->getRequest()->getBodyParams();
        $token = $body['token'] ?? null;
        $newPassword = $body['password'] ?? null;

        if (!$token || !$newPassword) return ['error' => 'token and password required'];

        $user = Usuario::findOne(['reset_token' => $token]);
        if (!$user) return ['error' => 'invalid token'];

        if ($user->reset_token_expire && strtotime($user->reset_token_expire) < time()) {
            return ['error' => 'token expired'];
        }

        // setear nueva contraseña hasheada
        $user->setPassword($newPassword);
        $user->clearResetToken();
        $user->save(false);

        return ['message' => 'password reset successful'];
    }
}

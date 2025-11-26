<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Usuario;
use yii\helpers\Console;

class UserController extends Controller
{
    // Genera token para todos los usuarios (o solo los que indiqués) y lo imprime
    // php yii user/bulk-generate-reset --only-active=1
    public function options($actionID)
    {
        return ['only-active'];
    }

    public function actionBulkGenerateReset($onlyActive = 0)
    {
        $query = Usuario::find();
        if ((int)$onlyActive === 1) {
            $query->andWhere(['activo' => true]);
        }
        $users = $query->all();
        foreach ($users as $user) {
            $token = Yii::$app->security->generateRandomString(48);
            $user->reset_token = $token;
            $user->reset_token_expire = date('c', time() + 60*60*24); // 24h
            $user->must_change_password = true;
            $user->save(false);
            $this->stdout("User {$user->id} ({$user->nombreusuario}) => token: {$token}\n", Console::FG_GREEN);
        }
        $this->stdout("Tokens generados para " . count($users) . " usuarios.\n", Console::FG_YELLOW);
    }

    // Setea password (hasheado) para un usuario (administrativo)
    // php yii user/set-password 2 nuevaPass123
    public function actionSetPassword($id, $password)
    {
        $user = Usuario::findOne((int)$id);
        if (!$user) {
            $this->stderr("Usuario no encontrado: $id\n", Console::FG_RED);
            return 1;
        }
        $user->setPassword($password); // usa el método del model para hashear
        $user->must_change_password = false;
        $user->reset_token = null;
        $user->reset_token_expire = null;
        $user->save(false);
        $this->stdout("Password actualizado para usuario {$id}\n", Console::FG_GREEN);
        return 0;
    }
}

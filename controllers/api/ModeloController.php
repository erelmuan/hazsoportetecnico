<?php
namespace app\controllers\api;
use yii\rest\ActiveController;
use yii\filters\Cors;
use yii\filters\auth\HttpBearerAuth;
use Yii;

class ModeloController extends ActiveController
{
     public $modelClass = 'app\models\Modelo';
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // 1) CORS
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['http://localhost:4200'], // ajustá a tu frontend
                'Access-Control-Request-Method' => ['GET','POST','PUT','PATCH','DELETE','OPTIONS'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Allow-Headers' => ['Authorization','Content-Type','X-Requested-With'],
                'Access-Control-Max-Age' => 3600,
            ],
        ];

        // 2) Autenticación Bearer
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            // exceptuar acciones públicas
            'except' => ['options','index','view'],
        ];

        // Opcional: si usás verbs
        // $behaviors['verbs'] = [ ... ];

        return $behaviors;
    }
 
    public function actionIndex()
    {
        return $this->render('index');
    }

}

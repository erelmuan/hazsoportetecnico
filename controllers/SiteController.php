<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Auditoria;
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
      return [
              'access' => [
                  'class' => AccessControl::class,
                  'except' => ['login', 'error'], // Estas son públicas
                  'rules' => [
                      [
                          'allow' => true,
                          'roles' => ['@'], // Todo lo demás requiere login
                      ],
                  ],
              ],
          ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        $ultimosCreados = Auditoria::ultimosCreados();
        $ultimosModificados = Auditoria::ultimosModificados();
        return $this->render('index' ,['ultimosCreados'=> $ultimosCreados,'ultimosModificados'=>$ultimosModificados]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
     public function actionLogin()
   {
     $this->layout = 'main-login'; // <<--- usa el layout especial

       if (!Yii::$app->user->isGuest) {
           return $this->goHome();
       }

       $model = new \app\models\LoginForm();

       if ($model->load(Yii::$app->request->post()) && $model->login()) {
           return $this->goBack();
       }

       // limpiar contraseña antes de renderizar
       $model->password = '';
       return $this->render('login', [
           'model' => $model,
       ]);
   }
    public function actionTablasExtras(){
      return $this->render("tablas-extras");
    }

    public function actionAdministracion(){
      return $this->render("administracion");
    }
    public function actionControlacceso(){
      return $this->render("controlacceso");
    }
    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}

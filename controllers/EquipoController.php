<?php

namespace app\controllers;

use Yii;
use app\models\Equipo;
use app\models\Marca;
use app\models\Modelo;
use app\models\Servicio;
use app\models\Tipoequipo;
use app\models\EquipoSearch;
use app\models\patronState\EstadoBase;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use app\components\Metodos\Metodos;
use app\models\AdjuntoEquipo;


/**
 * EquipoController implements the CRUD actions for Equipo model.
 */
class EquipoController extends BaseController
{
  // behaviors heredado

    /**
     * Lists all Equipo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EquipoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 10;

        $columnas = Metodos::obtenerColumnas(new Equipo());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'columns' => $columnas,
        ]);
    }


    /**
     * Displays a single Equipo model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Equipo #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

 /** Devolver las relaciones en forma de arrays */
   public function devolverArrayHelper()
  {
      $marcas = ArrayHelper::map(Marca::find()->orderBy('nombre')->all(), 'id', 'nombre');
      $modelos = ArrayHelper::map(Modelo::find()->orderBy('nombre')->all(), 'id', 'nombre');
      $servicios = ArrayHelper::map(Servicio::find()->orderBy('nombre')->all(), 'id', 'nombre');
      $tipoEquipos = ArrayHelper::map(TipoEquipo::find()->orderBy('nombre')->all(), 'id', 'nombre');
      return ['marcas'=>$marcas,'modelos'=>$modelos,'servicios'=>$servicios,'tipoequipos'=>$tipoEquipos];
  }




    /**
     * Creates a new Equipo model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Equipo();
        $arrayHelper= $this->devolverArrayHelper();
        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Crear nuevo Equipo",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                        'arrayHelper'=>$arrayHelper,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])

                ];
            }else if($model->load($request->post()) && $model->save()){
                $model->sincronizarAdjuntos();
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Crear nuevo Equipo",
                    'content'=>'<span class="text-success">Equipo creado con éxito</span>',
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Crear más',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])

                ];
            }else{
                return [
                    'title'=> "Crear nuevo Equipo",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                        'arrayHelper'=>$arrayHelper,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])

                ];
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                $model->sincronizarAdjuntos();
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'arrayHelper'=>$arrayHelper,
                ]);
            }
        }

    }

    /**
     * Updates an existing Equipo model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $arrayHelper= $this->devolverArrayHelper();
        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Actualizar Equipo #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                        'arrayHelper'=>$arrayHelper,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];
            }else if($model->load($request->post()) && $model->save()){
              $model->refresh(); // o $model = $this->findModel($model->id); PARA OBTENER EL VALOR SINCRONIZADO CON LA BASE DE DATOS SEGUN LA IA
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Equipo #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                        'arrayHelper'=>$arrayHelper,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];
            }else{
                 return [
                    'title'=> "Actualizar Equipo #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                        'arrayHelper'=>$arrayHelper,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'arrayHelper'=>$arrayHelper,

                ]);
            }
        }
    }

    //delete heredado


    public function actionSubcat() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $id_marca = $parents[0];
                //obtener todas los modelos por el id de la provincia
                $ArrayModelos = Modelo::findall(['id_marca' => $id_marca]);
                ArrayHelper::multisort($ArrayModelos, ['nombre'], [SORT_ASC]);
                $i = 0;
                $modelos = [];
                foreach ($ArrayModelos as $key => $value) {
                    $modelos[$i] = array(
                        'id' => $value['id'],
                        'name' => $value['nombre']
                    );
                    $i = $i + 1;
                }
                $out = [['id' => '<sub-cat-id-1>', 'name' => '<sub-cat-name1>'], ['id' => '<sub-cat_id_2>', 'name' => '<sub-cat-name2>']];
                return Json::encode(['output' => $modelos]);
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }
    /**
     * Finds the Equipo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Equipo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Equipo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

<?php

namespace app\controllers;

use Yii;
use app\models\Log;
use app\models\LogSearch;
use app\models\Equipo;
use app\models\Proveedor;
use app\models\Contactohistorico;
use app\models\patronState\EstadoBase;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


/**
 * LogController implements the CRUD actions for Log model.
 */
class LogController extends BaseController
{
  // behaviors heredado


    /**
     * Lists all Log models.
     * @return mixed
     */
    public function actionIndex($id_equipo)
    {
        $searchModel = new LogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id_equipo);
        $model_equipo=  Equipo::findOne(['id'=>$id_equipo]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model_equipo'=> $model_equipo,
        ]);
    }

    /**
     * Displays a single Log model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Log #".$id,
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

    /**
     * Creates a new Log model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_equipo)
    {
        $request = Yii::$app->request;
        $model = new Log();
        //por defecto tiene el estado del equipo
        $model->id_equipo=$id_equipo;
        $stateOptions = \app\models\patronState\EstadoFactory::getAvailableTransitions(
            $model->id_estado,
            Yii::$app->user->identity,
            $model
        );
        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Crear nuevo Log",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                        'stateOptions' =>   $stateOptions,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])

                ];
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Crear nuevo Log",
                    'content'=>'<span class="text-success">Log creado con éxito</span>',
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Crear más',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])

                ];
            }else{
                return [
                    'title'=> "Crear nuevo Log",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                        'stateOptions' =>   $stateOptions,

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
                return $this->render('create', [
                    'model' => $model,
                    'stateOptions' =>   $stateOptions,

                ]);
            }
        }

    }


  public function precarga_update($model ,$modelAnterior=null, $contactosPost=null){


    $estadoEnviadoId = EstadoBase::ENVIADO_A; // ID real del estado "ENVIADO A"
    $estadoReparadoId = EstadoBase::REPARADO;
    $estadoIrreparableId = EstadoBase::IRREPARABLE;


    /* ============================
     * DATOS BASE PARA LA VISTA
     * ============================ */
    $proveedores = ArrayHelper::map(
        Proveedor::find()->orderBy('razonsocial')->all(),
        'id',
        'razonsocial'
    );

    $urlContactos = Url::to(['contacto/byproveedor']);

    /* ============================
     * PRE-CARGA DE BLOQUE PROVEEDOR
     * ============================ */
    $mostrarBloque   = false;
    $contactosHtml   = '<em>Seleccione un proveedor</em>';

    if (($model->id_estado == $estadoEnviadoId || $modelAnterior->id_estado == $estadoReparadoId  ||  $modelAnterior->id_estado == $estadoIrreparableId  )) {
        $mostrarBloque = true;

        // Opción 1: Orden lógico correcto
        if (!$model->isNewRecord) {
            // 🔹 PRIMERO: Si es un registro existente, cargar históricos
            $contactos = Contactohistorico::find()
                ->where(['id_log' => $model->id])
                ->asArray()
                ->all();

        } elseif (!empty($contactosPost)) {
            // 🔹 SEGUNDO: Solo si es nuevo o hay datos POST de validación
            $contactos = \app\models\Contacto::find()
                ->where(['id' => $contactosPost])
                ->asArray()
                ->all();

        } else {
            // 🔹 TERCERO: Caso por defecto (nuevo sin datos)
            $contactos = [];
        }
        // Puede no haber contactos
        if ($contactos) {

            $html = '';
            foreach ($contactos as $c) {
                $html .= '
                    <div class="checkbox" style="margin-bottom:6px">
                        <label>
                            <input type="checkbox"  checked name="Log[contactos][]" value='.$c['id'].'>
                            <strong>' . Html::encode($c['nombre']) . '</strong>
                            ' . (!empty($c['cargo']) ? ' — ' . Html::encode($c['cargo']) : '') . '
                            ' . (!empty($c['email']) ? ' <small>(' . Html::encode($c['email']) . ')</small>' : '') . '
                        </label>
                    </div>
                ';
            }
            $contactosHtml = $html;
        } else {
            $contactosHtml = '<em>El proveedor no tiene contactos asociados</em>';
        }
    }


    $stateOptions = \app\models\patronState\EstadoFactory::getAvailableTransitions(
        $model->id_estado,
        Yii::$app->user->identity,
        $model
    );

    return [
       'proveedores'    => $proveedores,
        'urlContactos'   => $urlContactos,
        'estadoEnviadoId'=> $estadoEnviadoId,
        'mostrarBloque'  => $mostrarBloque,
        'contactosHtml'  => $contactosHtml,
        'model'  => $model,
        'stateOptions'  => $stateOptions,
        'estadoReparadoId' =>$estadoReparadoId,
        'estadoIrreparableId'=>$estadoIrreparableId

      ];

  }




    /**
     * Updates an existing Log model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
     public function actionUpdate($id)
   {
       $request = Yii::$app->request;
       $model   = $this->findModel($id);
       $modelAnterior  = $model;

       /* =========================================================
        * AJAX
        * ========================================================= */
       if ($request->isAjax) {

           Yii::$app->response->format = Response::FORMAT_JSON;

           /* ---------- GET (abrir modal) ---------- */
           if ($request->isGet) {
               return [
                   'title'   => "Actualizar Log #".$id,
                   'content' => $this->renderAjax(
                       'update',
                       $this->precarga_update($model,$modelAnterior)
                   ),
                   'footer'  =>
                       Html::button('Cerrar', [
                           'class' => 'btn btn-default pull-left',
                           'data-dismiss' => 'modal'
                       ]) .
                       Html::button('Guardar', [
                           'class' => 'btn btn-primary',
                           'type'  => 'submit'
                       ]),
               ];
           }

           /* ---------- POST (guardar) ---------- */
           if ($model->load($request->post())) {

               // Contactos seleccionados desde el form
               $contactosIds = $request->post('Log')['contactos'] ?? [];
               $contactosIds = array_values(
                   array_filter(
                       array_map('intval', (array)$contactosIds),
                       fn($v) => $v > 0
                   )
               );

               // Validación del Log
               if (!$model->validate()) {
                   return [
                       'title'   => "Actualizar Log #".$id,
                       'content' => $this->renderAjax('update',
                        $this->precarga_update($model,$modelAnterior, $contactosIds)),
                       'footer'  =>
                           Html::button('Cerrar', [
                               'class' => 'btn btn-default pull-left',
                               'data-dismiss' => 'modal'
                           ]) .
                           Html::button('Guardar', [
                               'class' => 'btn btn-primary',
                               'type'  => 'submit'
                           ]),
                   ];
               }

               /* ---------- TRANSACCIÓN ---------- */
               $transaction = Yii::$app->db->beginTransaction();
               try {

                   // Guardar Log
                   if (!$model->save(false)) {
                       throw new \RuntimeException('No se pudo guardar el Log.');
                   }

                   $existingHistoricosIds = \app\models\Contactohistorico::find()
                       ->select('id_contacto')
                       ->where(['id_log' => $model->id])
                       ->column();
                  if ($model->id_estado == EstadoBase::EN_REPARACION) {
                   // Borrar históricos anteriores
                   \app\models\Contactohistorico::deleteAll([
                       'id_log' => $model->id
                   ]);}
                   else if(empty($existingHistoricosIds)){
                   // Crear nuevos históricos
                   foreach ($contactosIds as $cid) {

                       $c = \app\models\Contacto::findOne($cid);

                       $ch = new \app\models\Contactohistorico();
                       $ch->id_log       = $model->id;
                       $ch->id_contacto  = $c ? $c->id : $cid;
                       $ch->nombre       = $c->nombre    ?? null;
                       $ch->cargo        = $c->cargo     ?? null;
                       $ch->telefono     = $c->telefono  ?? null;
                       $ch->email        = $c->email     ?? null;

                       if (!$ch->save(false)) {
                           throw new \RuntimeException(
                               'Error guardando ContactoHistorico (contacto id '.$cid.')'
                           );
                       }
                   }
                  }



                   $transaction->commit();

                   return [
                       'forceReload' => '#crud-datatable-pjax',
                       'title'       => "Actualizar Log #".$id,
                       'content'     => '<span class="text-success">Log actualizado correctamente</span>',
                       'footer'      =>
                           Html::button('Cerrar', [
                               'class' => 'btn btn-default',
                               'data-dismiss' => 'modal'
                           ]),
                   ];

               } catch (\Throwable $e) {

                   $transaction->rollBack();
                   Yii::error($e->getMessage(), __METHOD__);

                   return [
                       'title'   => "Actualizar Log #".$id,
                       'content' => '<div class="alert alert-danger">'.$e->getMessage().'</div>',
                       'footer'  =>
                           Html::button('Cerrar', [
                               'class' => 'btn btn-default',
                               'data-dismiss' => 'modal'
                           ]),
                   ];
               }
           }
       }

       /* =========================================================
        * NO AJAX
        * ========================================================= */
       if ($model->load($request->post()) && $model->save()) {
           return $this->redirect(['view', 'id' => $model->id]);
       }

       return $this->render(
           'update',
           $this->precarga_update($model,$modelAnterior)
       );
   }


    //delete heredado


    /**
     * Finds the Log model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Log the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Log::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

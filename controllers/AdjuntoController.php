<?php

namespace app\controllers;

use Yii;
use app\models\Adjunto;
use app\models\Equipo;
use app\models\AdjuntoEquipo;
use app\models\AdjuntoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * AdjuntoController implements the CRUD actions for Adjunto model.
 */
class AdjuntoController extends BaseController
{
  // behaviors heredado

  public function devolverModelos($id_equipo){

    $searchModel = new AdjuntoSearch();
    // EVOLUCIONES ENFERMERIA //
    $dataProviderOp = $searchModel->search(Yii::$app->request->queryParams ,Adjunto::TIPOCATEGORIA_OPERATIVO,$id_equipo);
    $dataProviderOp ->pagination->pageSize = 7;
    //EVOLUCIONES LOS DEMAS PROFRESIONALES//
    $dataProviderBib = $searchModel->search(Yii::$app->request->queryParams,Adjunto::TIPOCATEGORIA_BIBLIOGRAFIA,$id_equipo);
    $dataProviderBib ->pagination->pageSize = 7;
    $dataConfig=[
            'searchModel' => $searchModel,
            'dataProviderOp' => $dataProviderOp,
            'dataProviderBib' => $dataProviderBib ,
        ];
    return $dataConfig;
  }

    /**
     * Lists all Adjunto models.
     * @return mixed
     */
    public function actionIndex($id_equipo)
    {
        $model_equipo=  Equipo::findOne(['id'=> $id_equipo]);
        $dataConfig = $this->devolverModelos($id_equipo);

        return $this->render('index', [
            'dataConfig' => $dataConfig,
            'model_equipo'=>$model_equipo
        ]);
    }


    /**
     * Displays a single Adjunto model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Adjunto #".$id,
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
     * Creates a new Adjunto model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
     public function actionCreate($id_equipo, $tipocategoria)
     {
         $request = Yii::$app->request;
         $model = new Adjunto();
         if($tipocategoria==Adjunto::TIPOCATEGORIA_OPERATIVO){
           $model->id_equipo = $id_equipo;
         }
         $model->tipocategoria =  $tipocategoria;

         if ($request->isAjax) {
             Yii::$app->response->format = Response::FORMAT_JSON;

             if ($request->isGet) {
                 return [
                     'title' => "Crear nuevo Adjunto",
                     'content' => $this->renderAjax('create', [
                         'model' => $model
                     ]),
                     'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                 Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                 ];
             } else if ($model->load($request->post())) {

                 $archivo = \yii\web\UploadedFile::getInstance($model, 'file');
                 $transaction = Yii::$app->db->beginTransaction();

                 try {
                     if ($archivo) {
                         $model->nombreasignado = $this->generarNombreUnico($archivo->extension);
                         $model->nombreoriginal = $archivo->name;
                     }

                     if (!$model->save()) {
                         throw new \Exception('Error al guardar el adjunto');
                     }

                     if ($archivo && !$this->subirArchivo($archivo, $model->nombreasignado,$model->tipocategoria)) {
                         throw new \Exception('Error al subir el archivo');
                     }
                     $crud_table='';
                     if($model->tipocategoria===Adjunto::TIPOCATEGORIA_BIBLIOGRAFIA){
                        $equipo= Equipo::findOne($id_equipo);
                        //  REPLICAR LA BIBLIOGRAFIA A TODOS LOS EQUIPOS QUE TENGA EL MISMO MODELO Y MARCA
                        $equipo->replicarBibliografia($model->id);

                       $crud_table='#crud-datatable-bib-pjax';
                     }
                     else {
                       $crud_table='#crud-datatable-op-pjax';
                     }
                     $transaction->commit();

                     return [
                         'forceReload' => $crud_table,
                         'title' => "Crear nuevo Adjunto",
                         'content' => '<span class="text-success">Adjunto creado con éxito</span>',
                         'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                     ];

                 } catch (\Exception $e) {

                     $transaction->rollBack();
                     $model->addError('file', $e->getMessage());
                 }
             }

             return [
                 'title' => "Crear nuevo Adjunto",
                 'content' => $this->renderAjax('create', [
                     'model' => $model
                 ]),
                 'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                             Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
             ];
         }
     }


     public function actionCreatebibliografia($id_equipo)
   {
       $request = Yii::$app->request;
       $model = new AdjuntoEquipo();

       // GET vía AJAX -> devolver formulario para modal
       if ($request->isAjax) {
          if ($request->isGet) {
           Yii::$app->response->format = Response::FORMAT_JSON;
           return [
               'title'   => 'Vincular adjunto bibliografia',
               'content' => $this->renderAjax('createbibliografia', [
                   'model' => $model,
                   'id_equipo' => $id_equipo,
               ]),

               'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                           Html::button('Vincular',['class'=>'btn btn-primary', 'id'=>'vincular','type'=>"submit"])
           ];
         }
           // POST normal (sin AJAX) - procesar el formulario
           if ($model->load($request->post())) {
               $model->id_equipo = $id_equipo; // Asegurar que tenga el id_equipo
               if ($model->save()) {
                   $mensaje='Adjunto vinculado correctamente.';
                   $respuesta="success";
               } else {
                   $mensaje='No se pudo crear la bibliografia.';
                   $respuesta="danger";

               }
               Yii::$app->response->format = Response::FORMAT_JSON;

               return [
                   'forceReload'=>'#crud-datatable-bib-pjax',
                   'title'=> "Devinculación",
                   'content'=>'<span class="text-'.$respuesta.'">'.$mensaje.'</span>',
                   'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
               ];
           }
       }

       // GET normal -> redirigir
       return $this->redirect(['adjunto/index', 'id_equipo' => $id_equipo]);
   }

     /**
      * Genera un nombre único para el archivo
      */
     private function generarNombreUnico($extension)
     {
         return Yii::$app->security->generateRandomString(32) . '_' . time() . '.' . $extension;
     }

     /**
      * Sube el archivo al servidor
      */
     private function subirArchivo($archivo, $nombreAsignado, $tipocategoria)
     {

         $ruta = Yii::getAlias('@webroot/uploads/adjuntos/'.$tipocategoria."/");

         if (!is_dir($ruta)) {
             FileHelper::createDirectory($ruta, 0755);
         }

         return $archivo->saveAs($ruta . $nombreAsignado);
     }


    /**
     * Updates an existing Adjunto model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Actualizar Adjunto #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];
            }else if($model->load($request->post()) && $model->save()){
                $crud_table='';
                if($model->tipocategoria===Adjunto::TIPOCATEGORIA_BIBLIOGRAFIA){
                  $crud_table='#crud-datatable-bib-pjax';
                }else {
                  $crud_table='#crud-datatable-op-pjax';
                }
                return [
                  'forceReload' => $crud_table,
                  'forceClose' => false, // cierra el modal ajaxcrud
                  'title'=> "Adjunto #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];
            }else{
                 return [
                    'title'=> "Actualizar Adjunto #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model
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
                    'model' => $model
                ]);
            }
        }
    }


    public function actionDelete($id)
   {
       $request = Yii::$app->request;

       if ($request->isAjax) {
           Yii::$app->response->format = Response::FORMAT_JSON;
           try {
               $model = $this->findModel($id);
               $model->delete();
               $mensaje='El registro se eliminó correctamente.';
               $respuesta="success";
           } catch (\yii\db\Exception $e) {
               $errorInfo = $e->errorInfo;
               if ($errorInfo[0] === '23503') { // Violación de clave foránea
                   $tablasRelacionadas = $this->obtenerTablasQueReferencian($model);

                   if (!empty($tablasRelacionadas)) {
                       $mensajeTablas = implode(', ', $tablasRelacionadas); // Combina las tablas en un mensaje
                       $mensaje ="No se puede eliminar el registro, está vinculado con la/s tabla/s: {$mensajeTablas}.";
                   } else {
                     $mensaje = "No se puede eliminar el registro debido a una relación de clave foránea.";
                   }
               } else {
                    $mensaje="Ocurrió un error inesperado: " . $e->getMessage();
               }
               $respuesta="danger";

           }
           return [
               'forceReload'=>'#crud-datatable-op-pjax',
               'title'=> "Eliminación",
               'content'=>'<span class="text-'.$respuesta.'">'.$mensaje.'</span>',
               'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
           ];
       } else {

           return $this->redirect(['index']);
       }
   }

   public function actionDeletebibliografia($id, $id_equipo)
   {
       $request = Yii::$app->request;
       if ($request->isAjax) {
           Yii::$app->response->format = Response::FORMAT_JSON;
           $transaction = Yii::$app->db->beginTransaction();
           try {
             $rel = \app\models\AdjuntoEquipo::findOne([
                 'id_adjunto' => $id,
                 'id_equipo'  => $id_equipo,
             ]);
             // borrar la vinculación
             $rel->delete();
             $adj = $this->findModel($id); // si no existe lanzará NotFoundHttpException
             $adj->delete();
             $transaction->commit();
             $mensaje='El registro se eliminó correctamente.';
             $respuesta="success";

             } catch (\Throwable $e) {
               $transaction->rollBack();
               $respuesta='No se pudo eliminar: ' . $e->getMessage();
               $respuesta="danger";
             }
             return [
                 'forceReload'=>'#crud-datatable-bib-pjax',
                 'title'=> "Eliminación",
                 'content'=>'<span class="text-'.$respuesta.'">'.$mensaje.'</span>',
                 'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
             ];
       }else {
         return $this->redirect(['index', 'id_equipo'=>$id_equipo]);

       }
   }

   public function actionUnlink($id , $id_equipo)
   {
       $request = Yii::$app->request;

       if($request->isAjax){
         $transaction= Yii::$app->db->beginTransaction();
         try {
           Yii::$app->response->format = Response::FORMAT_JSON;
           $adjunto_equipo= AdjuntoEquipo::findOne(["id_equipo"=>$id_equipo, "id_adjunto"=>$id]);
           $adjunto_equipo->delete();
           $transaction->commit();

           $mensaje='El registro se devinculo correctamente.';
           $respuesta="success";
         } catch (\Throwable $e) {
           $transaction->rollBack();
           $mensaje='No se pudo desvincular: ' . $e->getMessage();
           $respuesta="danger";
         }
         return [
             'forceReload'=>'#crud-datatable-bib-pjax',
             'title'=> "Devinculación",
             'content'=>'<span class="text-'.$respuesta.'">'.$mensaje.'</span>',
             'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
         ];

       }else{

           return $this->redirect(['index']);
       }


   }


    /**
     * Finds the Adjunto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Adjunto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Adjunto::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

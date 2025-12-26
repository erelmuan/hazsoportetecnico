<?
namespace app\controllers;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\helpers\Html;
use \yii\web\Response;

class BaseController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    public function setearMensajeError($mensaje){
        Yii::$app->getSession()->setFlash('warning', [
            'type' => 'danger',
            'duration' => 6500,
            'icon' => 'fas fa-warning',
            'message' => $mensaje,
            'title' => 'NOTIFICACIÓN',
            'positonY' => 'top',
            'positonX' => 'right'
        ]);

      }
    public function setearMensajeExito($mensaje){
          Yii::$app->getSession()->setFlash('success', [
              'type' => 'success',
              'duration' => 5000,
              'icon' => 'fas fa-check-circle',
              'message' => $mensaje,
              'title' => 'NOTIFICACIÓN',
              'positonY' => 'top',
              'positonX' => 'right'
          ]);
        }

        public function obtenerTablasQueReferencian($model)
        {
            $db = Yii::$app->db;
            $schema = $db->schema;
            $tablaActual = $model->tableName();
            $pkValor = $model->id;

          //  proveedor	Tabla padre / tabla referenciada
          //  contacto	Tabla hija / tabla que referencia
            $tablasQueReferencian = [];
            //recorre cada tabla de la base de datos
            foreach ($schema->tableSchemas as $tabla) {
              //recorre cada foreign key (cada tabla que hace referencia $tabla)
                foreach ($tabla->foreignKeys as $fk) {

                    // fk[0] => tabla referenciada
                    if ($fk[0] === $tablaActual) {

                        $tablaReferenciadora = $tabla->name;

                        // fk[column_fk] = column_pk
                        foreach ($fk as $columnaFk => $columnaPk) {

                            // salteamos el índice 0
                            if ($columnaFk === 0) {
                                continue;
                            }

                            $count = (int) $db->createCommand(
                                "SELECT COUNT(*) FROM {$tablaReferenciadora} WHERE {$columnaFk} = :id"
                            )
                            ->bindValue(':id', $pkValor)
                            ->queryScalar();

                            if ($count === 1) {
                                $tablasQueReferencian[] = $tablaReferenciadora;
                            }
                        }
                    }
                }
            }

            return array_unique($tablasQueReferencian);
        }


    /**
     * Delete an existing Paciente model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
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
                'forceReload'=>'#crud-datatable-pjax',
                'title'=> "Eliminación",
                'content'=>'<span class="text-'.$respuesta.'">'.$mensaje.'</span>',
                'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
            ];

        } else {

            return $this->redirect(['index']);
        }
    }

}
?>

<?php
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Iniciar sesión';

/* CSS local para personalizar el login (puedes moverlo a site.css si querés) */
$this->registerCss(<<<'CSS'
.login-card {
  border-radius: 12px;
  box-shadow: 0 12px 30px rgba(10,30,60,0.12);
  overflow: hidden;
  display: flex;
  min-height: 380px;
}

/* Panel izquierdo con imagen / branding */
.login-left {
  flex: 0 0 220px;
  background: #343a40;
  color: #fff;
  padding: 26px;
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:center;
  text-align:center;
}
.login-left .brand-logo {
  width: 92px;
  height: 92px;
  border-radius: 50%;
  background:#1e4f80;
  display:flex;
  align-items:center;
  justify-content:center;
  margin-bottom:10px;
  font-size:36px;
}
.login-left h3 { margin:6px 0 0; font-weight:700; letter-spacing:.6px; }
.login-left p { margin:6px 0 0; font-size:13px; color: rgba(255,255,255,0.82); }

/* Panel derecho con el formulario */
.login-right {
  flex:1;
  background: #fff;
  padding: 22px;
}
.login-card .login-card-body {
  padding: 8px 0 0 0;
}
.login-msg { font-weight:700; color:#1f3b4a; margin-bottom:14px; text-align:center; }

/* Inputs */
.input-group .form-control {
  border-right: 0;
  border-radius: 6px 0 0 6px;
}
.input-group .input-group-text {
  background:transparent;
  border-left:0;
  border-radius: 0 6px 6px 0;
}
.form-control:focus {
  box-shadow: 0 4px 14px rgba(29,122,160,0.12);
  border-color: #1d9ad6;
}

/* Botón principal con gradiente */
.btn-signin {
  background: linear-gradient(90deg,#1d9ad6,#147db0);
  border: none;
  color: #fff;
  box-shadow: 0 8px 20px rgba(20,125,176,0.16);
  border-radius: 8px;
  padding: .48rem .9rem;
}

/* Recordarme */
.icheck-primary { display:flex; align-items:center; gap:.45rem; color:#196d84; font-weight:600; }

/* Enlaces */
.forgot-link { display:block; margin-top:10px; color:#1a7fb1; font-weight:600; text-decoration:none; }

/* Responsive */
@media (max-width:720px) {
  .login-card { flex-direction: column; min-height: auto; }
  .login-left { flex: 0 0 auto; padding:18px 14px; }
}
CSS
);
?>

<div class="card login-card">
    <!-- Panel izquierdo: branding -->
    <div class="login-left">
        <div class="brand-logo">
            <span class="fas fa-tools" style="color:#fff;"></span>
        </div>
        <h3>HAZ <span style="color:#8fe1ff">SOPORTE</span></h3>
        <p>Sistema de gestión de equipos</p>
    </div>

    <!-- Panel derecho: formulario -->
    <div class="login-right">
        <div class="login-card-body">
            <p class="login-msg">Inicie sesión para comenzar</p>

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'enableClientValidation' => true,
                'fieldConfig' => [
                    'template' => "{beginWrapper}\n{input}\n{error}\n{endWrapper}",
                    'wrapperOptions' => ['class' => 'input-group mb-3'],
                    'errorOptions' => ['class' => 'invalid-feedback d-block'],
                    'inputOptions' => ['class' => 'form-control'],
                ],
            ]); ?>

            <?= $form->field($model, 'username', [
                    'inputOptions' => ['placeholder' => $model->getAttributeLabel('username')],
                ])->textInput()->label(false)
                ->hint(false)
                ->widget(\yii\widgets\InputWidget::class, []) // no-op to keep chaining safe
            ?>
            <?php // Render manual to include icon on right like original pattern ?>
            <div class="input-group mb-3">
                <?= Html::activeTextInput($model, 'username', ['class'=>'form-control','placeholder'=>$model->getAttributeLabel('username')]) ?>
                <div class="input-group-append">
                    <div class="input-group-text"><span class="fas fa-user"></span></div>
                </div>
                <?= Html::error($model, 'username', ['class'=>'invalid-feedback d-block']) ?>
            </div>

            <div class="input-group mb-3">
                <?= Html::activePasswordInput($model, 'password', ['class'=>'form-control','placeholder'=>$model->getAttributeLabel('password')]) ?>
                <div class="input-group-append">
                    <div class="input-group-text"><span class="fas fa-lock"></span></div>
                </div>
                <?= Html::error($model, 'password', ['class'=>'invalid-feedback d-block']) ?>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <?= $form->field($model, 'rememberMe')->checkbox([
                        'template' => '<div class="icheck-primary">{input}{label}</div>',
                        'labelOptions' => ['style' => 'margin-left:.35rem;'],
                        'uncheck' => null
                    ])->label(false) ?>
                </div>

                <div>
                    <?= Html::submitButton('<i class="fas fa-sign-in-alt mr-1"></i> Ingresar', [
                        'class' => 'btn btn-signin'
                    ]) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

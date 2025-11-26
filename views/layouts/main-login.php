<?php

/* @var $this \yii\web\View */
/* @var $content string */

\hail812\adminlte3\assets\AdminLteAsset::register($this);

\hail812\adminlte3\assets\PluginAsset::register($this)->add(['fontawesome', 'icheck-bootstrap']);
$this->registerCssFile('@web/css/site.css');
?>
<style>
.main-footer {
  position: fixed !important;
  left: 0 !important;
  margin-left: 0px!important;
  right: 0 !important;
  bottom: 0 !important;
  width: 100vw !important;
  z-index: 1050;
  box-sizing: border-box;
    padding: 8px 20px;
    border-top: 1px solid #dee2e6;
    font-size: 14px;
}

.main-footer p {
    margin: 0;
}

</style>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AdminLTE 3 | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<body class="hold-transition login-page">
<?php  $this->beginBody() ?>
<div class="login-box">

    <!-- /.login-logo -->

    <?= $content ?>

</div>
<!-- /.login-box -->


<!-- FOOTER: imagen + barra informativa -->
<footer class="main-footer">
  <div id="datosHospital" class="d-flex justify-content-between">
    <p>&copy; Departamento de informática Artémides Zatti - <?= date('Y') ?></p>
    <p><?= Yii::powered() ?></p>
  </div>
</footer>


<?php $this->endBody() ?>

</body>

</html>

<?php $this->endPage() ?>

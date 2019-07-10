<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Resumen de la orden';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>


    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-6\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-3 control-label'],
        ],
        'action' => '?r=site/new-order-process'
    ]); ?>
        <div class="row">
        <label class="col-lg-3 control-label">Nombre del adquiriente</label><div class="col-lg-3"><?= $model->customer_name ?></div><div class="col-lg-6">&nbsp;</div>
        </div>
        <div class="row">
        <label class="col-lg-3 control-label">Correo electr&oacute;nico</label><div class="col-lg-3"><?= $model->customer_email ?></div><div class="col-lg-6">&nbsp;</div>
        </div>
        <div class="row">
        <label class="col-lg-3 control-label">Tel&eacute;fono</label><div class="col-lg-3"><?= $model->customer_mobile ?></div><div class="col-lg-6">&nbsp;</div>
        </div>
        <div class="row">
        <label class="col-lg-3 control-label">Identificador de Place to Pay</label><div class="col-lg-3"><?= $model->reqId ? $model->reqId : "No asignado aún" ?></div><div class="col-lg-6">&nbsp;</div>
        </div>
        <div class="row">
        <label class="col-lg-3 control-label">Estado en Place to Pay</label><div class="col-lg-3"><?= $p2pStatus ?></div><div class="col-lg-6">&nbsp;</div>
        </div>
        
            <div class="row">
            <?php
            if($model->status != "PAYED"){
                ?>
                    <a target="_blank" href="?r=site/order-pay&id=<?= $model->id ?>" class="btn btn-<?= ["REJECTED" => "danger", "CREATED" => "success"][$model->status] ?>"><?= ["REJECTED" => "Reintentar", "CREATED" => "Pagar"][$model->status] ?></a>
                 <?php   
            }?>
            </div>

        

    <?php ActiveForm::end(); ?>

    
</div>

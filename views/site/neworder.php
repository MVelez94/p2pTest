<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Nueva orden';
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
        <?= $form->field($model, 'id')->hiddenInput(["value" => $model->id ? $model->id : 0]) ; ?>
        <?= $form->field($model, 'customer_name')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'customer_email')->textInput() ?>
        <?= $form->field($model, 'customer_mobile')->textInput() ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Ordenar', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

    
</div>

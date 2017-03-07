<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\cms\models\CmsBlockMapper */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cms-block-mapper-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'block_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cms_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_by')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

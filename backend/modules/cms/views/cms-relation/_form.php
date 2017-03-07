<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\cms\models\CmsRelation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cms-relation-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cms_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'keyword_id')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

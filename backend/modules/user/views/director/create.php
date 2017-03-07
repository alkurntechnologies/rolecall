<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\user\models\User */

$this->title = Yii::t('app', 'Create School');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelUserAddress' => $modelUserAddress,
        'modelUserProfile' => $modelUserProfile,
        'modelUserSchool' => $modelUserSchool,
    ]) ?>

</div>

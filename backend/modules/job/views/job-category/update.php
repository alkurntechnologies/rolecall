<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\job\models\JobCategory */

$this->title = 'Update Job Category: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Job Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->category_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="job-category-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'modelJobCategoryFr' => $modelJobCategoryFr,
        'modelJobCategoryTemplate' => $modelJobCategoryTemplate,
        'modelJobCategoryTemplateFr' => $modelJobCategoryTemplateFr,
    ]) ?>


</div>

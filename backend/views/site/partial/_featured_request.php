<?php
use yii\grid\GridView;
use backend\modules\user\models\UserProfile;
use yii\helpers\Html;

?>
<div class="box box-warning">
    <div class="box-header with-border">
       <h3 class="box-title">Request For Featured Talents</h3>
       <div class="box-tools pull-right">
            <!--span class="label label-danger">8 New Members</span -->
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <?php
        $searchFeaturedTalents->is_featured = UserProfile::FEATURED_REQUEST;
        $dataProvider = $searchFeaturedTalents->search(Yii::$app->request->queryParams);
        ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'label' => 'Name',
                    'attribute' => 'userProfile.name',
                ],
                'email:email',

                [
                    'attribute' => 'status',
                    'value' => function ($model) {
                        return $model->status == 10 ? 'Active' . (($model->userProfile->is_verified == 0) ? ' (Un Verified)' : '') : 'Blocked';
                    },
                ],
                [
                    'label' => 'Registration Date',
                    'attribute' => 'created_at',
                    'value' => function ($model) {
                        return date('j F, Y H:i: A', $model->created_at);
                    },
                ],
                ['class' => 'yii\grid\ActionColumn',
                    'template' => '{view}',
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if ($action === 'view') {
                            $url = '/admin/user/talent/view?id=' . $model->id;
                            return $url;
                        }
                    },
                ],
            ],
        ]); ?>
        <!-- /.users-list -->
    </div>
    <!-- /.box-body -->
    <div class="box-footer text-center">
        <?=Html::a( 'View all featured requested talents', ['/user/talent/index', 'is_featured'=>1], ['class' => 'btn btn-sm btn-default btn-flat pull-right uppercase']);?>
    </div>
    <!-- /.box-footer -->
</div>
<!--/.box -->

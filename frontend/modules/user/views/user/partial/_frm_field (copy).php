<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\jui\DatePicker;
use backend\modules\user\models\UserFieldValue;
use backend\modules\user\models\UserField;
use kartik\file\FileInput;
use yii\helpers\Url;
use yii\bootstrap\Modal;

$this->registerJsFile('@web/js/custom-user.js',['depends' => [\yii\web\JqueryAsset::className()]]);

$flds = [];

$modelUFValue = new UserFieldValue();
$role = Yii::$app->user->identity->getRoleName();

$userFieldList = UserField::find()
    ->where(['status' => '1', 'section' => $role])
    ->orderBy(['order_by' => SORT_ASC])
    ->all();

foreach ($userFieldList as $i => $modelUF) {
    // necessary for update action.
    if (!$model->isNewRecord) {
        $form->field($modelUFValue, "[{$i}]user_id")->hiddenInput(['value' => $model->id])->label(false);
        $modelUFValue = UserFieldValue::findOne(['user_id' => $model->id, 'field_id' => $modelUF->field_id]) ?? $modelUFValue;
    }

    echo $form->field($modelUFValue, "[{$i}]field_id")
        ->hiddenInput(['value' => $modelUF->field_id])
        ->label(false);

    $param = [];
    $indxFlds[$modelUF->field_id] = $i;



    if (!is_null($modelUF->depend) && isset($indxFlds[$modelUF->depend])) {
        $idxDepend = $indxFlds[$modelUF->depend];
        $param = ['class' => 'depended-field', 'depended-field_id' => $idxDepend, 'depends' => 'userfieldvalue-' . $idxDepend . '-value'];
    }else{
        $param = ['class' => $modelUF->field];
    }

    switch ($modelUF->type) {
        case 'Text':
            $flds[$modelUF->section][$modelUF->layout][$modelUF->order_by]
                = $form->field($modelUFValue,
                "[{$i}]value")->textInput()
                ->label($modelUF->name);
            break;
        case "DatePicker":
            $flds[$modelUF->section][$modelUF->layout][$modelUF->order_by]
                = $form->field($modelUFValue, "[{$i}]value")->widget(DatePicker::className(), ['name' => "[{$i}]value", 'clientOptions' => [
                    'changeMonth' => true,
                    'yearRange' => '1960:' . date('Y'),
                    'changeYear' => true,
                ],])->label($modelUF->name);
            break;
        case 'TextArea':
            $flds[$modelUF->section][$modelUF->layout][$modelUF->order_by] =
                $form->field($modelUFValue, "[{$i}]value")
                    ->textarea(['rows' => '6', 'columns' => '10'])
                    ->label($modelUF->name);
            break;
        case 'List':
            $optionList = ArrayHelper::map($modelUF->userFieldOptions, 'value', 'name');
            $flds[$modelUF->section][$modelUF->layout][$modelUF->order_by] =
                $form->field($modelUFValue,
                    "[{$i}]value")->widget(Select2::classname(),
                    ['data' => $optionList,
                        'options' => ['placeholder' => 'Select ...'],
                        'pluginOptions' => ['allowClear' => true],])
                    ->label($modelUF->name);
            break;
        case 'MultiList':
            $optionList = ArrayHelper::map($modelUF->userFieldOptions, 'value', 'name');
            $modelUFValue->value = ($modelUFValue->field && is_null($modelUFValue->field->is_serialize))
                ? ArrayHelper::map($modelUF->userFieldValues, 'value_id', 'value') :
                @unserialize($modelUFValue->value);
            $flds[$modelUF->section][$modelUF->layout][$modelUF->order_by] =
                $form->field($modelUFValue,
                    "[{$i}]value")->widget(Select2::classname(),
                    ['data' => $optionList,
                        'options' =>
                            ['placeholder' => 'Select ...', 'multiple' => true],
                        'pluginOptions' => ['allowClear' => true],])
                    ->label($modelUF->name);
            break;
        case 'Radio':
            $optionList = ArrayHelper::map($modelUF->userFieldOptions, 'value', 'name');
            $flds[$modelUF->section][$modelUF->layout][$modelUF->order_by] =
                $form->field($modelUFValue,
                    "[{$i}]value")->RadioList($optionList, $param)->label($modelUF->name);
            break;

        case 'File':
            echo $form->field($modelUFValue, "[{$i}]image_data")
                ->hiddenInput(['value' => ''])
                ->label(false);

            $flds[$modelUF->section][$modelUF->layout][$modelUF->order_by] =
                $form->field($modelUFValue, "[{$i}]value")->widget(FileInput::classname(), [
                    'options' => [
                        'multiple' => false,
                        'accept' => 'image/*',
                        'class' => 'option-image'
                    ],
                    'pluginOptions' => [
                        'previewFileType' => 'image',
                        'showCaption' => false,
                        'showUpload' => false,
                        'showRemove' => false,
                        'initialPreviewAsData' => true,
                        'allowedFileExtensions' => ['jpg', 'png','gif'],
                        'browseLabel' => ' Pick image',
                        'browseIcon' => '<i' . ' class="glyphicon glyphicon-picture"></i>',
                        'browseClass' => 'btn btn-default btn-sm',
                        'removeClass' => 'btn btn-danger btn-sm',
                        'removeLabel' => ' Delete',
                        'removeIcon' => '<i' . ' class="fa fa-trash"></i>',
                        'previewSettings' => ['image' => ['width' => '138px', 'height' => 'auto']],
                        'initialPreview' => [ '/uploads/' . ( empty($modelUFValue->value) ? 'picture.jpg' : $modelUFValue->value) ],
                        'layoutTemplates' => ['footer' => $this->render('_footer')],
                    ],
                    'pluginEvents' => [
                        'change' => 'function(e) {
                                var fieldId = $(this).attr("id");
                                $("#modal").attr("data-image-id", fieldId);
                                $("#modal").modal("show");
                            
                        }',
                    ],
                ])->label($modelUF->name);

            break;

        case 'Files':
            $flds[$modelUF->section][$modelUF->layout][$modelUF->order_by] =
                $form->field($modelUFValue, "[{$i}]value[]")->widget(FileInput::classname(), [
                    'options' => [
                        'multiple' => true,
                        'accept' => 'image/*',
                        'class' => 'option-image'
                    ],
                    'pluginOptions' => [
                        'previewFileType' => 'image',
                        'showCaption' => false,
                        'showUpload' => false,
                        'showRemove' => false,
                        'initialPreviewAsData' => true,
                        'allowedFileExtensions' => ['jpg', 'png','gif'],
                        'browseClass' => 'btn btn-default btn-sm',
                        'browseLabel' => ' Pick image',
                        'browseIcon' => '<i' . ' class="glyphicon glyphicon-picture"></i>',
                        'removeClass' => 'btn btn-danger btn-sm',
                        'removeLabel' => ' Delete',
                        'removeIcon' => '<i' . ' class="fa fa-trash"></i>',
                        'previewSettings' => [
                            'image' => ['width' => '138px', 'height' => 'auto']
                        ],
                        'initialPreview' => '/uploads/' . ( empty($modelUFValue->value) ? 'picture.jpg' : $modelUFValue->value),
                        'layoutTemplates' => ['footer' => '']
                    ]
                ])->label($modelUF->name);
            break;

        default:
    }
}
reset($flds);

foreach ($flds as $section => $layout_array) {
    reset($layout_array);
    ?>
    <div class="job-field-form">
        <!--<h1 class="secondti"><?php if ($role == "User") {
            echo $section;
        } ?></h1>-->
    </div>
    <div class="">
        <?php foreach ($layout_array as $layout => $fld_array) { ?>
            <?php reset($fld_array); ?>
                <h1 class="secondti two"><?php if ($layout != "Talent Overview") { echo $layout; } ?></h1>
                <div class="row">
                    <?php foreach ($fld_array as $key => $fld) { ?>
                        <div class="<?= ($role == "Director") ? "col-sm-12" : "col-sm-4" ?> user-field-<?= $key ?>"><?= $fld; ?></div>
                    <?php } ?>
                </div>
        <?php } ?>
        <?=$this->render('_photo-cropper');?>
    </div>
<?php } ?>

<?php
$js = <<<JS
    $(function () {
            
            var cropBoxData;
            var canvasData;
            var image = $('#croping-image');   
            
            $('#modal').on('shown.bs.modal', function () {  
                
                image.attr('src','/uploads/loading.gif');       
                console.log('tester');
                var imageId = $(this).attr("data-image-id");
                var content = $('div.form-group.field-'+imageId).find('.file-preview-thumbnails'); 
                var img = $(content).find('img');                
                image.attr('src', $(img).prop('src'));  
                
                image.cropper({                    
                    aspectRatio: 1,
                    crop: function (e) {
                        var json = [
                              '{"x":' + e.x,
                              '"y":' + e.y,
                              '"height":' + e.height,
                              '"width":' + e.width,
                              '"rotate":' + e.rotate + '}'
                            ].join();  
                        
                        $('#'+imageId).attr('value', json);  
                    },
                    ready: function () {
                        image.cropper('setCanvasData', canvasData);
                        image.cropper('setCropBoxData', cropBoxData);
                    }
                });
                
                
            }).on('hidden.bs.modal', function () {
                cropBoxData = image.cropper('getCropBoxData');
                canvasData = image.cropper('getCanvasData');
                imageData = image.cropper('getImageData'); 
                croppedCanvas = image.cropper("getCroppedCanvas"); 
                image.cropper('destroy');           
            }).on( 'click', '#btn-crop',function(){
                
                /*cropBoxData = image.cropper('getCropBoxData');
                canvasData = image.cropper('getCanvasData');
                imageData = image.cropper('getImageData'); 
                croppedCanvas = image.cropper("getCroppedCanvas");*/
                
                imageURL = croppedCanvas.toDataURL("image/jpeg");
                
                var imageId = $('#modal').attr("data-image-id");
                var content = $('div.form-group.field-'+imageId).find('.file-preview-thumbnails'); 
                var img = $(content).find('img');   
                $(img).attr('src', imageURL);                   
                image.cropper('destroy');           
            }); 
    });
JS;

$this->registerJs($js);



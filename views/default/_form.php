<?php
/**
 * @copyright Copyright &copy; Caleb Crosby, CICSolutions.com, 2014
 * @package yii2-testimonials
 * @version 1.0.0
 */

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\widgets\AlertBlock;

use frontend\assets\TestimonialsAsset;
TestimonialsAsset::register($this);

/* @var $this yii\web\View */
/* @var $model frontend\models\Testimonial */
/* @var $form yii\widgets\ActiveForm */

// display growl message
echo AlertBlock::widget([
	'useSessionFlash' => true,
	'type' => AlertBlock::TYPE_GROWL,
	'delay' => 700,
	'alertSettings' => ['success' => [
			'type' => 'success',
			'title' => 'Testimonial Saved!',
			'icon' => 'glyphicon glyphicon-ok-sign',
			'showSeparator' => true,
			'pluginOptions' => [
				'position' => [
					'from' => 'top',
					'align' => 'right',
				]
			]
		]
	]
]);
?>

<div class="testimonial-form">

    <?php $form = ActiveForm::begin([
    	'id' => 'testimonial-form',
    	'type' => ActiveForm::TYPE_HORIZONTAL,
    	'formConfig' => ['labelSpan' => 2, 'deviceSize' => ActiveForm::SIZE_SMALL],
    	'options' => ['enctype' => 'multipart/form-data']
	]);
    ?>

	    <?= Html::activeHiddenInput($model, 'user_id') ?>
	    <?= Html::activeHiddenInput($model, 'created_at') ?>

	    <?= $form->field($model, 'name')->textInput(['maxlength' => 255])->hint(
	    	$form->field($model, 'anonymous', ['options'=> ['class'=>'']])->checkbox()
	    ) ?>
	    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
	    <?= $form->field($model, 'url')->textInput(['maxlength' => 255]) ?>
	    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
<!-- 		    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?> -->
		<div class="form-group">
			<label class="col-sm-2 control-label" for="testimonial-text">Text</label>
			<div class="col-sm-10">
				<?= yii\imperavi\Widget::widget([
				
				    // You can either use it for model attribute
				    'model' => $model,
				    'attribute' => 'text',
	
				    // Some options, see http://imperavi.com/redactor/docs/
				    'options' => [
				    	'buttons' => ['bold', 'italic','unorderedlist', 'orderedlist'],
				    	'minHeight' => 120,
	/* 			        'css' => 'wym.css', */
				    ],
				]);	
				?>
			</div>
		</div>
		
	    <?= $form->field($model, 'image')->widget(FileInput::classname(),
	    		['options' => ['accept' => 'image/*','multiple' => false],
	    		'pluginOptions' => [
	    			'overwriteInitial' => true,
	    			'showUpload' => false,
	    			'showRemove' => true,
	//				'browseClass' => 'btn btn-primary btn-block',
					'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
					'browseLabel' => '&nbsp;&nbsp;Select Photo',
	    			'initialPreview' => !$model->image ? false : [
	    				Html::img(Yii::$app->params['uploadPath'] . 'testimonial_images/' . $model->image, ['class'=>'file-preview-image', 'alt'=>'User Name Testimonial Image', 'title'=>'User Name Testimonial Image']),
	    			],
	    		]
	    	]);
	    ?>
	    <?= $form->field($model, 'video_url')->textInput(['maxlength' => 255]) ?>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
		        <?= Html::submitButton($model->isNewRecord ? 'Share your testimonial' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		        <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-default']) ?>
	    	</div>
		</div>

    <?php ActiveForm::end(); ?>

</div>

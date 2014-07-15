<?php
/**
 * @copyright Copyright &copy; Caleb Crosby, CICSolutions.com, 2014
 * @package yii2-testimonials
 * @version 1.0.0
 */

use yii\helpers\Html;

use soju\yii2fontawesome\FontAwesome as FA;

/* @var $this yii\web\View */
/* @var $model frontend\models\Testimonial */
/* @var $form yii\widgets\ActiveForm */


/* Set conditional display options */
$anonymous 	= (1==2);
/* $modal_id 	= $model->id . '-modal'; */

// handle testimonial image
$show_image = (1==1 && !empty($model->image));

if ($show_image) {
	// set the image path
	$image_path 	= Yii::$app->params['uploadPath'] . 'testimonial_images/' . $model->image;

	// set the image options/classes
	$image_options 	= ['class' => 'img-responsive img-rounded img-thumbnail'];
}

// handle testimonial video
$show_video = (1==1 && !empty($model->video_embed));

if ($show_video){
	//
}

// setting for if to play the video in the lightbox or not? If so, use image preview with customizable play button icons
$lightbox_vid 	= (1==1);

$layout_2col 	= $show_image || $show_video;
$layout_media 	= 'left';

// only prepare the media content if we have media to show
if ($layout_2col) {
	// prepare the media column content
	ob_start(); ?>
	<div class="col-sm-5 col-lg-4">
		<?php 
		if ($show_image) {
			echo newerton\fancybox\FancyBox::widget([
			    'target' => 'a[rel=' . $model->id . '-lightbox]',
			    'helpers' => true,
			    'mouse' => true,
			    'config' => [
			    	'title' => Html::encode($model->title),
			        'maxWidth' => '90%',
			        'maxHeight' => '90%',
			        'playSpeed' => 7000,
			        'padding' => 0,
			        'fitToView' => false,
			        'width' => '70%',
			        'height' => '70%',
			        'autoSize' => false,
			        'closeClick' => false,
			        'openEffect' => 'elastic',
			        'closeEffect' => 'elastic',
			        'prevEffect' => 'elastic',
			        'nextEffect' => 'elastic',
			        'closeBtn' => false,
			        'openOpacity' => true,
			        'helpers' => [
			            'title' => ['type' => 'float'],
			            'buttons' => [],
			            'thumbs' => ['width' => 68, 'height' => 50],
			            'overlay' => [
			                'css' => [
			                    'background' => 'rgba(0, 0, 0, 0.8)'
			                ]
			            ]
			        ],
			    ]
			]);
		
			echo Html::a(Html::img($image_path, $image_options), $image_path, ['rel' => $model->id . '-lightbox']);
		}
		?>		
		
		<?php if ($show_video): ?>
			<div class="video-container"><?php echo $model->video_embed; ?></div>
		<?php endif; ?>	
	</div>
	<?php
	$media_column_content = ob_get_contents();
	ob_end_clean();	
}
?>

<div class="testimonial clearfix rounded">

	<div class="row">
	
		<?php if ($layout_2col && $layout_media == 'left')
			echo $media_column_content; ?>
		
		<div class="<?php echo $layout_2col ? 'col-sm-7 col-lg-8' : 'col-sm-12' ?>">
		
			<?php // CICS Todo: need to amp up the testimonial code for seo and overall flexibility ?>
		
			<blockquote>
			
				<?= FA::icon('quote-left 3x pull-left') ?>
				<h2><?= Html::encode($model->title) ?></h2>
				<?= $model->text ?>
				<?= FA::icon('quote-right 3x pull-right') ?>
				
				<!-- CAN WE MAKE THIS AN HTML5 TAG FOR PERSON - ALSO SHOULD INCLUDE THE TAGS THAT ADAM SUGGESTD! -->
				<small><?= Html::encode($model->name) ?></small>
				
			</blockquote>
		</div>

		<?php if ($layout_2col && $layout_media == 'right')
			echo $media_column_content; ?>
		
	</div>

	<?php if (Yii::$app->user->can("admin")): ?>
	<div class="admin-controls">
		<?php
		echo Html::a('[Edit]', ['update', 'id' => $model->id]);
		echo Html::a('[Delete]', ['delete', 'id' => $model->id]);
		echo Html::a('[Approve]', ['update', 'id' => $model->id]);
		?>
	</div>
	<?php endif; ?>

</div>
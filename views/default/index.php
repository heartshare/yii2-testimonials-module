<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use kartik\widgets\AlertBlock;
//use kartik\sortable\Sortable; // could make conditional based on user can admin

use frontend\assets\TestimonialsAsset;
TestimonialsAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\search\TestimonialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

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

$this->title = 'Testimonials';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="testimonial-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Submit Your Testimonial!', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'layout'=>'{items}{summary}{pager}',
/*
        'pager'=> [
            'firstPageLabel' => Glyph::icon(Glyph::ICON_FAST_BACKWARD),
            'lastPageLabel' => Glyph::icon(Glyph::ICON_FAST_FORWARD),
            'nextPageLabel' => Glyph::icon(Glyph::ICON_STEP_FORWARD),
            'prevPageLabel' => Glyph::icon(Glyph::ICON_STEP_BACKWARD),
        ],
*/
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return $this->render('_testimonial',['model' => $model]);
        },
    ]) ?>

</div>

<?php
/*
echo Sortable::widget([
	'items'=>[
		['content'=>'Item 1'],
		['content'=>'Item 2'],
		['content'=>'Item 3'],
		['content'=>'Item 4'],
	]
]);
*/
?>

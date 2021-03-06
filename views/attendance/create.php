<?php

use yii\helpers\Html;
use yii\web\ForbiddenHttpException;

/* @var $this yii\web\View */
/* @var $model app\models\Attendance */
if(!$isGuest){
	$this->title = 'Create Attendance';
	$this->params['breadcrumbs'][] = ['label' => 'Attendances', 'url' => ['index']];
	$this->params['breadcrumbs'][] = $this->title;
	?>
	<div class="attendance-create">

	    <h1><?= Html::encode($this->title) ?></h1>

	    <?= $this->render('_form', [
	        'model' => $model,
	    ]) ?>

	</div>
<?php } else throw new ForbiddenHttpException('You are not allowed to access this page.'); ?>
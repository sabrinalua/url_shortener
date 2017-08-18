<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use  yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Shortener */
/* @var $form ActiveForm */
?>
<div class="site-url_shortener_rdr">
	
	<h1>Shortened URL:</h1>
	<?=Html::a($servername.$homeurl.'/'.$key, Url::to($homeurl.'/'.$key,true));?>

</div><!-- site-url_shortener -->

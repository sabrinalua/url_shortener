<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Shortener */
/* @var $form ActiveForm */

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<div class="site-url_shortener">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'url')->input('url')->label('<h2>Enter an url to be shortened</h2>') ?>
        
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'id'=>'btn_submit']) ?>
        </div>
    <?php ActiveForm::end(); ?>

    <?php
    if(Yii::$app->session->hasFlash('key')){
    	echo Yii::$app->session->getFlash('key');
    }
    ?>

</div><!-- site-url_shortener -->
<script>
$(document).ready(function(){
    $("#btn_submit").click(function(){
        console.log("XX");
        // $("input:text").val("Glenn Quagmire");
    });
});
</script>
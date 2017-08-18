<?php

// use yii\helpers\Html;
// use yii\widgets\ActiveForm;
// use  yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Shortener */
/* @var $form ActiveForm */

?>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>



<div class="site-url_shortener_rdr">	
	<form id="w0" action="#" method="post">
        <?='<input type="hidden" id="home" name="home" value="'.$home.'">';?>
        <div class="form-group field-shortener-url required">
				<h1>Enter an url to be shortened</h1>
			<input type="url" id="shortener_url" class="form-control" name="shortener_url" aria-required="true" required="true">
			<div class="help-block"></div>
		</div>        
        <div class="form-group">
            <button type="submit" id="btn_submit" class="btn btn-success">Submit</button>
        </div>
    </form>

</div><!-- site-url_shortener -->
<script>
$(document).ready(function(){
    $("#w0").validate({
  rules: {
    // simple rule, converted to {required:true}
    // compound rule
    shortener_url: {
      required: true,
      url: true
    }
  }
});
    $("#btn_submit").on('click', function(event){
         
         event.preventDefault();
         
        if($('#w0').valid()){
            console.log('XX');
        }

         var home =$('#home').val();
         var bla = $('#shortener_url').val();
         console.log(home);

         var jqxhr = $.get( "http://127.0.0.1/excercise1/web/index.php/api/list?url=localhost/excercise1/web/index.php/ca668caf", function(data) {
            var x = JSON.stringify(data);
            console.log(data.records);
                $.getJSON('https://ipinfo.io', function(data){
                  console.log(data)
                })
            })
          .done(function() {
            // alert( "second success" );
          })
          .fail(function(data) {
            // var x = JSON.stringify(data);
            // alert(x);
          })
          .always(function() {
            // alert( "finished" );
          });
         
        // Perform other work here ...
         
        // Set another completion function for the request above
        jqxhr.always(function() {
          // alert( "second finished" );
        });
    });
});
</script>

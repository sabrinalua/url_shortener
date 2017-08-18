
<html>
<head>
<meta charset="utf-8">
 
</head>
<body>
	<div class="jumbotron">	
		<form id="myform" method="post">
		<?php echo'<input type="hidden" id="home" name="home" value="'.$home.'">';?>

		<div class="form-group field-shortener-url required">
			<p class="lead">Enter an url to be shortened</p>
			<input placeholder="https://" type="url" id="to_shorten" class="form-control"  name="to_shorten" aria-required="true" required="true" size="200">
			<div class="help-block"></div>

			<div style="text-align:center">  
			</br>
			<input type="submit" id="submit_btn" value="Submit" align="center">
			</div>
		</div> 

		</form>
		<div id="result_div" style="display:none;">
			<p class="lead" id="kk">successfully shortened url:</p>
			<textarea align="center" class="form-control" rows="1" cols="1" onclick="this.focus();this.select()" readonly="readonly" id= "result" style="text-align:center;">http://google.com.my</textarea>
			</br>
			<h8 style="color:blue;"> to copy url, select the generated url and hit ctrl/cmd + C</h8>
		</div>
		
		
	</div>

	<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
	<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
	<script>
	// just for the demos, avoids form submit
	jQuery.validator.setDefaults({
	  debug: true,
	  success: "valid"
	});
	$( "#myform" ).validate({
	  rules: {
	    to_shorten: {
	      required: true,
	      url: true
	    }
	  }
	});

	var form = $( "#myform" );
	
	$("#submit_btn").on('click', function(event){
		 var home =$('#home').val();
         var bla = $('#to_shorten').val();
         console.log(home);
         console.log($( "#myform" ).serialize());
         if(form.valid()){
	        var jqxhr = $.post( "http://"+home+"/api/add", $( "#myform" ).serialize(), function(data){
	        	console.log(data);
	        	$("#result_div").css("display", "block");
	        	$("#result").html(data.short);

	        }).done(function( data ) {
	        	// console.log("ok")
	        }).fail(function(data){
	        	alert(data.responseJSON.message)
	        });

         }else{
         	console.log("try again");
         	$("#result_div").css("display", "none");
         }
         
	});
	</script>
</body>
</html>
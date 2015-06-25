<?php
	require('vendor/autoload.php');
	// this will simply read AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY from env vars
	$s3 = Aws\S3\S3Client::factory();
	$bucket = getenv('S3_BUCKET')?: die('No "S3_BUCKET" config var in found in env!');
	$thankyou = "";

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['userfile']) && $_FILES['userfile']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['userfile']['tmp_name'])) {
        // FIXME: add more validation, e.g. using ext/fileinfo
        try {
            // FIXME: do not use 'name' for upload (that's the original filename from the user's computer)
            $upload = $s3->upload($bucket, $_FILES['userfile']['name'], fopen($_FILES['userfile']['tmp_name'], 'rb'), 'public-read');
            $url = $upload->get('ObjectURL');
            
            $thankyou = "Yay :) ".$url;

        } catch(Exception $e) { 
            $thankyou = "Error :(";
        } 
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Samurai Sales</title>


	<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-59967352-1', 'auto');
  ga('send', 'pageview');

	</script>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/custom.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href='http://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>

  </head>
  <body>
    <div class="container-fluid">
	    
	    
	  <div class="row r1-custom">
	  	<div class="col-sm-6">
		  	<div class="r1-left">
			  	<img src="Images/samurai_sales_logo.png" alt="logo" class="logo_image"/> 
			  	<h1 class="text-center r1-logo-header">Samurai Sales</h1>
			  	<h4 class="text-center r1-logo-subheader">Work from home doing lead generation for startups</h4>
		  	</div>
	  	</div>
	  	
	  	<div class="col-sm-6 no-pad">
		  	<div class="r1-right">
			  	<p class="r1-p">We hire talented people to generate b2b leads for 
				  	startups over the phone.</p>
				<div class="r1-item">
					<span class="glyphicon glyphicon-home r1-glyph" aria-hidden="true"></span>
					<p class="r1-p-list"><span class="r1-p-list-bold">Work from home</span> - make calls from your 
						web-browser with our platform.</p>
				</div>
				
				<div class="r1-item">
					<span class="glyphicon glyphicon-time r1-glyph" aria-hidden="true"></span>
					<p class="r1-p-list"><span class="r1-p-list-bold">Flexible hours</span> - tell us your schedule 
						and  we'll send you work.</p>
				</div>
				
				<div class="r1-item">
					<span class="glyphicon glyphicon-fire r1-glyph" aria-hidden="true"></span>
					<p class="r1-p-list"><span class="r1-p-list-bold">Awesome work</span> - collaborate with startups in 
						diverse, exciting markets.</p>
				</div>
				
				<div class="r1-item">
					<span class="glyphicon glyphicon-piggy-bank r1-glyph" aria-hidden="true"></span>
					<p class="r1-p-list"><span class="r1-p-list-bold">Great pay</span> - We pay competitively for 
						talented Sales Samurai.</p>
				</div>
				
		  	</div>
	  	</div>
	  
	  </div>
	    
	  
	  
	  <div class="row button-row">
		<div class="btn-centerer">
			<a href="#join"><button type="button" class="btn btn-primary btn-custom">Join us - become a Sales Samurai</button></a>
		</div>
	  </div>
	  
	  
	  
	  <div class="row" id="join">
		  	<div class="r2-bg">
				<div class="r2-head-container">
				  	<h3 class="text-center r2-header">Join the team</h3>
				  	<p class="text-center r2-header-text">Become a telemarketing Sales Samurai now. Fill in the form below 
					  	and we'll have you generating leads for our clients in no time. Work from home - we'll provide you with all
					  	the tools you need.
				  	</p>
				</div>
				  
				<div class="form-wrap">			  
					  <form>
						  <div class="form-group">
						    <label for="inputPassword" class="control-label">Name</label>
						      <input type="text" class="form-control" id="inputname" placeholder="">
						  </div>
						  
						  <div class="form-group">
						    <label for="inputEmail3" class="control-label">Email</label>
						      <input type="email" class="form-control" id="inputEmail3" placeholder="">
						  </div>
						  
						  <div class="form-group">
						    <label for="inputPhone" class="control-label">Phone</label>
						      <input type="phone" class="form-control" id="inputPhone" placeholder="">
						  </div>
						  		
						  <div class="form-group">
						    <label for="inputAvailability" class="control-label">How many hours can you work each week?</label>
							  <select class="form-control">
								  <option>5-10 hours</option>
								  <option>10-20 hours</option>
								  <option>20-30 hours</option>
								  <option>30-40 hours</option>
							  </select>
						  </div>
						  
						  
						  <div class="form-group">
						    <label for="inputMonths" class="control-label">What's your availability for the next few months?</label>
							  <select class="form-control">
								  <option>I'm available this month</option>
								  <option>I'm available over the next 2 months</option>
								  <option>I'm available over the next 3 months</option>
								  <option>I'm available over the next 6+ months</option>
							  </select>
						  </div>
						  
	
						  <div class="form-group">
						      <label for="inputCV">Upload CV</label>
							  <input type="file" id="CVFile">
						  </div>
						  
						  
						  <div class="form-group">
						      <button type="submit" class="btn btn-primary">Submit</button>
						  </div>
						</form>	  
			  	</div>
		  	  
		    </div>
	  </div>
	  
	  
      
      <div class="row footer">© 2015 All Rights Reserved. Yak Hub Ltd</div>
    </div>
    

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>

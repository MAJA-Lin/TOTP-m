<!DOCTYPE html>
<html lang="en">
	<head>
		<?php ini_set( "display_errors", 0); ?>
		<meta charset="UTF-8">
		<title>Form</title>
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
		<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
		<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php

			include_once 'src/oauth_totp.php';
			include_once 'src/base32.php';
			include_once 'src/phpqrcode.php';


			//Pass from form.html
			/*
			echo $_POST['email']."<br>";
			echo $_POST['seed']."<br>";
			*/

			$EMAIL = trim($_POST['email']);
			$SEED = trim($_POST['seed']);
			$PERIOD = trim($_POST['period']);
			$ALGORITHM = trim($_POST['algorithm']);
			$DIGITS = trim($_POST['digits']);

			// Get the exact time from the server
			$exact_time = microtime(true);
			$rounded_time = floor($exact_time/$PERIOD);

			// Seconds until key expires
			$str_time_to_expire = $exact_time/$PERIOD;
			$array_time_to_expire= explode(".", $str_time_to_expire);
			$erg_time_to_expire = "0.".$array_time_to_expire[1];
			$time_to_expire = $PERIOD - floor($erg_time_to_expire*$PERIOD);

			// Generate TOTP
			$totp_generated=oauth_totp($SEED, $rounded_time, $DIGITS, $ALGORITHM);

			function G_OPT($SEED, $DIGITS, $ALGORITHM, $PERIOD)
			{
				$exact_time = microtime(true);
				$rounded_time = floor($exact_time/$PERIOD);
				$totp_generated=oauth_totp($SEED, $rounded_time, $DIGITS, $ALGORITHM);
				return $totp_generated;
			}

			function G_Time($PERIOD)
			{
				$exact_time = microtime(true);
				$rounded_time = floor($exact_time/$PERIOD);
				$str_time_to_expire = $exact_time/$PERIOD;
				$array_time_to_expire= explode(".", $str_time_to_expire);
				$erg_time_to_expire = "0.".$array_time_to_expire[1];
				$time_to_expire = $PERIOD - floor($erg_time_to_expire*$PERIOD);
				return $time_to_expire;
			}


		 ?>

	</head>
	<body>
		<div data-role="header" data-theme="b">
					<a href="javascript:history.back()" data-role="button"data-icon="forward" class="ui-btn-left" data-theme="a">previous</a>
					<h1>Result of your TOTP</h1>
					<a href="#" data-role="button" data-icon="refresh" class="ui-btn-right" data-theme="a" data-ajax="true">N/T</a>
		</div>

		<div data-role="content" >
			<h3>First number is your OPT, second number is remaining time .</h3>

			<div class="content-primary">
			<!--
				<ul data-role="listview" >
					<li id="otp">
						<?php
							/*
							echo '<h3>Your One-Time Password is "'.$totp_generated.'"<h3><br>';
							echo '<h3>The Password will be unavalible in '.$PERIOD.' seconds<h3><br>';
							*/
						?>
					</li>
					   Save for javascript
					<li id="timing"></li>
				</ul>
			!-->
				<div data-role="content" id="otp">
					<?php
						echo 'Loading..';
						//echo '<h3>Your One-Time Password is "'.$totp_generated.'"<h3><br>';
						//echo '<h3>The Password will be unavalible in '.$PERIOD.' seconds<h3><br>';
					?>
				</div>
				<div data-role="content" id="time">
					<?php
						echo 'Time remaining:';
					?>
				</div>
			</div>
		</div>

		<div data-role="footer" data-position="fixed" data-theme="b">
					<a href="#" data-role="button" data-icon="gear" class="ui-btn-left ui-corner-all ui-shadow" data-theme="b" data-ajax="false">English</a>
					<h4>&copy;Scott Lin</h4>
					<a href="#" data-rel="dialog" data-role="button" data-icon="info" class="ui-btn-right ui-corner-all ui-shadow" data-theme="d">Contact</a>
		</div>




		<script>

			// call your function every 500ms
			setInterval(function(){
				showTOTP();
			}, 500);

			function showTOTP()
			{

				var show = <?php echo G_OPT($SEED, $DIGITS, $ALGORITHM, $PERIOD); ?>;
				var seconds = <?php echo G_Time($PERIOD); ?>;
				//var tmp = document.getElementById("otp");
				document.getElementById("otp").innerHTML = show;
				document.getElementById("time").innerHTML = seconds;
			}

			/*
			function showTOTP()
			{
				// code for IE7+, Firefox, Chrome, Opera, Safari
				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				 }
				 // code for IE6, IE5
				else {
				 	xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				 }

				xmlhttp.onreadystatechange=function()
				 {
					//if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				    	document.getElementById("otp").innerHTML = xmlhttp.responseText;
				    //}
				 }

				xmlhttp.open("GET","totp.php",true);
				xmlhttp.send();
			}
			*/
		</script>
	</body>
</html>
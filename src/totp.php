<?php

	include 'oauth_totp.php';
	include 'base32.php';
	include 'phpqrcode.php';

	//Pass from form.html
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

	echo '<h1>'.$totp_generated."</h1>";
	echo "time to expire: ".$time_to_expire."s";

 ?>
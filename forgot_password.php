<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


	require 'config/database.php';
	
	var_dump($email);
	if (isset($email) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL))
	{
        $email	= $_POST['email'];
	    $token	= bin2hex(openssl_random_pseudo_bytes(16));

		$conn = new PDO("mysql:host=$DB_DSN;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		$sql = "USE ".$DB_NAME;
		$stmt = $conn->prepare("SELECT * FROM users WHERE email=:email");
		$stmt->bindValue(':email', $email);
		$stmt->execute();
		$result = $stmt->fetch();
		if (!$result)
			echo('email does not exist');
		else
		{
			//var_dump($token);
			
			$conn = new PDO("mysql:host=$DB_DSN;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
			$sql = "USE ".$DB_NAME;
			$stmt = $conn->prepare("UPDATE users SET token = :token");
			$stmt->bindParam(':token', $token);
			$stmt->execute();
			// echo "added token\n";
			// echo "$email";
			
			$to			= $email; 
			$subject	= 'Password Reset';
			$message	= 
			"
cant believe you forgot your password, but anyway lets reset it OK:):
http://127.0.0.1:8080/camagru/reset.php?email='$email'&token='$token'
			";
			if (mail($to, $subject, $message))
			{
				echo "email sent\n";
				//header('Location: login.php');
				exit;
			}
			else
				echo "email failed to send\n";
		}			
	}
	$conn = null;
?>


<!doctype html>
<html>
    <head>
        <title>Camagru</title>
        <link href="style.css" rel="stylesheet" type="text/css">
    </head>
<body>
<header>
    <div class="logo-signup">
        <img src="pictures/logo.png">
    </div>
    </header>
    <div class="us" align="center">
    <form class="box" action="reset.php" method="post">
        <h1 align="center">*RESET*</h1>
        <input type="text" placeholder="email" name='email'>
        <input type="submit" name="" value="submit">
    </form>
    </div>
</body>
</html>
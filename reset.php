

<?php
session_start();

echo $_SESSION['Username'] ;

include 'config/database.php';

	try
	{
		if (!empty($_POST['psw_new']) || !empty($_POST['psw_new']) || !empty($_POST['psw_repeat']) || !empty($_POST['psw_repeat']))
		{
			$psw_new		= htmlspecialchars($_POST['psw_new']);
			$psw_repeat		= htmlspecialchars($_POST['psw_repeat']);

		if (!isset($psw_new) || empty($psw_new) || !(strlen($$psw_new) > 6) || (!preg_match('/(?=.*[a-z])(?=.*[0-9]).{6,}/i', $psw_new)) 
			|| (!isset($psw_repeat) || empty($psw_repeat) && !($psw_repeat === $psw_repeat)))
			{
				if (!(strlen($psw_new) > 6))
				{
					echo "! Password length is too short, must be atleast 6 characters long<br>";
				}
				if (!preg_match('/(?=.*[a-z])(?=.*[0-9]).{6,}/i', $psw_new))
				{
					echo "! Passowrd must contain letters and digits<br>";
				}
			}
		else if ((isset($psw_new) && !empty($psw_new) && (strlen($psw_new) > 6) && (preg_match('/(?=.*[a-z])(?=.*[0-9]).{6,}/i', $psw_new))) 
			&& (isset($psw_repeat) && !empty($psw_repeat) && ($psw_new === $psw_repeat)))
		{
			$conn = new PDO("mysql:host=$DB_DSN;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
			$sql = "USE ".$DB_NAME;		
			$conn->exec($sql);
			$stmt = $conn->prepare("SELECT Username = :Username");
			$stmt->bindParam(':Username', $Username);
			// $stmt->bindParam(':token', $token);
			$stmt->execute();
			$user = $stmt->fetch();
			if (!$user)
				die('Could not access credentials through database!');
			else
			{
			
				$stmt = $conn->prepare("UPDATE users SET passwrd = :psw_new");
				$psw_new = password_hash($psw_new, PASSWORD_BCRYPT);
				$stmt->bindParam(':psw_new', $psw_new);
				$stmt->execute();
				
				echo "password changed!";
				exit;		
			}
		 }
		else 
			die('Something went wrong...');
	}
}
	catch(PDOException $e)
	{
		echo $stmt . "<br>" . $e->getMessage();
	}
	$conn = null;
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Sign Up</title>
	<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<header>
        <div class="logo-signup">
        <img src="pictures/logo.png">
        </div>
        <ul class="signup-nav">
            <li><a href="index.php"> HOME </a></li>
            <li class="active"><a href=""> RESET_PASSWORD </a></li>
            <li><a href="login.php"> LOGIN </a></li>
            <li><a href="gallery.php"> GALLERY </a></li>
        </ul>
    </header>

<div class="us" align="center">
    <form class="box" action="" method="post">
        <h1>Enter New Password</h1>
        <input type="password" placeholder="Password" name='psw_new' required>
        <input type="password" placeholder="Password_repeat" name='psw_repeat'required>
		<input type="submit" name="" value="submit">
    </form>
    </div>
	
</body>
</html>
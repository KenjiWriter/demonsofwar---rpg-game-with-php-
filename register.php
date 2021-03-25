<?php
require('config.php');
global $con;
require("Templates/header.php");
if(!empty($_POST['register'])){
	$username = stripslashes($_REQUEST['username']);
	$username = mysqli_real_escape_string($con,$username); 
	$password = stripslashes($_REQUEST['password']);
	$password = mysqli_real_escape_string($con,$password);
	$trn_date = date("Y-m-d H:i:s");

    try {
        // Username
        if(strlen($username) < 5){
            throw new Exception ('Username must be at least 5 characters!');
        }

        if(strlen($username) > 16){
            throw new Exception ('Username must be shorter than 16 characters!');
}

        if(!ctype_alnum($username)){
            throw new Exception ('Username must be only letters and numbers!');
        }
		$query1 = "SELECT * FROM `user` WHERE username='$username'";
		$result1 = mysqli_query($con,$query1);
		$rows = mysqli_num_rows($result1);
				if($rows == 1)
		 {
            throw new Exception ('The name is already taken!');
		 }


        if(strlen($password) < 5){
            throw new Exception ('Password must be at least 5 characters!');
        }

        $password = md5($password);

        $query = "INSERT into `user` (username, password, level, exp, health, max_health, silver_coin, str, dexterity, intelligence, endurance, max_equipped_attacks,equipped_attacks, min_dmg,luck, crit_chance, mana, stamina)
VALUES ('$username', '$password', '1', '0', '100', '100', '15', '1', '1', '1', '1', '4', '1', '20', '1', '0.5', '50', '50')";
        $result = mysqli_query($con,$query);
        if($result){
            echo "Account created! <a href='./'>Login now!</a> <br>";
        }
    }catch (Exception $e){
        $error = $e->getMessage();
    }

}
?>

<!doctype html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Register page</title>
</head>

<body>
	<div class="FormContainer centerDiv">
		<form action="./register" method="POST">
			<?php if(!empty($error)){echo '<p  style="color: #ff0000;">' .$error.'</p>';} ?>
            <label style='width: 6em;'>Username:</label> <input type='text' name="username" /> <br />
            <label style='width: 6em;'>Password:</label> <input type='password' name="password" /> <br />
			<input type="submit" name="register" value="create account" /><br> <a href="./">Already registered? Click to login</a>
		</form>
	</div>
</body>

</html>

<?php 

require("Templates/footer.php");

?>

<?php
error_reporting(E_ALL & ~E_NOTICE);
include('config.php');
include('DatabaseObject.php');
include('DatabaseVars.php');

$database = new DatabaseObject($host, $username, $password, $database);
$con = mysqli_connect("localhost","root","","rpg");

session_start();
if($_SERVER['REQUEST_METHOD'] == "POST")
{
	//Username and Password sent from Form
 $username = mysqli_real_escape_string($con, $_POST['user']);
 $password = mysqli_real_escape_string($con, $_POST['pass']);
 $password = md5($password);
	$query = "SELECT * FROM `user` WHERE username='$username' and password='$password'";
	$result = mysqli_query($con,$query);
	$rows = mysqli_num_rows($result);

 //If result match $username and $password Table row must be 1 row
 if($rows == 1)
 {
     $result_row = mysqli_query($con,$query);
     $row = mysqli_fetch_array($result);
     $_SESSION["user_id"] = $row["id"];
	 header("Location: ./");
 }
 else
 {
 $error = "Invalid Username or Password";
 }
}
require("Templates/header.php");
if (!isset($_SESSION["user_id"])){
?>
<!doctype html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Wenzzi | Login</title>
</head>

<body>
	<div class="FormContainer centerDiv">
		<h1>Login</h1>
		<form action="./" method="post">
			<?php if(!empty($error)){echo '<p  style="color: red;">'.$error.'</p>';} ?>
			<label>Username</label>
			<input type="text" name="user"><br /><br />
			<label>Password</label>
			<input type="password" name="pass"><br /><br />
			<input type="submit" name="submit" value="Login"><br />
			<p><a href="register">New User Register!</a></p>
		</form>
	</div>
</body>

</html>


<?php
} else {
    require ('user.php');
    require ('item.php');
    $player = new User($_SESSION['user_id'], $con);

    $time = time();

    echo "<script src='//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>";
    echo "	  <script>
		function updateUserStatus(){
			jQuery.ajax({
				url:'update_user_status.php',
				success:function(){
					
				}
			});
		}
		
		setInterval(function(){
			updateUserStatus();
		},3000);
	  </script>";
        if($player->level < 80) {
            while ($player->exp >= $player->exp_to_lvl_up) {
                $player->health = $player->max_health;
                $player->level = $player->level + 1;
                $player->stats_points = $player->stats_points + 1;
                $player->exp -= $player->exp_to_lvl_up;
                $player->update();
            }
        }else {

        }


    if (empty($player->attacks) or empty($player->attacks_equipped)){
        $player->attacks_equipped[1] = array();
        $player->update();
    }

    if ($player->health > $player->max_health){
        $player->health = $player->max_health;
    }



    $default_page = 'profile';
 	$pages = array (
       'profile' => array(
	   		'name' => 'Profile',
		    'file' => 'profile.php',
		    'function' => 'Profile'
	   ),
        'inventory' => array(
            'name' => 'Inventory',
            'file' => 'inventory.php',
            'function' => 'inventory'
        ),
        'arena' => array(
            'name' => 'Combat Arena',
            'file' => 'Arena.php',
            'function' => 'arena',
        ),
        'attack_shop' => array(
            'name' => 'Attack shop',
            'file' => 'attackShop.php',
            'function' => 'attackShop',
        ),
        'blacksmith' => array(
            'name' => 'blacksmith',
            'file' => 'blacksmith.php',
            'function' => 'blacksmith',
        ),
        'hospital' => array(
            'name' => 'Hospital',
            'file' => 'hospital.php',
            'function' => 'hospital',
        ),
        'chat' => array(
            'name' => 'Chat',
            'file' => 'Chat.php',
            'function' => 'Chat',
        ),
        'create_monster' => array(
            'name' => 'Create monster',
            'file' => 'monsterpages.php',
            'function' => 'createMonster',
        ),
        'create_item' => array(
            'name' => 'Create item',
            'file' => 'itempages.php',
            'function' => 'createItem',
        ),
        'create_attack' => array(
            'name' => 'Create attack',
            'file' => 'attackpages.php',
            'function' => 'createAttack',
        ),
        'friends' => array(
            'name' => 'friends',
            'file' => 'friends.php',
            'function' => 'friends_list',
        ),

    );
    require("Templates/menu.php");
 	if(!empty($_GET['page'])){
 	    $page = strtolower(trim($_GET['page']));
        if(isset($pages[$page])){
			require($pages[$page] ['file']);
			echo "<p class='pageTitle'>".$pages[$page]['name']."</p>";
            $self_link = '?page='.$page;
			$pages[$page] ['function'] ();
        }else {
			require($pages[$default_page] ['file']);
			echo "<p class='pageTitle'>".$pages[$default_page]['name']."</p>";
            $self_link = '?page='.$default_page;
			$pages[$default_page] ['function'] ();
        }
    } else {
		require($pages[$default_page] ['file']);
		echo "<p class='pageTitle'>".$pages[$default_page]['name']."</p>";
        $self_link = '?page='.$default_page;
		$pages[$default_page] ['function'] ();
    }
}
require("Templates/footer.php");
?>

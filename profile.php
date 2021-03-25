<?php
include("config.php");




function profile() {
    global $system;
    global $player;
    global $con;
    global $self_link;

    //strength point
    if(isset($_POST['point'])){
        try{
            $point_id = $_POST['point_id'];
            if($player->stats_points <= 0){
                throw new Exception ('You dont have enough points!');
            }
        // Add point
        $player->$point_id = $player->$point_id +1;
        $player->stats_points = $player->stats_points - 1;

        if($point_id == "endurance"){
            $player->max_health = $player->max_health+10;
        }
        if($point_id == "luck"){
                $player->crit_chance = $player->crit_chance+0.5;
        }
        if($point_id == "strength"){
                $player->max_stamina = $player->max_stamina+0.5;
        }
        if($point_id == "intelligence"){
                $player->max_mana = $player->max_mana+0.5;
        }
        $player->update();
        $message = "Added +1 $point_id";
        unset($_POST['point']);


        }catch (Exception $e){
            $message = $e->getMessage();
        }
    }


	$label_width = 7.5;
	echo "<div class='centerDiv' style=' width:350px;  text-align: left; '>
	<label style='width: {$label_width}em;'> Nickname: </label> {$player->username} <br><br>
	<label style='width: {$label_width}em;'> health: </label> {$player->health}/{$player->max_health} <br>
	<label style='width: {$label_width}em;'> mana: </label> {$player->max_mana} <br>
	<label style='width: {$label_width}em;'> stamina: </label> {$player->max_stamina} <br><br>
	<label style='width: {$label_width}em;'> Silver coins: </label> {$player->silver_coin} <br><br>
	<label style='width: {$label_width}em;'> level: </label> {$player->level}";if($player->level >= 80){ echo " [MAX] <br>";  }else{echo "<br>";}

	if($player->level >= 80){echo "";}else{
	echo "<label style='width: {$label_width}em;'> experience: </label> {$player->exp}/{$player->exp_to_lvl_up} <br>";}

	echo "<label style='width: {$label_width}em;'> Stat points: </label> {$player->stats_points} <br><br>
	";

    if($player->stats_points > 0){
        if(!empty($message)){echo '<p  style="color: #ff0000;">' .$message.'</p>';}

        echo "    
              <label style='width: {$label_width}em;'> damage: </label> {$player->damage} <br>
              <form action='$self_link' method='post'><label style='width: 6.5em;'>Strength: </label>{$player->strength} <input type='hidden' name='point_id' value='strength'/><input type='submit'  value='+1' name='point'  /><br></form>";
        echo "<form action='$self_link' method='post'><label style='width: 6.5em;'>Intelligence: </label>{$player->intelligence} <input type='hidden' name='point_id' value='intelligence'/><input type='submit'  value='+1' name='point'  /><br></form>";
        echo "<form action='$self_link' method='post'><label style='width: 6.5em;'>Endurance: </label>{$player->endurance} <input type='hidden' name='point_id' value='endurance'/><input type='submit'  value='+1' name='point'  /><br></form>";
        echo "<form action='$self_link' method='post'><label style='width: 6.5em;'>Luck: </label>{$player->luck} <input type='hidden' name='point_id' value='luck'/><input type='submit'  value='+1' name='point'  /><br></form>";
    }else {
        if(!empty($message)){echo '<p  style="color: #ff0000;">' .$message.'</p>';}echo "
    <label style='width: {$label_width}em;'> damage: </label> {$player->damage} <br>
    <label style='width: {$label_width}em;'> Strength: </label> {$player->strength} <br>
	<label style='width: {$label_width}em;'> Intelligence: </label> {$player->intelligence} <br> 
	<label style='width: {$label_width}em;'>Endurance: </label> {$player->endurance} <br> 
	<label style='width: {$label_width}em;'>Luck: </label> {$player->luck} |      <label style='width: {$label_width}em;'> Critical chance: </label> {$player->crit_chance}%  <br> 
	";}


	echo "<br></div>";





}

function profiles() {
    global $con;
    if(empty($_POST["user_id"])){
        header("Location: ?page=profile");
    }else {
        global $time;
        $query_user = "SELECT * FROM `user` WHERE id={$_POST["user_id"]}";
        $result_user = mysqli_query($con, $query_user);
        $row_user = mysqli_fetch_array($result_user);
        $status = 'Offline';
        if($row_user['last_online'] > $time){
            $status = 'Online';
        }

        $label_width = 7.5;
        echo "<div class='centerDiv' style=' width:350px;  text-align: left; '><br>
	<label style='width: {$label_width}em;'> Status: </label> <div id='status'>{$status}</div><br>
	<label style='width: {$label_width}em;'> Nickname: </label> {$row_user["username"]} <br><br>
	<label style='width: {$label_width}em;'> health: </label> {$row_user["health"]}/{$row_user["max_health"]} <br>
	<label style='width: {$label_width}em;'> mana: </label> {$row_user["mana"]} <br>
	<label style='width: {$label_width}em;'> stamina: </label> {$row_user["stamina"]} <br><br>
	<label style='width: {$label_width}em;'> Silver coins: </label> {$row_user["silver_coin"]} <br><br>
	<label style='width: {$label_width}em;'> level: </label> {$row_user["level"]}";if($row_user["level"] >= 80){ echo " [MAX] <br><br>";  }else{echo "<br><br>";}


        echo "
	";
    echo "
    <label style='width: {$label_width}em;'> damage: </label> {$row_user["min_dmg"]} <br>
    <label style='width: {$label_width}em;'> Strength: </label> {$row_user["strength"]} <br>
	<label style='width: {$label_width}em;'> Intelligence: </label> {$row_user["intelligence"]} <br> 
	<label style='width: {$label_width}em;'>Endurance: </label> {$row_user["endurance"]} <br> 
	<label style='width: {$label_width}em;'>Luck: </label> {$row_user["luck"]} |      <label style='width: {$label_width}em;'> Critical chance: </label> {$row_user["crit_chance"]}%  <br> 
	";


        echo "<br></div>";
    }
}

<?php

/** Hospital.php
 *  Place where u can recover your lost health
 */

function Hospital() {


    // Display
    global $con;
    global $database;
    global $self_link;
    global $player;

    if(!empty($_SESSION['monster_id'])){
        $error_message = "You can't go to hospital right now!";
        $_SESSION['Error_message'] = $error_message;
        header("location: ?page=arena");
    }

        if($player->level <= 10){
            $heal_cost = 15;
        }
        if($player->level >= 10){
            $heal_cost = $player->level;
        }


        $healing = array(
            'small' => array(
                'cost' => $heal_cost,
                'health' => 30,
            ),
            'medium' => array(
                'cost' => round($heal_cost*1.8,0),
                'health' => 50,
            ),
            'large' => array(
                'cost' => round($heal_cost*3,0),
                'health' => 100,
            ),
        );



    foreach ($healing as $name => $kit){
       if(isset($_POST[$name])){
           try{
            if($player->silver_coin <= $kit['cost']){
                throw new Exception ('You dont have enough coins!');
            }
               if($player->health >= $player->max_health){
                   throw new Exception ('You already have full health!');
               }

               //Update data
               $player->health = $kit['health']/100*$player->max_health;
               $player->silver_coin -= $kit['cost'];
               if($player->health > $player->max_health){
                   $player->health = $player->max_health;
               }
               $message = "Med kit successfully purchased!";
               $player->update();
           }catch (Exception $e){
               $message = $e->getMessage();
           }
       }

    }


    echo "<div class='FormContainer centerDiv' style='text-align: left; width: 400px;'> 
            <form action='$self_link' method='POST'>
          ";
    if(!empty($message)){echo '<p  style="color: #ff0000;">' .$message.'</p>';}
            foreach ($healing as $name => $kit){
                echo "<label style='width: 20em;'>".ucwords($name) . " health kit (Price: {$kit['cost']} coins, +{$kit['health']}% HP)</label>
                        <input type='submit' name='$name' value='Purchase'><br><br>";

            }
    if(!isset($_SESSION['expire']))
    {
        $_SESSION['expire'] = time()+250;
    }
    $time_remaining = $_SESSION['expire'] - time();

    $time = round(abs($time_remaining) / 60, 2) . ' min';
    echo "<label style='width: 20em;'>Free health recovery ".$time."</label>";
    if ($time <= 1) {
        unset($time_remaining);
        unset($time);
        echo "<form action='$self_link' method='post'><input type='submit' value='Claim' name='free_heal' /></form>";
    }
            echo "</form>           
          </div>";
}
?>

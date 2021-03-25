<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
/** Arena.php
 *
 * Arena for fighting vs monsters
 *
 */

function arena() {
    global $con;
    global $player;
    global $self_link;


    // Process monster choice
    try {
        if (empty($_SESSION['monster_id']) && !empty($_POST['fight'])) {
            if (empty(($_POST['monster_id']))) {
                header("location: ?page=arena");
                throw new Exception();
            }
            $monster_id = (int)$_POST['monster_id'];
            require('monster.php');
            try {
                $monster = new Monster($monster_id, $con);
                $_SESSION['monster_id'] = $monster_id;


            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }catch (Exception $e) {
        echo $e->getMessage();
    }
    function battle($player, $opponent) {
        global $con;
        global $self_link;

        $result = mysqli_query($con,"SELECT * FROM attacks");
        $attacks = array();
        while ($attack = mysqli_fetch_array($result)) {
            $attacks[$attack['id']] = $attack;
        }

        $winner = false;
        $combat_display = '';

        //Mana recovery
        if(!empty($_POST['mana_recovery'])) {
            try {
                if ($player->mana > $player->max_mana) {
                    $player->mana = $player->max_mana;
                    throw new Exception ("You already have full mana");
                } else {
                    $player->mana += 50 / 100 * $player->max_mana;
                    if($player->mana > $player->max_mana){
                        $player->mana = $player->max_mana;
                        throw new Exception ("You already have full stamina");
                    }
                }

                $attack = $opponent->attacks[array_rand($opponent->attacks)];

                $opponent_damage = $attack['power'] * 3;
                if (!empty($attack['type']) == "melee") ;
                {
                    $opponent_damage += 10 + $opponent->strength;
                }
                if (!empty($attack['type']) == "magic") ;
                {
                    $opponent_damage += 10 + $opponent->intelligence;
                }
                $opponent_min_dmg = $attack['power'] / 100 * $opponent->min_dmg;
                $opponent_damage = rand($opponent_min_dmg, $opponent_min_dmg * 1.5);

                $combat_display .=  $player->username. " regenerates mana" ;
                $combat_display .= '<br/><br />' . $opponent->name . ' ' . $attack['combat_text'] . '<br>' . $opponent->name . ' deals ' . $opponent_damage . ' damage!';

                // Apply damage
                if ($opponent->health > 0) {
                    $player->health -= $opponent_damage;
                }
                // Check for winner
                if ($player->health <= 0) {
                    $player->health = 0;
                    $winner = "opponent";
                    $combat_display .= "<br> " . $opponent->name . " won the battle! <br>";
                }


                $player->update();
                $player->update_stamina_mana();
                $opponent->update();

            }catch (exception $e){
                echo $e->getMessage();
            }
        }

        //Stamina recovery
        if(!empty($_POST['stamina_recovery'])) {
            try {
                if ($player->stamina > $player->max_stamina) {
                    $player->stamina = $player->max_stamina;
                    throw new Exception ("You already have full stamina");
                } else {
                    $player->stamina += 50 / 100 * $player->max_stamina;
                    if($player->stamina > $player->max_stamina){
                        $player->stamina = $player->max_stamina;
                        throw new Exception ("You already have full stamina");
                    }
                }

                $attack = $opponent->attacks[array_rand($opponent->attacks)];

                $opponent_damage = $attack['power'] * 3;
                if (!empty($attack['type']) == "melee") ;
                {
                    $opponent_damage += 10 + $opponent->strength;
                }
                if (!empty($attack['type']) == "magic") ;
                {
                    $opponent_damage += 10 + $opponent->intelligence;
                }
                $opponent_min_dmg = $attack['power'] / 100 * $opponent->min_dmg;
                $opponent_damage = rand($opponent_min_dmg, $opponent_min_dmg * 1.5);

                $combat_display .=  $player->username. " regenerates stamine" ;
                $combat_display .= '<br/><br />' . $opponent->name . ' ' . $attack['combat_text'] . '<br>' . $opponent->name . ' deals ' . $opponent_damage . ' damage!';

                // Apply damage
                if ($opponent->health > 0) {
                    $player->health -= $opponent_damage;
                }
                // Check for winner
                if ($player->health <= 0) {
                    $player->health = 0;
                    $winner = "opponent";
                    $combat_display .= "<br> " . $opponent->name . " won the battle! <br>";
                }


                $player->update();
                $player->update_stamina_mana();
                $opponent->update();

            }catch (exception $e){
                echo $e->getMessage();
            }
        }

        //Attack
        if(!empty($_POST['attack'])){
            try {
                if(!empty($_POST['attack_id'])) {
                    $attack_id = $_POST['attack_id'];
                    if (empty($attack_id)) {
                        throw new Exception("Invalid attack!");
                    }

                    if (!isset($attacks[$attack_id])) {
                        throw new Exception("Invalid attack!");
                    }
                    if (!isset($_POST['attack_id'])) {
                        throw new Exception("Select attack!");
                    }


                    if (!isset($player->attacks_equipped[$attack_id])) {
                        throw new Exception("You don't have this attack!");
                    }


                    // Run turn
                    $attack_id = $_POST['attack_id'];
                    // Calc player damage
                    $type = $attacks[$attack_id]['Type'];

                    error_reporting(E_ALL & ~E_NOTICE);
                    $player_min_damage = 0;

                    if($attacks[$attack_id]['Type'] = 'melee'){
                        if($player->stamina+$attacks[$attack_id]['stamina_cost'] <= $attacks[$attack_id]['stamina_cost']){
                            throw new Exception ("Not enough SP");
                        }
                        $player_basic_dmg = $player->strength/2;
                        $player->stamina -= $attacks[$attack_id]['stamina_cost'];
                        $energy_type='stamina';
                    }

                    if($attacks[$attack_id]['Type'] = 'magic') {
                        if($player->mana <= $attacks[$attack_id]['mana_cost']){
                            throw new Exception ("Not enough MP");
                        }
                        $player_basic_dmg = $player->intelligence/2;
                        $player->mana -= $attacks[$attack_id]['mana_cost'];
                        $energy_type='mana';
                    }



                    $crit = rand(1,100);
                    if($crit <= $player->crit_chance){
                        $player_min_dmg = $attacks[$attack_id]['Combat_power'] / 100 * $player->damage;
                        $player_min_dmg = $player_min_dmg+$player_basic_dmg;
                        $player_damage = rand( $player_min_dmg*2, $player_min_dmg * 3.0);
                        $combat_display .= $player->username . ' ' . $attacks[$attack_id]['Combat_text'] . '<br />' . $player->username . ' deals ' . $player_damage . ' <b>Critical</b> damage!';

                    }else {
                        $player_min_dmg = $attacks[$attack_id]['Combat_power'] / 100 * $player->damage;
                        $player_min_dmg = $player_min_dmg+$player_basic_dmg;
                        $player_damage = rand($player_min_dmg, $player_min_dmg*1.5);
                        $combat_display .= $player->username . ' ' . $attacks[$attack_id]['Combat_text'] . '<br />' . $player->username . ' deals ' . $player_damage . ' damage!';
                    }





                    // Calc opponent damage
                    $attack = $opponent->attacks[array_rand($opponent->attacks)];

                    $opponent_damage = $attack['power'] * 3;
                    if (!empty($attack['type']) == "melee") ;
                    {
                        $opponent_damage += 10 + $opponent->strength;
                    }
                    if (!empty($attack['type']) == "magic") ;
                    {
                        $opponent_damage += 10 + $opponent->intelligence;
                    }
                    $opponent_min_dmg = $attack['power'] / 100 * $opponent->min_dmg;
                    $opponent_damage = rand($opponent_min_dmg, $opponent_min_dmg * 1.5);

                    $combat_display .= '<br/><br />' . $opponent->name . ' ' . $attack['combat_text'] . '<br>' . $opponent->name . ' deals ' . $opponent_damage . ' damage!';

                    // Apply damage
                    $opponent->health -= $player_damage;
                    if ($opponent->health > 0) {
                        $player->health -= $opponent_damage;
                    }
                    // Check for winner
                    if ($opponent->health <= 0) {
                        $opponent->health = 0;
                        $winner = 'player';
                        $combat_display .= "<br> You win! <br>";
                    } else if ($player->health <= 0) {
                        $player->health = 0;
                        $winner = "opponent";
                        $combat_display .= "<br> " . $opponent->name . " won! <br>";
                    }


                    $player->update();
                    $player->update_stamina_mana();
                    $opponent->update();
                }else {
                    echo "Select attack!";
                }
            }catch (exception $e){
                echo $e->getMessage();
            }
        }else {
        }


        // Display
        if(!empty($_SESSION['Error_message'])){
            $error_message = $_SESSION['Error_message'];
            echo '<p  style="color: #ff0000;">' . $error_message . '</p>';
            unset($_SESSION['Error_message']);
        }
        echo "<table class='centerDiv' style='width: 500px; border: 2px solid rgb(0,0,0);'>
               <tr>
               <th style='width: 50%;'>$player->username</th>
               <th style='width: 50%;'>$opponent->name</th>
               </tr>               
               <tr>
                <td class='center' style='width: 50%;'> HP: $player->health/$player->max_health <br><label style='width: 3em;'>MP:</label>  $player->mana/$player->max_mana<br><label style='width: 3em;'>SP:</label>  $player->stamina/$player->max_stamina</td>
                <td class='center' style='width: 50%;'>HP: $opponent->health/$opponent->max_health</td>
               </tr>";

        // Attack text
        if($combat_display) {
            echo "<tr><td colspan='2'>". $combat_display ."</td></tr>";
        }

        // Move prompt
        echo "<tr><td colspan='2'>";
        if(is_array($player->attacks_equipped)) {
            echo "<form action='$self_link' method='post'>";
            foreach ($player->attacks_equipped as $id => $attack) {
                echo "<input type='radio' name='attack_id' value='$id' />". $attacks[$id]['name']."<br>";
            }
            echo "<input type='submit' value='Attack' name='attack' />";
            echo "  <input type='submit' value='Mana recovery' name='mana_recovery' />";
            echo "  <input type='submit' value='Stamina recovery' name='stamina_recovery' />";
            echo "</form>";
        } else {
            unset($_SESSION['monster_id']);
            unset($_SESSION['monster_health']);
            unset($player->monster_que);
            unset($_SESSION['mana']);
            unset($_SESSION['stamina']);
        }
        echo "</td></tr></table>";

            return $winner;


    }

    // Fight opponent
    if(isset($_SESSION['monster_id'])){
        if(!isset($monster)) {
            require('monster.php');
            $monster = new Monster($_SESSION['monster_id'], $con);
        }
        $winner = battle($player, $monster);
        if ($winner) {
            if($winner == 'player'){
                echo "Drop: ";
                foreach ($monster->drops as $name => $drop){
                    $item_id = $drop['id'];
                    $item = new Item($item_id, $con);
                        $chance = rand(1,100);
                        if($chance <= $drop['chances']){
                            $amount = rand($drop['amount'],$drop['max_amount']);
                            echo $item->name." x$amount, ";
                            if(empty($player->items[$item->id])) {
                                $count = 0+$amount;
                            }else{
                                foreach ($player->items[$item->id] as $player_item) {
                                    $count = $player_item+$amount;
                                }
                            }
                            $player->items[$item->id] = array('count' => $count);
                        }else{
                            echo "";
                        }
                }

                $money_gain = mt_rand($monster->level *4, $monster->level *5);
                $exp_gain = mt_rand($monster->level *4, $monster->level*5);
                $player->silver_coin = $player->silver_coin + $money_gain;
                $player->exp = $player->exp + $exp_gain;
                $player->update();
                echo "<div class='center'>You gain {$money_gain} coins and {$exp_gain} exp!</div>";
            } elseif ($winner == 'opponent'){

            }
            unset($_SESSION['monster_id']);
            unset($_SESSION['monster_health']);
            unset($player->monster_que);
            unset($_SESSION['mana']);
            unset($_SESSION['stamina']);
        }else {
        }
    }
    // Display select form
    if(!isset($_SESSION['monster_id']) && empty($_POST['fight'])) {


        if(empty($player->monster_que)) {
            $result = mysqli_query($con, "SELECT * FROM monsters WHERE level>=$player->level-15 AND level<=$player->level+15 ORDER BY RAND() LIMIT 1");

            echo "
        <div class='FormContainer centerDiv' style='width: 350px;' >
        <form action='$self_link' method='post'> ";
            while ($monsters = mysqli_fetch_array($result)) {
                $player->monster_que = $monsters['id'];
                $player->update();
                if ($monsters['level'] <= $player->level - 16) {
                    $level_difference = $monsters['level'] + $player->level - 16;
                }

                echo "
                   <input type='radio' name='monster_id' value='{$monsters['id']} '/>" . $monsters['name'] . ' [LVL ' . $monsters['level'] . ']' . "<br>";
            }
        }else{
            $result = mysqli_query($con, "SELECT * FROM monsters WHERE id=$player->monster_que");

            echo "
            <div class='FormContainer centerDiv' style='width: 350px;' >
            <form action='$self_link' method='post'> ";
            while ($monsters = mysqli_fetch_array($result)) {
                if ($monsters['level'] <= $player->level - 16) {
                    $level_difference = $monsters['level'] + $player->level - 16;
                }

                echo "
                   <input type='radio' name='monster_id' value='{$monsters['id']} '/>" . $monsters['name'] . ' [LVL ' . $monsters['level'] . ']' . "<br>";
            }
        }




        echo "<input type='submit' name='fight' value='Fight!'>
        </form>
        </div>
        
        
        ";
    }

}

<?php

/** Blacksmith.php
 *
 *  Page where player can buy items
 *
 */

function blacksmith() {
    global $con;
    global $database;
    global $self_link;
    require ('config.php');

    // Fetch attack data
    $result = mysqli_query($con,"SELECT * FROM items");
    $items = array();
    while ($attack = mysqli_fetch_array($result)) {
        $items[$attack['id']] = $attack;
    }

    if(!empty($_POST['buy'])) {
        global $player;
        $attack_id = (int)$_POST['attack_id'];

        try {
            global $player;
            if(!isset($items[$attack_id])){
                throw new Exception("Invalid item!");
            }
            if(isset($player->items[$attack_id])){
                throw new Exception("You already have this item!");
            }
            if($player->silver_coin < $items[$attack_id]['purchase_cost']){
                throw new Exception("You don't have enough silver to buy this item!");
            }

            //Check the quantity
            if(empty($player->items[$attack_id])) {
                $count = 1;
            }else{
                foreach ($player->items[$attack_id] as $player_item) {
                    $count = $player_item+1;
                }
            }
            $player->items[$attack_id] = array('count' => $count);
            // Purchase technique
            $player->silver_coin -= $items[$attack_id]['purchase_cost'];
            $player->update();
            echo "item purchased!";
            foreach ($player->items[$attack_id] as $player_item) {
                echo $player_item;
            }

        }catch (Exception $e) {
            $error = $e->getMessage();
        }
    }

    // Display form
    echo "<table class='centerDiv' style='width: 500px;'>
       <tr>
       <th style='width: 30%;'>Name</th>
       <th style='width: 20%;'>Type</th>
       <th style='width: 25%;'>Required level</th>
       <th style='width: 35%;'>Price</th>
       <th style='width: 15%;'>&nbsp;</th>
       </tr>
";
    if(!empty($error)){echo '<p  style="color: #ff0000;">' .$error.'</p>';}

    foreach ($items as $id => $attack) {
        if (isset($player->items[$id])) {
            continue;}

        echo "<tr> 
            <td>{$attack['name']}</td>
            <td>{$attack['type']}</td>
            <td>{$attack['required_level']}</td>
            <td>{$attack['purchase_cost']}</td>
            <td>
             <form action='$self_link' method='POST'>
                <input type='hidden' name='attack_id' value='{$id}'/>
                <input type='submit' name='buy' value='Buy'>
                </form>
             </td>
             </tr> ";
    }

    echo "</table>";

    // Chest system
    echo "<form action='$self_link' method='POST'> 
    


</form>";

}
<?php
/** attackShop.php
 *
 * Shop for buying weapon and spells
 *
 */

function attackShop(){
    global $con;
    global $database;
    global $self_link;
    require ('config.php');

    // Fetch attack data
    $result = mysqli_query($con,"SELECT * FROM attacks");
    $attacks = array();
    while ($attack = mysqli_fetch_array($result)) {
        $attacks[$attack['id']] = $attack;
    }

    if(!empty($_POST['buy'])) {
        global $player;
        $attack_id = (int)$_POST['attack_id'];

        try {
            global $player;
            if(!isset($attacks[$attack_id])){
                throw new Exception("Invalid attack!");
            }
            if(isset($player->attacks[$attack_id])){
                throw new Exception("You already have this attack!");
            }
            if($player->silver_coin < $attacks[$attack_id]['Purchase_cost']){
                throw new Exception("You don't have enough silver to buy this attack!");
            }

            // Purchase technique
            $player->silver_coin -= $attacks[$attack_id]['Purchase_cost'];
            $player->attacks[$attack_id] = array();
            $player->update();
            echo "Attack purchased!";

        }catch (Exception $e) {
            $error = $e->getMessage();
        }
    }

    // Display form
    echo "<table class='centerDiv' style='width: 500px;'>
       <tr>
       <th style='width: 30%;'>Name</th>
       <th style='width: 20%;'>Type</th>
       <th style='width: 35%;'>Price</th>
       <th style='width: 15%;'>&nbsp;</th>
       </tr>
";
    if(!empty($error)){echo '<p  style="color: #ff0000;">' .$error.'</p>';}
    foreach ($attacks as $id => $attack) {
        if(isset($player->attacks[$id])) {
            continue;
        }

        if($attack['Purchase_cost'] <= 0){
            continue;
        }

        echo "<tr> 
            <td>{$attack['name']}</td>
            <td>{$attack['Type']}</td>
            <td>{$attack['Purchase_cost']}</td>
            <td>
             <form action='$self_link' method='POST'>
                <input type='hidden' name='attack_id' value='{$id}'/>
                <input type='submit' name='buy' value='Buy'>
                </form>
             </td>
             </tr> ";
    }

            echo "</table>";

}
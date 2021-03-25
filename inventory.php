<?php
/** Inventory.php
 *
 * Page where u can see what items you have and equip items and add skill to your deck
 *
 */

function inventory() {
    global $player;
    global $con;
    global $self_link;


    // Equip skill
    if(isset($_POST['equip'])){
        try{
            if($player->equipped_attacks >= $player->max_equipped_attacks) {
                throw new Exception ("You cannot have more attacks than $player->max_equipped_attacks");
            }
            $attack_id = (int)$_POST['attack_id'];
            $player->attacks_equipped[$attack_id] = array();
            $equipped_attacks = $player->equipped_attacks + 1;
            $player->equipped_attacks = $equipped_attacks;
            unset($player->attacks[$attack_id]);
            unset($_POST[$equipped_attacks]);
            $player->update();
            header("location: ?page=inventory");

        }catch (Exception $e){
            echo $e->getMessage()."<br>";
        }

    }



    // Unequip skill
    if(isset($_POST['unequip'])){
        try{
            if($player->equipped_attacks < 2){
                throw new Exception ("you must have at least 1 attack!");
            }
            $attack_id = (int)$_POST['attack_id'];
            $player->attacks[$attack_id] = array();
            $player->equipped_attacks = $player->equipped_attacks - 1;
            unset($player->attacks_equipped[$attack_id]);
            $player->update();
            header("location: ?page=inventory");

            echo "Attack unequipped!";
        }catch (Exception $e){
            echo $e->getMessage()."<br>";
        }
    }

    // Equip item
    if(isset($_POST['equip_item'])){
        $item_id = (int)$_POST['item_id'];
        $item = new Item($item_id, $con);
        $type = $item->type;
        try{


                if($item->required_level > $player->level){
                    throw new Exception ("Your level is too low");
                }
                if(empty($player->$type)){
                    $player->$type = $item->id;
                    $player->damage = $player->damage + $item->damage;
                    $player->strength = $player->strength + $item->strength;
                    if($item->strength > 0){
                        $player->max_stamina = $player->max_stamina+0.5*$item->strength;
                    }
                    $player->intelligence = $player->intelligence + $item->intelligence;
                    if($item->intelligence > 0){
                        $player->max_mana = $player->max_mana+0.5*$item->intelligence;
                    }
                    $player->endurance = $player->endurance + $item->endurance;
                    if($item->endurance > 0){
                        $player->max_health = $player->max_health+$item->endurance*10.00;
                    }
                    $player->luck = $player->luck+$item->luck;
                    if($item->luck > 0){
                        $player->crit_chance = $player->crit_chance+0.5*$item->luck;
                    }
                    foreach ($player->items[$item->id] as $player_item) {
                        $count = $player_item;
                    }
                    if($count <= 1){
                        unset($player->items[$item_id]);
                    } if($count >= 2) {
                        $count -= 1;
                        $player->items[$item_id] = array('count' => $count);
                    }
                    $player->update();
                    header("location: ?page=inventory");

                }else {
                    throw new Exception ("First you need unequip your $type!");
                }

        }catch (Exception $e){
            echo $e->getMessage()."<br>";
        }
    }

    // Unequip item
    if(isset($_POST['unequip_item'])){
        $item_id = (int)$_POST['item_id'];
        $item = new Item($item_id, $con);
        $type = $item->type;
        if($item->type = $type) {
            foreach ($player->items[$item->id] as $player_item) {
                $count = $player_item;
            }
            if ($count >= 1){
                $count += 1;
            }
            if($count <= 1){
                $count=1;
            }
            $player->items[$item_id] = array('count' => $count);
            $player->damage = $player->damage - $item->damage;
            $player->strength = $player->strength - $item->strength;
            $player->intelligence = $player->intelligence - $item->intelligence;
            $player->endurance = $player->endurance - $item->endurance;
            $player->luck = $player->luck - $item->luck;
            if($item->luck > 0){
                $player->crit_chance = $player->crit_chance-$item->luck*0.5;
            }
            if($item->endurance > 0){
                $player->max_health = $player->max_health-$item->endurance*10.00;
            }
            if($item->strength > 0){
                $player->max_stamina = $player->max_stamina-$item->strength*0.5;
            }
            if($item->intelligence > 0){
                $player->max_mana = $player->max_mana-$item->intelligence*0.5;
            }
            unset($player->$type);
            $player->update();
            header("location: ?page=inventory");
        }
    }

    // Sell item
    if(isset($_POST['sell_item'])){
        $item_id = (int)$_POST['item_id'];
        $item = new Item($item_id, $con);
        try{
            foreach ($player->items[$item->id] as $player_item) {
                $count = $player_item;
            }
            if(!isset($count)){
                throw new Exception ("You dont have more this item!");
            }
            if($count <= 1){
            unset($player->items[$item_id]);
        } else {
            $count -= 1;
            $player->items[$item_id] = array('count' => $count);
        }
        $player->silver_coin = $player->silver_coin + $item->price/2;
        unset($_POST['sell_item']);
        $player->update();
        echo "Sold $item->name for ".$item->price/2 ." silver coins! <br>";
        }catch (Exception $e){
            echo $e->getMessage()."<br>";
        }
    }

    // Sills owned (Display)
    echo "<label style='width: 8em;'> Your Attacks: </label> ";
    $result = mysqli_query($con,"SELECT * FROM attacks");
    $attacks = array();
    while ($attack = mysqli_fetch_array($result)) {
        $attacks[$attack['id']] = $attack;
    }
    foreach ($player->attacks as $id => $attack) {
        global $self_link;
        echo "<form action='$self_link' method='post'>{$attacks[$id]['name']} <input type='hidden' name='attack_id' value='{$id}'/><input type='submit'  value='Equip' name='equip'  /></form>";
    }echo "<br>";

    // Skills equipped
    echo "<br><label style='width: 8em;'> Your equipped Attacks ({$player->equipped_attacks}/$player->max_equipped_attacks) : </label> ";
    foreach ($player->attacks_equipped as $id => $attack) {
        global $self_link;
        echo "<form action='$self_link' method='post'>{$attacks[$id]['name']}<input type='hidden' name='attack_id' value='{$id}'/><input type='submit'  value='Unequip' name='unequip'  /></form>";
    }echo "<br>";




    // Items (Display)
    $result_items = mysqli_query($con,"SELECT * FROM items");
    $items = array();
    while ($attack = mysqli_fetch_array($result_items)) {
        $items[$attack['id']] = $attack;
    }
    echo "<label style='width: 8em;'> Your items: </label> ";
        foreach ($player->items as $id => $attack) {
            foreach ($player->items[$id] as $player_item) {
                $count = $player_item;
            }
                echo "<form action='$self_link' method='post'><input type='hidden' name='item_id' value='{$id}'/> {$items[$id]['name']}"; if($items[$id]['item_type'] == 'equipment'){echo "[lvl. {$items[$id]['required_level']}]";} if($count>1){echo " x$count ";}  if($items[$id]['item_type'] == 'equipment'){echo "<input type='submit' value='Equip' name='equip_item'  /> ";} echo "<input type='submit'  value='Sell' name='sell_item'  /></form>";
        }
    echo "<br><br>";
    echo "equipment: <br>";
    // items equipped
        //Weapon
        if (empty($player->weapon)) {
            echo "Weapon: empty <br>";
        } else {
            $item_id = (int)$player->weapon;
            $item = new Item($item_id, $con);
            echo "Weapon: " . $item->name ." (";
            if ($item->damage > 0){
                echo  "dmg: +".$item->damage." ";
            }

            if ($item->strength > 0){
                echo  "str: +".$item->strength." ";
            }
            if ($item->intelligence > 0){
                echo  "int: +".$item->intelligence." ";
            }
            if ($item->endurance > 0){
                echo  "endurance: +".$item->endurance." ";
            }

            echo ")<form action='$self_link' method='post'><input type='hidden' name='item_id' value='$player->weapon'/> <input type='submit' value='unequip' name='unequip_item'></form>";
        }
        //armor
        if (empty($player->armor)) {
        echo "Armor: empty <br>";
    } else {
        $item_id = (int)$player->armor;
        $item = new Item($item_id, $con);
        echo "Armor: " . $item->name ." (";
            if ($item->strength > 0){
                echo  "str: +".$item->strength." ";
            }
            if ($item->intelligence > 0){
                echo  "int: +".$item->intelligence." ";
            }
            if ($item->endurance > 0){
                echo  "endurance: +".$item->endurance." ";
            }
            if ($item->luck> 0){
                echo  "luck: +".$item->luck." ";
            }

            echo ") <form action='$self_link' method='post'><input type='hidden' name='item_id' value='$player->armor'/> <input type='submit' value='unequip' name='unequip_item'></form>";
    }
        //talisman
        if (empty($player->talisman)) {
        echo "Talisman: empty <br>";
    } else {
            $item_id = (int)$player->talisman;
        $item = new Item($item_id, $con);
        echo "Talisman: " . $item->name ." (";
            if ($item->strength > 0){
                echo  "str: +".$item->strength." ";
            }
            if ($item->intelligence > 0){
                echo  "int: +".$item->intelligence." ";
            }
            if ($item->endurance > 0){
                echo  "endurance: +".$item->endurance." ";
            }
            if ($item->luck> 0){
                echo  "luck: +".$item->luck." ";
            }

            echo ") <form action='$self_link' method='post'><input type='hidden' name='item_id' value='$player->talisman'/> <input type='submit' value='unequip' name='unequip_item'></form>";
    }
        //ring
        if (empty($player->ring)) {
        echo "Ring: empty <br>";
    } else {
            $item_id = (int)$player->ring;
        $item = new Item($item_id, $con);
        echo "Ring: " . $item->name ." (";
            if ($item->strength > 0){
                echo  "str: +".$item->strength." ";
            }
            if ($item->intelligence > 0){
                echo  "int: +".$item->intelligence." ";
            }
            if ($item->endurance > 0){
                echo  "endurance: +".$item->endurance." ";
            }
            if ($item->luck> 0){
                echo  "luck: +".$item->luck." ";
            }

            echo ") <form action='$self_link' method='post'><input type='hidden' name='item_id' value='$player->ring'/> <input type='submit' value='unequip' name='unequip_item'></form>";
    }

}
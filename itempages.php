<?php

/** Itempages.php
 *
 * Page where u can add new item
 *
 */

function createItem()
{
    global $database;
    global $player;

    global $self_link;
    global $con;
    require("config.php");

    require("monster.php");

    if (!empty($_POST["Create_item"])) {
        try {
            $name = $database->clean($_POST['name']);
            $level = (int)$_POST['level'];
            $strength = (int)$_POST['str'];
            $intelligence = (int)$_POST['intelligence'];
            $endurance = (int)$_POST['endurance'];
            $Purchase_cost = (int)$_POST['purchase_cost'];
            $damage = (int)$_POST['damage'];
            $luck = (int)$_POST['luck'];
            $type = $database->clean($_POST['Type']);
            $item_type = $database->clean($_POST['Item_type']);
            $rare = $database->clean($_POST['rare']);

            if (!$name) {
                throw new Exception("Insert a item name");
            }

            if ($level < 1) {
                throw new Exception("Invalid level");
            }
            if ($strength < 0) {
                throw new Exception("Invalid strength");
            }
            if ($intelligence < 0) {
                throw new Exception("Invalid intelligence");
            }
            if ($endurance < 0) {
                throw new Exception("Invalid endurance");
            }
            if ($Purchase_cost <= 0.5) {
                throw new Exception("Invalid Purchase cost");
            }
            if ($damage < 0) {
                throw new Exception("Invalid damage");
            }
            if ($luck < 0) {
                throw new Exception("Invalid luck");
            }

            // insert new item to database
            $query = "INSERT into `items` (name, required_level, strength, intelligence, endurance, type, rare, item_type, damage,purchase_cost, luck)
        VALUES ('$name', '$level', '$strength', '$intelligence', '$endurance', '$type', '$rare', $item_type, '$damage','$Purchase_cost', '$luck')";
            $result = mysqli_query($con, $query);
            if ($result) {
                echo "New item created!";
            }


        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }


    // Display form
    $label_width = 6;
    echo "
<div style='text-align: left;' class='FormContainer centerDiv'>
    <form  action='$self_link' method='post'>";
    if (!empty($error)) {
        echo '<p  style="color: #ff0000;">' . $error . '</p>';
    }
    echo
    "
          <label style='width: 6.5em;'>Item name:</label> <input type='text' placeholder='Name' name='name' autocomplete='off' /><br>
          <label style='width: 6em;'>Required level:</label> <input type='number' placeholder='Required level' name='level' /><br>
          <label style='width: 6em;'>Item stretngth:</label>  <input type='number' placeholder='Strength' name='str' /><br>
          <label style='width: 6em;'>Item intelligence:</label> <input type='number' placeholder='Intelligence' name='intelligence' /><br>
          <label style='width: 6em;'>Item endurance:</label> <input type='number' placeholder='Endurance' name='endurance' /><br><br>
          <label style='width: 6em;'>Item luck:</label> <input type='number' placeholder='Luck' name='luck' /><br>
          <label style='width: 6em;'>Item damage:</label> <input type='number' placeholder='Damage' name='damage' /><br>
          <label style='width: 6em;'>Purchase cost:</label> <input type='number' placeholder='Purchase cost' name='purchase_cost' /><br><br>
          <label style='width: 6em;'>Item type:</label>  
          <select name='Type'>
            <option value='weapon'>Weapon</option>
            <option value='armor'>Armor</option>
            <option value='talisman'>Talisman</option>
            <option value='ring'>Ring</option>
            <option value='none'>None</option>
          </select><br> <br>       
           <label style='width: 6em;'>Specific item type:</label>  
          <select name='Item_type'>
            <option value='equipment'>Equipment</option>
            <option value='gold'>Gold</option>
            <option value='mission_item'>Mission item</option>
            <option value='improver'>Improver</option>
          </select><br> <br>     
          <label style='width: 6em;'>Rarity of the item:</label>  
          <select name='rare'>
            <option value='common'>Common</option>
            <option value='uncommon'>Uncommon</option>
            <option value='rare'>Rare</option>
            <option value='Epic'>Epic</option>
            <option value='Legendary'>Legendary</option>
          </select><br>


    
    </p> <input type='submit' value='Create item!' name='Create_item' />
    
    </form>
</div>";
}
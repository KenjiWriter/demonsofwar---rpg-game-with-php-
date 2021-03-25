<?php
/** Monster.php
*
* Functions for creating and editing monsters
*
*/

function createMonster(){
global $database;
global $player;
global $self_link;
global $con;
require ("config.php");

require ("monster.php");

if(!empty($_POST[ "Create_monster"])){
    try {
    $name = $database->clean($_POST['name']);
    $level = (int)$_POST['level'];
    $max_health = $_POST['max_health'];
    $min_damage = (int)$_POST['min_damage'];
    $strength = (int)$_POST['str'];
    $intelligence = (int)$_POST['intelligence'];
    $endurance = (int)$_POST['endurance'];

    if(!$name) {
        throw new Exception("Insert a monster name");
    }

    if ($level < 1) {
        throw new Exception("Invalid level");
    }
    if ($max_health < 1) {
            throw new Exception("Invalid health");
        }
    if ($strength < 1) {
            throw new Exception("Invalid strength");
        }
    if ($min_damage < 1) {
            throw new Exception("Invalid minimal damage of monster");
        }
    if ($intelligence < 1) {
            throw new Exception("Invalid intelligence");
        }
    if ($endurance < 1) {
            throw new Exception("Invalid endurance");
        }
    $attacks = array();
    foreach ($_POST['attacks'] as $id=>$attack) {
            if(!is_int($id)){
                throw new Exception("Invalid attack id $id");
            }
            $attacks[$id]['combat_text'] = $database->clean($attack['combat_text']);
            if(!$attacks[$id]['combat_text']){
                throw new Exception("Enter combat text");
            }
            $attacks[$id]['power'] = (int)($attack['power']);
            if(!$attacks[$id]['power'] > 0){
                throw new Exception("Enter power of the attack");
            }
            $attacks[$id]['type'] = $database->clean($attack['type']);
            if(!$attacks[$id]['type']){
                throw new Exception("Enter type of attack");
            }

        }
    $drops = array();
        foreach ($_POST['drops'] as $id=>$drop) {
            if(!is_int($id)){
                throw new Exception("Invalid drop id $id");
            }
            $drops[$id]['id'] = (int)($drop['id']);
            $drops[$id]['amount'] = (int)($drop['amount']);
            $drops[$id]['max_amount'] = (int)($drop['max_amount']);
            $drops[$id]['chances'] = (int)($drop['chances']);
        }
    // insert new monster to database
        $attacks = json_encode($attacks);
        $drops = json_encode($drops);
        echo $drops;
        $query = "INSERT into `monsters` (name, level, strength, intelligence, endurance, attacks, max_health, drops, min_dmg)
        VALUES ('$name', '$level', '$strength', '$intelligence', '$endurance', '$attacks', '$max_health',  '$drops','$min_damage')";
        $result = mysqli_query($con,$query);
        if($result){
            echo "New monster created!";
        }





    }catch (Exception $e){
        $error = $e->getMessage();
    }
    }






    // Display form
    $label_width=6;
    echo "
<div style='text-align: left;' class='FormContainer centerDiv'>
    <form  action='$self_link' method='post'>";
         if (!empty($error)) {
             echo '<p  style="color: #ff0000;">' . $error . '</p>';
         }
         echo
         "
          <label style='width: 6.5em;'>Monster name:</label> <input type='text' placeholder='Name' name='name' autocomplete='off' /><br><br>
          <label style='width: 6em;'>Monster level:</label> <input type='number' placeholder='Level' name='level' /><br>
          <label style='width: 6em;'>Monster health:</label>  <input type='number' placeholder='Health' name='max_health' /><br><br>
          <label style='width: 6em;'>Monster min damage:</label>  <input type='number' placeholder='Minimal damage' name='min_damage' /><br><br>
          <label style='width: 6em;'>Monster strength:</label>  <input type='number' placeholder='Strength' name='str' /><br>
          <label style='width: 6em;'>Monster intelligence:</label> <input type='number' placeholder='Intelligence' name='intelligence' /><br>
          <label style='width: 6em;'>Monster endurance:</label> <input type='number' placeholder='Endurance' name='endurance' /><br>
          <label>Attacks: </label><br> <p style='margin-left: 10px;'>";
         for ($i = 1; $i <= 2; $i++) {
                echo "Skill {$i}<br>
                <label style='width: 6em;'>Combat text:</label> <input autocomplete='off' type='text' name='attacks[$i][combat_text]'/> <br>
                <label style='width: 6em;'>Combat power:</label><input type='number' name='attacks[$i][power]'/><br>
                <label style='width: 6em;'>Type of attack</label> <select name='attacks[$i][type]'>
                        <option value='melee'>Melee</option>
                        <option value='magic'>Magic</option> 
                 </select><br><br>
                
                
                ";
          
          }
         for ($i = 1; $i <= 2; $i++) {
                echo "Drop {$i}<br>
                <label style='width: 6em;'>Id:</label><input type='number' name='drops[$i][id]'/><br>
                <label style='width: 6em;'>Chances:</label><input type='number' name='drops[$i][chances]'/><br>
                <label style='width: 6em;'>Min amount:</label><input type='number' name='drops[$i][amount]'/><br>
                <label style='width: 6em;'>Max amount:</label><input type='number' name='drops[$i][max_amount]'/><br><br>
                       
                ";

    }
          echo "</p> <input type='submit' value='Create monster' name='Create_monster' />
    
    </form>
</div>";



}


function editMonster(){

}

?>

<?php
/** AttackPage.php
 *
 * Functions for creating and editing attacks
 *
 */

function createAttack(){
    global $database;
    global $con;
    global $player;
    global $self_link;
    require ("config.php");

    require ("monster.php");

    if(!empty($_POST[ "Create_attack"])){
        try {
            $name = $database->clean($_POST['name']);
            $Combat_text = $database->clean($_POST['Combat_text']);
            $Combat_power = (int)$_POST['Combat_power'];
            $Purchase_cost = (int)$_POST['purchase_cost'];
            $type = $database->clean($_POST['Type']);
            if(!$name) {
                throw new Exception("Insert a attack name");
            }
            if(!$Combat_text) {
                throw new Exception("Insert a combat text");
            }
            if ($Combat_power < 1) {
                throw new Exception("Invalid combat power");
            }
            if ($Purchase_cost < 1) {
                throw new Exception("Invalid Purchase cost");
            }
            // insert new monster to database
            $query = "INSERT into `attacks` (name, Combat_text, Combat_power, Purchase_cost, type)
        VALUES ('$name', '$Combat_text', '$Combat_power', '$Purchase_cost', '$type')";
            $result = mysqli_query($con,$query);
            if($result){
                echo "New attack created!";
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
          <label style='width: 6.5em;'>Attack name:</label> <input type='text' placeholder='Name' name='name' autocomplete='off' /><br><br>
          <label style='width: 6em;'>Combat text:</label> <input type='text' placeholder='Combat text' name='Combat_text' autocomplete='off'/><br>
          <label style='width: 6em;'>Combat power:</label>  <input type='number' placeholder='Combat power' name='Combat_power' /><br>
          <label style='width: 6em;'>Cost:</label>  <input type='number' placeholder='Purchase cost' name='purchase_cost' /><br>
          <label style='width: 6em;'>Attack type:</label>  
          <select name='Type'>
            <option value='melee'>Mele</option>
            <option value='magic'>Magic</option>
          </select><br>

    
</p> <input type='submit' value='Create attack' name='Create_attack' />
    
    </form>
</div>";



}


function editMonster(){

}



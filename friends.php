<?php 
/** friends.php
 *
 * here player can view, adds and removes ppl from friends list
 *
 */

function friends_list() {
global $player;
global $self_link;
global $con;
$output = '';
if(isset($_POST['submit'])){
    $inv_player = $_POST['player_name'];
    $inv_player = preg_replace("#[^0-9a-z]#i","", $inv_player);

    $query = mysqli_query($con,"SELECT * FROM user WHERE username LIKE '%$inv_player%'") or die("Could not add player!");
    $count = mysqli_num_rows($query);

    if($count == 0){
        echo "There is no such player!";
    }else{
        $row = mysqli_fetch_array($query);
        $username = $row['username'];



    }
}

echo "<form action='$self_link' method='POST'>

<input type='text' name='player_name' />
<input type='submit' name='submit' value='add' />

</form>";

}
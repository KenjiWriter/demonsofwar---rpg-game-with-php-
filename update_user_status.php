<?php
session_start();

require ('config.php');

$user_id = $_SESSION["user_id"];
$time=time()+10;
$res = mysqli_query($con,"UPDATE user set last_online={$time} WHERE id={$user_id}");
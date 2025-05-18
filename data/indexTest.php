<!-- <?php


setcookie("FOOD" , "Chicken" , time()+86400 , '/');
setcookie("DRINKS" , "juice" , time()+86400 , '/');


foreach($_COOKIE as $key => $value){
    echo "{$key} = {$value} <br>";
}

if (isset($_COOKIE["FOOD"])){
    echo "You like some {$_COOKIE["FOOD"]}";
}
else {
    echo "You have no chicken";
}

?> -->

<?php
session_start();

echo "{$_SESSION['access_history']}";
echo "{$_SESSION['current_patient']}";
echo "{$_SESSION['patient_treatments']}";


?>
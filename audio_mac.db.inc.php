<?php 
 $conn = mysqli_connect('localhost','jimmy','test123','music_app');
 if(!$conn){
    echo 'Error connecting to Db' . mysqli_connect_error();
 }
?>
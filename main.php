
<?php
 
 session_start();

 if(!isset($_SESSION['zalogowany']))
 {
    header('Location: index.php');
 }
 ?> 
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
	<title>Sklep internetowy </title>
	<meta charset="utf-8"/>

</head>
<body>
   <?php

echo "<p class='witaj'>witaj ".$_SESSION['imie'].' [<a href="logout.php"> Wyloguj się </a>]</p>';


echo "<p class='witaj'><b>email: </b>".$_SESSION['email'];
   ?>
    
</body>
</html>
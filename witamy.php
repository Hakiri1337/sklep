<?php
 
 session_start(); //żeby sesja działała dopisujemy tą funkcję 

 if(!isset($_SESSION['udanarejestracja']))
 {
	 header("Location: index.php");
	 exit();
 }
 else
 {
	unset($_SESSION['udanarejstracja']);
 }
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>sklep </title>
	<meta charset="utf-8"/>

</head>
<body>
Udało ci się zarejestrowac <br/><br/>
<a class="urej" href=logowanie.php>Zaloguj sie na swoje nowe konto!;</a>




</body>
</html>
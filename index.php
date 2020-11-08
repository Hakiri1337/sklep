
<?php
 
 session_start(); //żeby sesja działała dopisujemy tą funkcję 

 if(isset($_SESSION['zalogowany'])&& $_SESSION['zalogowany']==true)
 {
	 header("Location: main.php");
	 exit();
 }
 ?>
<!DOCTYPE html>
<html>
<head>

<link rel="stylesheet" href="style.css">
	<title>Witamy w naszym sklepie! </title>
	<meta charset="utf-8"/>

</head>
<body>
	<div id="container">
		<div id="div-naglowek">
<h1 id="naglowek">Narazie jest tylko system logowania ale z czasem bedzie tu dużo więcje tresci</h1> <br/><br/>
</div>
<div class="header">
    <h1 id="title class"> Zaloguj Się
    </h1>
    <p id="description">
	Nie masz konta?<a id="reg" href="rejestracja.php"> kliknij tutaj </a>
    </p>
    </div >
  

<div id="logowanie">
<form action=logowanie.php method="post">

<span id="imie">Imię:</span> <br/> <input type="text" name="imie" id="imie" placeholder="Wpisz imie" /><br/>
 	
<span id="haslo">Hasło:</span> <br/> <input type="password" name="haslo" id="haslo" placeholder="Wpisz hasło" /><br/><br/>
<div id="submitt">
<input type="submit" name="zaloguj" value="zaloguj sie" id="submit">
</div>
<?php

If(isset($_SESSION['blad']))
echo $_SESSION['blad'];

?>


</div>

</form>


</div>


</body>
</html>
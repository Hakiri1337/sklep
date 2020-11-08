
<?php
 
 require_once "connect.php";
 session_start(); //żeby sesja działała dopisujemy tą funkcję 
mysqli_report(MYSQLI_REPORT_STRICT);

 if(isset($_POST['email']))
 {
	 //udana walidacja
	 $wszystko_ok=true;
	 //sprawdźmy poprawnosc nickname 
	 $nick=$_POST['nick'];
	// sprawdzenie długosci nicku 
	if((strlen($nick)<3) || (strlen($nick)>20)	){

		$wszystko_ok = false;
		$_SESSION['e_nick']="Imię musi posiadac od 3 do 20 znakow";
	}

	if(ctype_alnum($nick) == false)
	{

		$wszystko_ok = false;
		$_SESSION['e_nick']="Nick moze składać sie tylko z liter i cyfr (bez polskich znakow)";
	}
	$nazwisko=$_POST['nazw'];
	if(ctype_alnum($nazwisko) == false)
	{

		$wszystko_ok = false;
		$_SESSION['e_nazw']="Nazwisko moze składać sie tylko z liter (bez polskich znakow)";
	}


	 $email = $_POST['email'];
	 $emailB = filter_var($email,FILTER_SANITIZE_EMAIL);
	 if((filter_var($emailB,FILTER_VALIDATE_EMAIL)==false) ||($emailB!=$email))
	 {

		$wszystko_ok=false;
		$_SESSION['e_email']="Podaj poprawny adres e-mail!";
	 }

	 

	 

	
	
	$haslo1=$_POST['haslo1'];
	$haslo2=$_POST['haslo2'];
	 
	if(strlen($haslo1) < 8 || strlen($haslo1) > 20)
	{
		$wszystko_ok=false;
		$_SESSION['e_haslo']="Haslo musi posiadac od 8 do 20 znaków";

	}
	//czy hasla są takie same 
	if($haslo1!=$haslo2)
	{
		$wszystko_ok=false;
		$_SESSION['e_haslo']="Podane hasla nie są identyczne";
	}
	//hashowanie hasla
	$haslo_hash=password_hash($haslo1,PASSWORD_DEFAULT);

	

	//czy regulamin został zaakceptowany

	if(!isset($_POST['regulamin']))
{
	$wszystko_ok=false;
	$_SESSION['e_regulamin']="Potwierdź akceptacje regulaminu";
}

//bot or not
$sekret="6LeDG94ZAAAAAE0uZ0bIzr1lg-Vn7-O2qanke-N3";
$sprawdz=file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$_POST['g-recaptcha-response']);
$odpowiedz=json_decode($sprawdz);
if($odpowiedz->success==false)
{

	$wszystko_ok=false;
	$_SESSION['e_bot']="Potwierdz ze nie jestes botem";
}




try 
{
	
$poloczenie = @new mysqli($host,$db_user,$db_password,$db_name); // do zmiennej jest przypisywany obiekt łączący php z mysql
$result = $poloczenie->query("SELECT id_klienta FROM klienci ");
$ile_userow=$result->num_rows;




if($poloczenie->connect_errno!=0) 
{
	throw new Exception(mysqli_connect_errno());
}
else
{
	//czy email juz istnieje
	
	
	@$rezultat=$poloczenie->query("SELECT id_klienta FROM klienci WHERE email='$email'");
	if(!$rezultat) throw new Exception($poloczenie->error);

	$ile_takich_maili=$rezultat->num_rows;
	if($ile_takich_maili>0)
	{
		$wszystko_ok=false;
		$_SESSION['e_email']="Istnieje juz konto przypisane do tego adresu email";
	}
	
	//czy login juz istnieje
	
	$rezultat=$poloczenie->query("SELECT id_klienta FROM klienci WHERE imie='$nick'");
	if(!$rezultat) throw new Exception($poloczenie->error);

	$ile_takich_nickow=$rezultat->num_rows;
	if($ile_takich_nickow>0)
	{
		$wszystko_ok=false;
		$_SESSION['e_nick']="Podany login jest zajęty";
	}
	if($wszystko_ok==true)
	{
		if($poloczenie->query("INSERT INTO klienci values ($ile_userow+1,'$nick','$nazwisko','$email','$haslo_hash')"))
		{
			$_SESSION['udanarejestracja']=true;
			header('Location: witamy.php');
		}
		else
		{
			throw new Exception($poloczenie->error);
		}

	}

	$poloczenie->close();

	}	
	}
	catch(Exception $e)
	{

		echo'<span style="color:red;">Błąd serwera! Przepraszamy za utrudnienia i niedogodności, prosimy o rejestrację w innym terminie</span>';
		echo'</br>Informacja deweloperska'.$e;
	}
}
 ?>
<!DOCTYPE html>
<html lang="pl">
<html>
<head>
<link rel="stylesheet" href="style.css">
	<title>Sklep załóż damowe konto </title>
	<meta charset="utf-8"/>
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<style>
.error{
color:red;
margin-bottom:10px;
margin-top:10px;
	 
}

</style>
</head>
<body>
<div id="container">
<div id="rejestracja">
<form method="post">

<span id="imie">Imię:</span> <br/> <input type="text" name="nick" id="haslo" placeholder="Wpisz imie" /><br/>
<?php 
if(isset($_SESSION['e_nick']))
{
echo'<div class="error" >'.$_SESSION['e_nick'].'</div>';
unset($_SESSION['e_nick']);
}
?>

<form method="post">

<span id="imie">Nazwisko:</span> <br/> <input type="text" name="nazw" id="haslo" placeholder="Wpisz nazwisko"/><br/>
<?php 
if(isset($_SESSION['e_nazw']))
{
echo'<div class="error" >'.$_SESSION['e_nazw'].'</div>';
unset($_SESSION['e_nazw']);}

?>



<span id="imie">Email:</span> <br/> <input type="text" name="email"/ id="haslo" placeholder="Wpisz email" ><br/>
<?php
if(isset($_SESSION['e_email']))
{
echo'<div class="error" >'.$_SESSION['e_email'].'</div>';
unset($_SESSION['e_email']);}

?>


<span id="imie" >Haslo:</span> <br/> <input type="password" name="haslo1" id="haslo" placeholder="Wpisz haslo"/><br/>
<?php 
if(isset($_SESSION['e_haslo']))
{
echo'<div class="error" >'.$_SESSION['e_haslo'].'</div>';
unset($_SESSION['e_haslo']);}
?>


<span id="imie">Powtorz Haslo:</span> <br/> <input type="password" name="haslo2"/ id="haslo" placeholder="potwierdz haslo" ><br/>
<label>
<input type="checkbox" name="regulamin"/>Akceptuje Regulamin
</label>
<?php 
if(isset($_SESSION['e_regulamin']))
{
echo'<div class="error" >'.$_SESSION['e_regulamin'].'</div>';
unset($_SESSION['e_regulamin']);}
?>
<div class="g-recaptcha" data-sitekey="6LeDG94ZAAAAAL1VfYoAUje5zmDc9zkmyf8BNxK3
"></div>
<?php 
if(isset($_SESSION['e_bot']))
{
echo'<div class="error" >'.$_SESSION['e_bot'].'</div>';
unset($_SESSION['e_bot']);}
?>
      <br/>
	  
      <input type="submit" value="Submit" id="submit2" >
</form>
</div>


</div>


</body>
</html>

<?php
 
 session_start(); //żeby sesja działała dopisujemy tą funkcję 

if(!isset($_POST['imie'])|| !isset($_POST['haslo']))
{
    header('Location: index.php');
   exit();
}

require_once "connect.php"; //użyj raz pliku connect.php

$poloczenie = @new mysqli($host,$db_user,$db_password,$db_name); // do zmiennej jest przypisywany obiekt łączący php z mysql	

    if($poloczenie->connect_errno!=0) 
    {
        echo "Error: ".$poloczenie->connect_errno;
    }

    else
    {

        $login=$_POST["imie"]; // jeżeli udało nam sie połączyć przypisujemy do zmiennych login i hasło wartości przesłane metodą _post
        $haslo=$_POST["haslo"];
        
        $login=htmlentities($login,ENT_QUOTES,"UTF-8");
      

       "SELECT * FROM klienci WHERE imie='$login' and haslo='$haslo'"; //kwerenda wyciągająca dane użytkownika według loginu i hasła 
         
       if($rezultat = @$poloczenie->query(sprintf("SELECT * FROM klienci WHERE imie='%s'",mysqli_real_escape_string($poloczenie,$login),mysqli_real_escape_string($poloczenie,$login))))//kwerenda poktórej wybieramy użytkowniaka z bazy danych
       { 
           $user_count = $rezultat->num_rows; //liczba użytkowników to ilosc wykonan kwerendy dla których pasuje login i haslo
            if($user_count>0) //jezeli ta liczba jest większa od zera 
            {
                    $row=$rezultat->fetch_assoc(); // tworzymy tablice asocjacyjną do której wyciąmy nazwy wierszy z bazy danych
                    if(password_verify($haslo,$row['haslo'])){
                    $_SESSION['zalogowany']=true; //tworzymy zmienną która przyjmuje wartosć true jeżeli udało nam się zalogować
                    $_SESSION['id']=$row['id_klienta']; // zmienna która pozwala nam zobaczyć kto jest zalogowany 
                    $user=$row['imie']; // przypisujemy do zmiennej user wartosc z tablicy asocjacyjnej 
                    $pass=$row['haslo']; 
                    $_SESSION['imie']=$row['imie']; //tworzymy zmiennną sesji która jest przypisana do użytkownika za pomocą PHPSESSID
                    $_SESSION['email']=$row['email'];
                   
                    unset($_SESSION['blad']);


                    $rezultat->close(); //usuwamy to co baza danych nam zwróciła 
                    header('Location: main.php'); // przekierowanie przeglądarki do inngo dokumentu
                }
                else
                    {
                    $_SESSION['blad']='<span id="blad">Nieprawidłowe dane logowania! </span>';
                    header('Location: index.php');
                    }

            }
            
                else
                     {
                    $_SESSION['blad']='<span id="blad">Nieprawidłowe dane logowania! </span>';
                     header('Location: index.php');
                    }

            
           
 
        }
      
        $poloczenie->close();
    }
   
    
 




?>
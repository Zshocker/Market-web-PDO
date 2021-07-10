<?php 
session_start();
require_once 'ConnexionToBD.php';

if($_POST)
{
    $conn=Conect_ToBD("magasin_en_ligne","root");
    $id=$_SESSION['id_uti'];
    $name=$_POST['nom'];
    $pname=$_POST['prenom'];
    $mail=$_POST['email'];
    $adr=$_POST['adresse'];
    $log=$_POST['login'];
    $mdp=md5($_POST['mdp']);
    $tel=$_POST['tele'];
    if($mdp==""){
        $scr="UPDATE utilisateur SET nom='$name',prenom='$pname',email='$mail',adresse='$adr',login='$log',tele='$tel' where id_uti=$id";
    }
     else{
        $scr="UPDATE utilisateur SET nom='$name',prenom='$pname',email='$mail',adresse='$adr',login='$log',mdp='$mdp',tele='$tel' where id_uti=$id";
     }   
    
    
    $conn->exec($scr);
      
    CloseCon($conn);
}
header("Location: ClientPa.php", true, 301);
?>
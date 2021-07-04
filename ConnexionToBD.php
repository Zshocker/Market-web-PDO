<?php 
function Conect_ToBD($Bd,$usr)
{
    $dbhost="localhost";
    $dbmdp="";
    try{
    $conn=new PDO("mysql:host=$dbhost;dbname=$Bd",$usr,$dbmdp);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
    }
    catch(PDOException $e)
    {
        echo "connexion failed : ".$e->getMessage();
    }   
}
function CloseCon($conn)
 {
    $conn = null;
 }
?>
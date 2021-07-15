<?php
require_once 'ConnexionToBD.php';
if($_POST)
{
    $conn=Conect_ToBD("magasin_en_ligne","root");
    $id_prod=$_POST['id_prod'];
    $qte=$_POST['QteAch'];
    $prix=$_POST['prixAch'];
    if(isset($_POST['fornis'])){
        $forn=$_POST['fornis'];
    $scr="INSERT INTO achat_prod(id_prod,id_forn,qte_achat,prix_unite) VALUES ($id_prod,$forn,$qte,$prix)";
    }
    else {
        $nouv=$_POST['NFourn'];
        $scr="INSERT INTO fornisseur(nom_forn) VALUES('$nouv')";
        $conn->exec($scr);
        $forn=$conn->lastInsertId();
        $scr="INSERT INTO achat_prod(id_prod,id_forn,qte_achat,prix_unite) VALUES ($id_prod,$forn,$qte,$prix)";
    }
    $conn->exec($scr);
    CloseCon($conn);
}
header("Location:adminPa.php ",true,301);
?>
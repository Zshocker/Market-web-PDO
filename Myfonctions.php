<?php
require_once "ConnexionToBD.php";
function Get_qte($id_prod)
{
$conn = Conect_ToBD("magasin_en_ligne", "root");
$scr="SELECT SUM(quantite) from ligne_commande where id_prod=$id_prod";
$scr2="SELECT SUM(qte_achat) from achat_prod where id_prod=$id_prod";
$res=$conn->query($scr);
$res=$res->fetchAll(PDO::FETCH_NUM);
$qteCom=$res[0][0];
$res=$conn->query($scr2);
$res=$res->fetchAll();
$qteAch=$res[0][0];
$qte_stock=$qteAch-$qteCom;
CloseCon($conn);
return $qte_stock;
}
function getRed($id,$conn2)
{
    $scr="SELECT reduction from produit where id_prod=$id";
    $res=$conn2->query($scr);
    $red=$res->fetch(PDO::FETCH_ASSOC);
    return $red['reduction'];
}
function getQtePanier($id,$conn2,$id_pan)
{

    $scr="SELECT qte from avoir_pan_pro where id_prod=$id and id_panier=$id_pan";
    $res=$conn2->query($scr);
    $red=$res->fetch(PDO::FETCH_ASSOC);
    return $red['qte'];
}
function Get_PrixTot($id_Com)
{
$conn = Conect_ToBD("magasin_en_ligne", "root");
$scr = "SELECT * FROM ligne_commande NATURAL JOIN produit where id_commande=$id_Com";
$resulta= $conn->query($scr);
$prixTot=0;
while($qe=$resulta->fetch(PDO::FETCH_ASSOC))
{
    $prixTot+=($qe['prix_std']-$qe['prix_std']*$qe['reduction_ins'])*$qe['quantite'];
}
return $prixTot;
}

function check_etat($id_Com,$etatInit)
{
    if($etatInit!="EN ATTENTE D'ALIMENTATION DU STOCK")return 1;
    $conn = Conect_ToBD("magasin_en_ligne", "root");
    $scr="SELECT * from ligne_commande where id_commande=$id_Com";
    $resulta=$conn->query($scr);
    while($qe=$resulta->fetch(PDO::FETCH_ASSOC))
    {
        $qte=Get_qte($qe['id_prod']);
        if($qte<0)return false;
    }
    $scr="UPDATE commande SET id_etat=(SELECT id_etat from etat_commande where etat_com='EN COURS de TRAIT') where id_commande=$id_Com ";
    $conn->exec($scr);
    return 2;
}
?>
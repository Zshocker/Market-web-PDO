<?php
session_start();
require_once 'Myfonctions.php';
require_once 'ConnexionToBD.php';
var_dump($_POST);
if(isset($_POST['Confirm']))
{   
    $id_uti=$_SESSION['id_uti'];
    $conn=Conect_ToBD("magasin_en_ligne","root");
    $tab_Prod=$_POST['ProdIds'];
    $tab_Qte=$_POST['qteP'];
    $type_paiment=intval($_POST["type_Paiment"]);
    $address_liv=$_POST["adresse"];
    $date_com=date("Y-m-d");
    $scr="INSERT INTO commande(date_com,adresse_liv,id_etat,id_uti) values('$date_com','$address_liv',(SELECT id_etat from etat_commande where etat_com='EN COURS DE TRAIT'),$id_uti)";
    $conn->exec($scr);
    $id_Comm= $conn->lastInsertId();
    if($type_paiment==1)
    {
        $type_cart=$_POST["type-Carte"];
        $scr="INSERT INTO paiement_carte(id_typecarte,id_commande) Values($type_cart,$id_Comm)";
        $conn->exec($scr);
        $id_pai= $conn->lastInsertId();
        $scr="UPDATE commande SET id_paiementCa=$id_pai where id_commande=$id_Comm ";
        $conn->exec($scr);
    }
    elseif($type_paiment==2)
    {
        $scr="INSERT INTO paiement_espece(id_commande) Values($id_Comm)";
        $conn->exec($scr);
        $id_pai= $conn->lastInsertId();
        $scr="UPDATE commande SET id_paiementE=$id_pai where id_commande=$id_Comm ";
        $conn->exec($scr);
    }
    $scr="SELECT id_panier from utilisateur where id_uti=$id_uti";
    $id_pan=$conn->query($scr);
    $id_pan=$id_pan->fetch(PDO::FETCH_ASSOC);
    $id_pan=$id_pan['id_panier'];
    for ($i=0; $i < count($tab_Prod); $i++) 
    { 
        $id_prod=$tab_Prod[$i];
        $qte=$tab_Qte[$i];
        $qte_stock=Get_qte($id_prod);
        if($qte_stock<$qte){
            $scr="UPDATE commande SET id_etat=(SELECT id_etat from etat_commande where etat_com='EN ATTENTE D\'ALIMENTATION DU STOCK') where id_commande=$id_Comm ";
            $conn->exec($scr);
        }
        $red=getRed($id_prod,$conn);
        $scr="INSERT INTO ligne_commande(reduction_ins,quantite,id_commande,id_prod) VALUES($red,$qte,$id_Comm,$id_prod)";
        $conn->exec($scr);
        if(!isset($_POST['Not_pan'])){
        $qte_pan=getQtePanier($id_prod,$conn,$id_pan);
        if($qte_pan<=$qte)
        {
            $scr="DELETE FROM avoir_pan_pro where id_panier=$id_pan AND id_prod=$id_prod";
            $conn->exec($scr);
        }
        else
        {
            $qte=$qte_pan-$qte;
            $scr="UPDATE avoir_pan_pro SET qte=$qte where id_panier=$id_pan and id_prod=$id_prod";
            $conn->exec($scr);
        }
    }
    }
CloseCon($conn);
}
header("location: CommandePa.php",true,301);
?>
<?php 
require_once 'ConnexionToBD.php';

if($_POST)
{
    $conn=Conect_ToBD("magasin_en_ligne","root");
    $id_com=$_POST['id-Com'];
    $type=$_POST['type_P'];
    $etat=$_POST['etat_com'];
    $id_et=explode("-",$etat);
    $id_etat=$id_et[0];
    if($type=="espese")
    {
        if($id_et[1]=='PAYEE'||$id_et[1]=='LIVREE'){
            $scr="UPDATE  paiement_espece SET date_paiementE=NOW() where id_commande=$id_com";
            $conn->exec($scr);
            $scr="UPDATE commande SET id_etat=(SELECT id_etat from etat_commande where etat_com='PAYEE') where id_commande=$id_com"; 
            $conn->exec($scr);
        }else
        {
            $scr="UPDATE commande SET id_etat=$id_etat where id_commande=$id_com";
            $conn->exec($scr);
        }
    }elseif($type=="carte")
    {
        if($id_et[1]=='PAYEE'||$id_et[1]=='LIVREE')$scr="UPDATE commande SET id_etat=(SELECT id_etat from etat_commande where etat_com='PAYEE') where id_commande=$id_com"; 
        else $scr="UPDATE commande SET id_etat=$id_etat where id_commande=$id_com";
        $conn->exec($scr);
    }
    elseif($type=="cheque"){
        $scr="UPDATE commande SET id_etat=$id_etat where id_commande=$id_com";
        $conn->exec($scr);
        $i=0;
        if(isset($_POST['id_check']))
        {
            $TabDesSheque=$_POST['id_check'];
            for ($i=0; $i <count($TabDesSheque); $i++) 
            { 
                $cheq=$TabDesSheque[$i];
                $date_ech=$_POST['datee'][$i];
                $scr="UPDATE paiement_cheque SET date_ech='$date_ech' where id_paiementC=$cheq";
                $conn->exec($scr);
            }
        }
        if(isset($_POST['dateC'])){
            $t=$i;
            for ($i=0; $i <count($_POST['dateC']) ; $i++) 
            { 
                $date_P=$_POST['dateC'][$i];
                $date_ech=$_POST['datee'][$i+$t];
                $man=$_POST['montant'][$i];
                $scr="INSERT INTO paiement_cheque(date_ech,date_paiementC,montant,id_commande) VALUES ('$date_ech','$date_P',$man,$id_com)";
                $conn->exec($scr);
            }
        }

    }
    CloseCon($conn);
   
}
header("Location: TousCom.php", true, 301);
?>
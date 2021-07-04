<?php
require_once 'ConnexionToBD.php';
$conn=Conect_ToBD("magasin_en_ligne","root");
if(isset($_POST['Delete']))
{
    $id_Prod=$_POST['id_prod'];
    $scr="DELETE FROM produit WHERE id_prod=$id_Prod";
    $scr_panier="DELETE FROM avoir_pan_pro WHERE id_prod=$id_Prod";
    $scr_photos="DELETE FROM photo WHERE id_prod=$id_Prod";
    $sele="SELECT photo From photo where id_prod=$id_Prod";
    $res=$conn->query($sele);
    while($qe=$res->fetch(PDO::FETCH_ASSOC))
    {
        $qe=$qe['photo'];
        unlink($qe);
    }
    $conn->exec($scr_photos);
    $conn->exec($scr_panier);
    $conn->exec($scr);
    header("Location: adminPa.php", true, 301);
}
CloseCon($conn);
?>
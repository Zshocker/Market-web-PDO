<?php
require_once 'ConnexionToBD.php';

if($_POST)
{
    $conn=Conect_ToBD("magasin_en_ligne","root");
    $name=$_POST['prodName'];
    $Desc=$_POST['prodDescri'];
    $prix=floatval($_POST['prodPrix']);
    $red=floatval($_POST['prodRed']);
    $id_cat=intval($_POST['prodCat']);
    if(isset($_POST['fake_prix']))
    {
        $fake=$_POST['fake_prix'];
        $scr="INSERT INTO produit (Designation,Description,prix_std,reduction,id_cat,prix_barre) Values('$name','$Desc',$prix,$red,$id_cat,$fake)";
    } else $scr="INSERT INTO produit (Designation,Description,prix_std,reduction,id_cat) Values('$name','$Desc',$prix,$red,$id_cat)";
    $id_photo_scr="SELECT MAX(id_photo) as id from photo";
    $conn->exec($scr);
    $id_prod= $conn->lastInsertId();
    /*image*/
    $id_photo=$conn->query($id_photo_scr);
    $id_photo=$id_photo->fetch(PDO::FETCH_ASSOC);
    $id_photo=intval($id_photo['id']);
    $id_photo++;
    $numofPhotos=count($_FILES["prodImage"]["name"]);
    for ($i=0; $i < $numofPhotos; $i++) 
    { 
        $target="uploadedImages\imgfor $name  $id_photo.jpeg";
        move_uploaded_file($_FILES["prodImage"]["tmp_name"][$i],$target);
        $target="uploadedImages\\\\imgfor $name  $id_photo.jpeg";
        $inser_img_scr="INSERT INTO photo (photo,id_prod) Values('$target',$id_prod)";
        $conn->exec($inser_img_scr);   
        $id_photo++;
    }
    CloseCon($conn);
    header("Location: adminPa.php", true, 301);
}



?>
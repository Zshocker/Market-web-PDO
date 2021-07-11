<?php
session_start();
require_once 'ConnexionToBD.php';
$conn = Conect_ToBD("magasin_en_ligne", "root");
$id_uti = $_SESSION['id_uti'];
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $scr = "SELECT id_commande,date_com,adresse_liv,id_uti,etat_com,id_paiementE,id_paiementCa FROM commande  NATURAL JOIN etat_commande where id_uti=$id_uti and (date_com like '%$search%' or adresse_liv like '%$search%') ORDER BY id_commande ";
} else
    $scr = "SELECT id_commande,date_com,adresse_liv,id_uti,etat_com,id_paiementE,id_paiementCa FROM commande NATURAL JOIN etat_commande where id_uti=$id_uti ORDER BY id_commande";
$result = $conn->query($scr);
?>

<html>

<head><meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les inscriptions</title>
    <script src="JS Scripts/name.js"></script>
    <link rel="StyleSheet" href="styleForInscrip.css">
    <link rel="StyleSheet" href="tableStyle.css">

    <link rel="stylesheet"  href="CssFontA/css/all.css">
</head><meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<body style="margin:0px;">
    <div class="bar">
        <div style="height:100%;">
            <a href="index.php"><img src="rw-markets.png" style="width:auto; height:75%; margin-left:25px;"></a>
            <?php if (!isset($_SESSION['id_uti'])) {
                header("Location: index.php", true, 301);
            } else {
            ?>
                <form method="POST" action="LogMeOut.php" style="float:right; margin:0px">
                    <input type="submit" value="logout" name="Logout" class="mi" onclick="return confirm('Are you sure?');">
                </form>
            <?php
            }
            ?>
        </div>
    </div>

   <div class="cont-92-5">
        <?php include "OurSidebar.php"?>
        <div class="MainCont">
            <div class="navBar">
                <input type="text" name="search" class="searchBar" id="searcher" placeholder="Search by date or adresse">
                <button type="submit" class="miniBut" onclick="window.location.href='CommandePa.php?search='+Get_Search('searcher');" style="margin-top:8px; width: 30px; height: 32px;"><i class="fa fa-search"></i></button>
            </div>
            <div class="table-wrapper">
                <table class="fl-table">
                    <thead><meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <tr>
                            <th>id_commande</th>
                            <th>date_com</th>
                            <th>adresse_liv</th>
                            <th>prix Totale</th>
                            <th>etat</th>
                            <th>type paiement</th>
                            <th> informations paiement </th>
                            <th> informations commande </th>


                        </tr>
                    </thead><meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <tbody>
                        <?php
                        while ($qe = $result->fetch(PDO::FETCH_ASSOC)) {
                            $id_commande = $qe['id_commande'];
                            $id_uti = $qe['id_uti'];
                            $adresse = $qe['adresse_liv'];
                            $date = $qe['date_com'];
                            $etat = $qe['etat_com'];
                            $id_paiementE = $qe['id_paiementE'];
                            $id_paiementCa = $qe['id_paiementCa'];
                           

                        ?>
                            <tr>
                                <td><?php echo "$id_commande" ?></td>
                                <td><?php echo "$date" ?></td>
                                <td><?php echo "$adresse" ?></td>
                                <td>
                                    <?php
                                    require_once 'Myfonctions.php';
                                    $prixTot=Get_PrixTot($id_commande);
                                    echo $prixTot;

                                    ?>
                                dh</td>
                                <td><?php echo "$etat" ?></td>
                                <td><?php if ($id_paiementE != '') {
                                        $var = 1;
                                        echo "espece";
                                    } elseif ($id_paiementCa != '') {
                                        $var = 2;
                                        echo "carte";
                                    } else {
                                        $var = 3;
                                        echo "chèque";
                                    } ?></td>
                                <td> <?php if ($var == 2) {
                                            $scr = "SELECT type_carte from type_carte NATURAL JOIN paiement_carte where id_commande=$id_commande";
                                            $res = $conn->query($scr);
                                            $res = $res->fetch(PDO::FETCH_ASSOC);
                                            $tp = $res['type_carte'];
                                            echo "$tp";
                                        } elseif ($var == 1) {
                                            $scr = "SELECT date_paiementE from paiement_espece  where id_commande=$id_commande";
                                            $res = $conn->query($scr);
                                            $res = $res->fetch(PDO::FETCH_ASSOC);
                                            $tp = $res['date_paiementE'];
                                            if($tp!='')echo "date de paiement: $tp";
                                            else  echo "Pas encore payee";
                                        } elseif ($var == 3) {
                                        ?>
                                        Les cheque:
                                        <button class="miniBut" style="background-color: aqua;" name="afficher" onclick="show_elem_id('info_cheque-<?php echo $id_commande; ?>')"><i class="fa fa-plus"></i></button>
                                    <?php

                                        }  ?>
                                </td>
                                <td>
                                    <button class="miniBut" style="background-color: aqua;" name="afficher1" onclick="show_elem_id('info_com-<?php echo $id_commande; ?>')"><i class="fa fa-plus"></i></button>

                                </td>

                            </tr>
                        <?php
                        }
                        ?>
                    <tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
    $scr = "SELECT distinct id_commande from commande NATURAL JOIN paiement_cheque where $id_uti=id_uti";
    $res = $conn->query($scr);
    while ($qe = $res->fetch(PDO::FETCH_ASSOC)) {
        $id_commande = $qe['id_commande'];
    ?>
        <div class="modal" id="info_cheque-<?php echo $id_commande; ?>">
            <center>

                <div class="container">
                    <div class="row">
                        <button class="mi" onclick="unshow_elem_id('info_cheque-<?php echo $id_commande; ?>');">&times;</button>
                    </div>
                    <div class="row">
                        <div class="table-wrapper">
                            <table class="fl-table">
                                <thead><meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                    <tr>
                                        <th>date paiement</th>
                                        <th>date encaissment</th>
                                        <th>Montant</th>

                                    </tr>
                                </thead><meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                <tbody>
                                    <?php
                                    $scr = "SELECT * FROM paiement_cheque where id_commande=$id_commande";
                                    $result = $conn->query($scr);
                                    while ($q = $result->fetch(PDO::FETCH_ASSOC)) {
                                        $datep = $q['date_paiementC'];
                                        $datee = $q['date_ech'];
                                        $montant = $q['montant'];

                                    ?>
                                        <tr>
                                            <td><?php echo "$datep"; ?></td>
                                            <td><?php echo "$datee"; ?></td>
                                            <td><?php echo "$montant"; ?>dh</td>

                                        </tr>
                                    <?php
                                    }
                                    ?>
                                <tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </center>
        </div>





    <?php
    }
    ?>
    <?php
    $scr = "SELECT distinct id_commande from commande NATURAL JOIN ligne_commande where $id_uti=id_uti";
    $res = $conn->query($scr);
    while ($qe = $res->fetch(PDO::FETCH_ASSOC)) {
        $id_commande = $qe['id_commande'];
    ?>
        <div class="modal" id="info_com-<?php echo $id_commande; ?>">
            <center>

                <div class="container">
                    <div class="row">
                        <button class="mi" onclick="unshow_elem_id('info_com-<?php echo $id_commande; ?>');">&times;</button>
                    </div>
                    <div class="row">
                        <div class="table-wrapper">
                            <table class="fl-table">
                                <thead><meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                    <tr>
                                        <th>produit</th>
                                        <th>quantité</th>
                                        <th>réduction</th>
                                        <th>prix d'achat(1 unite)</th>

                                    </tr>
                                </thead><meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                <tbody>
                                    <?php
                                    $scr = "SELECT * FROM ligne_commande NATURAL JOIN produit where id_commande=$id_commande";
                                    $result = $conn->query($scr);
                                    $mt = 0;
                                    while ($q = $result->fetch(PDO::FETCH_ASSOC)) {
                                        $nomp = $q['designation'];
                                        $qte = $q['quantite'];
                                        $red = $q['reduction_ins'] * 100;
                                        $prix=$q['prix_std']-$q['prix_std']*$q['reduction_ins'];

                                    ?>
                                        <tr>
                                            <td><?php echo "$nomp"; ?></td>
                                            <td><?php echo "$qte"; ?></td>
                                            <td><?php echo "$red%"; ?></td>
                                            <td><?=$prix ?></td>

                                        </tr>
                                    <?php
                                    }
                                    ?>
                                <tbody>
                            </table>

                        </div>
                    </div>
                </div>

            </center>
        </div>





    <?php
    }
    ?>


</body>
<script>
    function Get_Search(str) {
        return document.getElementById(str).value;
    }
</script>

</html>
<?php
session_start();
require_once 'ConnexionToBD.php';
require_once 'Myfonctions.php';
$conn = Conect_ToBD("magasin_en_ligne", "root");

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $etat= $_GET['ETAT'];
    if($etat!=''&&$search!=''){
       $scr = "SELECT id_commande,date_com,adresse_liv,id_uti,etat_com,id_paiementE,id_paiementCa FROM commande  NATURAL JOIN etat_commande where ( id_etat=$etat AND (adresse_liv like '%$search%' OR id_commande=$search )) ORDER BY id_commande DESC";
    }
    elseif($search!='') $scr = "SELECT id_commande,date_com,adresse_liv,id_uti,etat_com,id_paiementE,id_paiementCa FROM commande  NATURAL JOIN etat_commande where adresse_liv like '%$search%' or id_commande=$search ORDER BY id_commande ";
    else 
    $scr = "SELECT id_commande,date_com,adresse_liv,id_uti,etat_com,id_paiementE,id_paiementCa FROM commande  NATURAL JOIN etat_commande where  id_etat=$etat ORDER BY id_commande DESC";
}
    else
    $scr = "SELECT id_commande,date_com,adresse_liv,id_uti,etat_com,id_paiementE,id_paiementCa FROM commande NATURAL JOIN etat_commande  ORDER BY id_commande";
    $result = $conn->query($scr);
?>

<html>

<head><meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des commandes</title>
    <script src="JS Scripts/name.js"></script>
    <link rel="StyleSheet" href="styleForInscrip.css">
    <link rel="StyleSheet" href="tableStyle.css">

    <link rel="stylesheet" href="CssFontA/css/all.css">
    <script>
        var i = 0;
    </script>
</head>

<body style="margin:0px;">
    <div class="bar">
        <div style=" height:100%;">
            <a href="index.php"><img src="rw-markets.png" style="width: 7%; height: 100%; margin-left:25px;"></a>
            <?php if (!isset($_SESSION['id_uti'])) {
                header("Location: index.php", true, 301);
            } elseif ($_SESSION['type_uti'] != 'admin') {
                header("Location: index.php", true, 301);
            } else {
            ?>
                <form method="POST" action="LogMeOut.php" style="float:right; margin:0px">
                     <input type="submit" value="logout" name="Logout" style="margin-top:15px; margin-right: 15px;" class="mi" onclick="return confirm('Are you sure?');">
                </form>
            <?php
            }
            ?>
        </div>
    </div>

    <div class="cont-92-5">
        <?php include "OurSidebar.php" ?>
        <div class="MainCont">
            <div class="navBar">
                <input type="text" name="search"  style="margin-right: 80px!important" class="searchBar" id="searcher" placeholder="Search by id_commande or adresse ">
                <span style="font-size: 20px">Etat commande:</span>
                <select class="searchBar" style="float: none !important;" id="searchEt">
                    <option></option>
                    <?php 
                    $scr = "SELECT * from etat_commande  ";
                    $res = $conn->query($scr);
                    
                    while ($qe = $res->fetch(PDO::FETCH_ASSOC)) {
                        $ets=$qe['id_etat'];
                        $eat=$qe['etat_com'];
                    ?>
                    <option value="<?=$ets?>"><?=$eat?></option>
                    <?php
                    }
                    ?>
                </select>
                <button type="submit" class="miniBut" onclick="window.location.href='TousCom.php?search='+Get_Search('searcher')+'&ETAT='+Get_Search('searchEt');" style="margin-top:8px; width: 30px; height: 32px;"><i class="fa fa-search"></i></button>
                <button class="miniBut" onclick="window.location.href='TousCom.php'" style="margin-top:8px; width: 30px; height: 32px;">&times;</button>
                
            </div>
            <div class="table-wrapper">
                <table class="fl-table">
                    <thead>
                        <tr>
                            <th>id_commande</th>
                            <th>date_com</th>
                            <th>adresse_liv</th>
                            <th>id_uti</th>
                            <th>prix totale</th>
                            <th> informations commande </th>
                            <th>type paiement</th>
                            <th>etat</th>
                            <th> informations paiement </th>
                            <th> modifier informations</th>
                        </tr>
                    </thead>
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
                            $testt=check_etat($id_commande,$etat);
                            if($testt==2)$etat="EN COURS de TRAIT";
                        ?>
                            <tr>
                                <td><?php echo "$id_commande" ?></td>
                                <td><?php echo "$date" ?></td>
                                <td><?php echo "$adresse" ?></td>
                                <td><?php echo "$id_uti" ?></td>
                                <td><?= Get_PrixTot($id_commande); ?></td>
                                <td><button class="miniBut"  name="afficher1" onclick="show_elem_id('info_com-<?php echo $id_commande; ?>')"><i class="fa fa-plus"></i></button></td>
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
                                <td><?php echo "$etat" ?></td>

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
                                            if ($tp != '')
                                                echo "date de paiement: $tp";
                                            else  echo "Pas encore payee";
                                        } elseif ($var == 3) {
                                        ?>
                                        Les cheque:
                                        <button class="miniBut"  name="afficher" onclick="show_elem_id('info_cheque-<?php echo $id_commande; ?>')"><i class="fa fa-plus"></i></button>
                                    <?php

                                        }  ?>
                                </td>
                                <td> <button class="miniBut"  name="modifier" onclick="show_elem_id('info_mod-<?php echo $id_commande; ?>')"><i class="fa fa-edit"></i></button> </td>
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
    $scr = "SELECT distinct id_commande from commande NATURAL JOIN paiement_cheque ";
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
                                <thead>
                                    <tr>
                                        <th>date paiement</th>
                                        <th>date encaissment</th>
                                        <th>Montant</th>

                                    </tr>
                                </thead>
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
    $scr = "SELECT * from commande NATURAL JOIN etat_commande  ";
    $res = $conn->query($scr);
    $i = 0;
    while ($qe = $res->fetch(PDO::FETCH_ASSOC)) {
        $id_commande = $qe['id_commande'];
        $i++;
    ?>
        <div class="modal" id="info_mod-<?php echo $id_commande; ?>">
            <center>

                <div class="container">
                    <div class="row">
                        <button class="mi" onclick="unshow_elem_id('info_mod-<?php echo $id_commande; ?>');">&times;</button>
                    </div>
                    <form method="POST" action="Update_Com.php">
                        <input type="hidden" name="id-Com" value="<?= $id_commande ?>">
                        <div class="row">
                            <div class="col-25">
                                <label for="etat">Etat commande: </label>
                            </div>
                            <div class="col-75">

                                <select name="etat_com" id="etat-<?= $i ?>" required <?php $etat_c = $qe['etat_com'];
                                                                                        if ($etat_c == 'PAYEE'||$etat_c=='EN ATTENTE D\'ALIMENTATION DU STOCK'){echo "disabled";$chek=false; }else $chek=true;  ?>>
                                    <?php
                                    $scr = "SELECT * from etat_commande";
                                    $result = $conn->query($scr);
                                    $etat_c = $qe['id_etat'];
                                    while ($q = $result->fetch(PDO::FETCH_ASSOC)) {
                                        $id_etat = $q['id_etat'];
                                        $content = $q['etat_com'];
                                        if ($etat_c == $id_etat) {
                                    ?>
                                            <option value="<?= "$id_etat-$content" ?>" selected>
                                                <?= $content ?>
                                            </option>
                                        <?php


                                        } else {
                                        ?>
                                            <option value="<?= "$id_etat-$content" ?>">
                                                <?= $content ?>
                                            </option>
                                    <?php

                                        }
                                    }
                                    ?>



                                </select>

                            </div>
                        </div>
                        <div class="row">
                            <?php
                            $id_espece = $qe['id_paiementE'];
                            $id_carte = $qe['id_paiementCa'];
                            if ($id_espece != '') $type_P = "espese";
                            elseif ($id_carte != '') $type_P = "carte";
                            else {
                                $type_P = "cheque";
                            ?>

                                <div class="col-25">
                                    Gestion chèque:
                                </div>

                                <div class="table-wrapper">
                                    <table class="fl-table">
                                        <thead>
                                            <tr>
                                                <th>date paiement</th>
                                                <th>date encaissment</th>
                                                <th>Montant</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-<?= $id_commande ?>">
                                            <?php
                                            $scr = "SELECT * FROM paiement_cheque where id_commande=$id_commande";
                                            $result = $conn->query($scr);
                                            while ($q = $result->fetch(PDO::FETCH_ASSOC)) {
                                                $id_cheque = $q["id_paiementC"];
                                                $datep = $q['date_paiementC'];
                                                $datee = $q['date_ech'];
                                                $montant = $q['montant'];

                                            ?>
                                                <tr>
                                                    <input type="hidden" name="id_check[]" value="<?= $id_cheque ?>">
                                                    <td><input type="date" id="dateC" value="<?= $datep ?>" disabled></td>
                                                    <td> <input type="date" id="date" name="datee[]" min="<?= $datep ?>" value="<?= $datee ?>" >
                                                    </td>
                                                    <td><input type="number"  class="numberN noVWR" id="num" min="1" value="<?= $montant ?>" disabled style="text-align:center;"></td>

                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        <tbody>
                                    </table>
                                    <button type="button" onclick="Insert_new_check('<?= $id_commande ?>')" class="mi"> <i class="fa fa-plus"></i></button>
                                </div>
                            <?php
                            }
                            ?>
                            <input type="hidden" name="type_P" value="<?= $type_P ?>">
                        </div>
                        <div class="row">
                            <button type="submit" name="Sub" value="He" class="mi" <?php if (!$chek)echo "disabled"; ?> >Confirmer</button>
                        </div>
                    </form>

            </center>
        </div>





    <?php
    }

    ?>
    <?php
    $scr = "SELECT distinct id_commande from commande NATURAL JOIN ligne_commande ";
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
                                <thead>
                                    <tr>
                                        <th>produit</th>
                                        <th>quantité</th>
                                        <th>réduction</th>
                                        <th>prix d'achat(1 unite)</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $scr = "SELECT * FROM ligne_commande NATURAL JOIN produit where id_commande=$id_commande";
                                    $result = $conn->query($scr);
                                    $mt = 0;
                                    while ($q = $result->fetch(PDO::FETCH_ASSOC)) {
                                        $nomp = $q['designation'];
                                        $qte = $q['quantite'];
                                        $red = $q['reduction_ins'] * 100;
                                        $prix = $q['prix_std'] - $q['prix_std'] * $q['reduction_ins'];

                                    ?>
                                        <tr>
                                            <td><?php echo "$nomp"; ?></td>
                                            <td><?php echo "$qte"; ?></td>
                                            <td><?php echo "$red%"; ?></td>
                                            <td><?= $prix ?></td>

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
<script src="JS scripts/jquery.min.js"></script>
<script>
    function Get_Search(str) {
        return document.getElementById(str).value;
    }

    function Insert_new_check(id) {
        $("#table-" + id).append('<tr id="TAJ-' + i + '"><td><input type="date" id="dateC" name="dateC[]" required></td><td> <input type="date" id="date" name="datee[]" ></td><td><input type="number" step="0.01" id="num" name="montant[]" min="1" style="text-align:center;" class="numberN noVWR" required></td><td><button type="button" onclick="remove_html_by_id(\'TAJ-' + i + '\')" ><i class="fa fa-minus" aria-hidden="true"></i></button></td></tr>');
        i++;
    }
</script>

</html>
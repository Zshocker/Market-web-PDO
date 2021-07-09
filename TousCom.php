<?php
session_start();
require_once 'ConnexionToBD.php';

$conn = Conect_ToBD("magasin_en_ligne", "root");

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $scr = "SELECT id_commande,date_com,adresse_liv,id_uti,etat_com,id_paiementE,id_paiementCa FROM commande  NATURAL JOIN etat_commande where date_com like '%$search%' or adresse_liv like '%$search%' ORDER BY id_commande ";
} else
    $scr = "SELECT id_commande,date_com,adresse_liv,id_uti,etat_com,id_paiementE,id_paiementCa FROM commande NATURAL JOIN etat_commande  ORDER BY id_commande";
$result = $conn->query($scr);
?>

<html>

<head>
    <title>Les inscriptions</title>
    <script src="JS Scripts/name.js"></script>
    <link rel="StyleSheet" href="styleForInscrip.css">
    <link rel="StyleSheet" href="tableStyle.css">

    <link rel="stylesheet" href="CssFontA/css/all.css">
</head>

<body style="margin:0px;">
    <div class="bar">
        <div style=" height:100%;">
        <a href="index.php"><img src="rw-markets.png" style="width:auto; height:75%; margin-left:25px;"></a>
            <?php if (!isset($_SESSION['id_uti'])) {
                header("Location: index.php", true, 301);
            } elseif ($_SESSION['type_uti'] != 'admin') {
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
    <div class="sidebar">
            <div class="sideBDiv"><button class="sideBut" onclick="window.location.href='ClientPa.php';">Consulter les produits</button></div>
            <div class="sideBDiv"><button class="sideBut" onclick="window.location.href='PanierPa.php';">Afficher Mon panier</button></div>
            <div class="sideBDiv"><button class="sideBut" onclick="window.location.href='CommandePa.php';">Afficher Mes Commandes</button></div>
            <?php
            if ($_SESSION['type_uti'] == 'admin') {
            ?>
                <div class="sideBDiv"><button class="sideBut" onclick="window.location.href='adminPa.php';">Gestion des produits</button></div>
                <div class="sideBDiv"><button class="sideBut" onclick="window.location.href='ListInscri.php';">Afficher les inscription</button></div>
                <div class="sideBDiv"><button class="sideBut" onclick="window.location.href='ListUti.php';">Afficher les utilisateurs</button></div>
                <div class="sideBDiv"><button class="sideBut" onclick="window.location.href='TousCom.php';"> Tous les Commandes </button></div>
            <?php
            }
            ?>
        </div>
        <div class="MainCont">
            <div class="navBar">
                <input type="text" name="search" class="searchBar" id="searcher" placeholder="Search">
                <button type="submit" class="miniBut" onclick="window.location.href='CommandePa.php?search='+Get_Search('searcher');" style="margin-top:8px; width: 30px; height: 32px;"><i class="fa fa-search"></i></button>
            </div>
            <div class="table-wrapper">
                <table class="fl-table">
                    <thead>
                        <tr>
                            <th>id_commande</th>
                            <th>date_com</th>
                            <th>adresse_liv</th>
                            <th>id_uti</th>
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
                            //$type_c=$qe['type_carte'];

                            ?>
                            <tr>
                                <td><?php echo "$id_commande" ?></td>
                                <td><?php echo "$date" ?></td>
                                <td><?php echo "$adresse" ?></td>
                                <td><?php echo "$id_uti" ?></td>
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
                                            echo "$tp";
                                        } elseif ($var == 3) {
                                            ?>
                                        <button class="miniBut" style="background-color: aqua;" name="afficher" onclick="show_elem_id('info_cheque-<?php echo $id_commande; ?>')"><i class="fa fa-plus"></i></button>
                                    <?php

                                }  ?></td>
                                <td> <button class="miniBut" style="background-color: aqua;" name="modifier" onclick="show_elem_id('info_mod-<?php echo $id_commande; ?>')"><i class="fa fa-edit"></i></button> </td>
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
    $scr = "SELECT * from commande  ";
    $res = $conn->query($scr);
    while ($qe = $res->fetch(PDO::FETCH_ASSOC)) {
        $id_commande = $qe['id_commande'];
        ?>
        <div class="modal" id="info_mod-<?php echo $id_commande; ?>">
            <center>

                <div class="container">
                    <div class="row">
                        <button class="mi" onclick="unshow_elem_id('info_mod-<?php echo $id_commande; ?>');">&times;</button>
                    </div>
                    <form method="POST" action="Update_Com.php">
                        <div class="row">
                            <div class="col-25">
                                <label for="etat">Etat commande: </label>
                            </div>
                            <div class="col-75">
                                <select name="etat_com" id="etat" required>
                                    <?php
                                    $scr = "SELECT * from etat_commande";
                                    $result = $conn->query($scr);
                                    $etat_c = $qe['id_etat'];
                                    while ($q = $result->fetch(PDO::FETCH_ASSOC)) {
                                        $id_etat = $q['id_etat'];
                                        $content = $q['etat_com'];
                                        if ($etat_c == $id_etat) {
                                            ?>
                                            <option value="<?= $id_etat ?>" selected>
                                                <?= $content ?>
                                            </option>
                                        <?php


                                    } else {
                                        ?>
                                            <option value="<?= $id_etat ?>">
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
                            if ($id_espece == '' && $id_carte == '') {
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

                                            </tr>
                                        </thead>
                                        <tbody id="table1">
                                            <?php
                                            $scr = "SELECT * FROM paiement_cheque where id_commande=$id_commande";
                                            $result = $conn->query($scr);
                                            while ($q = $result->fetch(PDO::FETCH_ASSOC)) {
                                                $datep = $q['date_paiementC'];
                                                $datee = $q['date_ech'];
                                                $montant = $q['montant'];

                                                ?>
                                                <tr>
                                                    <td><input type="date" id="dateC" name="dateC[]" value="<?= $datep ?>"disabled></td>
                                                    <td> <input type="date" id="date" name="datee[]" min="<?= $datep ?>" value="<?= $datee ?>">
                                                    </td>
                                                    <td><input type="number" class="numberN" id="num" name="montant[]" min="1" value="<?= $montant ?>" disabled style="text-align:center;"></td>

                                                </tr>
                                            <?php
                                        }
                                        ?>
                                        <tbody>
                                    </table>
                                    <button type="button" id="button_aj" class="mi"> <i class="fa fa-plus"></i></button>
                                </div>


                            <?php
                        }
                        ?>

                        </div>
                    </form>

            </center>
        </div>





    <?php
}
?>


</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    var i=0;
    function Get_Search(str) {
        return document.getElementById(str).value;
    }
    $("#button_aj").click(function() {
        $("#table1").append('<tr id="TAJ-'+i+'"><td><input type="date" id="dateC" name="dateC[]"></td><td> <input type="date" id="date" name="datee[]"></td><td><input type="number" id="num" name="montant[]" min="1" style="text-align:center;" class="numberN"></td><td><button type="button" onclick="remove_html_by_id(\'TAJ-'+i+'\')" ><i class="fa fa-minus" aria-hidden="true"></i></button></td></tr>');
    i++;
    })
</script>

</html>
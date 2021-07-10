<?php
session_start();
require_once 'ConnexionToBD.php';

$conn = Conect_ToBD("magasin_en_ligne", "root");
$id_uti = $_SESSION['id_uti'];


$scr = "SELECT id_uti,nom,prenom,email,adresse,login,mdp,tele FROM utilisateur where id_uti=$id_uti ";
$result = $conn->query($scr);
$qe=$result->fetch(PDO::FETCH_ASSOC);

?>

<html>

<head>
    <title>Mon Compte</title>
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
            <div class="sideBDiv"><button class="sideBut" onclick="window.location.href='MonCompte.php';">Mon Compte</button></div>
            

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
            
            <div class="container1" >
                <center>
                <form action="compte_uti.php" method="POST" enctype="multipart/form-data">

                    <div class="row">
                        <div class="col-25">
                            <label for="nomu"> Nom: </label>
                        </div>
                        <div class="col-75">
                            <input type="text" id="nom" name="nom" value="<?php echo $qe['nom'] ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                            <label for="prenomu">Prenom: </label>
                        </div>
                        <div class="col-75">
                            <input type="text" id="prenom" name="prenom" value="<?php echo $qe['prenom'] ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                            <label for="emailu"> Email </label>
                        </div>
                        <div class="col-75">
                            <input type="text" id="email" name="email" value="<?php echo $qe['email'] ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                            <label for="adresse">Adresse</label>
                        </div>
                        <div class="col-75">
                            <textarea id="adresse" name="adresse" style="height:200px" maxlength="100" required><?php echo $qe['adresse'] ?></textarea>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-25">
                            <label for="adresseu"> Login </label>
                        </div>
                        <div class="col-75">
                            <input type="text" id="login" name="login" value="<?php echo $qe['login'] ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                            <label for="mdpu"> Mot De Pass </label>
                        </div>
                        <div class="col-75">
                            <input type="password" id="mdp" name="mdp"  >
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                            <label for="teleu"> Téléphone </label>
                        </div>
                        <div class="col-75">
                            <input type="text" id="tele" name="tele" value="<?php echo $qe['tele'] ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <input type="submit" name="Submit" value="Update" onclick="return confirm('Are you sure?');">
                    </div>
                </form>
                </center>
            </div>

        </div>


    </div>


</body>


</html>
<div class="sidebar">
    <?php 
    if(isset($_SESSION['id_uti'])){
        ?>
            <div class="sideBDiv" style="margin-top: 10%"><button class="sideBut" onclick="window.location.href='ClientPa.php';"><i class="fas fa-boxes"></i>Accueil</button></div>
            <div class="sideBDiv"><button class="sideBut" onclick="window.location.href='PanierPa.php';"><i class="fas fa-shopping-cart"></i> Mon panier</button></div>
            <div class="sideBDiv"><button class="sideBut" onclick="window.location.href='CommandePa.php';"><i class="far fa-clipboard"></i> Mes Commandes</button></div>
            <div class="sideBDiv"><button class="sideBut" onclick="window.location.href='MonCompte.php';"><i class="fas fa-user"></i>Mon Compte</button></div>
            <?php
            if ($_SESSION['type_uti'] == 'admin') {
            ?>
                <div class="sideBDiv"><button class="sideBut" onclick="window.location.href='adminPa.php';"><i class="fas fa-box-open"></i>Gestion des produits</button></div>
                <div class="sideBDiv"><button class="sideBut" onclick="window.location.href='ListInscri.php';"><i class="fas fa-user-plus"></i> Gestion des inscriptions</button></div>
                <div class="sideBDiv"><button class="sideBut" onclick="window.location.href='ListUti.php';"><i class="fas fa-users"></i>Gestion des utilisateurs</button></div>
                <div class="sideBDiv"><button class="sideBut" onclick="window.location.href='TousCom.php';"><i class="fas fa-columns"></i> Gestion des Commandes </button></div>
            <?php
            }}else{
            ?>
            <center><h2>Welcome To ilisi market</h2></center>
            <?php 
            }
            ?>
        </div>
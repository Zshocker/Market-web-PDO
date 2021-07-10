<div class="sidebar">
    <?php 
    if(isset($_SESSION['id_uti'])){
        ?>
            <div class="sideBDiv"><button class="sideBut" onclick="window.location.href='ClientPa.php';"><i class="fas fa-boxes"></i>Consulter les produits</button></div>
            <div class="sideBDiv"><button class="sideBut" onclick="window.location.href='PanierPa.php';"><i class="fas fa-shopping-cart"></i>Afficher Mon panier</button></div>
            <div class="sideBDiv"><button class="sideBut" onclick="window.location.href='CommandePa.php';"><i class="far fa-clipboard"></i>Afficher Mes Commandes</button></div>
            <div class="sideBDiv"><button class="sideBut" onclick="window.location.href='MonCompte.php';"><i class="fas fa-user"></i>Mon Compte</button></div>
            <?php
            if ($_SESSION['type_uti'] == 'admin') {
            ?>
                <div class="sideBDiv"><button class="sideBut" onclick="window.location.href='adminPa.php';"><i class="fas fa-box-open"></i>Gestion des produits</button></div>
                <div class="sideBDiv"><button class="sideBut" onclick="window.location.href='ListInscri.php';"><i class="fas fa-user-plus"></i>Afficher les inscription</button></div>
                <div class="sideBDiv"><button class="sideBut" onclick="window.location.href='ListUti.php';"><i class="fas fa-users"></i>Afficher les utilisateurs</button></div>
                <div class="sideBDiv"><button class="sideBut" onclick="window.location.href='TousCom.php';"><i class="fas fa-columns"></i> Tous les Commandes </button></div>
            <?php
            }}
            ?>
        </div>
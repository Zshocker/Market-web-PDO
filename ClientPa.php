<?php
session_start();

?>
<html>

<head><meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>

    <link rel="StyleSheet" href="styleForInscrip.css">
    <link rel="StyleSheet" href="prods.css">
    <script src="JS Scripts/name.js"></script>
    <link rel="stylesheet" href="CssFontA/css/all.css">
    <script>
        function Get_Search(str) {
            return document.getElementById(str).value;
        }
    </script>
</head>

<body style="margin:0px;">

    <div class="bar">
        <div style="height:100%;">
            <a href="index.php"><img src="rw-markets.png" style="width: 7%; height: 100%; margin-left:25px;"></a>
            <?php if (!isset($_SESSION['id_uti'])) {
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
                <input type="text" name="search" style="margin-right: 80px!important" class="searchBar" id="searcher" placeholder="Search by name">
                <span style="font-size: 20px">Categorie:</span>
                <select id="CatSearch" name="prodCat" style="float: none !important;" class="searchBar" placeholder="Search by Categorie">
                    <option ></option>
                    <?php
                    require_once 'ConnexionToBD.php';
                    $conn = Conect_ToBD("magasin_en_ligne", "root");
                    $resultE = $conn->query("Select * from categorie");
                    while ($qe = $resultE->fetch(PDO::FETCH_ASSOC)) {
                        $content = $qe['label_cat'];
                        $id = $qe['id_cat'];
                        echo "<option value=\"$id\"> $content </option>";
                    }
                    CloseCon($conn);
                    ?>
                </select>
                <button class="miniBut" onclick="window.location.href='ClientPa.php?search='+Get_Search('searcher')+'&Cat='+Get_Search('CatSearch')" style="margin-top:8px; width: 30px; height: 32px;"><i class="fa fa-search"></i></button>
                <button class="miniBut" onclick="window.location.href='ClientPa.php'" style="margin-top:8px; width: 30px; height: 32px;">&times;</button>
                    
            </div>
            <div>
                <?php
                if (isset($_GET['search'])) {
                    $ser = $_GET['search'];
                    if($_GET['Cat']!=''){
                        $Cat=$_GET['Cat'];
                        $scr = "SELECT id_prod,Designation,prix_std,reduction,prix_barre FROM produit WHERE Designation LIKE '%$ser%' and id_cat=$Cat ORDER BY id_prod ";
                    }else  $scr = "SELECT id_prod,Designation,prix_std,reduction,prix_barre FROM produit WHERE Designation LIKE '%$ser%' ORDER BY id_prod ";
                } else $scr = "SELECT id_prod,Designation,prix_std,reduction,prix_barre FROM produit ORDER BY id_prod ";
                $result = $conn->query($scr);

                while ($qe = $result->fetch(PDO::FETCH_ASSOC)) {
                    $id_prod = $qe['id_prod'];
                    $sc_photo = "SELECT MIN(id_photo),photo FROM photo where id_prod=$id_prod";
                    $rs = $conn->query($sc_photo);
                    $rs = $rs->fetch(PDO::FETCH_ASSOC);
                    $imag = $rs['photo'];
                    $name = $qe['Designation'];
                    $prix = $qe['prix_std'];
                    $red = floatval($qe['reduction']);
                    $prixF = $qe['prix_barre'];
                    $red = $red * 100;
                ?>
                    <div class="boxProd">

                        <div class="ProdImageDiv">
                            <?php
                            if ($red > 0) {
                            ?>
                                <span class="reductionCls">-<?php echo $red; ?>%</span>
                            <?php
                            }
                            ?>
                            <a href="ProdInfo.php?id=<?php echo $id_prod;  ?>">

                                <center><img src="<?php echo $imag; ?>" style="width:200px;height:200px; border-radius:10px;"></center>
                            </a>

                        </div>
                        <div class="ProdInfoDiv">
                            <div style="margin: 5px;">
                                <center><a class="Prod_name" href="ProdInfo.php?id=<?php echo $id_prod;  ?>"><span> <?php echo $name;  ?></span></a></center>
                            </div>
                            <div style="margin-top: 25px; margin-left: 5px; margin-right:5px;">
                                <?php
                                if ($prixF != "0" && $prixF!='') {
                                ?>
                                    <del style="font-weight:bold; margin:5px; float:left;"><?php echo $prixF;  ?>DH</del>
                                <?php
                                }
                                ?>
                                <span style="font-weight:bold; margin:5px; float:left;"><?php echo $prix;  ?>DH</span>

                            </div>
                        </div>


                    </div>

                <?php
                }
                ?>
            </div>
        </div>

    </div>


    <div class="modal" id="inscrip">
        <center>
            <div class="container">
                <div class="row">
                    <button class="mi" onclick="unshow_elem_id('inscrip')">&times;</button>
                </div>
                <form action="insert_inscription.php" method="POST">
                    <div class="row">
                        <div class="col-25">
                            <label for="fname">Prenom: </label>
                        </div>
                        <div class="col-75">
                            <input type="text" id="fname" name="Prenom" placeholder="Votre prenom.." required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                            <label for="lname">Nom: </label>
                        </div>
                        <div class="col-75">
                            <input type="text" id="lname" name="Nom" placeholder="votre Nom.." required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                            <label for="Email">Email: </label>
                        </div>
                        <div class="col-75">
                            <input type="text" id="Email" name="Email" placeholder="votre Email.." required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                            <label for="mdp">Mot de passe: </label>
                        </div>
                        <div class="col-75">
                            <input type="password" id="mdp" name="mdp" placeholder="Creer un mdp.." required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                            <label for="tele">Telephone: </label>
                        </div>
                        <div class="col-75">
                            <input type="text" id="tele" name="tele" placeholder="votre Tel..">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                            <label for="ville">Ville:</label>
                        </div>
                        <div class="col-75">
                            <select id="ville" name="ville">
                                <?php
                                $result = $conn->query("Select * from ville");
                                while ($qe = $result->fetch(PDO::FETCH_ASSOC)) {
                                    $content = $qe['ville'];
                                    $id = $qe['id_ville'];
                                    echo "<option value=\"$id\"> $content </option>";
                                }
                                CloseCon($conn);
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                            <label for="adresse">Adresse</label>
                        </div>
                        <div class="col-75">
                            <textarea id="adresse" name="adresse" placeholder="Write something.." style="height:200px" maxlength="100" required></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <input type="submit" value="Submit">
                    </div>
                </form>
            </div>
        </center>
    </div>
    <div class="modal" id="Login">
        <center>
            <div class="container">
                <div class="row">
                    <button class="mi" onclick="unshow_elem_id('Login')">&times;</button>
                </div>
                <form action="LoginChek.php" method="POST">
                    <div class="row">
                        <div class="col-25">
                            <label for="Log">Login: </label>
                        </div>
                        <div class="col-75">
                            <input type="text" id="log" name="Login" placeholder="Votre Login.." required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                            <label for="md">Mot de passe: </label>
                        </div>
                        <div class="col-75">
                            <input type="password" id="md" name="mdp" placeholder="votre mot de passe.." required>
                        </div>
                        <div class="row">
                            <input type="submit" value="Log In">
                        </div>
                </form>
            </div>
        </center>
    </div>
</body>

</html>
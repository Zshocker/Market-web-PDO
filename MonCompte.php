<?php
session_start();
require_once 'ConnexionToBD.php';

$conn = Conect_ToBD("magasin_en_ligne", "root");
$id_uti = $_SESSION['id_uti'];


$scr = "SELECT id_uti,nom,prenom,email,adresse,login,mdp,tele,id_ville FROM utilisateur where id_uti=$id_uti ";
$result = $conn->query($scr);
$qe=$result->fetch(PDO::FETCH_ASSOC);

?>

<html>

<head><meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte</title>
    <script src="JS Scripts/name.js"></script>
    <link rel="StyleSheet" href="styleForInscrip.css">
    <link rel="StyleSheet" href="tableStyle.css">

    <link rel="stylesheet" href="CssFontA/css/all.css">
</head>

<body style="margin:0px;">
    <div class="bar">
        <div style=" height:100%;">
            <a href="index.php"><img src="rw-markets.png" style="width: 5%; height: 100%; margin-left:25px;"></a>
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
   <?php include "OurSidebar.php"?>
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
              <label for="ville">Ville:</label>
            </div>
            <div class="col-75"> 
              <select id="ville" name="ville" >
                <?php
                $result = $conn->query("Select * from ville");
                while ($q = $result->fetch(PDO::FETCH_ASSOC)) {
                  $content = $q['ville'];
                  $id = $q['id_ville'];
                  if($id==$qe['id_ville'])
                   echo "<option value=\"$id\" selected> $content </option>";
                  else echo "<option value=\"$id\"> $content </option>";
                }
                
                ?>
              </select>
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
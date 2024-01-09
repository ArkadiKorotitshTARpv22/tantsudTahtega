<?php
require_once ("conf.php");
// punktide lisamine
session_start();
if(isset($_REQUEST["punktid0"])){
    global $yhendus;
    $kask=$yhendus->prepare("UPDATE tantsud SET punktid=0 WHERE id=?");
    $kask->bind_param("i", $_REQUEST["punktid0"]);
    $kask->execute();

}
if(isset($_REQUEST["peitmine"])){
    global $yhendus;
    $kask=$yhendus->prepare("UPDATE tantsud SET avalik=0 WHERE id=?");
    $kask->bind_param("i", $_REQUEST["peitmine"]);
    $kask->execute();

}
if(isset($_REQUEST["naitmine"])){
    global $yhendus;
    $kask=$yhendus->prepare("UPDATE tantsud SET avalik=1 WHERE id=?");
    $kask->bind_param("i", $_REQUEST["naitmine"]);
    $kask->execute();

}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tantsud tahtega</title>
</head>
<body>
<header>
    <?php
    if(isset($_SESSION['kasutaja'])){
        ?>
        <h1>Tere, <?="$_SESSION[kasutaja]"?></h1>
        <a href="logout.php">Logi välja</a>
        <?php
    } else {
        ?>
        <a href="login.php">Logi sisse</a>
        <?php
    }
    ?>
</header>
<h1>Tantsud tähtedega</h1>
<h2>AdministreerimisLeht</h2>
<table>
    <tr>
        <th>Tantsupaari nimi</th>
        <th>Punktid</th>
        <th>Kuupäev</th>
        <th>Kommentaarid</th>
        <th>Punktid</th>
    </tr>
<?php
    global $yhendus;
    $kask=$yhendus->prepare("SELECT id, tantsupaar, punktid, ava_paev, kommentaarid, avalik FROM tantsud");
$kask->bind_result($id, $tantsupaar, $punktid, $paev, $kommentaarid, $avalik);
    $kask->execute();
    while($kask->fetch()){
        $tekst="Näita";
        $seisund="naitmine";
        $tekst2="Kasutata ei näe";
        if($avalik==1){
            $tekst="Peida";
            $seisund="peitmine";
            $tekst2="Kasutaja näeb";
        }

        echo "<tr>";
        $tantsupaar=htmlspecialchars($tantsupaar);
        echo "<td>".$tantsupaar."</id>";
        echo "<td>".$punktid."</td>";
        echo "<td>".$paev."</td>";
        echo "<td>".$kommentaarid."</td>";
        echo "<td>".$avalik."/".$tekst2."</td>";
        echo "<td><a href='?punktid0=$id'>Punktid Nulliks!</a></td>";
        echo "<td><a href='?$seisund=$id'>$tekst</a></td>";
        echo "<td>Peida/Näita</td>";
        echo "</tr>";
    }
?>

</table>

</body>
</html>
<!--kasutaja 1. admin õihused: ei saa +1punkt ja -1punkt ja üldse ei näe need lingid kasutaja 2. opilane õigused ei näe faili admin leht.php-->
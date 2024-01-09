<?php
require_once ("conf.php");
session_start();
// punktide lisamine
if(isset($_REQUEST["paarnimi"]) && !empty($_REQUEST["paarinimi"]) && isAdmin()){
    global $yhendus;
    $kask=$yhendus->prepare("INSERT INTO tantsud (tantsupaar, ava_paev) VALUES(?, NOW())");
    $kask->bind_param("s", $_REQUEST["paarinimi"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
    //exit()
}
if(isset($_REQUEST["komment"])) {
    if(isset($_REQUEST["uuskomment"]) && !empty($_REQUEST["uuskomment"]) && isAdmin()) {
    global $yhendus;
    $kask = $yhendus->prepare("UPDATE tantsud set kommentaarid=CONCAT(kommentaarid, ?) WHERE id=?");
    $kommentplus=$_REQUEST["uuskomment"]. "\n";
    $kask->bind_param("si", $kommentplus, $_REQUEST["komment"] );
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
    //exit()
}
}
if(isset($_REQUEST["heatants"])){
    global $yhendus;
    $kask=$yhendus->prepare("UPDATE tantsud SET punktid=punktid+1 WHERE id=?");
    $kask->bind_param("i", $_REQUEST["heatants"]);
    $kask->execute();

}
if(isset($_REQUEST["badtants"])){
    global $yhendus;
    $kask=$yhendus->prepare("UPDATE tantsud SET punktid=punktid-1 WHERE id=?");
    $kask->bind_param("i", $_REQUEST["heatants"]);
    $kask->execute();

}
function isAdmin(){
    return isset($_SESSION['onAdmin']) && $_SESSION['onAdmin'] ;
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
<nav>
    <ul>

        <li>
            <a href="haldusLeht.php">Kasutaja</a>
        </li>
        <?php if(isAdmin()){ ?>
            <li>
                <a href="adminLeht.php">Admin</a>
            </li>
        <?php } ?>
    </ul>
</nav>
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
<h2>Punktide lisamine</h2>
<?php if(isset($_SESSION["kasutaja"])){?>
    <table>
        <tr>
            <th>Tantsupaari nimi</th>
            <th>Punktid</th>
            <th>Kuupäev</th>
            <th>Kommentaarid</th>
        </tr>
        <?php
        global $yhendus;
        $kask=$yhendus->prepare("SELECT id, tantsupaar, punktid, ava_paev, kommentaarid FROM tantsud WHERE avalik=1");
        $kask->bind_result($id, $tantsupaar, $punktid, $paev, $kommentaarid);
        $kask->execute();
        while($kask->fetch()){
            echo "<tr>";
            $tantsupaar=htmlspecialchars($tantsupaar);
            echo "<td>".$tantsupaar."</id>";
            echo "<td>".$punktid."</td>";
            echo "<td>".$paev."</td>";
            echo "<td>".nl2br(htmlspecialchars($kommentaarid))."</td>";

            if(isAdmin()){

            } else {
                echo "<td><a href='?heatants=$id'>Lisa +1punkt</a></td>";
                echo "<td><a href='?badtants=$id'>Lisa -1punkt</a></td>";
            }
            echo "</tr>";
        }
        ?>
        <form action='?'>
            <input type='hidden' value='$id' name='komment'>
            <input type='text' name='uuskomment' id='uuskomment'>
            <input type='submit' value='OK'>
        </form>
        <?php if(isAdmin()){?>


            <form action="?">
                <label for="paarinimi">Lisa uus paar</label>
                <input type="text" name="paarinimi" id="paarinimi">
                <input type="submit" value="Lisa paar">
            </form>
        <?php } ?>
    </table>
<?php } ?>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passwort ändern</title>
</head>
<body>
<?php include ('inc/navigation.php') ?>
<?php include ('inc/inc.php');?>
<h2>Passwort ändern</h2>
<form action="passwort.php" method="post"> 
    <label for="username">Benutzername:</label><br>
    <input type="text" id="username" name="username" required/><br><br>
    <label for="passA">Altes Passwort:</label><br>
    <input type="password" id="password" name="passA" required/><br><br>
    <label for="passN">Neues Passwort:</label><br>
    <input type="password" id="password" name="passN" required/><br><br>
    <label for="passNB">Neues Passwort bestätigen:</label><br>
    <input type="password" id="password" name="passNB" required/><br><br>
    <input id="logbutton" name="changePS" type="submit" value="Passwort ändern">
</form>
<?php
if(isset($_POST['changePS'])){ 
    $error = "Ups etwas ist schiefgegangen! Überprüfen Sie Ihre Angaben.";
    $username = htmlspecialchars(trim($_POST['username']));
    $passAlt = trim($_POST['passA']);
    $passNeu = trim($_POST['passN']);
    $passNB = trim($_POST['passNB']);
    $statement = $conn->prepare("SELECT * FROM benutzer WHERE benutzername = :username"); 
    $statement->execute(array(':username' => $username)); 
    $benutzer = $statement->fetch();

    if ($benutzer && password_verify($passAlt, $benutzer['passwort'])){ 
        if ($passNeu == $passNB){
            $passHash = password_hash($passNeu, PASSWORD_DEFAULT); 
            $sqlUpdate = "UPDATE benutzer SET passwort = $passHash WHERE benutzername = $username AND passwort = $passAlt"; 
            $stmt = $conn->prepare($sqlUpdate);
            $stmt->execute();
            echo "Passwort wurde erfolgreich geändert!";
        }else{
            echo "<div class='error'>";
            echo($error);
            echo "</div>";
        }
    } else{
        echo "<div class='error'>";
        echo($error);
        echo "</div>";
    }
}
?>
<?php include ('inc/footer.php') ?>
</body>
</html>

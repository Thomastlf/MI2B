<?php
session_start();

$utilisateurs = json_decode(file_get_contents("../json/utilisateur.json"), true);

foreach ($utilisateurs as $index => $ligne) {/*on prend l'index car sinon le tableau que l'on modifie n'est qu'une copie*/
    if ($ligne['email'] == $_SESSION['email']) {
        if ($_GET['nom']!="") {
            $utilisateurs[$index]['nom']=$_GET['nom'];
        }
        if ($_GET['prenom']!="") {
            $utilisateurs[$index]['prenom']=$_GET['prenom'];
        }
        if ($_GET['adresse']!="") {
            $utilisateurs[$index]['adresse']=$_GET['adresse'];
        }
        if ($_GET['code']!="") {
            $utilisateurs[$index]['code_interphone']=$_GET['code'];
        }
        if ($_GET['numero']!="") {
            $utilisateurs[$index]['numero']=$_GET['numero'];
        }
    }
}

file_put_contents("../json/utilisateur.json", json_encode($utilisateurs, JSON_PRETTY_PRINT));
?>

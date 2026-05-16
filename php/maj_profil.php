<?php
session_start();

$utilisateurs = json_decode(file_get_contents("../json/utilisateur.json"), true);

foreach ($utilisateurs as $index => $ligne) {/*on prend l'index car sinon le tableau que l'on modifie n'est qu'une copie*/
    if ($ligne['email'] == $_SESSION['email']) {
        $utilisateurs[$index]['nom']=$_GET['nom'];
        $utilisateurs[$index]['prenom']=$_GET['prenom'];
        $utilisateurs[$index]['adresse']=$_GET['adresse'];
        $utilisateurs[$index]['code_interphone']=$_GET['code'];
        $utilisateurs[$index]['numero']=$_GET['numero'];
    }
}

file_put_contents("../json/utilisateur.json", json_encode($utilisateurs, JSON_PRETTY_PRINT));
?>
<?php
session_start(); 
session_unset(); // On détruit toutes les données de la session
header('Location: http://localhost:8000/php/connexion.php'); // Retour à l'accueil ou au login
?>
<?php

$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "";
$nom_base = "library";
$conn = mysqli_connect(
    $serveur,
    $utilisateur,
    $motdepasse,
    $nom_base
);
if (!$conn) {
    die("Connexion échouée : " . mysqli_connect_error());
}


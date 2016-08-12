<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 18/01/2016
     * Time: 13:18
     *
     * Ce script génère la liste des propositions de libellés dans les champs de saisie d'articles sous forme JSON
     */
    header("Content-Type: application/json; charset=UTF-8");
    //TODO: Les 2 lignes ci-dessous ont été ajoutées pour palier au problème de redirection du fichier config.ini depuis le fichier fonctions.php
    $config = parse_ini_file('../../config.ini');
    $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

    $json_articles = array();

    $sql = "SELECT designation_art FROM articles ORDER BY designation_art ASC ";

    if ($resultat = $connexion->query($sql)) {
        $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
        foreach ($ligne as $list) {
            $json_articles[] = $list;
        }
    }

    echo json_encode($json_articles);
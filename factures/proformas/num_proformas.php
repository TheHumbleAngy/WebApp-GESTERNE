<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 31-Mar-17
     * Time: 4:27 PM
     */
    header("Content-Type: application/json; charset=UTF-8");
    //TODO: Les 2 lignes ci-dessous ont été ajoutées pour palier au problème de redirection du fichier config.ini depuis le fichier fonctions.php
    if (!$config = parse_ini_file('../../../config.ini')) $config = parse_ini_file('../../config.ini');
    $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

    $json_proformas = array();

    $sql = "SELECT num_fp FROM proformas";

    if ($resultat = $connexion->query($sql)) {
        $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
        foreach ($ligne as $list) {
            $json_proformas[] = $list;
        }
    }

    echo json_encode($json_proformas);
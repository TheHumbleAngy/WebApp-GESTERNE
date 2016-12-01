<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 07/09/2016
     * Time: 16:33
     */
    header("Content-Type: application/json; charset=UTF-8");
    //TODO: Les 2 lignes ci-dessous ont été ajoutées pour palier au problème de redirection du fichier config.ini depuis le fichier fonctions.php
    include '../fonctions.php';
    $iniFile = 'config.ini';

    while (!$config = parse_ini_file($iniFile))
        configpath($iniFile);
    
    $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

    $json_proformas = array();

    $sql = "SELECT ref_fp FROM proformas ORDER BY ref_fp ASC ";

    if ($resultat = $connexion->query($sql)) {
        $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
        foreach ($ligne as $list) {
            $json_proformas[] = $list;
        }
    }

    echo json_encode($json_proformas);
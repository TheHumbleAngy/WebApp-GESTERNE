<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 26/01/2016
     * Time: 15:07
     *
     * Ce script permet de générer la liste des articles à afficher dans l'aperçu de la forme de saisie des articles
     */
    if ($_GET['opt']) {
        $iniFile = 'config.ini';
        //A modifier selon l'emplacement du fichier
        $config = parse_ini_file('../../' . $iniFile);

        $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
        if ($connexion->connect_error)
            die($connexion->connect_error);
        
        $json_articles = "";

        if ($_GET['opt'] == "saisie")
            $sql = "SELECT code_art, designation_art, description_art, stock_art FROM articles";
        elseif ($_GET['opt'] == "mvt")
            $sql = "SELECT designation_art, stock_art FROM articles";

        if ($resultat = $connexion->query($sql)) {
            foreach ($resultat as $list) {
                $json_articles[] = $list;
            }
            echo json_encode($json_articles);
        }
    }

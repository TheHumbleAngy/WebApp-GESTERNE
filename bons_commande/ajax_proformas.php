<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 17-Jul-15
     * Time: 3:11 PM
     */

    if (!$config = parse_ini_file('../../config.ini')) $config = parse_ini_file('../config.ini');
    $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

//On appelle ici toutes les proformas qui ne figurent pas encore sur un bon de commande
    $sql = "SELECT num_fp FROM proformas";

    $resultat = $connexion->query($sql);
    $option = "";
    $data = $resultat->fetch_all(MYSQLI_NUM);
    $n = $resultat->num_rows;
    for ($i = 0; $i < $n; $i++) {
        $option .= $data[$i][0] . ";";
    }
    echo $option;
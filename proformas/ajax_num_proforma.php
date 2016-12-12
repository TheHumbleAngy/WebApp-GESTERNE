<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 17-Sep-15
     * Time: 10:10 AM
     */

    if (!$config = parse_ini_file('../../config.ini')) $config = parse_ini_file('../config.ini');
    $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

    $req = "SELECT num_fp FROM proformas ORDER BY num_fp DESC LIMIT 1";
    $resultat = $connexion->query($req);

    if ($resultat->num_rows > 0) {
        $ligne = $resultat->fetch_all(MYSQLI_ASSOC);

        $num_fp = "";
        foreach ($ligne as $data) {
            $num_fp = stripslashes($data['num_fp']);
        }

        //extraction des 4 derniers chiffres
        $num_fp = substr($num_fp, -4);

        //incrementation du nombre
        $num_fp += 1;
    } else {
        //s'il n'existe pas d'enregistrements dans la base de donn�es
        $num_fp = 1;
    }

    $b = "FPRO";
    $dat = date("Y");
    $dat = substr($dat, -2);
    $format = '%04d';
    $resultat = $dat . "" . $b . "" . sprintf($format, $num_fp);

    //on affecte au code le resultat
    echo $num_fp = $resultat;
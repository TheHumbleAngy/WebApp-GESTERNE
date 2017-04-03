<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 04-Sep-15
     * Time: 10:48 AM
     */

    if (!$config = parse_ini_file('../../../config.ini')) $config = parse_ini_file('../../config.ini');
    $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
    //On vérifie s'il y a un en registrement dans la base de données
    $req = "SELECT num_bl FROM bons_livraison ORDER BY num_bl DESC LIMIT 1";
    $resultat = $connexion->query($req);

    if ($resultat->num_rows > 0) {
        $ligne = $resultat->fetch_all(MYSQLI_ASSOC);

        //reccuperation du code
        $num_bl = "";
        foreach ($ligne as $data) {
            $num_bl = stripslashes($data['num_bl']);
        }

        //extraction des 4 derniers chiffres
        $num_bl = substr($num_bl, -4);

        //incrementation du nombre
        $num_bl += 1;

        $b = "BL";
        $dat = date("Y");
        $dat = substr($dat, -2);
        $format = '%04d';
        $resultat = $dat . "" . $b . "" . sprintf($format, $num_bl);
        
    } else {
        //s'il n'existe pas d'enregistrements dans la base de donn�es
        $num_bl = 1;
        $b = "BL";
        $dat = date("Y");
        $dat = substr($dat, -2);
        $format = '%04d';
        $resultat = $dat . "" . $b . "" . sprintf($format, $num_bl);
    }
    //on affecte au code le resultat
    echo $num_bl = $resultat;
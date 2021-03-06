<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 04-Sep-15
     * Time: 10:34 AM
     */

    if (!$config = parse_ini_file('../../../config.ini'))
        $config = parse_ini_file('../../config.ini');
    $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

    $req = "SELECT num_bc FROM bons_commande ORDER BY num_bc DESC LIMIT 1";
    $resultat = $connexion->query($req);

    if ($resultat->num_rows > 0) {
        $ligne = $resultat->fetch_all(MYSQLI_ASSOC);

        $num_bc = "";
        foreach ($ligne as $data) {
            $num_bc = stripslashes($data['num_bc']);
        }

        $num_bc = substr($num_bc, -4);

        $num_bc += 1;
    }
    else {
        $num_bc = 1;
    }

    $b = "BC";
    $dat = date("Y");
    $dat = substr($dat, -2);
    $format = '%04d';
    $code = $dat . "" . $b . "" . sprintf($format, $num_bc);

    echo $num_bc = $code;
<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 04-Sep-15
     * Time: 10:41 AM
     */

    if (!$config = parse_ini_file('../../../config.ini'))
        $config = parse_ini_file('../../config.ini');
    $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

    $req = "SELECT num_fact FROM factures ORDER BY num_fact DESC LIMIT 1";
    $resultat = $connexion->query($req);

    if ($resultat->num_rows > 0) {
        $ligne = $resultat->fetch_all(MYSQLI_ASSOC);

        $num_fact = "";
        foreach ($ligne as $data) {
            $num_fact = stripslashes($data['num_fact']);
        }

        $num_fact = substr($num_fact, -4);

        $num_fact += 1;
    }
    else {
        $num_fact = 1;
    }

    $b = "FCT";
    $dat = date("Y");
    $dat = substr($dat, -2);
    $format = '%04d';
    $code = $dat . "" . $b . "" . sprintf($format, $num_fact);

    echo $num_fact = $code;
<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 04-Sep-15
     * Time: 9:58 AM
     */

    $config = parse_ini_file('../../config.ini');
    $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

    $req = "SELECT code_dbs FROM demandes ORDER BY code_dbs DESC LIMIT 1";
    $resultat = $connexion->query($req);

    if ($resultat->num_rows > 0) {
        $ligne = $resultat->fetch_all(MYSQLI_ASSOC);

        $code_dbs = "";
        foreach ($ligne as $data)
            $code_dbs = stripslashes($data['code_dbs']);

        $code_dbs = substr($code_dbs, -4);

        $code_dbs += 1;
    } else
        $code_dbs = 1;

    $b = "DBS";
    $dat = date("Y");
    $dat = substr($dat, -2);
    $format = '%04d';
    $resultat = $dat . "" . $b . "" . sprintf($format, $code_dbs);

    if ($_POST['option'] == "bien_service")
        echo $code_dbs = $resultat;
    else echo "Non";
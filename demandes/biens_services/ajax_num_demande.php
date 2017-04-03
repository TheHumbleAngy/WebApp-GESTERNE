<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 04-Sep-15
     * Time: 9:58 AM
     */

    if (!$config = parse_ini_file('../../../config.ini')) $config = parse_ini_file('../../config.ini');
    $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

    $req = "SELECT num_dbs FROM demandes ORDER BY num_dbs DESC LIMIT 1";
    $resultat = $connexion->query($req);

    if ($resultat->num_rows > 0) {
        $ligne = $resultat->fetch_all(MYSQLI_ASSOC);

        $num_dbs = "";
        foreach ($ligne as $data)
            $num_dbs = stripslashes($data['num_dbs']);

        $num_dbs = substr($num_dbs, -4);

        $num_dbs += 1;
    } else
        $num_dbs = 1;

    $b = "DBS";
    $dat = date("Y");
    $dat = substr($dat, -2);
    $format = '%04d';
    $resultat = $dat . "" . $b . "" . sprintf($format, $num_dbs);

    if ($_POST['option'] == "bien_service")
        echo $num_dbs = $resultat;
    else echo "Non";
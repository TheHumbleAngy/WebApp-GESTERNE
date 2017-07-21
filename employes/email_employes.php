<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 24/03/2016
     * Time: 14:21
     *
     * Ce script génère la liste des propositions de libellés dans les champs de saisie d'articles sous forme JSON
     */
    header("Content-Type: application/json; charset=UTF-8");
    
    $json_email = array();

    $config = parse_ini_file('../../config.ini');
    $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
    if ($connexion->connect_error)
        die($connexion->connect_error);

    $sql = "SELECT email_emp FROM employes ORDER BY email_emp ASC ";

    if ($resultat = $connexion->query($sql)) {
        $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
        foreach ($ligne as $list) {
            $json_email[] = $list;
        }
        
        echo json_encode($json_email);
    }
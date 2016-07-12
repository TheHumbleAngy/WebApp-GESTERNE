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
    require_once '../bd/connection.php';

    $json_email = array();

    $sql = "SELECT email_emp FROM employes ORDER BY email_emp ASC ";

    if ($resultat = $connexion->query($sql)) {
        $ligne = $resultat->fetch_all(MYSQL_ASSOC);
        foreach ($ligne as $list) {
            $json_email[] = $list;
        }

//    print json_encode($json_email);
        echo json_encode($json_email);
    }
<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 29/08/2016
     * Time: 18:20
     */

    if (isset($_POST['id']) && isset($_GET['operation']) && $_GET['operation'] == "suppr") {
        //TODO: Suppression des infos depuis la form liste_proformas

        $id = $_POST['id'];

        if (!$config = parse_ini_file('../../config.ini')) $config = parse_ini_file('../config.ini');
        $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

        $sql = "DELETE FROM proformas WHERE ref_fp = '" . $id . "'";

        if ($result = mysqli_query($connexion, $sql)) {
            echo "
            <div class='alert alert-success alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                <strong>Succès!</strong><br/> La facture proforma " . $id . " a été supprimée de la base.
            </div>
            ";
        } else {
            echo "
            <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                <strong>Erreur!</strong><br/><br/>
                La facture proforma " . $id . " est liée à certains formulaires et ne peut donc pas être supprimée de la base.<br/>
                Veuillez donc contacter un administrateur.
            </div>
            ";
        }
    }
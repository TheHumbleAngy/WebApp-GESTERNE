<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 01/09/2016
     * Time: 17:12
     */

    if (isset($_POST['id']) && isset($_GET['operation']) && $_GET['operation'] == "suppr") {

    $id = $_POST['id'];

    if (!$config = parse_ini_file('../../../config.ini')) $config = parse_ini_file('../../config.ini');
    $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

    $sql = "DELETE FROM bons_livraison WHERE num_bl = '" . $id . "'";

    if ($result = mysqli_query($connexion, $sql)) {
        echo "
            <div class='alert alert-success alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                <strong>Succès!</strong><br/> Le bon de livraison " . $id . " a été supprimée de la base.
            </div>
            ";
    } else {
        echo "
            <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                <strong>Erreur!</strong><br/><br/>
                Le bon de livraison " . $id . " est lié à certains formulaires et ne peut donc pas être supprimé de la base.<br/>
                Veuillez donc contacter un administrateur.
            </div>
            ";
    }
}
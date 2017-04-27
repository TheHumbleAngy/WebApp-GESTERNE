<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 29/08/2016
     * Time: 18:20
     */

    session_start();
    if (isset($_POST['id']) && isset($_GET['operation']) && $_GET['operation'] == "suppr") {
        //TODO: Suppression des infos depuis la form liste_proformas

        $id = $_POST['id'];

        if (!$config = parse_ini_file('../../../config.ini')) $config = parse_ini_file('../../config.ini');
        $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

        $sql = "DELETE FROM proformas WHERE num_fp = '" . $id . "'";

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
    } elseif (isset($_GET['operation']) && $_GET['operation'] == "ajout_proforma") {
        include 'class_proformas.php';
        
        $nbr = $_POST['i'];
        $num_pro = $_POST['num_pro'];
        $code_four = $_POST['code_four'];
        $date_eta = $_POST['date_eta'];
        $date_rec = $_POST['date_rec'];
        $notes_fp = $_POST['notes_fp'];

        $proforma = new proformas();

        if ($proforma->recuperer($num_pro)) {
            $arr_libelle = json_decode($_POST['libelle_dp']);
            $arr_qte = json_decode($_POST['qte_dp']);
            $arr_pu = json_decode($_POST['pu_dp']);
            $arr_rem = json_decode($_POST['rem_dp']);

            if ($proforma->enregistrer()) {
                $details_proforma = new detail_proformas();

                $no_error = 0;
                for ($i = 0; $i < $nbr; $i++) {
                    if ($details_proforma->recuperer_detail($arr_libelle[$i], $arr_qte[$i], $arr_pu[$i], $arr_rem[$i])) {
                        if (!$details_proforma->enregistrer_detail($proforma->recup_num())) {
                            $no_error = 1;
                            break;
                        }
                    } else {
                        $no_error = 2;
                        break;
                    }
                }

                if ($no_error == 0) {
                    echo "
                    <div class='alert alert-success alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                        <strong>Succès!</strong><br/> La proforma " . $num_pro . " a été bien enregistrée.
                    </div>
                    ";
                }
                elseif ($no_error == 1) {
                    if ($proforma->supprimer($num_pro))
                        echo "
                        <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                            <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative d'enregistrement de la proforma. Veuillez contacter l'administrateur.
                        </div>
                        ";
                    else
                        echo "
                        <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                            <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative d'enregistrement de la proforma. 
                            Veuillez contacter l'administrateur (La proforma n'a pas été supprimée).
                        </div>
                        ";
                }
                elseif ($no_error == 2) {
                    if ($proforma->supprimer($num_pro))
                        echo "
                        <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                            <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative de recupereation des details de la proforma. 
                            Veuillez contacter l'administrateur.
                        </div>
                        ";
                    else
                        echo "
                        <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                            <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative de recupereation des details de la proforma. 
                            Veuillez contacter l'administrateur (La proforma n'a pas été supprimée).
                        </div>
                        ";
                }
            }
            else {
                echo "
                    <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                        <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative d'enregistrement de la proforma. 
                        Veuillez contacter l'administrateur.
                    </div>
                    ";
            }
                
        } else {
            echo "
                <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                    <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative de récupération des informations de la proforma. 
                    Veuillez contacter l'administrateur.
                </div>
                ";
        }
    }
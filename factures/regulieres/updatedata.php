<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 01/09/2016
     * Time: 11:30
     */

    session_start();
    if (isset($_GET['operation']) && $_GET['operation'] == "ajout_facture") {
        include 'class_factures.php';

        $nbr = $_POST['i'];
        $num_fact = $_POST['num_fact'];
        $ref_fact = $_POST['ref_fact'];
        $code_four = $_POST['code_four'];
        $date_e = $_POST['date_e'];
        $date_r = $_POST['date_r'];
        $rem_fact = $_POST['rem_fact'];
        
        $facture = new factures();

        if ($facture->recuperer($num_fact, $code_four, $ref_fact, $date_e, $date_r, $rem_fact)) {
            $arr_libelle = json_decode($_POST['libelle_dfact']);
            $arr_qte = json_decode($_POST['qte_dfact']);
            $arr_pu = json_decode($_POST['pu_dfact']);
            $arr_rem = json_decode($_POST['rem_dfact']);

            if ($facture->enregistrer()) {
                $detail_facture = new details_factre();

                $no_error = 0; //echo $nbr;
                for ($i = 0; $i < $nbr; $i++) {
                    if ($detail_facture->recuperer_details($arr_libelle[$i], $arr_qte[$i], $arr_pu[$i], $arr_rem[$i])) {
                        if (!$detail_facture->enregistrer_details($facture->recup_num())) {
                            $no_error = 1;
                            break;
                        }
                        //$detail_facture->enregistrer_details($facture->recup_num());
                    }
                    else {
                        $no_error = 2;
                        break;
                    }
                }

                echo $no_error;
                if ($no_error == 0) {
                    echo "
                    <div class='alert alert-success alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                        <strong>Succès!</strong><br/> La facture " . $num_fact . " a été bien enregistrée.
                    </div>
                    ";
                }
                elseif ($no_error == 1) {
                    if ($facture->supprimer($num_fact))
                        echo "
                        <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                            <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative d'enregistrement de la facture. Veuillez contacter l'administrateur.
                        </div>
                        ";
                    else
                        echo "
                        <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                            <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative d'enregistrement de la facture. 
                            Veuillez contacter l'administrateur (La facture n'a pas été supprimée).
                        </div>
                        ";
                }
                elseif ($no_error == 2) {
                    if ($facture->supprimer($num_fact))
                        echo "
                        <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                            <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative de recupereation des details de la facture. 
                            Veuillez contacter l'administrateur.
                        </div>
                        ";
                    else
                        echo "
                        <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                            <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative de recupereation des details de la facture. 
                            Veuillez contacter l'administrateur (La facture n'a pas été supprimée).
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
                        <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative d'enregistrement de la facture. 
                        Veuillez contacter l'administrateur.
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
                    <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative de récupération des informations de la facture. 
                    Veuillez contacter l'administrateur.
                </div>
                ";
        }
    }
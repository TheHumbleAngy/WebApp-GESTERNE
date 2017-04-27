<?php
    /**
     * Created by PhpStorm.
     * User: ange-marius.kouakou
     * Date: 26/08/2015
     * Time: 6:59PM
     */

    session_start();
    if (isset($_POST['id']) && isset($_GET['operation']) && $_GET['operation'] == "suppr") {
        //TODO: Suppression des infos depuis la form liste_demandes

        $id = $_POST['id'];
        
        include_once 'class_demandes.php';

        $demande = new demandes();

        if ($demande->supprimer($id)) {
            echo "
            <div class='alert alert-success alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                <strong>Succès!</strong><br/> La demande " . $id . " a été supprimée de la base.
            </div>
            ";
        } else {
            echo "
            <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                <strong>Erreur!</strong><br/><br/>
                La demande " . $id . " est liée à certains formulaires et ne peut donc pas être supprimée de la base.<br/>
                Veuillez donc contacter un administrateur.
            </div>
            ";
        }
    } elseif (isset($_GET['operation']) && $_GET['operation'] == "ajout_demande_bs") {
        include 'class_demandes.php';
        
        $nbr = $_POST['i'];
        $num_dmd = $_POST['num_dmd'];
        $objet_dmd = $_POST['objet_dmd'];

        $demande = new demandes();

        if ($demande->recuperer($_SESSION['user_id'])) {
            $arr_libelle = json_decode($_POST['libelle_dd']);
            $arr_nature = json_decode($_POST['nature_dd']);
            $arr_qte = json_decode($_POST['qte_dd']);
            $arr_obsv = json_decode($_POST['obsv_dd']);

            if ($demande->enregistrer()) {
                $details_demande = new details_demandes();

                $no_error = 0;
                for ($i = 0; $i < $nbr; $i++) {
                    if ($details_demande->recuperer_detail($arr_libelle[$i], $arr_nature[$i], $arr_qte[$i], $arr_obsv[$i])) {
                        if (!$details_demande->enregistrer_detail($demande->recup_num())) {
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
                        <strong>Succès!</strong><br/> La demande " . $num_dmd . " a été bien enregistrée.
                    </div>
                    ";
                }
                elseif ($no_error == 1) {
                    if ($demande->supprimer($num_dmd))
                        echo "
                        <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                            <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative d'enregistrement de la demande. Veuillez contacter l'administrateur.
                        </div>
                        ";
                    else
                        echo "
                        <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                            <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative d'enregistrement de la demande. 
                            Veuillez contacter l'administrateur (La demande n'a pas été supprimée).
                        </div>
                        ";
                }
                elseif ($no_error == 2) {
                    if ($demande->supprimer($num_dmd))
                        echo "
                        <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                            <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative de recupereation des details de la demande. 
                            Veuillez contacter l'administrateur.
                        </div>
                        ";
                    else
                        echo "
                        <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                            <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative de recupereation des details de la demande. 
                            Veuillez contacter l'administrateur (La demande n'a pas été supprimée).
                        </div>
                        ";
                }
            } else {
                echo "
                    <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                        <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative d'enregistrement de la demande. 
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
                    <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative de récupération des informations de la demande. 
                    Veuillez contacter l'administrateur.
                </div>
                ";
        }
    }
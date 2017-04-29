<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 28-Apr-17
     * Time: 2:22 PM
     */

    session_start();
    if (isset($_GET['operation']) && $_GET['operation'] == "ajout_bon_cmd") {
        include 'class_bons_commandes.php';
        
        $nbr = $_POST['i'];
        $num_bc = $_POST['num_bc'];
        $code_four = $_POST['code_four'];
        
        $bon_commande = new bons_commandes();
        
        if ($bon_commande->recuperer($num_bc, $code_four)) {
            $arr_libelle = json_decode($_POST['libelle_dbc']);
            $arr_qte = json_decode($_POST['qte_dbc']);
            $arr_pu = json_decode($_POST['pu_dbc']);
            $arr_rem = json_decode($_POST['rem_dbc']);

            //print_r($arr_rem);
            if ($bon_commande->enregistrer()) {
                $detail_bon_commande = new details_bons_commande();

                $no_error = 0;
                for ($i = 0; $i < $nbr; $i++) {
                    if ($detail_bon_commande->recuperer_detail($arr_libelle[$i], $arr_qte[$i], $arr_pu[$i], $arr_rem[$i])) {
                        if (!$detail_bon_commande->enregistrer_detail($bon_commande->recup_num())) {
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
                        <strong>Succès!</strong><br/> Le bon de commande " . $num_bc . " a été bien enregistré.
                    </div>
                    ";
                }
                elseif ($no_error == 1) {
                    if ($bon_commande->supprimer($num_bc))
                        echo "
                        <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                            <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative d'enregistrement du bon de commande. Veuillez contacter l'administrateur.
                        </div>
                        ";
                    else
                        echo "
                        <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                            <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative d'enregistrement du bon de commande. 
                            Veuillez contacter l'administrateur (Le bon n'a pas été supprimé).
                        </div>
                        ";
                }
                elseif ($no_error == 2) {
                    if ($bon_commande->supprimer($num_bc))
                        echo "
                        <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                            <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative de recupereation des details du bon de commande. 
                            Veuillez contacter l'administrateur.
                        </div>
                        ";
                    else
                        echo "
                        <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                            <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative de recupereation des details du bon de commande. 
                            Veuillez contacter l'administrateur (Le bon n'a pas été supprimé).
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
                        <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative d'enregistrement du bon de commande. 
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
                    <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative de récupération des informations du bon de commande. 
                    Veuillez contacter l'administrateur.
                </div>
                ";
        }
    }
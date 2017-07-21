<?php
    /**
     * Created by PhpStorm.
     * User: ange-marius.kouakou
     * Date: 26/08/2015
     * Time: 16:05
     */

    if (isset($_GET['operation']) && $_GET['operation'] == "ajouter") {
        include 'class_employes.php';

        $employe = new employes();

        if ($employe->recuperer()) {
            $employe->motdepasse("ncare");
            if ($employe->enregistrer()) {
                echo "
                <div class='alert alert-success alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                    " . $_POST['nom_emp'] . " a été enregistré avec <strong>succès</strong>.
                </div>
                ";
            } else {
                echo "
                <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                    <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative d'enregistrement des informations. Veuillez contacter l'administrateur.
                </div>
                ";
            }
        } else {
            echo "
            <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative de récupération des informations. Veuillez contacter l'administrateur.
            </div>
            ";
        }

    }
    elseif (isset($_GET['code_emp']) && isset($_GET['operation']) && $_GET['operation'] == "maj") {
        $code_emp = $_GET['code_emp'];
        
        include 'class_employes.php';

        $employe = new employes();

        if ($employe->recuperer()) {
            if ($employe->modifier($code_emp)) {
                echo "
                <div class='alert alert-success alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                    <strong>Succès!</strong><br/> Les informations sur l'employé " . $code_emp . " ont été mises à jour.
                </div>
                ";
            } else {
                echo "
                <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                    <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative de modifier de l'employé " . $code_emp . ". Veuillez contacter l'administrateur.
                </div>
                ";
            }
        } else {
            echo "
            <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative de récupération des informations. Veuillez contacter l'administrateur.
            </div>
            ";
        }

    }
    elseif (isset($_POST['id']) && isset($_GET['operation']) && $_GET['operation'] == "supprimer") {
        //TODO: Suppression des infos depuis la form liste_employes

        $id = $_POST['id'];
        
        include 'class_employes.php';

        $employe = new employes();

        if ($employe->supprimer($id)) {
            echo "
            <div class='alert alert-success alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                <strong>Succès!</strong><br/> L'employé " . $id . " a été supprimé de la base.
            </div>
            ";
        } else {
            echo "
            <div class='alert alert-warning alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                <strong>Attention!</strong><br/>
                L'employé <strong>" . $id . "</strong> ne peut être supprimé. Soit, il s'agit de l'utilisateur en cours, soit il est lié à certains formulaires.<br/>
                Veuillez donc contacter un administrateur.
            </div>
            ";
        }

    }
    elseif (isset($_POST['code_emp']) || isset($_POST['action'])) {
        //TODO: MAJ et supprimer des infos depuis les forms modif_employes et suppr_employes

        $code = $_POST['code_emp'];
        
        include 'class_employes.php';

        $employe = new employes();

        if ($employe->recuperer()) {
            if ($_POST['action'] == "maj") {
                $employe->modifier($code);
                if ($employe->modifier($code)) {
                    header("refresh:3;url=form_principale.php?page=administration&source=employes");
                    echo "
                    <div style='width: 80%; margin-right: auto; margin-left: auto; margin-top: 10%'>
                        <div class='alert alert-success alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                            <a href='form_principale.php?page=administration&source=employes' type='button' class='close'
                                   data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                    <span aria-hidden='true'>&times;</span>
                            </a>
                            <p><strong>Succès!</strong><br/> Les informations sur l'employé " . $code . " ont été mises a jour.</p>
                        </div>
                    </div>
                    ";
                } else {
                    echo "
                    <div style='width: 80%; margin-right: auto; margin-left: auto; margin-top: 10%'>
                        <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                            <a href='form_principale.php?page=administration&source=employes' type='button' class='close'
                                   data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                    <span aria-hidden='true'>&times;</span>
                            </a>
                            <strong>Erreur!</strong><br/><br/>
                            Une erreur s'est produite lors de la tentative de modification des informations sur l'employé <strong>" . $code . "</strong>.<br/>
                            Veuillez contacter votre administrateur.
                        </div>
                    </div>
                    ";
                }
            } elseif ($_POST['action'] == "supprimer") {
                if ($employe->supprimer($code)) {
                    header("refresh:3;url=form_principale.php?page=administration&source=employes");
                    echo "
                    <div style='width: 80%; margin-right: auto; margin-left: auto; margin-top: 10%'>
                        <div class='alert alert-success alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                            <a href='form_principale.php?page=administration&source=employes' type='button' class='close'
                                   data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                    <span aria-hidden='true'>&times;</span>
                            </a>
                            <strong>Succès!</strong><br/> L'employé " . $code . " a été supprimé de la base.
                        </div>
                    </div>
                    ";
                } else {
                    echo "
                    <div style='width: 80%; margin-right: auto; margin-left: auto; margin-top: 10%'>
                        <div class='alert alert-warning alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                            <a href='form_principale.php?page=administration&source=employes' type='button' class='close'
                                   data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                    <span aria-hidden='true'>&times;</span>
                            </a>
                            <strong>Attention!</strong><br/>
                            L'employé <strong>" . $code . "</strong> ne peut être supprimé. Soit, il s'agit de l'utilisateur en cours, soit il est lié à certains formulaires.<br/>
                            Veuillez donc contacter un administrateur.
                        </div>
                    </div>
                    ";
                }
            } else {
                echo "
                <div style='width: 80%; margin-right: auto; margin-left: auto; margin-top: 10%'>
                    <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                        <a href='form_principale.php?page=administration&source=employes' type='button' class='close'
                               data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                <span aria-hidden='true'>&times;</span>
                        </a>
                        <strong>Erreur!</strong><br/><br/>
                        Une erreur s'est produite lors de la tentative de récupération des informations entrées.<br/>
                        Veuillez contacter votre administrateur.
                    </div>
                </div>
                ";
            }
        }
    }
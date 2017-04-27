<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 26/11/2015
     * Time: 15:03
     */
    session_start();
    if (isset($_GET['operation']) && $_GET['operation'] == "ajout") {
        //TODO: Enregistrement d'un nouvel article
        
        include 'class_articles.php';

        $article = new articles();

        if ($article->recuperer()) {
            if ($article->enregistrer()) {
                echo "
                <div class='alert alert-success alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                    L'article a été enregistré avec <strong>succès</strong>.
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
            <div style='width: 480px; margin-right: auto; margin-left: auto'>
                <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                    <a href='form_principale.php?page=articles/form_articles' type='button' class='close'
                           data-dismiss='alert' aria-label='Close' style='position: inherit'>
                            <span aria-hidden='true'>&times;</span>
                    </a>
                    Une erreur s'est produite lors de la tentative de récupération des informations entrées.
                </div>
            </div>
            ";
        }
    }
    elseif (isset($_GET['id']) && isset($_GET['operation']) && $_GET['operation'] == "maj") {
        //TODO: MAJ des infos depuis la form liste_articles

        $id = $_GET['id'];
        
        include_once 'class_articles.php';

        $article = new articles();

        if ($article->recuperer()) {
            if ($article->modifier($id)) {
                echo "
                <div class='alert alert-success alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                    <strong>Succès!</strong><br/> Les informations sur l'article " . $id . " ont été mises à jour.
                </div>
                ";
            } else {
                echo "
                <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                    <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative de modifier de l'article " . $id . ". Veuillez contacter l'administrateur.
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
    elseif (isset($_POST['id']) && isset($_GET['operation']) && $_GET['operation'] == "suppr") {
        //TODO: Suppression des infos depuis la form liste_articles

        $id = $_POST['id'];
        
        include_once 'class_articles.php';

        $article = new articles();

        if ($article->supprimer($id)) {
            echo "
            <div class='alert alert-success alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                <strong>Succès!</strong><br/> L'article " . $id . " a été supprimé de la base.
            </div>
            ";
        } else {
            echo "
            <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                <strong>Erreur!</strong><br/><br/>
                L'article " . $id . " est lié à certains formulaires et ne peut donc pas être supprimé de la base.<br/>
                Veuillez donc contacter un administrateur.
            </div>
            ";
        }

    }
    elseif (isset($_POST['code_art']) || isset($_POST['action'])) {
        //TODO: MAJ et supprimer des infos depuis les forms modif_articles et suppr_articles

        $code = $_POST['code_art'];
        include_once 'class_articles.php';

        $article = new articles();

        if ($article->recuperer()) {
            if ($_POST['action'] == "maj") {
                if ($article->modifier($code)) {
                    header("refresh:3;url=form_principale.php?page=form_actions&source=articles&action=modifier");
                    echo "
                    <div style='width: 80%; margin-right: auto; margin-left: auto; margin-top: 10%'>
                        <div class='alert alert-success alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                            <a href='form_principale.php?page=form_actions&source=articles&action=modifier' type='button' class='close'
                                   data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                    <span aria-hidden='true'>&times;</span>
                            </a>
                            <p><strong>Succès!</strong><br/> Les informations sur l'article " . $code . " ont été mises a jour.</p>
                        </div>
                    </div>
                    ";
                }
            } elseif ($_POST['action'] == "supprimer") {
                if ($article->supprimer($code)) {
                    header("refresh:3;url=form_principale.php?page=form_actions&source=articles&action=supprimer");
                    echo "
                    <div style='width: 80%; margin-right: auto; margin-left: auto; margin-top: 10%'>
                        <div class='alert alert-success alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                            <a href='form_principale.php?page=suppr_articles' type='button' class='close'
                                   data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                    <span aria-hidden='true'>&times;</span>
                            </a>
                            <strong>Succès!</strong><br/> L'article " . $code . " a été supprimé de la base.
                        </div>
                    </div>
                    ";
                } else {
                    echo "
                    <div style='width: 80%; margin-right: auto; margin-left: auto; margin-top: 10%'>
                        <div class='alert alert-warning alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                            <a href='form_principale.php?page=suppr_articles' type='button' class='close'
                                   data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                    <span aria-hidden='true'>&times;</span>
                            </a>
                            <strong>Erreur!</strong><br/><br/>
                            L'article " . $code . " est lié à certains formulaires et ne peut donc pas être supprimé de la base.<br/>
                            Veuillez donc contacter un administrateur.
                        </div>
                    </div>
                    ";
                }
            } else {
                echo "
                <div style='width: 80%; margin-right: auto; margin-left: auto; margin-top: 10%'>
                    <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                        <a href='form_principale.php' type='button' class='close'
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
    elseif (isset($_GET['operation']) && $_GET['operation'] == "ajout_sortie_demande") {
        include 'class_articles.php';
        session_start();
        $sortie = new sorties_articles();
        $sortie->recuperer($_SESSION['user_id'], $_POST['nbr']);
//        if () { $sortie->enregistrer();
//            /*if ($sortie->enregistrer()) {
//                echo "Great!";
//            } else {
//                echo "Oops!";
//            }*/
//        } else
//            echo "
//            <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
//                <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
//                    <span aria-hidden='true'>&times;</span>
//                </button>
//               <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative de récupération de la demande. Veuillez contacter l'administrateur.
//            </div>
//            ";
    }
    elseif (isset($_POST['i']) && isset($_GET['operation']) && $_GET['operation'] == "entrer") {
        include 'class_articles.php';
        $nbr = $_POST['i'];

        $entree = new entrees_articles();

        if ($entree->recuperer($_SESSION['user_id'])) {

            $arr_libelle = json_decode($_POST['libelle']);
            $arr_qte = json_decode($_POST['qte']);
            $arr_obsv = json_decode($_POST['obsv']);

            $n = sizeof($arr_libelle);

            if ($entree->enregistrer($n, $arr_libelle, $arr_qte, $arr_obsv)) {
                echo "Success!";
            } else {
                echo "Une erreur s'est produite lors de la tentative d'enregistrement";
            }
        }
        else
            echo "Une erreur s'est produite lors de la tentative de récupération de l'entrée de stock. Veuillez contacter l'administrateur.";
    }
    elseif (isset($_POST['i']) && isset($_GET['operation']) && $_GET['operation'] == "sortir") {
        include 'class_articles.php';
        $nbr = $_POST['i'];

        $sortie = new sorties_articles();

        if ($sortie->recuperer($_SESSION['user_id'])) {

            $arr_libelle = json_decode($_POST['libelle']);
            $arr_qte = json_decode($_POST['qte']);
            $arr_obsv = json_decode($_POST['obsv']);

            $n = sizeof($arr_libelle);
            
            if ($sortie->enregistrer($n, $arr_libelle, $arr_qte, $arr_obsv)) {
                echo "Success!";
            } else {
                echo "Une erreur s'est produite lors de la tentative d'enregistrement";
            }
        }
        else
            echo "Une erreur s'est produite lors de la tentative de récupération de l'entrée de stock. Veuillez contacter l'administrateur.";
    }
    elseif (isset($_POST['i']) && isset($_GET['operation']) && $_GET['operation'] == "sortir_demande" && isset($_POST['n'])) {
        include 'class_articles.php';
        $nbr = $_POST['i'];
        $nbr_dmd = $_POST['n'];

        $sortie = new sorties_articles();

        if ($sortie->recuperer($_SESSION['user_id'])) {

            $arr_libelle = json_decode($_POST['libelle']);
            $arr_qte = json_decode($_POST['qte']);
            $arr_obsv = json_decode($_POST['obsv']);
            $arr_num_dmd = json_decode($_POST['num_dmd']);
            $arr_num_dd = json_decode($_POST['num_dd']);

            $n = sizeof($arr_libelle);

            if ($sortie->recup_demandes($arr_num_dmd, $arr_num_dd, $nbr_dmd)) {
//                $sortie->enregistrer($n, $arr_libelle, $arr_qte, $arr_obsv);
                if ($sortie->enregistrer($n, $arr_libelle, $arr_qte, $arr_obsv)) {
                    echo "Success!";
                } else {
                    echo "Une erreur s'est produite lors de la tentative d'enregistrement";
                }
            }
        }
        else
            echo "Une erreur s'est produite lors de la tentative de récupération de l'entrée de stock. Veuillez contacter l'administrateur.";
    }
<?php
//    error_reporting(0);
    ob_start();
    session_start();
    require_once 'fonctions.php';

    if (!isset($_SESSION['user_id'])) { //TODO: conditionner aussi le paramètre de connexion
        header('Location: index.php');
    }

    setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

    $_SESSION['last_activity'] = time();
    /*if ((int)$_SESSION['last_activity'] - (int)$_SESSION['login_time'] > 5 * 60) {
        header('Location: processing.php');
    }*/


    $page = "accueil";
    if (isset($_GET['page']))
        $page = $_GET['page'];
    $page .= '.php';

    header('Content-Type: text/html; charset=utf-8');

    $connexion = db_connect();

    $sql = "SELECT nom_emp, prenoms_emp FROM employes WHERE code_emp = '" . $_SESSION['user_id'] . "'";
    if ($resultat = $connexion->query($sql)) {
        $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
        $droit = "normal";
        $req = "SELECT libelle_droit FROM droits WHERE code_emp = '" . $_SESSION['user_id'] . "'";
        if ($res = $connexion->query($req)) {
            $rows = $res->fetch_all(MYSQLI_ASSOC);
            $droit = $rows[0]['libelle_droit'];
        }

        foreach ($ligne as $data) {
            $nom = $data['prenoms_emp'] . ' ' . $data['nom_emp'];
            ?>

            <!DOCTYPE html>
            <head>
                <meta charset="utf-8">
                <link type="text/css" href="css_js/bootstrap-3.3.4-dist/css/bootstrap.css" rel="stylesheet">
                <link type="text/css" href="css_js/bootstrap-3.3.4-dist/css/bootstrap-theme.css" rel="stylesheet">
                <link type="text/css" href="css_js/bootstrap-table-master/src/bootstrap-table.css" rel="stylesheet">
                <link type="text/css" href="css_js/menu_nav.css" rel="stylesheet"/>
                <link type="text/css" href="css_js/stylish.css" rel="stylesheet">
                <link type="text/css" href="css_js/jquery-ui-1.11.4.custom/jquery-ui.min.css" rel="stylesheet">
                <link type="text/css" href="css_js/windows-10-icons-1.0.0/windows-10-icons-1.0.0/font/styles.min.css"
                      rel="stylesheet">
                <link type="text/css" href="css_js/slick.css" rel="stylesheet">
                <link type="text/css" href="css_js/slick-theme.css" rel="stylesheet">
                <!--<link type="text/css" href="css_js/unslider.css" rel="stylesheet">-->

                <script src="css_js/bootstrap-3.3.4-dist/js/jquery-1.11.3.js"></script>
                <script src="css_js/bootstrap-3.3.4-dist/js/bootstrap.js"></script>
                <script src="css_js/bootstrap-table-master/src/bootstrap-table.js"></script>
                <script src="css_js/menu_vertical.js"></script>
                <script src="css_js/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
                <script src="css_js/slick.js"></script>
                <!--<script src="css_js/unslider.js"></script>-->

                <link rel="shortcut icon" href="img/icone_ncare.ico"/>

                <title>.:NCARe | GESTERNE:.</title>
            </head>
            <html>
        <body class="arriere_plan">
        <div class="container" style="padding-right: 0; padding-left: 0">

            <div style="position: fixed; top: 0; z-index: 100; width: 100%; box-shadow: 0 0 8px #000">
                <!-- Entête -->
                <div class="row hautdepage">
                    <div class="col-md-12 nopadding">
                        <div class="row nomargin_lr">
                            <div class="col-md-3 nopadding">
                                <div class="logo_entete">
                                    <a href="form_principale.php?page=accueil">
                                        <img alt="Logo" src="img/logowebsitencare.png" class="img-responsive">
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3 col-md-offset-6" style="color: #0e76bc; text-align: right; top: -5px">
                                <p style="padding-top: 40px; margin: 0"><?php echo utf8_encode(ucwords(strftime("%A %d %B %Y")));?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin Entête -->

                <!-- Menu horizontal -->
                <div class="row menuhorizontal" style="">
                    <div class="col-md-12 nopadding">
                        <div style="max-width: 100%; height: 44px; background-color: #0e76bc; position: relative;">
                            <ul class="nav main">
                                <li class="ic-form-style">
                                    <a>
                                        <span>Demandes</span>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="form_principale.php?page=demandes/form_absences">Absence</a>
                                        </li>
                                        <li>
                                            <a href="form_principale.php?page=demandes/form_demandes&action=ajout">Biens
                                                & Services</a>
                                        </li>
                                        <li>
                                            <a href="form_principale.php?page=demandes/form_conges&action=ajout">Congés</a>
                                        </li>
                                        <li>
                                            <a href="form_principale.php?page=demandes/form_permissions">Permission</a>
                                        </li>


                                    </ul>
                                </li>
                                <li class="ic-bon">
                                    <a>
                                        <span>Bons & Fiches</span>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="">Bon de Carburant</a>
                                        </li>
                                        <?php if ($droit === "administrateur" || $droit === "moyens generaux"): ?>
                                            <li><a href="form_principale.php?page=bons_commande/form_bon_commande">Bon
                                                    de
                                                    Commande</a></li>
                                            <li><a href="form_principale.php?page=bons_livraison/form_bons_livraison">Bon
                                                    de
                                                    Livraison</a></li>
                                        <?php endif; ?>
                                        <li>
                                            <a href="">Bon Provisoire</a>
                                        </li>
                                        <li>
                                            <a href="">Réception de Matériel</a>
                                        </li>
                                        <li>
                                            <a href="">Bon de Sortie</a>
                                        </li>
                                    </ul>
                                </li>
                                <?php if ($droit === "administrateur" || $droit === "moyens generaux"): ?>
                                    <li class="ic-facture">
                                        <a>
                                            <span>Factures</span>
                                        </a>
                                        <ul>
                                            <li><a href="form_principale.php?page=proformas/form_proformas">Facture
                                                    Proforma</a>
                                            </li>
                                            <li><a href="form_principale.php?page=factures/form_factures">Facture</a>
                                            </li>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                                <li class="ic-grid-tables">
                                    <a>
                                        <span>Listes</span>
                                    </a>
                                    <ul>
                                        <li><a href="form_principale.php?page=demandes/liste_demandes">Demandes</a></li>
                                        <?php if ($droit === "administrateur" || $droit === "moyens generaux"): ?>
                                            <li><a href="form_principale.php?page=proformas/liste_proformas">Factures
                                                    Proformas</a></li>
                                            <li><a href="form_principale.php?page=bons_commande/liste_bons_commande">Bons
                                                    de
                                                    Commande</a></li>
                                            <li><a href="form_principale.php?page=factures/liste_factures">Factures</a>
                                            </li>
                                            <li><a href="form_principale.php?page=bons_livraison/liste_bons_livraison">Bons
                                                    de Livraison</a></li>
                                        <?php endif; ?>

                                    </ul>
                                </li>
                                <?php if ($droit === "administrateur" || $droit === "moyens generaux"): ?>
                                    <li class="ic-imprimer">
                                        <a>
                                            <span>Imprimer</span>
                                        </a>
                                        <ul>
                                            <li><a href="employes/fiche_employes.php" target="_blank">Liste des
                                                    Employés</a>
                                            </li>
                                            <li><a href="fournisseurs/fiche_fournisseurs.php" target="_blank">Liste
                                                    des Fournisseurs</a>
                                            </li>
                                            <li><a href="articles/fiche_articles.php" target="_blank">Liste des
                                                    Articles</a>
                                            </li>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                                <li class="ic-statistique">
                                    <a>
                                        <span>Statistiques</span>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="form_principale.php?page=apropos">Etats Periodiques</a>
                                        </li>
                                        <li>
                                            <a href="form_principale.php?page=apropos">Utilisation/Employé</a>
                                        </li>
                                        <li>
                                            <a href="form_principale.php?page=apropos">Interaction/Fournisseur</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="ic-recherche">
                                    <a>
                                        <span>Recherche</span>
                                    </a>
                                    <ul>
                                        <?php if ($droit === "administrateur" || $droit === "moyens generaux"): ?>
                                            <li>
                                                <a href="form_principale.php?page=form_actions&source=employes&action=rechercher">Employés</a>
                                            </li>
                                            <li>
                                                <a href="form_principale.php?page=form_actions&source=fournisseurs&action=rechercher">Fournisseurs</a>
                                            </li>
                                        <?php endif; ?>
                                        <li>
                                            <a href="form_principale.php?page=form_actions&source=demandes&action=rechercher">Demandes</a>
                                        </li>
                                        <?php if ($droit === "administrateur" || $droit === "moyens generaux"): ?>
                                            <li>
                                                <a href="form_principale.php?page=form_actions&source=proformas&action=rechercher">Factures
                                                    Proformas</a>
                                            </li>
                                            <li>
                                                <a href="form_principale.php?page=form_actions&source=factures&action=rechercher">Factures</a>
                                            </li>
                                            <li>
                                                <a href="form_principale.php?page=form_actions&source=bons_commande&action=rechercher">Bons
                                                    de Commande</a></li>
                                            <li>
                                                <a href="form_principale.php?page=form_actions&source=bons_livraison&action=rechercher">Bons
                                                    de Livraison</a></li>
                                        <?php endif; ?>
                                    </ul>
                                </li>
                                <li class="ic-a_propos">
                                    <a>
                                        <span>Info.</span>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="form_principale.php?page=apropos">A Propos</a>
                                        </li>
                                        <li>
                                            <a href="http://192.168.1.190:8087/NCARE_WEBSITE/" target="_blank">Notre
                                                Site Web</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Fin Menu horizontal -->
            </div>

            <!-- Corps de page -->
            <div class="row corpsdepage" style="overflow: auto; position: relative; top: 110px; ">
                <!-- Menu vertical -->
                <?php if ($droit === "normal") { ?>
                    <div class="col-md-2"
                         style="position: fixed; top: 116px; padding-left: 0; padding-right: 5px">
                        <div class="panel panel-default">
                            <div class="panel-heading" style="text-align: center; font-size: 12px">
                                <?php echo 'Bonjour ' . $nom . ' !'; ?>
                            </div>
                            <div class="panel-body">
                                <ul class="widget">
                                    <li><a href="form_principale.php?page=accueil"><span
                                                class="icons8-home"> Accueil</span></a>
                                    </li>
                                    <li><a href="form_principale.php?page=utilisateurs/infos_utilisateur"><span
                                                class="icons8-gender-neutral-user"> Mon Compte</span></a></li>
                                    <li><a href="form_principale.php?page=utilisateurs/modif_mdp"><span
                                                class="icons8-key"> Changer de Mot de Passe</span></a>
                                    </li>
                                    <li><a href="processing.php"><span class="icons8-shutdown"> Déconnexion</span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php } elseif ($droit === "administrateur" || $droit === "moyens generaux") { ?>
                    <div class="col-md-2 nopadding"
                         style="border-right: 1px solid #0e76bc; position: fixed; top: 115px">
                        <div class="box sidemenu">
                            <ul class="section menu">
                                <li>
                                    <a>Administration</a>
                                    <ul>
                                        <li>
                                            <a href="form_principale.php?page=administration&source=employes">Employés</a>
                                        </li>
                                        <li>
                                            <a href="form_principale.php?page=administration&source=fournisseurs">Fournisseurs</a>
                                        </li>
                                        <li>
                                            <a href="form_principale.php?page=administration&source=utilisateurs">Utilisateurs</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a>Gestion des Articles</a>
                                    <ul>
                                        <li>
                                            <a href="form_principale.php?page=articles/form_articles">Ajouter un
                                                Article</a>
                                        </li>
                                        <li>
                                            <a href="form_principale.php?page=form_actions&source=articles&action=modifier">Modifier
                                                un Article</a>
                                        </li>
                                        <li>
                                            <a href="form_principale.php?page=form_actions&source=articles&action=supprimer">Supprimer
                                                un Article</a>
                                        </li>
                                        <li>
                                            <a href="form_principale.php?page=articles/liste_articles">Lister les
                                                Articles</a>
                                        </li>
                                        <li>
                                            <a href="form_principale.php?page=form_actions&source=articles&action=rechercher">Rechercher
                                                un Article</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a>Mouvements de Stock</a>
                                    <ul>
                                        <li>
                                            <a href="form_principale.php?page=articles/mouvements_stock&action=entree">
                                                Entrées d'Articles
                                            </a>
                                        </li>
                                        <li>
                                            <a href="form_principale.php?page=articles/mouvements_stock&action=sortie">
                                                Sorties d'Articles
                                            </a>
                                        </li>
                                        <li>
                                            <a href="form_principale.php?page=articles/recap">
                                                Récap. Entrées/Sorties
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
                <!-- Fin Menu vertical -->
                <div class="col-md-8 nopadding col-xs-offset-2">
                    <div style="margin-bottom: 10px"></div>

                    <?php include($page); ?>

                </div>
                <!-- Widget -->
            <?php if ($droit === "administrateur" || $droit === "moyens generaux"): ?>
                <div class="col-md-2 col-md-offset-10"
                     style="position: fixed; top: 116px; padding-left: 0; padding-right: 5px">
                    <div class="panel panel-default">
                        <div class="panel-heading" style="text-align: center; font-size: 12px">
                            <?php echo 'Bonjour ' . $nom . ' !'; ?>
                        </div>
                        <div class="panel-body">
                            <ul class="widget">
                                <li><a href="form_principale.php?page=accueil"><span class="icons8-home"> Accueil</span></a>
                                </li>
                                <li><a href="form_principale.php?page=utilisateurs/infos_utilisateur"><span
                                            class="icons8-gender-neutral-user"> Mon Compte</span></a></li>
                                <li><a href="form_principale.php?page=utilisateurs/modif_mdp"><span class="icons8-key"> Changer de Mot de Passe</span></a>
                                </li>
                                <li><a href="processing.php"><span class="icons8-shutdown"> Déconnexion</span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
                <!-- Fin widget -->
            </div>
        </div>
        </body>

            <?php
        }
    }
    ob_end_flush();
?>
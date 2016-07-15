<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 22/01/2016
     * Time: 11:21
     */
    if (isset($_GET['action']) && $_GET['action'] == "entree") : ?>
        <!--suppress ALL -->
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Mouvement de Stock
                        <a href='form_principale.php?page=accueil' type='button' class='close' data-dismiss='alert'
                           aria-label='Close' style='position: inherit'>
                            <span aria-hidden='true'>&times;</span>
                        </a>
                    </div>
                    <div class="panel-body">
                        <div class="jumbotron"
                             style="width: 70%; height: 120px;
                                    padding: 20px 30px 20px 30px;
                                    background-color: rgba(1, 139, 178, 0.1);
                                    margin-left: auto;
                                    margin-right: auto">
                            <h4 style="margin-top: 0">Saisie des Entrées</h4>

                            <div style="height: 70%; overflow: auto">
                                <p style="font-size: small">Pour saisir une entrée d'articles, veuillez entrer le nombre
                                    d'articles
                                    à saisir. Dans les differents champs "Désignation", sélectionnez un article à partir
                                    de
                                    la liste déroulante; vous avez entre parenthèses, le stock en temps réel de chaque
                                    article. En face se trouve le champ de saisie du nombre de chaque entrée d'articles
                                    et un
                                    autre
                                    (facultatif) pour mentionner un commentaire.</p>
                            </div>
                        </div>

                        <form method="post">
                            <table class="formulaire" border="0">
                                <tr>
                                    <td class="champlabel" style="vertical-align: bottom; padding-bottom: 5px">Nombre
                                        d'articles :
                                    </td>
                                    <td style="vertical-align: bottom">
                                        <label>
                                            <input type="number" min="1" class="form-control" id="nbr_articles"
                                                   name="nbr"
                                                   required/>
                                        </label>
                                    </td>
                                    <td>
                                        <table id="table"
                                               data-toggle="table"
                                               data-url="articles/infos_articles.php?opt=mvt"
                                               data-height="288"
                                               data-pagination="true"
                                               data-page-size="4"
                                               data-show-refresh="true"
                                               data-search="true">
                                            <thead>
                                            <tr>
                                                <th data-field="designation_art" data-sortable="true">Désignation</th>
                                                <th data-field="stock_art" data-sortable="true">Stock Actuel</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <div class="feedback"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            //Ce script permet d'afficher la liste des articles à saisir
            var articles = ["a", "b"],
                nbr_art = $('input[type=number]#nbr_articles');

            nbr_art.bind('keyup mouseup', function () {
                var n = $("#nbr_articles").val();
                $.ajax({
                    type: "POST",
                    url: "articles/entrees_stock.php",
                    data: {
                        nbr: n
                    },
                    success: function (resultat) {
                        if (n > 0) {
                            $('.feedback').html(resultat);
                        }
                    }
                });
            });

        </script>

        <?php
        if ((sizeof($_POST) > 0) && ((int)$_POST['nbr'] > 0)) {
            include 'class_articles.php';

            $entree = new entrees_articles();

            if ($entree->recuperation()) {
                if (!($entree->enregistrement())) {
                    echo "Une erreur s'est produite lors de la tentative d'enregistrement des informations";
                }
            }
            else {
                echo "Une erreur s'est produite lors de la tentative de récupération des informations entrées";
            }
        }
        ?>

    <?php else : ?>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Mouvement de Stock
                        <a href='form_principale.php?page=accueil' type='button' class='close' data-dismiss='alert'
                           aria-label='Close' style='position: inherit'>
                            <span aria-hidden='true'>&times;</span>
                        </a>
                    </div>
                    <div class="panel-body">
                        <div class="jumbotron"
                             style="width: 70%; height: 120px;
                                    padding: 20px 30px 20px 30px;
                                    background-color: rgba(1, 139, 178, 0.1);
                                    margin-left: auto;
                                    margin-right: auto">
                            <h4 style="margin-top: 0">Saisie des Sorties</h4>

                            <div style="height: 70%; overflow: auto">
                                <p style="font-size: small">Pour saisir une sortie d'articles, veuillez entrer le nombre
                                    d'articles à saisir. Dans les differents champs "Désignation", sélectionnez un
                                    article à partir de
                                    la liste déroulante; vous avez entre parenthèses, le stock en temps réel de chaque
                                    article. En face se trouve le champ de saisie du nombre de chaque entrée d'articles
                                    et un
                                    autre
                                    (facultatif) pour mentionner un commentaire.</p>

                                <p style="font-size: small">*Notez que les articles en rupture de stock ne sont pas
                                    disponibles dans la liste déroulante.</p>
                            </div>
                        </div>

                        <form method="post">
                            <table class="formulaire" border="0">
                                <tr>
                                    <td class="champlabel" style="vertical-align: bottom; padding-bottom: 5px">Demande :</td>
                                    <td style="vertical-align: bottom">
                                        <label>
                                            <select name="num_dmd" class="form-control demandes" required>
                                                <option disabled selected>N° Demande</option>
                                                <?php
                                                    $sql = "SELECT code_dbs FROM demandes WHERE statut = 'non satisfaite' ORDER BY date_dbs DESC ";
                                                    /*$sql = "SELECT DISTINCT d.code_dbs FROM demandes AS d
                                                            INNER JOIN details_demande AS dd ON d.code_dbs = dd.code_dbs
                                                            WHERE d.statut <> 'satisfaite' AND dd.nature_dd = 'bien'";*/
                                                    $res = mysqli_query($connexion, $sql) or exit(mysqli_error($connexion));
                                                    while ($list = mysqli_fetch_array($res)) {
                                                        ?>
                                                        <option value="<?php echo $list['code_dbs']; ?>"><?php echo $list['code_dbs']; ?></option>
                                                        <?php
                                                    }
                                                ?>
                                            </select>
                                        </label>
                                    </td>
                                    
                                </tr>
                            </table>
                            <div class="feedback"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            //Ce script permet d'afficher la liste des articles à saisir
            var articles = ["a", "b"],
                nbr_art = $('input[type=number]#nbr_articles');

            nbr_art.bind('keyup mouseup', function () {
                var n = $("#nbr_articles").val();
                $.ajax({
                    type: "POST",
                    url: "articles/sorties_stock.php",
                    data: {
                        nbr: n
                    },
                    success: function (resultat) {
                        if (n > 0) {
                            $('.feedback').html(resultat);
                        }
                    }
                });
            });

            /* Ce script permet d'afficher les différents articles figurant sur la demande sélectionnée */
            $("select.demandes").change(function () {
                var dmd = $(".demandes option:selected").val();
                $.ajax({
                    type: "POST",
                    url: "articles/ajax_demandes.php",
                    data: {
                        demande: dmd
                    },
                    success: function (resultat) {
                        $('.feedback').html(resultat);
                    }
                });
            });
        </script>

        <?php
        if ((sizeof($_POST) > 0) && ((int)$_POST['nbr'] > 0)) {
            include 'class_articles.php';

            $sortie = new sorties_articles();
            
            if ($sortie->recuperation($_SESSION['user_id'])) {
                if ($sortie->enregistrement()) {
                    $detail = new details_sortie();
    
                    $nbr = $_POST['nbr']; //depuis le fichier ajax_demandes.php
    
                    for ($i = 0; $i < $nbr; $i++) {
                        if ($detail->recuperation($sortie->code_dbs, $sortie->code, $i)) {
                            if ($detail->enregistrement()) {
                                /*header('Location: form_principale.php?page=articles/mouvements_stock&action=sortie');*/
                                continue;
                            }
                            else {
                                echo "
                                <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                                    <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                        <span aria-hidden='true'>&times;</span>
                                    </button>
                                    <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative d'enregistrement des détails de la sortie. Veuillez contacter l'administrateur.
                                </div>
                                ";
                                break;
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
                            break;
                        }
                    }
                } else {
                    echo "
                    <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                        <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative d'enregistrement de la sortie. Veuillez contacter l'administrateur.
                    </div>
                    ";
                }

            } else
                echo "
                <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                    <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative de récupération de la demande. Veuillez contacter l'administrateur.
                </div>
                ";
        }
        ?>
    <?php endif; ?>

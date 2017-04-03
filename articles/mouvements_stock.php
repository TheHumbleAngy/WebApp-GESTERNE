<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 22/01/2016
     * Time: 11:21
     */
    $connexion = db_connect();
    
    if (isset($_GET['action']) && $_GET['action'] == "entree") : ?>
        <!--suppress ALL -->
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Mouvement de Stock - Entrée
                        <a href='form_principale.php?page=accueil' type='button' class='close' data-dismiss='alert'
                           aria-label='Close' style='position: inherit'>
                            <span aria-hidden='true'>&times;</span>
                        </a>
                    </div>
                    <div class="panel-body">
                        <div class="jumbotron"
                             style="width: 70%;
                                    padding: 20px 30px 20px 30px;
                                    background-color: rgba(1, 139, 178, 0.1);
                                    margin-left: auto;
                                    margin-right: auto">
                            <table class="formulaire" border="0" width="100%">
                                <tr>
                                    <td>
                                        <h4 style="margin-top: 0">Saisie des Entrées</h4>
                                        <button type="button" class="btn btn-sm btn-default" onclick="saisieEntree()">
                                            Procéder
                                            <img src="img/icons_1775b9/right_filled.png" width="20">
                                        </button>
                                        <!--Fenetres modal-->
                                        <div class="modal fade"
                                             id="modalEntrees" tabindex="-1"
                                             role="dialog" aria-hidden="true"
                                             style="width: 100%">
                                            <div class="modal-dialog" style="width: 750px">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        <h4 class="modal-title">
                                                            Entrée d'Articles
                                                        </h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form id="form_entree">
                                                            <table class="formulaire" width="35%" border="0">
                                                                <tr>
                                                                    <td class="champlabel">
                                                                        Nombre d'Articles :
                                                                    </td>
                                                                    <td style="vertical-align: bottom; width: 5%">
                                                                        <label>
                                                                            <input type="number" min="1"
                                                                                   class="form-control"
                                                                                   id="nbr_articles_entree"
                                                                                   name="nbr"
                                                                                   required/>
                                                                        </label>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <div class="feedback_entree"></div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="modal-success" role="dialog">
                                            <div class="modal-dialog modal-sm" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close"><span
                                                                aria-hidden="true">&times;</span>
                                                        </button>
                                                        <h4 class="modal-title" style="color: #0e76bc">
                                                            <span class="glyphicon glyphicon-info-sign"></span>
                                                            Message
                                                        </h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h5>Succès! Les articles ont bien été ajoutés.</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="modal-error" role="dialog">
                                            <div class="modal-dialog modal-sm" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close"><span
                                                                aria-hidden="true">&times;</span>
                                                        </button>
                                                        <h4 class="modal-title" style="color: red">
                                                            <span class="glyphicon glyphicon-exclamation-sign"></span>
                                                            Message
                                                        </h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h5>
                                                            <strong>Erreur!</strong><br/> 
                                                            Une erreur s'est produite. Veuillez contacter l'administrateur.
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="text-align: right">
                                        <a tabindex="0" class="btn btn-xs btn-info"
                                           role="button" data-toggle="popover" data-placement="bottom"
                                           data-trigger="focus" title="Fonctionnement"
                                           data-content="Pour saisir une entrée d'articles, veuillez entrer le nombre
                                           d'articles à saisir. Dans les differents champs 'Désignation', sélectionnez
                                           un article à partir de la liste déroulante; vous avez entre parenthèses,
                                           le stock en temps réel de chaque article. En face se trouve le champ de
                                           saisie du nombre de chaque entrée d'Articles et un autre (facultatif)
                                           pour mentionner un commentaire.">
                                            <img src="img/icons_1775b9/info_2_white.png" width="20">
                                        </a>
                                    </td>
                                </tr>
                            </table>

                        </div>

                        <form method="post">
                            <table class="formulaire" border="0" width="100%" id="tabletest">
                                <tr>
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

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            //Ce script permet d'afficher la liste des articles à saisir
            var articles = ["a", "b"],
                nbr_art = $('input[type=number]#nbr_articles_entree');

            nbr_art.bind('keyup mouseup', function () {
                var n = $("#nbr_articles_entree").val();
                $.ajax({
                    type: "POST",
                    url: "articles/entrees_sorties_stock.php?operation=entree",
                    data: {
                        nbr: n
                    },
                    success: function (resultat) {
                        if (n > 0) {
                            $('.feedback_entree').html(resultat);
                        }
                    }
                });
            });

            $(document).ready(function(){
                $('[data-toggle="popover"]').popover();
            });

            //Script qui permet de redimensioner la fenetre modal
            $('#modalEntrees').on('show.bs.modal', function () {
                $('.modal .modal-body').css('overflow-y', 'auto');
                $('.modal .modal-body').css('max-height', $(window).height() * 0.7);
            });

            function saisieEntree() {
                $('#modalEntrees').modal('show');
                $('#feedback_entree').empty();
                $('#form_entree')[0].reset();
            }

            function confirmationEntree() {
                $('#modalEntrees').modal('hide');
                $('#modal-success').modal('show');
            }
            
            function erreurEntree() {
                $('#modalEntrees').modal('hide');
                $('#modal-error').modal('show');
            }

            function champsRequis() {
                alert("Veuillez renseigner le/les champs \"Désignation\"");
            }

            function validation() {
                var i = 0;
                $("select[id^=libelle]").each(function () {
                    if (this.value == "--- Sélectionner un article ---")
                        i++;
                });
                return i;
            }

            function ajout_entrees() {
                if (validation() != 0) {
                    champsRequis();
                } else {
                    var nbr = $('#nbr_articles_entree').val();
                    var libelle = new Array();
                    var qte = new Array();
                    var obsv = new Array();
                    
                    for (var i = 0; i < nbr; i = i + 1) {
                        libelle[i] = $('[id*="libelle"]')[i].value;
                        qte[i] = $('[id*="qte"]')[i].value;
                        obsv[i] = $('[id*="obsv"]')[i].value;
                    }

                    var json_libelle = JSON.stringify(libelle),
                        json_qte = JSON.stringify(qte),
                        json_obsv = JSON.stringify(obsv);

                    var infos = "i=" + nbr + "&libelle=" + json_libelle + "&qte=" + json_qte + "&obsv=" + json_obsv;
                    var operation = "entrer";

                    $.ajax({
                        type: 'POST',
                        url: 'articles/updatedata.php?operation=' + operation,
                        data: infos,
                        success: function (data) {
                            console.log(data);
                            if (data == "Success!") {
                                confirmationEntree();
                                $('#tabletest').bootstrapTable('refresh')
                            } else {
                                erreurEntree();
                            }
                        }
                    });
                }
            }
        </script>

    <?php else : ?>

        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Mouvement de Stock - Sortie
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
                            <table class="formulaire" border="0" width="100%">
                                <tr>
                                    <td>
                                        <h4 style="margin-top: 0">Saisie des Sorties</h4>
                                        <button type="button" class="btn btn-sm btn-default" onclick="saisieSource()">
                                            Procéder
                                            <img src="img/icons_1775b9/right_filled.png" width="20">
                                        </button>

                                        <!--Modal source - A partir d'une demande ? -->
                                        <div class="modal fade"
                                             id="modalSource" tabindex="-1"
                                             role="dialog" aria-hidden="true"
                                             style="width: 100%">
                                            <div class="modal-dialog" style="width: 400px;">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        <h4 class="modal-title">
                                                            Sortie d'Articles
                                                        </h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form id="form_source">
                                                            <table class="formulaire" width="100%" border="0">
                                                                <tr>
                                                                    <td class="champlabel">
                                                                        A partir d'une demande ?
                                                                    </td>
                                                                    <td style="padding-left: 5px">
                                                                        <label>
                                                                            <select name="question" class="form-control" id="question">
                                                                                <option disabled selected></option>
                                                                                <option value="oui">OUI</option>
                                                                                <option value="non">NON</option>
                                                                            </select>
                                                                        </label>
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-sm btn-default" onclick="choix()">
                                                                            Suivant
                                                                            <img src="img/icons_1775b9/right_filled.png" width="20">
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!--Modal saisie à partir des demandes-->
                                        <div class="modal fade" id="modalSortie_demandes" role="dialog" style="width: 100%">
                                            <div class="modal-dialog" style="width: 1000px;">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        <h4 class="modal-title">
                                                            Sortie d'Articles
                                                        </h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form id="form_sortie_demande">
                                                            <table class="formulaire" width="90%" border="0">
                                                                <tr>
                                                                    <td class="champlabel" style="width: 15%">
                                                                        Demandes :
                                                                    </td>
                                                                    <td style="width: 20%">
                                                                        <label>
                                                                            <select name="num_dmd" id="num_dbs" class="form-control demandes" multiple size="5">
                                                                                <?php
                                                                                    $sql = "SELECT num_dbs FROM demandes WHERE statut = 'non satisfaite' ORDER BY num_dbs DESC ";
                                                                                    $res = mysqli_query($connexion, $sql) or exit(mysqli_error($connexion));
                                                                                    while ($list = mysqli_fetch_array($res)) {
                                                                                        ?>
                                                                                        <option value="<?php echo $list['num_dbs']; ?>"><?php echo $list['num_dbs']; ?></option>
                                                                                        <?php
                                                                                    }
                                                                                ?>
                                                                            </select>
                                                                        </label>
                                                                        <button class="btn btn-default" type="button" name="valider" style="margin-left: 5px" onclick="ajax_demandes()">
                                                                            <span class="ui-icon ui-icon-circle-triangle-e"></span>
                                                                        </button>
                                                                    </td>
                                                                    <td>
                                                                        <div class="jumbotron" style="padding: 20px 30px 20px 30px;
                                                                                                        background-color: rgba(1, 139, 178, 0.1);
                                                                                                        margin-left: auto;
                                                                                                        margin-right: auto">
                                                                            <p style="font-size: small">
                                                                                Pour choisir plusieurs demandes, veuillez maintenir la
                                                                                touche "Ctrl" et sélectionner les différents numéros de demandes.
                                                                            </p>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <div class="feedback_sortie_demande"></div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="modal-success-demandes" role="dialog">
                                            <div class="modal-dialog modal-sm" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close"><span
                                                                aria-hidden="true">&times;</span>
                                                        </button>
                                                        <h4 class="modal-title" style="color: #0e76bc">
                                                            <span class="glyphicon glyphicon-info-sign"></span>
                                                            Message
                                                        </h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h5>Succès! Les articles ont bien été ajoutés.</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="modal-error-demandes" role="dialog">
                                            <div class="modal-dialog modal-sm" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close"><span
                                                                aria-hidden="true">&times;</span>
                                                        </button>
                                                        <h4 class="modal-title" style="color: red">
                                                            <span class="glyphicon glyphicon-exclamation-sign"></span>
                                                            Message
                                                        </h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h5>
                                                            <strong>Erreur!</strong><br/>
                                                            Une erreur s'est produite. Veuillez contacter l'administrateur.
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!--Modal saisie nombre d'Articles-->
                                        <div class="modal fade" id="modalSortie_standard"
                                             role="dialog" aria-hidden="true">
                                            <div class="modal-dialog" style="width: 750px">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        <h4 class="modal-title">
                                                            Sortie d'Articles
                                                        </h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form id="form_sortie">
                                                            <table class="formulaire" width="35%" border="0">
                                                                <tr>
                                                                    <td class="champlabel">
                                                                        Nombre d'Articles :
                                                                    </td>
                                                                    <td style="vertical-align: bottom; width: 5%">
                                                                        <label>
                                                                            <input type="number" min="1"
                                                                                   class="form-control"
                                                                                   id="nbr_articles_sortie"
                                                                                   name="nbr"
                                                                                   required/>
                                                                        </label>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <div class="feedback_sortie_standard"></div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="modal-success-sortie" role="dialog">
                                            <div class="modal-dialog modal-sm" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close"><span
                                                                aria-hidden="true">&times;</span>
                                                        </button>
                                                        <h4 class="modal-title" style="color: #0e76bc">
                                                            <span class="glyphicon glyphicon-info-sign"></span>
                                                            Message
                                                        </h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h5>Succès! La sortie d'Articles a bien été enregistrées.</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="modal-error-sortie" role="dialog">
                                            <div class="modal-dialog modal-sm" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close"><span
                                                                aria-hidden="true">&times;</span>
                                                        </button>
                                                        <h4 class="modal-title" style="color: red">
                                                            <span class="glyphicon glyphicon-exclamation-sign"></span>
                                                            Message
                                                        </h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h5>
                                                            <strong>Erreur!</strong><br/>
                                                            Une erreur s'est produite. Veuillez contacter l'administrateur.
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                    <td style="text-align: right">
                                        <a tabindex="0" class="btn btn-xs btn-info"
                                           role="button" data-toggle="popover" data-placement="bottom"
                                           data-trigger="focus" title="Fonctionnement"
                                           data-content="Pour saisir une sortie d'articles, veuillez
                                           spécifier l'origine de la sortie d'articles et suivez les instructions.">
                                            <img src="img/icons_1775b9/info_2_white.png" width="20">
                                        </a>
                                    </td>
                                </tr>
                            </table>

                        </div>

                        <form method="post">
                            <table class="formulaire" border="0" width="100%">
                                <tr>
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

                        </form>

                    </div>
                </div>
            </div>
        </div>

        <script>

            $(document).ready(function () {
                $('[data-toggle="popover"]').popover();
            })
            //Script qui permet de redimensioner la fenetre modal
            $('#modalSortie_demandes').on('show.bs.modal', function () {
                $('.modal .modal-body').css('overflow-y', 'auto');
                $('.modal .modal-body').css('max-height', $(window).height() * 0.7);
            });

            //Script qui permet de redimensioner la fenetre modal
            $('#modalSortie_standard').on('show.bs.modal', function () {
                $('.modal .modal-body').css('overflow-y', 'auto');
                $('.modal .modal-body').css('max-height', $(window).height() * 0.7);
            });

            //Affiche la modal qui pose la question à savoir "à partir d'une demande ou pas"
            function saisieSource() {
                $('#modalSource').modal('show');
                $('.feedback').empty();
                $('#form_source')[0].reset();
            }

            //Affiche soit la modal à partir de demandes ou standard
            function choix() {
                var question = $('#question').val();

                if (question == "non") {
                    /*On appelle ici la forme de saisie du nombre d'Articles*/
                    $('#modalSource').modal('hide');
                    $('#modalSortie_standard').modal('show');
                    $('#form_sortie')[0].reset();
                    $('.feedback_sortie_standard').empty();

                } else if (question == "oui") {
                    /*On appelle ici la forme de saisie à partir des demandes sélectionnées*/
                    $('#modalSource').modal('hide');
                    $('#modalSortie_demandes').modal('show');
                    $('#form_sortie_demande')[0].reset();
                    $('.feedback_sortie_demande').empty();

                }
            }

            //Ce script permet d'afficher les différents articles figurant sur la demande sélectionnée
            function ajax_demandes() {
                var dmd = $('.demandes').val(); //retourne un array du genre $('.demandes').val()[i]
                if (dmd != null) {
//                    console.log(dmd);
                    $.ajax({
                        type: "POST",
                        url: "articles/ajax_demandes.php",
                        data: {
                            demande: dmd
                        },
                        success: function (resultat) {
                            $('.feedback_sortie_demande').show();
                            $('.feedback_sortie_demande').html(resultat);
                        }
                    });
                }
            }

            //Ce script permet d'afficher les différents articles à saisir
            var articles = ["a", "b"],
                nbr_art = $('input[type=number]#nbr_articles_sortie');
            
            nbr_art.bind('keyup mouseup', function () {
                var n = $("#nbr_articles_sortie").val();
                $.ajax({
                    type: "POST",
                    url: "articles/entrees_sorties_stock.php?operation=sortie",
                    data: {
                        nbr: n
                    },
                    success: function (resultat) {
                        if (n > 0) {
                            $('.feedback_sortie_standard').show();
                            $('.feedback_sortie_standard').html(resultat);
                        }
                    }
                });
            });

            function confirmationSortie() {
                $('#modalSortie_standard').modal('hide');
                $('#modal-success-sortie').modal('show');
            }

            function erreurSortie() {
                $('#modalSortie_standard').modal('hide');
                $('#modal-error-sortie').modal('show');
            }

            function confirmationSortieDemande() {
                $('#modalSortie_demandes').modal('hide');
                $('#modal-success-demandes').modal('show');
            }

            function erreurSortieDemande() {
                $('#modalSortie_standard').modal('hide');
                $('#modal-error-demandes').modal('show');
            }

            function validation() {
                var i = 0;
                $("select[id^=libelle_sortie]").each(function () {
                    if (this.value == "--- Sélectionner un article ---")
                        i++;
                });
                return i;
            }

            function validation_demandes() {
                var i = 0;
                $("input[name^=qte_serv]").each(function () {
                    if (this.value == 0)
                        i++;
                });
                return i;
            }

            function champsRequis() {
                alert("Veuillez renseigner le/les champs \"Désignation\"");
            }

            /*function qteRequis() {
                alert("Veuillez renseigner la quantité des articles à sortir");
            }*/

            function ajout_sorties() {
                if (validation() != 0) {
                    champsRequis();
                } else {
                    var nbr = $('#nbr_articles_sortie').val(),
                        libelle = new Array(),
                        qte = new Array(),
                        obsv = new Array();

                    for (var i = 0; i < nbr; i = i + 1) {
                        libelle[i] = $('[id*="libelle_sortie"]')[i].value;
                        qte[i] = $('[id*="qte_sortie"]')[i].value;
                        obsv[i] = $('[id*="obsv_sortie"]')[i].value;
                    }

                    var json_libelle = JSON.stringify(libelle),
                        json_qte = JSON.stringify(qte),
                        json_obsv = JSON.stringify(obsv);

                    var infos = "i=" + nbr + "&libelle=" + json_libelle + "&qte=" + json_qte + "&obsv=" + json_obsv;
                    var operation = "sortir";

                    $.ajax({
                        type: 'POST',
                        url: 'articles/updatedata.php?operation=' + operation,
                        data: infos,
                        success: function (data) {
//                            console.log(data);
                            if (data == "Success!") {
                                confirmationSortie();
                            } else {
                                erreurSortie();
                            }
                        }
                    });
                }
            }
            
            function ajout_sortie_demande() {
                var nbr = $('#nbr_dd').val(),
                    nbr_dmd = $('#nbr_dmd').val(),
                    libelle = new Array(),
                    qte = new Array(),
                    obsv = new Array(),
                    num_dmd = new Array(),
                    num_dd = new Array();
                
                for (var i = 0; i < nbr; i = i + 1) {
                    try {
                        libelle[i] = $('[id*="libelle_dd"]')[i].value;
                        qte[i] = $('[id*="qte_aserv"]')[i].value;
                        obsv[i] = $('[id*="obsv"]')[i].value;
                        num_dmd[i] = $('[id*="num_demande"]')[i].value;
                        num_dd[i] = $('[id*="num_details_demande"]')[i].value;
                    } catch (e) {
                        alert(e.message + ". Veuillez consulter la console pour plus de détails");
                        console.log(e);
                    }
                }

                var json_libelle = JSON.stringify(libelle),
                    json_qte = JSON.stringify(qte),
                    json_obsv = JSON.stringify(obsv),
                    json_num_dmd = JSON.stringify(num_dmd),
                    json_num_dd = JSON.stringify(num_dd);

                var infos = "i=" + nbr + "&n=" + nbr_dmd +"&libelle=" + json_libelle + "&qte=" + json_qte + "&obsv=" + json_obsv + "&num_dmd=" + json_num_dmd + "&num_dd=" + json_num_dd;
                var operation = "sortir_demande";

                $.ajax({
                    type: 'POST',
                    url: 'articles/updatedata.php?operation=' + operation,
                    data: infos,
                    success: function (data) {
//                        console.log(data);
                        if (data == "Success!") {
                         confirmationSortieDemande();
                         } else {
                         erreurSortieDemande();
                         }
                    }
                });
                //}
            }
        </script>
    <?php endif; ?>
<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 27-Aug-15
     * Time: 6:03 PM
     */
    //session_start();
    if (isset($_GET['action']) && $_GET['action'] == "consultation") : ?>

        <?php
            $code = $_GET['id'];
    
            $sql = "SELECT * FROM demandes WHERE num_dbs = '" . $code . "'";
            if ($valeur = $connexion->query($sql)) {
                $ligne = $valeur->fetch_all(MYSQLI_ASSOC);
                foreach ($ligne as $data) {
                    ?>
                    <!--suppress ALL -->
                    <div class="col-md-10 col-md-offset-1">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Formulaire Demande
                                <a href='form_principale.php?page=demandes/biens_services/liste_demandes' type='button'
                                   class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                    <span aria-hidden='true'>&times;</span>
                                </a>
                            </div>
                            <div class="panel-body">
                                <form id="myform" method="post">
                                    <table class="formulaire" style="width: 100%" border="0">
                                        <tr>
                                            <td class="champlabel" title="Le numéro de la demande en cours de saisie">Numéro
                                                :
                                            </td>
                                            <td>
                                                <label>
                                                    <h4>
                                                        <span class="label label-primary" name="num_dbs" id="num_dbs">
                                                            <?php echo stripslashes($data['num_dbs']); ?>
                                                        </span>
                                                    </h4>
                                                </label>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td rowspan="3" style="padding-right: 0">
                                                <a href="demandes/biens_services/fiche_demandes.php?id=<?php echo stripslashes($data['num_dbs']); ?>"
                                                   target="_blank"
                                                   class="btn btn-default" role="button"
                                                   style="color: #1775b9; font-weight: bold"
                                                   title="Imprimer une fiche pour cette demande">
                                                    Imprimer <img src="img/icons_1775b9/print_2.png" width="20">
                                                </a>
                                                <!--<button type="button" title="Imprimer la demande"
                                                        class="btn btn-sm btn-default"
                                                        style="color: #1775b9; font-weight: bold"
                                                        onclick="$('#modalImprimer').modal('show');">
                                                    Imprimer <img src="img/icons_1775b9/print_2.png" width="20">
                                                </button>-->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="champlabel">Employé :</td>
                                            <td>
                                                <label>
                                                    <?php
                                                        $sql = "SELECT e.nom_emp, e.prenoms_emp
                                                                FROM employes AS e
                                                                INNER JOIN demandes AS d
                                                                ON e.code_emp = d.code_emp
                                                                WHERE d.num_dbs = '" . stripslashes($data['num_dbs']) . "'";
                                                        if ($valeur = $connexion->query($sql)) {
                                                            $ligne = $valeur->fetch_all(MYSQLI_ASSOC);
                                                            $nom_prenoms_emp = "";
                                                            foreach ($ligne as $list) {
                                                                $nom_prenoms_emp = stripslashes($list['prenoms_emp']) . ' ' . stripslashes($list['nom_emp']);
                                                            }
                                                        }
                                                    ?>
                                                    <h4>
                                                        <span class="label label-primary" name="code_emp">
                                                            <?php echo $nom_prenoms_emp; ?>
                                                        </span>
                                                    </h4>
                                                </label>
                                            </td>
                                            <td class="champlabel" rowspan="2">Objet :</td>
                                            <td rowspan="2">
                                                <label>
                                                    <textarea id="objets_dbs" name="objets_dbs" rows="3"
                                                              class="form-control" readonly
                                                              style="resize: none"><?php echo stripslashes($data['objets_dbs']); ?></textarea>
                                                </label>
                                            </td>
                                        </tr>
                                    </table>
                                    <br>
    
                                    <div class="feedback">
                                        <?php
                                            $sql = "SELECT * FROM details_demande WHERE num_dbs = '" . $code . "'";
                                            if ($valeur = $connexion->query($sql)) {
                                                $ligne = $valeur->fetch_all(MYSQLI_ASSOC);
                                                ?>
                                                <div class="col-md-12">
                                                    <div class="panel panel-default">
                                                        <table border="0" class="table table-hover table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th class="entete" style="text-align: center; width: 45%">
                                                                    Libellé
                                                                </th>
                                                                <th class="entete" style="text-align: center">Nature</th>
                                                                <th class="entete" style="text-align: center">Quantité</th>
                                                                <th class="entete" style="text-align: center; width: 30%">
                                                                    Observation
                                                                </th>
                                                            </tr>
                                                            </thead>
                                                            <?php
                                                                foreach ($ligne as $list) {
                                                                    ?>
                                                                    <tr>
                                                                        <td style="text-align: center; vertical-align: middle"><?php echo $list['libelle_dd']; ?></td>
                                                                        <td style="text-align: center; vertical-align: middle"><?php echo ucfirst($list['nature_dd']); ?></td>
                                                                        <td style="text-align: center; vertical-align: middle"><?php echo $list['qte_dd']; ?></td>
                                                                        <td style="vertical-align: middle"><?php echo $list['observations_dd']; ?></td>
                                                                    </tr>
                                                                    <?php
                                                                } ?>
                                                        </table>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        ?>
                                    </div>
                                    <!--Fenetre modal-->
                                    <div class="modal fade"
                                         id="modalImprimer" tabindex="-1"
                                         role="dialog" aria-hidden="true">
                                        <div class="modal-dialog" style="width: 950px">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <h4 class="modal-title">
                                                        Test d'Impression
                                                    </h4>
                                                </div>
                                                <div class="modal-body">
                                                    <?php
                                                        //$_GET['id'] = stripslashes($data['num_dbs']);
                                                        //include 'fiche_demandes.php';
                                                        //echo $_GET['id'];
                                                        //echo "Text before include <br>";
                                                        ///include 'fiche_demandes.php';
                                                        //echo "Text after include";
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
    
                    <?php
                }
            }
        ?>
    <?php else : ?>
        <head>
            <meta charset="UTF-8">
        </head>
        <body onload="numero_demande()">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Formulaire Demande
                    <a href='form_principale.php?page=accueil' type='button'
                       class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                        <span aria-hidden='true'>&times;</span>
                    </a>
                </div>
                <div class="panel-body">
                    <form id="myform">
                        <table class="formulaire" style="width: 100%" border="0">
                            <tr>
                                <td class="champlabel" title="Le numéro de la demande en cours de saisie">Numéro :</td>
                                <td>
                                    <label>
                                        <h4>
                                            <span class="label label-primary" name="num_dbs" id="num_dbs"></span>
                                        </h4>
                                    </label>
                                </td>
                                <td></td>
                                <td></td>
                                <td rowspan="3" style="padding-right: 0">
                                    <img src="img/icons_1775b9/agreement_1.png">
                                </td>
                            </tr>
                            <tr>
                                <td class="champlabel">Employé :</td>
                                <td>
                                    <label>
                                        <?php
                                            $sql = "SELECT code_emp, nom_emp, prenoms_emp FROM employes WHERE employes.email_emp = '" . $_SESSION['email'] . "'";
                                            if ($valeur = $connexion->query($sql)) {
                                                $ligne = $valeur->fetch_all(MYSQLI_ASSOC);
                                                $nom_prenoms_emp = "";
                                                foreach ($ligne as $data) {
                                                    $code_emp = stripslashes($data['code_emp']);
                                                    $nom_prenoms_emp = stripslashes($data['prenoms_emp']) . ' ' . stripslashes($data['nom_emp']);
                                                }
                                            }
                                        ?>
                                        <h4>
                                            <span class="label label-primary" name="code_emp">
                                                <?php echo $nom_prenoms_emp; ?>
                                            </span>
                                        </h4>
                                    </label>
                                </td>
                                <td class="champlabel" rowspan="2">Objet :</td>
                                <td rowspan="2">
                                    <label>
                                    <textarea id="objets_dbs" name="objets_dbs" rows="3" class="form-control"
                                              style="resize: none"></textarea>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="champlabel">Nombre d'articles :</td>
                                <td>
                                    <label>
                                        <input type="number" min="1" class="form-control" id="nbr_articles"
                                               name="nbr"
                                               required/>
                                    </label>
                                </td>
                            </tr>
                        </table>
                        <br>

                        <div class="feedback"></div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            var articles = ["a", "b"],
                x = $('input[name*="libelle"]'),
                nbr_articles = $('input[type=number]#nbr_articles');

            function numero_demande() {
                $.ajax({
                    type: "POST",
                    url: "demandes/biens_services/ajax_num_demande.php",
                    data: {
                        option: "bien_service"
                    },
                    success: function (resultat) {
                        $('#num_dbs').text(resultat);
                    }
                });
            }

            nbr_articles.bind('keyup mouseup', function () {
                var n = $("#nbr_articles").val();
                $.ajax({
                    type: "POST",
                    url: "demandes/biens_services/ajax_saisie_demandes.php",
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

            nbr_articles.bind('blur', function () {
                $.ajax({
                    url: "articles/libelles_articles.php",
                    dataType: "json",
                    type: "GET",
                    success: function (data) {
                        for (var i = 0; i < data.length; i += 1) {
                            articles[i] = data[i].designation_art;
                        }
                        $('input[name*="libelle"]').autocomplete({
                            source: articles
                        });
                    }
                })
            });
            
            function validation() {
                var i = 0;
                $(':input[required]').each(function () {
                    if (this.value == '')
                        i++;
                });
                return i;
            }
            
            function ajout_demande() {
                if (validation() != 0)
                    alert("Veuillez remplir les champs requis s'il vous plait");
                else {
                    //Different variables declarations and assignments
                    //variables pour la demande
                    var num_dmd = $('#num_dbs').text(),
                        objet_dmd = $('#objets_dbs').val(),
                        nbr = $('#nbr_articles').val();

                    //variables pour les details sur la demande
                    var libelle_dd = new Array(),
                        nature_dd = new Array(),
                        qte_dd = new Array(),
                        obsv_dd = new Array();

                    for (var i = 0; i < nbr; i = i + 1) {
                        try {
                            libelle_dd[i] = $('[id*="libelle_dd"]')[i].value;
                            nature_dd[i] = $('[id*="nature_dd"]')[i].value;
                            qte_dd[i] = $('[id*="qte_dd"]')[i].value;
                            obsv_dd[i] = $('[id*="obsv_dd"]')[i].value;
                        } catch (e) {
                            alert(e.message + ". Veuillez consulter la console pour plus de détails");
                            console.log(e);
                        }
                    }

                    //conversion des var tableaux au format Json afin de les utiliser
                    // dans l'autre fichier grace à AJAX
                    var json_libelle = JSON.stringify(libelle_dd),
                        json_nature = JSON.stringify(nature_dd),
                        json_qte = JSON.stringify(qte_dd),
                        json_obsv = JSON.stringify(obsv_dd);

                    var infos = "i=" + nbr + "&num_dmd=" + num_dmd + "&objet_dmd=" + objet_dmd + "&libelle_dd=" + json_libelle + "&nature_dd=" + json_nature + "&qte_dd=" + json_qte + "&obsv_dd=" + json_obsv,
                        operation = "ajout_demande_bs";

                    $.ajax({
                        type: 'POST',
                        url: 'demandes/biens_services/updatedata.php?operation=' + operation,
                        data: infos,
                        success: function (data) {
                            numero_demande();
                            $('#myform').trigger('reset');
                            $('.feedback').html(data);
                            setTimeout(function () {
                                $('.alert-success').slideToggle('slow');
                            }, 2500);
                        }
                    });
                }
            }
        </script>
        </body>
    <?php endif; ?>
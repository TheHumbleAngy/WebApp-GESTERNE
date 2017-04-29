<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 17-Jul-15
     * Time: 1:10 PM
     */
    //error_reporting(0);
    if (isset($_GET['action']) && $_GET['action'] == "consultation") : ?>

        <?php
        $code = $_GET['id'];

        $sql = "SELECT * FROM bons_commande WHERE num_bc = '" . $code . "'";
        if ($res = $connexion->query($sql)) {
            $lines = $res->fetch_all(MYSQLI_ASSOC);
            foreach ($lines as $line) { ?>
                <!--suppress ALL -->
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Bon de Commande
                            <a href='form_principale.php?page=form_actions&source=bons_commande&action=rechercher'
                               type='button'
                               class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                <span aria-hidden='true'>&times;</span>
                            </a>
                        </div>
                        <div class="panel-body" style="overflow: auto">
                            <div class="col-md-10">
                                <table class="formulaire" border="0" style="margin-right: auto">
                                    <tr>
                                        <td class="champlabel">Numéro :</td>
                                        <td>
                                            <label>
                                                <h4>
                                                    <span class="label label-primary" name="num_bc" id="num_bc">
                                                        <?php echo stripslashes($line['num_bc']); ?>
                                                    </span>
                                                </h4>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left: 30px">
                                            Fournisseur :
                                        </td>
                                        <td>
                                            <?php
                                                $fournisseur = stripslashes($line['code_four']);
                                                $sql = "SELECT nom_four FROM fournisseurs WHERE code_four = '" . $fournisseur . "'";
                                                $res = $connexion->query($sql);
                                                $rows = $res->fetch_all(MYSQLI_ASSOC);
                                                foreach ($rows as $row) {
                                                    $fournisseur = $row['nom_four'];
                                                }
                                            ?>
                                            <label>
                                                <input type="text" size="20" name="fournisseur"
                                                       class="form-control"
                                                       id="code_four" readonly
                                                       value="<?php echo $fournisseur; ?>"/>
                                            </label>
                                        </td>
                                    </tr>

                                </table>
                            </div>
                            <div class="col-md-2" style="margin-bottom: 15px">
                                <img src="img/icons_1775b9/bon.png">
                            </div>

                            <br/>

                            <div id="response">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <table border="0" class="table table-hover table-bordered">
                                            <thead>
                                            <th class="entete" style="text-align: center">Libelle</th>
                                            <th class="entete" style="text-align: center">Quantité</th>
                                            <th class="entete" style="text-align: center">Prix Unitaire</th>
                                            <th class="entete" style="text-align: center">Remise</th>
                                            <th class="entete" style="text-align: center">Prix T.T.C</th>
                                            </thead>
                                            <?php
                                                $sql3 = "SELECT * FROM details_bon_commande WHERE num_bc = '" . stripslashes($line['num_bc']) . "'";
                                                if ($result = $connexion->query($sql3)) { //print_r($sql3);
                                                    $rows = $result->fetch_all(MYSQLI_ASSOC);
                                                    $total = 0;
                                                    foreach ($rows as $row) {
                                                        $qte = stripslashes($row['qte_dbc']);
                                                        $pu = stripslashes($row['pu_dbc']);
                                                        $rem = stripslashes($row['remise_dbc']);

                                                        if ($rem > 0) {
                                                            $rem = $rem / 100;
                                                            $ttc = $qte * $pu * (1 - $rem);
                                                        } else
                                                            $ttc = $qte * $pu;

                                                        $total = (int)$total + (int)$ttc;
                                                        ?>

                                                        <tr>
                                                            <td style="text-align: left"><?php echo stripslashes($row['libelle_dbc']); ?></td>
                                                            <td style="text-align: center"><?php echo stripslashes($row['qte_dbc']); ?></td>
                                                            <td style="text-align: right"><?php echo number_format(stripslashes($row['pu_dbc']), 0, ',', ' '); ?></td>
                                                            <td style="text-align: center"><?php echo stripslashes($row['remise_dbc']); ?>
                                                                %
                                                            </td>
                                                            <td style="text-align: right"><?php echo number_format($ttc, 0, ',', ' '); ?></td>
                                                        </tr>

                                                        <?php
                                                    }
                                                }
                                            ?>
                                            <thead>
                                            <th class="entete" style="text-align: center" colspan="4">
                                                TOTAL
                                            </th>
                                            <th class="entete"
                                                style="text-align: right"><?php echo number_format($total, 0, ',', ' '); ?></th>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        ?>

    <?php else : ?>

        <body onload="numero_bon_cmd();">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Bon de Commande
                    <a href='form_principale.php?page=accueil' type='button'
                       class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                        <span aria-hidden='true'>&times;</span>
                    </a>
                </div>
                <div class="panel-body" style="overflow: auto">
                    <form id="myForm">
                        <div class="row">
                            <div class="col-md-10">
                                <table class="formulaire" border="0" style="margin-right: auto; width: 100%">
                                    <tr>
                                        <td class="champlabel" style="width: 150px">
                                            Numéro :
                                        </td>
                                        <td style="width: 100px">
                                            <label>
                                                <h4>
                                                    <span class="label label-primary" name="num_bc" id="num_bc"></span>
                                                </h4>
                                            </label>
                                        </td>
                                        <td>
                                            <div class="panel panel-default" style="margin-bottom: 0; width: 80%">
                                                <table border="0" style="border-collapse: separate;border-spacing: 10px">
                                                    <tr>
                                                        <td>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="choix" value="oui">
                                                                <span id="choixLabel1">A partir d'une proforma</span>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="choix" value="non" readonly>
                                                                <span id="choixLabel2">Générique</span>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>

                                        </td>
                                    </tr>
                                    <tr id="row_proforma">
                                        <td class="champlabel proforma" style="padding-left: 10px" colspan="2">
                                            Veuillez entrer la proforma :
                                        </td>
                                        <td class="proforma" style="padding-left: 5px">
                                            <label>
                                                <input type="text" class="form-control" id="num_pro">
                                            </label>
                                        </td>
                                    </tr>
                                    <tr id="row_new">
                                        <td colspan="4">
                                            <table class="formulaire" style="width: 100%" border="0">
                                                <tr>
                                                    <td class="champlabel">Employé :</td>
                                                    <td>
                                                        <label>
                                                            <?php
                                                                //include 'fonctions.php';
                                                                $iniFile = 'config.ini';

                                                                $config = parse_ini_file('../' . $iniFile);

                                                                $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
                                                                if ($connexion->connect_error)
                                                                    die($connexion->connect_error);
                                                                
                                                                $sql = "SELECT code_emp, nom_emp, prenoms_emp FROM employes WHERE email_emp= '" . $_SESSION['email'] . "'";
                                                                if ($resultat = $connexion->query($sql)) {
                                                                    $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                                                                    foreach ($ligne as $data) {
                                                                        $code_emp = stripslashes($data['code_emp']);
                                                                        $nom_prenoms_emp = stripslashes($data['prenoms_emp']) . ' ' . stripslashes($data['nom_emp']);
                                                                    }
                                                                }
                                                            ?>
                                                            <h4>
                                                                <span class="label label-primary">
                                                                    <?php echo $nom_prenoms_emp; ?>
                                                                </span>
                                                            </h4>
                                                            <input type="hidden" name="code_emp" value="<?php echo $code_emp; ?>">
                                                        </label>
                                                    </td>
                                                    <td class="champlabel" style="padding-left: 10px"
                                                        title="Si le fournisseur désiré n'apparait pas dans la liste déroulante, veuillez le créer à partir du formulaire Fournisseur">Fournisseur :
                                                    </td>
                                                    <td>
                                                        <label>
                                                            <select id="code_four_gen" class="form-control" required>
                                                                <option disabled selected>Raison Sociale</option>
                                                                <?php
                                                                    $sql = "SELECT code_four, nom_four FROM fournisseurs ORDER BY nom_four ASC ";
                                                                    $res = mysqli_query($connexion, $sql) or exit(mysqli_error($connexion));
                                                                    while ($data = mysqli_fetch_array($res)) {
                                                                        echo '<option value="' . $data['code_four'] . '" >' . $data['nom_four'] . '</option>';
                                                                    }
                                                                ?>
                                                            </select>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="champlabel">Nombre d'articles :</td>
                                                    <td>
                                                        <label>
                                                            <input type="number" min="1" class="form-control" id="nbr_articles" name="nbr" required/>
                                                        </label>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-2">
                                <img src="img/icons_1775b9/delivery.png">
                            </div>
                        </div>

                        <br/>

                        <div id="response"></div>
                    </form>
                </div>
            </div>
        </div>
        </body>

        <script>
            var proformas = ["a", "b"],
                n = $("#nbr_articles").val(),
                nbr_art = $('#nbr_articles'),
                articles = ["a", "b"],
                choix = $('input[type=radio][name=choix]'),
                nbr = $('#nbr').val(),
                num_bc = $('#num_bc').text(),
                code_four = $('#code_four').val();

            function numero_bon_cmd() {
                $.ajax({
                    type: "POST",
                    url: "bons/bons_commande/ajax_num_bon_commande.php",
                    success: function (resultat) {
                        $('#num_bc').text(resultat);
                    }
                });
            }

            $(document).ready(function () {
                //Initialisation
                $('#row_proforma').hide();
                $('#row_new').hide();

                //Script qui previent la validation de la touche entrée
                $('#myform').on('keyup keypress', function (e) {
                    var key = e.keyCode || e.which;
                    if (key === 13) {
                         e.preventDefault;
                         return false;
                     }
                });

                choix.change(function () {
                    if (this.value == "oui") {
                        $('#row_proforma').show();
                        $('#row_new').hide();

                        $('#choixLabel1').addClass('label label-success');
                        $('#choixLabel1').css('font-size', '100%');
                        $('#choixLabel2').removeClass('label label-success');

                        //Reinitialiser le champ du numero de la proforma et la zone
                        //d'affichage des details
                        $('#num_pro').val("");
                        $('#response').empty();

                        $.ajax({
                            url: "factures/proformas/num_proformas.php",
                            dataType: "json",
                            type: "GET",
                            success: function (data) {
                                for (var i = 0; i < data.length; i += 1)
                                    proformas[i] = data[i].num_fp;

                                $('#num_pro').autocomplete({
                                    source: proformas
                                });
                            }
                        })
                    } else if (this.value == "non") {
                        $('#row_proforma').hide();
                        $('#row_new').show();

                        $('#choixLabel2').addClass('label label-success');
                        $('#choixLabel2').css('font-size', '100%');
                        $('#choixLabel1').removeClass('label label-success');

                        //Reinitialiser le combobox du fournisseur et la zone
                        //d'affichage des details
                        $('#code_four_gen')[0].selectedIndex = 0;
                        $('#nbr_articles').val("");
                        $('#response').empty();
                    }
                });
            });

            /* Ce script permet d'afficher le fournisseur et les différents articles figurants de la proforma sélectionnée */
            $("#num_pro").on('keypress', function (e) {
                if (e.which == 13) {
                    $('#response').show();
                    var prof = $("#num_pro").val();
                    $.ajax({
                        type: "POST",
                        url: "bons/bons_commande/ajax_bon_commande.php",
                        data: {
                            proforma: prof
                        },
                        success: function (resultat) {
                            $('#response').html(resultat);
                        }
                    });
                }
            });

            nbr_art.bind('keyup mouseup', function () {
                var n = $("#nbr_articles").val();
                $.ajax({
                    type: "POST",
                    url: "bons/bons_commande/ajax_saisie_details_bon_commande.php",
                    data: {
                        nbr: n
                    },
                    success: function (resultat) {
                        if (n > 0) {
                            $('#response').html(resultat);
                        }
                    }
                });
            });

            nbr_art.bind('blur', function () {
                $.ajax({
                    url: "articles/libelles_articles.php",
                    dataType: "json",
                    type: "GET",
                    success: function (data) {
                        for (var i = 0; i < data.length; i += 1) {
                            articles[i] = data[i].designation_art;
                        }
                        $('input[id*="libelle_dbc"]').autocomplete({
                            source: articles
                        });
                    }
                });
            });

            function validation() {
                var i = 0;
                $(':input[required]').each(function () {
                    if (this.value == '')
                        i++;
                });
                return i;
            }

            function ajout() {
                //variables pour les details sur le bon de commande
                var libelle_dbc = new Array(),
                    qte_dbc = new Array(),
                    pu_dbc = new Array(),
                    rem_dbc = new Array();

                for (var i = 0; i < nbr; i = i + 1) {
                    try {
                        libelle_dbc[i] = $('[id*="libelle_dbc"]')[i].value;
                        qte_dbc[i] = $('[id*="qte_dbc"]')[i].value;
                        pu_dbc[i] = $('[id*="pu_dbc"]')[i].value;
                        rem_dbc[i] = $('[id*="rem_dbc"]')[i].value;
                    } catch(e) {
                        alert(e.message + ". Veuillez consulter la console pour plus de détails");
                        console.log(e);
                    }
                }

                var json_libelle = JSON.stringify(libelle_dbc),
                    json_qte = JSON.stringify(qte_dbc),
                    json_pu = JSON.stringify(pu_dbc),
                    json_rem = JSON.stringify(rem_dbc);

                var infos = "i=" + nbr + "&num_bc=" + num_bc + "&code_four=" + code_four + "&libelle_dbc=" + json_libelle + "&qte_dbc=" + json_qte + "&pu_dbc=" + json_pu + "&rem_dbc=" + json_rem,
                    operation = "ajout_bon_cmd";

                $.ajax({
                    type: 'POST',
                    url: 'bons/bons_commande/updatedata.php?operation=' + operation,
                    data: infos,
                    success: function (data) {
                        numero_bon_cmd();
                        $('#myForm').trigger('reset');
                        $('#row_proforma').hide();
                        $('#choixLabel1').removeClass('label label-success');
                        $('#choixLabel2').removeClass('label label-success');
                        $('#row_new').hide();
                        $('#response').html(data);
                        setTimeout(function () {
                            $('.alert-success').slideToggle('slow');
                        }, 2500);
                    }
                });
            }

            function ajout_bon_commande() {
                var test = true;
                console.log(test);
                console.log(code_four);

                if (code_four == null) {
                    code_four = $('#code_four_gen').val();
                    test = false;
                }

                if (test == true)
                    console.log(test);
                else {
                    if ($('#code_four_gen')[0].value == "Raison Sociale")
                        alert("Veuillez sélectionner un fournisseur");
                    else if (validation() != 0)
                        alert("Veuillez remplir TOUS les champs requis s'il vous plaît.");
                    else {
                        ajout();
                    }
                }

            }
        </script>

    <?php endif; ?>
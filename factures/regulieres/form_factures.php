<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 12-Aug-15
     * Time: 10:14 AM
     */
    if (isset($_GET['action']) && $_GET['action'] == "consultation") : ?>

        <?php
        $code = $_GET['id'];

        $sql = "SELECT * FROM factures WHERE num_fact = '" . $code . "'";
        if ($res = $connexion->query($sql)) {
            $lines = $res->fetch_all(MYSQLI_ASSOC);
            foreach ($lines as $line) { ?>
                <!--suppress ALL -->
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Facture <?php echo $code; ?>
                            <a href='form_principale.php?page=accueil' type='button'
                               class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                <span aria-hidden='true'>&times;</span>
                            </a>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-10">
                                    <table class="formulaire" style="width= 100%" border="0">
                                        <tr>
                                            <td class="champlabel" title="Référence facture">Référence :</td>
                                            <td>
                                                <label>
                                                    <input type="text" name="num_fact" class="form-control" size="15"
                                                           readonly
                                                           value=""/>
                                                    <h4>
                                                        <span class="label label-primary" name="num_fact">
                                                            <?php echo $line['num_fact']; ?>
                                                        </span>
                                                    </h4>
                                                </label>
                                            </td>
                                            <td class="champlabel" title="Numéro figurant sur la facture">N° Facture :
                                            </td>
                                            <td>
                                                <label>
                                                    <input type="text" name="ref_fact" class="form-control" size="15"
                                                           value="<?php echo $line['ref_fact']; ?>"
                                                           readonly/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="champlabel">Date d'Etablissement :</td>
                                            <td>
                                                <label>
                                                    <input type="text" name="dateetablissement_fact"
                                                           readonly
                                                           value="<?php echo rev_date($line['dateetablissement_fact']); ?>"
                                                           class="form-control"/>
                                                </label>
                                            </td>
                                            <td class="champlabel">Date de reception :</td>
                                            <td>
                                                <label>
                                                    <input type="text" name="datereception_fact"
                                                           class="form-control" readonly
                                                           value="<?php echo rev_date($line['datereception_fact']); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="champlabel" rowspan="2">Remarques facture :</td>
                                            <td>
                                                <label>
                                                        <textarea name="remarques_facture" rows="3" cols="20"
                                                                  style="resize: none"
                                                                  maxlength="200" readonly
                                                                  class="form-control"><?php echo $line['remarques_facture']; ?></textarea>
                                                </label>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-2">
                                    <img src="img/icons_1775b9/facture-100.png">
                                </div>
                                <br/>

                                <div class="response">
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
                                                    $sql3 = "SELECT * FROM details_facture WHERE num_fact = '" . $code . "'";
                                                    if ($result = $connexion->query($sql3)) {
                                                        $rows = $result->fetch_all(MYSQLI_ASSOC);
                                                        $total = 0;
                                                        foreach ($rows as $row) {
                                                            $qte = stripslashes($row['qte_df']);
                                                            $pu = stripslashes($row['pu_df']);
                                                            $rem = stripslashes($row['remise_df']);

                                                            if ($rem > 0) {
                                                                $rem = $rem / 100;
                                                                $ttc = $qte * $pu * (1 - $rem);
                                                            }
                                                            else
                                                                $ttc = $qte * $pu;

                                                            $total = (int)$total + (int)$ttc;
                                                            ?>

                                                            <tr>
                                                                <td style="text-align: left"><?php echo stripslashes($row['libelle_df']); ?></td>
                                                                <td style="text-align: center"><?php echo stripslashes($row['qte_df']); ?></td>
                                                                <td style="text-align: right"><?php echo number_format(stripslashes($row['pu_df']), 0, ',', ' '); ?></td>
                                                                <td style="text-align: center"><?php echo stripslashes($row['remise_df']); ?>
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
                </div>
                <?php
            }
        }
        ?>

    <?php else: ?>

        <!--suppress ALL -->
        <body onload="numero_fact()">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Facture
                    <a href='form_principale.php?page=accueil' type='button'
                       class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                        <span aria-hidden='true'>&times;</span>
                    </a>
                </div>
                <div class="panel-body">
                    <form name="myForm">
                        <div class="row">
                            <div class="col-md-10">
                                <table class="formulaire" style="width= 100%" border="0">
                                    <tr>
                                        <td class="champlabel" title="Référence facture">Référence :</td>
                                        <td>
                                            <label>
                                                <h4>
                                                    <span class="label label-primary" name="num_fact" id="num_fact"></span>
                                                </h4>
                                            </label>
                                        </td>
                                        <td class="champlabel" title="Numéro figurant sur la facture">N° Facture :
                                        </td>
                                        <td>
                                            <label>
                                                <input type="text" name="ref_fact" class="form-control" size="15"
                                                       onblur="this.value = this.value.toUpperCase();"
                                                       required/>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="champlabel">Date d'Etablissement :</td>
                                        <td>
                                            <label>
                                                <input type="text" name="dateetablissement_fact"
                                                       id="date_e" readonly
                                                       title="Veuillez cliquer ici pour sélectionner une date"
                                                       class="form-control"/>
                                            </label>
                                        </td>
                                        <td class="champlabel">Date de reception :</td>
                                        <td>
                                            <label>
                                                <input type="text" name="datereception_fact"
                                                       class="form-control" id="date_r" readonly
                                                       title="Veuillez cliquer ici pour sélectionner une date"
                                                       required/>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="champlabel">Remarques facture :</td>
                                        <td>
                                            <label>
                                                <textarea name="remarques_facture" rows="3" cols="20" style="resize: none"
                                                          maxlength="200"
                                                          class="form-control"></textarea>
                                            </label>
                                        </td>
                                        <td colspan="2">
                                            <div class="panel panel-default" style="margin-bottom: 0">
                                                <table class="formulaire" border="0">
                                                    <tr>
                                                        <td>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="choix" value="oui">
                                                                <span id="choixLabel1">A partir d'une proforma</span>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="choix" value="non">
                                                                <span id="choixLabel2">Générique</span>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="row_proforma">
                                        <td colspan="4">
                                            <table>
                                                <tr>
                                                    <td class="champlabel">
                                                        Veuillez entrer la proforma :
                                                    </td>
                                                    <td class="proforma" style="padding-left: 5px">
                                                        <label>
                                                            <input type="text" class="form-control" id="num_pro">
                                                        </label>
                                                    </td>
                                                </tr>
                                            </table>
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
                            <div class="col-md-1" style="margin-left: 3%;">
                                <img src="img/icons_1775b9/facture-100.png">
                            </div>
                            <br/>

                            <div id="response"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </body>


        <script>
            var proformas = ["a", "b"],
                choix = $('input[type=radio][name=choix]');

            $(document).ready(function () {
                $('#date_e').datepicker({dateFormat: 'dd-mm-yy'});
                $('#date_r').datepicker({dateFormat: 'dd-mm-yy'});
                //Initialisation
                $('#row_proforma').hide();
                $('#row_new').hide();

                $("select.proforma").change(function () {
                    var pro = $(".proforma option:selected").val();
                    $.ajax({
                        type: "POST",
                        url: "factures/regulieres/ajax_factures_proforma.php",
                        data: {
                            proforma: pro
                        },
                        success: function (resultat) {
                            $('.response').html(resultat);
                        }
                    });
                });
            });

            function numero_fact() {
                $.ajax({
                    type: "POST",
                    url: "factures/regulieres/ajax_num_facture.php",
                    success: function (resultat) {
                        $('#num_fact').text(resultat);
                    }
                });
            }

            choix.change(function () {
                if (this.value == "oui") {
                    $('#row_proforma').show();
                    $('#row_new').hide();

                    $('#choixLabel1').addClass('label label-info');
                    $('#choixLabel1').css('font-size', '100%');
                    $('#choixLabel2').removeClass('label label-info');

                    //Reinitialiser le champ du numero de la proforma et la zone
                    //d'affichage des details
                    $('#num_pro').val("");
                    $('#response').empty();

                    $.ajax({
                        url: "factures/proformas/num_proformas.php",
                        dataType: "json",
                        type: "GET",
                        success: function (data) {
                            for (var i = 0; i < data.length; i += 1) {
                                proformas[i] = data[i].num_fp;
                            }
                            $('#num_pro').autocomplete({
                                source: proformas
                            });
                        }
                    });
                }
                else if (this.value == "non") {
                    $('#row_proforma').hide();
                    $('#row_new').show();

                    $('#choixLabel2').addClass('label label-info');
                    $('#choixLabel2').css('font-size', '100%');
                    $('#choixLabel1').removeClass('label label-info');

                    //Reinitialiser le combobox du fournisseur et la zone
                    //d'affichage des details
                    $('#code_four_gen')[0].selectedIndex = 0;
                    $('#nbr_articles').val("");
                    $('#response').empty();
                }
            })

            $("#num_pro").on('keypress', function (e) {
                if (e.which == 13) {
                    $('#response').show();
                    var proforma = $("#num_pro").val();
                    $.ajax({
                        type: "POST",
                        url: "factures/regulieres/ajax_factures_proforma.php",
                        data: {
                            proforma: proforma
                        },
                        success: function (resultat) {
                            $('#response').html(resultat);
                        }
                    });
                }
            });

            function validationForme() {
                var date_e = $('#date_e').val();
                var date_r = $('#date_r').val();
                if (date_e == null || date_e == "" || date_r == null || date_r == "") {
                    alert("Veuillez renseignez les différentes dates s'il vous plaît.");
                    return false;
                }
            }
            
            function ajout(code_four) {
                var code_four = code_four,
                    num_fact = $('#num_fact').text();
            }
        </script>

    <?php endif; ?>
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
                                                           value="<?php echo $line['num_fact']; ?>"/>
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
        <meta charset="utf-8">
        <script>
            $.ajax({
                type: "POST",
                url: "factures/ajax_num_facture.php",
                success: function (resultat) {
                    $('#num_fact').val(resultat);
                }
            });
        </script>

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
                    <form action="form_principale.php?page=factures/form_factures" method="POST" name="myForm"
                          onsubmit="return validationForme();">
                        <div class="row">
                            <div class="col-md-10">
                                <table class="formulaire" style="width= 100%" border="0">
                                    <tr>
                                        <td class="champlabel" title="Référence facture">Référence :</td>
                                        <td>
                                            <label>
                                                <input type="text" name="num_fact" class="form-control" size="15"
                                                       readonly
                                                       id="num_fact"/>
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
                                        <td class="champlabel" rowspan="2">Remarques facture :</td>
                                        <td>
                                            <label>
                                                <textarea name="remarques_facture" rows="3" cols="20" style="resize: none"
                                                  maxlength="200"
                                                  class="form-control"></textarea>
                                            </label>
                                        </td>
                                        <td colspan="2">
                                            <div class="panel panel-default">
                                                <table class="formulaire" border="0">
                                                    <tr>
                                                        <td>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="choix" value="oui">A partir d'une proforma
                                                            </label>
                                                        </td>
                                                        <td class="champlabel pro">Proforma :</td>
                                                        <td class="pro">
                                                            <label>
                                                                <input type="text" class="form-control" id="num_pro" size="9">
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="choix" value="non" readonly>Générique
                                                            </label>
                                                        </td>
                                                        <td class="champlabel generique">Nombre d'articles :</td>
                                                        <td class="generique">
                                                            <label>
                                                                <input type="number" class="form-control" name="nbr">
                                                            </label>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>

                                        </td>

                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-1" style="margin-left: 3%;">
                                <img src="img/icons_1775b9/facture-100.png">
                            </div>
                            <br/>

                            <div class="response"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            var proformas = ["a", "b"];
            function validationForme() {
                var date_e = $('#date_e').val();
                var date_r = $('#date_r').val();
                if (date_e == null || date_e == "" || date_r == null || date_r == "") {
                    alert("Veuillez renseignez les différentes dates s'il vous plaît.");
                    return false;
                }
            }

            $(document).ready(function () {
                $('#date_e').datepicker({dateFormat: 'dd-mm-yy'});
                $('#date_r').datepicker({dateFormat: 'dd-mm-yy'});
                $('.pro').hide();
                $('.generique').hide();

                $("select.proforma").change(function () {
                    var pro = $(".proforma option:selected").val();
                    $.ajax({
                        type: "POST",
                        url: "factures/ajax_factures_proforma.php",
                        data: {
                            proforma: pro
                        },
                        success: function (resultat) {
                            $('.response').html(resultat);
                        }
                    });
                });

                $.ajax({
                    url: "bons_commande/num_proformas.php",
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
            })

            $('#num_pro').on('keypress', function (e) {
                if (e.which == 13) {
                    var prof = $("#num_pro").val();
                    console.log(prof);
                    $.ajax({
                        type: "POST",
                        url: "factures/ajax_factures_proforma.php",
                        data: {
                            proforma: prof
                        },
                        success: function (resultat) {
                            $('.response').html(resultat);
                        }
                    });
                }
            });
        </script>

        <?php

        if ((sizeof($_POST) > 0) && (isset($_POST['num_fact']))) {

            include 'class_factures.php';

            $facture = new factures();

            if ($facture->recuperation($_POST['num_fact'])) { $facture->enregistrement();
                /*if ($facture->enregistrement()) {
                    header('Location: form_principale.php?page=factures/form_factures');
                }
                else {
                    echo "
            <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative d'enregistrement de la facture. Veuillez contacter l'administrateur.
            </div>
            ";
                }*/
            }
            else {
                echo "
            <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la récupération des informations de la facture. Veuillez contacter l'administrateur.
            </div>
            ";
            }
        }
        ?>

    <?php endif; ?>
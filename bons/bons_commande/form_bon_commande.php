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
                                                       id="four" readonly
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

                            <div class="zone">
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
                    <form method="POST" name="myForm" onsubmit="return validationForme();">
                        <div class="row">
                            <div class="col-md-10">
                                <table class="formulaire" border="0" style="margin-right: auto">
                                    <tr>
                                        <td class="champlabel">Numéro :</td>
                                        <td>
                                            <label>
                                                <h4>
                                                    <span class="label label-primary" name="num_bc" id="num_bc"></span>
                                                </h4>
                                            </label>
                                        </td>
                                        <td style="padding-left: 30px">
                                            A partir d'une proforma ?
                                        </td>
                                        <td style="padding-left: 5px">
                                            <label>
                                                <select name="question" class="form-control" required id="question">
                                                    <option disabled selected></option>
                                                    <option value="oui">OUI</option>
                                                    <option value="non">NON</option>
                                                </select>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="champlabel proforma" style="padding-left: 10px" colspan="3">
                                            Veuillez entrer la proforma :
                                        </td>
                                        <td class="proforma" style="padding-left: 5px">
                                            <label>
                                                <input type="text" class="form-control" id="num_pro">
                                            </label>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-2">
                                <img src="img/icons_1775b9/bon.png">
                            </div>
                        </div>

                        <br/>

                        <div class="zone"></div>
                    </form>
                </div>
            </div>
        </div>
        </body>

        <script>
            var proformas = ["a", "b"];
            function validationForme() {
                var code_four = $('#four').val();
                if (code_four == null || code_four == "" || code_four == "Raison Sociale") {
                    alert("Veuillez sélectionner un fournisseur.");
                    return false;
                }
            }

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
                $('.proforma').hide();
                $('.zone').hide();

                //Script qui previent la validation de la touche entrée
                $('#myform').on('keyup keypress', function (e) {
                    var key = e.keyCode || e.which;
                    if (key === 13) {
                         e.preventDefault;
                         return false;
                     }
                });

                $('#question').change(function () {
                    var reponse = $('#question').val();
                    if (reponse == 'oui') {
                        $('.proforma').show();
                        $('.zone').hide();
                        //Vider le champ du numero de la proforma
                        $('')

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
                    }
                    else {
                        $('.proforma').hide();
                        $('.zone').show();
                        $.ajax({
                            type: 'GET',
                            url: 'bons/bons_commande/ajax_saisie_bon_commande.php',
                            success: function (resultat) {
                                $('.zone').html(resultat);
                            }
                        });
                    }
                });

                /* Ce script permet d'afficher le fournisseur et les différents articles figurants de la proforma sélectionnée */
                $("#num_pro").on('keypress', function (e) {
                    if (e.which == 13) {
                        $('.zone').show();
                        var prof = $("#num_pro").val();
                        $.ajax({
                            type: "POST",
                            url: "bons/bons_commande/ajax_bon_commande.php",
                            data: {
                                proforma: prof
                            },
                            success: function (resultat) {
                                $('.zone').html(resultat);
                            }
                        });
                    }
                });

                setTimeout(function () {
                    $(".alert-success").remove();
                }, 3000);

                function ajout_bon_cmd() {
                    var num_bon = $('#num_bc').text();

                }

            })
        </script>

        <?php
        if ((sizeof($_POST) > 0) && (isset($_POST['num_bc']))) {
            include 'class_bons_commandes.php';

            $bon_commande = new bons_commandes();
            if ($bon_commande->recuperer($_POST['num_bc'])) {
                if ($bon_commande->enregistrer()) {
                    header('Location: form_principale.php?page=bons/bons_commande/form_bon_commande');
                } else {
                    echo "
            <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative d'enregistrement du bon de commande. Veuillez contacter l'administrateur.
            </div>
            ";
                }
            } else {
                echo "
            <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la récupération des informations du bon de commande. Veuillez contacter l'administrateur.
            </div>
            ";
            }
        }
        ?>

    <?php endif; ?>
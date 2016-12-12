<?php
/**
 * Created by PhpStorm.
 * User: Ange KOUAKOU
 * Date: 19-Jul-15
 * Time: 8:10 PM
 */
?>

<?php if (isset($_GET['action']) && $_GET['action'] == "consultation") : ?>

    <?php
    $code = $_GET['id'];

    $sql = "SELECT * FROM proformas WHERE num_fp = '" . $code . "'";
    if ($valeur = $connexion->query($sql)) {
        $ligne = $valeur->fetch_all(MYSQLI_ASSOC);
        foreach ($ligne as $data) {
            ?>
            <!--suppress ALL -->
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Facture Proforma
                        <a href='form_principale.php?page=proformas/liste_proformas' type='button'
                           class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                            <span aria-hidden='true'>&times;</span>
                        </a>
                    </div>
                    <div class="panel-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-10" style="margin-bottom: 10px">
                                    <table class="formulaire" style="width= 100%" border="0">
                                        <tr>
                                            <td class="champlabel">Numéro :
                                            </td>
                                            <td>
                                                <label>
                                                    <input type="text" size="10" name="num_fp" class="form-control"
                                                           id="num_fp"
                                                           value="<?php echo stripslashes($data['num_fp']); ?>"
                                                           readonly/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="champlabel">Fournisseur :</td>
                                            <td>
                                                <label>
                                                    <?php
                                                    $code_four = stripslashes($data['code_four']);
                                                    $sql = "SELECT nom_four FROM fournisseurs WHERE code_four = '" . $code_four . "'";
                                                    $res = mysqli_query($connexion, $sql) or exit(mysqli_error($connexion));
                                                    while ($list = mysqli_fetch_array($res)) {
                                                        ?>
                                                        <input type="text" name="four" size="40"
                                                               value="<?php echo $list['nom_four']; ?>"
                                                               class="form-control" readonly>
                                                        <?php
                                                    }
                                                    ?>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="champlabel" title="Date d'établissement de la Proforma">Date :
                                            </td>
                                            <td>
                                                <label>
                                                    <input type="text" name="dateetablissement_fp"
                                                           value="<?php echo rev_date($data['dateetablissement_fp']); ?>" readonly
                                                           class="form-control"/>
                                                </label>
                                            </td>
                                            <td class="champlabel" rowspan="2">Notes :</td>
                                            <td rowspan="2">
                                                <label>
                                                    <textarea rows="3" cols="20" name="notes_fp" style="resize: none"
                                                              class="form-control"
                                                              readonly><?php echo $data['notes_fp']; ?></textarea>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="champlabel">Date de reception :</td>
                                            <td>
                                                <label>
                                                    <input type="text" name="datereception_fp"
                                                           value="<?php echo rev_date($data['datereception_fp']); ?>"
                                                           class="form-control" readonly/>
                                                </label>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-2">
                                    <img src="img/icons_1775b9/proforma.png">
                                </div>
                                <br/>

                                <div class="response">
                                    <?php
                                    $sql = "SELECT * FROM details_proforma WHERE num_fp = '" . $code . "'";
                                    if ($valeur = $connexion->query($sql)) {
                                        $ligne = $valeur->fetch_all(MYSQLI_ASSOC);
                                        ?>
                                        <div class="col-md-12">
                                            <div class="panel panel-default">
                                                <table class="table table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th class="entete" style="text-align: center; width: 65%">
                                                            Libellé
                                                        </th>
                                                        <th class="entete" style="text-align: center">Quantité</th>
                                                        <th class="entete" style="text-align: center; width: 10%">P.U.
                                                        </th>
                                                        <th class="entete" style="text-align: center">Remise</th>
                                                        <th class="entete" style="text-align: center; width: 20%">Prix
                                                            T.T.C
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <?php
                                                    $total = 0;
                                                    foreach ($ligne as $list) {
                                                        $rem = $list['remise_dfp'];
                                                        $qte = $list['qte_dfp'];
                                                        $pu = $list['pu_dfp'];
                                                        if ($rem > 0) {
                                                            $rem = $rem / 100;
                                                            $ttc = $qte * $pu * (1 - $rem);
                                                        } else
                                                            $ttc = $qte * $pu;

                                                        $total += $ttc
                                                        ?>
                                                        <tr>
                                                            <td class="champlabel"
                                                                style="text-align: left"><?php echo $list['libelle']; ?></td>
                                                            <td class="champlabel"
                                                                style="text-align: center"><?php echo $list['qte_dfp']; ?></td>
                                                            <td class="champlabel"
                                                                style="text-align: right"><?php echo number_format($list['pu_dfp'], 0, ',', ' '); ?></td>
                                                            <td class="champlabel"
                                                                style="text-align: center"><?php echo $list['remise_dfp'] . '%'; ?></td>
                                                            <td class="champlabel"
                                                                style="text-align: right; color: #0e76bc"><?php echo number_format($ttc, 0, ',', ' '); ?></td>
                                                        </tr>
                                                        <?php
                                                    } ?>
                                                    <tr style="font-weight: bolder; font-family: OpenSans-Semibold, serif; color: #0e76bc">
                                                        <td class="entete" style="text-align: center" colspan="4">
                                                            TOTAL
                                                        </td>
                                                        <td class="entete"
                                                            style="text-align: right"><?php echo number_format($total, 0, ',', ' '); ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
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

    <script>
        $.ajax({
            type: "POST",
            url: "proformas/ajax_num_proforma.php",
            success: function (resultat) {
                $('#num_fp').val(resultat);
            }
        });
    </script>

    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                Facture Proforma
                <a href='form_principale.php?page=accueil' type='button'
                   class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </a>
            </div>
            <div class="panel-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-10">
                            <table class="formulaire" style="width= 100%" border="0">
                                <tr>
                                    <td class="champlabel" title="Le numéro de la proforma en cours de saisie">
                                        Numéro :
                                    </td>
                                    <td>
                                        <label>
                                            <input type="text" size="10" name="num_fp" class="form-control"
                                                   id="num_fp"
                                                   readonly/>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="champlabel">*Fournisseur :</td>
                                    <td>
                                        <label>
                                            <select name="code_four" required class="form-control">
                                                <option disabled selected></option>
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
                                    <td class="champlabel" title="Date d'établissement de la Proforma">*Date :</td>
                                    <td>
                                        <label>
                                            <input type="text" id="dateetablissement_fp"
                                                   name="dateetablissement_fp" required
                                                   title="Veuillez cliquer ici pour sélectionner une date"
                                                   class="form-control" readonly/>
                                        </label>
                                    </td>
                                    <td class="champlabel" rowspan="2">Notes :</td>
                                    <td rowspan="2">
                                        <label>
                                                <textarea rows="3" cols="20" name="notes_fp" style="resize: none"
                                                          class="form-control"></textarea>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="champlabel">*Date de reception :</td>
                                    <td>
                                        <label>
                                            <input type="text" id="datereception_fp"
                                                   name="datereception_fp" required
                                                   title="Veuillez cliquer ici pour sélectionner une date"
                                                   class="form-control" readonly/>
                                        </label>
                                    </td>
                                    <td></td>

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

                                <script>
                                    $(document).ready(function() {
                                        $('#dateetablissement_fp').datepicker({ dateFormat: 'dd-mm-yy' });
                                        $('#datereception_fp').datepicker({ dateFormat: 'dd-mm-yy' });
                                    });

                                    var date_eta = "";
                                    var date_rec = "";

                                    $('#dateetablissement_fp').on('change', function () {
                                        date_eta = this.value;
                                        if (date_rec != "") {
                                            if (this.value > date_rec) {
                                                alert("Veuillez choisir une date postérieure à la date de réception.");
                                                this.value = "";
                                            }
                                        }
                                    })

                                    $('#datereception_fp').on('change', function () {
                                        date_rec = this.value;
                                        if (date_eta != "") {
                                            if (this.value < date_eta) {
                                                alert("Veuillez choisir une date antérieure à la date d'établissement.");
                                                this.value = "";
                                            }
                                        }
                                    })
                                    
                                    var articles = ["a", "b"],
                                        nbr_art = $('input[type=number]#nbr_articles');

                                    nbr_art.bind('keyup mouseup', function () {
                                        var n = $("#nbr_articles").val();
                                        $.ajax({
                                            type: "POST",
                                            url: "proformas/ajax_saisie_proformas.php",
                                            data: {
                                                nbr: n
                                            },
                                            success: function (resultat) {
                                                //console.log(resultat);
                                                if (n >= 1) {
                                                    $('.response').html(resultat);
                                                }
                                            }
                                        });
                                    });

                                    nbr_art.bind('blur', function() {
                                        $.ajax({
                                            url: "articles/libelles_articles.php",
                                            dataType: "json",
                                            type: "GET",
                                            success: function(data) {
                                                for (var i = 0; i < data.length; i += 1) {
                                                    articles[i] = data[i].designation_art;
                                                }
                                                $('input[name*="libelle"]').autocomplete({
                                                    source: articles
                                                });
                                            }
                                        });
                                    });
                                </script>
                            </table>
                        </div>
                        <div class="col-md-2">
                            <img src="img/icons_1775b9/proforma.png">
                        </div>
                        <br/>

                        <div class="response"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php

    if ((sizeof($_POST) > 0) && (isset($_POST['num_fp']))) {
        include 'class_proformas.php';

        $proforma = new proformas();
        
        if ($proforma->recuperation($_POST['num_fp'])) {
            if ($proforma->enregistrement()) {
                header('Location: form_principale.php?page=proformas/form_proformas');
            } else {
                echo "
            <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative d'enregistrement de la proforma. Veuillez contacter l'administrateur.
            </div>
            ";
            }
        } else {
            echo "
            <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la récupération des informations de la proforma. Veuillez contacter l'administrateur.
            </div>
            ";
        }
    }
    ?>

<?php endif; ?>
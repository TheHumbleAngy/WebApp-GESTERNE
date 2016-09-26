<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 12-Aug-15
     * Time: 10:14 AM
     */
?>
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
            <form action="form_principale.php?page=factures/form_factures" method="POST" name="myForm" onsubmit="return validationForme();">
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
                                <td class="champlabel">Proforma :</td>
                                <td>
                                    <label>
                                        <input type="text" class="form-control" id="num_pro">
                                    </label>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-2">
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
        var date_e = document.forms["myForm"]["dateetablissement_fact"].value;
        var date_r = document.forms["myForm"]["datereception_fact"].value;
        if (date_e == null || date_e == "" || date_r == null || date_r == "") {
            alert("Veuillez renseignez les différentes dates s'il vous plaît.");
            return false;
        }
    }

    $(document).ready(function () {
        $('#date_e').datepicker({dateFormat: 'dd-mm-yy'});
        $('#date_r').datepicker({dateFormat: 'dd-mm-yy'});

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
                    proformas[i] = data[i].ref_fp;
                }
                $('#num_pro').autocomplete({
                    source: proformas
                });
            }
        });
    })

    $('#num_pro').on('change', function () {
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
    });
</script>

<?php

    if (sizeof($_POST) > 0) {

        $num_fact = htmlspecialchars($_POST['num_fact'], ENT_QUOTES);
        $ref_fact = htmlspecialchars($_POST['ref_fact'], ENT_QUOTES);
        $code_four = htmlspecialchars($_POST['code_four'], ENT_QUOTES);
        $dateetablissement_fact = rev_date($_POST['dateetablissement_fact']);
        $datereception_fact = rev_date($_POST['datereception_fact']);
        $remarques_facture = addslashes($_POST['remarques_facture']);

        $req = "INSERT INTO factures (num_fact,
                            code_four,
                            ref_fact,
                            dateetablissement_fact,
                            datereception_fact,
                            remarques_facture)
                VALUES      ('$num_fact',
                            '$code_four',
                            '$ref_fact',
                            '$dateetablissement_fact',
                            '$datereception_fact',
                            '$remarques_facture')";
        
        if ($result = mysqli_query($connexion, $req)) {

            //Saisie de chaque article de la facture dans la table "details_facture"
            $n = $_POST['nbr'];
            $test = TRUE;
            for ($i = 0; $i < $n; $i++) {

                $req = "SELECT code_df FROM details_facture ORDER BY code_df DESC LIMIT 1";
                $resultat = $connexion->query($req);

                if ($resultat->num_rows > 0) {
                    $ligne = $resultat->fetch_all(MYSQLI_ASSOC);

                    //reccuperation du code
                    $code_df = "";
                    foreach ($ligne as $data) {
                        $code_df = stripslashes($data['code_df']);
                    }

                    //extraction des 4 derniers chiffres
                    $code_df = substr($code_df, -4);

                    //incrementation du nombre
                    $code_df += 1;

                    $b = "DF";
                    $dat = date("Y");
                    $dat = substr($dat, -2);
                    $format = '%04d';
                    $resultat = $dat . "" . $b . "" . sprintf($format, $code_df);

                } else {
                    //s'il n'existe pas d'enregistrements dans la base de données
                    $code_df = 1;
                    $b = "DF";
                    $dat = date("Y");
                    $dat = substr($dat, -2);
                    $format = '%04d';
                    $resultat = $dat . "" . $b . "" . sprintf($format, $code_df);
                }
                //on affecte au code le resultat
                $code_df = $resultat;

                $libelle_df = ($_POST['libelle'][$i]);
                $qte_df = ($_POST['qte'][$i]);
                $pu_df = ($_POST['pu'][$i]);
                $rem = ($_POST['rem'][$i]);

                $libelle_df = addslashes($libelle_df);
                $qte_df = htmlspecialchars($qte_df, ENT_QUOTES);
                $pu_df = htmlspecialchars($pu_df, ENT_QUOTES);
                $rem = addslashes($rem);

                $REQ = "INSERT INTO details_facture (code_df, num_fact, libelle_df, qte_df, pu_df, remise_df)
	            VALUES ('$code_df', '$num_fact', '$libelle_df', '$qte_df', '$pu_df', '$rem')";

                //exécution de la requête REQ:
                if (!mysqli_query($connexion, $REQ)) {
                    $test = FALSE;
                    break;
                }
            }
            if ($test) {
                header('Location: form_principale.php?page=factures/form_factures');
            } else {
                echo "
                    <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                        <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative d'enregistrement des détails de la facture. Veuillez contacter l'administrateur.
                    </div>
                    ";
            }
        } else {
            echo "
            <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                <strong>Une erreur s'est produite lors de la tentative d'enregistrement de la facture. Veuillez contacter l'administrateur.</strong>
            </div>
            ";
        }
        mysqli_close($connexion);
    }
?>  

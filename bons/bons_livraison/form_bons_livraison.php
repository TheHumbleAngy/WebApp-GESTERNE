<!--suppress ALL -->
<body onload="numero_bon_liv();">
<div class="col-md-11" style="margin-left: 4.33%">
    <div class="panel panel-default">
        <div class="panel-heading">
            Bon de Livraison
            <a href='form_principale.php?page=accueil' type='button'
               class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                <span aria-hidden='true'>&times;</span>
            </a>
        </div>
        <div class="panel-body">
            <form action="form_principale.php?page=bons_livraison/form_bons_livraison" method="POST">
                <div class="row">
                    <div class="col-md-10">
                        <table class="formulaire" style="width= 100%" border="0">
                            <tr>
                                <td class="champlabel">Numéro :</td>
                                <td>
                                    <label>
                                        <h4>
                                            <span class="label label-primary" name="num_bl" id="num_bl"></span>
                                        </h4>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="champlabel">Date d'établissement :</td>
                                <td>
                                    <label>
                                        <input type="text" name="dateetablissement_bl"
                                               id="dateetablissement_bl" readonly
                                               title="Veuillez cliquer ici pour sélectionner une date"
                                               class="form-control"/>
                                    </label>
                                </td>
                                <td class="champlabel">Date de Réception :</td>
                                <td>
                                    <label>
                                        <input type="text" name="datereception_bl"
                                               id="datereception_bl" readonly
                                               title="Veuillez cliquer ici pour sélectionner une date"
                                               class="form-control"/>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="champlabel">*Fournisseur :</td>
                                <td>
                                    <label>
                                        <select name="code_four" class="form-control" id="four">
                                            <option selected disabled>Raison sociale</option>
                                            <?php
                                            $connexion = db_connect();
                                            $sql = "SELECT code_four, nom_four FROM fournisseurs ORDER BY nom_four ASC ";
                                            $res = mysqli_query($connexion, $sql) or exit(mysqli_error($connexion));
                                            while ($data = mysqli_fetch_array($res)) {
                                                echo '<option value="' . $data['code_four'] . '">' . $data['nom_four'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </label>
                                </td>
                                <td class="champlabel">Livreur :</td>
                                <td>
                                    <label>
                                        <input type="text" name="nom_livreur" class="form-control" size="40"
                                               onblur="this.value = this.value.toUpperCase();"
                                               required/>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="champlabel" title="Numéro du bon de commande">Bon de Commande :</td>
                                <td>
                                    <label>
                                        <input type="text" class="form-control" id="num_bon">
                                        <!--<select name="num_bc" class="form-control" id="bon_commande">
                                            <option selected disabled>Numéro</option>
                                        </select>-->
                                    </label>
                                </td>
                                <td class="champlabel">Réceptionniste :</td>
                                <td>
                                    <label>
                                        <select name="code_emp" required class="form-control">
                                            <option disabled selected>Employé</option>
                                            <?php
                                            //$sql = "SELECT code_emp, nom_emp, prenoms_emp FROM employes WHERE departement_emp IN ('ADMINISTRATION', 'MOYENS GENERAUX') AND nom_emp <> 'ABBEY' ORDER BY nom_emp ASC";
                                            $sql = "SELECT code_emp, nom_emp, prenoms_emp FROM employes WHERE nom_emp <> 'ABBEY' ORDER BY nom_emp ASC";
                                            $res = mysqli_query($connexion, $sql) or exit(mysqli_error($connexion));
                                            while ($data = mysqli_fetch_array($res)) {
                                                echo '<option value="' . $data['code_emp'] . '" >' . $data['prenoms_emp'] . ' ' . $data['nom_emp'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="champlabel">Commentaires :</td>
                                <td>
                                    <label>
                                    <textarea name="commentaires_bl" rows=3 cols="20" class="form-control"
                                              style="resize: none"></textarea>
                                    </label>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-2">
                        <img src="img/icons_1775b9/shipped.png">
                    </div>
                    <br/>

                    <div class="response"></div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>


<script>
    var bon_commande = ["a", "b"];

    function numero_bon_liv() {
        $.ajax({
         type: "POST",
         url: "bons/bons_livraison/ajax_num_bons_livraison.php",
         success: function (resultat) {
         $('#num_bl').text(resultat);
         }
         });
    }

    $(document).ready(function () {
        $('#dateetablissement_bl').datepicker({dateFormat: 'dd-mm-yy'});
        $('#datereception_bl').datepicker({dateFormat: 'dd-mm-yy'});

        var date_e = "";
        var date_r = "";

        $('#dateetablissement_bl').on('change', function () {
            date_e = this.value;
        })

        $('#datereception_bl').on('change', function () {
            date_r = this.value;
        })

        /* Ce script permet de remplir le combobox de la liste des bons de commande en fonction du fournisseur sélectionné */
        $("#four").change(function () {
            var four = $("#four").val();
            $.ajax({
                type: "POST",
                data: {
                    fournisseur: four
                },
                url: "bons/bons_livraison/ajax_bons_livraison.php",
                success: function (resultat) {
                    //On éclate notre string résultat en un tableau 1D où chaque céllule du tableau est fonction du ";"
                    var option = resultat.split(';');
                    var select = $('#bon_commande');
                    select.empty();
                    select.append("<option selected disabled>Numéro</option>");
                    for (var i = 0; i < option.length - 1; i++) {
                        select.append("<option value='" + option[i] + "'>" + option[i] + "</option>");
                    }
                    $('#num_bon').autocomplete({
                        source: option
                    });
                }
            });
        });

        /* Ce script permet d'afficher la liste des articles du bon de commande sélectionné */
        $("#num_bon").on('change', function () {
            var bon = $("#num_bon").val();
            console.log(bon);
            $.ajax({
                type: "POST",
                url: "bons/bons_livraison/ajax_articles_bons_livraison.php",
                data: {
                    bon_cmd: bon
                },
                success: function (resultat) {
                    $('.response').html(resultat);
                }
            });
        });
    })
</script>
<?php

    if (sizeof($_POST) > 0) {

        $num_bl = htmlspecialchars($_POST['num_bl'], ENT_QUOTES);
        $dateetablissement_bl = rev_date($_POST['dateetablissement_bl']);
        $datereception_bl = rev_date($_POST['datereception_bl']);
        $code_four = htmlspecialchars($_POST['code_four'], ENT_QUOTES);
        $num_bc = htmlspecialchars($_POST['num_bc'], ENT_QUOTES);
        $code_emp = htmlspecialchars($_POST['code_emp'], ENT_QUOTES);
        $commentaires_bl = addslashes($_POST['commentaires_bl']);

        $req = "INSERT INTO bons_livraison (num_bl,
                                        num_bc,
                                        dateetablissement_bl,
                                        datereception_bl,
                                        code_four,
                                        code_emp,
                                        commentaires_bl)
                                 VALUES('$num_bl',
                                        '$num_bc',
                                        '$dateetablissement_bl',
                                        '$datereception_bl',
                                        '$code_four',
                                        '$code_emp',
                                        '$commentaires_bl')";

        if ($result = mysqli_query($connexion, $req)) {
            $n = $_POST['n'];
            $tot = 0;
            for ($i = 0; $i < $n; $i++) {
                $req = "SELECT num_dbl FROM details_bon_livraison ORDER BY num_dbl DESC LIMIT 1";
                $resultat = $connexion->query($req);

                if ($resultat->num_rows > 0) {
                    $ligne = $resultat->fetch_all(MYSQLI_ASSOC);

                    $num_dbl = "";
                    foreach ($ligne as $data) {
                        $num_dbl = stripslashes($data['num_dbl']);
                    }

                    $num_dbl = substr($num_dbl, -4);

                    $num_dbl += 1;

                    $b = "DBL";
                    $dat = date("Y");
                    $dat = substr($dat, -2);
                    $format = '%04d';
                    $resultat = $dat . "" . $b . "" . sprintf($format, $num_dbl);


                }
                else {
                    //s'il n'existe pas d'enregistrements dans la base de données
                    $num_dbl = 1;
                    $b = "DBL";
                    $dat = date("Y");
                    $dat = substr($dat, -2);
                    $format = '%04d';
                    $resultat = $dat . "" . $b . "" . sprintf($format, $num_dbl);
                }
                //on affecte au code le resultat
                $num_dbl = $resultat;

                $libelle_dbl = addslashes($_POST['libelle_dbl'][$i]);
                $qte_cmd = $_POST['qte_cmd'][$i];
                $qte_livree = $_POST['qte_livree'][$i];

                $qte_restante = $qte_cmd - $qte_livree;

                //On fait la somme des quantites restantes du bon de livraison en cours
                $tot = (int)$tot + (int)$qte_restante;

                $REQ = "INSERT INTO details_bon_livraison (num_dbl, num_bl, libelle_dbl, qte_livree, qte_restante)
	            VALUES ('$num_dbl', '$num_bl', '$libelle_dbl', '$qte_livree', '$qte_restante')";

                if ($requete = mysqli_query($connexion, $REQ))
                    $_SESSION['temp'] = htmlspecialchars($_POST['code_emp'], ENT_QUOTES);
                else
                    echo "
                <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                    <strong>Une erreur s'est produite lors de l'enregistrer des détails du bon de livraison. Veuillez contacter l'administrateur.</strong>
                </div>
                ";
            }

            //On verifie si le total comptabilise des quantites restantes est superieur a 0. Cela voudra donc dire que le bon de livraison
            //satisfait partiellement le bon de commande
            if ($tot > 0)
                $sql = "UPDATE bons_commande SET statut = 'livre partiellement' WHERE num_bc = '" . $num_bc . "'";
            //Sinon, c'est a dire le total est a 0, on marque le bon de commande comme livre; le bon de livraison satisfait otalement la commande
            else
                $sql = "UPDATE bons_commande SET statut = 'livre' WHERE num_bc = '" . $num_bc . "'";

            if ($result = mysqli_query($connexion, $sql))
                header('Location: form_principale.php?page=bons_livraison/form_bons_livraison');
            else
                echo "
                <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                    <strong>Une erreur s'est produite lors de la mise à jour du bon de commande. Veuillez contacter l'administrateur.</strong>
                </div>
                ";
        } else
            echo "
                <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                    <strong>Une erreur s'est produite lors de l'enregistrer du bon de livraion. Veuillez contacter l'administrateur.</strong>
                </div>
                ";
        mysqli_close($connexion);
    }
?>
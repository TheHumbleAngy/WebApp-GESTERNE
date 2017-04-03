<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 31-Aug-15
     * Time: 3:49 PM
     */
    include '../../fonctions.php';
    $iniFile = 'config.ini';

    while (!$config = parse_ini_file($iniFile))
        configpath($iniFile);

    $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

    session_start();
?>
    <table class="formulaire" style="width: 100%" border="0">
        <tr>
            <td class="champlabel">Employé :</td>
            <td>
                <label>
                    <?php
                        $sql = "SELECT code_emp, nom_emp, prenoms_emp FROM employes WHERE email_emp= '" . $_SESSION['email'] . "'"; //print_r($sql);
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
                title="Si le fournisseur désiré n'apparait pas
    dans la liste déroulante, veuillez le créer
    à partir du formulaire Fournisseur">Fournisseur :</td>
            <td>
                <label>
                    <select name="code_four" class="form-control" id="four" required>
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
        <tr>
            <td colspan="4">
                <div class="response"></div>
            </td>
        </tr>
    </table>

    <script>
        var n = $("#nbr_articles").val(),
            nbr_art = $('input[type=number]#nbr_articles'),
            articles = ["a", "b"];
    
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
                        $('.response').html(resultat);
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
                        //noinspection JSUnresolvedVariable
                        articles[i] = data[i].designation_art;
                    }
                    $('input[name*="libelle"]').autocomplete({
                        source: articles
                    });
                }
            });
        });
    </script>
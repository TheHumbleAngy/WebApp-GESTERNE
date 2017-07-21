<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 13/11/2015
     * Time: 11:07
     */
    $iniFile = 'config.ini';
    $config = parse_ini_file('../../' . $iniFile);
    $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

    $action = $_POST['action'];
?>

<?php if ($action == "ajouter"): ?>
    <!--suppress ALL-->
    <div class="col-md-7" id="ajout_user" style="margin-left: 20.83%">
        <div class="panel panel-default">
            <div class="panel-heading" style="font-size: 12px; font-weight: bolder">
                Ajout d'un Utilisateur
                <a id='fermer' type='button'
                   class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </a>
            </div>
            <div class="panel-body">

                <?php
                    $sql = "SELECT code_emp, nom_emp, prenoms_emp FROM employes WHERE code_droit IS NULL ORDER BY nom_emp ASC ";
                    $res = mysqli_query($connexion, $sql) or exit(mysqli_error($connexion));
                    if ($res->num_rows > 0) { ?>
                        <form method="post" action="">
                            <table class="formulaire" style="width: 100%; margin-left: auto; margin-right: auto;" border="0">
                                <tr>
                                    <td class="champlabel">Employé:</td>
                                    <td>
                                        <label>
                                            <select class="form-control employe" name="emp" required>
                                                <option disabled selected></option>
                                                <?php
                                                    $sql = "SELECT code_emp, nom_emp, prenoms_emp FROM employes WHERE code_droit IS NULL ORDER BY nom_emp ASC ";
                                                    $res = mysqli_query($connexion, $sql) or exit(mysqli_error($connexion));
                                                    while ($data = mysqli_fetch_array($res)) {
                                                        echo '<option value="' . $data['code_emp'] . '">' . $data['prenoms_emp'] . ' ' . $data['nom_emp'] . '</option>';
                                                    }
                                                ?>
                                            </select>
                                        </label>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td rowspan="2" style="padding-right: 0">
                                        <img src="img/icons_1775b9/key_exchange_filled.png" height="80" width="80">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="champlabel">Groupe:</td>
                                    <td>
                                        <label>
                                            <select class="form-control" name="compte" required>
                                                <option disabled selected></option>
                                                <option value="administrateur">Administrateur</option>
                                                <option value="moyens generaux">Moyens Generaux</option>
                                                <option value="normal">Normal</option>
                                            </select>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                            <br/>

                            <div style="text-align: center">
                                <input type="hidden" name="validation" value="<?php echo 'valider ' . $action; ?>">
                                <button class="btn btn-info" type="submit" name="valider" style="width: 150px">
                                    Valider
                                </button>
                            </div>
                        </form>
                <?php
                    } else {
                        echo "
                <div class='alert alert-info alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'></button>
                    <strong>Tous les employés de la base ont été déjà assignés un droit.</strong>
                </div>
                ";
                    }
                ?>


            </div>
        </div>

        <script>
            $('#fermer').click(function () {
                $('#ajout_user').remove();
            })
        </script>
    </div>

<?php elseif ($action == "modifier"): ?>

    <div class="col-md-7" id="modifier_user" style="margin-left: 20.83%">
        <div class="panel panel-default">
            <div class="panel-heading" style="font-size: 12px; font-weight: bolder">
                Modification de droits
                <a id='fermer' type='button'
                   class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </a>
            </div>
            <div class="panel-body">
                <form method="post" action="">
                    <table style="border-collapse: separate; border-spacing: 10px" border="0" width="100%">
                        <tr>
                            <td class="champlabel">Employé:</td>
                            <td>
                                <label>
                                    <select class="form-control" name="emp" required>
                                        <option disabled selected></option>
                                        <?php
                                            $sql = "SELECT code_emp, nom_emp, prenoms_emp FROM employes WHERE code_droit IS NOT NULL ORDER BY nom_emp ASC ";
                                            $res = mysqli_query($connexion, $sql) or exit(mysqli_error($connexion));
                                            while ($data = mysqli_fetch_array($res)) {
                                                echo '<option value="' . $data['code_emp'] . '" >' . $data['prenoms_emp'] . ' ' . $data['nom_emp'] . '</option>';
                                            }
                                        ?>
                                    </select>
                                </label>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td rowspan="2" style="padding-right: 0">
                                <img src="img/icons_1775b9/edit.png" height="80" width="80">
                            </td>
                        </tr>
                        <tr>
                            <td class="champlabel">Groupe:</td>
                            <td>
                                <label>
                                    <select class="form-control" name="compte" required>
                                        <option disabled selected></option>
                                        <option value="administrateur">Administrateur</option>
                                        <option value="moyens generaux">Moyens Generaux</option>
                                        <option value="normal">Normal</option>
                                    </select>
                                </label>
                            </td>
                        </tr>
                    </table>
                    <br/>

                    <div style="text-align: center">
                        <input type="hidden" name="validation" value="<?php echo 'valider ' . $action; ?>">
                        <button class="btn btn-info" type="submit" name="valider" style="width: 150px">
                            Valider
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            $('#fermer').click(function () {
                $('#modifier_user').remove();
            })
        </script>
    </div>

<?php elseif ($action == "liste"): ?>

    <div class="col-md-7" id="liste_user" style="margin-left: 20.83%">
        <div class="panel panel-default">
            <div class="panel-heading" style="font-size: 12px; font-weight: bolder">
                Liste des Utilisateurs
                <a id='fermer' type='button'
                   class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </a>
            </div>
            <div class="panel-body" style="overflow: auto">
                <table class="table table-hover table-bordered ">
                    <thead>
                    <tr>
                        <th class="entete" style="text-align: center">Matricule</th>
                        <th class="entete" style="text-align: center">Utilisateur</th>
                        <th class="entete" style="text-align: center">Groupe</th>
                    </tr>
                    </thead>
                    <?php
                        $sql = "SELECT e.code_emp, e.nom_emp, e.prenoms_emp, d.libelle_droit
                                FROM employes AS e
                                INNER JOIN droits AS d
                                ON e.code_droit = d.code_droit
                                WHERE e.code_droit IS NOT NULL ORDER BY e.code_emp ASC ";

                        if ($resultat = $connexion->query($sql)) {
                            if ($resultat->num_rows > 0) {
                                $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                                foreach ($ligne as $list) {
                                    ?>
                                    <tr>
                                        <td><?php echo stripslashes($list['code_emp']); ?></td>
                                        <td><?php echo stripslashes($list['prenoms_emp']) . ' ' . stripslashes($list['nom_emp']); ?></td>
                                        <td><?php echo ucfirst(stripslashes($list['libelle_droit'])); ?></td>
                                    </tr>
                                    <?php
                                }
                            } else { ?>
                                <tr>
                                    <th colspan="5" class="entete" style="text-align: center">
                                        <h5>Aucun utilisateur n'a été enregistré à ce jour</h5>
                                    </th>
                                </tr>
                            <?php }
                        }
                    ?>
                </table>
            </div>
        </div>

        <script>
            $('#fermer').click(function () {
                $('#liste_user').remove();
            })
        </script>
    </div>

<?php elseif ($action == "rechercher"): ?>

    <div class="col-md-7" id="rechercher_user" style="margin-left: 20.83%">
        <div class="panel panel-default">
            <div class="panel-heading" style="font-size: 12px; font-weight: bolder">
                Recherche
                <a id='fermer' type='button'
                   class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </a>
            </div>
            <div class="panel-body" style="overflow: auto">
                <table border="0" width="100%">
                    <tr>
                        <td style="text-align: center">
                            <img src="img/icons_1775b9/under_construction_100.png">
                            <h3 style="color: #0e76bc">En construction...</h3>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <script>
            $('#fermer').click(function () {
                $('#rechercher_user').remove();
            })
        </script>
    </div>

<?php endif; ?>
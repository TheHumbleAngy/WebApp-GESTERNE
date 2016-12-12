<?php
    /**
     * Created by PhpStorm.
     * User: ange-marius.kouakou
     * Date: 26/08/2015
     * Time: 15:10
     */
    error_reporting(E_ERROR);
    protected function configpath(&$ini) {
        return $ini = '../' . $ini;
    }

    if (sizeof($_POST) > 0) {
        if (isset($_POST['opt'])) {
            $option = $_POST['opt'];
            $element = $_POST['element'];

            switch ($option) {
                case 'matricule':
                    $sql = "SELECT * FROM employes WHERE code_emp LIKE '%" . $element . "%'";
                    break;
                case 'nom':
                    $sql = "SELECT * FROM employes WHERE nom_emp LIKE '%" . $element . "%'";
                    break;
                case 'prenoms':
                    $sql = "SELECT * FROM employes WHERE prenoms_emp LIKE '%" . $element . "%'";
                    break;
                case 'fonction':
                    $sql = "SELECT * FROM employes WHERE fonction_emp LIKE '%" . $element . "%'";
                    break;
                case 'departement':
                    $sql = "SELECT * FROM employes WHERE departement_emp LIKE '%" . $element . "%'";
                    break;
                case 'email':
                    $sql = "SELECT * FROM employes WHERE email_emp LIKE '%" . $element . "%'";
                    break;
                case 'tel':
                    $sql = "SELECT * FROM employes WHERE tel_emp LIKE '%" . $element . "%'";
                    break;
            }

            $res = mysqli_query($connexion, $sql) or exit(mysqli_error($connexion));
            
            if ($res->num_rows > 0) { ?>
                <!--suppress ALL -->
                <div style="margin-left: 1.5%; margin-right: 1.5%">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <img src="img/icons_1775b9/search.png" width="20" height="20">
                            Liste Employés - Résultats de recherche pour "<?php echo ucfirst($option); ?>" contenant "<?php echo $element; ?>"
                            <a href='form_principale.php?page=form_actions&source=employes&action=rechercher' type='button' class='close'
                               data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                <span aria-hidden='true'>&times;</span>
                            </a>
                        </div>
                        <div class="panel-body">
                            <table border="0" class="table table-hover table-bordered ">
                                <thead>
                                <tr>
                                    <th class="entete" style="text-align: center">Matricule</th>
                                    <th class="entete" style="text-align: center">Nom et Prénoms</th>
                                    <th class="entete" style="text-align: center">Fonction</th>
                                    <th class="entete" style="text-align: center">Département</th>
                                    <th class="entete" style="text-align: center">Contacts</th>
                                    <th class="entete" style="text-align: center; width: 13%">Actions</th>
                                </tr>
                                </thead>
                                <?php while ($data = mysqli_fetch_array($res)) { ?>
                                    <tr>
                                        <td><?php echo stripslashes($data['code_emp']); ?></td>
                                        <td><?php echo stripslashes($data['titre_emp']) . " " . stripslashes($data['nom_emp']) . " " . stripslashes($data['prenoms_emp']); ?></td>
                                        <td><?php echo stripslashes($data['fonction_emp']); ?></td>
                                        <td><?php echo stripslashes($data['departement_emp']); ?></td>
                                        <td><?php echo "E-mail: " . stripslashes($data['email_emp']) . "<br>Tel: " . stripslashes($data['tel_emp']); ?></td>
                                        <td>
                                            <div style="text-align: center">
                                                <a class="btn btn-default modifier" data-toggle="modal"
                                                   data-target="#modalModifier<?php echo stripslashes($data['code_emp']); ?>">
                                                    <img height="20" width="20" src="img/icons_1775b9/edit_user.png"
                                                         title="Modifier"/>
                                                </a>
                                                <a class="btn btn-default modifier" data-toggle="modal"
                                                   data-target="#modalSupprimer<?php echo stripslashes($data['code_emp']); ?>">
                                                    <img height="20" width="20" src="img/icons_1775b9/cancel.png"
                                                         title="Supprimer"/>
                                                </a>
                                            </div>

                                            <!-- Modal Mise à jour des infos -->
                                            <div class="modal fade"
                                                 id="modalModifier<?php echo stripslashes($data['code_emp']); ?>"
                                                 tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog update">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close"><span
                                                                    aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title"
                                                                id="modalModifier<?php echo stripslashes($data['code_emp']); ?>">
                                                                Modifications [<?php echo stripslashes($data['code_emp']); ?>]</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="formulaire"
                                                                   style="width: 90%; border-collapse: separate; border-spacing: 8px; margin-left: auto; margin-right: auto"
                                                                   border="0">
                                                                <tr>
                                                                    <td class="champlabel">Titre :</td>
                                                                    <td>
                                                                        <label>
                                                                            <select name="titre_emp" id="titre_emp<?php echo $data['code_emp']; ?>" class="form-control" required>
                                                                                <option disabled></option>
                                                                                <?php
                                                                                    $req = "SELECT DISTINCT titre_emp FROM employes ORDER BY titre_emp ASC ";
                                                                                    $result = mysqli_query($connexion, $req);
                                                                                    while ($data_grp = mysqli_fetch_array($result)) {
                                                                                        if ($data['titre_emp'] == $data_grp['titre_emp']) {
                                                                                            echo '<option value="' . $data_grp['titre_emp'] . '" selected >' . $data_grp['titre_emp'] . '</option>';
                                                                                        } else {
                                                                                            echo '<option value="' . $data_grp['titre_emp'] . '" >' . $data_grp['titre_emp'] . '</option>';
                                                                                        }
                                                                                    }
                                                                                ?>
                                                                            </select>
                                                                        </label>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="champlabel">*Nom :</td>
                                                                    <td>
                                                                        <label>
                                                                            <input type="text" class="form-control"
                                                                                   id="nom_emp<?php echo $data['code_emp']; ?>"
                                                                                   value="<?php echo $data['nom_emp']; ?>">
                                                                        </label>
                                                                    </td>
                                                                    <td class="champlabel">Prénoms :</td>
                                                                    <td>
                                                                        <label>
                                                                            <input type="text" class="form-control" size="30"
                                                                                   id="prenoms_emp<?php echo $data['code_emp']; ?>"
                                                                                   value="<?php echo $data['prenoms_emp']; ?>">
                                                                        </label>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="champlabel">Fonction :</td>
                                                                    <td>
                                                                        <label>
                                                                            <input type="text" class="form-control" size="25"
                                                                                   id="fonction_emp<?php echo $data['code_emp']; ?>"
                                                                                   value="<?php echo $data['fonction_emp']; ?>">
                                                                        </label>
                                                                    </td>
                                                                    <td class="champlabel">Département :</td>
                                                                    <td>
                                                                        <label>
                                                                            <select name="departement_emp" id="departement_emp<?php echo $data['code_emp']; ?>" required class="form-control">
                                                                                <option disabled></option>
                                                                                <?php
                                                                                    $req = "SELECT DISTINCT departement_emp FROM employes ORDER BY departement_emp ASC ";
                                                                                    $result = mysqli_query($connexion, $req);
                                                                                    while ($data_grp = mysqli_fetch_array($result)) {
                                                                                        if ($data['departement_emp'] == $data_grp['departement_emp']) {
                                                                                            echo '<option value="' . $data_grp['departement_emp'] . '" selected >' . $data_grp['departement_emp'] . '</option>';
                                                                                        } else {
                                                                                            echo '<option value="' . $data_grp['departement_emp'] . '" >' . $data_grp['departement_emp'] . '</option>';
                                                                                        }
                                                                                    }
                                                                                ?>
                                                                            </select>
                                                                        </label>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="champlabel">E-mail :</td>
                                                                    <td>
                                                                        <label>
                                                                            <input type="email" class="form-control" size="25"
                                                                                   id="email_emp<?php echo $data['code_emp']; ?>"
                                                                                   value="<?php echo $data['email_emp']; ?>">
                                                                        </label>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="champlabel">Contact :</td>
                                                                    <td>
                                                                        <label>
                                                                            <input type="tel" class="form-control"
                                                                                   id="tel_emp<?php echo $data['code_emp']; ?>"
                                                                                   value="<?php echo $data['tel_emp']; ?>">
                                                                        </label>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-default" data-dismiss="modal">Fermer
                                                            </button>
                                                            <button class="btn btn-primary" data-dismiss="modal"
                                                                    onclick="majInfos('<?php echo $data['code_emp']; ?>')">
                                                                Enregistrer
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal suppression des infos -->
                                            <div class="modal fade"
                                                 id="modalSupprimer<?php echo stripslashes($data['code_emp']); ?>"
                                                 tabindex="-1"
                                                 role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close"><span
                                                                    aria-hidden="true">&times;</span>
                                                            </button>
                                                            <h4 class="modal-title"
                                                                id="modalSupprimer<?php echo stripslashes($data['code_emp']); ?>">
                                                                Confirmation</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            Voulez-vous supprimer
                                                            l'employé <?php echo stripslashes($data['code_emp']); ?>" de la base ?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-default" data-dismiss="modal">Non
                                                            </button>
                                                            <button class="btn btn-primary" data-dismiss="modal"
                                                                    onclick="suppressionInfos('<?php echo stripslashes($data['code_emp']); ?>')">
                                                                Oui
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                } ?>
                            </table>
                        </div>
                    </div>
                </div>
            <?php } else {
                echo "
        <div style='width: 400px; margin-right: auto; margin-left: auto; padding-top: 20px'>
            <div class='alert alert-danger alert-dismissible' role='alert' >
                <a href='form_principale.php?page=form_actions&source=employes&action=rechercher' type='button' class='close'
                       data-dismiss='alert' aria-label='Close' style='position: inherit'>
                        <span aria-hidden='true'>&times;</span>
                    </a>
                <p style='font-size: small'>La recherche n'a renvoyé aucun résultat.</p>
            </div>
        </div>
        ";
            }
        }
    }
    else {
        $iniFile = 'config.ini';
        while (!$config = parse_ini_file($iniFile))
            configpath($iniFile);
        $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
        session_start();
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <img src="img/icons_1775b9/permanent_job.png" width="20" height="20">
                Liste des Employés
                <a href='form_principale.php?page=administration&source=employes' type='button' class='close'
                   data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </a>
            </div>
            <div class="panel-body">
                <table class="table table-hover table-bordered">
                    <thead>
                    <tr>
                        <th class="entete" style="text-align: center">Matricule</th>
                        <th class="entete" style="text-align: center">Nom et Prénoms</th>
                        <th class="entete" style="text-align: center">Fonction</th>
                        <th class="entete" style="text-align: center">Département</th>
                        <th class="entete" style="text-align: center">Contacts</th>
                        <th class="entete" style="text-align: center; width: 13%">Actions</th>
                    </tr>
                    </thead>
                    <?php
                        $req = "SELECT * FROM employes ORDER BY code_emp ASC ";
                        if ($resultat = $connexion->query($req)) {
                            $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                            foreach ($ligne as $list) {
                                ?>
                                <tr>
                                    <td><?php echo stripslashes($list['code_emp']); ?></td>
                                    <td><strong><?php echo stripslashes($list['titre_emp']) . " " . stripslashes($list['nom_emp']) . " " . stripslashes($list['prenoms_emp']); ?></strong></td>
                                    <td><?php echo stripslashes($list['fonction_emp']); ?></td>
                                    <td><?php echo ucfirst(stripslashes($list['departement_emp'])); ?></td>
                                    <td><?php echo "E-mail: " . stripslashes($list['email_emp']) . "<br>Tel: " . stripslashes($list['tel_emp']); ?></td>
                                    <td>
                                        <div style="text-align: center">
                                            <a class="btn btn-default modifier" data-toggle="modal" title="Modifier"
                                               data-target="#modalModifier<?php echo stripslashes($list['code_emp']); ?>">
                                                <img height="20" width="20" src="img/icons_1775b9/edit_user.png"/>
                                            </a>
                                            <a class="btn btn-default modifier" data-toggle="modal" title="Supprimer"
                                               data-target="#modalSupprimer<?php echo stripslashes($list['code_emp']); ?>">
                                                <img height="20" width="20" src="img/icons_1775b9/remove_user.png"/>
                                            </a>
                                        </div>

                                        <!-- Modal Mise à jour des infos -->
                                        <div class="modal fade"
                                             id="modalModifier<?php echo stripslashes($list['code_emp']); ?>"
                                             tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog update">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close"><span
                                                                aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title"
                                                            id="modalModifier<?php echo stripslashes($list['code_emp']); ?>">
                                                            Modifications [<?php echo stripslashes($list['code_emp']); ?>]</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="formulaire"
                                                               style="width: 90%; border-collapse: separate; border-spacing: 8px; margin-left: auto; margin-right: auto"
                                                               border="0">
                                                            <tr>
                                                                <td class="champlabel">*Titre :</td>
                                                                <td>
                                                                    <label>
                                                                        <select name="titre_emp" id="titre_emp<?php echo $list['code_emp']; ?>" class="form-control" required>
                                                                            <option disabled></option>
                                                                            <?php
                                                                                $req = "SELECT DISTINCT titre_emp FROM employes ORDER BY titre_emp ASC ";
                                                                                $result = mysqli_query($connexion, $req);
                                                                                while ($data_grp = mysqli_fetch_array($result)) {
                                                                                    if ($list['titre_emp'] == $data_grp['titre_emp']) {
                                                                                        echo '<option value="' . $data_grp['titre_emp'] . '" selected >' . $data_grp['titre_emp'] . '</option>';
                                                                                    } else {
                                                                                        echo '<option value="' . $data_grp['titre_emp'] . '" >' . $data_grp['titre_emp'] . '</option>';
                                                                                    }
                                                                                }
                                                                            ?>
                                                                        </select>
                                                                    </label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="champlabel">*Nom :</td>
                                                                <td>
                                                                    <label>
                                                                        <input type="text" class="form-control"
                                                                               onblur="this.value = this.value.toUpperCase();"
                                                                               id="nom_emp<?php echo $list['code_emp']; ?>"
                                                                               value="<?php echo $list['nom_emp']; ?>">
                                                                    </label>
                                                                </td>
                                                                <td class="champlabel">Prénoms :</td>
                                                                <td>
                                                                    <label>
                                                                        <input type="text" class="form-control" size="30"
                                                                               onblur="this.value = this.value.toUpperCase();"
                                                                               id="prenoms_emp<?php echo $list['code_emp']; ?>"
                                                                               value="<?php echo $list['prenoms_emp']; ?>">
                                                                    </label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="champlabel">Fonction :</td>
                                                                <td>
                                                                    <label>
                                                                        <label>
                                                                            <input type="text" class="form-control" size="25"
                                                                                   onblur="this.value = this.value.toUpperCase();"
                                                                                   id="fonction_emp<?php echo $list['code_emp']; ?>"
                                                                                   value="<?php echo $list['fonction_emp']; ?>">
                                                                        </label>
                                                                    </label>
                                                                </td>
                                                                <td class="champlabel">*Département :</td>
                                                                <td>
                                                                    <label>
                                                                        <select name="departement_emp" id="departement_emp<?php echo $list['code_emp']; ?>" required class="form-control">
                                                                            <option disabled></option>
                                                                            <?php
                                                                                $req = "SELECT DISTINCT departement_emp FROM employes ORDER BY departement_emp ASC ";
                                                                                $result = mysqli_query($connexion, $req);
                                                                                while ($data_grp = mysqli_fetch_array($result)) {
                                                                                    if ($list['departement_emp'] == $data_grp['departement_emp']) {
                                                                                        echo '<option value="' . $data_grp['departement_emp'] . '" selected >' . $data_grp['departement_emp'] . '</option>';
                                                                                    } else {
                                                                                        echo '<option value="' . $data_grp['departement_emp'] . '" >' . $data_grp['departement_emp'] . '</option>';
                                                                                    }
                                                                                }
                                                                            ?>
                                                                        </select>
                                                                    </label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="champlabel">*E-mail :</td>
                                                                <td>
                                                                    <label>
                                                                        <input type="email" class="form-control" size="25"
                                                                               id="email_emp<?php echo $list['code_emp']; ?>"
                                                                               value="<?php echo $list['email_emp']; ?>">
                                                                    </label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="champlabel">*Contact :</td>
                                                                <td>
                                                                    <label>
                                                                        <input type="tel" class="form-control"
                                                                               id="tel_emp<?php echo $list['code_emp']; ?>"
                                                                               value="<?php echo $list['tel_emp']; ?>">
                                                                    </label>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                        <button class="btn btn-primary" data-dismiss="modal"
                                                                onclick="majInfos('<?php echo $list['code_emp']; ?>')">
                                                            Enregistrer
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal suppression des infos -->
                                        <div class="modal fade"
                                             id="modalSupprimer<?php echo stripslashes($list['code_emp']); ?>"
                                             tabindex="-1"
                                             role="dialog">
                                            <div class="modal-dialog delete" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close"><span
                                                                aria-hidden="true">&times;</span>
                                                        </button>
                                                        <h4 class="modal-title"
                                                            id="modalSupprimer<?php echo stripslashes($list['code_emp']); ?>">
                                                            Confirmation</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Voulez-vous supprimer
                                                            l'employé "<?php echo stripslashes($list['nom_emp']) . " " . stripslashes($list['prenoms_emp']); ?>" de la base ?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-default" data-dismiss="modal">Non
                                                        </button>
                                                        <button class="btn btn-primary" data-dismiss="modal"
                                                                onclick="suppressionInfos('<?php echo stripslashes($list['code_emp']); ?>')">
                                                            Oui
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                    ?>
                </table>
            </div>
        </div>
    <?php
    }
?>
<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 30-Sep-15
     * Time: 4:36 PM
     *
     * Ce script génère la liste des articles où des opérations de modifications et suppressions peuvent
     * être faites sur chaque article
     */
    if (sizeof($_POST) > 0) {
        if (isset($_POST['opt'])) {
            $option = $_POST['opt'];
            $element = $_POST['element'];

            switch ($option) {
                case 'numero':
                    $sql = "SELECT * FROM articles WHERE code_art LIKE '%" . $element . "%'";
                    break;
                case 'designation':
                    $sql = "SELECT * FROM articles WHERE designation_art LIKE '%" . $element . "%'";
                    break;
                case 'description':
                    $sql = "SELECT * FROM articles WHERE description_art LIKE '%" . $element . "%'";
                    break;
                case 'stock':
                    $sql = "SELECT * FROM articles WHERE stock_art = $element";
                    break;
                case 'groupe':
                    $sql = "SELECT code_grp FROM groupe_articles WHERE designation_grp LIKE '%" . $element . "%'";
                    $res = mysqli_query($connexion, $sql) or exit(mysqli_error($connexion));
                    $grp = "";
                    while ($data = mysqli_fetch_array($res)) {
                        $grp = $data['code_grp'];
                    }
                    $sql = "SELECT * FROM articles WHERE code_grp = '" . $grp . "'";
                    break;
            }

            $res = mysqli_query($connexion, $sql) or exit(mysqli_error($connexion));

            if ($res->num_rows > 0) { ?>
                <!--suppress ALL -->
                <div id="info"></div>
                <div id="feedback">
                    <div style="margin-left: 1.5%; margin-right: 1.5%">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <img src="img/icons_1775b9/search.png" width="20" height="20">
                                Liste Articles - Résultats de recherche pour "<?php echo ucfirst($option); ?>" contenant
                                "<?php echo $element; ?>"
                                <a href='form_principale.php?page=form_actions&source=articles&action=rechercher'
                                   type='button' class='close' data-dismiss='alert' aria-label='Close'
                                   style='position: inherit'>
                                    <span aria-hidden='true'>&times;</span>
                                </a>
                            </div>
                            <div class="panel-body">
                                <table border="0" class="table table-hover table-bordered ">
                                    <thead>
                                    <tr>
                                        <th class="entete" style="text-align: center; width: 5%">Numéro</th>
                                        <th class="entete" style="text-align: center; width: 20%">Désignation</th>
                                        <th class="entete" style="text-align: center; width: 18%">Groupe</th>
                                        <th class="entete" style="text-align: center; width: 30%">Description</th>
                                        <th class="entete" style="text-align: center; width: 14%">Détails</th>
                                        <?php //if (($_SESSION['type_utilisateur'] == 'administrateur') || ($_SESSION['type_utilisateur'] == 'moyens_genereaux')): ?>
                                        <th class="entete" style="text-align: center; width: 13%">Actions</th>
                                        <?php //endif ?>
                                    </tr>
                                    </thead>
                                    <?php while ($data = mysqli_fetch_array($res)) { ?>
                                        <tr>
                                            <td><?php echo stripslashes($data['code_art']); ?></td>
                                            <td>
                                                <strong><?php echo ucfirst(stripslashes($data['designation_art'])); ?></strong>
                                            </td>
                                            <td>
                                                <?php
                                                    $code = $data['code_grp'];
                                                    $desig = "";
                                                    $req = "SELECT designation_grp FROM groupe_articles WHERE code_grp = '" . $code . "'";
                                                    $result = mysqli_query($connexion, $req);
                                                    while ($info = mysqli_fetch_array($result)) {
                                                        echo $desig = $info['designation_grp'];
                                                    }
                                                ?>
                                            </td>
                                            <td><?php echo stripslashes($data['description_art']); ?></td>
                                            <td><?php echo "<strong>Stock Actuel : " . stripslashes($data['stock_art']) . "</strong><br>Niveau Réappro. : " . stripslashes($data['niveau_reappro_art']) . "<br>Niveau Ciblé : " . stripslashes($data['niveau_cible_art']); ?></td>
                                            <td>
                                                <div style="text-align: center">
                                                    <a class="btn btn-default modifier" data-toggle="modal"
                                                       data-target="#modalModifier<?php echo stripslashes($data['code_art']); ?>">
                                                        <img height="20" width="20"
                                                             src="img/icons_1775b9/ball_point_pen_filled.png"
                                                             title="Modifier"/>
                                                    </a>
                                                    <a class="btn btn-default modifier" data-toggle="modal"
                                                       data-target="#modalSupprimer<?php echo stripslashes($data['code_art']); ?>">
                                                        <img height="20" width="20" src="img/icons_1775b9/cancel.png"
                                                             title="Supprimer"/>
                                                    </a>
                                                </div>

                                                <!-- Modal Mise à jour des infos -->
                                                <div class="modal fade"
                                                     id="modalModifier<?php echo stripslashes($data['code_art']); ?>"
                                                     tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog update">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                                <h4 class="modal-title"
                                                                    id="modalModifier<?php echo stripslashes($data['code_art']); ?>">
                                                                    Modifications
                                                                    [<?php echo stripslashes($data['code_art']); ?>]</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <table class="formulaire"
                                                                       style="width: 100%; border-collapse: separate; border-spacing: 8px; margin-left: auto; margin-right: auto"
                                                                       border="0">
                                                                    <tr>
                                                                        <td class="champlabel">*Désignation:</td>
                                                                        <td>
                                                                            <label>
                                                                                <input type="text" size="30"
                                                                                       required
                                                                                       id="designation_art<?php echo $data['code_art']; ?>"
                                                                                       value="<?php echo $data['designation_art']; ?>"
                                                                                       onblur="this.value = this.value.toUpperCase();"
                                                                                       class="form-control"/>
                                                                            </label>
                                                                        </td>
                                                                        <td class="champlabel">Stock :</td>
                                                                        <td>
                                                                            <label>
                                                                                <input type="number" size="2"
                                                                                       id="stock_art<?php echo $data['code_art']; ?>"
                                                                                       value="<?php echo $data['stock_art']; ?>"
                                                                                       class="form-control"/>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="champlabel">*Groupe :</td>
                                                                        <td>
                                                                            <label>
                                                                                <select class="form-control"
                                                                                        id="code_grp<?php echo $data['code_art']; ?>"
                                                                                        name="code_grp" required>
                                                                                    <option disabled
                                                                                            selected></option>
                                                                                    <?php
                                                                                        $str = "SELECT code_grp, designation_grp FROM groupe_articles ORDER BY designation_grp ASC ";
                                                                                        $resultat = mysqli_query($connexion, $str) or exit(mysqli_error($connexion));
                                                                                        while ($infos = mysqli_fetch_array($resultat)) {
                                                                                            if ($data['code_grp'] == $infos['code_grp']) {
                                                                                                echo '<option value="' . $infos['code_grp'] . '" selected >' . $infos['designation_grp'] . '</option>';
                                                                                            }
                                                                                            else {
                                                                                                echo '<option value="' . $infos['code_grp'] . '" >' . $infos['designation_grp'] . '</option>';
                                                                                            }
                                                                                        }
                                                                                    ?>
                                                                                </select>
                                                                            </label>
                                                                        </td>
                                                                        <td class="champlabel">Niveau Ciblé:</td>
                                                                        <td>
                                                                            <label>
                                                                                <input type="number" size="3"
                                                                                       min="0"
                                                                                       id="niveau_cible_art<?php echo $data['code_art']; ?>"
                                                                                       value="<?php echo $data['niveau_cible_art']; ?>"
                                                                                       class="form-control"/>
                                                                            </label>
                                                                        </td>
                                                                        <td class="champlabel">Niveau Réapp. :</td>
                                                                        <td>
                                                                            <label>
                                                                                <input type="number" size="3"
                                                                                       min="0"
                                                                                       id="niveau_reappro_art<?php echo $data['code_art']; ?>"
                                                                                       name="niveau_reappro_art"
                                                                                       value="<?php echo $data['niveau_reappro_art']; ?>"
                                                                                       class="form-control"/>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="champlabel">Description:</td>
                                                                        <td rowspan="1">
                                                                            <label>
                                                                            <textarea cols="30" rows="5"
                                                                                      id="description_art<?php echo $data['code_art']; ?>"
                                                                                      required style="resize: none"
                                                                                      class="form-control"><?php echo $data['description_art']; ?></textarea>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-default" data-dismiss="modal">
                                                                    Fermer
                                                                </button>
                                                                <button class="btn btn-primary" data-dismiss="modal"
                                                                        onclick="majInfos('<?php echo $data['code_art']; ?>')">
                                                                    Enregistrer
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Modal supprimer des infos -->
                                                <div class="modal fade"
                                                     id="modalSupprimer<?php echo stripslashes($data['code_art']); ?>"
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
                                                                    id="modalSupprimer<?php echo stripslashes($data['code_art']); ?>">
                                                                    Confirmation</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                Voulez-vous supprimer
                                                                l'article <?php echo stripslashes($data['code_art']); ?>
                                                                ?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-default" data-dismiss="modal">Non
                                                                </button>
                                                                <button class="btn btn-primary" data-dismiss="modal"
                                                                        onclick="suppressionInfos('<?php echo stripslashes($data['code_art']); ?>')">
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
                </div>
            <?php }
            else {
                header("refresh: 3;url=form_principale.php?page=form_actions&source=articles&action=rechercher");
                echo "
            <div class='alert alert-info alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                La recherche n'a renvoyé aucun résultat.
            </div>
            ";
            }
        }
    }

    else if (isset($_GET['statut'])) {

        if ($_GET['statut'] == "tous") {

            //TODO: Les 2 lignes ci-dessous ont été ajoutées pour palier au problème de redirection du fichier config.ini depuis le fichier fonctions.php
            if (!$config = parse_ini_file('../../config.ini')) $config = parse_ini_file('../../config.ini');
            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
            ?>

            <!--suppress ALL -->
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <img src="img/icons_1775b9/box_filled.png" width="20" height="20">
                        Liste des Articles
                        <a href='form_principale.php?page=accueil' type='button'
                           class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                            <span aria-hidden='true'>&times;</span>
                        </a>
                    </div>
                    <div class="panel-body">
                        <div class="">
                            <table border="0" class="table table-hover table-bordered ">
                                <thead>
                                <tr>
                                    <th class="entete" style="text-align: center; width: 5%">Numéro</th>
                                    <th class="entete" style="text-align: center; width: 20%">Désignation</th>
                                    <th class="entete" style="text-align: center; width: 18%">Groupe</th>
                                    <th class="entete" style="text-align: center; width: 30%">Description</th>
                                    <th class="entete" style="text-align: center; width: 14%">Détails</th>
                                    <?php //if (($_SESSION['type_utilisateur'] == 'administrateur') || ($_SESSION['type_utilisateur'] == 'moyens_genereaux')): ?>
                                    <th class="entete" style="text-align: center; width: 13%">Actions</th>
                                    <?php //endif ?>
                                </tr>
                                </thead>
                                <?php
                                    $sql = "SELECT * FROM articles ORDER BY code_art ASC";
                                    if ($resultat = $connexion->query($sql)) {
                                        if ($resultat->num_rows > 0) {
                                            $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                                            foreach ($ligne as $list) {
                                                ?>
                                                <tr>
                                                    <td><?php echo stripslashes($list['code_art']); ?></td>
                                                    <td><strong><?php echo ucfirst(stripslashes($list['designation_art'])); ?></strong></td>
                                                    <td>
                                                        <?php
                                                            $code = $list['code_grp'];
                                                            $desig = "";
                                                            $sql = "SELECT designation_grp FROM groupe_articles WHERE code_grp = '" . $code . "'";
                                                            $res = mysqli_query($connexion, $sql) or exit(mysqli_error($connexion));
                                                            while ($info = mysqli_fetch_array($res)) {
                                                                echo $desig = $info['designation_grp'];
                                                            }
                                                        ?>
                                                    </td>
                                                    <td><?php echo ucfirst(stripslashes($list['description_art'])); ?></td>
                                                    <td><?php echo "<strong>Stock Actuel : <span class='label label-primary'>" . stripslashes($list['stock_art']) . "</span></strong><br>Niv. Réappro. : " . stripslashes($list['niveau_reappro_art']) . "<br>Niv. Ciblé : " . stripslashes($list['niveau_cible_art']); ?></td>
                                                    <?php //if (($_SESSION['type_utilisateur'] == 'administrateur') || ($_SESSION['type_utilisateur'] == 'moyens_genereaux')): ?>
                                                    <td>
                                                        <div style="text-align: center">
                                                            <a class="btn btn-default modifier" data-toggle="modal"
                                                               data-target="#modalModifier<?php echo stripslashes($list['code_art']); ?>">
                                                                <img height="20" width="20" src="img/icons_1775b9/ball_point_pen_filled.png"
                                                                     title="Modifier"/>
                                                            </a>
                                                            <a class="btn btn-default modifier" data-toggle="modal"
                                                               data-target="#modalSupprimer<?php echo stripslashes($list['code_art']); ?>">
                                                                <img height="20" width="20" src="img/icons_1775b9/cancel.png"
                                                                     title="Supprimer"/>
                                                            </a>
                                                        </div>

                                                        <!-- Modal Mise à jour des infos -->
                                                        <div class="modal fade"
                                                             id="modalModifier<?php echo stripslashes($list['code_art']); ?>"
                                                             tabindex="-1" role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog update">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal"
                                                                                aria-label="Close"><span
                                                                                aria-hidden="true">&times;</span>
                                                                        </button>
                                                                        <h4 class="modal-title"
                                                                            id="modalModifier<?php echo stripslashes($list['code_art']); ?>">
                                                                            Modifications
                                                                            [<?php echo stripslashes($list['code_art']); ?>]</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <table class="formulaire"
                                                                               style="width: 100%; border-collapse: separate; border-spacing: 8px; margin-left: auto; margin-right: auto"
                                                                               border="0">
                                                                            <tr>
                                                                                <td class="champlabel">*Désignation:</td>
                                                                                <td>
                                                                                    <label>
                                                                                        <input type="text" size="30" required
                                                                                               id="designation_art<?php echo $list['code_art']; ?>"
                                                                                               value="<?php echo $list['designation_art']; ?>"
                                                                                               onblur="this.value = this.value.toUpperCase();"
                                                                                               class="form-control"/>
                                                                                    </label>
                                                                                </td>
                                                                                <td class="champlabel">Stock :</td>
                                                                                <td>
                                                                                    <label>
                                                                                        <input type="number" size="2" min="0"
                                                                                               id="stock_art<?php echo $list['code_art']; ?>"
                                                                                               value="<?php echo $list['stock_art']; ?>"
                                                                                               class="form-control"/>
                                                                                    </label>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="champlabel">*Groupe :</td>
                                                                                <td>
                                                                                    <label>
                                                                                        <select class="form-control"
                                                                                                id="code_grp<?php echo $list['code_art']; ?>"
                                                                                                name="code_grp" required>
                                                                                            <option disabled selected></option>
                                                                                            <?php
                                                                                                $sql = "SELECT code_grp, designation_grp FROM groupe_articles ORDER BY designation_grp ASC ";
                                                                                                $res = mysqli_query($connexion, $sql) or exit(mysqli_error($connexion));
                                                                                                while ($data = mysqli_fetch_array($res)) {
                                                                                                    if ($list['code_grp'] == $data['code_grp']) {
                                                                                                        echo '<option value="' . $data['code_grp'] . '" selected >' . $data['designation_grp'] . '</option>';
                                                                                                    } else {
                                                                                                        echo '<option value="' . $data['code_grp'] . '" >' . $data['designation_grp'] . '</option>';
                                                                                                    }
                                                                                                    echo '<option value="' . $data['code_grp'] . '" >' . $data['designation_grp'] . '</option>';
                                                                                                }
                                                                                            ?>
                                                                                        </select>
                                                                                    </label>
                                                                                </td>
                                                                                <td class="champlabel">Niveau Ciblé:</td>
                                                                                <td>
                                                                                    <label>
                                                                                        <input type="number" size="3" min="0"
                                                                                               id="niveau_cible_art<?php echo $list['code_art']; ?>"
                                                                                               value="<?php echo $list['niveau_cible_art']; ?>"
                                                                                               class="form-control"/>
                                                                                    </label>
                                                                                </td>
                                                                                <td class="champlabel">Niveau Réapp. :</td>
                                                                                <td>
                                                                                    <label>
                                                                                        <input type="number" size="3" min="0"
                                                                                               id="niveau_reappro_art<?php echo $list['code_art']; ?>"
                                                                                               name="niveau_reappro_art"
                                                                                               value="<?php echo $list['niveau_reappro_art']; ?>"
                                                                                               class="form-control"/>
                                                                                    </label>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="champlabel">Description:</td>
                                                                                <td rowspan="1">
                                                                                    <label>
                                                                                <textarea cols="30" rows="5"
                                                                                          id="description_art<?php echo $list['code_art']; ?>"
                                                                                          required style="resize: none"
                                                                                          class="form-control"><?php echo $list['description_art']; ?></textarea>
                                                                                    </label>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button class="btn btn-default" data-dismiss="modal">Fermer
                                                                        </button>
                                                                        <button class="btn btn-primary" data-dismiss="modal"
                                                                                onclick="majInfos('<?php echo $list['code_art']; ?>')">
                                                                            Enregistrer
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Modal supprimer des infos -->
                                                        <div class="modal fade"
                                                             id="modalSupprimer<?php echo stripslashes($list['code_art']); ?>"
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
                                                                            id="modalSupprimer<?php echo stripslashes($list['code_art']); ?>">
                                                                            Confirmation de Suppression</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Voulez-vous supprimer
                                                                        l'article "<?php echo stripslashes($list['designation_art']); ?>" de la base ?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button class="btn btn-default" data-dismiss="modal">Non
                                                                        </button>
                                                                        <button class="btn btn-primary" data-dismiss="modal"
                                                                                onclick="suppressionInfos('<?php echo stripslashes($list['code_art']); ?>')">
                                                                            Oui
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <?php //endif ?>
                                                </tr>
                                                <?php
                                            }
                                        } else { ?>
                                            <tr>
                                                <th colspan="6" class="entete" style="text-align: center">
                                                    <h5>Aucun article n'a été enregistré à ce jour</h5>
                                                </th>
                                            </tr>
                                        <?php }
                                    }
                                ?>
                            </table>
                        </div>
                        <!--<a href="biens_services/impression_biens_ou_services.php" target="_blank"
                           title="Télécharger">
                            <img src="img/File_Pdf.png" width="40px" height="40px" alt="File" title="Télécharger"/><br/>

                            <p>Imprimer</p>
                        </a>-->
                    </div>
                </div>
            </div>
            <?php
        }
        else if ($_GET['statut'] == "rupture") {

            //TODO: Les 2 lignes ci-dessous ont été ajoutées pour palier au problème de redirection du fichier config.ini depuis le fichier fonctions.php
            if (!$config = parse_ini_file('../../config.ini')) $config = parse_ini_file('../../config.ini');
            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
            ?>

            <!--suppress ALL -->
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <img src="img/icons_1775b9/empty_box_2.png" width="20" height="20">
                        Liste des Articles en Rupture de Stock
                        <a href='form_principale.php?page=accueil' type='button'
                           class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                            <span aria-hidden='true'>&times;</span>
                        </a>
                    </div>
                    <div class="panel-body">
                        <div class="">
                            <table border="0" class="table table-hover table-bordered ">
                                <thead>
                                <tr>
                                    <th class="entete" style="text-align: center; width: 5%">Numéro</th>
                                    <th class="entete" style="text-align: center; width: 30%">Désignation</th>
                                    <th class="entete" style="text-align: center; width: 18%">Groupe</th>
                                    <th class="entete" style="text-align: center; width: 20%">Description</th>
                                    <th class="entete" style="text-align: center; width: 14%">Détails</th>
                                </tr>
                                </thead>
                                <?php
                                    $sql = "SELECT * FROM articles WHERE stock_art = 0 ORDER BY code_art ASC";
                                    if ($resultat = $connexion->query($sql)) {
                                        if ($resultat->num_rows > 0) {
                                            $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                                            foreach ($ligne as $list) {
                                                ?>
                                                <tr>
                                                    <td><?php echo stripslashes($list['code_art']); ?></td>
                                                    <td><strong><?php echo ucfirst(stripslashes($list['designation_art'])); ?></strong></td>
                                                    <td>
                                                        <?php
                                                            $code = $list['code_grp'];
                                                            $desig = "";
                                                            $sql = "SELECT designation_grp FROM groupe_articles WHERE code_grp = '" . $code . "'";
                                                            $res = mysqli_query($connexion, $sql) or exit(mysqli_error($connexion));
                                                            while ($info = mysqli_fetch_array($res)) {
                                                                echo $desig = $info['designation_grp'];
                                                            }
                                                        ?>
                                                    </td>
                                                    <td><?php echo ucfirst(stripslashes($list['description_art'])); ?></td>
                                                    <td><?php echo "<strong>Stock Actuel : <span class='label label-primary'>" . stripslashes($list['stock_art']) . "</span></strong><br>Niv. Réappro. : " . stripslashes($list['niveau_reappro_art']) . "<br>Niv. Ciblé : " . stripslashes($list['niveau_cible_art']); ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else { ?>
                                            <tr>
                                                <th colspan="6" class="entete" style="text-align: center">
                                                    <h5>Aucun article n'a été enregistré à ce jour</h5>
                                                </th>
                                            </tr>
                                        <?php }
                                    }
                                ?>
                            </table>
                        </div>
                        <!--<a href="biens_services/impression_biens_ou_services.php" target="_blank"
                           title="Télécharger">
                            <img src="img/File_Pdf.png" width="40px" height="40px" alt="File" title="Télécharger"/><br/>

                            <p>Imprimer</p>
                        </a>-->
                    </div>
                </div>
            </div>
            <?php
        }
    }
?>
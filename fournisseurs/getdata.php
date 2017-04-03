<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 29-Aug-15
     * Time: 8:13 PM
     */
    if (sizeof($_POST) > 0) {
        if (isset($_POST['opt'])) {
            $option = $_POST['opt'];
            $element = $_POST['element'];

            switch ($option) {
                case 'matricule': $sql = "SELECT * FROM fournisseurs WHERE code_four LIKE '%" . $element . "%'";
                    break;
                case 'nom': $sql = "SELECT * FROM fournisseurs WHERE nom_four LIKE '%" . $element . "%'";
                    break;
                case 'adresse': $sql = "SELECT * FROM fournisseurs WHERE adresse_four LIKE '%" . $element . "%'";
                    break;
                case 'activite': $sql = "SELECT * FROM fournisseurs WHERE activite_four LIKE '%" . $element . "%'";
                    break;
                case 'fax': $sql = "SELECT * FROM fournisseurs WHERE fax_four LIKE '%" . $element . "%'";
                    break;
                case 'email': $sql = "SELECT * FROM fournisseurs WHERE email_four LIKE '%" . $element . "%'";
                    break;
                case 'tel': $sql = "SELECT * FROM fournisseurs WHERE telephonepro_four LIKE '%" . $element . "%'";
                    break;
            }

            $res = mysqli_query($connexion, $sql) or exit(mysqli_error($connexion));

            if ($res->num_rows > 0) { ?>
                <!--suppress ALL -->
                <div style="margin-left: 1.5%; margin-right: 1.5%">
                <div class="panel panel-default">
                <div class="panel-heading">
                    <img src="img/icons_1775b9/search.png" width="20" height="20">
                    Liste Fournisseurs - Résultats de recherche pour "<?php echo ucfirst($option); ?>" contenant "<?php echo $element; ?>"
                    <a href='form_principale.php?page=form_actions&source=fournisseurs&action=rechercher' type='button' class='close'
                       data-dismiss='alert' aria-label='Close' style='position: inherit'>
                        <span aria-hidden='true'>&times;</span>
                    </a>
                </div>
                <div class="panel-body">
                <table border="0" class="table table-hover table-bordered ">
                <thead>
                <tr>
                    <th class="entete" style="text-align: center; width: 10%">Raison Sociale</th>
                    <th class="entete" style="text-align: center; width: 10%">Contacts</th>
                    <th class="entete" style="text-align: center; width: 10%">Adresse</th>
                    <th class="entete" style="text-align: center; width: 10%">Activité</th>
                    <th class="entete" style="text-align: center; width: 10%">Notes</th>
                    <?php //if (($_SESSION['type_utilisateur'] == 'administrateur') || ($_SESSION['type_utilisateur'] == 'moyens_genereaux')):?>
                    <th class="entete" style="width: 10%; text-align: center">Actions</th>
                    <?php //endif?>
                </tr>
                </thead>
                <?php
                //$req = "SELECT * FROM fournisseurs ORDER BY code_four ASC ";
                if ($resultat = $connexion->query($sql)) {
                    $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                    foreach ($ligne as $list) {
                        ?>
                        <tr>
                            <td><strong><?php echo stripslashes($list['nom_four']); ?></strong></td>
                            <td><?php echo "Tel: " . stripslashes($list['telephonepro_four']) . "<br>Fax: " . stripslashes($list['fax_four']) . "<br>E-mail: " . stripslashes($list['email_four']); ?></td>
                            <!--                                            <td>-->
                            <?php //echo stripslashes($list['fax_four']); ?><!--</td>-->
                            <!--                                            <td>-->
                            <?php //echo stripslashes($list['email_four']); ?><!--</td>-->
                            <td><?php echo stripslashes($list['adresse_four']); ?></td>
                            <td><?php echo stripslashes($list['activite_four']); ?></td>
                            <td><?php echo stripslashes($list['notes_four']); ?></td>
                            <?php //if (($_SESSION['type_utilisateur'] == 'administrateur') || ($_SESSION['type_utilisateur'] == 'moyens_genereaux')):?>
                            <td>
                                <div style="text-align: center">
                                    <a class="btn btn-default modifier" data-toggle="modal"
                                       data-target="#modalModifier<?php echo stripslashes($list['code_four']); ?>">
                                        <img height="20" width="20" src="img/icons_1775b9/ball_point_pen_filled.png"
                                             title="Modifier"/>
                                    </a>
                                    <a class="btn btn-default modifier" data-toggle="modal"
                                       data-target="#modalSupprimer<?php echo stripslashes($list['code_four']); ?>">
                                        <img height="20" width="20" src="img/icons_1775b9/cancel.png" title="Supprimer"/>
                                    </a>
                                </div>

                                <!-- Modal -->
                                <div class="modal fade"
                                     id="modalModifier<?php echo stripslashes($list['code_four']); ?>"
                                     tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close"><span
                                                        aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title"
                                                    id="modalModifier<?php echo stripslashes($list['code_four']); ?>">
                                                    Modifications</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form>
                                                    <table>
                                                        <tr>
                                                            <td>Nom :</td>
                                                            <td>
                                                                <label>
                                                                    <input type="text" size="60" class="form-control"
                                                                           id="nom_four<?php echo $list['code_four']; ?>"
                                                                           value="<?php echo $list['nom_four']; ?>">
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>E-mail :</td>
                                                            <td>
                                                                <label>
                                                                    <input type="email" size="60" class="form-control"
                                                                           id="email_four<?php echo $list['code_four']; ?>"
                                                                           value="<?php echo $list['email_four']; ?>">
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Contact Pro. :</td>
                                                            <td>
                                                                <label>
                                                                    <input type="tel" class="form-control"
                                                                           id="telephonepro_four<?php echo $list['code_four']; ?>"
                                                                           value="<?php echo $list['telephonepro_four']; ?>">
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Acitivit� :</td>
                                                            <td>
                                                                <label>
                                                                    <input type="text" size="60" class="form-control"
                                                                           id="activite_four<?php echo $list['code_four']; ?>"
                                                                           value="<?php echo $list['activite_four']; ?>">
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Fax :</td>
                                                            <td>
                                                                <label>
                                                                    <input type="tel" size="60" class="form-control"
                                                                           id="fax_four<?php echo $list['code_four']; ?>"
                                                                           value="<?php echo $list['fax_four']; ?>">
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Addresse :</td>
                                                            <td>
                                                                <label>
                                                                    <input type="text" size="60" class="form-control"
                                                                           id="adresse_four<?php echo $list['code_four']; ?>"
                                                                           value="<?php echo $list['adresse_four']; ?>">
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Notes :</td>
                                                            <td>
                                                                <label>
                                                                    <input type="text" size="60" class="form-control"
                                                                           id="notes_four<?php echo $list['code_four']; ?>"
                                                                           value="<?php echo $list['notes_four']; ?>">
                                                                </label>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                <button class="btn btn-primary" data-dismiss="modal"
                                                        onclick="majInfos('<?php echo $list['code_four']; ?>')">
                                                    Enregistrer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal -->
                                <div class="modal fade"
                                     id="modalSupprimer<?php echo stripslashes($list['code_four']); ?>"
                                     tabindex="-1"
                                     role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close"><span aria-hidden="true">&times;</span>
                                                </button>
                                                <h4 class="modal-title"
                                                    id="modalSupprimer<?php echo stripslashes($list['code_four']); ?>">
                                                    Confirmation</h4>
                                            </div>
                                            <div class="modal-body">
                                                Voulez-vous supprimer
                                                le fournisseur <?php echo stripslashes($list['nom_four']); ?>" de la base ?
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-default" data-dismiss="modal">Non</button>
                                                <button class="btn btn-primary" data-dismiss="modal"
                                                        onclick="suppressionInfos('<?php echo stripslashes($list['code_four']); ?>')">
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
                    ?>
                    </table>
                    </div>
                    </div>
                    </div>
                <?php } else echo "La recherche n'a renvoye aucun resultat.";
            } else echo "
                <div style='width: 400px; margin-right: auto; margin-left: auto'>
                    <div class='alert alert-info alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                        <a href='form_principale.php?page=form_actions&source=fournisseurs&action=rechercher' type='button' class='close'
                               data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                <span aria-hidden='true'>&times;</span>
                            </a>
                        <strong>Desole!</strong><br/> La recherche n'a retourné aucun resultat.
                    </div>
                </div>
                ";
        }
    } else {
    //TODO: Les 2 lignes ci-dessous ont été ajoutées pour palier au problème de redirection du fichier config.ini depuis le fichier fonctions.php
    if (!$config = parse_ini_file('../../../config.ini')) $config = parse_ini_file('../../config.ini');
    $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
?>
<!--suppress ALL -->
<div class="panel panel-default">
    <div class="panel-heading">
        <img src="img/icons_1775b9/bon.png" width="20" height="20">
        Liste des Fournisseurs
        <a href='form_principale.php?page=administration&source=fournisseurs' type='button'
           class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
            <span aria-hidden='true'>&times;</span>
        </a>
    </div>
    <div class="panel-body">
        <table class="table table-hover table-bordered ">
            <thead>
            <tr>
                <th class="entete" style="text-align: center; width: 10%">Raison Sociale</th>
                <th class="entete" style="text-align: center; width: 10%">Contacts</th>
                <th class="entete" style="text-align: center; width: 10%">Adresse</th>
                <th class="entete" style="text-align: center; width: 10%">Activité</th>
                <th class="entete" style="text-align: center; width: 10%">Notes</th>
                <?php //if (($_SESSION['type_utilisateur'] == 'administrateur') || ($_SESSION['type_utilisateur'] == 'moyens_genereaux')):?>
                <th class="entete" style="width: 10%; text-align: center">Actions</th>
                <?php //endif?>
            </tr>
            </thead>
            <?php
                $req = "SELECT * FROM fournisseurs ORDER BY code_four ASC ";
                if ($resultat = $connexion->query($req)) {
                    if ($resultat->num_rows > 0) {
                        $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                        foreach ($ligne as $list) {
                            ?>
                            <tr>
                                <td><strong><?php echo stripslashes($list['nom_four']); ?></strong></td>
                                <td><?php echo "Tel: " . stripslashes($list['telephonepro_four']) . "<br>Fax: " . stripslashes($list['fax_four']) . "<br>E-mail: " . stripslashes($list['email_four']); ?></td>
                                <td><?php echo stripslashes($list['adresse_four']); ?></td>
                                <td><?php echo stripslashes($list['activite_four']); ?></td>
                                <td><?php echo stripslashes($list['notes_four']); ?></td>
                                <?php //if (($_SESSION['type_utilisateur'] == 'administrateur') || ($_SESSION['type_utilisateur'] == 'moyens_genereaux')):?>
                                <td>
                                    <div style="text-align: center">
                                        <a class="btn btn-default modifier" data-toggle="modal"
                                           data-target="#modalModifier<?php echo stripslashes($list['code_four']); ?>">
                                            <img height="20" width="20" src="img/icons_1775b9/ball_point_pen_filled.png"
                                                 title="Modifier"/>
                                        </a>
                                        <a class="btn btn-default modifier" data-toggle="modal"
                                           data-target="#modalSupprimer<?php echo stripslashes($list['code_four']); ?>">
                                            <img height="20" width="20" src="img/icons_1775b9/cancel.png" title="Supprimer"/>
                                        </a>
                                    </div>

                                    <!-- Modal Mise à jour des infos -->
                                    <div class="modal fade"
                                         id="modalModifier<?php echo stripslashes($list['code_four']); ?>"
                                         tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog update">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close"><span
                                                            aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title"
                                                        id="modalModifier<?php echo stripslashes($list['code_four']); ?>">
                                                        Modifications [<?php echo stripslashes($list['code_four']); ?>
                                                        ]</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <table class="formulaire"
                                                           style="width: 100%; border-collapse: separate; border-spacing: 8px"
                                                           border="0">
                                                        <tr>
                                                            <td class="champlabel">Raison Sociale :</td>
                                                            <td>
                                                                <label>
                                                                    <input type="text" class="form-control"
                                                                           onblur="this.value = this.value.toUpperCase();"
                                                                           id="nom_four<?php echo $list['code_four']; ?>"
                                                                           value="<?php echo $list['nom_four']; ?>">
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="champlabel">E-mail :</td>
                                                            <td>
                                                                <label>
                                                                    <input type="email" class="form-control" size="30"
                                                                           id="email_four<?php echo $list['code_four']; ?>"
                                                                           value="<?php echo $list['email_four']; ?>">
                                                                </label>
                                                            </td>
                                                            <td class="champlabel">Contact Pro. :</td>
                                                            <td>
                                                                <label>
                                                                    <input type="tel" class="form-control"
                                                                           id="telephonepro_four<?php echo $list['code_four']; ?>"
                                                                           value="<?php echo $list['telephonepro_four']; ?>">
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="champlabel">Activité :</td>
                                                            <td>
                                                                <label>
                                                                    <input type="text" class="form-control" size="30"
                                                                           id="activite_four<?php echo $list['code_four']; ?>"
                                                                           value="<?php echo $list['activite_four']; ?>">
                                                                </label>
                                                            </td>
                                                            <td class="champlabel">Fax :</td>
                                                            <td>
                                                                <label>
                                                                    <input type="tel" class="form-control"
                                                                           id="fax_four<?php echo $list['code_four']; ?>"
                                                                           value="<?php echo $list['fax_four']; ?>">
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="champlabel">Addresse :</td>
                                                            <td>
                                                                <label>
                                                                        <textarea name="adresse_four" rows="4" cols="25"
                                                                                  id="adresse_four<?php echo $list['code_four']; ?>"
                                                                                  style="resize: none" required
                                                                                  class="form-control"><?php echo $list['adresse_four']; ?></textarea>
                                                                </label>
                                                            </td>
                                                            <td class="champlabel">Notes :</td>
                                                            <td>
                                                                <label>
                                                                        <textarea name="notes_four" rows="4" cols="25"
                                                                                  id="notes_four<?php echo $list['code_four']; ?>"
                                                                                  style="resize: none"
                                                                                  class="form-control"><?php echo $list['notes_four']; ?></textarea>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                    <button class="btn btn-primary" data-dismiss="modal"
                                                            onclick="majInfos('<?php echo $list['code_four']; ?>')">
                                                        Enregistrer
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal -->
                                    <div class="modal fade"
                                         id="modalSupprimer<?php echo stripslashes($list['code_four']); ?>"
                                         tabindex="-1"
                                         role="dialog">
                                        <div class="modal-dialog delete" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close"><span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <h4 class="modal-title"
                                                        id="modalSupprimer<?php echo stripslashes($list['code_four']); ?>">
                                                        Confirmation</h4>
                                                </div>
                                                <div class="modal-body">
                                                    Voulez-vous supprimer
                                                    le fournisseur "<?php echo stripslashes($list['nom_four']); ?>" de la
                                                    base ?
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-default" data-dismiss="modal">Non</button>
                                                    <button class="btn btn-primary" data-dismiss="modal"
                                                            onclick="suppressionInfos('<?php echo stripslashes($list['code_four']); ?>')">
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
                    } else { ?>
                        <tr>
                            <th colspan="6" class="entete" style="text-align: center">
                                <h5>Aucun fournisseur n'a été enregistré à ce jour</h5>
                            </th>
                        </tr>
                    <?php }
                }
            ?>
        </table>
    </div>
</div>
        <?php
    }
?>
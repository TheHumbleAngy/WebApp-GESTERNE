<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 29/08/2016
     * Time: 18:35
     */
    if (sizeof($_POST) > 0) {
        if (isset($_POST['opt'])) {
            $option = $_POST['opt'];
            $element = $_POST['element'];

            switch ($option) {
                case 'numero': $sql = "SELECT * FROM proformas WHERE ref_fp LIKE '%" . $element . "%'";
                    break;
                case 'date_eta': $sql = "SELECT * FROM proformas WHERE dateetablissement_fp LIKE '%" . $element . "%'";
                    break;
                case 'date_rcp': $sql = "SELECT * FROM proformas WHERE datereception_fp LIKE '%" . $element . "%'";
                    break;
                case 'notes': $sql = "SELECT * FROM proformas WHERE notes_fp LIKE '%" . $element . "%'";
                    break;
            }

            $res = mysqli_query($connexion, $sql) or exit(mysqli_error($connexion));

            if ($res->num_rows > 0) { ?>
                <!--suppress ALL-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Liste des Factures Proformas - Résultats de recherche pour "<?php echo ucfirst($option); ?>" contenant "<?php echo $element; ?>"
                                <a href='form_principale.php?page=form_actions&source=proformas&action=rechercher' type='button' class='close'
                                   data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                    <span aria-hidden='true'>&times;</span>
                                </a>
                            </div>
                            <div class="panel-body">
                                <table border="0" class="table table-hover table-bordered ">
                                    <thead>
                                    <tr>
                                        <th class="entete" style="text-align: center; width: 10%">Numéro</th>
                                        <th class="entete" style="text-align: center; width: 30%">Fournisseur</th>
                                        <th class="entete" style="text-align: center; width: 10%">Date d'Etablissement</th>
                                        <th class="entete" style="text-align: center; width: 10%">Date de Reception</th>
                                        <th class="entete" style="text-align: center">Notes</th>
                                        <th class="entete" style="text-align: center; width: 8%">Action</th>
                                    </tr>
                                    </thead>
                                    <?php
                                        if ($resultat = $connexion->query($sql)) {
                                            $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                                            foreach ($ligne as $list) {
                                                ?>
                                                <tr>
                                                    <td><a class="btn btn-default" href="form_principale.php?page=proformas/form_proformas&action=consultation&id=<?php echo stripslashes($list['ref_fp']); ?>" role="button"><?php echo stripslashes($list['ref_fp']); ?></a></td>
                                                    <td>
                                                        <?php
                                                            $req = "SELECT nom_four FROM fournisseurs WHERE code_four = '" . stripslashes($list['code_four']) . "'";
                                                            if ($result = $connexion->query($req)) {
                                                                $rows = $result->fetch_all(MYSQLI_ASSOC);
                                                                foreach ($rows as $row) {
                                                                    echo stripslashes($row['nom_four']);
                                                                }
                                                            }
                                                        ?>
                                                    </td>
                                                    <td><?php echo rev_date($list['dateetablissement_fp'])?></td>
                                                    <td><?php echo rev_date($list['datereception_fp']); ?></td>
                                                    <td><?php echo stripslashes($list['notes_fp']); ?></td>
                                                    <?php //if (($_SESSION['type_utilisateur'] == 'administrateur') || ($_SESSION['type_utilisateur'] == 'moyens_genereaux')):?>
                                                    <td style="text-align: center">
                                                        <a class="btn btn-default" data-toggle="modal"
                                                           data-target="#modalSupprimer<?php echo stripslashes($list['ref_fp']); ?>">
                                                            <img height="20" width="20" src="img/icons_1775b9/cancel.png" title="Supprimer"/>
                                                        </a>
                                                        <div class="modal fade"
                                                             id="modalSupprimer<?php echo stripslashes($list['ref_fp']); ?>"
                                                             tabindex="-1"
                                                             role="dialog">
                                                            <div class="modal-dialog delete" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal"
                                                                                aria-label="Close"><span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                        <h4 class="modal-title"
                                                                            id="modalSupprimer<?php echo stripslashes($list['ref_fp']); ?>">
                                                                            Confirmation</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Voulez-vous supprimer
                                                                        la demande "<?php echo stripslashes($list['ref_fp']); ?>" de la
                                                                        base ?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button class="btn btn-default" data-dismiss="modal">Non</button>
                                                                        <button class="btn btn-primary" data-dismiss="modal"
                                                                                onclick="suppressionInfos('<?php echo stripslashes($list['ref_fp']); ?>')">
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
                                        } else echo "La recherche n'a renvoye aucun resultat.";?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else echo "
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
        $config = parse_ini_file('../../config.ini');
        $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
        session_start();
        require_once '../fonctions.php';
?>
    <div class="panel panel-default">
        <div class="panel-heading" style="font-size: 14px; font-weight: bolder">
            Liste des Factures Proformas
            <a href='form_principale.php?page=accueil' type='button'
               class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                <span aria-hidden='true'>&times;</span>
            </a>
        </div>
        <div class="panel-body">
            <table class="table table-hover table-bordered ">
                <thead>
                <tr>
                    <th class="entete" style="text-align: center; width: 10%">Numéro</th>
                    <th class="entete" style="text-align: center; width: 15%">Date d'Etablissement</th>
                    <th class="entete" style="text-align: center; width: 15%">Date de Reception</th>
                    <th class="entete" style="text-align: center">Notes</th>
                    <th class="entete" style="text-align: center; width: 10%">Action</th>
                    <?php //if (($_SESSION['type_utilisateur'] == 'administrateur') || ($_SESSION['type_utilisateur'] == 'moyens_genereaux')): ?>
                    <!--                        <td class="entete" style="text-align: center">Actions</td>-->
                    <?php //endif ?>
                </tr>
                </thead>

                <?php
                    $sql = "SELECT * FROM proformas ORDER BY dateetablissement_fp DESC";
                    if ($resultat = $connexion->query($sql)) {
                        if ($resultat->num_rows > 0) {
                            $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                            foreach ($ligne as $list) {
                                ?>
                                <tr>
                                    <td style="text-align: center">
                                        <?php
                                            //Recuperation des articles figurants sur la proforma
                                            $req = "SELECT libelle FROM details_proforma WHERE ref_fp = '" . stripslashes($list['ref_fp']) . "'";
                                            if ($resultat = $connexion->query($req)) {
                                                $rows = $resultat->fetch_all(MYSQLI_ASSOC);
                                                $str = "";
                                                foreach ($rows as $row) {
                                                    $str = $str . stripslashes($row['libelle']) . "\r\n";
                                                }
                                            }
                                        ?>
                                        <a class="btn btn-default"
                                           href="form_principale.php?page=proformas/form_proformas&action=consultation&id=<?php echo stripslashes($list['ref_fp']); ?>"
                                           title="<?php echo $str; ?>"
                                           role="button"><?php echo stripslashes($list['ref_fp']); ?></a>
                                    </td>
                                    <td><?php echo rev_date($list['dateetablissement_fp']); ?></td>
                                    <td><?php echo rev_date($list['datereception_fp']); ?></td>
                                    <td><?php echo stripslashes($list['notes_fp']); ?></td>
                                    <input type="hidden" name="ref_fp"
                                           value="<?php echo stripslashes($list['ref_fp']); ?>"/>

                                    <?php //if (($_SESSION['type_utilisateur'] == 'administrateur') || ($_SESSION['type_utilisateur'] == 'moyens_genereaux')): ?>
                                    <td style="text-align: center">
                                        <a class="btn btn-default" data-toggle="modal"
                                           data-target="#modalSupprimer<?php echo stripslashes($list['ref_fp']); ?>">
                                            <img height="20" width="20" src="img/icons_1775b9/cancel.png" title="Supprimer"/>
                                        </a>
                                        <div class="modal fade"
                                             id="modalSupprimer<?php echo stripslashes($list['ref_fp']); ?>"
                                             tabindex="-1"
                                             role="dialog">
                                            <div class="modal-dialog delete" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close"><span aria-hidden="true">&times;</span>
                                                        </button>
                                                        <h4 class="modal-title"
                                                            id="modalSupprimer<?php echo stripslashes($list['ref_fp']); ?>">
                                                            Confirmation</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        Voulez-vous supprimer
                                                        la demande "<?php echo stripslashes($list['ref_fp']); ?>" de la
                                                        base ?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-default" data-dismiss="modal">Non</button>
                                                        <button class="btn btn-primary" data-dismiss="modal"
                                                                onclick="suppressionInfos('<?php echo stripslashes($list['ref_fp']); ?>')">
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
                                <th colspan="4" class="entete" style="text-align: center">
                                    <h5>Aucune proforma n'a été enregistrée à ce jour</h5>
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
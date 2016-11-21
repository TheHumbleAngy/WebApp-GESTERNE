<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 31/08/2016
     * Time: 17:17
     */
    if (sizeof($_POST) > 0) {
        if (isset($_POST['opt'])) {
            $option = $_POST['opt'];
            $element = $_POST['element'];

            switch ($option) {
                case 'numero':
                    $sql = "SELECT * FROM factures WHERE num_fact LIKE '%" . $element . "%'";
                    break;
                case 'ref':
                    $sql = "SELECT * FROM factures WHERE ref_fact LIKE '%" . $element . "%'";
                    break;
                case 'four':
                    $sql = "SELECT * FROM factures INNER JOIN fournisseurs ON factures.code_four = fournisseurs.code_four WHERE fournisseurs.nom_four LIKE '%" . $element . "%'";
                    break;
                case 'date_eta':
                    $sql = "SELECT * FROM factures WHERE dateetablissement_fact LIKE '%" . $element . "%'";
                    break;
                case 'date_rcp':
                    $sql = "SELECT * FROM factures WHERE datereception_fact LIKE '%" . $element . "%'";
                    break;
                case 'rem':
                    $sql = "SELECT * FROM factures WHERE remarques_facture LIKE '%" . $element . "%'";
                    break;
            }

            $res = mysqli_query($connexion, $sql) or exit(mysqli_error($connexion));

            if ($res->num_rows > 0) { ?>
                <!--suppress ALL-->
                <div style="margin-left: 1.5%; margin-right: 1.5%">
                    <div class="panel panel-default">
                        <div class="panel-heading" style="font-size: 14px; font-weight: bolder">
                            Liste des Factures - Résultats de recherche pour "<?php echo ucfirst($option); ?>" contenant "<?php echo $element; ?>"
                            <a href='form_principale.php?page=form_actions&source=factures&action=rechercher' type='button'
                               class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                <span aria-hidden='true'>&times;</span>
                            </a>
                        </div>
                        <div class="panel-body">
                            <table border="0" class="table table-hover table-bordered">
                                    <thead>
                                    <tr>
                                        <th class="entete" style="text-align: center; width: 10%">Numéro</th>
                                        <th class="entete" style="text-align: center; width: 10%">Référence</th>
                                        <th class="entete" style="text-align: center; width: 40%">Fournisseur</th>
                                        <th class="entete" style="text-align: center; width: 10%">Date d'Etabl.</th>
                                        <th class="entete" style="text-align: center; width: 10%">Date Récep.</th>
                                        <!--<th class="entete" style="text-align: center">Etat</th>-->
                                        <th class="entete" style="text-align: center; width: 12%">Remarques</th>
                                        <?php //if (($_SESSION['type_utilisateur'] == 'administrateur') || ($_SESSION['type_utilisateur'] == 'moyens_genereaux')): ?>
                                        <th class="entete" colspan="2" style="text-align: center; width: 8%">Actions
                                        </th>
                                        <?php //endif ?>
                                    </tr>
                                    </thead>
                                    <?php
                                        if ($resultat = $connexion->query($sql)) {
                                            if ($resultat->num_rows > 0) {
                                                $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                                                foreach ($ligne as $list) {
                                                    ?>
                                                    <tr>
                                                        <td style="text-align: center">
                                                            <?php
                                                                $req = "SELECT libelle_df FROM details_facture WHERE num_fact = '" . stripslashes($list['num_fact']) . "'";
                                                                $str = "";
                                                                if ($resultat = $connexion->query($req)) {
                                                                    $rows = $resultat->fetch_all(MYSQLI_ASSOC);
                                                                    foreach ($rows as $row) {
                                                                        $str = $str . stripslashes($row['libelle_df']) . "\r\n";
                                                                    }
                                                                }
                                                            ?>
                                                            <a class="btn btn-default"
                                                               href="form_principale.php?page=factures/form_factures&action=consultation&id=<?php echo stripslashes($list['num_fact']); ?>"
                                                               title="<?php echo $str; ?>"
                                                               role="button"><?php echo stripslashes($list['num_fact']); ?></a>
                                                        </td>

                                                        <td><?php echo stripslashes($list['ref_fact']); ?></td>
                                                        <td>
                                                            <?php
                                                                $req = "SELECT nom_four FROM fournisseurs WHERE code_four = '" . stripslashes($list['code_four']) . "'";
                                                                if ($result = $connexion->query($req)) {
                                                                    $rows = $result->fetch_all(MYSQLI_ASSOC);
                                                                    foreach ($rows as $row)
                                                                        echo stripslashes($row['nom_four']);
                                                                }
                                                            ?>
                                                        </td>
                                                        <td><?php echo rev_date($list['dateetablissement_fact']); ?></td>
                                                        <td><?php echo rev_date($list['datereception_fact']); ?></td>

                                                        <!--                                <td>-->
                                                        <?php //echo stripslashes($list['etatavecfacpro_facture']); ?><!--</td>-->
                                                        <td><?php echo stripslashes($list['remarques_facture']); ?></td>
                                                        <?php //if (($_SESSION['type_utilisateur'] == 'administrateur') || ($_SESSION['type_utilisateur'] == 'moyens_genereaux')): ?>
                                                        <td>
                                                            <a class="btn btn-default" data-toggle="modal"
                                                               data-target="#modalSupprimer<?php echo stripslashes($list['num_fact']); ?>">
                                                                <img height="20" width="20" src="img/icons_1775b9/cancel.png" title="Supprimer"/>
                                                            </a>
                                                            <div class="modal fade"
                                                                 id="modalSupprimer<?php echo stripslashes($list['num_fact']); ?>"
                                                                 tabindex="-1"
                                                                 role="dialog">
                                                                <div class="modal-dialog delete" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal"
                                                                                    aria-label="Close"><span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                            <h4 class="modal-title"
                                                                                id="modalSupprimer<?php echo stripslashes($list['num_fact']); ?>">
                                                                                Confirmation</h4>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            Voulez-vous supprimer
                                                                            la facture "<?php echo stripslashes($list['num_fact']); ?>" de la
                                                                            base ?
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button class="btn btn-default" data-dismiss="modal">Non</button>
                                                                            <button class="btn btn-primary" data-dismiss="modal"
                                                                                    onclick="suppressionInfos('<?php echo stripslashes($list['num_fact']); ?>')">
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
                                            }
                                            else { ?>
                                                <tr>
                                                    <th colspan="7" class="entete" style="text-align: center">
                                                        <h5>Aucune facture n'a été enregistrée à ce jour</h5>
                                                    </th>
                                                </tr>
                                            <?php }
                                        }
                                    ?>
                                </table>
                        </div>
                    </div>
                </div>
            <?php }
            else echo "
                    <div style='width: 400px; margin-right: auto; margin-left: auto'>
                        <div class='alert alert-info alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                            <a href='form_principale.php?page=form_actions&source=factures&action=rechercher' type='button' class='close'
                                   data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                    <span aria-hidden='true'>&times;</span>
                                </a>
                            <strong>Desole!</strong><br/> La recherche n'a retourné aucun resultat.
                        </div>
                    </div>
                    ";
        }
    } else {
        if (!$config = parse_ini_file('../../config.ini')) $config = parse_ini_file('../config.ini');
        $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
        session_start();
        require_once '../fonctions.php';
        ?>
        <div style="margin-left: 1.5%; margin-right: 1.5%">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Liste des Factures
                    <a href='form_principale.php?page=accueil' type='button'
                       class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                        <span aria-hidden='true'>&times;</span>
                    </a>
                </div>
                <div class="panel-body">
                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th class="entete" style="text-align: center; width: 10%">Numéro</th>
                            <th class="entete" style="text-align: center; width: 10%">Référence</th>
                            <th class="entete" style="text-align: center; width: 40%">Fournisseur</th>
                            <th class="entete" style="text-align: center; width: 10%">Date d'Etabl.</th>
                            <th class="entete" style="text-align: center; width: 10%">Date Récep.</th>
                            <!--<th class="entete" style="text-align: center">Etat</th>-->
                            <th class="entete" style="text-align: center; width: 12%">Remarques</th>
                            <?php //if (($_SESSION['type_utilisateur'] == 'administrateur') || ($_SESSION['type_utilisateur'] == 'moyens_genereaux')): ?>
                            <th class="entete" colspan="2" style="text-align: center; width: 8%">Actions</th>
                            <?php //endif ?>
                        </tr>
                        </thead>

                        <?php
                            $sql = "SELECT * FROM factures ORDER BY dateetablissement_fact ASC";
                            if ($resultat = $connexion->query($sql)) {
                                if ($resultat->num_rows > 0) {
                                    $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                                    foreach ($ligne as $list) {
                                        ?>
                                        <tr>
                                            <td style="text-align: center">
                                                <?php
                                                    //Recuperation des détails figurants sur la demande
                                                    $req = "SELECT libelle_df FROM details_facture WHERE num_fact = '" . stripslashes($list['num_fact']) . "'";
                                                    $str = "";
                                                    if ($resultat = $connexion->query($req)) {
                                                        $rows = $resultat->fetch_all(MYSQLI_ASSOC);
                                                        foreach ($rows as $row) {
                                                            $str = $str . stripslashes($row['libelle_df']) . "\r\n";
                                                        }
                                                    }
                                                ?>
                                                <a class="btn btn-default"
                                                   href="form_principale.php?page=factures/form_factures&action=consultation&id=<?php echo stripslashes($list['num_fact']); ?>"
                                                   title="<?php echo $str; ?>"
                                                   role="button"><?php echo stripslashes($list['num_fact']); ?></a>
                                            </td>

                                            <td style="text-align: center"><?php echo stripslashes($list['ref_fact']); ?></td>
                                            <td style="text-align: center">
                                                <?php
                                                    $req = "SELECT nom_four FROM fournisseurs WHERE code_four = '" . stripslashes($list['code_four']) . "'";
                                                    if ($result = $connexion->query($req)) {
                                                        $rows = $result->fetch_all(MYSQLI_ASSOC);
                                                        foreach ($rows as $row)
                                                            echo stripslashes($row['nom_four']);
                                                    }
                                                ?>
                                            </td>
                                            <td style="text-align: center"><?php echo rev_date($list['dateetablissement_fact']); ?></td>
                                            <td style="text-align: center"><?php echo rev_date($list['datereception_fact']); ?></td>

                                            <!--                                <td>-->
                                            <?php //echo stripslashes($list['etatavecfacpro_facture']); ?><!--</td>-->
                                            <td style="text-align: center"><?php echo stripslashes($list['remarques_facture']); ?></td>
                                            <?php //if (($_SESSION['type_utilisateur'] == 'administrateur') || ($_SESSION['type_utilisateur'] == 'moyens_genereaux')): ?>
                                            <td style="text-align: center">
                                                <a class="btn btn-default" data-toggle="modal"
                                                   data-target="#modalSupprimer<?php echo stripslashes($list['num_fact']); ?>">
                                                    <img height="20" width="20" src="img/icons_1775b9/cancel.png"
                                                         title="Supprimer"/>
                                                </a>
                                                <div class="modal fade"
                                                     id="modalSupprimer<?php echo stripslashes($list['num_fact']); ?>"
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
                                                                    id="modalSupprimer<?php echo stripslashes($list['num_fact']); ?>">
                                                                    Confirmation</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                Voulez-vous supprimer
                                                                la facture
                                                                "<?php echo stripslashes($list['num_fact']); ?>" de la
                                                                base ?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-default" data-dismiss="modal">
                                                                    Non
                                                                </button>
                                                                <button class="btn btn-primary" data-dismiss="modal"
                                                                        onclick="suppressionInfos('<?php echo stripslashes($list['num_fact']); ?>')">
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
                                        <th colspan="7" class="entete" style="text-align: center">
                                            <h5>Aucune facture n'a été enregistrée à ce jour</h5>
                                        </th>
                                    </tr>
                                <?php }
                            }
                        ?>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }
?>

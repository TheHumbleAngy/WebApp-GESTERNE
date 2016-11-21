<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 02/07/2016
     * Time: 09:21
     */
    if (sizeof($_POST) > 0) {
        if (isset($_POST['opt'])) {
            $option = $_POST['opt'];
            $element = $_POST['element'];

            switch ($option) {
                case 'numero': $sql = "SELECT * FROM bons_commande WHERE num_bc LIKE '%" . $element . "%'"; //print_r($sql);
                    break;
                case 'employe':
                    $sql = "SELECT * FROM bons_commande WHERE code_emp IN (SELECT code_emp FROM employes WHERE nom_emp LIKE '%" . $element . "%' OR prenoms_emp LIKE '%" . $element . "%')"; //print_r($sql);
                    break;
                case 'fournisseur': $sql = "SELECT * FROM bons_commande WHERE code_four LIKE '%" . $element . "%'"; //print_r($sql);
                    break;
                case 'date': $sql = "SELECT * FROM bons_commande WHERE date_bc LIKE '%" . $element . "%'"; //print_r($sql);
                    break;
            }

            $resultat = mysqli_query($connexion, $sql) or exit(mysqli_error($connexion));
            if ($resultat->num_rows > 0) { ?>
                <!--suppress ALL -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Liste des Bons de Commande - Résultats de recherche pour "<?php echo ucfirst($option); ?>" contenant "<?php echo $element; ?>"
                                <a href='form_principale.php?page=form_actions&source=bons_commande&action=rechercher' type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                    <span aria-hidden='true'>&times;</span>
                                </a>
                            </div>
                            <div class="panel-body">
                                <table border="0" class="table table-hover table-bordered ">
                                    <thead>
                                    <tr>
                                        <th class="entete" style="text-align: center">Numéro</th>
                                        <th class="entete" style="text-align: center">Editeur</th>
                                        <th class="entete" style="text-align: center">Fournisseur</th>
                                        <th class="entete" style="text-align: center">Date</th>
                                        <th class="entete" style="text-align: center; width: 18%">Action</th>
                                    </tr>
                                    </thead>
                                    <?php while ($data = mysqli_fetch_array($resultat)) { ?>
                                        <tr>
                                            <td style="text-align: center"><a class="btn btn-default" href="form_principale.php?page=bons_commande/form_bon_commande&action=consultation&id=<?php echo stripslashes($data['num_bc']); ?>" role="button"><?php echo stripslashes($data['num_bc']); ?></a></td>
                                            <td style="text-align: center">
                                                <?php
                                                    $req = "SELECT nom_emp, prenoms_emp FROM employes WHERE code_emp = '" . stripslashes($data['code_emp']) . "'";
                                                    if ($result = $connexion->query($req)) {
                                                        $rows = $result->fetch_all(MYSQLI_ASSOC);
                                                        foreach ($rows as $row)
                                                            echo stripslashes($row['prenoms_emp']) . " " . stripslashes($row['nom_emp']);
                                                    }
                                                ?>
                                            </td>
                                            <td style="text-align: center">
                                                <?php
                                                    $req = "SELECT nom_four FROM fournisseurs WHERE code_four = '" . stripslashes($data['code_four']) . "'";
                                                    if ($result = $connexion->query($req)) {
                                                        $rows = $result->fetch_all(MYSQLI_ASSOC);
                                                        foreach ($rows as $row)
                                                            echo stripslashes($row['nom_four']);
                                                    }
                                                ?>
                                            </td>
                                            <td style="text-align: center"><?php echo rev_date($data['date_bc']); ?></td>
                                            <td>
                                                <div style="text-align: center">
                                                    <a class="btn btn-default modifier" data-toggle="modal"
                                                       data-target="#modalSupprimer<?php echo stripslashes($data['num_bc']); ?>">
                                                        <img height="20" width="20" src="img/icons_1775b9/cancel.png" title="Supprimer"/>
                                                    </a>
                                                </div>

                                                <!-- Modal suppression des infos -->
                                                <div class="modal fade"
                                                     id="modalSupprimer<?php echo stripslashes($data['num_bc']); ?>" tabindex="-1"
                                                     role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close"><span aria-hidden="true">&times;</span>
                                                                </button>
                                                                <h4 class="modal-title"
                                                                    id="modalSupprimer<?php echo stripslashes($data['num_bc']); ?>">
                                                                    Confirmation</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                Voulez-vous supprimer
                                                                le bon de commande <?php echo stripslashes($data['num_bc']); ?>" de la base ?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-default" data-dismiss="modal">Non</button>
                                                                <button class="btn btn-primary" data-dismiss="modal"
                                                                        onclick="suppressionInfos('<?php echo stripslashes($data['num_bc']); ?>')">
                                                                    Oui
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else {
                echo "La recherche n'a renvoyé aucun resultat.";
            }
        }
    } else {
        if (!$config = parse_ini_file('../../config.ini')) $config = parse_ini_file('../config.ini');
        $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
        require_once '../fonctions.php';
?>
<div class="panel panel-default">
    <div class="panel-heading">
        Liste des Bons de Commande
        <a href='form_principale.php?page=accueil' type='button'
           class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
            <span aria-hidden='true'>&times;</span>
        </a>
    </div>
    <div class="panel-body">
        <table class="table table-hover table-bordered">
            <thead>
            <tr>
                <th class="entete" style="text-align: center; width: 5%">Numéro</th>
                <th class="entete" style="text-align: center; width: 5%">Date</th>
                <th class="entete" style="text-align: center; width: 20%">Editeur</th>
                <th class="entete" style="text-align: center; width: 20%">Fournisseur</th>
                <th class="entete" style="text-align: center; width: 5%">Action</th>
                <?php /*if (($_SESSION['type_utilisateur'] == 'administrateur') || ($_SESSION['type_utilisateur'] == 'moyens_genereaux')|| ($_SESSION['type_utilisateur'] == 'normal') ):*/ ?>
                <!--                    <td class="entete" colspan="3" style="text-align: center">Actions</td>-->
                <?php /*endif*/ ?>
            </tr>
            </thead>
            <?php
                $req = "SELECT b.num_bc, b.date_bc, e.nom_emp, e.prenoms_emp, f.nom_four
                            FROM bons_commande AS b
                            INNER JOIN employes AS e
                             ON e.code_emp = b.code_emp
                            INNER JOIN fournisseurs AS f
                             ON f.code_four = b.code_four
                            ORDER BY num_bc DESC";
                if ($resultat = $connexion->query($req)) {
                    if ($resultat->num_rows > 0) {
                        $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                        foreach ($ligne as $list) {
                            ?>
                            <tr>
                                <td style="text-align: center">
                                    <?php
                                        //Recuperation des articles figurants sur la demande
                                        $req = "SELECT libelle_dbc FROM details_bon_commande WHERE num_bc = '" . stripslashes($list['num_bc']) . "'";
                                        $str = "";
                                        if ($resultat = $connexion->query($req)) {
                                            $rows = $resultat->fetch_all(MYSQLI_ASSOC);
                                            foreach ($rows as $row) {
                                                $str = $str . stripslashes($row['libelle_dbc']) . "\r\n";
                                            }
                                        }
                                    ?>
                                    <a class="btn btn-default"
                                       href="form_principale.php?page=bons_commande/form_bon_commande&action=consultation&id=<?php echo stripslashes($list['num_bc']); ?>"
                                       title="<?php echo $str; ?>"
                                       role="button"><?php echo stripslashes($list['num_bc']); ?></a>
                                </td>
                                <td style="text-align: center"><?php echo rev_date($list['date_bc']); ?></td>
                                <td style="text-align: center"><?php echo stripslashes($list['prenoms_emp']) . " " . stripslashes($list['nom_emp']); ?></td>
                                <td><?php echo stripslashes($list['nom_four']); ?></td>
                                <td style="text-align: center">
                                    <a class="btn btn-default" data-toggle="modal"
                                       data-target="#modalSupprimer<?php echo stripslashes($list['num_bc']); ?>">
                                        <img height="20" width="20" src="img/icons_1775b9/cancel.png" title="Supprimer"/>
                                    </a>
                                    <div class="modal fade"
                                         id="modalSupprimer<?php echo stripslashes($list['num_bc']); ?>"
                                         tabindex="-1"
                                         role="dialog">
                                        <div class="modal-dialog delete" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close"><span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <h4 class="modal-title"
                                                        id="modalSupprimer<?php echo stripslashes($list['num_bc']); ?>">
                                                        Confirmation</h4>
                                                </div>
                                                <div class="modal-body">
                                                    Voulez-vous supprimer
                                                    le bon de commande "<strong><?php echo stripslashes($list['num_bc']); ?></strong>" de la
                                                    base ?
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-default" data-dismiss="modal">Non</button>
                                                    <button class="btn btn-primary" data-dismiss="modal"
                                                            onclick="suppressionInfos('<?php echo stripslashes($list['num_bc']); ?>')">
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
                            <th colspan="5" class="entete" style="text-align: center">
                                <h5>Aucun bon de commande n'a été enregistré à ce jour</h5>
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
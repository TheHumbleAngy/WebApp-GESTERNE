<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 27/08/2015
     * Time: 6:58 PM
     */
    if (sizeof($_POST) > 0) {
        if (isset($_POST['opt'])) {
            $option = $_POST['opt'];
            $element = $_POST['element'];

            switch ($option) {
                case 'numero': $sql = "SELECT * FROM demandes WHERE num_dbs LIKE '%" . $element . "%' ORDER BY num_dbs DESC ";
                    break;
                case 'emp': $sql = "SELECT * FROM demandes WHERE code_emp LIKE '%" . $element . "%' ORDER BY num_dbs DESC ";
                    break;
                case 'date': $sql = "SELECT * FROM demandes WHERE date_dbs LIKE '%" . $element . "%' ORDER BY num_dbs DESC ";
                    break;
                case 'obj': $sql = "SELECT * FROM demandes WHERE objets_dbs LIKE '%" . $element . "%' ORDER BY num_dbs DESC ";
                    break;
            }

            $res = mysqli_query($connexion, $sql) or exit(mysqli_error($connexion));

            if ($res->num_rows > 0) { ?>
                <!--suppress ALL -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <img src="img/icons_1775b9/search.png" width="20" height="20">
                                Liste des Demandes - Résultats de recherche pour "<?php echo ucfirst($option); ?>" contenant "<?php echo $element; ?>"
                                <a href='form_principale.php?page=form_actions&source=demandes&action=rechercher' type='button' class='close'
                                   data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                    <span aria-hidden='true'>&times;</span>
                                </a>
                            </div>
                            <div class="panel-body">
                                <table class="table table-hover table-bordered ">
                                    <thead>
                                    <tr>
                                        <th class="entete" style="text-align: center; width: 5%">Numéro</th>
                                        <th class="entete" style="text-align: center; width: 5%">Date</th>
                                        <th class="entete" style="text-align: center; width: 20%">Demandeur</th>
                                        <th class="entete" style="text-align: center; width: 20%">Objet</th>
                                        <th class="entete" style="text-align: center; width: 5%">Action</th>
                                    </tr>
                                    </thead>
                                    <?php
                                        //$req = "SELECT * FROM fournisseurs ORDER BY code_four ASC ";
                                        if ($resultat = $connexion->query($sql)) {
                                            $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                                            foreach ($ligne as $list) {
                                                ?>
                                                <tr>
                                                    <td style="text-align: center">
                                                        <?php
                                                            //Recuperation des articles figurants sur la demande
                                                            $req = "SELECT libelle_dd FROM details_demande WHERE num_dbs = '" . stripslashes($list['num_dbs']) . "'";
                                                            $str = "";
                                                            if ($resultat = $connexion->query($req)) {
                                                                $rows = $resultat->fetch_all(MYSQLI_ASSOC);
                                                                $str = "";
                                                                foreach ($rows as $row) {
                                                                    $str = $str . stripslashes($row['libelle_dd']) . "\r\n";
                                                                }
                                                            }
                                                        ?>
                                                        <a class="btn btn-default"
                                                           href="form_principale.php?page=demandes/biens_services/form_demandes&action=consultation&id=<?php echo stripslashes($list['num_dbs']); ?>"
                                                           title="<?php echo $str; ?>"
                                                           role="button"><?php echo stripslashes($list['num_dbs']); ?></a>
                                                    </td>
                                                    <td><?php echo rev_date($list['date_dbs'])?></td>
                                                    <td>
                                                        <h4>
                                                            <span class="label label-primary">
                                                                <?php
                                                                    $req = "SELECT e.nom_emp, e.prenoms_emp
                                                                            FROM demandes AS d, employes AS e
                                                                            WHERE d.code_emp = e.code_emp AND e.code_emp = '" . $list['code_emp'] . "'";
                                                                    $result = $connexion->query($req);
                                                                    $emp = "RAS";
                                                                    if ($result->num_rows > 0) {
                                                                        $lines = $result->fetch_all(MYSQLI_ASSOC);
                                                                        foreach ($lines as $line)
                                                                            $emp = stripslashes($line['prenoms_emp']) . " " . stripslashes($line['nom_emp']);
                                                                    }
                                                                    echo $emp;
                                                                ?>
                                                            </span>
                                                        </h4>

                                                    </td>
                                                    <td><?php echo stripslashes($list['objets_dbs']); ?></td>
                                                    <?php //if (($_SESSION['type_utilisateur'] == 'administrateur') || ($_SESSION['type_utilisateur'] == 'moyens_genereaux')):?>
                                                    <td style="text-align: center">
                                                        <a class="btn btn-default" data-toggle="modal"
                                                           data-target="#modalSupprimer<?php echo stripslashes($list['num_dbs']); ?>">
                                                            <img height="20" width="20" src="img/icons_1775b9/cancel.png" title="Supprimer"/>
                                                        </a>
                                                        <div class="modal fade"
                                                             id="modalSupprimer<?php echo stripslashes($list['num_dbs']); ?>"
                                                             tabindex="-1"
                                                             role="dialog">
                                                            <div class="modal-dialog delete" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal"
                                                                                aria-label="Close"><span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                        <h4 class="modal-title"
                                                                            id="modalSupprimer<?php echo stripslashes($list['num_dbs']); ?>">
                                                                            Confirmation</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Voulez-vous supprimer
                                                                        la demande "<?php echo stripslashes($list['num_dbs']); ?>" de la
                                                                        base ?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button class="btn btn-default" data-dismiss="modal">Non</button>
                                                                        <button class="btn btn-primary" data-dismiss="modal"
                                                                                onclick="suppressionInfos('<?php echo stripslashes($list['num_dbs']); ?>')">
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
                                        } else echo "La recherche n'a renvoyé aucun resultat.";?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else echo "
                        <div style='width: 400px; margin-right: auto; margin-left: auto'>
                            <div class='alert alert-info alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                                <a href='form_principale.php?page=form_actions&source=demandes&action=rechercher' type='button' class='close'
                                       data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                        <span aria-hidden='true'>&times;</span>
                                    </a>
                                <strong>Desole!</strong><br/> La recherche n'a retourné aucun resultat.
                            </div>
                        </div>
                        ";
        }
    } else {
        if (!$config = parse_ini_file('../../../config.ini')) $config = parse_ini_file('../../config.ini');
        $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
        session_start();
        require_once '../../fonctions.php';
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                Liste des Demandes
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
                        <th class="entete" style="text-align: center; width: 20%">Demandeur</th>
                        <th class="entete" style="text-align: center; width: 20%">Objet</th>
                        <th class="entete" style="text-align: center; width: 5%">Action</th>
                        <?php /*if (($_SESSION['type_utilisateur'] == 'administrateur') || ($_SESSION['type_utilisateur'] == 'moyens_genereaux')|| ($_SESSION['type_utilisateur'] == 'normal') ):*/ ?>
                        <!--                    <td class="entete" colspan="3" style="text-align: center">Actions</td>-->
                        <?php /*endif*/ ?>
                    </tr>
                    </thead>
                    <?php
                        $req = "SELECT d.num_dbs, d.date_dbs, d.objets_dbs, e.nom_emp, e.prenoms_emp
                        FROM demandes AS d, employes AS e
                        WHERE d.code_emp = e.code_emp AND e.email_emp = '" . $_SESSION['email'] . "'
                        ORDER BY d.date_dbs DESC";
                        if ($resultat = $connexion->query($req)) {
                            if ($resultat->num_rows > 0) {
                                $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                                foreach ($ligne as $list) {
                                    ?>
                                    <tr>
                                        <td style="text-align: center">
                                            <?php
                                                //Recuperation des articles figurants sur la demande
                                                $req = "SELECT libelle_dd FROM details_demande WHERE num_dbs = '" . stripslashes($list['num_dbs']) . "'";
                                                $str = "";
                                                if ($resultat = $connexion->query($req)) {
                                                    $rows = $resultat->fetch_all(MYSQLI_ASSOC);
                                                    $str = "";
                                                    foreach ($rows as $row) {
                                                        $str = $str . stripslashes($row['libelle_dd']) . "\r\n";
                                                    }
                                                }
                                            ?>
                                            <a class="btn btn-default"
                                               href="form_principale.php?page=demandes/biens_services/form_demandes&action=consultation&id=<?php echo stripslashes($list['num_dbs']); ?>"
                                               title="<?php echo $str; ?>"
                                               role="button"><?php echo stripslashes($list['num_dbs']); ?></a>
                                        </td>
                                        <td style="text-align: center"><?php echo rev_date($list['date_dbs']); ?></td>
                                        <td style="text-align: center">
                                            <h4>
                                                <span class="label label-primary">
                                                    <?php echo stripslashes($list['prenoms_emp']) . " " . stripslashes($list['nom_emp']); ?>
                                                </span>
                                            </h4>
                                        </td>
                                        <td><?php echo stripslashes($list['objets_dbs']); ?></td>
                                        <td style="text-align: center">
                                            <a class="btn btn-default" data-toggle="modal"
                                               data-target="#modalSupprimer<?php echo stripslashes($list['num_dbs']); ?>">
                                                <img height="20" width="20" src="img/icons_1775b9/cancel.png" title="Supprimer"/>
                                            </a>
                                            <div class="modal fade"
                                                 id="modalSupprimer<?php echo stripslashes($list['num_dbs']); ?>"
                                                 tabindex="-1"
                                                 role="dialog">
                                                <div class="modal-dialog delete" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close"><span aria-hidden="true">&times;</span>
                                                            </button>
                                                            <h4 class="modal-title"
                                                                id="modalSupprimer<?php echo stripslashes($list['num_dbs']); ?>">
                                                                Confirmation</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            Voulez-vous supprimer
                                                            la demande "<?php echo stripslashes($list['num_dbs']); ?>" de la
                                                            base ?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-default" data-dismiss="modal">Non</button>
                                                            <button class="btn btn-primary" data-dismiss="modal"
                                                                    onclick="suppressionInfos('<?php echo stripslashes($list['num_dbs']); ?>')">
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
                                        <h5>Aucune demande n'a été enregistrée à ce jour</h5>
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
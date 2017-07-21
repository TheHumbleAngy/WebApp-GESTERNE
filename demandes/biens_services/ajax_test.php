<?php
/**
 * Copyright (c) Ange KOUAKOU, 2017.
 */

/**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 29-Jun-17
     * Time: 4:17 PM
     */

    require_once '../../fonctions.php';
    $iniFile = 'config.ini';
    //A modifier selon l'emplacement du fichier
    $config = parse_ini_file('../../../' . $iniFile);
    $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

    if (isset($_POST['debut']) && isset($_POST['fin'])) {
        $debut = $_POST['debut'];
        $fin = $_POST['fin'];

        echo '
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <h5 style="color: #29487d">Sur la période du <strong>' . $debut . '</strong> au <strong>' . $fin . '</strong></h5>
    </div>
</div>
';

        $debut = rev_date($_POST['debut']);
        $fin = rev_date($_POST['fin']);
        session_start();
    /*if (!$config = parse_ini_file('../../../config.ini')) $config = parse_ini_file('../../config.ini');
    $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
    session_start();
    require_once '../../fonctions.php';*/
?>
        <div class="panel-body">
            <table class="table table-hover table-bordered">
                <thead>
                <tr>
                    <th class="entete" style="text-align: center; width: 5%">Numéro</th>
                    <th class="entete" style="text-align: center; width: 10%">Date</th>
                    <th class="entete" style="text-align: center; width: 20%">Demandeur</th>
                    <th class="entete" style="text-align: center; width: 15%">Objet</th>
                    <th class="entete" style="text-align: center; width: 5%">Action</th>
                    <?php /*if (($_SESSION['type_utilisateur'] == 'administrateur') || ($_SESSION['type_utilisateur'] == 'moyens_genereaux')|| ($_SESSION['type_utilisateur'] == 'normal') ):*/ ?>
                    <!--                    <td class="entete" colspan="3" style="text-align: center">Actions</td>-->
                    <?php /*endif*/ ?>
                </tr>
                </thead>
                <?php
                    $req = "SELECT d.num_dbs, d.date_dbs, d.objets_dbs, e.nom_emp, e.prenoms_emp
                        FROM demandes AS d, employes AS e
                        WHERE d.code_emp = e.code_emp AND e.email_emp = '" . $_SESSION['email'] . "' AND d.date_dbs >= '$debut' AND d.date_dbs <= '$fin'
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
                                        <a class="btn btn-sm btn-default"
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
<?php
    }
?>
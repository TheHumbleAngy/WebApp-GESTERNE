<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 02/07/2016
     * Time: 09:21
     */require_once '../bd/connection.php';
    session_start();
?>
<!--suppress ALL -->
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
                    $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                    foreach ($ligne as $list) {
                        ?>
                        <tr>
                            <td style="text-align: center">
                                <?php
                                    //Recuperation des articles figurants sur la demande
                                    $req = "SELECT libelle_dbc FROM details_bon_commande WHERE num_bc = '" . stripslashes($list['num_bc']) . "'";
                                    if ($resultat = $connexion->query($req)) {
                                        $rows = $resultat->fetch_all(MYSQLI_ASSOC);
                                        $str = "";
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
                            <td style="text-align: center"><?php echo stripslashes($list['date_bc']); ?></td>
                            <td style="text-align: center"><?php echo stripslashes($list['prenoms_emp']) . " " . stripslashes($list['nom_emp']); ?></td>
                            <td><?php echo stripslashes($list['nom_four']); ?></td>
                            <td style="text-align: center">
                                <a class="btn btn-default" data-toggle="modal"
                                   data-target="#modalSupprimer<?php echo stripslashes($list['num_bc']); ?>">
                                    <img height="20" width="20" src="img/icons8/cancel.png" title="Supprimer"/>
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
                }
            ?>
        </table>
    </div>
</div>
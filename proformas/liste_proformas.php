<?php
/**
 * Created by PhpStorm.
 * User: stagiaireinfo
 * Date: 31/03/14
 * Time: 12:00
 */
?>
<!--suppress ALL -->
<div style="margin-left: 1.5%; margin-right: 1.5%">
    <div class="panel panel-default">
        <div class="panel-heading" style="font-size: 14px; font-weight: bolder">
            Factures Proformas
            <a href='form_principale.php?page=accueil' type='button'
               class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                <span aria-hidden='true'>&times;</span>
            </a>
        </div>
        <div class="panel-body" style="overflow: auto">
            <table class="table table-hover table-bordered ">
                <thead>
                <tr>
                    <th class="entete" style="text-align: center">Numéro</th>
                    <th class="entete" style="text-align: center">Date d'Etablissement</th>
                    <th class="entete" style="text-align: center">Date de Reception</th>
                    <th class="entete" style="text-align: center">Notes</th>
                    <th class="entete" style="text-align: center">Action</th>
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
                                $date_eta = stripslashes($list['dateetablissement_fp']);
                                $date_rcp = stripslashes($list['datereception_fp']);

                                $arr = preg_split("/-/", $date_eta);
                                $date_eta = "";
                                for ($i = count($arr) - 1; $i >= 0; $i--) {
                                    if ($i <> 0)
                                        $date_eta .= $arr[$i] . "-";
                                    else
                                        $date_eta .= $arr[$i];
                                }

                                $arr = preg_split("/-/", $date_rcp);
                                $date_rcp = "";
                                for ($i = count($arr) - 1; $i >= 0; $i--) {
                                    if ($i <> 0)
                                        $date_rcp .= $arr[$i] . "-";
                                    else
                                        $date_rcp .= $arr[$i];
                                }
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
                                    <td><?php echo $date_eta; ?></td>
                                    <td><?php echo $date_rcp; ?></td>
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
</div>
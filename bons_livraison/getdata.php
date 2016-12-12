<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 01/09/2016
     * Time: 13:51
     */
    if (sizeof($_POST) > 0) {
        if (isset($_POST['opt'])) {

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
                    Liste des Bons de Livraison
                    <a href='form_principale.php?page=accueil' type='button'
                       class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                        <span aria-hidden='true'>&times;</span>
                    </a>
                </div>
                <div class="panel-body">
                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th class="entete" style="text-align: center">Numéro</th>
                            <th class="entete" style="text-align: center">Date d'Etablissement</th>
                            <th class="entete" style="text-align: center">Date de Réception</th>
                            <th class="entete" style="text-align: center">Fournisseur</th>
                            <th class="entete" style="text-align: center">Réceptionniste</th>
                            <th class="entete" style="text-align: center">Commentaire</th>
                            <?php /*if (($_SESSION['type_utilisateur'] == 'administrateur') || ($_SESSION['type_utilisateur'] == 'moyens_genereaux')):*/ ?>
                            <th class="entete" colspan="2" style="text-align: center">Actions</th>
                            <?php /*endif*/ ?>
                        </tr>
                        </thead>
                        <?php
                            $sql = "SELECT * FROM bons_livraison ORDER BY datereception_bl ASC";

                            if ($valeur = $connexion->query($sql)) {
                                if ($valeur->num_rows > 0) {
                                    $ligne = $valeur->fetch_all(MYSQLI_ASSOC);
                                    foreach ($ligne as $list) {
                                        ?>
                                        <tr>
                                            <td style="text-align: center">
                                                <?php
                                                    $req = "SELECT libelle_dbl FROM details_bon_livraison WHERE num_bl = '" . stripslashes($list['num_bl']) . "'";
                                                    $str = "";
                                                    if ($resultat = $connexion->query($req)) {
                                                        $rows = $resultat->fetch_all(MYSQLI_ASSOC);
                                                        foreach ($rows as $row) {
                                                            $str = $str . stripslashes($row['libelle_dbl']) . "\r\n";
                                                        }
                                                    }
                                                ?>
                                                <a class="btn btn-default"
                                                   href="form_principale.php?page=bons_livraison/form_bons_livraison&action=consultation&id=<?php echo stripslashes($list['num_bl']); ?>"
                                                   title="<?php echo $str; ?>"
                                                   role="button"><?php echo stripslashes($list['num_bl']); ?></a>
                                            </td>
                                            <td style="text-align: center"><?php echo rev_date($list['dateetablissement_bl']); ?></td>
                                            <td style="text-align: center"><?php echo rev_date($list['datereception_bl']); ?></td>
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
                                            <td style="text-align: center">
                                                <?php
                                                    $req = "SELECT nom_emp, prenoms_emp FROM employes WHERE code_emp = '" . stripslashes($list['code_emp']) . "'";
                                                    if ($result = $connexion->query($req)) {
                                                        $rows = $result->fetch_all(MYSQLI_ASSOC);
                                                        foreach ($rows as $row)
                                                            echo stripslashes($row['prenoms_emp']) . " " . stripslashes($row['nom_emp']);
                                                    }
                                                ?>
                                            </td>
                                            <td style="text-align: center"><?php echo stripslashes($list['commentaires_bl']); ?></td>
                                            <?php /*if (($_SESSION['type_utilisateur'] == 'administrateur') || ($_SESSION['type_utilisateur'] == 'moyens_genereaux')):*/ ?>
                                            <td style="text-align: center">
                                                <div style="text-align: center">
                                                    <a class="btn btn-default modifier" data-toggle="modal"
                                                       data-target="#modalSupprimer<?php echo stripslashes($list['num_bl']); ?>">
                                                        <img height="20" width="20" src="img/icons_1775b9/cancel.png"
                                                             title="Supprimer"/>
                                                    </a>
                                                </div>

                                                <!-- Modal suppression des infos -->
                                                <div class="modal fade"
                                                     id="modalSupprimer<?php echo stripslashes($list['num_bl']); ?>"
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
                                                                    id="modalSupprimer<?php echo stripslashes($list['num_bl']); ?>">
                                                                    Confirmation</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                Voulez-vous supprimer
                                                                le bon de
                                                                commande <?php echo stripslashes($list['num_bl']); ?>"
                                                                de la base ?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-default" data-dismiss="modal">
                                                                    Non
                                                                </button>
                                                                <button class="btn btn-primary" data-dismiss="modal"
                                                                        onclick="suppressionInfos('<?php echo stripslashes($list['num_bl']); ?>')">
                                                                    Oui
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <?php /*endif*/ ?>
                                        </tr>
                                        <?php
                                    }
                                }
                                else { ?>
                                    <tr>
                                        <th colspan="7" class="entete" style="text-align: center">
                                            <h5>Aucun bon de livraison n'a été enregistré à ce jour</h5>
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
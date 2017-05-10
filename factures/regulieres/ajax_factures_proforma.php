<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 12-Aug-15
     * Time: 11:58 AM
     */
    if (isset($_POST["proforma"])) {

        include '../../fonctions.php';
        $iniFile = 'config.ini';

        $config = parse_ini_file('../../../' . $iniFile);

        $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
        if ($connexion->connect_error)
            die($connexion->connect_error);

        $pro = htmlspecialchars($_POST['proforma'], ENT_QUOTES);

        //On vérifie que la proforma existe
        $sql = "SELECT num_fp FROM proformas WHERE num_fp = '$pro'";
        $resultat = $connexion->query($sql);
        if ($resultat->num_rows > 0) {
            /* on réccupère le département de l'employé demandeur à partir des tables proformas, demandes_proformas, demandes, employés
                    en fonction de la proforma sélectionnée*/
            $sql = "SELECT libelle, qte_dfp, pu_dfp, remise_dfp, fournisseurs.code_four, fournisseurs.nom_four
                FROM details_proforma INNER JOIN proformas
                ON details_proforma.num_fp = proformas.num_fp
                INNER JOIN fournisseurs
                ON fournisseurs.code_four = proformas.code_four
                WHERE proformas.num_fp = '" . $pro . "'";

            $i = 0;

            if ($result = $connexion->query($sql)) {
                if ($result->num_rows > 0) {
                    $lignes = $result->fetch_all(MYSQLI_ASSOC);
                    $code_four = stripslashes($lignes[0]['code_four']);
                    echo '
<div style="text-align: center; margin-bottom: 2%">
    <button class="btn btn-info" type="button" name="valider" onclick="ajout_facture();" style="width: 150px">
        Valider
    </button>
</div>
<div class="col-md-12">
    <table style="border-collapse: separate; border-spacing: 8px" border="0">
        <tr>
            <td class="champlabel">Fournisseur :</td>
            <td>
                <label>
                    <h4>
                        <span class="label label-primary" id="four">' . stripslashes($lignes[0]['nom_four']) . '</span>
                    </h4>
                    <input type="hidden" id="code_four" value="' . $code_four . '">
                </label>
            </td>
        </tr>
    </table>
    <div class="panel panel-default">
        <table border="0" class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th class="entete" style="text-align: center; width: 50%">Désignation</th>
                    <th class="entete" style="text-align: center; width: 5%">Quantité</th>
                    <th class="entete" style="text-align: center; width: 15%">Prix Unitaire</th>
                    <th class="entete" style="text-align: center; width: 5%">Remise</th>
                    <th class="entete" style="text-align: center; width: 20%">Prix T.T.C</th>
                </tr>
            </thead>';

                    $total = 0;
                    $i = 0;
                    foreach ($lignes as $list) {
                        echo '<tr>';
                        echo '<td style="text-align: left">' . stripslashes($list['libelle']) . '<input type="hidden" id="libelle_dfact' . $i . '" value="' . stripslashes($list['libelle']) . '"/></td>';
                        echo '<td style="text-align: center">' . stripslashes($list['qte_dfp']) . '<input type="hidden" id="qte_dfact' . $i . '" value="' . stripslashes($list['qte_dfp']) . '"/></td>';
                        echo '<td style="text-align: center">' . number_format(stripslashes($list['pu_dfp']), 0, ',', ' ') . '<input type="hidden" id="pu_dfact' . $i . '" value="' . stripslashes($list['pu_dfp']) . '"/></td>';
                        echo '<td style="text-align: center">' . stripslashes($list['remise_dfp']) . '%<input type="hidden" id="rem_dfact' . $i . '" value="' . stripslashes($list['remise_dfp']) . '"/></td>';

                        $qte = stripslashes($list['qte_dfp']);
                        $pu = stripslashes($list['pu_dfp']);
                        $rem = stripslashes($list['remise_dfp']);

                        if ($rem > 0) {
                            $rem = $rem / 100;
                            $ttc = $qte * $pu * (1 - $rem);
                        }
                        else
                            $ttc = $qte * $pu;

                        $total += (int)$ttc;
                        echo '<td style="text-align: right">';
                        echo number_format($ttc, 0, ',', ' ');
                        echo '</td>';
                        echo '</tr>';
                        $i++;
                    }

                    echo '<thead>
                    <tr style="font-weight: bolder">
                        <th class="entete" style="text-align: center" colspan="4">TOTAL</th>
                        <th class="entete" style="text-align: right">' . number_format($total, 0, ',', ' ') . '</th>
                    </tr>
                  </thead>
            </table>
        </div>
    </div>
    <input type="hidden" id="nbr" value="' . $i . '">';
                }
                else {
                    echo '
            <div class="col-md-12" style="margin-top: 10px">
                <div class="jumbotron" style="width: 70%; padding: 30px 30px 20px 30px; background-color: rgba(1, 139, 178, 0.1); margin-left: auto; margin-right: auto">
                    <p style="font-size: small">Désolé, cette proforma ne contient aucun article.</p>
                </div>
            </div>
            ';
                }
            }
        }
        else {
            echo "
            <div class='alert alert-info alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                <strong>Info!</strong><br/> Cette proforma n'existe pas. Veuillez entrer un autre numéro s'il vous plaît.
            </div>
            ";
        }
        
    }
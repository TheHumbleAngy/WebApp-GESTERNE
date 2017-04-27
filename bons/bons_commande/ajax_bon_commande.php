<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 12-Jul-15
     * Time: 7:22 PM
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

            $sql1 = "SELECT four.code_four, four.nom_four
            FROM fournisseurs AS four INNER JOIN proformas AS pro
            ON four.code_four = pro.code_four
            WHERE pro.num_fp = '" . $pro . "'";

            $sql2 = "SELECT libelle, qte_dfp, pu_dfp, remise_dfp
            FROM details_proforma INNER JOIN proformas
            ON details_proforma.num_fp = proformas.num_fp
            WHERE proformas.num_fp = '" . $pro . "'";

            $fournisseur = "";
            if ($result = $connexion->query($sql1)) {
                if ($result->num_rows === 1) {
                    $lignes = $result->fetch_all(MYSQLI_ASSOC);
                    foreach ($lignes as $list) {
                        $nom_four = $list['nom_four'];
                        $code_four = $list['code_four'];
                    }

                    echo '
        <div style="text-align: center; margin-bottom: 1%">
                <button class="btn btn-info" type="submit" name="valider" style="width: 150px">
                    Valider
                </button>
            </div>
        <table class="formulaire">
            <tr>
                <td class="champlabel" style="padding-left: 10px">Fournisseur :</td>
                <td>
                    <label>
                        <input type="text" name="nom_four" class="form-control" id="four" readonly value="' . $nom_four . '">
                        <input type="hidden" name="cod_four" value="' . $code_four . '">
                    </label>
                </td>
            </tr>
            <tr></tr>
        </table>
        <div class="col-md-12">
            <div class="panel panel-default">
                <table border="0" class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th class="entete" style="text-align: center">Libellé</th>
                        <th class="entete" style="text-align: center">Quantité</th>
                        <th class="entete" style="text-align: center">Prix Unitaire</th>
                        <th class="entete" style="text-align: center">Remise</th>
                        <th class="entete" style="text-align: center">Prix T.T.C</th>
                    </tr>
                </thead>
            ';

                    $i = 0;
                    if ($result = $connexion->query($sql2)) {
                        $lignes = $result->fetch_all(MYSQLI_ASSOC);
                        $total = 0;
                        foreach ($lignes as $list) {
                            $i++;
                            echo '<tr>';
                            echo '<td style="text-align: center">' . stripslashes($list['libelle']) . '<input type="hidden" name="libelle_dbc[]" value="' . stripslashes($list['libelle']) . '"></td>';
                            echo '<td style="text-align: center">' . stripslashes($list['qte_dfp']) . '<input type="hidden" name="qte_dbc[]" value="' . stripslashes($list['qte_dfp']) . '"></td>';
                            echo '<td style="text-align: center">' . number_format(stripslashes($list['pu_dfp']), 0, ',', ' ') . '<input type="hidden" name="pu_dbc[]" value="' . stripslashes($list['pu_dfp']) . '"></td>';
                            echo '<td style="text-align: center">' . stripslashes($list['remise_dfp']) . '%' . '<input type="hidden" name="remise_dbc[]" value="' . stripslashes($list['remise_dfp']) . '"></td>';

                            $qte = stripslashes($list['qte_dfp']);
                            $pu = stripslashes($list['pu_dfp']);
                            $rem = stripslashes($list['remise_dfp']);

                            if ($rem > 0) {
                                $rem = $rem / 100;
                                $ttc = $qte * $pu * (1 - $rem);
                            } else
                                $ttc = $qte * $pu;

                            $total = (int)$total + (int)$ttc;
                            echo '<td style="text-align: right">';
                            echo number_format($ttc, 0, ',', ' ');
                            echo '</td>';
                            echo '</tr>';
                        }
                    }

                    echo '<thead>
                    <tr style="font-weight: bolder">
                        <th class="entete" style="text-align: center" colspan="4">TOTAL</th>
                        <th class="entete" style="text-align: right">' . number_format($total, 0, ',', ' ') . '</th>
                    </tr>
                  </thead>

                </table>
            </div>
        </div>';
                    echo '<input type="hidden" name="nbr" value="' . $i . '">';

                }
            }
        } else {
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
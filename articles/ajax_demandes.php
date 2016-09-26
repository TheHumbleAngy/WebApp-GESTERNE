<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 08/07/2016
     * Time: 17:42
     */
    /** @lang MySQL */
    if (isset($_POST["demande"])) {
        $dmd = htmlspecialchars($_POST['demande'], ENT_QUOTES);
        $config = parse_ini_file('../../config.ini');
        $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

        $sql = "SELECT * FROM details_demande WHERE code_dbs = '" . $dmd . "' AND statut_dd = 'non satisfait'";

        if (($result = $connexion->query($sql)) && ($result->num_rows > 0)) {
            $lignes = $result->fetch_all(MYSQLI_ASSOC);

            echo '
        <div style="text-align: center; margin-bottom: 1%">
                <button class="btn btn-info" type="submit" name="valider" style="width: 150px">
                    Valider
                </button>
            </div>
        <div class="col-md-12">
            <div class="panel panel-default">
                <table border="0" class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th class="entete" style="text-align: center">Libellé</th>
                        <th class="entete" style="text-align: center; width: 12%">Qté. Demandée</th>
                        <th class="entete" style="text-align: center; width: 12%">Qté. Déjà Servie</th>
                        <th class="entete" style="text-align: center; width: 10%">Qté. Dispo.</th>
                        <th class="entete" style="text-align: center; width: 10%">Qté. Servie</th>
                        <th class="entete" style="text-align: center; width: 12%">Observation</th>
                    </tr>
                </thead>
            ';
            $nbr = 0;
            foreach ($lignes as $list) {
                $nbr += 1;
                $qte_dmd = (int)stripslashes($list['qte_dd']);
                $libelle_dmd = stripslashes($list['libelle_dd']);
                $nature_dmd = stripslashes($list['nature_dd']);

                echo '<tr>';
                echo '<td style="text-align: center">' . stripslashes($list['libelle_dd']) . '<input type="hidden" name="libelle_dd[]" value="' . stripslashes($list['libelle_dd']) . '"></td>';
                echo '<td style="text-align: center">' . $qte_dmd . '<input type="hidden" name="qte_dd[]" value="' . stripslashes($list['qte_dd']) . '"></td>';
                $sql = "SELECT qte_serv FROM details_demande WHERE code_dbs = '" . $dmd . "' AND libelle_dd = '" . addslashes($libelle_dmd) . "'";
                if ($result = $connexion->query($sql)) {
                    $lines = $result->fetch_assoc();
                    $qte_serv = (int)$lines['qte_serv'];
                    echo '<td style="text-align: center">' . $qte_serv . '</td>';
                }

                if ($nature_dmd === 'bien') {
                    //On ressort la quantité disponible pour chaque article de la demande
                    $sql = "SELECT stock_art FROM articles WHERE designation_art = '" . addslashes($libelle_dmd) . "'";
                    if ($result = $connexion->query($sql)) {
                        $lines = $result->fetch_assoc();
                        $qte_dispo = 0;
                        $qte_dispo = (int)$lines['stock_art'];
                        echo '<td style="text-align: center">' . $qte_dispo . '</td>';
                        echo '<td style="text-align: center"><label style="margin-left: auto; margin-right: auto" class="nomargin_tb"><input type="number" name="qte_serv[]" class="form-control" min="0" max="'. $qte_dispo .'" required></label></td>';
                        echo '<td style="text-align: center"><input type="hidden" name="obsv[]" value=""></td>';
                    }
                } else {
                    echo '<td style="text-align: center"></td>';
                    echo '<td style="text-align: center"><input type="hidden" name="qte_serv[]" value="'. $qte_dmd . '"></td>';
                    echo '<td style="text-align: center">
                        <select class="form-control" name="obsv[]">
                            <option value="non">NON FAIT</option>
                            <option value="ok">FAIT</option>
                        </select>
                      </td>';
                }
                echo '</tr>';
            }
            echo '<input type="hidden" name="nbr" value="' . $nbr . '">';
        }
    }
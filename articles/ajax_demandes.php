<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 08/07/2016
     * Time: 17:42
     */
    /** @lang MySQL */
    if (isset($_POST["demande"])) {
    
        error_reporting(E_ERROR);
        include '../fonctions.php';
    
        $iniFile = 'config.ini';
    
        while (!$config = parse_ini_file($iniFile))
            configpath($iniFile);

        $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

        $demandes = $_POST['demande'];
        $length = sizeof($demandes);

        echo '
        <div style="text-align: center; margin-bottom: 1%">
                <button class="btn btn-info" type="button" onclick="ajout_sortie_demande()" name="valider" style="width: 150px">
                    Valider
                </button>
        </div>
        <table class="formulaire">
            <tr>
                <td><h4>Demandes Sélectionnées </h4></td>                
            ';

        //Affiche la liste des différents numéros de demandes du tableau
        for ($i = 0; $i < $length; $i++) {
            echo '<td><h4><span class="label label-info">' . $demandes[$i] . '</span></h4></td>';
        }

        echo '
            </tr>
        </table>
        <div class="col-md-12">
            <div class="panel panel-default">
                <table border="0" class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th class="entete" style="text-align: center; vertical-align: middle" rowspan="2">Libellé</th>
                        <th class="entete" style="text-align: center" colspan="4">Quantités</th>
                        <th class="entete" style="text-align: center; vertical-align: middle" rowspan="2">Observation</th>
                    </tr>
                    <tr>
                        <th class="entete" style="text-align: center; width: 10%">Demandée</th>
                        <th class="entete" style="text-align: center; width: 10%">Déjà Servie</th>
                        <th class="entete" style="text-align: center; width: 10%">Dispo.</th>
                        <th class="entete" style="text-align: center; width: 10%">Servie</th>
                    </tr>
                </thead>
        ';

        $nbr = 0;
        for ($i = 0; $i < $length; $i++) {
            $sql = "SELECT * FROM details_demande WHERE num_dbs = '" . $demandes[$i] . "' AND statut_dd = 'non satisfait'";
            if (($result = $connexion->query($sql)) && ($result->num_rows > 0)) {
                $lignes = $result->fetch_all(MYSQLI_ASSOC);

                foreach ($lignes as $list) {
                    $qte_dmd = (int)stripslashes($list['qte_dd']);
                    $libelle_dmd = stripslashes($list['libelle_dd']);
                    $nature_dmd = stripslashes($list['nature_dd']);

                    echo '<tr>';
                    echo '<td style="text-align: center">' . stripslashes($list['libelle_dd']) . '<input type="hidden" name="libelle_dd['. $nbr . ']" id="libelle_dd' . $nbr . '" value="' . stripslashes($list['libelle_dd']) . '">
                            <input type="hidden" name="num_demandes[]" id="num_demande' . $nbr . '" value="' . stripslashes($list['num_dbs']) . '">
                            <input type="hidden" name="num_details_demande[]" id="num_details_demande' . $nbr . '"value="' . stripslashes($list['num_dd']) . '"></td>';

                    //TODO: décommenter au cas où l'info sur la qte demandée est requise
                    echo '<td style="text-align: center">' . $qte_dmd . '</td>';

                    if ($nature_dmd === 'bien') {

                        //La quantité déjà servie
                        $sql = "SELECT qte_serv FROM details_demande WHERE num_dbs = '" . $demandes[$i] . "' AND libelle_dd = '" . addslashes($libelle_dmd) . "'";
                        if ($result = $connexion->query($sql)) {
                            $lines = $result->fetch_assoc();
                            $qte_serv = (int)$lines['qte_serv'];
                            echo '<td style="text-align: center">' . $qte_serv . '</td>';
                        }

                        //On ressort la quantité disponible pour chaque article de la demande
                        $sql = "SELECT stock_art FROM articles WHERE designation_art = '" . addslashes($libelle_dmd) . "'";
                        if ($result = $connexion->query($sql)) {
                            $lines = $result->fetch_assoc();
                            $qte_dispo = 0;
                            $qte_dispo = (int)$lines['stock_art'];
                            echo '<td style="text-align: center">' . $qte_dispo . '</td>';

                            //La quantité à servir pour chaque article de la demande
                            echo '<td style="text-align: center">
                                    <label style="margin-left: auto; margin-right: auto" class="nomargin_tb">
                                        <input type="number" name="qte_aserv['. $nbr . ']" id="qte_aserv' . $nbr . '" class="form-control" min="0" max="'. $qte_dispo .'">
                                    </label>
                                  </td>';
                            //TODO: décommenter au cas où l'info sur l'observation est requise
                            echo '<td><input type="hidden" name="obsv[]" value="RAS" id="obsv' . $nbr . '"></td>';
//                            echo '<td></td>';
                        }
                    } else {
//                        echo '<td colspan="2"></td>';
                        echo '<td colspan="3"><input type="hidden" name="qte_aserv[]" value="null" id="qte_aserv' . $nbr . '"></td>';
                        echo '<td style="text-align: center">
                        <select class="form-control" name="obsv['. $nbr . ']" id="obsv' . $nbr . '" >
                            <option value="non">NON FAIT</option>
                            <option value="ok">FAIT</option>
                        </select>
                      </td>';
                    }
                    echo '</tr>';
                    $nbr += 1;
                }
            }
        }
        echo '<input type="hidden" name="nbr_dd" id="nbr_dd" value="' . $nbr . '">';
        echo '<input type="hidden" name="nbr_dmd" id="nbr_dmd" value="' . $length . '">';
    }
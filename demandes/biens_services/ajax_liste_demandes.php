<?php
    /**
     * Copyright (c) Ange KOUAKOU, 2017.
     */

    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 11-Jul-17
     * Time: 11:47 AM
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
        
        $req = "SELECT * FROM demandes WHERE date_dbs BETWEEN '$debut' AND '$fin' ORDER BY date_dbs DESC ";
        if ($resultat = $connexion->query($req)) {
            echo '
<div style="height: 380px; overflow: auto">
            ';

            if (mysqli_num_rows($resultat) > 0) {
                echo '
<div class="panel-body">
    <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th class="entete" style="text-align: center; width: 5%">Numéro</th>
            <th class="entete" style="text-align: center; width: 10%">Date</th>
            <th class="entete" style="text-align: center; width: 20%">Demandeur</th>
            <th class="entete" style="text-align: center; width: 15%">Objet</th>
        </tr>
        </thead>
                ';

                $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                $i = 0;
                foreach ($ligne as $list) {
                    echo '
        <tr>
            <!-- Colonne NUMERO -->
            <td style="text-align: center">
                    ';
                    //Recuperation des articles figurants sur la demande
                    $num_demande = stripslashes($list['num_dbs']);
                    $req = "SELECT libelle_dd FROM details_demande WHERE num_dbs = '" . $num_demande . "'";
                    $str = "";
                    if ($resultat = $connexion->query($req)) {
                        $rows = $resultat->fetch_all(MYSQLI_ASSOC);
                        $str = "";
                        foreach ($rows as $row) {
                            $str = $str . stripslashes($row['libelle_dd']) . "\r\n";
                        }
                    }
                    echo '
                <button class="btn btn-sm btn-default" data-toggle="modal" 
                data-target="#modalDemande' . $i .'" type="button">' . $num_demande . '</button>
                    ';
                    echo '
                <!-- MODAL -->
                <div class="modal fade" id="modalDemande' . $i . '" tabindex="-1"
                role="dialog" aria-hidden="true">
                    <div class="modal-dialog" style="width: 850px">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close"
                                        data-dismiss="modal"
                                        aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title">
                                    Demande
                                    - <span class="label label-primary">' . $num_demande . '</span>
                                </h4>
                            </div>
                            <div class="modal-body">
                                <table class="formulaire" style="width: 100%"
                                       border="0">
                                    <tr>
                                        <td class="champlabel">Employé :</td>
                                        <td class="textbox">
                                            <label>
                </div>
                    ';
                    $sql = "SELECT e.nom_emp, e.prenoms_emp
                            FROM employes AS e
                            INNER JOIN demandes AS d
                            ON e.code_emp = d.code_emp
                            WHERE d.num_dbs = '" . $num_demande . "'";
                    if ($valeur = $connexion->query($sql)) {
                        $ligne = $valeur->fetch_all(MYSQLI_ASSOC);
                        $nom_prenoms_emp = "";
                        foreach ($ligne as $data) {
                            $nom_prenoms_emp = stripslashes($data['prenoms_emp']) . ' ' . stripslashes($data['nom_emp']);
                        }
                    }
                    echo '
                    <h4><span class="label label-primary">' . $nom_prenoms_emp . '</span></h4>
                    </label>
                    </td>
                    <td class="champlabel" rowspan="2">Objet :
                    </td>
                    <td class="textbox">
                        <label>
                        <textarea id="objets_dbs"
                                  name="objets_dbs" rows="3"
                                  class="form-control" readonly
                                  style="resize: none">' . stripslashes($list['objets_dbs']) . '</textarea>
                        </label>
                    </td>
                </tr>
            </table>

            <br/>

            <div class="row">
                    ';
                    $sql = "SELECT * FROM details_demande WHERE num_dbs = '" . $num_demande . "'";
                    if ($valeur = $connexion->query($sql)) {
                        $ligne = $valeur->fetch_all(MYSQLI_ASSOC);
                        echo '
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <table border="0"
                                       class="table table-hover table-bordered">
                                    <thead>
                                    <tr>
                                        <th class="entete"
                                            rowspan="2"
                                            style="text-align: center; width: 35%; vertical-align: middle">
                                            Libellé
                                        </th>
                                        <th class="entete"
                                            rowspan="2"
                                            style="text-align: center; vertical-align: middle">
                                            Nature
                                        </th>
                                        <th class="entete"
                                            colspan="2"
                                            style="text-align: center">
                                            Quantité
                                        </th>
                                        <th class="entete"
                                            rowspan="2"
                                            style="text-align: center; width: 25%; vertical-align: middle">
                                            Observation
                                        </th>
                                        <th class="entete"
                                            rowspan="2"
                                            style="text-align: center; width: 15%; vertical-align: middle">
                                            Statut
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="entete">
                                            Demandée
                                        </th>
                                        <th class="entete">
                                            Servie
                                        </th>
                                    </tr>
                                    </thead>
                        ';

                        foreach ($ligne as $data) {
                            ?>
                            <tr>
                                <td style="text-align: center; vertical-align: middle"><?php echo $data['libelle_dd']; ?></td>
                                <td style="text-align: center; vertical-align: middle"><?php echo ucfirst($data['nature_dd']); ?></td>
                                <td style="text-align: center; vertical-align: middle"><?php echo $data['qte_dd']; ?></td>
                                <td style="text-align: center; vertical-align: middle"><?php echo $data['qte_serv']; ?></td>
                                <td style="vertical-align: middle"><?php echo $data['observations_dd']; ?></td>
                                <td style="vertical-align: middle"><?php echo ucfirst($data['statut_dd']); ?></td>
                            </tr>
                            <?php
                        }

                        echo '
                            </table>
                            </div>

                            <br>
                            <a role="button"
                               title="Traiter la demande"
                               href="form_principale.php?page=articles/mouvements_stock&action=sortie"
                               target="_blank"
                               class="btn btn-sm btn-default">
                                Procéder
                                <img src="img/icons_1775b9/right_filled.png" width="20">
                            </a>
                        </div>
                        ';
                    }
                    echo '
                    </div>

                </div>
            </div>
        </div>
    </div>
</td>

<!-- Colonne DATE -->
<td style="text-align: center">' . rev_date($list['date_dbs']) . '</td>

<!-- Colonne DEMANDEUR -->
<td style="text-align: center">
    <h4>
    <span class="label label-primary">' . $nom_prenoms_emp . '</span>
    </h4>
</td>

<!-- Colonne OBJET -->
<td>' . stripslashes($list['objets_dbs']) . '</td>
</tr>
                    ';

                    $i++;
                }
            }
            else {
                echo '
                <tr>
                    <th colspan="5" class="entete" style="text-align: center">
                        <strong><h5 style="color: #29487d">Aucune demande n\'a été enregistrée à ce jour</h5></strong>
                    </th>
                </tr>
                ';
            }
        }
        echo '
</table>
</div>
        ';
    }
<?php
    /**
     * Created by PhpStorm.
     * User: ange-marius.kouakou
     * Date: 03/08/2016
     * Time: 18:11
     */
    require_once '../fonctions.php';
    if (!$config = parse_ini_file('../../../config.ini')) $config = parse_ini_file('../../config.ini');
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
        
        //Traitement des entrées
        $req = "SELECT * FROM entrees_stock WHERE date_entr BETWEEN '$debut' AND '$fin' ORDER BY date_entr DESC ";
        if ($resultat = $connexion->query($req)) {

            echo '
<div style="height: 380px; overflow: auto">
            ';

            if (mysqli_num_rows($resultat) > 0) {

                echo '
<div class="col-md-6">
    <strong>
        <h4 style="color: #29487d"><span class="label label-info">Entrées </span></h4>
    </strong>
    <div class="panel panel-default">
        <table class="table table-hover table-bordered">
            <thead>
            <tr>
                <th class="entete" style="text-align: center">Date</th>
                <th class="entete" style="text-align: center">Numéro</th>
            </tr>
            </thead>
            ';

                $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                foreach ($ligne as $list) {

                    echo '
            <tr>
                <td style="text-align: center">' . rev_date(stripslashes($list['date_entr'])) . '</td>
                <td style="text-align: center">
                    <a class="btn btn-default" data-toggle="modal" data-target="#modalConsultation' . stripslashes($list['num_entr']) . '">' . stripslashes($list['num_entr']) . '</a>
                    <div class="modal fade" id="modalConsultation' . stripslashes($list['num_entr']) . '" tabindex="-1" role="dialog">
                        <div class="modal-dialog delete" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close"><span
                                            aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title" id="modalConsultation' . stripslashes($list['num_entr']) . '">
                                        Entrée ' . stripslashes($list['num_entr']) . ' au ' . rev_date(stripslashes($list['date_entr'])) . '
                                    </h4>
                                </div>
                                <div class="modal-body">
                                    <table border="0" class="table table-hover table-bordered ">
                                        <thead>
                                        <tr>
                                            <th class="entete" style="text-align: center">Article(s) Reçu(s)</th>
                                            <th class="entete" style="text-align: center">Quantité(s) Reçue(s)</th>
                                        </tr>
                                        </thead>
                    ';

                    $sql = "SELECT * FROM details_entree WHERE num_entr = '" . stripslashes($list['num_entr']) . "'";
                    if ($result = $connexion->query($sql)) {
                        $lignes = $result->fetch_all(MYSQLI_ASSOC);
                        foreach ($lignes as $liste) {
                            $sql1 = "SELECT designation_art FROM articles WHERE code_art = '" . stripslashes($liste['code_art']) . "'";
                            $art = "";
                            if ($result1 = $connexion->query($sql1)) {
                                $lignes1 = $result1->fetch_all(MYSQLI_ASSOC);
                                foreach ($lignes1 as $liste1) {
                                    $art = stripslashes($liste1['designation_art']);
                                }
                            }
                            echo '
                        <tr>
                            <td>' . $art . '</td>
                            <td>' . stripslashes($liste['qte_dentr']) . '</td>
                        </tr>
                        ';
                        }
                    }
                    echo '
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
                ';
                }
                echo '
        </table>
    </div>
</div>
        ';
            } else
                echo '
                <div class="col-md-6">
                    <strong>
                        <h5 style="color: #29487d"><span class="label label-info">Entrée </span></h5>
                    </strong>
                    <strong>
                        <h5 style="color: #29487d">Il n\'y a eu aucune entrée durant cette période.</h5>
                    </strong>
                </div>
                ';

            //Traitement des sorties
            $req1 = "SELECT * FROM sorties_stock WHERE date_sort BETWEEN '$debut' AND '$fin' ORDER BY date_sort DESC ";
            if ($resultat1 = $connexion->query($req1)) {
                if (mysqli_num_rows($resultat1) > 0) {

                    echo '
<div class="col-md-6">
    <strong>
        <h4 style="color: #29487d"><span class="label label-info">Sorties </span></h4>
    </strong>
    <div class="panel panel-default">
        <table class="table table-hover table-bordered">
            <thead>
            <tr>
                <th class="entete" style="text-align: center">Date</th>
                <th class="entete" style="text-align: center">Numéro</th>
            </tr>
            </thead>
            ';

                    $ligne = $resultat1->fetch_all(MYSQLI_ASSOC);
                    foreach ($ligne as $list) {

                        echo '
            <tr>
                <td style="text-align: center">' . rev_date(stripslashes($list['date_sort'])) . '</td>
                <td style="text-align: center">
                    <a class="btn btn-default" data-toggle="modal" data-target="#modalConsultation' . stripslashes($list['num_sort']) . '">' . stripslashes($list['num_sort']) . '</a>
                    <div class="modal fade" id="modalConsultation' . stripslashes($list['num_sort']) . '" tabindex="-1" role="dialog">
                        <div class="modal-dialog delete" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close"><span
                                            aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title" id="modalConsultation' . stripslashes($list['num_sort']) . '">
                                        Sortie ' . stripslashes($list['num_sort']) . ' au ' . rev_date(stripslashes($list['date_sort'])) . '
                                    </h4>
                                </div>
                                <div class="modal-body">
                                    <table border="0" class="table table-hover table-bordered ">
                                        <thead>
                                        <tr>
                                            <th class="entete" style="text-align: center">Article(s) Sorti(s)</th>
                                            <th class="entete" style="text-align: center">Quantité(s) Sorite(s)</th>
                                        </tr>
                                        </thead>
                    ';

                        $sql = "SELECT num_sort, num_dsort, qte_dsort, designation_art
                                FROM details_sortie
                                  INNER JOIN articles ON details_sortie.code_art = articles.code_art
                                WHERE num_sort = '" . stripslashes($list['num_sort']) . "'
                                  ";
                        if ($result = $connexion->query($sql)) {
                            $lignes = $result->fetch_all(MYSQLI_ASSOC);
                            foreach ($lignes as $liste) {
                                $art = stripslashes($liste['designation_art']);
                                echo '
                        <tr>
                            <td>' . $art . '</td>
                            <td>' . stripslashes($liste['qte_dsort']) . '</td>
                        </tr>
                        ';
                            }
                        }
                        echo '
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
                ';
                    }
                    echo '
        </table>
    </div>
</div>
        ';
                } else
                    echo '
                <div class="col-md-6">
                    <strong>
                        <h5 style="color: #29487d"><span class="label label-info">Sorties </span></h5>
                    </strong>
                    <strong>
                        <h5 style="color: #29487d">Il n\'y a eu aucune sortie durant cette période.</h5>
                    </strong>
                </div>
                ';
            }

            echo '
</div>            
            ';
        }
    }
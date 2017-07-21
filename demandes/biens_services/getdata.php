<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 27/08/2015
     * Time: 6:58 PM
     */
    if (sizeof($_POST) > 0) {
        if (isset($_POST['opt'])) {
            $option = $_POST['opt'];
            $element = $_POST['element'];

            switch ($option) {
                case 'numero':
                    $sql = "SELECT * FROM demandes WHERE num_dbs LIKE '%" . $element . "%' ORDER BY num_dbs DESC ";
                    break;
                case 'emp':
                    $sql = "SELECT * FROM demandes WHERE code_emp LIKE '%" . $element . "%' ORDER BY num_dbs DESC ";
                    break;
                case 'date':
                    $sql = "SELECT * FROM demandes WHERE date_dbs LIKE '%" . $element . "%' ORDER BY num_dbs DESC ";
                    break;
                case 'obj':
                    $sql = "SELECT * FROM demandes WHERE objets_dbs LIKE '%" . $element . "%' ORDER BY num_dbs DESC ";
                    break;
            }

            $res = mysqli_query($connexion, $sql) or exit(mysqli_error($connexion));

            if ($res->num_rows > 0) { ?>
                <!--suppress ALL -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <img src="img/icons_1775b9/search.png" width="20" height="20">
                                Liste des Demandes - Résultats de recherche pour "<?php echo ucfirst($option); ?>"
                                contenant "<?php echo $element; ?>"
                                <a href='form_principale.php?page=form_actions&source=demandes&action=rechercher'
                                   type='button' class='close'
                                   data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                    <span aria-hidden='true'>&times;</span>
                                </a>
                            </div>
                            <div class="panel-body">
                                <table class="table table-hover table-bordered ">
                                    <thead>
                                    <tr>
                                        <th class="entete" style="text-align: center; width: 5%">Numéro</th>
                                        <th class="entete" style="text-align: center; width: 5%">Date</th>
                                        <th class="entete" style="text-align: center; width: 20%">Demandeur</th>
                                        <th class="entete" style="text-align: center; width: 20%">Objet</th>
                                        <th class="entete" style="text-align: center; width: 5%">Action</th>
                                    </tr>
                                    </thead>
                                    <?php
                                        //$req = "SELECT * FROM fournisseurs ORDER BY code_four ASC ";
                                        if ($resultat = $connexion->query($sql)) {
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
                                                        <a class="btn btn-default"
                                                           href="form_principale.php?page=demandes/biens_services/form_demandes&action=consultation&id=<?php echo stripslashes($list['num_dbs']); ?>"
                                                           title="<?php echo $str; ?>"
                                                           role="button"><?php echo stripslashes($list['num_dbs']); ?></a>
                                                    </td>
                                                    <td><?php echo rev_date($list['date_dbs']) ?></td>
                                                    <td>
                                                        <h4>
                                                            <span class="label label-primary">
                                                                <?php
                                                                    $req = "SELECT e.nom_emp, e.prenoms_emp
                                                                            FROM demandes AS d, employes AS e
                                                                            WHERE d.code_emp = e.code_emp AND e.code_emp = '" . $list['code_emp'] . "'";
                                                                    $result = $connexion->query($req);
                                                                    $emp = "RAS";
                                                                    if ($result->num_rows > 0) {
                                                                        $lines = $result->fetch_all(MYSQLI_ASSOC);
                                                                        foreach ($lines as $line)
                                                                            $emp = stripslashes($line['prenoms_emp']) . " " . stripslashes($line['nom_emp']);
                                                                    }
                                                                    echo $emp;
                                                                ?>
                                                            </span>
                                                        </h4>

                                                    </td>
                                                    <td><?php echo stripslashes($list['objets_dbs']); ?></td>
                                                    <?php //if (($_SESSION['type_utilisateur'] == 'administrateur') || ($_SESSION['type_utilisateur'] == 'moyens_genereaux')):?>
                                                    <td style="text-align: center">
                                                        <a class="btn btn-default" data-toggle="modal"
                                                           data-target="#modalSupprimer<?php echo stripslashes($list['num_dbs']); ?>">
                                                            <img height="20" width="20"
                                                                 src="img/icons_1775b9/cancel.png" title="Supprimer"/>
                                                        </a>
                                                        <div class="modal fade"
                                                             id="modalSupprimer<?php echo stripslashes($list['num_dbs']); ?>"
                                                             tabindex="-1"
                                                             role="dialog">
                                                            <div class="modal-dialog delete" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close"
                                                                                data-dismiss="modal"
                                                                                aria-label="Close"><span
                                                                                aria-hidden="true">&times;</span>
                                                                        </button>
                                                                        <h4 class="modal-title"
                                                                            id="modalSupprimer<?php echo stripslashes($list['num_dbs']); ?>">
                                                                            Confirmation</h4>
                                                                    </div>
                                                                    <div class="modal-body" style="font-size: larger">
                                                                        Voulez-vous supprimer
                                                                        la demande
                                                                        <span class="label label-primary"><?php echo stripslashes($list['num_dbs']); ?></span>
                                                                        de la base ?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button class="btn btn-default"
                                                                                data-dismiss="modal">Non
                                                                        </button>
                                                                        <button class="btn btn-primary"
                                                                                data-dismiss="modal"
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
                                        }
                                        else echo "La recherche n'a renvoyé aucun resultat."; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
            else echo "
                        <div style='width: 400px; margin-right: auto; margin-left: auto'>
                            <div class='alert alert-info alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                                <a href='form_principale.php?page=form_actions&source=demandes&action=rechercher' type='button' class='close'
                                       data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                        <span aria-hidden='true'>&times;</span>
                                    </a>
                                <strong>Desole!</strong><br/> La recherche n'a retourné aucun resultat.
                            </div>
                        </div>
                        ";
        }
    }
    else {
        if (isset($_GET['statut']))
            $statut = $_GET['statut'];
        else
            $statut = "toutes";

        include '../../fonctions.php';
        ?>
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <?php if ((isset($_GET['statut'])) && ($statut == "attente")) { ?>
                    <?php
                        $iniFile = 'config.ini';
                        //A modifier selon l'emplacement du fichier
                        $config = parse_ini_file('../../../' . $iniFile);
                        $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
                    ?>
                    <div class="panel-heading">
                        Liste - Demandes En Attente
                        <a href='form_principale.php?page=accueil' type='button' class='close'
                           data-dismiss='alert' aria-label='Close' style='position: inherit'>
                            <span aria-hidden='true'>&times;</span>
                        </a>
                    </div>
                    <div class="panel-body">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th class="entete" style="text-align: center; width: 5%">Numéro</th>
                                <th class="entete" style="text-align: center; width: 10%">Date</th>
                                <th class="entete" style="text-align: center; width: 20%">Demandeur</th>
                                <th class="entete" style="text-align: center; width: 15%">Objet</th>
                                <th class="entete" style="text-align: center; width: 5%">Action</th>
                            </tr>
                            </thead>
                            <?php
                                $req = "SELECT d.num_dbs, d.date_dbs, d.objets_dbs, e.nom_emp, e.prenoms_emp
                                    FROM demandes AS d 
                                    INNER JOIN employes AS e
                                    ON d.code_emp = e.code_emp
                                    WHERE d.statut = 'non satisfaite'
                                    ORDER BY d.date_dbs DESC";

                                if ($resultat = $connexion->query($req)) {
                                    if ($resultat->num_rows > 0) {
                                        $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                                        $i = 0;
                                        foreach ($ligne as $list) {
                                            ?>
                                            <tr>
                                                <!-- Colonne NUMERO -->
                                                <td style="text-align: center">
                                                    <?php
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
                                                    ?>

                                                    <button class="btn btn-sm btn-default"
                                                            data-toggle="modal"
                                                            data-target="#modalDemande<?php echo $i; ?>"
                                                            type="button">
                                                        <?php echo $num_demande; ?>
                                                    </button>
                                                    <!-- MODAL -->
                                                    <div class="modal fade"
                                                         id="modalDemande<?php echo $i; ?>" tabindex="-1"
                                                         role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog" style="width: 750px">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal"
                                                                            aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                    <h4 class="modal-title">
                                                                        Demande
                                                                        - <span
                                                                            class="label label-primary"><?php echo $num_demande; ?></span>
                                                                    </h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <table class="formulaire" style="width: 100%"
                                                                           border="0">
                                                                        <tr>
                                                                            <td class="champlabel">Employé :</td>
                                                                            <td class="textbox">
                                                                                <label>
                                                                                    <?php
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
                                                                                    ?>
                                                                                    <h4>
                                                                                    <span class="label label-primary">
                                                                                        <?php echo $nom_prenoms_emp; ?>
                                                                                    </span>
                                                                                    </h4>
                                                                                </label>
                                                                            </td>
                                                                            <td class="champlabel" rowspan="2">Objet :
                                                                            </td>
                                                                            <td class="textbox">
                                                                                <label>
                                                                                <textarea id="objets_dbs"
                                                                                          name="objets_dbs" rows="3"
                                                                                          class="form-control" readonly
                                                                                          style="resize: none"><?php echo stripslashes($list['objets_dbs']); ?></textarea>
                                                                                </label>
                                                                            </td>
                                                                        </tr>
                                                                    </table>

                                                                    <br/>

                                                                    <div class="row">
                                                                        <?php
                                                                            $sql = "SELECT * FROM details_demande WHERE num_dbs = '" . $num_demande . "'";
                                                                            if ($valeur = $connexion->query($sql)) {
                                                                                $ligne = $valeur->fetch_all(MYSQLI_ASSOC);
                                                                                ?>
                                                                                <div class="col-md-12">
                                                                                    <div class="panel panel-default">
                                                                                        <table border="0"
                                                                                               class="table table-hover table-bordered">
                                                                                            <thead>
                                                                                            <tr>
                                                                                                <th class="entete"
                                                                                                    style="text-align: center; width: 45%">
                                                                                                    Libellé
                                                                                                </th>
                                                                                                <th class="entete"
                                                                                                    style="text-align: center">
                                                                                                    Nature
                                                                                                </th>
                                                                                                <th class="entete"
                                                                                                    style="text-align: center">
                                                                                                    Quantité
                                                                                                </th>
                                                                                                <th class="entete"
                                                                                                    style="text-align: center; width: 30%">
                                                                                                    Observation
                                                                                                </th>
                                                                                            </tr>
                                                                                            </thead>
                                                                                            <?php
                                                                                                foreach ($ligne as $data) {
                                                                                                    ?>
                                                                                                    <tr>
                                                                                                        <td style="text-align: center; vertical-align: middle"><?php echo $data['libelle_dd']; ?></td>
                                                                                                        <td style="text-align: center; vertical-align: middle"><?php echo ucfirst($data['nature_dd']); ?></td>
                                                                                                        <td style="text-align: center; vertical-align: middle"><?php echo $data['qte_dd']; ?></td>
                                                                                                        <td style="vertical-align: middle"><?php echo $data['observations_dd']; ?></td>
                                                                                                    </tr>
                                                                                                    <?php
                                                                                                } ?>
                                                                                        </table>
                                                                                    </div>

                                                                                    <br>
                                                                                    <a role="button"
                                                                                       title="Traiter la demande"
                                                                                       href="form_principale.php?page=articles/mouvements_stock&action=sortie"
                                                                                       target="_blank"
                                                                                       class="btn btn-sm btn-default">
                                                                                        Procéder
                                                                                        <img
                                                                                            src="img/icons_1775b9/right_filled.png"
                                                                                            width="20">
                                                                                    </a>
                                                                                </div>
                                                                                <?php
                                                                            }
                                                                        ?>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- Colonne DATE -->
                                                <td style="text-align: center"><?php echo rev_date($list['date_dbs']); ?></td>

                                                <!-- Colonne DEMANDEUR -->
                                                <td style="text-align: center">
                                                    <h4>
                                                <span class="label label-primary">
                                                    <?php echo stripslashes($list['prenoms_emp']) . " " . stripslashes($list['nom_emp']); ?>
                                                </span>
                                                    </h4>
                                                </td>

                                                <!-- Colonne OBJET -->
                                                <td><?php echo stripslashes($list['objets_dbs']); ?></td>

                                                <!-- Colonne ACTION -->
                                                <td style="text-align: center">
                                                    <a class="btn btn-default" data-toggle="modal"
                                                       data-target="#modalSupprimer<?php echo $num_demande; ?>">
                                                        <img height="20" width="20" src="img/icons_1775b9/cancel.png"
                                                             title="Supprimer"/>
                                                    </a>
                                                    <div class="modal fade"
                                                         id="modalSupprimer<?php echo $num_demande; ?>"
                                                         tabindex="-1"
                                                         role="dialog">
                                                        <div class="modal-dialog delete" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal"
                                                                            aria-label="Close"><span
                                                                            aria-hidden="true">&times;</span>
                                                                    </button>
                                                                    <h4 class="modal-title"
                                                                        id="modalSupprimer<?php echo $num_demande; ?>">
                                                                        Confirmation</h4>
                                                                </div>
                                                                <div class="modal-body" style="font-size: larger">
                                                                    Voulez-vous supprimer
                                                                    la demande <span class="label label-primary"><?php echo $num_demande; ?></span> de la
                                                                    base ?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button class="btn btn-default"
                                                                            data-dismiss="modal">Non
                                                                    </button>
                                                                    <button class="btn btn-primary" data-dismiss="modal"
                                                                            onclick="suppressionInfos('<?php echo $num_demande; ?>')">
                                                                        Oui
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                            $i++;
                                        }
                                    }
                                    else { ?>
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

                <?php }
                    else if ((isset($_GET['statut'])) && ($statut == "partielle")) { ?>
                <?php
                    $iniFile = 'config.ini';
                    //A modifier selon l'emplacement du fichier
                    $config = parse_ini_file('../../../' . $iniFile);
                    $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
                ?>
                    <div class="panel-heading">
                        Liste - Demandes Partielles
                        <a href='form_principale.php?page=accueil' type='button' class='close'
                           data-dismiss='alert' aria-label='Close' style='position: inherit'>
                            <span aria-hidden='true'>&times;</span>
                        </a>
                    </div>
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
                            <?php
                                $req = "SELECT d.num_dbs, d.date_dbs, d.objets_dbs, e.nom_emp, e.prenoms_emp
                                    FROM demandes AS d 
                                    INNER JOIN employes AS e
                                    ON d.code_emp = e.code_emp
                                    WHERE d.statut = 'partielle'
                                    ORDER BY d.date_dbs DESC";

                                if ($resultat = $connexion->query($req)) {
                                    if ($resultat->num_rows > 0) {
                                        $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                                        $i = 0;
                                        foreach ($ligne as $list) {
                                            ?>
                                            <tr>
                                                <!-- Colonne NUMERO -->
                                                <td style="text-align: center">
                                                    <?php
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
                                                    ?>

                                                    <button class="btn btn-sm btn-default"
                                                            data-toggle="modal"
                                                            data-target="#modalDemande<?php echo $i; ?>"
                                                            type="button">
                                                        <?php echo $num_demande; ?>
                                                    </button>
                                                    <!-- MODAL -->
                                                    <div class="modal fade"
                                                         id="modalDemande<?php echo $i; ?>" tabindex="-1"
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
                                                                        - <span
                                                                            class="label label-primary"><?php echo $num_demande; ?></span>
                                                                    </h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <table class="formulaire" style="width: 100%"
                                                                           border="0">
                                                                        <tr>
                                                                            <td class="champlabel">Employé :</td>
                                                                            <td class="textbox">
                                                                                <label>
                                                                                    <?php
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
                                                                                    ?>
                                                                                    <h4>
                                                                                    <span class="label label-primary">
                                                                                        <?php echo $nom_prenoms_emp; ?>
                                                                                    </span>
                                                                                    </h4>
                                                                                </label>
                                                                            </td>
                                                                            <td class="champlabel" rowspan="2">Objet :
                                                                            </td>
                                                                            <td class="textbox">
                                                                                <label>
                                                                                <textarea id="objets_dbs"
                                                                                          name="objets_dbs" rows="3"
                                                                                          class="form-control" readonly
                                                                                          style="resize: none"><?php echo stripslashes($list['objets_dbs']); ?></textarea>
                                                                                </label>
                                                                            </td>
                                                                        </tr>
                                                                    </table>

                                                                    <br/>

                                                                    <div class="row">
                                                                        <?php
                                                                            $sql = "SELECT * FROM details_demande WHERE num_dbs = '" . $num_demande . "'";
                                                                            if ($valeur = $connexion->query($sql)) {
                                                                                $ligne = $valeur->fetch_all(MYSQLI_ASSOC);
                                                                                ?>
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
                                                                                            <?php
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
                                                                                                } ?>
                                                                                        </table>
                                                                                    </div>

                                                                                    <br>
                                                                                    <a role="button"
                                                                                       title="Traiter la demande"
                                                                                       href="form_principale.php?page=articles/mouvements_stock&action=sortie"
                                                                                       target="_blank"
                                                                                       class="btn btn-sm btn-default">
                                                                                        Procéder
                                                                                        <img
                                                                                            src="img/icons_1775b9/right_filled.png"
                                                                                            width="20">
                                                                                    </a>
                                                                                </div>
                                                                                <?php
                                                                            }
                                                                        ?>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- Colonne DATE -->
                                                <td style="text-align: center"><?php echo rev_date($list['date_dbs']); ?></td>

                                                <!-- Colonne DEMANDEUR -->
                                                <td style="text-align: center">
                                                    <h4>
                                                    <span class="label label-primary">
                                                        <?php echo stripslashes($list['prenoms_emp']) . " " . stripslashes($list['nom_emp']); ?>
                                                    </span>
                                                    </h4>
                                                </td>

                                                <!-- Colonne OBJET -->
                                                <td><?php echo stripslashes($list['objets_dbs']); ?></td>
                                            </tr>
                                            <?php
                                            $i++;
                                        }
                                    }
                                    else { ?>
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

                <?php }
                    else if ((isset($_GET['statut'])) && ($statut == "toutes")) { ?>
                    <div class="panel-heading">
                        Liste Des Demandes
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="jumbotron info">
                                    <table>
                                        <tr>
                                            <td>
                                                <table>
                                                    <tr>
                                                        <td>
                                                            <p style="font-size: small">Veuillez spécifier la
                                                                période.</p>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <table class="formulaire" style="margin-left: auto; margin-right: auto">
                                                    <tr>
                                                        <td class="champlabel">Du:</td>
                                                        <td>
                                                            <label>
                                                                <input type="text" name="datedebut"
                                                                       id="date_d" readonly required
                                                                       title="Veuillez cliquer ici pour sélectionner une date"
                                                                       class="form-control"/>
                                                            </label>
                                                        </td>
                                                        <td class="champlabel">Au:</td>
                                                        <td>
                                                            <label>
                                                                <input type="text" name="datefin"
                                                                       class="form-control" id="date_f" readonly
                                                                       title="Veuillez cliquer ici pour sélectionner une date"
                                                                       required/>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-default" id="valider"
                                                                    style="margin-left: 5px">
                                                                <span class="ui-icon ui-icon-circle-triangle-e"></span>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td style="padding-left: 10px; vertical-align: top">
                                                <img src="img/icons_1775b9/info.png" height="40" width="40">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div id="feedback_"></div>
                        </div>
                    </div>

                    <script>
                        $(document).ready(function () {
                            $('#date_d').datepicker({dateFormat: 'dd-mm-yy'});
                            $('#date_f').datepicker({dateFormat: 'dd-mm-yy'});
                        });

                        $('#valider').click(function () {
                            var date_debut = $('#date_d').val();
                            var date_fin = $('#date_f').val();

                            console.log(date_debut + "_" + date_fin);
                            if ((date_debut != "") && (date_fin != "")) {
                                if (date_fin > date_debut) {
                                    $.ajax({
                                        type: 'POST',
                                        url: 'demandes/biens_services/ajax_liste_demandes.php',
                                        data: {
                                            debut: date_debut,
                                            fin: date_fin
                                        },
                                        success: function (resultat) {
                                            $('#feedback_').html(resultat);
                                        }
                                    });
                                } else
                                    alert("La date de début doit être antérieure à celle de fin.");
                            } else
                                alert("Veuillez sélectionner un intervalle de date.");
                        });
                    </script>
                <?php } ?>
            </div>
        </div>
        <?php
    }
?>
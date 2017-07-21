<?php /*if ($_SESSION['type_utilisateur'] == 'moyens_genereaux') : */?><!--
<!--suppress ALL -->
<script>
    $(document).ready(function () {
        $(".slider-attente").slick({
            autoplay: true,
            autoplaySpeed: 1500,
            vertical: false,
            arrows: false,
        });

        $(".slider-partielle").slick({
            autoplay: true,
            autoplaySpeed: 1500,
            vertical: false,
            arrows: false,
        });

        $(".slider-rupture").slick({
            autoplay: true,
            autoplaySpeed: 1500,
            vertical: true,
            arrows: false,
            slidesToShow: 2
        });

        $(".slider-stock").slick({
            autoplay: true,
            autoplaySpeed: 1500,
            vertical: true,
            arrows: false,
            slidesToShow: 3
        });

        $(".slider-mouvement").slick({
            autoplay: true,
            autoplaySpeed: 1500,
            vertical: true,
            arrows: false,
            slidesToShow: 2
        });
    })
</script>
<div class="container-fluid">
    <div class="row" style="color: #29487d">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <img src="img/icons_1775b9/administrative_tools.png" width="20"> Tableau de Bord
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="panel panel-default" style="margin-bottom: 10px">
                                <div class="panel-heading">
                                    <img src="img/icons_1775b9/box.png" width="20"> Etat des stocks
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>
                                                <h5>A la date de ce jour :</h5>
                                            </strong>

                                            <div class="panel panel-default" style="margin-bottom: 10px">
                                                <div class="panel-body">
                                                    <div class="slider-stock">
                                                        <?php
                                                            $connexion = db_connect();
                                                            
                                                            $sql = "SELECT * FROM articles";
                                                            if ($resultat = $connexion->query($sql)) {
                                                                if ($resultat->num_rows > 0) {
                                                                    $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                                                                    foreach ($ligne as $list) {
                                                                        ?>
                                                                        <div style="margin-bottom: 2px">
                                                                            <?php echo stripslashes($list['designation_art']) . " <span class=\"label label-info\">" . stripslashes($list['stock_art']) . "</span>"; ?>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                } else { ?>
                                                                    <div>
                                                                        <strong>RAS</strong>
                                                                    </div>
                                                                <?php }
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>
                                                <h5>Seuil minimum :</h5>
                                            </strong>

                                            <div class="panel panel-default" style="margin-bottom: 10px">
                                                <div class="panel-body">
                                                    <div class="slider-stock">
                                                        <?php
                                                            $sql = "SELECT * FROM articles WHERE stock_art = niveau_reappro_art OR stock_art < niveau_reappro_art";
                                                            if ($resultat = $connexion->query($sql)) {
                                                                if ($resultat->num_rows > 0) {
                                                                    $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                                                                    foreach ($ligne as $list) {
                                                                        ?>
                                                                        <div style="margin-bottom: 2px">
                                                                            <?php echo stripslashes($list['designation_art']) . " <span class=\"label label-warning\">" . stripslashes($list['stock_art']) . "</span>"; ?>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                } else { ?>
                                                                    <div>
                                                                        <strong>RAS</strong>
                                                                    </div>
                                                                <?php }
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <strong>
                                                <h5>Rupture de stock :</h5>
                                            </strong>

                                            <div class="col-md-9">
                                                <div class="panel panel-default" style="margin-bottom: 10px">
                                                    <div class="panel-body">
                                                        <div class="slider-rupture">
                                                            <?php
                                                                $sql = "SELECT * FROM articles WHERE stock_art = 0";
                                                                if ($resultat = $connexion->query($sql)) {
                                                                    if ($resultat->num_rows > 0) {
                                                                        $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                                                                        $statut = "rupture";
                                                                        foreach ($ligne as $list) {
                                                                            ?>
                                                                            <div style="margin-bottom: 2px">
                                                                                <span class="label label-danger">
                                                                                    <?php echo stripslashes($list['designation_art']); ?>
                                                                                </span>
                                                                            </div>
                                                                            <?php
                                                                        }
                                                                    } else { ?>
                                                                        <div>
                                                                            <strong>RAS</strong>
                                                                        </div>
                                                                    <?php }
                                                                }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if (isset($statut) && ($statut == "rupture")) { ?>
                                                <a class="btn btn-sm btn-default" title="Consulter la liste des articles"
                                                   href="form_principale.php?page=articles/liste_articles&statut=rupture" style="margin-top: 40px"
                                                   role="button">Consulter<img src="img/icons_1775b9/right_filled.png" width="20"></a>
                                            <?php } ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="row">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <img src="img/icons_1775b9/agreement_1.png" width="20"> Demandes
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?php
                                                    $sql = "SELECT COUNT(*) FROM demandes WHERE statut = 'non satisfaite'";
                                                    $nbr = 0;
                                                    if ($resultat = $connexion->query($sql)) {
                                                        $ligne = $resultat->fetch_all(MYSQLI_NUM);
                                                        foreach ($ligne as $list) {
                                                            $nbr = $list[0];
                                                        }
                                                    }
                                                    if ($nbr > 0) {
                                                        ?>
                                                        <strong>
                                                            <h5>En Attente <span class="label label-info"><?php echo $nbr; ?></span> :</h5>
                                                        </strong>
                                                        <div class="panel panel-default" style="margin-bottom: 5%">
                                                            <div class="panel-body">
                                                                <div class="slider-attente">
                                                                    <?php
                                                                        $sql = "SELECT * FROM demandes WHERE statut = 'non satisfaite'";
                                                                        if ($resultat = $connexion->query($sql)) {
                                                                            $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                                                                            foreach ($ligne as $list) {
                                                                                ?>
                                                                                <div
                                                                                    ><?php echo stripslashes($list['num_dbs']); ?></div>
                                                                                <?php
                                                                            }
                                                                        }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <a class="btn btn-sm btn-default" 
                                                           href="form_principale.php?page=demandes/biens_services/liste_demandes&statut=attente"
                                                           role="button">Consulter<img src="img/icons_1775b9/right_filled.png" width="20"></a>

                                                    <?php }
                                                    else { ?>
                                                        <strong>
                                                            <h5>En Attente :</h5>
                                                        </strong>
                                                        <div class="panel panel-default">
                                                            <div class="panel-body">
                                                                <p style="text-align: center; ">Il n'y a en ce moment
                                                                    aucune
                                                                    demande en attente.</p>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?php
                                                    $sql = "SELECT COUNT(*) FROM demandes WHERE statut = 'partielle'";
                                                    $nbr = 0;
                                                    if ($resultat = $connexion->query($sql)) {
                                                        $ligne = $resultat->fetch_all(MYSQLI_NUM);
                                                        foreach ($ligne as $list) {
                                                            $nbr = $list[0];
                                                        }
                                                    }
                                                    if ($nbr > 0) {
                                                        ?>
                                                        <strong>
                                                            <h5 title="Il s'agit ici des demandes partiellement satisfaites">
                                                                Partielles <span class="label label-info"><?php echo $nbr; ?></span> :</h5>
                                                        </strong>
                                                        <div class="panel panel-default" style="margin-bottom: 5%">
                                                            <div class="panel-body">
                                                                <div class="slider-partielle">
                                                                    <?php
                                                                        $sql = "SELECT * FROM demandes WHERE statut = 'partielle'";
                                                                        if ($resultat = $connexion->query($sql)) {
                                                                            $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                                                                            foreach ($ligne as $list) {
                                                                                ?>
                                                                                <div><?php echo stripslashes($list['num_dbs']); ?></div>
                                                                                <?php
                                                                            }
                                                                        }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <a class="btn btn-sm btn-default"
                                                           href="form_principale.php?page=demandes/biens_services/liste_demandes&statut=partielle"
                                                           role="button">Consulter<img src="img/icons_1775b9/right_filled.png" width="20"></a>

                                                    <?php }
                                                    else { ?>
                                                        <strong>
                                                            <h5>Partielles :</h5>
                                                        </strong>
                                                        <div class="panel panel-default">
                                                            <div class="panel-body">
                                                                <p style="text-align: center; ">Il n'y a en ce moment
                                                                    aucune
                                                                    demande partielle.</p>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-9">
                            <div class="panel panel-default" style="margin-bottom: 10px">
                                <div class="panel-heading">
                                    <img src="img/icons_1775b9/box_filled_2.png" width="20"> Mouvements de Stock
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <!-- TODO: interroger la BD sur la dernière entrée d'articles -->
                                            <?php
                                                $sql = "SELECT MAX(date_entr) FROM entrees_stock";
                                                if ($resultat = $connexion->query($sql)) {
                                                    $ligne = $resultat->fetch_all(MYSQLI_NUM);
                                                    foreach ($ligne as $list) {
                                                        $dat = $list[0];
                                                    }
                                                    if ($dat <> "") {
                                                        $date_entr = date('j/m/Y', strtotime($dat));
                                                        echo '
                                                        <strong>
                                                            <h4 title="La dernière entrée en date"><span class="label label-primary">Entrées
                                                                au ' . $date_entr . '</span></h4>
                                                        </strong>
                                                        ';
                                                    }
                                                    else
                                                        echo '
                                                    <strong>
                                                        <h5 title="La dernière entrée en date">Aucune entrée n\'a été enregistrée.</h5>
                                                    </strong>
                                                    ';
                                                }?>

                                            <div class="panel panel-default" style="margin-bottom: 3%">
                                                <div class="panel-body">
                                                    <div class="slider-mouvement">
                                                        <?php
                                                            $sql = "SELECT A.designation_art, D.qte_dentr FROM articles AS A
                                                                    INNER JOIN details_entree AS D
                                                                    ON A.code_art = D.code_art
                                                                    INNER JOIN entrees_stock AS E
                                                                    ON D.num_entr = E.num_entr
                                                                    WHERE E.date_entr = '$dat'";
                                                            if ($resultat = $connexion->query($sql)) {
                                                                if ($resultat->num_rows > 0) {
                                                                    $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                                                                    foreach ($ligne as $list) { ?>
                                                                        <div><?php echo stripslashes($list['designation_art']) . " <img src='img/icons_1775b9/reply_arrow.png' width='20' style='margin-bottom: 5px'> <span class=\"label label-success\">" . stripslashes($list['qte_dentr']) . "</span>" ?></div>
                                                                    <?php }
                                                                } else { ?>
                                                                    <div>
                                                                        <strong>RAS</strong>
                                                                    </div>
                                                                <?php }
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <a class="btn btn-sm btn-default"
                                               href="form_principale.php?page=demandes/biens_services/liste_demandes&statut=attente"
                                               role="button">Consulter<img src="img/icons_1775b9/right_filled.png" width="20"></a>
                                        </div>

                                        <div class="col-md-6">
                                            <!-- TODO: interroger la BD sur la dernière sortie d'articles -->
                                            <?php
                                                $sql = "SELECT max(date_sort) FROM sorties_stock";
                                                if ($resultat = $connexion->query($sql)) {
                                                    $ligne = $resultat->fetch_all(MYSQLI_NUM);
                                                    foreach ($ligne as $list) {
                                                        $dat = $list[0];
                                                    }
                                                    if ($dat <> "") {
                                                        $date_sort = date('j/m/Y', strtotime($dat));
                                                        echo '
                                                        <strong>
                                                            <h4 title="La dernière sortie en date"><span class="label label-primary">Sorties
                                                                au ' . $date_sort . '</span></h4>
                                                        </strong>
                                                        ';
                                                    }
                                                    else
                                                        echo '
                                                    <strong>
                                                        <h5 title="La dernière sortie en date">Aucune sortie n\'a été enregistrée.</h5>
                                                    </strong>
                                                    ';
                                                }
                                            ?>

                                            <div class="panel panel-default" style="margin-bottom: 3%">
                                                <div class="panel-body">
                                                    <div class="slider-mouvement">
                                                        <?php
                                                            $sql = "SELECT A.designation_art, D.qte_dsort FROM articles AS A
                                                                    INNER JOIN details_sortie AS D
                                                                    ON A.code_art = D.code_art
                                                                    INNER JOIN sorties_stock AS S
                                                                    ON D.num_sort = S.num_sort
                                                                    WHERE S.date_sort = '$dat'";
                                                            if ($resultat = $connexion->query($sql)) {
                                                                if ($resultat->num_rows > 0) {
                                                                    $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                                                                    foreach ($ligne as $list) { ?>
                                                                        <div><?php echo stripslashes($list['designation_art']) . " <img src='img/icons_1775b9/forward_arrow.png' width='20' style='margin-bottom: 5px'> <span class=\"label label-danger\">" . stripslashes($list['qte_dsort']) . "</span>" ?></div>
                                                                    <?php }
                                                                } else { ?>
                                                                    <div>
                                                                        <strong>RAS</strong>
                                                                    </div>
                                                                <?php }
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <a class="btn btn-sm btn-default"
                                               href="form_principale.php?page=demandes/biens_services/liste_demandes&statut=attente"
                                               role="button">Consulter<img src="img/icons_1775b9/right_filled.png" width="20"></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php //elseif ($_SESSION['type_utilisateur'] == 'moyens_genereaux') : ?>

<?php /*elseif ($_SESSION['type_utilisateur'] == 'normal') : */?>

    <!-- Voir le fichier note-->

<?php /*endif; */?>
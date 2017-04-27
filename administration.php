<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 22-Oct-15
     * Time: 11:21 AM
     */
    $source = $_GET['source'];
//    echo $source;
?>

<?php if ($source == 'employes') : ?>
    <!--suppress ALL -->
    <div class="col-md-10 col-lg-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <img src="img/icons_1775b9/electronic_id.png" width="20"> Administration - Employés
                <a href='form_principale.php?page=accueil' type='button' class='close'
                   data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </a>
            </div>
            <div class="panel-body">
                <table class="formulaire" border="0" style="width: 100%">
                    <tr>
                        <td>
                            <a class="a-minimenu"
                               href="form_principale.php?page=employes/form_employes">
                                <div class="btn-minimenu">
                                    <img src="img/icons_1775b9/add_user.png" width="30" height="30">
                                    Ajouter
                                </div>
                            </a>
                        </td>
                        <td>
                            <a class="a-minimenu"
                               href="form_principale.php?page=form_actions&source=employes&action=modifier">
                                <div class="btn-minimenu">
                                    <img src="img/icons_1775b9/edit_user.png" width="30" height="30">
                                    Modifier
                                </div>
                            </a>
                        </td>
                        <td>
                            <a class="a-minimenu"
                               href="form_principale.php?page=form_actions&source=employes&action=supprimer">
                                <div class="btn-minimenu">
                                    <img src="img/icons_1775b9/remove_user.png" width="30" height="30">
                                    Supprimer
                                </div>
                            </a>
                        </td>
                        <td>
                            <a class="a-minimenu"
                               href="form_principale.php?page=employes/liste_employes">
                                <div class="btn-minimenu">
                                    <img src="img/icons_1775b9/permanent_job.png" width="30" height="30">
                                    Liste
                                </div>
                            </a>
                        </td>
                        <td>
                            <a class="a-minimenu"
                               href="form_principale.php?page=form_actions&source=employes&action=rechercher">
                                <div class="btn-minimenu">
                                    <img src="img/icons_1775b9/find_user.png" width="30" height="30">
                                    Rechercher
                                </div>
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

<?php elseif ($source == 'fournisseurs') : ?>
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <img src="img/icons_1775b9/product.png" width="20" height="20">
                Administration - Fournisseurs
                <a href='form_principale.php?page=accueil' type='button' class='close'
                   data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </a>
            </div>
            <div class="panel-body">
                <table class="formulaire" border="0" style="width: 100%">
                    <tr>
                        <td>
                            <a class="a-minimenu"
                               href="form_principale.php?page=fournisseurs/form_fournisseurs">
                                <div class="btn-minimenu">
                                    <img src="img/icons_1775b9/conference_call.png" width="30" height="30">
                                    Ajouter
                                </div>
                            </a>
                        </td>
                        <td>
                            <a class="a-minimenu"
                               href="form_principale.php?page=form_actions&source=fournisseurs&action=modifier">
                                <div class="btn-minimenu">
                                    <img src="img/icons_1775b9/edit_file.png" width="30" height="30">
                                    Modifier
                                </div>
                            </a>
                        </td>
                        <td>
                            <a class="a-minimenu"
                               href="form_principale.php?page=form_actions&source=fournisseurs&action=supprimer">
                                <div class="btn-minimenu">
                                    <img src="img/icons_1775b9/delete.png" width="30" height="30">
                                    Supprimer
                                </div>
                            </a>
                        </td>
                        <td>
                            <a class="a-minimenu"
                               href="form_principale.php?page=fournisseurs/liste_fournisseurs">
                                <div class="btn-minimenu">
                                    <img src="img/icons_1775b9/bon.png" width="30" height="30">
                                    Liste
                                </div>
                            </a>
                        </td>
                        <td>
                            <a class="a-minimenu"
                               href="form_principale.php?page=form_actions&source=fournisseurs&action=rechercher">
                                <div class="btn-minimenu">
                                    <img src="img/icons_1775b9/search.png" width="30" height="30">
                                    Rechercher
                                </div>
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

<?php elseif ($source == 'utilisateurs') : ?>
    <div class="col-md-9" style="margin-left: 12.66%">
        <div class="panel panel-default">
            <div class="panel-heading">
                <img src="img/icons_1775b9/circled_user.png" width="20"> Utilisateurs
                <a href='form_principale.php?page=accueil' type='button' class='close'
                   data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </a>
            </div>
            <div class="panel-body">
                <table class="formulaire" border="0" style="width: 100%">
                    <tr>
                        <td>
                            <a class="a-minimenu" onclick="ajouter();">
                                <div class="btn-minimenu">
                                    <img src="img/icons_1775b9/key_exchange.png" width="30" height="30">
                                    Ajouter
                                </div>
                            </a>
                        </td>
                        <td>
                            <a class="a-minimenu" onclick="modifier();">
                                <div class="btn-minimenu">
                                    <img src="img/icons_1775b9/edit.png" width="30" height="30">
                                    Modifier
                                </div>
                            </a>
                        </td>
                        <td>
                            <a class="a-minimenu" onclick="lister();">
                                <div class="btn-minimenu">
                                    <img src="img/icons_1775b9/user_groups.png" width="30" height="30">
                                    Liste
                                </div>
                            </a>
                        </td>
                        <td>
                            <a class="a-minimenu" onclick="rechercher();">
                                <div class="btn-minimenu">
                                    <img src="img/icons_1775b9/find_user.png" width="30" height="30">
                                    Rechercher
                                </div>
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="feedback"></div>
    </div>

<?php endif ?>

    <script>
        function ajouter() {
            //alert("Scripting...");
            $.ajax({
                type: "POST",
                url: "utilisateurs/form_utilisateurs.php",
                data: {
                    action: "ajout"
                },
                success: function (resultat) {
                    $(".feedback").html(resultat);
                }
            })
        }

        function modifier() {
            $.ajax({
                type: "POST",
                url: "utilisateurs/form_utilisateurs.php",
                data: {
                    action: "modifier"
                },
                success: function (resultat) {
                    $(".feedback").html(resultat);
                }
            })
        }

        function lister() {
            $.ajax({
                type: "POST",
                url: "utilisateurs/form_utilisateurs.php",
                data: {
                    action: "liste"
                },
                success: function (resultat) {
                    $(".feedback").html(resultat);
                }
            })
        }

        function rechercher() {
            $.ajax({
                type: "POST",
                url: "utilisateurs/form_utilisateurs.php",
                data: {
                    action: "rechercher",
                },
                success: function (resultat) {
                    $(".feedback").html(resultat);
                }
            })
        }
    </script>

<?php
    if (sizeof($_POST) > 0) {

        $connexion = db_connect();

        if (isset($_POST['validation']) && (($_POST['validation'] == "valider ajout") || ($_POST['validation'] == "valider modifier"))) {
            $droit = $_POST['compte'];
            $employe = $_POST['emp'];
            $sql = "UPDATE employes SET code_droit = (SELECT code_droit FROM droits WHERE libelle_droit = '$droit') WHERE code_emp = '$employe'";

            print_r($connexion);
            if ($res = mysqli_query($connexion, $sql)) {
                header('Location:form_principale.php?page=administration&source=utilisateurs');
            }
            else echo $sql;
        }
    }
?>
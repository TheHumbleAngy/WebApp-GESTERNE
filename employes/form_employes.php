<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 15-Sep-15
     * Time: 9:17 AM
     */
?>
<!--suppress ALL -->
<div class="col-md-9" style="margin-left: 12.66%">
    <div class="panel panel-default">
        <div class="panel-heading">
            <img src="img/icons_1775b9/add_user.png" width="20" height="20">
            Formulaire Employé
            <a href='form_principale.php?page=administration&source=employes' type='button' class='close'
               data-dismiss='alert' aria-label='Close' style='position: inherit'>
                <span aria-hidden='true'>&times;</span>
            </a>
        </div>
        <div class="panel-body">
            <form method="POST" id="myform">
                <table class="formulaire" style="width: 100%; border-collapse: separate; border-spacing: 8px"
                       border="0">
                    <tr>
                        <td class="champlabel">*Titre :</td>
                        <td>
                            <label>
                                <select name="titre_emp" id="titre_emp" class="form-control" required>
                                    <option disabled selected></option>
                                    <option value="M.">M.</option>
                                    <option value="Mme.">Mme.</option>
                                    <option value="Mlle.">Mlle.</option>
                                </select>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="champlabel">*Nom :</td>
                        <td>
                            <label>
                                <input type="text" name="nom_emp" id="nom_emp" required class="form-control"
                                       onblur="this.value = this.value.toUpperCase();"/>
                            </label>
                        </td>
                        <td class="champlabel">Prénoms :</td>
                        <td>
                            <label>
                                <input type="text" name="prenoms_emp" id="prenoms_emp" size="40" class="form-control"
                                       onblur="this.value = this.value.toUpperCase();"/>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="champlabel">Fonction :</td>
                        <td>
                            <label>
                                <input type="text" name="fonction_emp" id="fonction_emp" class="form-control"
                                       onblur="this.value = this.value.toUpperCase();"/>
                            </label>
                        </td>
                        <td class="champlabel">*Département :</td>
                        <td>
                            <label>
                                <select name="departement_emp" id="departement_emp" required class="form-control">
                                    <option disabled selected></option>
                                    <option value="ADMINISTRATION">ADMINISTRATION</option>
                                    <option value="FINANCE ET COMPTABILITE">FINANCE ET COMPTABILITE</option>
                                    <option value="INFORMATIQUE">INFORMATIQUE</option>
                                    <option value="MOYENS GENEREAUX">MOYENS GENEREAUX</option>
                                    <option value="PRODUCTION">PRODUCTION</option>
                                </select>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="champlabel">*E-mail :</td>
                        <td>
                            <label>
                                <input type="email" name="email_emp" id="email_emp" size="30" required
                                       class="form-control"/>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="champlabel">*Contact :</td>
                        <td>
                            <label>
                                <input type="tel" name="tel_emp" id="tel_emp" required class="form-control"/>
                            </label>
                        </td>
                    </tr>
                </table>
                <br/>

                <div style="text-align: center;">
                    <button class="btn btn-info" type="button" name="valider"
                            style="width: 150px" onclick="ajout()">
                        Valider
                    </button>
                    <div class="modal fade" id="modal-success" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close"><span
                                            aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title" style="color: #0e76bc">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                        Message
                                    </h4>
                                </div>
                                <div class="modal-body">
                                    <h5>L'employé a été enregistré avec succès.</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="modal-warning" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close"><span
                                            aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title" style="color: red">
                                        <span class="glyphicon glyphicon-exclamation-sign"></span>
                                        Message
                                    </h4>
                                </div>
                                <div class="modal-body">
                                    <h5>
                                        Veuillez renseigner tous les champs précédés de "*".
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="info"></div>
            </form>

            <div class="container">
                <table id="table"
                       data-toggle="table"
                       data-url="employes/infos_employes.php"
                       data-height="288"
                       data-pagination="true"
                       data-page-size="4"
                       data-show-pagination-switch="true"
                       data-show-refresh="true"
                       data-search="true">
                    <thead>
                    <tr>
                        <th data-field="code_emp" data-sortable="true">Matricule</th>
                        <th data-field="nom_emp" data-sortable="true">Nom</th>
                        <th data-field="prenoms_emp" data-sortable="true">Prénoms</th>
                        <th data-field="departement_emp">Département</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    var email = ["a", "b"];

    function emailEmployés() {
        $.ajax({
            url: "employes/email_employes.php",
            dataType: "json",
            type: "GET",
            success: function (data) {
                for (var i = 0; i < data.length; i += 1) {
                    email[i] = data[i].email_emp;
                }
            }
        })
    }

    $(document).ready(emailEmployés());

    $('#email_emp').bind('blur', function () {
        if (email.indexOf(this.value) > -1) {
            alert("Cette addresse existe déjà dans la base.");
            this.value = "";
        }
    });

    function validation() {
        var i = 0;
        $(':input[required]').each(function () {
            if (this.value == '')
                i++;
        });
        return i;
    }

    function ajout() {
        if (validation() != 0) {
            $('#modal-warning').modal('show');
        } else {
            var titre_emp = $('#titre_emp').val();
            var nom_emp = $('#nom_emp').val();
            var prenoms_emp = $('#prenoms_emp').val();
            var fonction_emp = $('#fonction_emp').val();
            var departement_emp = $('#departement_emp').val();
            var email = $('#email_emp').val();
            var tel = $('#tel_emp').val();

            var infos = "titre_emp=" + titre_emp + "&nom_emp=" + nom_emp + "&prenoms_emp=" + prenoms_emp + "&fonction_emp=" + fonction_emp + "&departement_emp=" + departement_emp + "&email_emp=" + email + "&tel_emp=" + tel;
            var operation = "ajout";

            $.ajax({
                type: 'POST',
                url: 'employes/updatedata.php?operation=' + operation,
                data: infos,
                success: function (data) {
//                    $('#info').html(data);
                    $('#myform').trigger('reset');
                    $('#modal-success').modal('show');
                    emailEmployés();
                }
            });
        }
    }
</script>
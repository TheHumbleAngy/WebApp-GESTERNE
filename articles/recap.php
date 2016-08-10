<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 30/06/2016
     * Time: 14:35
     */
?>
<!--suppress ALL -->
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                Récap. Entrées/Sorties
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="jumbotron info">
                            <table>
                                <tr>
                                    <td>
                                        <table border="0">
                                            <tr>
                                                <td>
                                                    <p style="font-size: small">Veuillez spécifier la période.</p>
                                                </td>

                                            </tr>
                                        </table>
                                        <table class="formulaire" border="0" style="margin-left: auto; margin-right: auto">
                                            <tr>
                                                <td class="champlabel">Du: </td>
                                                <td>
                                                    <label>
                                                        <input type="text" name="datedebut"
                                                               id="date_d" readonly required
                                                               title="Veuillez cliquer ici pour sélectionner une date"
                                                               class="form-control"/>
                                                    </label>
                                                </td>
                                                <td class="champlabel">Au: </td>
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

                    <div class="feedback"></div>
                </div>
            </div>
        </div>
    </div>


<script>
    $(document).ready(function () {
        $('#date_d').datepicker({dateFormat: 'yy-mm-dd'});
        $('#date_f').datepicker({dateFormat: 'yy-mm-dd'});
    });

    function afficherInfos() {
        $.ajax({
            type: 'GET',
            url: 'articles/getdata.php',
            success: function (data) {
                $('#feedback').html(data);
            }
        });
    }

    $('#valider').click(function () {
        var date_debut = $('#date_d').val();
        var date_fin = $('#date_f').val();

        if ((date_debut != "") && (date_fin != "")){
            if (date_fin > date_debut) {
//                alert(date_debut + " " + date_fin);
                $.ajax({
                    type: 'POST',
                    url: 'articles/ajax_recap.php',
                    data: {
                        debut: date_debut,
                        fin: date_fin
                    },
                    success: function (resultat) {
                        $('.feedback').html(resultat);
                    }
                });
            } else
                alert("La date de début doit être antérieure à celle de fin.");
        } else
            alert("Veuillez sélectionner un intervalle de date.")
    })
</script>
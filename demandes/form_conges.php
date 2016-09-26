<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 21/09/2016
     * Time: 11:44
     */
?>
<!--suppress ALL -->
<div class="col-md-10 col-md-offset-1">
    <div class="panel panel-default">
        <div class="panel-heading">
            Demande de Congés
            <a href='form_principale.php?page=accueil' type='button'
               class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                <span aria-hidden='true'>&times;</span>
            </a>
        </div>
        <div class="panel-body">
            <form id="myform">
                <table class="formulaire" style="width: 100%" border="0">
                    <tr>
                        <td colspan="4"></td>
                        <td rowspan="3" class="champlabel">
                            <img src="img/icons_1775b9/clock_96.png" width="104">
                        </td>
                    </tr>
                    <tr>
                        <td class="champlabel" title="Date de début">Début :
                        </td>
                        <td>
                            <label>
                                <input type="text" id="date_debut"
                                       required title="Veuillez cliquer ici pour sélectionner une date"
                                       readonly
                                       class="form-control"/>
                            </label>
                        </td>
                        <td class="champlabel" title="Date de fin">Fin :</td>
                        <td>
                            <label>
                                <input type="text" id="date_fin"
                                       required title="Veuillez cliquer ici pour sélectionner une date"
                                       readonly
                                       class="form-control"/>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="champlabel">Nombre de jours :</td>
                        <td>
                            <label>
                                <input type="text" id="duree" class="form-control" size="3" readonly>
                            </label>
                        </td>
                    </tr>
                </table>
                <br>
                <div id="info"></div>
                <div style="text-align: center;">
                    <button class="btn btn-info" type="button" id="valider"
                            onclick="ajout();"
                            data-toggle="modal"
                            data-target="#modalFiche"
                            style="width: 150px">
                        Valider
                    </button>
                    <div class="modal fade"
                         id="modalFiche"
                         data-backdrop="static"
                         tabindex="-1"
                         role="dialog">
                        <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title"
                                        id="modalFiche">
                                        Confirmation</h4>
                                </div>
                                <div class="modal-body" style="font-size: 14px">
                                    Votre demande a été prise en compte. Veuillez cliquez
                                    <strong><a href='../demandes/fiche_absence.php' target='_blank'>ici</a></strong>
                                    pour l'imprimer.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#date_debut').datepicker({ dateFormat: 'dd-mm-yy' });
        $('#date_fin').datepicker({ dateFormat: 'dd-mm-yy' });
    });

    var date_debut = "";
    var date_fin = "";

    function parseDate(str) {
        var date = str.split('-');
        return new Date(date[2] + "/" + date[1] + "/" + date[0])
    }

    function dateDiff(debut, fin) {
        var diff = Math.abs(fin.getTime() - debut.getTime());
        return Math.round(diff /(24*60*60*1000));
    }

    $('#date_debut').on('change', function () {
        date_debut = this.value;
        console.log(parseDate(date_debut));
        console.log(date_debut);
        /*if (date_fin != "") {
            if (this.value > date_fin) {
                //alert("Veuillez choisir une date postérieure à la date de réception.");
                alert(date_fin);
                this.value.empty();
            }
        }*/
    })

    $('#date_fin').on('change', function () {
        date_fin = this.value;
        if (date_debut != "") {
            if (this.value < date_debut) {
                //alert("Veuillez choisir une date antérieure à la date d'établissement.");
                alert(date_debut);
                this.value.empty();
            } else {
                console.log(dateDiff(date_debut, date_fin));
                //console.log(dateDiff(parseDate(date_fin), parseDate(date_debut)));
                //$('#duree').val(dateDiff(parseDate(date_fin), parseDate(date_debut)));
            }
        }
    })
</script>
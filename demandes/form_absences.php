<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 14/09/2016
     * Time: 14:31
     */
?>
<!--suppress ALL -->
<div class="col-md-10 col-md-offset-1">
    <div class="panel panel-default">
        <div class="panel-heading">
            Demande d'Absence
            <a href='form_principale.php?page=accueil' type='button'
               class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                <span aria-hidden='true'>&times;</span>
            </a>
        </div>
        <div class="panel-body">
            <form id="myform">
                <table class="formulaire" style="width: 100%" border="0">
                    <tr>
                        <td colspan="7">
                            <div class="jumbotron"
                                 style="width: 70%;
                                    padding: 20px 30px 20px 30px;
                                    background-color: rgba(1, 139, 178, 0.1);
                                    margin-left: auto;
                                    margin-right: auto">

                                <div style="height: 70%">
                                    <p style="font-size: small">Il s’agit ici des absences ci-après : journée, semaine, mois.</p>
                                </div>
                            </div>
                        </td>
                        <td rowspan="3" class="champlabel">
                            <img src="img/icons_1775b9/clock_96.png" width="104">
                        </td>
                    </tr>
                    <tr>
                        <td class="champlabel">Motif :</td>
                        <td colspan="4">
                            <label>
                                <textarea id="motif" rows="2" cols="30" class="form-control" required
                                          style="resize: none"></textarea>
                            </label>
                        </td>
                        <td class="champlabel">Lieu :</td>
                        <td>
                            <label>
                                <textarea id="lieu" rows="2" class="form-control" required
                                          style="resize: none"></textarea>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="champlabel">Début :</td>
                        <td>
                            <label>
                                <input type="text" name="debut" size="10"
                                       id="debut_dab" readonly
                                       title="Veuillez cliquer ici pour sélectionner une date"
                                       class="form-control"/>
                            </label>
                        </td>
                        <td class="champlabel">Fin :</td>
                        <td>
                            <label>
                                <input type="text" name="fin" size="10"
                                       id="fin_dab" readonly
                                       title="Veuillez cliquer ici pour sélectionner une date"
                                       class="form-control"/>
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
                    <!--<div class="modal fade"
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
                    </div>-->
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var attr = $('#valider').attr('data-toggle');
    $('#debut_dab').datepicker({dateFormat: 'mm/dd/yy'});
    $('#fin_dab').datepicker({dateFormat: 'mm/dd/yy'});

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
            alert('Veuillez renseigner tous les champs précédés de ""*"');
            $('#valider').removeAttr('data-toggle');
        } else {
            $('#valider').attr('data-toggle', attr);
            var motif_dab = $('#motif').val();
            var lieu_dab = $('#lieu').val();
            var debut_dab = $('#debut_dab').val();
            var fin_dab = $('#fin_dab').val();

            function diffDates(date1, date2) {
                return (new Date(date2) - new Date(date1))/(1000*60*60*24) | 0;
            }

            var infos = "motif=" + motif_dab + "&lieu=" + lieu_dab + "&debut=" + debut_dab + "&fin=" + fin_dab + "&duree=" + diffDates(debut_dab, fin_dab);
            var operation =  "ajout";

            $.ajax({
                type: 'POST',
                url: 'demandes/updatedata.php?operation=' + operation,
                data: infos,
                success: function (data) {
                    $('#info').html(data);
                    $('#myform').trigger('reset');
                }
            });
        }
    }
</script>
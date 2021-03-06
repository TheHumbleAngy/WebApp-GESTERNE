<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 21/09/2016
     * Time: 17:34
     */
?>
<!--suppress ALL -->
<div class="col-md-10 col-md-offset-1">
    <div class="panel panel-default">
        <div class="panel-heading">
            Demande de Permission
            <a href='form_principale.php?page=accueil' type='button'
               class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                <span aria-hidden='true'>&times;</span>
            </a>
        </div>
        <div class="panel-body">
            <form id="myform">
                <table class="formulaire" style="width: 100%" border="0">
                    <tr>
                        <td colspan="4">
                            <div class="jumbotron"
                                 style="width: 70%;
                                    padding: 20px 30px 20px 30px;
                                    background-color: rgba(1, 139, 178, 0.1);
                                    margin-left: auto;
                                    margin-right: auto">

                                <div style="height: 70%">
                                    <p style="font-size: small">Il s’agit ici des absences ci-après : 30 minutes, 1 heure, 2 heures, une demi-journée.</p>
                                </div>
                            </div>
                        </td>
                        <td rowspan="3" class="champlabel">
                            <img src="img/icons_1775b9/overtime_100.png" width="104">
                        </td>
                    </tr>
                    <tr>
                        <td class="champlabel">Motif :</td>
                        <td>
                            <label>
                                <textarea id="motif" rows="2" cols="30" class="form-control" required
                                          style="resize: none"></textarea>
                            </label>
                        </td>
                        <td class="champlabel">Lieu :</td>
                        <td>
                            <label>
                                <textarea id="lieu" rows="2" class="form-control" required
                                          style="resize: none"><?php
                                        echo date('H:i:s', time()) . " " . date('H:i:s', (time() + 30 * 60));
                                    ?>
                                </textarea>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="champlabel">Durée :</td>
                        <td>
                            <label>
                                <input type="number" id="duree" class="form-control" min="1" max="5" required>
                            </label> Heure(s)
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
                                    <strong><a href='../absences/fiche_absence.php' target='_blank'>ici</a></strong>
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
    var attr = $('#valider').attr('data-toggle');

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
            var duree_dab = $('#duree').val();

            var infos = "motif=" + motif_dab + "&lieu=" + lieu_dab + "&duree=" + duree_dab;
            var operation = "ajout";

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

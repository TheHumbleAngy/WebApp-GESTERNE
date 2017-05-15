<?php
    /**
     * Created by PhpStorm.
     * User: stagiaireinfo
     * Date: 31/03/14
     * Time: 10:13
     */
?>
<head>
    <meta charset="UTF-8">
</head>
<body onload="">
    <div id="info"></div>
    <div id="feedback" style="margin-left: 1.5%; margin-right: 1.5%"></div>

    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                Liste - Demandes
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
                                                    <p style="font-size: small">Veuillez spécifier la période.</p>
                                                </td>
                                            </tr>
                                        </table>
                                        <table class="formulaire" style="margin-left: auto; margin-right: auto">
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
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    $(document).ready(function () {
        $('#date_d').datepicker({dateFormat: 'dd-mm-yy'});
        $('#date_f').datepicker({dateFormat: 'dd-mm-yy'});
    });
    
    function afficherInfos() {
        $.ajax({
            type: 'GET',
            url: 'demandes/biens_services/getdata.php',
            success: function (data) {
                $('#feedback').html(data);
            }
        });
    }

    function suppressionInfos(code) {
        $.ajax({
            type: 'POST',
            url: 'demandes/biens_services/updatedata.php?operation=suppr',
            data: {
                id: code
            },
            success: function (data) {
                $('#info').html(data);
                afficherInfos();
                $("div.modal-backdrop.fade.in").remove();
                setTimeout(function(){
                    $(".alert-success").slideToggle("slow");
                }, 2500);
            }
        });
    }
</script>
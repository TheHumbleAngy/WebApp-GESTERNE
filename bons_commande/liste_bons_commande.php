<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 02/07/2016
     * Time: 09:19
     */
?>
<body onload="afficherInfos()">
    <div id="info"></div>
    <div id="feedback" style="margin-left: 1.5%; margin-right: 1.5%"></div>
</body>

<script>
    function afficherInfos() {
        $.ajax({
            type: 'GET',
            url: 'bons_commande/getdata.php',
            success: function (data) {
                $('#feedback').html(data);
            }
        });
    }

    function suppressionInfos(code) {
        $.ajax({
            type: 'POST',
            url: 'demandes/updatedata.php?operation=suppr',
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
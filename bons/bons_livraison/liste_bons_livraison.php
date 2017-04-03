<?php
/**
 * Created by PhpStorm.
 * User: stagiaireinfo
 * Date: 03/04/14
 * Time: 09:48
 */
?>
<!--suppress ALL -->
<body onload="afficherInfos()">
    <div id="info"></div>
    <div id="feedback" style="margin-left: 1.5%; margin-right: 1.5%"></div>
</body>

<script>
    function afficherInfos() {
        $.ajax({
            type: 'GET',
            url: 'bons_livraison/getdata.php',
            success: function (data) {
                $('#feedback').html(data);
            }
        });
    }

    function suppressionInfos(code) {
        $.ajax({
            type: 'POST',
            url: 'bons_livraison/updatedata.php?operation=suppr',
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
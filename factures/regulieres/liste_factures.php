<?php
    /**
     * Created by PhpStorm.
     * User: stagiaireinfo
     * Date: 31/03/14
     * Time: 12:00
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
            url: 'factures/getdata.php',
            success: function (data) {
                $('#feedback').html(data);
            }
        });
    }

    function suppressionInfos(code) {
        $.ajax({
            type: 'POST',
            url: 'factures/updatedata.php?operation=suppr',
            data: {
                id: code
            },
            success: function (data) {
                afficherInfos();
                $('#info').html(data);
                $("div.modal-backdrop.fade.in").remove();
                setTimeout(function(){
                    $(".alert-success").slideToggle("slow");
                }, 2500);
            }
        });
    }
</script>
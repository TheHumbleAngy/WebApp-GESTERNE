<?php
    /**
     * Created by PhpStorm.
     * User: stagiaireinfo
     * Date: 31/03/14
     * Time: 10:13
     */

    if (isset($_GET['statut']))
        $statut = $_GET['statut'];
    else
        $statut = "toutes";
    //echo "liste_demandes " . $statut;
?>
<head>
    <meta charset="UTF-8">
</head>
<!-- suppress ALL -->
<body onload="afficherInfos()">
    <input type="hidden" id="statut" value="<?php echo $statut; ?>">
    <div id="info"></div>
    <div id="feedback" style="margin-left: 1.5%; margin-right: 1.5%"></div>
</body>

<script>
    function afficherInfos() {
        var info = $('#statut').val();
        console.log(info);
        $.ajax({
            type: 'GET',
            url: 'demandes/biens_services/getdata.php',
            data: {
                statut: info
            },
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
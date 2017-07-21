<?php
    /**
     * Created by PhpStorm.
     * User: stagiaireinfo
     * Date: 31/03/14
     * Time: 10:13
     */
    if (isset($_GET['statut']) && ($_GET['statut'] == "rupture"))
        $statut = $_GET['statut'];
    else
        $statut = "tous";
    
?>
<body onload="afficherInfos()">
    <div id="info"></div>
    <div id="feedback"></div>
    <input type="hidden" id="statut_id" value="<?php echo $statut; ?>">
</body>

<script>
    function afficherInfos() {
        var statut = $('#statut_id').val();
        $.ajax({
            type: 'GET',
            url: 'articles/getdata.php?statut=' + statut,
            success: function (data) {
                $('#feedback').html(data);
            }
        });
    }

    function majInfos(code) {
        var id = code;
        var stock_art = $('#stock_art' + code).val();
        var designation_art = $('#designation_art' + code).val();
        var code_grp = $('#code_grp' + code).val();
        var description_art = $('#description_art' + code).val();
        var niveau_reappro_art = $('#niveau_reappro_art' + code).val();
        var niveau_cible_art = $('#niveau_cible_art' + code).val();

        var infos = "stock_art=" + stock_art + "&designation_art=" + designation_art + "&code_grp=" + code_grp + "&description_art=" + description_art + "&niveau_reappro_art=" + niveau_reappro_art + "&niveau_cible_art=" + niveau_cible_art,
            operation = "maj";

        $.ajax({
            type: 'POST',
            url: 'articles/updatedata.php?id=' + id + '&operation=' + operation,
            data: infos,
            success: function (data) {
                $('#info').html(data);
                afficherInfos();
                $("div.modal-backdrop.fade.in").remove();
                setTimeout(function () {
                    $(".alert-success").slideToggle("slow");
                }, 2500);
            }
        });
    }

    function suppressionInfos(code) {
        $.ajax({
            type: 'POST',
            url: 'articles/deletedata.php',
            data: {
                id: code
            },
            success: function (data) {
                afficherInfos();
                $('#info').html(data);
                $("div.modal-backdrop.fade.in").remove();
                setTimeout(function () {
                    $(".alert-success").slideToggle("slow");
                }, 2500);
            }
        });
    }
</script>
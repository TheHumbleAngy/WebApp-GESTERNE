<?php
    /**
     * Copyright (c) Ange KOUAKOU, 2017.
     */

    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 03-May-17
     * Time: 3:30 PM
     */

    if (isset($_POST["nbr"])) {
        $nbr = htmlspecialchars($_POST['nbr'], ENT_QUOTES);

        echo '
<div class="col-md-12">
    <div style="text-align: center; margin-bottom: 1%">
        <button class="btn btn-info" id="valider" type="button" onclick="ajout_facture();" name="valider" style="width: 150px">
            Valider
        </button>
    </div>
    <div class="panel panel-default">
        <table border="0" class="table table-hover table-condensed">
        <thead>
            <tr>
                <th class="entete" style="text-align: center; width: 60%">Libelle</th>
                <th class="entete" style="text-align: center">Quantité</th>
                <th class="entete" style="text-align: center; width: 15%" title="Prix Unitaire">P.U.</th>
                <th class="entete" style="text-align: center; width: 15%" title="En pourcentage">Remise (%)</th>
            </tr>
        </thead>
            ';

        for ($i = 0; $i < $nbr; $i++) {
            echo '<tr>
                <td style="vertical-align: middle">
                    <label style="width: 100%" class="nomargin_tb">
                        <input type="text" class="form-control" id="libelle_dfact' . $i . '" required style="font-weight: lighter" onblur="this.value = this.value.toUpperCase();">
                    </label>
                </td>
                <td style="text-align: center; vertical-align: middle">
                    <label style="margin-left: auto; margin-right: auto" class="nomargin_tb">
                        <input type="number" value="1" min="1" maxlength="4" class="form-control nomargin_tb" id="qte_dfact' . $i . '" required>
                    </label>
                </td>
                <td style="vertical-align: middle">
                    <label style="width: 100%" class="nomargin_tb">
                        <input type="text" class="form-control" id="pu_dfact' . $i . '" style="font-weight: lighter; text-align: right" placeholder="0" required>
                    </label>
                </td>
                <td style="vertical-align: middle">
                    <label style="width: 100%" class="nomargin_tb">
                        <input type="text" class="form-control" id="rem_dfact' . $i . '" style="font-weight: lighter; text-align: right" placeholder="0">
                    </label>
                </td>
              </tr>
            ';
        }

        echo '
        </table>
    </div>
</div>
<input type="hidden" id="nbr" value="' . $i . '">';
    }
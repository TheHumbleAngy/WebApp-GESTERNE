<?php
/**
 * Created by PhpStorm.
 * User: Ange KOUAKOU
 * Date: 12-Aug-15
 * Time: 11:36 AM
 */



//On appelle ici tous les bons de commande qui ne figurent pas encore sur un bon de livraison
$sql = "SELECT num_fp FROM proformas
        INNER JOIN fournisseurs
        ON fournisseurs.code_four = proformas.code_four
        WHERE fournisseurs.code_four = '" . $_POST['fournisseur'] . "'
        ORDER BY num_fp DESC";

$resultat = $connexion->query($sql);

$option = "";
$data = $resultat->fetch_all(MYSQLI_NUM);
$n = $resultat->num_rows;

for ($i = 0; $i < $n; $i++) {
    $option .= $data[$i][0] . ";";
}
echo $option;
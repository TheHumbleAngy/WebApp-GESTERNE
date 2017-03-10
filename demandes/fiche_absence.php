<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 14/09/2016
     * Time: 17:14
     */

    require "../fpdf/fpdf.php";
    include "../fonctions.php";
    header('Content-Type: text/html; charset=iso-8859-1');
    session_start();

    class PDF_MC_Table extends FPDF
    {
        var $widths;
        var $aligns;

        function Header()
        {
            $this->Image('../img/logo2.png', 10, 10, 70);
            $this->SetFont('Arial', 'B', 15);
            $this->Ln(20);
            $this->Cell(85, 20);
            $this->Cell(20, 20, "DEMANDE D'ABSENCE", 0, 0, 'C');
            $this->Ln(25);
            $this->SetFont('Arial', '', 11);
            $this->Cell(135, 8);
            $this->Cell(60, 8, "Abidjan, le " . date("d/m/Y"), 0);
            $this->Ln(16);
        }

        function Footer()
        {
            // Arial italic 8
            $this->SetFont('Arial', 'I', 6);
            // Position at 10px from bottom
            $this->SetY(-10);
            $this->Cell(0, 0, "GESTERNE", 0);
            // Position at 138px from left, that is approximately the center bottom of the page
            $this->SetX(138);
            // Page number

            /*$this->Image('logo1.png');
            $this->SetLeftMargin(84);
            $this->Cell(22, 0, $this->Image('logo1.png') . '', 0, 0, 'C');
            $this->Ln(1);
            $this->SetX(-30);
            $this->Cell(0, 0, "Moyens Generaux", 0);*/
        }

        function SetWidths($w)
        {
            //Set the array of column widths
            $this->widths = $w;
        }

        function SetAligns($a)
        {
            //Set the array of column alignments
            $this->aligns = $a;
        }

        function CheckPageBreak($h)
        {
            //If the height h would cause an overflow, add a new page immediately
            if ($this->GetY() + $h > $this->PageBreakTrigger)
                $this->AddPage($this->CurOrientation);
        }

        function NbLines($w, $txt)
        {
            //Computes the number of lines a MultiCell of width w will take
            $cw =& $this->CurrentFont['cw'];

            if ($w == 0) //si l'épaisseur de la cellule est = 0
                $w = $this->w - $this->rMargin - $this->x;

            $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
            $s = str_replace("\r", '', $txt); //on remplace le saut de ligne par rien dans le text
            $nb = strlen($s); //on récupère le nombre de caractère contenu dans le text

            if ($nb > 0 and $s[$nb - 1] == "\n")
                $nb--; //on diminue ce nombre d'une unité s'il y a un retour à la ligne à la fin du text

            $sep = -1;
            $i = 0;
            $j = 0;
            $l = 0;
            $nl = 1;

            while ($i < $nb) { //on parcourt chaque caractère du text
                $c = $s[$i];    //on recupere le caratère
                if ($c == "\n") { //si le caractère est un retour à la ligne
                    $i++;
                    $sep = -1;
                    $j = $i;
                    $l = 0;
                    $nl++;
                    continue;
                }
                if ($c == ' ') //si le caractère est un espace
                    $sep = $i;
                $l += $cw[$c];
                if ($l > $wmax) {
                    if ($sep == -1) {
                        if ($i == $j)
                            $i++;
                    } else
                        $i = $sep + 1;
                    $sep = -1;
                    $j = $i;
                    $l = 0;
                    $nl++;
                } else
                    $i++;
            }

            return $nl;
        }

        function Row($data) //$value est la valeur limite pour la lageur de la cellule
        {
            //Calculate the height of the row
            $nb = 0;
            for ($i = 0; $i < count($data); $i++)
                $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
            $h = 5 * $nb;
            /*for ($i = 0; $i < count($data); $i++) {
                if ($data[$i] < $value) {
                    $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
                    $h = 5 * $nb;
                } else {
                    $h = $nb;
                }
            }*/


            //Issue a page break first if needed
            $this->CheckPageBreak($h);
            //Draw the cells of the row
            for ($i = 0; $i < count($data); $i++) {
                $w = $this->widths[$i];
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                //Save the current position
                $x = $this->GetX();
                $y = $this->GetY();
                //Draw the border
                //$this->Cell($w, $h);
                //Print the text
                $this->MultiCell($w, 5, $data[$i], 0, $a);
                //Put the position to the right of the cell
                $this->SetXY($x + $w, $y);
            }
            //Go to the next line
            $this->Ln($h);
        }

        function Row_Border($data)
        {
            //Calculate the height of the row
            $nb = 0;
            for ($i = 0; $i < count($data); $i++)
                $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
            $h = 5 * $nb;

            //Issue a page break first if needed
            $this->CheckPageBreak($h);
            //Draw the cells of the row
            for ($i = 0; $i < count($data); $i++) {
                $w = $this->widths[$i];
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                //Save the current position
                $x = $this->GetX();
                $y = $this->GetY();
                //Draw the border
                $this->Rect($x, $y, $w, $h);
                //Print the text
                $this->MultiCell($w, 5, $data[$i], 0, $a);
                //Put the position to the right of the cell
                $this->SetXY($x + $w, $y);
            }
            //Go to the next line
            $this->Ln($h);
        }
    }

    $pdf = new PDF_MC_Table('P');
    $pdf->AddPage();
    $pdf->SetTitle("Gesterne | Demande d'Absence", TRUE);
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetWidths(array(25, 70, 40, 60, 82));

    //DETAILS DE LA DEMANDE
//    if (!$config = parse_ini_file('../../config.ini')) $config = parse_ini_file('../config.ini');
    $iniFile = 'config.ini';
    while (!$config = parse_ini_file($iniFile))
        configpath($iniFile);
    $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

    $sql = "SELECT nom_emp, prenoms_emp, fonction_emp, motif_dab, lieu_dab, duree_dab, debut_dab, fin_dab
            FROM employes AS e INNER JOIN demandes_absence AS d 
            ON e.code_emp = d.code_emp
            WHERE e.code_emp = '" . $_SESSION['user_id'] . "' AND d.code_dab = '" . $_SESSION['id'] . "'";
    if ($valeur = $connexion->query($sql)) {
        if ($valeur->num_rows > 0) {
            $ligne = $valeur->fetch_all(MYSQLI_ASSOC);
            $i = 1;
            foreach ($ligne as $list) {
                $nom_emp = $list['nom_emp'] . " " . $list['prenoms_emp'];
                $fct = $list['fonction_emp'];
                $motif = $list['motif_dab'];
                $lieu = $list['lieu_dab'];
                $duree = $list['duree_dab'];
                $debut = $list['debut_dab'];
                $fin = $list['fin_dab'];
            }
        }
    }

    $date_dbs = date("d/m/Y");
    $pdf->SetLineWidth(0.5);

    $pdf->Line(20, 69, 210 - 25, 69);
    $pdf->SetFont('Arial', '', 11);
    $pdf->SetWidths(array(15, 60, 50));
    $pdf->Row(array("", "NOM ET PRENOMS", $nom_emp));
    $pdf->Ln(4);
    
    $pdf->Line(20, 78, 210 - 25, 78);
    $pdf->SetFont('Arial', '', 11);
    $pdf->SetWidths(array(15, 60, 100));
    $pdf->Row(array("", "QUALITE", $fct));
    $pdf->Ln(4);
    
    $pdf->Line(20, 87, 210 - 25, 87);
    $pdf->SetFont('Arial', '', 11);
    $pdf->SetWidths(array(15, 60, 100));
    if (strlen($motif) >= 55) {
        $pdf->Row(array("", "MOTIF", iconv('UTF-8', 'windows-1252', $motif)));
        $pdf->Ln(8);
    } else {
        $pdf->Row(array("", "MOTIF", iconv('UTF-8', 'windows-1252', $motif)));
        $pdf->Ln(13);
    }

    $pdf->Line(20, 105, 210 - 25, 105);
    $pdf->SetFont('Arial', '', 11);
    $pdf->SetWidths(array(15, 60, 100));
    $pdf->Row(array("", "LIEU", iconv('UTF-8', 'windows-1252', $lieu)));
    $pdf->Ln(4);
    
    $pdf->Line(20, 114, 210 - 25, 114);
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetWidths(array(15, 60, 70));
    $pdf->Row(array("", "DUREE", strtoupper($duree) . " jour(s) " . iconv('UTF-8', 'windows-1252', "à partir du ") . rev_date($debut)));
    $pdf->Ln(12);
    
    $pdf->Line(20, 123, 210-25, 123);

    $pdf->SetWidths(array(55, 55, 55));
    
    $pdf->Cell(10);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Row_Border(array("VISA DE L'INTERESSE", "VISA DU SUPERIEUR HIERARCHIQUE", "VISA DU DIRECTEUR GENERAL"));
    $pdf->Cell(10);
    $pdf->Cell(55, 30, "", 1);
    $pdf->Cell(55, 30, "", 1);
    $pdf->Cell(55, 30, "", 1);
    $pdf->Ln(100);

    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(10, 8);
    $str = "*Il s’agit ici des absences ci-après : journée, semaine, mois.";
    $str = iconv('UTF-8', 'windows-1252', $str);
    $pdf->Cell(60, 8, $str, 0);

    $pdf->Output();
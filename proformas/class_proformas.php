<?php
    error_reporting(E_ERROR);
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 30-Nov-16
     * Time: 2:44 PM
     */
    abstract class class_proformas
    {
        protected $ref_fp;
        protected $code_four;
        protected $date_eta;
        protected $date_rcp;
        protected $notes;
        protected $iniFile;
    }

    class proformas extends class_proformas {
        function recuperation($ref_fp) {
            $this->ref_fp = $ref_fp;
            $this->code_four = htmlspecialchars($_POST['code_four'], ENT_QUOTES);
            $this->date_eta = rev_date($_POST['dateetablissement_fp']);
            $this->date_rcp = rev_date($_POST['datereception_fp']);
            $this->notes = addslashes($_POST['notes_fp']);
            $this->iniFile = 'config.ini';

            return TRUE;
        }

        function configpath(&$ini) {
            return $ini = '../' . $ini;
        }

        function enregistrement() {
            while (!$config = parse_ini_file($this->iniFile))
                $this->configpath($this->iniFile);

            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
            if ($connexion->connect_error)
                die($connexion->connect_error);

            $requete = "INSERT INTO proformas(
                                ref_fp,
                                code_four,
                                dateetablissement_fp,
                                datereception_fp,
                                notes_fp)
                        VALUES ('$this->ref_fp',
                                '$this->code_four',
                                '$this->date_eta',
                                '$this->date_rcp',
                                '$this->notes')";
//            print_r($requete);
            if ($requete = mysqli_query($connexion, $requete)) {
                $n = $_POST['nbr'];
                for ($i = 0; $i < $n; $i++) {

                    $req = "SELECT id_dfp FROM details_proforma ORDER BY id_dfp DESC LIMIT 1";
                    $resultat = $connexion->query($req);

                    if ($resultat->num_rows > 0) {
                        $ligne = $resultat->fetch_all(MYSQLI_ASSOC);

                        //reccuperation du code
                        $id_dfp = "";
                        foreach ($ligne as $data) {
                            $id_dfp = stripslashes($data['id_dfp']);
                        }

                        //extraction des 4 derniers chiffres
                        $id_dfp = substr($id_dfp, -4);

                        //incrementation du nombre
                        $id_dfp += 1;

                        $b = "DFPRO";
                        $dat = date("Y");
                        $dat = substr($dat, -2);
                        $format = '%04d';
                        $resultat = $dat . "" . $b . "" . sprintf($format, $id_dfp);


                    } else {
                        //s'il n'existe pas d'enregistrements dans la base de données
                        $id_dfp = 1;
                        $b = "DFP";
                        $dat = date("Y");
                        $dat = substr($dat, -2);
                        $format = '%04d';
                        $resultat = $dat . "" . $b . "" . sprintf($format, $id_dfp);
                    }
                    //on affecte au code le resultat
                    $id_dfp = $resultat;

                    $libelle = ($_POST['libelle'][$i]);
                    $qte = ($_POST['qte'][$i]);
                    $pu = ($_POST['pu'][$i]);
                    $rem = ($_POST['rem'][$i]);

                    $libelle = mysqli_real_escape_string($connexion, $libelle);
                    $qte = htmlspecialchars($qte, ENT_QUOTES);
                    $pu = htmlspecialchars($pu, ENT_QUOTES);

                    $REQ = "INSERT INTO details_proforma (id_dfp, ref_fp, libelle, qte_dfp, pu_dfp, remise_dfp)
	                        VALUES ('$id_dfp', '$this->ref_fp', '$libelle', '$qte', '$pu', '$rem')";
//                    echo "<br>";
                    $requete = mysqli_query($connexion, $REQ) or die(mysqli_error($connexion));
                }
                
                return TRUE;
            }
            else 
                return FALSE;
        }
    }
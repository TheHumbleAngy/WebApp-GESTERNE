<?php
    
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 06-Dec-16
     * Time: 11:26 AM
     */
    abstract class class_factures
    {
        protected $num_fact;
        protected $code_four;
        protected $ref_fact;
        protected $date_eta;
        protected $date_rcp;
        protected $notes;
        protected $iniFile;
    }

    class factures extends class_factures {
        function recuperer($num_fact) {
            $this->num_fact = $num_fact;
            $this->code_four = htmlspecialchars($_POST['code_four'], ENT_QUOTES);
            $this->ref_fact = htmlspecialchars($_POST['ref_fact'], ENT_QUOTES);
            $this->date_eta = rev_date($_POST['dateetablissement_fact']);
            $this->date_rcp = rev_date($_POST['datereception_fact']);
            $this->notes = addslashes($_POST['notes_fp']);
            $this->iniFile = 'config.ini';

            return TRUE;
        }

        protected function configpath(&$ini) {
            return $ini = '../' . $ini;
        }

        function enregistrer() {
            while (!$config = parse_ini_file($this->iniFile))
                $this->configpath($this->iniFile);

            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
            if ($connexion->connect_error)
                die($connexion->connect_error);

            $requete = "INSERT INTO factures (num_fact, code_four, ref_fact, dateetablissement_fact, datereception_fact, remarques_facture)
                        VALUES ('$this->num_fact',
                                '$this->code_four',
                                '$this->ref_fact',
                                '$this->date_eta',
                                '$this->date_rcp',
                                '$this->notes')";
            //            print_r($requete);
            if ($requete = mysqli_query($connexion, $requete)) {
                $n = $_POST['nbr'];
                $test = TRUE;
                for ($i = 0; $i < $n; $i++) {

                    $req = "SELECT num_df FROM details_facture ORDER BY num_df DESC LIMIT 1";
                    $resultat = $connexion->query($req);

                    if ($resultat->num_rows > 0) {
                        $ligne = $resultat->fetch_all(MYSQLI_ASSOC);

                        //reccuperation du code
                        $code_df = "";
                        foreach ($ligne as $data) {
                            $code_df = stripslashes($data['code_df']);
                        }

                        //extraction des 4 derniers chiffres
                        $code_df = substr($code_df, -4);

                        //incrementation du nombre
                        $code_df += 1;

                        $b = "DF";
                        $dat = date("Y");
                        $dat = substr($dat, -2);
                        $format = '%04d';
                        $resultat = $dat . "" . $b . "" . sprintf($format, $code_df);

                    } else {
                        //s'il n'existe pas d'enregistrements dans la base de données
                        $code_df = 1;
                        $b = "DF";
                        $dat = date("Y");
                        $dat = substr($dat, -2);
                        $format = '%04d';
                        $resultat = $dat . "" . $b . "" . sprintf($format, $code_df);
                    }
                    //on affecte au code le resultat
                    $code_df = $resultat;

                    $libelle_df = ($_POST['libelle'][$i]);
                    $qte_df = ($_POST['qte'][$i]);
                    $pu_df = ($_POST['pu'][$i]);
                    $rem = ($_POST['rem'][$i]);

                    $libelle_df = addslashes($libelle_df);
                    $qte_df = htmlspecialchars($qte_df, ENT_QUOTES);
                    $pu_df = htmlspecialchars($pu_df, ENT_QUOTES);
                    $rem = addslashes($rem);

                    echo $REQ = "INSERT INTO details_facture (num_df, num_fact, libelle_df, qte_df, pu_df, remise_df)
	                        VALUES ('$code_df', '$this->num_fact', '$libelle_df', '$qte_df', '$pu_df', '$rem')";

                    //exécution de la requête REQ:
                    /*if (!mysqli_query($connexion, $REQ)) {
                        $test = FALSE;
                        break;
                    }*/
                }
                /*if ($test)
                    return TRUE;
                else
                    return FALSE;*/
            } //else return FALSE;
        }
    }
<?php

    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 06-Dec-16
     * Time: 11:26 AM
     */
    include '../../fonctions.php';

    abstract class class_factures {
        protected $num_fact;
        protected $code_four;
        protected $ref_fact;
        protected $date_eta;
        protected $date_rcp;
        protected $notes;
        protected $iniFile;
    }

    class factures extends class_factures {
        function recuperer($num_fact, $code_four, $ref_fact, $date_e, $date_r, $rem) {
            $this->num_fact = $num_fact;
            $this->code_four = $code_four;
            $this->ref_fact = $ref_fact;
            $this->date_eta = rev_date($date_e);
            $this->date_rcp = rev_date($date_r);
            $this->notes = addslashes($rem);
            $this->iniFile = 'config.ini';

            return TRUE;
        }
        
        function recup_num() {
            return $this->num_fact;
        }

        protected function configpath($ini) {
            try {
                $ini = '../../../' . $ini;

                return $ini;
            } catch (Exception $e) {
                return "Exception caught :" . $e->getMessage();
            }
        }

        function enregistrer() {
            $config = parse_ini_file($this->iniFile);

            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
            if ($connexion->connect_error)
                die($connexion->connect_error);

            $sql = "INSERT INTO factures (num_fact, code_four, ref_fact, dateetablissement_fact, datereception_fact, remarques_facture) 
                    VALUES ('$this->num_fact', '$this->code_four', '$this->ref_fact', '$this->date_eta', '$this->date_rcp', '$this->notes')";
            
            //print_r($sql); return TRUE;

            if ($result = mysqli_query($connexion, $sql))
                return TRUE;
            else
                return FALSE;
        }
        
        function supprimer($code) {
            $config = parse_ini_file($this->iniFile);

            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
            if ($connexion->connect_error)
                die($connexion->connect_error);
            
            $sql = "DELETE FROM factures WHERE num_fact = '$code'";

            if ($result = mysqli_query($connexion, $sql))
                return TRUE;
            else
                return FALSE;
        }
    }
    
    class details_factre extends factures {
        protected $num_dfact;
        protected $libelle_dfact;
        protected $qte_dfact;
        protected $pu_dfact;
        protected $rem_dfact;

        function recuperer_details($libelle, $qte, $pu, $rem) {
            $this->libelle_dfact = $libelle;
            $this->qte_dfact = $qte;
            $this->pu_dfact = $pu;
            if ($rem == "")
                $this->ref_fact = 0;
            else
                $this->rem_dfact = $rem;
            $this->iniFile = 'config.ini';

            return TRUE;
        }

        function enregistrer_details($num_fact) {
            $config = parse_ini_file($this->iniFile);

            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
            if ($connexion->connect_error)
                die($connexion->connect_error);

            $sql = "SELECT num_df FROM details_facture ORDER BY num_df DESC LIMIT 1";
            $result = $connexion->query($sql);

            if ($result->num_rows > 0) {
                $lignes = $result->fetch_all(MYSQLI_ASSOC);

                foreach ($lignes as $ligne)
                    $num_df = stripslashes($ligne['num_df']);

                $num_df = substr($num_df, -4);

                $num_df += 1;
            }
            else {
                $num_df = 1;
            }

            $b = "DF";
            $dat = date("Y");
            $dat = substr($dat, -2);
            $format = '%04d';
            $resultat = $dat . "" . $b . "" . sprintf($format, $num_df);

            $this->num_dfact = $resultat;

            $sql = "INSERT INTO details_facture (num_df, num_fact, libelle_df, qte_df, pu_df, remise_df) 
                    VALUES ('$this->num_dfact', '$num_fact', '$this->libelle_dfact', '$this->qte_dfact', '$this->pu_dfact', '$this->rem_dfact')";
            
            //print_r($sql); return TRUE;
            if ($result = mysqli_query($connexion, $sql))
                return TRUE;
            else
                return FALSE;
        }
    }
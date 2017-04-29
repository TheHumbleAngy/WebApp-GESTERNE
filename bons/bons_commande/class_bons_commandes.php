<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 01-Dec-16
     * Time: 11:17 AM
     */
    class class_bons_commandes
    {
        protected $num_bc;
        protected $code_emp;
        protected $code_four;
        protected $date_bc;
        protected $statut;
        protected $iniFile;
    }

    class bons_commandes extends class_bons_commandes {
        function recuperer($num_bc, $code_four)
        {
            $this->num_bc = $num_bc;
            $this->code_emp = $_SESSION['user_id'];
            $this->code_four = $code_four;
            $this->date_bc = date("Y-m-d");
            $this->iniFile = 'config.ini';

            return TRUE;
        }

        protected function configpath($ini) {
            try {
                $ini = '../../../' . $ini;
                return $ini;
            } catch (Exception $e) {
                return "Exception caught :" . $e->getMessage();
            }
        }
        function recup_num() {
            return $this->num_bc;
        }

        function enregistrer() {
            $config = parse_ini_file($this->configpath($this->iniFile));

            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
            if ($connexion->connect_error)
                die($connexion->connect_error);

            $sql = "INSERT INTO bons_commande (num_bc, code_emp, code_four, date_bc) 
                    VALUES ('$this->num_bc', '$this->code_emp', '$this->code_four', '$this->date_bc')";

            if ($result = mysqli_query($connexion, $sql))
                return TRUE;
            else
                return FALSE;
        }

        function supprimer($code) {
            $config = parse_ini_file($this->configpath($this->iniFile));

            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
            if ($connexion->connect_error)
                die($connexion->connect_error);

            $sql = "DELETE FROM bons_commande WHERE num_bc = '" . $code . "'";

            if ($result = mysqli_query($connexion, $sql))
                return TRUE;
            else
                return FALSE;
        }
    }

    class details_bons_commande extends bons_commandes {
        protected $num_dbc;
        protected $libelle_dbc;
        protected $qte_dbc;
        protected $pu_dbc;
        protected $rem_dbc;

        function recuperer_detail($libelle, $qte, $pu, $rem) {
            $this->libelle_dbc = $libelle;
            $this->qte_dbc = $qte;
            $this->pu_dbc = $pu;
            $this->rem_dbc = $rem;
            $this->iniFile = "config.ini";

            return TRUE;
        }

        function enregistrer_detail($num_bc) {
            $config = parse_ini_file($this->configpath($this->iniFile));

            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
            if ($connexion->connect_error)
                die($connexion->connect_error);

            $sql = "SELECT num_dbc FROM details_bon_commande ORDER BY num_dbc DESC LIMIT 1";
            $result = $connexion->query($sql);

            if ($result->num_rows > 0) {
                $ligne = $result->fetch_all(MYSQLI_ASSOC);
                //reccuperation du code
                foreach ($ligne as $data) {
                    $num_dbc = stripslashes($data['num_dbc']);
                }

                //extraction des 4 derniers chiffres
                $num_dbc = substr($num_dbc, -4);

                //incrementation du nombre
                $num_dbc += 1;
            }
            else {
                //s'il n'existe pas d'enregistrements dans la base de données
                $num_dbc = 1;
            }

            $b = "DBC";
            $dat = date("Y");
            $dat = substr($dat, -2);
            $format = '%04d';
            $resultat = $dat . "" . $b . "" . sprintf($format, $num_dbc);

            $this->num_dbc = $resultat;

            $sql = "INSERT INTO details_bon_commande (num_dbc, num_bc, libelle_dbc, qte_dbc, pu_dbc, remise_dbc)
                      VALUES ('$this->num_dbc', '$num_bc', '$this->libelle_dbc', '$this->qte_dbc', '$this->pu_dbc', '$this->rem_dbc')";

            if ($result = mysqli_query($connexion, $sql))
                return TRUE;
            else
                return FALSE;
        }
    }
<?php

    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 29/08/2016
     * Time: 14:57
     */
    abstract class class_bons_commande {
        public $num_bc;
        public $code_emp;
        public $code_four;
        public $date_bc;
        public $statut;
    }
    
    class bons_commande extends class_bons_commande {
        function recuperation($code_emp) {
            $this->num_bc = htmlspecialchars($_POST['num_bc'], ENT_QUOTES);
            $this->code_emp = $code_emp;
            $this->code_four = isset($_POST['code_four']) ? htmlspecialchars($_POST['code_four'], ENT_QUOTES) : htmlspecialchars($_POST['cod_four'], ENT_QUOTES);
            $this->date_bc = date("Y-m-d");

            return TRUE;
        }

        function afficher() {
            echo $this->num_bc; echo '<br>';
            echo $this->code_emp; echo '<br>';
            echo $this->code_four; echo '<br>';
            echo $this->date_bc; echo '<br>';
        }

        function enregistrement() {
            if (!$config = parse_ini_file('../../config.ini')) $config = parse_ini_file('../config.ini');
            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

            if ($connexion->connect_error)
                die($connexion->connect_error);

            $sql = "INSERT INTO bons_commande (num_bc, code_emp, code_four, date_bc) VALUES ('$this->num_bc', '$this->code_emp', '$this->code_four', '$this->date_bc')";
            print_r($sql);
            $result = mysqli_query($connexion, $sql);
            print_r($result);
            /*if ($result = mysqli_query($connexion, $sql))
                return TRUE;
            else
                return FALSE;*/
        }

        function suppression($code) {
            if (!$config = parse_ini_file('../../config.ini')) $config = parse_ini_file('../config.ini');
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
    
    class details_bon_commande extends bons_commande {
        protected $id_dbc;
        protected $libelle_dbc;
        protected $qte_dbc;
        protected $pu_dbc;
        protected $remise_dbc;
        
        function recuperation_details($id_dbc, $i) {
            $this->num_bc = $id_dbc;
            $this->libelle_dbc = addslashes($_POST['libelle_dbc'][$i]);
            $this->qte_dbc = htmlspecialchars($_POST['qte_dbc'][$i], ENT_QUOTES);
            $this->pu_dbc = htmlspecialchars($_POST['pu_dbc'][$i], ENT_QUOTES);
            $this->remise_dbc = htmlspecialchars($_POST['remise_dbc'][$i], ENT_QUOTES);
            
            return TRUE;
        }
        
        function enregistrement() {
            if (!$config = parse_ini_file('../../config.ini')) $config = parse_ini_file('../config.ini');
            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

            if ($connexion->connect_error)
                die($connexion->connect_error);

            $req = "SELECT id_dbc FROM details_bon_commande ORDER BY id_dbc DESC LIMIT 1";
            $resultat = $connexion->query($req);

            $id_dbc = 0;
            if ($resultat->num_rows > 0) {
                $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                //reccuperation du code
                foreach ($ligne as $data) {
                    $id_dbc = stripslashes($data['id_dbc']);
                }

                //extraction des 4 derniers chiffres
                $id_dbc = substr($id_dbc, -4);

                //incrementation du nombre
                $id_dbc += 1;
            } else {
                //s'il n'existe pas d'enregistrements dans la base de données
                $id_dbc = 1;
            }

            $b = "DBC";
            $dat = date("Y");
            $dat = substr($dat, -2);
            $format = '%04d';
            $resultat = $dat . "" . $b . "" . sprintf($format, $id_dbc);

            //on affecte au code le resultat
            $this->id_dbc = $resultat;

            $sql = "INSERT INTO details_bon_commande (id_dbc, num_bc, libelle_dbc, pu_dbc, qte_dbc, remise_dbc)
	                    VALUES ('$this->id_dbc', '$this->num_bc', '$this->libelle_dbc', '$this->pu_dbc', '$this->qte_dbc', '$this->remise_dbc')";

            print_r($sql);
            /*if ($result = $connexion->query($sql)) {
                return TRUE;
            } else
                return FALSE;*/
        }
    }
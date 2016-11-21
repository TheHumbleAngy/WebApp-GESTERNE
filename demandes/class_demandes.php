<?php
    
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 05/11/2015
     * Time: 15:55
     */
    abstract class class_demandes {
        public $code_dbs;
        public $code_emp;
        public $date_dbs;
        public $objets_dbs;
        public $statut;
    }
    
    class demandes extends class_demandes {        
        function recuperation($code_emp) {
            $this->code_dbs = htmlspecialchars($_POST['code_dbs'], ENT_QUOTES);
            $this->code_emp = $code_emp;
            $this->objets_dbs = addslashes($_POST['objets_dbs']);
            $this->date_dbs = date("Y-m-d");
            
            return TRUE;
        }
        
        function afficher() {
            echo $this->code_dbs;
            echo '<br>';
            echo $this->code_emp;
            echo '<br>';
            echo $this->date_dbs;
            echo '<br>';
            echo $this->objets_dbs;
            echo '<br>';
            echo $this->statut;
            echo '<br>';
        }
        
        function enregistrement() {
            $config = parse_ini_file('../config.ini');
            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
            
            if ($connexion->connect_error)
                die($connexion->connect_error);
            
            $sql = "INSERT INTO demandes (code_dbs, code_emp, date_dbs, objets_dbs)
	            VALUES ('$this->code_dbs', '$this->code_emp', '$this->date_dbs', '$this->objets_dbs')"; //print_r($sql);

            if ($result = mysqli_query($connexion, $sql))
                return TRUE;
            else
                return FALSE;
        }

        function suppression($code) {
            if (!$config = parse_ini_file('../../config.ini')) $config = parse_ini_file('../config.ini');
            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

            if ($connexion->connect_error)
                die($connexion->connect_error);
            
            $sql = "DELETE FROM demandes WHERE code_dbs = '" . $code . "'";
            
            if ($result = mysqli_query($connexion, $sql))
                return TRUE;
            else
                return FALSE;
        }
    }
    
    class details_demandes extends demandes {
        protected $code_dd;
        protected $nature_dd;
        protected $libelle_dd;
        protected $qte_dd;
        protected $observations_dd;
        
        function recuperation_details($code_dbs, $i) {
            $this->code_dbs = $code_dbs;
            $this->nature_dd = htmlspecialchars($_POST['nature'][$i], ENT_QUOTES);
            $this->libelle_dd = addslashes($_POST['libelle'][$i]);
            $this->qte_dd = htmlspecialchars($_POST['qte'][$i], ENT_QUOTES);
            $this->observations_dd = addslashes($_POST['obv'][$i]);
            
            return TRUE;
        }
        
        function afficher_details() {
            echo $this->code_dd;
            echo '<br>';
            echo $this->code_dbs;
            echo '<br>';
            echo $this->nature_dd;
            echo '<br>';
            echo $this->libelle_dd;
            echo '<br>';
            echo $this->qte_dd;
            echo '<br>';
            echo $this->observations_dd;
            echo '<br>';
        }
        
        function enregistrement_details() {
            $config = parse_ini_file('../config.ini');
            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
            
            if ($connexion->connect_error)
                die($connexion->connect_error);
            
            $req = "SELECT code_dd FROM details_demande ORDER BY code_dd DESC LIMIT 1";
            $res = $connexion->query($req);
            
            if ($res->num_rows > 0) {
                $ligne = $res->fetch_all(MYSQLI_ASSOC);
                
                $code_dd = "";
                foreach ($ligne as $data) {
                    $code_dd = stripslashes($data['code_dd']);
                }
                
                $code_dd = substr($code_dd, -4);
                
                $code_dd += 1;
            } else {
                $code_dd = 1;
            }
            
            $b = "DD";
            $dat = date("Y");
            $dat = substr($dat, -2);
            $format = '%04d';
            $resultat = $dat . "" . $b . "" . sprintf($format, $code_dd);
            
            $this->code_dd = $resultat;

            $sql = "INSERT INTO details_demande (code_dd, nature_dd, code_dbs, libelle_dd, qte_dd, observations_dd)
                        VALUES ('$this->code_dd', '$this->nature_dd', '$this->code_dbs', '$this->libelle_dd', '$this->qte_dd', '$this->observations_dd')";
            
            if ($result = $connexion->query($sql)) {
                return TRUE;
            } else
                return FALSE;
        }
    }

    class demandes_absence extends class_demandes {
        protected $motif_dab;
        protected $lieu_dab;
        protected $duree_dab;

        function recuperation($code){
            $this->code_emp = $code;
            $this->date_dbs = date("Y-m-d");
            $this->motif_dab = addslashes($_POST['motif']);
            $this->lieu_dab = addslashes($_POST['lieu']);
            $this->duree_dab = addslashes($_POST['duree']);

            return true;
        }

        function afficher() {
            echo $this->code_emp; echo '<br>';
            echo $this->date_dbs; echo '<br>';
            echo $this->motif_dab; echo '<br>';
            echo $this->lieu_dab; echo '<br>';
            echo $this->duree_dab; echo '<br>';
        }

        function enregistrement($emp) {
            if (!$config = parse_ini_file('../../config.ini')) $config = parse_ini_file('../config.ini');
            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

            if ($connexion->connect_error)
                die($connexion->connect_error);

            $sql = "SELECT code_dab FROM demandes_absence ORDER BY code_dab DESC LIMIT 1";
            $resultat = $connexion->query($sql);

            if ($resultat->num_rows > 0) {
                $lignes = $resultat->fetch_all(MYSQLI_ASSOC);
                $code = "";
                foreach ($lignes as $ligne)
                    $code = stripslashes($ligne['code_dab']);

                $code = substr($code, -4);
                $code += 1;
            } else
                $code = 1;

            $b = "DAB";
            $dat = date("Y");
            $dat = substr($dat, -2);
            $format = '%04d';
            $info = $dat . "" . $b . "" . sprintf($format, $code);
            $this->code_dbs = $info;
            
            $sql = "INSERT INTO demandes_absence (code_dab, code_emp, date_dab, motif_dab, lieu_dab, duree_dab) 
                VALUES ('$this->code_dbs', '$emp', '$this->date_dbs', '$this->motif_dab', '$this->lieu_dab', '$this->duree_dab')";

            if ($resultat = mysqli_query($connexion, $sql))
                return TRUE;
            else
                return FALSE;
        }
    }
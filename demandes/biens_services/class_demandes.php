<?php
    
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 05/11/2015
     * Time: 15:55
     */
    abstract class class_demandes {
        public $num_dmd;
        public $code_emp;
        public $date_dbs;
        public $objets_dbs;
        public $statut;
        protected $iniFile;
    }
    
    class demandes extends class_demandes {        
        function recuperation($code_emp) {
            $this->num_dmd = htmlspecialchars($_POST['num_dbs'], ENT_QUOTES);
            $this->code_emp = $code_emp;
            $this->objets_dbs = addslashes($_POST['objets_dbs']);
            $this->date_dbs = date("Y-m-d");
            $this->iniFile = "config.ini";
            
            return TRUE;
        }
        
        function afficher() {
            echo $this->num_dmd;
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

        protected function configpath(&$ini) {
            return $ini = '../' . $ini;
        }
        
        function enregistrement() {
            while (!$config = parse_ini_file($this->iniFile))
                $this->configpath($this->iniFile);
            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
            if ($connexion->connect_error)
                die($connexion->connect_error);
            
            $sql = "INSERT INTO demandes (num_dbs, code_emp, date_dbs, objets_dbs)
	            VALUES ('$this->num_dmd', '$this->code_emp', '$this->date_dbs', '$this->objets_dbs')"; //print_r($sql);

            if ($result = mysqli_query($connexion, $sql))
                return TRUE;
            else
                return FALSE;
        }

        function suppression($code) {
            if (!$config = parse_ini_file('../../../config.ini')) $config = parse_ini_file('../../config.ini');
            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

            if ($connexion->connect_error)
                die($connexion->connect_error);
            
            $sql = "DELETE FROM demandes WHERE num_dbs = '" . $code . "'";
            
            if ($result = mysqli_query($connexion, $sql))
                return TRUE;
            else
                return FALSE;
        }
    }
    
    class details_demandes extends demandes {
        protected $num_dd;
        protected $nature_dd;
        protected $libelle_dd;
        protected $qte_dd;
        protected $observations_dd;
        
        function recuperation_details($num_dmd, $i) {
            $this->num_dmd = $num_dmd;
            $this->nature_dd = htmlspecialchars($_POST['nature'][$i], ENT_QUOTES);
            $this->libelle_dd = addslashes($_POST['libelle'][$i]);
            $this->qte_dd = htmlspecialchars($_POST['qte'][$i], ENT_QUOTES);
            $this->observations_dd = addslashes($_POST['obv'][$i]);
            $this->iniFile = "config.ini";
//            echo "<br>";
            return TRUE;
        }
        
        function afficher_details() {
            echo $this->num_dd;
            echo '<br>';
            echo $this->num_dmd;
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
            while (!$config = parse_ini_file($this->iniFile))
                $this->configpath($this->iniFile);
            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
            if ($connexion->connect_error)
                die($connexion->connect_error);
            
            $req = "SELECT num_dd FROM details_demande ORDER BY num_dd DESC LIMIT 1";
            $res = $connexion->query($req);

            if ($res->num_rows > 0) {
                $ligne = $res->fetch_all(MYSQLI_ASSOC);
                
                $num_dd = "";
                foreach ($ligne as $data) {
                    $num_dd = stripslashes($data['num_dd']);
                }
                
                $num_dd = substr($num_dd, -4);
                
                $num_dd += 1;
            } else {
                $num_dd = 1;
            }
            
            $b = "DD";
            $dat = date("Y");
            $dat = substr($dat, -2);
            $format = '%04d';
            $resultat = $dat . "" . $b . "" . sprintf($format, $num_dd);
            
            $this->num_dd = $resultat;

            $sql = "INSERT INTO details_demande (num_dd, nature_dd, num_dbs, libelle_dd, qte_dd, observations_dd)
                        VALUES ('$this->num_dd', '$this->nature_dd', '$this->num_dmd', '$this->libelle_dd', '$this->qte_dd', '$this->observations_dd')";
            
            if ($result = mysqli_query($connexion, $sql))
                return TRUE;
            else
                return FALSE;
        }
    }
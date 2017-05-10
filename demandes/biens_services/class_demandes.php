<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 05/11/2015
     * Time: 15:55
     */
    abstract class class_demandes {
        protected $num_dmd;
        protected $code_emp;
        protected $date_dbs;
        protected $objets_dbs;
        protected $statut;
        protected $iniFile;
    }
    
    class demandes extends class_demandes {        
        function recuperer($code_emp) {
            $this->num_dmd = htmlspecialchars($_POST['num_dmd'], ENT_QUOTES);
            $this->code_emp = $code_emp;
            $this->objets_dbs = addslashes($_POST['objet_dmd']);
            $this->date_dbs = date("Y-m-d");
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
            return $this->num_dmd;
        }
        
        function enregistrer() {
            $config = parse_ini_file($this->configpath($this->iniFile));
            
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

        function supprimer($code) {
            $config = parse_ini_file($this->configpath($this->iniFile));

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
        
        function recuperer_detail($libelle_dd, $nature_dd, $qte_dd, $obsv_dd) {
            $this->nature_dd = $nature_dd;
            $this->libelle_dd = $libelle_dd;
            $this->qte_dd = $qte_dd;
            $this->observations_dd = $obsv_dd;
            $this->iniFile = 'config.ini';
            
            return TRUE;
        }
        
        function enregistrer_detail($num_dmd) {
            $config = parse_ini_file($this->configpath($this->iniFile));
            
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
                        VALUES ('$this->num_dd', '$this->nature_dd', '$num_dmd', '$this->libelle_dd', '$this->qte_dd', '$this->observations_dd')";
            
            if ($result = mysqli_query($connexion, $sql))
                return TRUE;
            else
                return FALSE;
        }
    }
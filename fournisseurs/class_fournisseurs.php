<?php
    //error_reporting(E_ERROR);
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 15-Sep-15
     * Time: 4:49 PM
     */
    abstract class class_fournisseurs {
        protected $code_four;
        protected $nom_four;
        protected $email_four;
        protected $telephonepro_four;
        protected $fax_four;
        protected $adresse_four;
        protected $notes_four;
        protected $activite_four;
        
        protected $config;
        protected $connexion;
        protected $iniFile;

        abstract protected function enregistrer();

        abstract protected function recuperer();

        abstract protected function modifier($code);

        abstract protected function supprimer($code);
    }

    class fournisseurs extends class_fournisseurs {

        /**
         * fournisseurs constructor.
         */
        public function __construct() {
            $this->iniFile = 'config.ini';

            while (!file_exists($this->iniFile))
                $this->configpath($this->iniFile);

            $this->config = parse_ini_file($this->iniFile);
            $this->connexion = mysqli_connect($this->config['hostname'], $this->config['username'], $this->config['password'], $this->config['dbname']);
            if ($this->connexion->connect_error)
                die($this->connexion->connect_error);
        }

        function recuperer() {
            $this->nom_four = addslashes($_POST['nom_four']);
            $this->email_four = htmlspecialchars($_POST['email_four'], ENT_QUOTES);
            $this->telephonepro_four = htmlspecialchars($_POST['telephonepro_four'], ENT_QUOTES);
            $this->fax_four = htmlspecialchars($_POST['fax_four'], ENT_QUOTES);
            $this->adresse_four = addslashes($_POST['adresse_four']);
            $this->notes_four = addslashes($_POST['notes_four']);
            $this->activite_four = addslashes($_POST['activite_four']);

            return TRUE;
        }

        function afficher() {
            echo $this->nom_four;
            echo '<br>';
            echo $this->email_four;
            echo '<br>';
            echo $this->telephonepro_four;
            echo '<br>';
            echo $this->fax_four;
            echo '<br>';
            echo $this->adresse_four;
            echo '<br>';
            echo $this->notes_four;
            echo '<br>';
            echo $this->activite_four;
            echo '<br>';
        }

        protected function configpath(&$ini) {
            try {
                $ini = '../' . $ini;

                return $ini;
            } catch (Exception $e) {
                return "Exception caught :" . $e->getMessage();
            }
        }

        function enregistrer() {
            //On vérifie s'il y a un en registrement dans la base de données
            $req = "SELECT code_four FROM fournisseurs ORDER BY code_four DESC LIMIT 1";
            $resultat = $this->connexion->query($req);

            $code_four = "";
            if ($resultat->num_rows > 0) {
                $ligne = $resultat->fetch_all(MYSQLI_ASSOC);

                //reccuperation du code
                foreach ($ligne as $data) {
                    $code_four = stripslashes($data['code_four']);
                }

                //extraction des 4 derniers chiffres
                $code_four = substr($code_four, -4);

                //incrementation du nombre
                $code_four += 1;
            }
            else {
                //s'il n'existe pas d'enregistrements dans la base de données
                $code_four = 1;
            }
            $b = "FOUR";
            $dat = date("Y");
            $dat = substr($dat, -2);
            $format = '%04d';
            $resultat = $dat . "" . $b . "" . sprintf($format, $code_four);

            //on affecte au code le resultat
            $this->code_four = $resultat;

            $sql = "INSERT INTO fournisseurs (code_four, nom_four, email_four, telephonepro_four, fax_four, adresse_four, notes_four, activite_four)
                    VALUES ('$this->code_four', '$this->nom_four', '$this->email_four', '$this->telephonepro_four', '$this->fax_four', '$this->adresse_four', '$this->notes_four', '$this->activite_four')";

            if ($result = mysqli_query($this->connexion, $sql))
                return TRUE;
            else
                return FALSE;
        }

        function modifier($code) {
            $sql = "UPDATE fournisseurs SET
                    nom_four = '" . $this->nom_four . "',
                    email_four = '" . $this->email_four . "',
                    telephonepro_four = '" . $this->telephonepro_four . "',
                    fax_four = '" . $this->fax_four . "',
                    adresse_four = '" . $this->adresse_four . "',
                    notes_four = '" . $this->notes_four . "',
                    activite_four = '" . $this->activite_four . "'
                    WHERE code_four = '" . $code . "'";

            if ($result = mysqli_query($this->connexion, $sql))
                return TRUE;
            else
                return FALSE;
        }

        function supprimer($code) {
            $sql = "DELETE FROM fournisseurs WHERE code_four = '" . $code . "'";

            if ($result = mysqli_query($this->connexion, $sql))
                return TRUE;
            else
                return FALSE;
        }
    }
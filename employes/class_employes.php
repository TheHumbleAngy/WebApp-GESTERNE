<?php
    //error_reporting(E_ERROR);
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 14-Sep-15
     * Time: 3:43 PM
     */
    abstract class class_employes {
        protected $code_emp;
        protected $titre_emp;
        protected $nom_emp;
        protected $prenoms_emp;
        protected $fonction_emp;
        protected $departement_emp;
        protected $email_emp;
        protected $mdp;
        protected $tel_emp;
        protected $etat_connecte;

        protected $iniFile;
        protected $config;
        protected $connexion;

        abstract protected function enregistrer();

        abstract protected function recuperer();

        abstract protected function modifier($code);

        abstract protected function supprimer($code);
    }

    class employes extends class_employes {

        /**
         * employes constructor.
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
            $this->titre_emp = htmlspecialchars($_POST['titre_emp'], ENT_QUOTES);
            $this->nom_emp = htmlspecialchars($_POST['nom_emp'], ENT_QUOTES);
            $this->prenoms_emp = htmlspecialchars($_POST['prenoms_emp'], ENT_QUOTES);
            $this->fonction_emp = addslashes($_POST['fonction_emp']);
            $this->departement_emp = htmlspecialchars($_POST['departement_emp'], ENT_QUOTES);
            $this->email_emp = htmlspecialchars($_POST['email_emp'], ENT_QUOTES);
            $this->tel_emp = htmlspecialchars($_POST['tel_emp'], ENT_QUOTES);

            return TRUE;
        }

        function afficher() {
            echo $this->titre_emp;
            echo '<br>';
            echo $this->nom_emp;
            echo '<br>';
            echo $this->prenoms_emp;
            echo '<br>';
            echo $this->fonction_emp;
            echo '<br>';
            echo $this->departement_emp;
            echo '<br>';
            echo $this->email_emp;
            echo '<br>';
            echo $this->tel_emp;
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

        function motdepasse($mdp) {
            $this->mdp = $mdp;
        }

        function enregistrer() {
            //On vérifie s'il y a un en registrement dans la base de données
            $req = "SELECT code_emp FROM employes ORDER BY code_emp DESC LIMIT 1";
            $resultat = $this->connexion->query($req);

            if ($resultat->num_rows > 0) {
                $ligne = $resultat->fetch_all(MYSQLI_ASSOC);

                //reccuperation du code
                $code_emp = "";
                foreach ($ligne as $data) {
                    $code_emp = stripslashes($data['code_emp']);
                }

                //extraction des 4 derniers chiffres
                $code_emp = substr($code_emp, -4);

                //incrementation du nombre
                $code_emp += 1;

            }
            else {
                //s'il n'existe pas d'enregistrements dans la base de données
                $code_emp = 1;
            }
            $b = "EMP";
            $dat = date("Y");
            $dat = substr($dat, -2);
            $format = '%04d';
            $code = $dat . "" . $b . "" . sprintf($format, $code_emp);

            //on affecte au code le resultat
            $this->code_emp = $code;

            $sql = "INSERT INTO employes (code_emp, titre_emp, nom_emp, prenoms_emp, fonction_emp, departement_emp, email_emp, mdp, tel_emp)
                    VALUES ('$this->code_emp', '$this->titre_emp', '$this->nom_emp', '$this->prenoms_emp', '$this->fonction_emp', '$this->departement_emp', '$this->email_emp', '$this->mdp', '$this->tel_emp')";

            //exécution de la requête SQL:
            if ($result = mysqli_query($this->connexion, $sql))
                return TRUE;
            else
                return FALSE;
        }

        function modifier($code) {
            $sql = "UPDATE employes SET
                    titre_emp = '" . $this->titre_emp . "',
                    nom_emp = '" . $this->nom_emp . "',
                    prenoms_emp = '" . $this->prenoms_emp . "',
                    fonction_emp = '" . $this->fonction_emp . "',
                    departement_emp = '" . $this->departement_emp . "',
                    email_emp = '" . $this->email_emp . "',
                    tel_emp = '" . $this->tel_emp . "'
                    WHERE code_emp = '" . $code . "'";

            if ($result = mysqli_query($this->connexion, $sql))
                return TRUE;
            else
                return FALSE;
        }

        function supprimer($code) {
            if (session_id() == "")
                session_start();
            if ($code == $_SESSION['user_id'])
                return FALSE;
            else {
                $sql = "DELETE FROM employes WHERE code_emp = '" . $code . "'";

                if ($result = mysqli_query($this->connexion, $sql))
                    return TRUE;
                else
                    return FALSE;
            }
        }
    }
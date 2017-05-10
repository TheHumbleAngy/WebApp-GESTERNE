<?php
    include '../../fonctions.php';
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 30-Nov-16
     * Time: 2:44 PM
     */
    abstract class class_proformas {
        protected $num_fp;
        protected $code_four;
        protected $date_eta;
        protected $date_rcp;
        protected $notes;
        protected $iniFile;
    }

    class proformas extends class_proformas {
        function recuperer($num_fp) {
            $this->num_fp = $num_fp;
            $this->code_four = htmlspecialchars($_POST['code_four'], ENT_QUOTES);
            $this->date_eta = rev_date($_POST['date_eta']);
            $this->date_rcp = rev_date($_POST['date_rec']);
            $this->notes = addslashes($_POST['notes_fp']);
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
            return $this->num_fp;
        }

        function enregistrer() {
            $config = parse_ini_file($this->configpath($this->iniFile));

            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
            if ($connexion->connect_error)
                die($connexion->connect_error);

            $sql = "INSERT INTO proformas(
                                num_fp,
                                code_four,
                                dateetablissement_fp,
                                datereception_fp,
                                notes_fp)
                        VALUES ('$this->num_fp',
                                '$this->code_four',
                                '$this->date_eta',
                                '$this->date_rcp',
                                '$this->notes')";

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

            $sql = "DELETE FROM proformas WHERE num_fp = '" . $code . "'";

            if ($result = mysqli_query($connexion, $sql))
                return TRUE;
            else
                return FALSE;
        }
    }

    class detail_proformas extends proformas {
        protected $num_dp;
        protected $libelle_dp;
        protected $qte_dp;
        protected $pu_dp;
        protected $rem_dp;

        function recuperer_detail($libelle, $qte, $pu, $rem) {
            $this->libelle_dp = $libelle;
            $this->qte_dp = $qte;
            $this->pu_dp = $pu;
            $this->rem_dp = $rem;
            $this->iniFile = 'config.ini';

            return TRUE;
        }

        function enregistrer_detail($num_fp) {
            $config = parse_ini_file($this->configpath($this->iniFile));

            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
            if ($connexion->connect_error)
                die($connexion->connect_error);

            $sql = "SELECT num_dfp FROM details_proforma ORDER BY num_dfp DESC LIMIT 1";
            $result = $connexion->query($sql);

            if ($result->num_rows > 0) {
                $ligne = $result->fetch_all(MYSQLI_ASSOC);

                //reccuperation du code
                $num_dfp = "";
                foreach ($ligne as $data) {
                    $num_dfp = stripslashes($data['num_dfp']);
                }

                //extraction des 4 derniers chiffres
                $num_dfp = substr($num_dfp, -4);

                //incrementation du nombre
                $num_dfp += 1;

            } else {
                $num_dfp = 1;
            }

            $b = "DFP";
            $dat = date("Y");
            $dat = substr($dat, -2);
            $format = '%04d';
            $resultat = $dat . "" . $b . "" . sprintf($format, $num_dfp);

            $this->num_dp = $resultat;

            $sql = "INSERT INTO details_proforma (num_dfp, num_fp, libelle, qte_dfp, pu_dfp, remise_dfp)
                      VALUES ('$this->num_dp', '$num_fp', '$this->libelle_dp', '$this->qte_dp', '$this->pu_dp', '$this->rem_dp')";

            if ($result = mysqli_query($connexion, $sql))
                return TRUE;
            else
                return FALSE;
        }
    }
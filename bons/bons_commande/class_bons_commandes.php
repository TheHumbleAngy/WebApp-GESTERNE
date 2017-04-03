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
        function recuperation($num_bc)
        {
            $this->num_bc = $num_bc;
            $this->code_emp = $_SESSION['user_id'];
            $this->code_four = isset($_POST['code_four']) ? htmlspecialchars($_POST['code_four'], ENT_QUOTES) : htmlspecialchars($_POST['cod_four'], ENT_QUOTES);
            $this->date_bc = date("Y-m-d");
            $this->iniFile = 'config.ini';

            return TRUE;
        }

        function configpath(&$ini)
        {
            return $ini = '../' . $ini;
        }

        function enregistrement()
        {
            while (!$config = parse_ini_file($this->iniFile))
                $this->configpath($this->iniFile);
            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

            if ($connexion->connect_error)
                die($connexion->connect_error);

            $sql = "INSERT INTO bons_commande (num_bc, code_emp, code_four, date_bc) 
                    VALUES ('$this->num_bc', '$this->code_emp', '$this->code_four', '$this->date_bc')";

            if ($result = mysqli_query($connexion, $sql)) {
                $n = $_POST['nbr'];
                for ($i = 0; $i < $n; $i++) {
                    $req = "SELECT num_dbc FROM details_bon_commande ORDER BY num_dbc DESC LIMIT 1";
                    $resultat = $connexion->query($req);

                    $num_dbc = 0;
                    if ($resultat->num_rows > 0) {
                        $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
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

                    $num_dbc = $resultat;
                    $libelle_dbc = addslashes($_POST['libelle_dbc'][$i]);
                    $qte_dbc = htmlspecialchars($_POST['qte_dbc'][$i], ENT_QUOTES);
                    $pu_dbc = htmlspecialchars($_POST['pu_dbc'][$i], ENT_QUOTES);
                    $remise_dbc = htmlspecialchars($_POST['remise_dbc'][$i], ENT_QUOTES);

                    $sql = "INSERT INTO details_bon_commande (num_dbc, num_bc, libelle_dbc, qte_dbc, pu_dbc, remise_dbc) 
                            VALUES ('$num_dbc', '$this->num_bc', '$libelle_dbc', '$qte_dbc', '$pu_dbc', '$remise_dbc')";

//                    print_r($sql);
                    if (!($result = $connexion->query($sql))) {
                        return FALSE;
                    }
                }

                return TRUE;
            }
            else
                return FALSE;
        }
    }
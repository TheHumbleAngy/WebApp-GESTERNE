<?php
    error_reporting(E_ERROR);
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 30/11/2015
     * Time: 11:34
     */
    abstract class class_articles {
        public $code_art;
        public $designation_art;
        public $date_art;
        public $code_grp;
        public $description_art;
        public $stock_art;
        public $niveau_reappro_art;
        public $niveau_cible_art;

        protected $iniFile;
    }

    abstract class mouvements extends class_articles {
        public $code;       //le numero du mouvement
        public $date_mvt;   //date du mouvement en stock
        public $code_emp;   //code de l'employé
        public $num_dbs;   //code de la demande
//        protected $nbr;
    }

    abstract class details extends mouvements {
        public $num_detail;         //le num_dsort
        public $qte_detail;         //la qte_dsort
        public $code_art;           //le code de l'article
        public $designation_art;    //la designation de l'article
        public $rem;                //rem_dsort

        public $test_nvo = TRUE;           //une variable qui nous permet de savoir si le service (le details du mouvement) doit faire l'objet de création ou pas
    }

    class articles extends class_articles {
        function recuperation() {
            $this->stock_art = htmlspecialchars($_POST['stock_art'], ENT_QUOTES);
            $this->designation_art = addslashes($_POST['designation_art']);
            $this->date_art = date("Y/m/d");
            $this->code_grp = htmlspecialchars($_POST['code_grp'], ENT_QUOTES);
            $this->description_art = addslashes($_POST['description_art']);
            $this->niveau_cible_art = htmlspecialchars($_POST['niveau_cible_art'], ENT_QUOTES);
            $this->niveau_reappro_art = htmlspecialchars($_POST['niveau_reappro_art'], ENT_QUOTES);

            return TRUE;
        }

        function enregistrement() {
            //TODO: Les 2 lignes ci-dessous ont été ajoutées pour palier au problème de redirection du fichier config.ini depuis le fichier fonctions.php
            if (!$config = parse_ini_file('../../config.ini')) $config = parse_ini_file('../config.ini');
            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
            
            if ($connexion->connect_error)
                die($connexion->connect_error);

            $req = "SELECT code_art FROM articles ORDER BY code_art DESC LIMIT 1";
            $resultat = $connexion->query($req);

            //echo $resultat->num_rows;
            if ($resultat->num_rows > 0) {
                $ligne = $resultat->fetch_all(MYSQLI_ASSOC);

                //reccuperation du code
                $code_art = "";
                foreach ($ligne as $data) {
                    $code_art = stripslashes($data['code_art']);
                }

                //extraction des 4 derniers chiffres
                $code_art = substr($code_art, -4);

                //incrementation du nombre
                $code_art += 1;
            } else {
                //s'il n'existe pas d'enregistrements dans la base de données
                $code_art = 1;
            }

            $b = "ART";
            $dat = date("Y");
            $dat = substr($dat, -2);
            $format = '%04d';
            $code = $dat . "" . $b . "" . sprintf($format, $code_art);

            //on affecte au code le resultat
            $this->code_art = $code;

            $sql = "INSERT INTO articles (code_art, code_grp, designation_art, date_art, description_art, niveau_reappro_art, niveau_cible_art, stock_art)
                     VALUES ('$this->code_art', '$this->code_grp', '$this->designation_art', '$this->date_art', '$this->description_art', '$this->niveau_reappro_art', '$this->niveau_cible_art', '$this->stock_art')";
            //print_r($sql);
            if ($result = mysqli_query($connexion, $sql))
                return TRUE;
            else
                return FALSE;
        }

        function modification($code) {
            //TODO: Les 2 lignes ci-dessous ont été ajoutées pour palier au problème de redirection du fichier config.ini depuis le fichier fonctions.php
            if (!$config = parse_ini_file('../../config.ini')) $config = parse_ini_file('../config.ini');
            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
            
            if ($connexion->connect_error)
                die($connexion->connect_error);

            $sql = "UPDATE articles SET
                stock_art = '" . $this->stock_art . "',
                code_grp = '" . $this->code_grp . "',
                designation_art = '" . $this->designation_art . "',
                description_art = '" . $this->description_art . "',
                niveau_reappro_art = '" . $this->niveau_reappro_art . "',
                niveau_cible_art = '" . $this->niveau_cible_art . "'
                WHERE code_art = '" . $code . "'";

            if ($result = mysqli_query($connexion, $sql))
                return TRUE;
            else
                return FALSE;
        }

        function suppression($code) {
            //TODO: Les 2 lignes ci-dessous ont été ajoutées pour palier au problème de redirection du fichier config.ini depuis le fichier fonctions.php
            if (!$config = parse_ini_file('../../config.ini')) $config = parse_ini_file('../config.ini');
            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
            
            if ($connexion->connect_error)
                die($connexion->connect_error);

            $sql = "DELETE FROM articles WHERE code_art = '" . $code . "'"; //print_r($sql);

            if ($result = mysqli_query($connexion, $sql))
                return TRUE;
            else
                return FALSE;
        }
    }

    class entrees_articles extends mouvements {
        function recuperation($employe) {
            $this->date_mvt = date('Y-m-j');
            $this->code_emp = $employe;
            $this->iniFile = 'config.ini';

            return TRUE;
        }

        protected function configpath(&$ini) {
            return $ini = '../' . $ini;
        }

        function enregistrement($nbr, $arr_libelle, $arr_qte, $arr_obsv) {
            //TODO: Les 2 lignes ci-dessous ont été ajoutées pour palier au problème de redirection du fichier config.ini depuis le fichier fonctions.php
            while (!$config = parse_ini_file($this->iniFile))
                $this->configpath($this->iniFile);
            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
            
            if ($connexion->connect_error)
                die($connexion->connect_error);

            $this->code_emp = $_SESSION['user_id'];

            //Enregistrement du mouvement d'entree en stock
            $req = "SELECT num_entr FROM entrees_stock ORDER BY num_entr DESC LIMIT 1";
            $res = $connexion->query($req);

            if ($res->num_rows > 0) {
                $ligne = $res->fetch_all(MYSQLI_ASSOC);
                $num_entr = "";
                foreach ($ligne as $data)
                    $num_entr = stripslashes($data['num_entr']);
                $num_entr = substr($num_entr, -4);
                $num_entr += 1;
            } else
                $num_entr = 1;

            $b = "ENT";
            $dat = date("Y");
            $dat = substr($dat, -2);
            $format = '%04d';
            $resultat = $dat . "" . $b . "" . sprintf($format, $num_entr);

            //on affecte au code le resultat
            $this->code = $resultat;

            $sql = "INSERT INTO entrees_stock (num_entr, code_emp, date_entr)
                VALUES ('$this->code', '$this->code_emp', '$this->date_mvt')";

            if ($result = mysqli_query($connexion, $sql)) {

                //Saisie des détails
                for ($i = 0; $i < $nbr; $i++) {
                    $req = "SELECT num_dentr FROM details_entree ORDER BY num_dentr DESC LIMIT 1"; //print_r($req); echo $i . '<br>';
                    $res = $connexion->query($req);

                    if ($res->num_rows > 0) {
                        $ligne = $res->fetch_all(MYSQLI_ASSOC);

                        //reccuperation du code
                        $code_ds = "";
                        foreach ($ligne as $data) {
                            $code_ds = stripslashes($data['num_dentr']);
                        }

                        //extraction des 4 derniers chiffres
                        $code_ds = substr($code_ds, -4);

                        //incrementation du nombre
                        $code_ds += 1;
                    } else {
                        //s'il n'existe pas d'enregistrements dans la base de données
                        $code_ds = 1;
                    }

                    $b = "DE";
                    $dat = date("Y");
                    $dat = substr($dat, -2);
                    $format = '%04d';
                    $resultat = $dat . "" . $b . "" . sprintf($format, $code_ds);

                    //on affecte au code le resultat
                    $num_dentr = $resultat;

                    //Recuperation du code de l'article en cours, celui pour lequel l'entree d'article est en cours de saisie
//                    $libelle = addslashes($_POST['libelle'][$i]);
//                    echo $libelle[$i];

                    $sql = "SELECT code_art, stock_art FROM articles WHERE designation_art = '$arr_libelle[$i]'"; //print_r($sql);
                    $res = $connexion->query($sql);
                    $code_art = "";
                    $stock_art = "";
                    if ($res->num_rows > 0) {
                        $ligne = $res->fetch_all(MYSQLI_ASSOC);
                        foreach ($ligne as $row) {
                            $code_art = $row['code_art'];
                            $stock_art = $row['stock_art'];
                        }
                    }

                    //Recuperation de la quantite
                    $qte = $arr_qte[$i];
                    $rem = $arr_obsv[$i];
                    $stock_art += $qte;

                    //Enregistrement du detail d'entree
                    $sql = "INSERT INTO details_entree (num_dentr, num_entr, qte_dentr, code_art, rem_entr)
                    VALUES ('$num_dentr', '$this->code', '$qte', '$code_art', '$rem')"; //print_r($sql);

                    /*$result = mysqli_query($connexion, $sql);
                    print_r($result);*/
                    if ($result = mysqli_query($connexion, $sql)) {
                        //Mise à jour de la quantité de l'article en cours
                        $sql = "UPDATE articles SET stock_art = $stock_art WHERE code_art = '" . $code_art . "'";
                        mysqli_query($connexion, $sql);
                    }
                }

                return TRUE;
                //            return !$test ? TRUE : FALSE;
            } else
                return FALSE;
        }
    }

    class sorties_articles extends mouvements {
        protected $arr_num_dmd;
        protected $arr_num_dd;
        
        function recuperation($employe) {
            $this->date_mvt = date('Y-m-j');
            $this->code_emp = $employe;
            $this->num_dbs = htmlspecialchars($_POST['num_dmd'], ENT_QUOTES);
            $this->iniFile = 'config.ini';

            return TRUE;
        }

        protected function configpath(&$ini) {
            return $ini = '../' . $ini;
        }
        
        function recup_demandes($arr_num_dmd, $arr_num_dd) {
            $this->arr_num_dmd = $arr_num_dmd;
            $this->arr_num_dd = $arr_num_dd;
            
            return TRUE;
        }

        function enregistrement($nbr, $arr_libelle, $arr_qte, $arr_obsv) {
            //TODO: Les 2 lignes ci-dessous ont été ajoutées pour palier au problème de redirection du fichier config.ini depuis le fichier fonctions.php
            while (!$config = parse_ini_file($this->iniFile))
                $this->configpath($this->iniFile);
            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

            if ($connexion->connect_error)
                die($connexion->connect_error);

            $this->code_emp = $_SESSION['user_id'];

            //Enregistrement dans la table sorties_stock
            $req = "SELECT num_sort FROM sorties_stock ORDER BY num_sort DESC LIMIT 1";
            $resultat = $connexion->query($req);

            if ($resultat->num_rows > 0) {
                $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                $num_sort = "";
                foreach ($ligne as $data)
                    $num_sort = stripslashes($data['num_sort']);
                $num_sort = substr($num_sort, -4);
                $num_sort += 1;
            } else
                $num_sort = 1;

            $b = "SOR";
            $dat = date("Y");
            $dat = substr($dat, -2);
            $format = '%04d';
            $res = $dat . "" . $b . "" . sprintf($format, $num_sort);

            $this->code = $res;

            $sql = "INSERT INTO sorties_stock (num_sort, code_emp, date_sort)
                        VALUES ('$this->code', '$this->code_emp', '$this->date_mvt')";

            if ($result = mysqli_query($connexion, $sql)) {

                //Saisie des détails
                for ($i = 0; $i < $nbr; $i++) {
                    $sql = "SELECT num_dsort FROM details_sortie ORDER BY num_dsort DESC LIMIT 1";
                    $resultat = $connexion->query($sql);

                    if ($resultat->num_rows > 0) {
                        $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                        $code_ds = "";
                        foreach ($ligne as $data)
                            $code_ds = stripslashes($data['num_dsort']);
                        $code_ds = substr($code_ds, -4);
                        $code_ds += 1;
                    } else $code_ds = 1;

                    $b = "DS";
                    $dat = date("Y");
                    $dat = substr($dat, -2);
                    $format = '%04d';
                    $res = $dat . "" . $b . "" . sprintf($format, $code_ds);

                    $num_dsort = $res;

                    $sql = "SELECT code_art, stock_art FROM articles WHERE designation_art = '$arr_libelle[$i]'"; //print_r($sql);
                    $res = $connexion->query($sql);
                    $code_art = "";
                    $stock_art = "";
                    if ($res->num_rows > 0) {
                        $ligne = $res->fetch_all(MYSQLI_ASSOC);
                        foreach ($ligne as $row) {
                            $code_art = $row['code_art'];
                            $stock_art = $row['stock_art'];
                        }
                    }

                    //Recuperation de la quantite
                    if ($arr_qte[$i] != "null")
                        $qte = $arr_qte[$i];
                    else
                        $qte = 0;

                    //Recuperation de l'observation
                    if ($arr_obsv[$i] != "RAS")
                        $rem = $arr_obsv[$i];
                    else
                        $rem = "";

                    $stock_art -= $qte;

                    //SORTIE D'ARTICLES A PARTIR D'UNE DEMANDE
                    if (isset($this->arr_num_dmd) && isset($this->arr_num_dd)) {
                        $arr_num_dmd = $this->arr_num_dmd;
                        $arr_num_dd = $this->arr_num_dd;

                        $sql = "INSERT INTO details_sortie (num_dsort, num_sort, code_art, num_dd, qte_dsort, rem_dsort)
                                VALUES ('$num_dsort', '$this->code', '$code_art', '$arr_num_dd[$i]', '$qte', '$rem')";

                        if ($resultat = mysqli_query($connexion, $sql)) {
                            if ($rem === "") {
                                //MAJ de la quantité de chaque article dans la table articles en fonction de du details_sortie
                                $sql = "UPDATE articles SET stock_art = $stock_art WHERE code_art = '" . $code_art . "'";

                                if ($resultat = mysqli_query($connexion, $sql)) {
                                    //MAJ de la quantité servie de chaque details_demande
                                    $sql = "UPDATE details_demande SET qte_serv = qte_serv + $qte WHERE num_dd = '$arr_num_dd[$i]'";
                                    //                                        print_r($sql);
                                    mysqli_query($connexion, $sql);
                                } else
                                    return FALSE;
                            }

                        } else
                            return FALSE;
                    } else {
                        //SORTIE D'ARTICLES SIMPLE
                        $sql = "INSERT INTO details_sortie (num_dsort, num_sort, code_art, qte_dsort, rem_dsort)
                                VALUES ('$num_dsort', '$this->code', '$code_art', '$qte', '$rem')";

                        if (!($resultat = mysqli_query($connexion, $sql)))
                            return FALSE;
                    }
                }
                return TRUE;
            } else
                return FALSE;
        }
    }


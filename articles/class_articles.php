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

        function enregistrement() {
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
                $nbr = $_POST['nbr']; //echo $nbr;
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
                    $libelle = addslashes($_POST['libelle'][$i]);

                    $sql = "SELECT code_art, stock_art FROM articles WHERE designation_art = '$libelle'"; //print_r($sql); echo $i . '<br>';
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
                    $qte = $_POST['qte'][$i];
                    $rem = htmlspecialchars($_POST['cmt'][$i], ENT_QUOTES);
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

        function enregistrement() {
            //TODO: Les 2 lignes ci-dessous ont été ajoutées pour palier au problème de redirection du fichier config.ini depuis le fichier fonctions.php
            while (!$config = parse_ini_file($this->iniFile))
                $this->configpath($this->iniFile);
            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

            if ($connexion->connect_error)
                die($connexion->connect_error);

            //TODO: Enregistrement dans la table sorties_stock
            $req = "SELECT num_sort FROM sorties_stock ORDER BY num_sort DESC LIMIT 1";
            $resultat = $connexion->query($req);

            if ($resultat->num_rows > 0) {
                $ligne = $resultat->fetch_all(MYSQLI_ASSOC);
                $num_sort = "";
                foreach ($ligne as $data)
                    $num_sort = stripslashes($data['num_sort']);
                $num_sort = substr($num_sort, -4);
                $num_sort += 1;
            }
            else
                $num_sort = 1;

            $b = "SOR";
            $dat = date("Y");
            $dat = substr($dat, -2);
            $format = '%04d';
            $res = $dat . "" . $b . "" . sprintf($format, $num_sort);

            $this->code = $res;

            $sql = "INSERT INTO sorties_stock (num_sort, code_emp, date_sort)
                    VALUES ('$this->code', '$this->code_emp', '$this->date_mvt')";

            if ($resultat = mysqli_query($connexion, $sql)) {
                if (isset($_POST['nbr_dmd'])) { //echo "demande";
                    $nbr = $_POST['nbr_dmd'];

                    //TODO: Enregistrement dans la table details_sortie
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
                        }
                        else $code_ds = 1;

                        $b = "DS";
                        $dat = date("Y");
                        $dat = substr($dat, -2);
                        $format = '%04d';
                        $res = $dat . "" . $b . "" . sprintf($format, $code_ds);

                        $num_dsort = $res;

                        //Recuperation du code de l'article en cours, celui pour lequel l'entree d'article est en cours de saisie
                        $libelle = addslashes($_POST['libelle_dd'][$i]);
                        $detail_demande = $_POST['num_details_demande'][$i];

                        $sql = "SELECT code_art, stock_art FROM articles WHERE designation_art = '$libelle'";
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
                        $qte = $_POST['qte_serv'][$i];
                        $rem = htmlspecialchars($_POST['obsv'][$i], ENT_QUOTES);
                        $stock_art -= $qte;

                        $sql = "INSERT INTO details_sortie (num_dsort, num_sort, code_art, qte_dsort, rem_dsort, num_dd)
                            VALUES ('$num_dsort', '$this->code', '$code_art', '$qte', '$rem', '$detail_demande')";

                        if ($resultat = mysqli_query($connexion, $sql)) {
                            //TODO: MAJ de la quantité de chaque article dans la table articles en fonction de du details_sortie
                            $sql = "UPDATE articles SET stock_art = $stock_art WHERE code_art = '" . $code_art . "'";

                            if ($resultat = mysqli_query($connexion, $sql)) {
                                //TODO: MAJ de la quantité servie de chaque details_demande
                                $sql = "UPDATE details_demande SET qte_serv = qte_serv + $qte WHERE num_dd = '$detail_demande'";

                                if ($resultat = mysqli_query($connexion, $sql)) {
                                    //Mise à jour du statut de chaque détail, satisfait/non satisfait
                                    $sql = "SELECT qte_dd, qte_serv FROM details_demande WHERE num_dd = '$detail_demande'";
                                    $res = $connexion->query($sql);

                                    $lines = $res->fetch_all(MYSQLI_ASSOC);
                                    foreach ($lines as $line) {
                                        $qte_dd = $line['qte_dd'];
                                        $qte_serv = $line['qte_serv'];
                                    }

                                    if ($qte_serv >= $qte_dd) {
                                        $sql = "UPDATE details_demande SET statut_dd = 'satisfait' WHERE num_dd = '$detail_demande'";
                                        mysqli_query($connexion, $sql);
                                    }
                                } else
                                    return FALSE;
                            } else
                                return FALSE;
                        } else
                            return FALSE;
                    }

                    //Recuperation des numeros de demandes en vue de la sauvegarde dans la table demandes_sorties_stock
                    $num_demandes = $_POST['num_demandes'];
                    $length = sizeof($num_demandes);
                    $j = 0;
                    $demandes[$j] = $num_demandes[0];
                    for ($i = 1; $i < $length; $i++) {
                        if ($num_demandes[$i] != $num_demandes[$i - 1]) {
                            $j++;
                            $demandes[$j] = $num_demandes[$i];
                        }
                    }
                    $length = sizeof($demandes); //print_r($demandes); echo "<br>";

                    //Vérification du statut de chaque détail pour MAJ du statut de la demande en elle même
                    for ($i = 0; $i < $length; $i++) {
                        $sql = "SELECT statut_dd FROM details_demande WHERE num_dbs = '$demandes[$i]'";
                        if ($result = mysqli_query($connexion, $sql)) {
                            $lines = $result->fetch_all(MYSQLI_ASSOC);
                            $test = TRUE;
                            foreach ($lines as $line) {
                                if ($line['statut_dd'] != "satisfait") {
                                    $test = FALSE;
                                    break;
                                }
                            }
                            if ($test) {
                                $sql = "UPDATE demandes SET statut = 'satisfaite' WHERE num_dbs = '$demandes[$i]'"; //echo "<br>";
                                mysqli_query($connexion, $sql);
                            }
                        }

                        //TODO: MAJ de la table demandes_sorties_stock
                        $sql = "INSERT INTO demandes_sorties_stock (num_dbs, num_sort, date_dss)
                            VALUES ('$demandes[$i]', '$this->code', '$this->date_mvt')";

                        mysqli_query($connexion, $sql);
                    }

                    return TRUE;
                } elseif (isset($_POST['nbr'])) { //echo "Random";
                    $nbr = $_POST['nbr'];

                    //TODO: Enregistrement dans la table details_sortie
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
                        }
                        else $code_ds = 1;

                        $b = "DS";
                        $dat = date("Y");
                        $dat = substr($dat, -2);
                        $format = '%04d';
                        $res = $dat . "" . $b . "" . sprintf($format, $code_ds);

                        $num_dsort = $res;

                        //Recuperation du code de l'article en cours, celui pour lequel l'entree d'article est en cours de saisie
                        $libelle = addslashes($_POST['libelle_dd'][$i]);

                        $sql = "SELECT code_art, stock_art FROM articles WHERE designation_art = '$libelle'";
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
                        $qte = $_POST['qte_serv'][$i];
                        $rem = htmlspecialchars($_POST['obsv'][$i], ENT_QUOTES);
                        $stock_art -= $qte;
                        $test = TRUE;

                        if ($stock_art >= 0) {
                            $sql = "INSERT INTO details_sortie (num_dsort, num_sort, code_art, qte_dsort, rem_dsort)
                            VALUES ('$num_dsort', '$this->code', '$code_art', '$qte', '$rem')";

                            mysqli_query($connexion, $sql);
                        } else {
                            $test = FALSE;
                            break;
                        }
                    }

                    if ($test) {
                        //TODO: MAJ de la quantité de chaque article dans la table articles en fonction de du details_sortie
                        for ($i = 0; $i < $nbr; $i++) {
                            //Recuperation du code de l'article en cours, celui pour lequel l'entree d'article est en cours de saisie
                            $libelle = addslashes($_POST['libelle_dd'][$i]);

                            $sql = "SELECT code_art, stock_art FROM articles WHERE designation_art = '$libelle'";
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
                            $qte = $_POST['qte_serv'][$i];
                            $stock_art -= $qte;

                            $sql = "UPDATE articles SET stock_art = $stock_art WHERE code_art = '" . $code_art . "'";

                            mysqli_query($connexion, $sql);
                        }
                    } else {
                        $sql = "DELETE FROM details_sortie WHERE num_sort = '" . $this->code . "'";
                        if ($resultat = mysqli_query($connexion, $sql)) {
                            $sql = "DELETE FROM sorties_stock WHERE num_sort = '" . $this->code . "'";
                            if ($resultat = mysqli_query($connexion, $sql)) {
                                echo "
                                    <div class='alert alert-warning alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                                        <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                                            <span aria-hidden='true'>&times;</span>
                                        </button>
                                        <strong>Attention!</strong><br/> La quantité à sortir pour l'article '<strong>" . $libelle . "</strong>' est supérieure à celle disponible en stock. L'opération a été stoppée. 
                                    </div>
                                    ";
                            }
                        }
                    }

                    return TRUE;
                }
            } else
                return FALSE;

            return TRUE;
        }
    }


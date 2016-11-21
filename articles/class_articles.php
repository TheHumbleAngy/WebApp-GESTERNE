<?php

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
    }

    abstract class mouvements extends class_articles {
        public $code;       //le numero du mouvement
        public $date_mvt;   //date du mouvement en stock
        public $code_emp;   //code de l'employé
        public $code_dbs;   //code de la demande
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
        function recuperation() {
//            $this->type_mvt = htmlspecialchars($_POST['type_mvt'], ENT_QUOTES);
            $this->date_mvt = date('Y-m-j');

            return TRUE;
        }

        function enregistrement() {
            //TODO: Les 2 lignes ci-dessous ont été ajoutées pour palier au problème de redirection du fichier config.ini depuis le fichier fonctions.php
            if (!$config = parse_ini_file('../../config.ini')) $config = parse_ini_file('../config.ini');
            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
            
            if ($connexion->connect_error)
                die($connexion->connect_error);

            $this->code_emp = $_SESSION['user_id'];

            //Enregistrement du mouvement d'entree en stock
            $req = "SELECT num_entr FROM entrees_stock ORDER BY num_entr DESC LIMIT 1";
            $res = $connexion->query($req);

            if ($res->num_rows > 0) {
                $ligne = $res->fetch_all(MYSQLI_ASSOC);

                //reccuperation du code
                $num_entr = "";
                foreach ($ligne as $data) {
                    $num_entr = stripslashes($data['num_entr']);
                }

                //extraction des 4 derniers chiffres
                $num_entr = substr($num_entr, -4);

                //incrementation du nombre
                $num_entr += 1;
            } else {
                //s'il n'existe pas d'enregistrements dans la base de données
                $num_entr = 1;
            }

            $b = "ENT";
            $dat = date("Y");
            $dat = substr($dat, -2);
            $format = '%04d';
            $resultat = $dat . "" . $b . "" . sprintf($format, $num_entr);

            //on affecte au code le resultat
            $num_entr = $resultat;

            $sql = "INSERT INTO entrees_stock (num_entr, date_entr, code_emp)
                VALUES ('$num_entr', '$this->date_mvt', '$this->code_emp')";

            if ($result = mysqli_query($connexion, $sql)) {

                //Saisie des détails
                $nbr = $_POST['nbr']; //echo $nbr;
                for ($i = 0; $i < $nbr; $i++) {
                    $req = "SELECT num_dentr FROM details_entree ORDER BY num_dentr DESC LIMIT 1"; //print_r($req); echo $i . '<br>';
                    $res = $connexion->query($req);

                    if ($res->num_rows > 0) {
                        $ligne = $res->fetch_all(MYSQLI_ASSOC);

                        //reccuperation du code
                        $code_de = "";
                        foreach ($ligne as $data) {
                            $code_de = stripslashes($data['num_dentr']);
                        }

                        //extraction des 4 derniers chiffres
                        $code_de = substr($code_de, -4);

                        //incrementation du nombre
                        $code_de += 1;
                    } else {
                        //s'il n'existe pas d'enregistrements dans la base de données
                        $code_de = 1;
                    }

                    $b = "DE";
                    $dat = date("Y");
                    $dat = substr($dat, -2);
                    $format = '%04d';
                    $resultat = $dat . "" . $b . "" . sprintf($format, $code_de);

                    //on affecte au code le resultat
                    $num_dentr = $resultat;

                    //Recuperation du code l'article en cours, celui pour lequel l'entree d'article est encours de saisie
                    $libelle = addslashes($_POST['libelle'][$i]);

                    $sql = sprintf("SELECT code_art, stock_art FROM articles WHERE designation_art = '%s'", $libelle); //print_r($sql); echo $i . '<br>';
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
                    VALUES ('$num_dentr', '$num_entr', '$qte', '$code_art', '$rem')"; //print_r($sql);

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
            $this->code_dbs = htmlspecialchars($_POST['num_dmd'], ENT_QUOTES);

            return TRUE;
        }

        function enregistrement() {
            //TODO: Les 2 lignes ci-dessous ont été ajoutées pour palier au problème de redirection du fichier config.ini depuis le fichier fonctions.php
            if (!$config = parse_ini_file('../../config.ini')) $config = parse_ini_file('../config.ini');
            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
            
            if ($connexion->connect_error)
                die($connexion->connect_error);

            $req = "SELECT num_sort FROM sorties_stock ORDER BY num_sort DESC LIMIT 1"; //print_r($req);
            $res = $connexion->query($req);

            if ($res->num_rows > 0) {
                $ligne = $res->fetch_all(MYSQLI_ASSOC);

                //reccuperation du code
                $num_sort = "";
                foreach ($ligne as $data) {
                    $num_sort = stripslashes($data['num_sort']);
                }

                //extraction des 4 derniers chiffres
                $num_sort = substr($num_sort, -4);

                //incrementation du nombre
                $num_sort += 1;
            } else {
                //s'il n'existe pas d'enregistrements dans la base de données
                $num_sort = 1;
            }

            $b = "SOR";
            $dat = date("Y");
            $dat = substr($dat, -2);
            $format = '%04d';
            $resultat = $dat . "" . $b . "" . sprintf($format, $num_sort);

            //on affecte au code le resultat
            $this->code = $resultat;

            $sql = "INSERT INTO sorties_stock (num_sort, code_dbs, date_sort, code_emp)
                    VALUES ('$this->code', '$this->code_dbs', '$this->date_mvt', '$this->code_emp')"; //print_r($sql); echo '<br><br>';

            if ($result = mysqli_query($connexion, $sql))
                return TRUE;
            else
                return FALSE;
        }
    }

    class details_sortie extends details {
        function recuperation($num_dmd, $num_sort, $i) {
            $this->code_dbs = $num_dmd;
            $this->code = $num_sort;
            $this->qte_detail = (int)htmlspecialchars($_POST['qte_serv'][$i], ENT_QUOTES);
            $this->rem = htmlspecialchars($_POST['obsv'][$i], ENT_QUOTES);

            //recuperation du code de l'article à partir du libelle
            //TODO: Les 2 lignes ci-dessous ont été ajoutées pour palier au problème de redirection du fichier config.ini depuis le fichier fonctions.php
            if (!$config = parse_ini_file('../../config.ini')) $config = parse_ini_file('../config.ini');
            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
            
            $art = htmlspecialchars($_POST['libelle_dd'][$i], ENT_NOQUOTES);
            $art = $connexion->real_escape_string($art);
            $sql = "SELECT code_art, designation_art FROM articles WHERE designation_art = '" . $art . "'";
            if ($result = $connexion->query($sql)) {
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $this->code_art = $row['code_art'];
                    $this->designation_art = $row['designation_art'];
                    $result->free();

                    $this->test_nvo = FALSE;
                } else
                    $this->designation_art = $art;
                
                return TRUE;
            } else
                return FALSE;
        }

        function afficher_detail() {
            echo "num_detail " . $this->num_detail;
            echo '<br>';
            echo "code " . $this->code;
            echo '<br>';
            echo "code_art " . $this->code_art;
            echo '<br>';
            echo "qte_detail " . $this->qte_detail;
            echo '<br>';
            echo "rem " . $this->rem;
            echo '<br>';
            echo '<br>';
        }

        function enregistrement() {
            //TODO: Les 2 lignes ci-dessous ont été ajoutées pour palier au problème de redirection du fichier config.ini depuis le fichier fonctions.php
            if (!$config = parse_ini_file('../../config.ini')) $config = parse_ini_file('../config.ini');
            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

            if ($connexion->connect_error)
                die($connexion->connect_error);

            //Enregistrement de la sortie dans la table "sortie_stock"
            $req = "SELECT num_dsort FROM details_sortie ORDER BY num_dsort DESC LIMIT 1";
            
            if ($res = $connexion->query($req)) {
                $ligne = $res->fetch_assoc();

                //reccuperation du code
                $code_ds = stripslashes($ligne['num_dsort']);

                //extraction des 4 derniers chiffres
                $code_ds = substr($code_ds, -4);

                //incrementation du nombre
                $code_ds += 1;
            } else {
                //s'il n'existe pas d'enregistrements dans la base de données
                $code_ds = 1;
            }

            $b = "DS";
            $dat = date("Y");
            $dat = substr($dat, -2);
            $format = '%04d';
            $resultat = $dat . "" . $b . "" . sprintf($format, $code_ds);

            //on affecte au code le resultat
            $this->num_detail = $resultat;

            //traitement d'un service
            $test_service = FALSE;
            if ($this->rem <> "") {
                if ($this->rem === "ok") {
                    $test_service = TRUE;
                    //on interroge la BD pour savoir si le service demandé existe
                    //On vérifie si le libellé sui figure sur la demande existe vraiment dans la liste des
                    //articles, soit dans la BD
                    //Si le libellé n'existe pas, on crée un "article" à partir de ce libellé
                    if ($this->test_nvo == TRUE) {
                        /*$sql = "SELECT * FROM articles WHERE code_art = '" . $this->designation_art . "'";
                        print_r($sql);
                        $res = $connexion->query($sql);
                        //Si le service n'existe pas... on le crée en tant qu'article
                        if ($res->num_rows == 0) {*/
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
                                //s'il n'existe pas d'enregistrements dans la base de donn�es
                                $code_art = 1;
                            }
        
                            $b = "ART";
                            $dat = date("Y");
                            $dat = substr($dat, -2);
                            $format = '%04d';
                            $code = $dat . "" . $b . "" . sprintf($format, $code_art);
        
                            //on affecte au code le resultat
                            $code_art = $code;
                            $this->code_art = $code;
        
                            //16GRP02 étant le code du groupe "divers"...
                            $code_grp = "16GRP02";
                            $date_art = date("Y/m/d");
                            $sql = "INSERT INTO articles (code_art, code_grp, designation_art, date_art, description_art)
                                VALUES ('$code_art', '$code_grp', '$this->designation_art', '$date_art', 'Prestation de service')";
                            if (!($resultat = $connexion->query($sql))) {
                                echo "Erreur lors de la création du service";
                            } else
                                $this->code_art = $code_art;
                        //}
                    }
                }
            }

            $stock_art = 0;
            if (!$test_service) {
                //Recuperation du nombre en stock de l'article
                $sql = "SELECT stock_art FROM articles WHERE code_art = '" . $this->code_art . "'";
                if (($res = $connexion->query($sql)) && ($res->num_rows > 0)) {
                    $row = $res->fetch_assoc();
                    $stock_art = (int)$row['stock_art'];
                }
                $stock_art -= (int)$this->qte_detail;
            }

            //Enregistrement du detail de sortie
            $sql = "INSERT INTO details_sortie (num_dsort, num_sort, qte_dsort, code_art, rem_dsort)
                    VALUES ('$this->num_detail', '$this->code', '$this->qte_detail', '$this->code_art', '$this->rem')";

            /*$this->afficher_details();*/
            if ($result = mysqli_query($connexion, $sql)) {
                if (!$test_service) {
                    //Mise à jour de la quantité de l'article en cours
                    $sql = "UPDATE articles SET stock_art = $stock_art WHERE code_art = '" . $this->code_art . "'";
                    mysqli_query($connexion, $sql) or exit(mysqli_error($connexion));
                }
                
                //Mise à jour de la demande en elle même
                //Mise à jour des détails de la demande dans un premier temps
                $designation_art = $connexion->real_escape_string($this->designation_art);
                $sql = "SELECT qte_serv, qte_dd FROM details_demande WHERE code_dbs = '" . $this->code_dbs . "' AND libelle_dd = '" . $designation_art . "'";
                $qte_serv = 0;
                $qte_dd = 0;
                if ($res = $connexion->query($sql)) {
                    $row = $res->fetch_assoc();
                    $qte_serv = (int)$row['qte_serv'];
                    $qte_dd = (int)$row['qte_dd'];
                    $res->free();
                }
                $qte_serv += (int)$this->qte_detail;

                //Si la qte servie est >= à la qte demandée, l'article en question est satisfait et MAJ,
                //sinon, il est seulement MAJ
                if ($qte_serv >= $qte_dd || ($this->rem === "ok"))
                    $sql = "UPDATE details_demande SET qte_serv = $qte_serv, statut_dd = 'satisfait' WHERE code_dbs = '" . $this->code_dbs . "' AND libelle_dd = '" . $designation_art . "'";
                else
                    $sql = "UPDATE details_demande SET qte_serv = $qte_serv WHERE code_dbs = '" . $this->code_dbs . "' AND libelle_dd = '" . $designation_art . "'";

                $res = mysqli_query($connexion, $sql) or exit(print_r($sql));

                //MAJ de la demande après vérification du statut de CHAQUE article sur la demande
                $sql = "SELECT statut_dd FROM details_demande WHERE code_dbs = '" . $this->code_dbs . "'";
                $test = FALSE;
                if ($res = $connexion->query($sql)) {
                    while ($row = $res->fetch_assoc()) {
                        if ($row['statut_dd'] === "satisfait")
                            $test = TRUE;
                        else {
                            $test = FALSE;
                            break;
                        }
                    }
                    $res->free();
                    if ($test) {
                        $sql = "UPDATE demandes SET statut = 'satisfaite' WHERE code_dbs = '" . $this->code_dbs . "'";
                        if ($res = $connexion->query($sql))
                            return TRUE;
                    }
                }

                return TRUE;
            }

            return TRUE;
        }
    }
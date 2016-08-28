<?php
    /**
     * Created by PhpStorm.
     * User: Ange Kouakou
     * Date: 3/13/14
     * Time: 11:46 AM
     */
    
    function db_connect() {
        static $connexion;

        if (!isset($connexion)) {
            $config = parse_ini_file('../config.ini');
            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
        }

        if ($connexion === FALSE) 
            return mysqli_connect_errno();
        else
            return $connexion;
    }

    function login($email, $password) {
        $connexion = db_connect();

        $req = "SELECT * FROM employes WHERE email_emp = '" . $email . "' AND mdp = '" . $password . "'";
        if ($resultat = $connexion->query($req)) {
            if ($resultat->num_rows == 1) {
                $ligne = $resultat->fetch_array(MYSQLI_ASSOC);
                $code_emp = $ligne['code_emp'];

                $req = "SELECT * FROM droits WHERE code_emp = '" . $code_emp . "'";
                if ($result = $connexion->query($req)) {
                    if ($result->num_rows === 1) {
                        session_start();
                        $_SESSION['etat_connecte'] = $ligne['etat_connecte'];

                        if ($ligne['etat_connecte'] == 0) {
                            $_SESSION['user_id'] = $ligne['code_emp'];
                            $_SESSION['email'] = $email;
                            $_SESSION['nom_emp'] = $ligne['nom_emp'];
                            $_SESSION['prenoms_emp'] = $ligne['prenoms_emp'];
                            $_SESSION['mdp'] = $password;
                            $_SESSION['test_login'] = TRUE;

                            //On met à jour la propriété etat_connecte de la table employés
                            $req = "UPDATE employes SET etat_connecte = 1 WHERE email_emp = '" . $email . "'";

                            $resultat = $connexion->query($req);

                            if (!$resultat) {
                                //die($connexion->error);
                                return FALSE;
                            }
                            else {
                                $_SESSION['etat_connecte'] = 1;

                                return TRUE;
                            }
                        } else return FALSE;
                    } else return FALSE;
                } else return FALSE;
            } else return FALSE;
        } else return FALSE;
    }

    function rediriger($page) {
        header('Location: ' . $page);
        exit;
    }

    function etat_connection($etat_connecte) {
        //On vérifie si l'utilisateur n'est pas connecté dans un autre navigateur
        if ($etat_connecte == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function etat_deconnection() {
        $qry = "UPDATE employes SET etat_connecte='0' WHERE email_emp='" . $_SESSION['email'] . "'";
        if ($result = $connexion->prepare($qry)) {
            $result->execute();
            $result->store_result();

            return TRUE;
        } else {
            return FALSE;
        }
    }

    function esc_url($url) {

        if ('' == $url) {
            return $url;
        }

        $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);

        $strip = array('%0d', '%0a', '%0D', '%0A');
        $url = (string)$url;

        $count = 1;
        while ($count) {
            $url = str_replace($strip, '', $url, $count);
        }

        $url = str_replace(';//', '://', $url);

        $url = htmlentities($url);

        $url = str_replace('&amp;', '&#038;', $url);
        $url = str_replace("'", '&#039;', $url);

        $url = htmlspecialchars($url);

        if ($url[0] !== '/') {
            // We're only interested in relative links from $_SERVER['PHP_SELF']
            return '';
        } else {
            return $url;
        }
    }

    function recuperation() {
        if (isset($_GET['id'])) {
            $_SESSION['code_temp'] = $_GET['id'];

            return TRUE;
        } else {
            return FALSE;
        }
    }

    function fonction_modif_employes() {
        if (recuperation()) {
            header('Location: form_principale.php?page=employes/modification_employes');
        } else {
            echo $_GET['id'];
            exit ('Erreur lors de la reccuperation du code');
        }
    }

    function fonction_modif_fournisseurs() {
        if (recuperation()) {
            header('Location: form_principale.php?page=fournisseurs/modification_fournisseurs');
        } else {
            echo $_GET['id'];
            exit ('Erreur lors de la reccuperation du code');
        }
    }

    function process_modif_reglements() {
        if (recuperation()) {
            header('Location: form_principale.php?page=reglements/modification_reglements');
        } else {
            echo $_GET['id'];
            exit ('Erreur lors de la reccuperation du code');
        }
    }

    function process_modif_biens_ou_services() {
        if (recuperation()) {
            header('Location: form_principale.php?page=articles/articles/modification_biens_ou_services');
        } else {
            echo $_GET['id'];
            exit ('Erreur lors de la reccuperation du code');
        }
    }

    function process_modif_demandes() {
        if (recuperation()) {
            header('Location: form_principale.php?page=articles/demandes/modif_demandes');
        } else {
            echo $_GET['id'];
            exit ('Erreur lors de la reccuperation du code');
        }
    }

    function process_modif_factures() {
        if (recuperation()) {
            header('Location: form_principale.php?page=factures/modification_factures');
        } else {
            echo $_GET['id'];
            exit ('Erreur lors de la reccuperation du code');
        }
    }

    function process_modif_factures_proforma() {
        if (recuperation()) {
            header('Location: form_principale.php?page=proformas/modification_factures_proforma');
        } else {
            echo $_GET['id'];
            exit ('Erreur lors de la reccuperation du code');
        }
    }

    function process_modif_bons_livraison() {
        if (recuperation()) {
            header('Location: form_principale.php?page=bons_livraison/modif_bons_livraison');
        } else {
            echo $_GET['id'];
            exit ('Erreur lors de la reccuperation du code');
        }
    }

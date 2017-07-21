<?php
    require_once 'fonctions.php';

    $connexion = db_connect();
    session_start();

    if (isset($_POST['txt_email'], $_POST['txt_password'])) {
        $email = $_POST['txt_email'];
        $password = $_POST['txt_password']; //login($email, $password);

        if (login($email, $password)) {
            header('Location: form_principale.php');
        } else {
            if (isset($_SESSION['etat_connecte'])) { //echo $_SESSION['etat_connecte'];
                $statut = $_SESSION['etat_connecte'];
                session_destroy();
                $_SESSION = array();
                if ($statut == 1)
                    header('Location: index.php?error=2');
                else
                    header('Location: index.php?error=1');
            } else
                header('Location: index.php?error=1');
        }
    }
    else if (isset($_GET['event']) && $_GET['event'] == "logout") {

        $qry = "UPDATE employes SET etat_connecte = '0' WHERE email_emp = '" . $_SESSION['email'] . "'";
        $result = $connexion->query($qry);

        if (!$result)
            exit ($connexion->error);
        else {
            // get session parameters
            $params = session_get_cookie_params();

            // Delete the actual cookie.
            setcookie(session_name(),
                '', time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]);

            // Destroy session
            session_destroy();

            // Unset all session values
            $_SESSION = array();
            header('Location:index.php');
        }
    }
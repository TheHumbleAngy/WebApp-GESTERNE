<?php
    require_once 'fonctions.php';
    
//    session_start();

    $connexion = db_connect();
    
    if (isset($_POST['txt_email'], $_POST['txt_password'])) {
        $email = $_POST['txt_email'];
        $password = $_POST['txt_password'];
        
        if (login($email, $password)) {
            header('Location: form_principale.php');
        } else {
            $status = $_SESSION['etat_connecte'];
            if ($status == 1) {
                header('Location: index.php?error=2');
            } else {
                header('Location: index.php?error=1');
            }
        }
    } else {
        
        $qry = "UPDATE employes SET etat_connecte = '0' WHERE email_emp = '" . $_SESSION['email'] . "'";
        $result = $connexion->query($qry);
        
        if (!$result)
            exit ($connexion->error);
        else {
            // Unset all session values
            $_SESSION = array();
            
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
            header('Location:index.php');
        }
    }



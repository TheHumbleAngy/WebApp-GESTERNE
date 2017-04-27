<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 28-Mar-17
     * Time: 4:20 PM
     */

    class demandes_absence extends class_demandes {
        protected $motif_dab;
        protected $lieu_dab;
        protected $debut_dab;
        protected $fin_dab;
        protected $duree_dab;

        function recuperer($code){
            $this->code_emp = $code;
            $this->date_dbs = date("Y-m-d");
            $this->motif_dab = addslashes($_POST['motif']);
            $this->lieu_dab = addslashes($_POST['lieu']);
            $this->debut_dab = rev_date(addslashes($_POST['debut']));
            $this->fin_dab = rev_date(addslashes($_POST['fin']));
            $this->duree_dab = addslashes($_POST['duree']);

            return true;
        }

        function afficher() {
            echo $this->code_emp; echo '<br>';
            echo $this->date_dbs; echo '<br>';
            echo $this->motif_dab; echo '<br>';
            echo $this->lieu_dab; echo '<br>';
            echo $this->debut_dab; echo '<br>';
            echo $this->fin_dab; echo '<br>';
            echo $this->duree_dab; echo '<br>';
        }

        function enregistrer($emp) {
            if (!$config = parse_ini_file('../../../config.ini')) $config = parse_ini_file('../../config.ini');
            $connexion = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

            if ($connexion->connect_error)
                die($connexion->connect_error);

            $sql = "SELECT code_dab FROM demandes_absence ORDER BY code_dab DESC LIMIT 1";
            $resultat = $connexion->query($sql);

            if ($resultat->num_rows > 0) {
                $lignes = $resultat->fetch_all(MYSQLI_ASSOC);
                $code = "";
                foreach ($lignes as $ligne)
                    $code = stripslashes($ligne['code_dab']);

                $code = substr($code, -4);
                $code += 1;
            } else
                $code = 1;

            $b = "DAB";
            $dat = date("Y");
            $dat = substr($dat, -2);
            $format = '%04d';
            $info = $dat . "" . $b . "" . sprintf($format, $code);
            $this->num_dmd = $info;

            $sql = "INSERT INTO demandes_absence (code_dab, code_emp, date_dab, motif_dab, lieu_dab, debut_dab, fin_dab, duree_dab) 
                VALUES ('$this->num_dmd', '$emp', '$this->date_dbs', '$this->motif_dab', '$this->lieu_dab', '$this->debut_dab', '$this->fin_dab', '$this->duree_dab')";

            if ($resultat = mysqli_query($connexion, $sql))
                return TRUE;
            else
                return FALSE;
        }
    }
<?php

    session_start();

    // print_r($_REQUEST)
    if(isset($_POST['submit']) && !empty($_POST['idnew_table']) && !empty($_POST['senha']))
    {
        //Acessa
        include_once('config.php');
        $idnew_table = $_POST['idnew_table'];
        $senha = $_POST['senha'];

        // print_r('Email: ' . $email);
        // print_r('<br>');
        // print_r('Senha: ' . $senha);

        $sql = "SELECT * FROM user WHERE idnew_table = '$idnew_table' and senha = '$senha'";

        $result = $conexao->query($sql);

        // print_r($sql);
        // print_r($result);

        if(mysqli_num_rows($result) < 1)
        {
            unset($_SESSION['idnew_table']);
            unset($_SESSION['senha']);
            header('location: login.php');
        }
        else
        {
            $_SESSION['idnew_table'] = $idnew_table;
            $_SESSION['senha'] = $senha;
            $_SESSION['nome'] = $nome;
            header('location: sistema.php');
        }
    }
    else
    {
        //Não Acessa
        header('location: login.php');
    }
?>
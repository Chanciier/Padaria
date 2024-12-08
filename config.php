<?php

    $dbHost = 'localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'padaria';

    $conexao = new mysqli($dbHost,$dbUsername,$dbPassword,$dbName);

    // if($conexao ->connect_errno)
    // {
    //     echo "erro";
    // }
    // else
    // {
    //     echo "conexão efetuada com sucesso";
    // }
?>
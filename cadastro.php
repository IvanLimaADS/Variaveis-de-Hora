<?php
include 'var/conect.php';
ob_start();
session_start();

if (!isset($_SESSION['idcliente'])) 
{
  header("location: index.php");
}
if(isset($_REQUEST['sair']))
{
  session_destroy();
  session_unset(['idcliente']);
  header("location: index.php");
}

//$valor = isset($_SESSION['idcliente']) ? 'S' : 'N';
//echo $valor;
?>
<?php

  $cliente_id = $_SESSION['idcliente'];
  $stmt = $pdo->prepare("SELECT a.id cliente_id, a.nome, b.nivel, c.departamento FROM user a JOIN nivel b ON(a.nivel = b.id) JOIN departamento c ON(a.departamento = c.id) WHERE a.id = $cliente_id;");

  $stmt->execute();
  $rs = $stmt->fetch(PDO::FETCH_OBJ);

  $clienteid = $rs->cliente_id;
  $nome = $rs->nome;
  $nivel = $rs->nivel;
  $departamento = $rs->departamento;

?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Banco de Hora</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">	
        <link rel="stylesheet" href="css/home.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="shortcut icon" type="image/x-icon" href="img/img2.png">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

        <nav class="navbar navbar-expand-lg navbar-light bg-light" id="bar">
        <a class="navbar-brand" href="#">
            <img src="../Hora/img/img.png" width="110" height="30" class="d-inline-block align-top" alt="">
        </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav mr-auto">
                </ul>
                <span class="navbar-text">
                    <button id="botao" type="button" class="btn btn-light" data-toggle="modal" data-target="#exampleModalCenter">
                        <?php
                            echo $nome;
                        ?>
                    </button>
                    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Deseja sair?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="#" method="get" >
                                        </div>
                                            <button type="submit" href="?sair" class="btn btn-light" id="botao" name="sair" value="sair">Sim</button>
                                            <button type="button" class="btn btn-light" id="botao" data-dismiss="modal">Não</button>
                                        </div>
                                    <form>
                                </div>               
                            </div>
                        </div>
                    </div>
                </span>
            </div>
        </nav>

        <div class="col-sm-12"><br></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm"></div>
                    <div class="col-sm-8" id="back">
                    <div class="jumbotron" id="fundo">
                        <h1 class="display-4">Seja bem-vindo,
                            <?php
                                echo $nome;
                            ?>!
                        </h1>
                        <p class="lead">Nesta área você pode gerenciar acessos as inclusões de variáveis do Grupo Aldo</p>
                        <hr class="my-4">
                        <p>Cadastrar variáveis!</p>
                        <p class="lead">
                            <a href="variaveis.php"><button type="button"  class="btn btn-light" id="botao">Cadastrar</button></a>
                            <a href="visualizar.php"><button type="button" class="btn btn-light" id="botao">Vizualizar</button></a>
<!--                            
                            <a href="Alterar.php"><button type="button" class="btn btn-light" id="botao">Alterar</button></a>
-->
                        </p>
                        <hr class="my-4">
                        <p>Cadastrar novo supervisionado ou alterar cadastro!</p>
                        <p class="lead">
                            <button type="button" class="btn btn-light" id="botao">Cadastrar</button>
                            <button type="button" class="btn btn-light" id="botao">Alterar</button>
                        </p>                     
                    </div>                                           
                    </div>              
                <div class="col-sm"></div>
            </div>
        </div>
    </body>
</html>
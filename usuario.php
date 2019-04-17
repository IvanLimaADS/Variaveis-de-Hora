<?php
ob_start();
session_start();
if (isset($_SESSION['idcliente'])){
    header("location: cadastro.php");
}
include 'var/conect.php';
include 'var/login.php';
include 'var/user.php';

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
        <link rel="shortcut icon" type="image/x-icon" href="img/LOGOALDO2.png">
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
            <img src="../Hora/img/LOGOALDO.png" width="110" height="30" class="d-inline-block align-top" alt="">
        </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav mr-auto">
                </ul>
                <span class="navbar-text">
                    <button id="botao" type="button" class="btn btn-light" data-toggle="modal" data-target="#exampleModalCenter">
                        Login
                    </button>
                    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Entre com as credenciais</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <div class="modal-body">
                            <form action="#" method="POST" >
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-2 col-form-label">Usuário</label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputEmail3" placeholder="" name="user">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPassword3" class="col-sm-2 col-form-label">Senha</label>
                                <div class="col-sm-10">
                                <input type="password" class="form-control" id="inputPassword3" placeholder="" name="senha">
                                </div>
                            </div>
                            </div>
                                <div class="modal-footer">
                                    <a href="usuario.php"><button type="button" class="btn btn-light" id="botao">Cadastrar</button></a>
                                    <button type="submit" class="btn btn-light" id="botao" name="login" value="login">Login</button>
                                </div>
                            </div>
                            </form>
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
                            <h1 class="display-4">
                                Entre com suas informações!
                            </h1>
                            <p class="lead">Por favor preencha os dados conforme fornecido pelo <b>Departamento Pessoal</b></p>
                            <hr class="my-4">
                            <form action="#" method="GET">
                                <div class="form-row">
                                    <div class="col">
                                        <label for="inputname" class="lead">Nome</label>
                                        <input type="text" class="form-control" placeholder="Nome" name="nome" value="" require>
                                    </div>
                                    <div class="col">
                                    <label for="inputname" class="lead">Sobrenome</label>
                                        <input type="text" class="form-control" placeholder="Sobrenome" name="sobrenome" value="" require>
                                    </div>                          
                                </div>
                                </br>
                                <div class="form-row">
                                    <div class="col">                              
                                    <label for="inputState" class="lead">Departamento</label>
                                        <select id="inputState" class="form-control" name="departamento" value="" >
                                            <option selected>Selecione</option>
                                            <?php
                                                try
                                                {
                                                    $stmt = $pdo->prepare("SELECT DISTINCT id, departamento FROM departamento");
                                                    $stmt->execute();                        
                                                    while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) 
                                                        {
                                                            echo "<option value='".$rs->id."'>".$rs->departamento."</option>";
                                                        }
                                                } 
                                                catch (PDOException $erro) 
                                                {

                                                }
                                            ?>
                                        </select>
                                    </div>                                
                                    <div class="col">
                                        <label for="inputname" class="lead">Função</label>
                                        <input type="text" class="form-control" placeholder="Função" name="funcao" value="" require>
                                    </div>
                                    <div class="col">
                                    <label for="inputname" class="lead">Matricula</label>
                                        <input type="text" class="form-control" placeholder="Matricula" name="matricula" value="" require>
                                    </div>                          
                                </div>
                                </br>
                                <div class="col-auto my-1">
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" class="custom-control-input" id="customControlAutosizing" value="1" name="nivel">
                                        <label class="custom-control-label" for="customControlAutosizing"><p id="fonte" class="lead">Supervisor?</p></label>
                                    </div>
                                </div>                                                                
                                
                                <div class="form-row">
                                    <div class="col">
                                        <label for="inputname" class="lead">Usuário</label>
                                        <input type="text" class="form-control" placeholder="Usuário" name="user" value="" require>
                                    </div>
                                    <div class="col">
                                    <label for="inputname" class="lead">Senha</label>
                                        <input type="password" class="form-control" placeholder="Senha" name="senha" value="" min="8" max="15" require>
                                    </div>                          
                                </div>     
                                </br>                           
                                <p class="lead">
                                    <a href="index.php"><button type="button" class="btn btn-light" id="botao">Voltar</button></a>
                                    <button type="submit" class="btn btn-light" id="botao" name="salvar2" value="salvar2">Salvar</button>
                                </p>                             
                            </form>             
                        </div>                                           
                    </div>              
                <div class="col-sm"></div>
            </div>
        </div>

    </body>
</html>
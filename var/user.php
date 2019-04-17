<?php
if (isset($_GET['salvar2'])) 
{
$nome = $_GET["nome"];
$sobrenome = $_GET["sobrenome"];
$departamento = $_GET['departamento'];
$funcao = $_GET['funcao'];
$matricula = $_GET['matricula'];
if(empty($_GET['nivel']))
{
    $nivel = 0;
}
else
{
    $nivel = $_GET['nivel'];
}
$user = $_GET['user'];
$senha = $_GET['senha'];

    try 
    {
            $stmt = $pdo->prepare("SELECT count(1) cont, user
                                    FROM user 
                                        WHERE user = '".$user."'
                                        GROUP BY user;");    

            $stmt->execute();                                   
            if (isset($stmt)) 
            {
                $rs = $stmt->fetch(PDO::FETCH_OBJ);
//                echo $rs->cont;
                if(empty($rs->cont))
                {
                    try 
                    {
//                        echo 'OK';

                        $ins = $pdo->prepare("INSERT INTO user(nome, sobrenome, departamento, funcao, matricula, nivel, user, senha)
                        VALUES('".$nome."','".$sobrenome."','".$departamento."','".$funcao."','".$matricula."'
                        ,'". $nivel."','".$user."','".$senha."')");
            
                        $ins->execute();
                        echo 
                        "<div class='alert alert-success' role='alert'>
                            Cadastrado com sucesso!<a class='btn btn-outline-success' href=index.php>Clique aqui para voltar</a>
                        </div>";
            
                    } catch (Exception $e) {
                        echo    '<div class="alert alert-danger">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>Falha na comunicação</strong>
                                </div> ' ;
                
                    }                    
                }
                else
                {
//                    echo 'NÃO OK';
                    
                    echo    '<div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="warnnig">×</button>
                                <strong>Verifique o nome de usuário!</strong>
                            </div> ' ;                    
                }
            } 
            else 
            {
                echo "Erro: Não foi possível recuperar os dados do banco de dados";
            }
    } 
    catch (PDOException $erro) {
        echo "Erro: " . $erro->getMessage();
    }
}
?>
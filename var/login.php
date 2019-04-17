<?php
if (isset($_POST["login"])) 
    {
    $user = $_POST["user"];
    $senha = $_POST["senha"];
        try 
        {
            $stmt = $pdo->prepare("SELECT a.id cliente_id
                                        , a.user
                                        , a.senha
                                        , b.nivel
                                        , c.departamento
                                        , 1 status 
                                    FROM user a 
                                    JOIN nivel b ON(a.nivel = b.id) 
                                    JOIN departamento c ON(a.departamento = c.id) 
                                        WHERE a.user = '".$user."' 
                                        AND a.senha = '".$senha."'"
                                );
            $stmt->execute();
            if(isset($stmt))
            {
                $rs = $stmt->fetch(PDO::FETCH_OBJ);                
                if(empty($rs->cliente_id))
                {
                    $cliente_id = 0;
                }else{
                    $cliente_id = $rs->cliente_id;
                }

                if(empty($rs->status))
                {
                    $status = '0';
                }else{
                    $status = $rs->status;
                }
                if($status == 1)
                {   
                    $_SESSION['idcliente'] = $cliente_id;              
                    header('location: cadastro.php');
                }
            }
            echo
            '<div class="alert alert-warning" role="alert" id="aviso">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Usuário ou senha não é válido tente novamente!</strong>
            </div> ' ;      
        }     
        catch (PDOException $erro) 
        {
            echo
            '<div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Usúario ou senha não identificado, verifique os campos!</strong>
            </div> ' ;
        }
    }  
?>
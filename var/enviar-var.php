<?php

if (isset($_GET['salvar'])) {
    $user = $_GET["userid"];
    $chamado = $_GET["chamado"];
    $tipo = $_GET["tipo"];
    $desc = $_GET["texto"];    
    $data1 = date('Y-m-d H:i:00', strtotime(str_replace("/", "-", $_GET["data1"])));
    $data2 = date('Y-m-d H:i:00', strtotime(str_replace("/", "-", $_GET["data2"])));
    $semana1 = date('w',strtotime($data1));
    $semana2 = date('w',strtotime($data2));  
    $datanot1 = date('Y-m-d 22:00:00', strtotime(str_replace("/", "-", $_GET["data1"])));
    $datanot2 = date('Y-m-d 05:00:00', strtotime(str_replace("/", "-", $_GET["data2"])));
    

    try {
            $ins = $pdo->prepare("INSERT INTO variavel(userid, inicio, fim, tipo, descricao, chamado, semanaini, semanafim, noturnoini, noturnofim)
            VALUES('".$user."','".$data1."','".$data2."','".$tipo."','".$desc."'
            ,'". $chamado."','".$semana1."'
            ,'".$semana2."','".$datanot1."','".$datanot2."')");

            $ins->execute();
            echo 
            "<div class='alert alert-success' role='alert'>
                Cadastrado com sucesso!<a class='btn btn-outline-success' href=cadastro.php>Clique aqui para voltar</a>
            </div>";

    } catch (Exception $e) {
        echo    '<div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Falha na comunicação</strong>
                </div> ' ;

    }
}
?>
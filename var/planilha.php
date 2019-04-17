<?php
if (isset($_GET["planilha"])) 
{
    $data1 = date('Y-m-d 00:00:00', strtotime(str_replace("/", "-", $_GET["data1"])));
    $data2 = date('Y-m-d 23:59:59', strtotime(str_replace("/", "-", $_GET["data2"])));
    $userid = $_GET['userid'];

    $dadosXls  = "";
    $dadosXls .= "<table border='1' class='table table-sm'>";
    $dadosXls .= "<tr>";
        $dadosXls .= "<th>Nome</th>";
        $dadosXls .= "<th>Início</th>";
        $dadosXls .= "<th>Fim</th>";
        $dadosXls .= "<th>Tempo</th>";
        $dadosXls .= "<th>Chamado</th>";
        $dadosXls .= utf8_decode("<th>Descrição</th>");
    $dadosXls .= "</tr>";


    try 
    {    
        $stmt = $pdo->prepare(utf8_decode("SELECT concat(b.nome,' ',b.Sobrenome) nome
                                    , a.inicio inicio
                                    , a.fim fim
                                    , concat(round(((minuto) / 60),0),':',lpad(((minuto) % 60),2,'0')) tempo
                                    , a.chamado
                                    , a.descricao 
                                FROM variavel a 
                                JOIN user b ON (a.userid = b.id) 
                                WHERE a.entrada BETWEEN '".$data1."' AND '".$data2."' 
                                AND a.tipo <> 1 
                                AND userid = '".$userid."';"
                                ));            
 
        if ($stmt->execute()) 
        {
            while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) 
            {

                $inicio = $rs->inicio;
                $fim = $rs->fim;
                $date1 = new Datetime($inicio);
                $date2 = new Datetime($fim);   
                                
            $dadosXls .= "<tr>";
                $dadosXls .= "<td>".$rs->nome."</td>";
                $dadosXls .= "<td>".date_format($date1, "d/m/Y H:i")."</td>";
                $dadosXls .= "<td>".date_format($date2, "d/m/Y H:i")."</td>";
                $dadosXls .= "<td>".$rs->tempo."</td>";
                $dadosXls .= "<td>".$rs->chamado."</td>";
                $dadosXls .= "<td>".utf8_decode($rs->descricao)."</td>";
            $dadosXls .= "</tr>";
            }
        } 
    }
        catch (Exception $e) 
        {
            echo    '<div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>Falha na comunicação</strong>
                    </div> ' ;
        }

    $dadosXls .= "  </table>";  
    $arquivo = "Variavel.xls";  
    header('Content-type: application/x-msexcel');
    header('Content-Disposition: attachment;filename="'.$arquivo.'"');
    header('Cache-Control: no-cache, must-revalidate');
    header('Pragma: no-cache');

    echo $dadosXls;  
    exit;
}
?>
<?php
if (isset($_GET["dp"])) 
{
    $data1 = date('Y-m-d 00:00:00', strtotime(str_replace("/", "-", $_GET["data1"])));
    $data2 = date('Y-m-d 23:59:59', strtotime(str_replace("/", "-", $_GET["data2"])));

    $dadosXls1  = "";
    $dadosXls1 .= "<table border='1' class='table table-sm'>";
    $dadosXls1 .= "<tr>";
        $dadosXls1 .= "<th colspan='9'>PLANILHA DE VARIAVEIS GRUPO ALDO</th>";
    $dadosXls1 .= "</tr>";    
    $dadosXls1 .= "<tr>";
        $dadosXls1 .= "<th colspan='9' style='backgroud-color: red;'></th>";
    $dadosXls1 .= "</tr>";       
    $dadosXls1 .= "<tr>";
        $dadosXls1 .= "<th>EMPRESA</th>";
        $dadosXls1 .= "<th>EVENTO</th>";
        $dadosXls1 .= utf8_decode("<th>DESC. EVENTO</th>");
        $dadosXls1 .= "<th>TIPO (EVENT)</th>";
        $dadosXls1 .= utf8_decode("<th>FUNÇÃO</th>");
        $dadosXls1 .= "<th>NOME DO COLABORADOR</th>";
        $dadosXls1 .= "<th>MATRICULA (CHAPA)</th>";
        $dadosXls1 .= "<th>VALOR FINAL</th>";
        $dadosXls1 .= utf8_decode("<th width='300px'>OBSERVAÇÕES</th>");
    $dadosXls1 .= "</tr>";


    try 
    {    
        $stmt = $pdo->prepare(utf8_decode(
            "SELECT empresa
                    , '' evento
                    , descricao
                    , tipo
                    , funcao
                    , upper(nome) nome
                    , matricula
                    , ((CASE WHEN descricao <> 'AUXILIO EDUCAÇÃO' 
                            THEN(CASE WHEN sum(valor) % 60 >= 60 
                                THEN concat(ROUND((sum(valor) / 60),0),',',lpad((sum(valor) % 60),2,0))
                                ELSE concat(FLOOR((sum(valor) / 60)),',',lpad((sum(valor) % 60),2,0))
                                END) 
                            ELSE SUM(valor)
                        END)) vlr
                    , '' obs
                FROM vw_var
                WHERE entrada BETWEEN '".$data1."' AND '".$data2."'
                    GROUP BY empresa
                            , tipo
                            , funcao
                            , nome
                            , matricula
                            , descricao;"
                            ));                         
 
        if ($stmt->execute()) 
        {
            while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) 
            {               
            $dadosXls1 .= "<tr>";
                $dadosXls1 .= "<td>".$rs->empresa."</td>";
                $dadosXls1 .= "<td>".$rs->evento."</td>";
                $dadosXls1 .= "<td>".$rs->descricao."</td>";
                $dadosXls1 .= "<td>".$rs->tipo."</td>";
                $dadosXls1 .= "<td>".$rs->funcao."</td>";
                $dadosXls1 .= "<td>".$rs->nome."</td>";
                $dadosXls1 .= "<td>".$rs->matricula."</td>";
                $dadosXls1 .= "<td>".$rs->vlr."</td>";
                $dadosXls1 .= "<td>".$rs->obs."</td>";                
            $dadosXls1 .= "</tr>";
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

    $dadosXls1 .= "  </table>";
    $arquivo1 = "GRUPOALDO.xls";  
    header('Content-type: application/x-msexcel');
    header('Content-Disposition: attachment;filename="'.$arquivo1.'"');
    header('Cache-Control: no-cache, must-revalidate');
    header('Pragma: no-cache');

    echo $dadosXls1;  
    exit;
}
?>
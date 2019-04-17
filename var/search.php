<?php
if (isset($_GET['search'])) 
{
$data1 = date('Y-m-d 00:00:00', strtotime(str_replace("/", "-", $_GET["data1"])));
$data2 = date('Y-m-d 23:59:59', strtotime(str_replace("/", "-", $_GET["data2"])));
$userid = $_GET['userid'];

    try 
    {
            $stmt = $pdo->prepare("SELECT concat(b.nome,' ',b.Sobrenome) nome, a.inicio inicio, a.fim fim, concat(round(((minuto) / 60),0),':',lpad(((minuto) % 60),2,'0')) tempo, a.chamado, a.descricao FROM variavel a JOIN user b ON (a.userid = b.id) WHERE a.entrada BETWEEN '".$data1."' AND '".$data2."' AND a.tipo <> 1 AND userid = '".$userid."';");               
            if ($stmt->execute()) 
            {
                while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) 
                {
                    $inicio = $rs->inicio;
                    $fim = $rs->fim;
                    $date1 = new Datetime($inicio);
                    $date2 = new Datetime($fim);
                    echo "<tr>";
                        echo "<th scope='row'>".$rs->nome."</th>";
                        echo "<td>".date_format($date1, "d/m/Y H:i")."</td>";
                        echo "<td>".date_format($date2, "d/m/Y H:i")."</td>";
                        echo "<td>".$rs->chamado."</td>";
                        echo "<td>".$rs->tempo."</td>";
                        echo "<td>".$rs->descricao."</td>";
                    echo "</tr>";
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
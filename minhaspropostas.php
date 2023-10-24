<?php
require 'settings.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET': {
        if (isset($_GET['idusuario'])) {
            $idusuario = $_GET['idusuario'];

            // Consulta SQL para selecionar projetos com base no código da cidade, incluindo dados da tabela tbprojetoservico
            $sql = "SELECT p.*, pj.*, s.nomeservico
                    FROM tbproposta p
                    INNER JOIN tbprojeto pj on pj.idprojeto = p.codprojeto
                    inner join tbfreelancerservico fs on p.codfreelancer = fs.codfreelancer
                    inner join tbservico s on s.idservico = fs.codservico
                    WHERE p.codfreelancer = $idusuario";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $projetos = array();

                while ($row = $result->fetch_assoc()) {
                    $projetos[] = $row;
                }

                // Retorna os projetos em formato JSON
                echo json_encode($projetos);
            } else {
                // Não foram encontrados projetos correspondentes ao código da cidade
                echo json_encode(array("message" => "Nenhum projeto encontrado."));
            }
        } else {
            // Código de cidade ausente ou inválido
            echo json_encode(array("message" => "Código de usuário ausente ou inválido."));
        }
    }
    break;
    default:
        echo json_encode(array("message" => "Metodo nao registrado")); // Mensagem Padrao
    break;
}

$conn->close();
?>

<?php
require 'settings.php';


switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET': {
            if (isset($_GET['codcidade'])) {
                $codcidade = $_GET['codcidade'];

                // Consulta SQL para selecionar projetos com base no código da cidade
                $sql = "SELECT p.* , c.nomecidade
                FROM tbprojeto p
                inner join tbcidade c on c.idcidade = p.codcidade
                WHERE p.codcidade = $codcidade";

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
                    echo json_encode(array("message" => "Nenhum projeto encontrado para este código de cidade."));
                }
            } else {
                // Código de cidade ausente ou inválido
                echo json_encode(array("message" => "Código de cidade ausente ou inválido."));
            }
        }
        break;
    default:
        echo json_encode(array("message" => "Metodo nao registrado")); //Mensagem Padrao
        break;


}

$conn->close();
?>
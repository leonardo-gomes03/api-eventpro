<?php
require 'settings.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET': {
            if (isset($_GET['idusuario'])) {
                $idusuario = $_GET['idusuario'];

                // Verifica propostas finalizadas sem avaliações para o freelancer
                $sqlFreelancer = "
                    SELECT tp.*
                    FROM tbproposta tp
                    LEFT JOIN tbavaliacao ta ON tp.idproposta = ta.codproposta AND ta.codavaliador = $idusuario
                    WHERE tp.codfreelancer = $idusuario AND ta.codavaliador IS NULL AND tp.statusproposta = 'Finalizada'
                ";

                $resultFreelancer = $conn->query($sqlFreelancer);

                // Se houver propostas finalizadas sem avaliações para o freelancer
                if ($resultFreelancer->num_rows > 0) {
                    $propostasFreelancer = $resultFreelancer->fetch_all(MYSQLI_ASSOC);
                    echo json_encode(array("propostas_freelancer" => $propostasFreelancer));
                }

                // Verifica propostas finalizadas sem avaliações para o usuário (cliente)
                $sqlCliente = "
                    SELECT tp.*
                    FROM tbproposta tp
                    LEFT JOIN tbavaliacao ta ON tp.idproposta = ta.codproposta AND ta.codavaliador = $idusuario
                    INNER JOIN tbprojeto tpr ON tp.codprojeto = tpr.idprojeto
                    WHERE tpr.codcliente = $idusuario AND ta.codavaliador IS NULL AND tp.statusproposta = 'Finalizada'
                ";

                $resultCliente = $conn->query($sqlCliente);

                // Se houver propostas finalizadas sem avaliações para o cliente
                if ($resultCliente->num_rows > 0) {
                    $propostasCliente = $resultCliente->fetch_all(MYSQLI_ASSOC);
                    echo json_encode(array("propostas_cliente" => $propostasCliente));
                }

                // Se não houver propostas finalizadas sem avaliações para nenhum dos casos
                if ($resultFreelancer->num_rows == 0 && $resultCliente->num_rows == 0) {
                    echo json_encode(array("message" => "Nenhuma proposta finalizada pendente encontrada para o usuário."));
                }
            } else {
                echo json_encode(array("message" => "Por favor, forneça o ID do usuário."));
            }
        }
        break;
    default:
        echo json_encode(array("message" => "Método não registrado"));
        break;
}

$conn->close();
?>

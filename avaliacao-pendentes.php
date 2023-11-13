<?php
require 'settings.php';

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET': {
      if (isset($_GET['idusuario'])) {
        $idusuario = $_GET['idusuario'];

        $sqlProposta = "SELECT * FROM tbproposta WHERE codfreelancer = $idusuario";

        $resultProposta = $conn->query($sqlProposta);

        if ($resultProposta->num_rows > 0) {
          $proposta = $resultProposta->fetch_assoc();
          $codprojeto = $proposta['codprojeto'];

          $sqlAvaliacao = "SELECT * FROM tbavaliacao WHERE codprojeto = $codprojeto";

          $resultAvaliacao = $conn->query($sqlAvaliacao);

          if ($resultAvaliacao->num_rows > 0) {
           
            $avaliacao = $resultAvaliacao->fetch_assoc();
            echo json_encode($avaliacao);
          } else {
           
            echo json_encode($proposta);
          }
        } else {
         
          echo json_encode(array("message" => "Nenhuma proposta pendente encontrada para o usuário."));
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
<?php
require 'settings.php';


switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET': {
      // Consulta SQL para recuperar dados da tabela tbusuario e, se freelancerusuario for 1, fazer um JOIN com tbfreelancerservico
      $sql = "SELECT * from tbcidade";

      // Executa a consulta SQL
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        $response = array();

        while ($row = $result->fetch_assoc()) {
          // Para cada linha retornada, adicione-a à resposta
          $response[] = $row;
        }

        // Retorna os resultados como JSON
        echo json_encode($response);
      } else {
        // Não foram encontrados registros correspondentes
        echo json_encode(array("message" => "Nenhum registro encontrado."));
      }
    }
    break;
  default:
    echo json_encode(array("message" => "Metodo nao registrado")); //Mensagem Padrao
    break;
}

$conn->close();
?>
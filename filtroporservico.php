<?php
require 'settings.php';


switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET': {
      if (isset($_GET['codServico'])) {
        $codServico = $_GET['codServico'];


        $sql = "SELECT p.*
                FROM tbprojeto p
                INNER JOIN tbprojetoservico ps ON p.idprojeto = ps.codprojeto
                WHERE ps.codservico = $codServico";

        // Executa a consulta SQL
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          $projetos = array();

          while ($row = $result->fetch_assoc()) {
            $projetos[] = $row;
          }


          // Retorna os projetos em formato JSON
          echo json_encode($projetos);
        } else {
          // Não há projetos correspondentes
          echo json_encode(array("message" => "Nenhum projeto encontrado para o serviço com código: $codServico."));
        }
      }
      break;
    }
  default: {
      echo json_encode(array("message" => "Metodo nao registrado")); //Mensagem Padrao
      break;
    }

}

$conn->close();
?>
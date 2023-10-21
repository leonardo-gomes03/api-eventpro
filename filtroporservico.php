<?php
require 'settings.php';


switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET': {
      if (isset($_GET['codservico'])) {
        $codservico = $_GET['codservico'];

        // Consulta SQL para selecionar projetos com base no código de serviço
        $sql = "SELECT p.*, c.nomecidade
              FROM tbprojeto p
              INNER JOIN tbprojetoservico ps ON p.idprojeto = ps.codprojeto
              inner join tbcidade c on c.idcidade= p.codcidade
              WHERE ps.codservico = $codservico";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          $projetos = array();

          while ($row = $result->fetch_assoc()) {
            $projetos[] = $row;
          }

          // Retorna os projetos em formato JSON
          echo json_encode($projetos);
        } else {
          // Não foram encontrados projetos correspondentes ao código de serviço
          echo json_encode(array("message" => "Nenhum projeto encontrado para este código de serviço."));
        }
      } else {
        // Código de serviço ausente ou inválido
        echo json_encode(array("message" => "Código de serviço ausente ou inválido."));
      }
    }
    break;
  default:
    echo json_encode(array("message" => "Metodo nao registrado")); //Mensagem Padrao
    break;


}

$conn->close();
?>
<?php
require 'settings.php';

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET': {
      // Consulta SQL para recuperar dados da tabela tbusuario e, se freelancerusuario for 1, fazer um JOIN com tbfreelancerservico
      $sql = "SELECT u.*, fs.codservico AS codservico, s.nomeservico
              FROM tbusuario u
              LEFT JOIN tbfreelancerservico fs ON u.idusuario = fs.codfreelancer
              LEFT JOIN tbservico s ON fs.codservico = s.idservico
              WHERE u.freelancerusuario = 1";

      // Executa a consulta SQL
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        $response = array();

        while ($row = $result->fetch_assoc()) {
          // Verifica se já existe uma entrada com o mesmo idusuario no array $response
          $found = false;
          $idusuario = $row["idusuario"];
          foreach ($response as &$user) {
            if ($user["idusuario"] == $idusuario) {
              $user["nomeservico"][] = array(
                "codservico" => $row["codservico"],
                "nomeservico" => $row["nomeservico"]
              );
              $found = true;
              break;
            }
          }

          // Se não encontrou uma entrada com o mesmo idusuario, cria uma nova
          if (!$found) {
            $user = array(
              "idusuario" => $idusuario,
              "nomeusuario" => $row["nomeusuario"],
              "username" => $row["username"],
              "datanascusuario" => $row["datanascusuario"],
              "telefoneusuario" => $row["telefoneusuario"],
              "generousuario" => $row["generousuario"],
              "emailusuario" => $row["emailusuario"],
              "cpfusuario" => $row["cpfusuario"],
              "senhausuario" => $row["senhausuario"],
              "statususuario" => $row["statususuario"],
              "freelancerusuario" => $row["freelancerusuario"],
              "fotoperfilusuario" => $row["fotoperfilusuario"],
              "biousuario" => $row["biousuario"],
              "nomeservico" => array(
                array(
                  "codservico" => $row["codservico"],
                  "nomeservico" => $row["nomeservico"]
                )
              )
            );
            $response[] = $user;
          }
        }

        // Retorna os resultados como JSON
        echo json_encode($response);
      } else {
        // Não foram encontrados registros correspondentes
        echo json_encode(array("message" => "Nenhum registro encontrado."));
      }
      break;
    }
  case "POST": {
      $data = json_decode(file_get_contents("php://input"));
    }
  default: {
      echo json_encode(array("message" => "Metodo nao registrado")); //Mensagem Padrao
      break;
    }
}

$conn->close();
?>
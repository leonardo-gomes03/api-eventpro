<?php
require 'settings.php';


switch ($_SERVER['REQUEST_METHOD']) {
  case 'POST': {
      $data = json_decode(file_get_contents("php://input"));
      $senhaantiga = isset($data->senhaantiga) ? $data->senhaantiga : null;
      $senhanova = isset($data->senhanova) ? $data->senhanova : null;

      $idusuario = isset($_GET['idusuario']);

      if ($idusuario == '1' && isset($senhaantiga) == '1' && isset($senhanova) == '1') {

        //Valida Email
        $sql = "SELECT * FROM tbusuario u 
                WHERE u.senhausuario = '$senhaantiga'
                AND u.idusuario = $_GET[idusuario]";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          $sqlUpdate = "UPDATE tbusuario u set u.senhausuario = '$senhanova' WHERE u.idusuario = $_GET[idusuario]";

          $resultUpdate = $conn->query($sqlUpdate);


          $response = array("message" => "Senha alterada com sucesso!");
          echo json_encode($response);
          return;


        } else {
          $response = array("message" => "Senha antiga errada!");
          echo json_encode($response);
          return;
        }

      } else {
        $response = array("message" => "Usuario nao encontrado");
        return;
      }
    } // Aqui voce coloca todo o codigo do POST (Create)
    break;
  default:
    echo json_encode(array("message" => "Metodo nao registrado")); //Mensagem Padrao
    break;
}

$conn->close();
?>
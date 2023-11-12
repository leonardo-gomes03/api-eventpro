<?php
require 'settings.php';

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET': {
      echo json_encode(array("message" => "Exemplo Get")); // CODIGO GET (Read)
    }
    break;
  case 'POST': {
      echo json_encode(array("message" => "Exemplo Post"));
    } // Aqui voce coloca todo o codigo do POST (Create)
    break;
  case 'PUT': {
      echo json_encode(array("message" => "Exemplo Put")); // Aqui voce coloca todo o codigo do PUT (Update)
    }
    break;
  case 'DELETE': {
      echo json_encode(array("message" => "Exemplo Delete")); // Aqui voce coloca todo o codigo do DELETE (Delete)
    }
    break;
  default:
    echo json_encode(array("message" => "Metodo nao registrado")); //Mensagem Padrao
    break;
}

$conn->close();
?>
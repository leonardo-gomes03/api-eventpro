<?php
require 'settings.php';
require('vendor/autoload.php');

$resend = Resend::client('re_HpBqymyJ_DmDy9rZUhCpDfwCLCZwTnpFf');

function gerarStringAleatoria($tamanho)
{
  $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $stringAleatoria = '';

  for ($i = 0; $i < $tamanho; $i++) {
    $posicao = rand(0, strlen($caracteres) - 1);
    $stringAleatoria .= $caracteres[$posicao];
  }

  return $stringAleatoria;
}


switch ($_SERVER['REQUEST_METHOD']) {
  case 'POST': {
      try {
        $email = isset($_GET['email']);

        if ($email) {
          // Assuming you have a database connection named $conn
          $emailValue = $conn->real_escape_string($_GET['email']);

          $sql = "SELECT u.*
                    FROM tbusuario u 
                    WHERE u.emailusuario = '$emailValue'";

          $result = $conn->query($sql);

          if ($result) {
            if ($result->num_rows > 0) {
              $row = $result->fetch_assoc();

              $novasenha = gerarStringAleatoria(12);

              $sql = "UPDATE tbusuario SET senhausuario = '$novasenha' where emailusuario = '$emailValue'";

              $result = $conn->query($sql);


              $resend->emails->send([
                'from' => 'Event Pro <onboarding@resend.dev>',
                'to' => [$emailValue],
                'subject' => 'Email de recuperação de senha',
                'html' => "Sua nova senha é: <strong>$novasenha</strong>",
              ]);

              echo '{"message": "Email enviado, olhe sua caixa de entrada"}';
            } else {
              echo "User not found";
            }
          } else {
            echo "Error: " . $conn->error;
          }
        } else {
          echo "Email parameter not set";
        }


      } catch (\Exception $e) {
        exit('Error: ' . $e->getMessage());
      }

      // echo $result->toJson();
      // echo json_encode(array("message" => "Email enviado"));

    } // Aqui voce coloca todo o codigo do POST (Create)
    break;
  default:
    echo json_encode(array("message" => "Metodo nao registrado")); //Mensagem Padrao
    break;
}

$conn->close();
?>
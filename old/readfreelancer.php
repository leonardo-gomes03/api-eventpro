<?php
// Conexão com o banco de dados (substitua pelas suas credenciais)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bdeventpro";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

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
                "datanascusuario" => $row["datanascusuario"],
                "telefoneusuario" => $row["telefoneusuario"],
                "generousuario" => $row["generousuario"],
                "emailusuario" => $row["emailusuario"],
                "cpfusuario" => $row["cpfusuario"],
                "senhausuario" => $row["senhausuario"],
                "freelancerusuario" => $row["freelancerusuario"],
                "fotoperfilusuario" => $row["fotoperfilusuario"],
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

// Fecha a conexão com o banco de dados
$conn->close();
?>

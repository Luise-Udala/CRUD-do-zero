<?php
// Criar conexão
$servidor = "localhost";
$user = "root";
$password = "Pi@31415926535";
$bd = "telalog";  // Nome do banco de dados atualizado

$conn = new mysqli($servidor, $user, $password, $bd);
if ($conn->connect_error) {
    die("<p style='color:red; text-align:center; font-size:25px;'>Erro de conexão: " . $conn->connect_error . "</p>");
}

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera os valores do formulário
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    // Preparar a consulta SQL para evitar SQL Injection
    $stmt = $conn->prepare("SELECT senha FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    // Verificar se o usuário existe no banco de dados
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($senha_hash);
        $stmt->fetch();
        
        // Verificar se a senha está correta
        if (password_verify($senha, $senha_hash)) {
            echo "<p style='color:green; text-align:center; font-size:25px;'>Login bem-sucedido!</p>";
        } else {
            echo "<p style='color:red; text-align:center; font-size:25px;'>Senha incorreta.</p>";
        }
    } else {
        echo "<p style='color:red; text-align:center; font-size:25px;'>E-mail não encontrado.</p>";
    }

    $stmt->close();
}

$conn->close();
?>

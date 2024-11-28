<?php
// Dados de conexão com o banco de dados
$host = 'localhost';
$db = 'sistema_login';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Gerar o hash da senha com bcrypt
$username = 'admin'; // Nome do usuário
$password = '123456'; // A senha original 

// Criação do hash da senha com bcrypt
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Inserir o usuário no banco de dados
$sql = "INSERT INTO usuarios (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $hashedPassword);

if ($stmt->execute()) {
    echo "Usuário '$username' criado com sucesso!";
} else {
    echo "Erro ao criar usuário: " . $stmt->error;
}

// Fechar a conexão com o banco de dados
$stmt->close();
$conn->close();
?>

<?php
session_start();

$host = 'localhost';
$db = 'sistema_login';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $recaptchaResponse = $_POST['g-recaptcha-response']; // Resposta do reCAPTCHA

    // Verifique se o reCAPTCHA foi preenchido
    if (empty($recaptchaResponse)) {
        $erro = "Por favor, verifique o reCAPTCHA!";
    } else {
        // Verifique se o reCAPTCHA é válido
        $secret = "6LdProwqAAAAAEWPzzXvdYVCPlrAUw2d4GylQCZ5"; // Sua chave secreta
        $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$recaptchaResponse";
        $response = file_get_contents($url);
        $responseKeys = json_decode($response, true);

        if (intval($responseKeys["success"]) !== 1) {
            $erro = "Falha na verificação do reCAPTCHA. Tente novamente.";
        } else {
            // Se o reCAPTCHA for validado, continue com a verificação de login
            $sql = "SELECT * FROM usuarios WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['username'] = $username;
                    header("Location: sistema.php");
                    exit();
                } else {
                    $erro = "Usuário ou senha inválidos!";
                }
            } else {
                $erro = "Usuário não encontrado!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login com reCAPTCHA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #333333;
        }
        .login-container form {
            display: flex;
            flex-direction: column;
        }
        .login-container input {
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #cccccc;
            border-radius: 5px;
            font-size: 1rem;
        }
        .login-container button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 0.8rem;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .login-container button:hover {
            background-color: #45a049;
        }
        .login-container p {
            color: red;
            text-align: center;
            margin-top: -0.5rem;
            margin-bottom: 1rem;
        }
    </style>
    <!-- Script do reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <div class="login-container">
        <h2>Login com reCAPTCHA</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Usuário" required>
            <input type="password" name="password" placeholder="Senha" required>
            <!-- Widget do reCAPTCHA -->
            <div class="g-recaptcha" data-sitekey="6LdProwqAAAAAGbnmbRXkTYiI0c_HRcs8aJamgS6"></div>
            <button type="submit">Entrar</button>
        </form>
        <?php if (isset($erro)) echo "<p>$erro</p>"; ?>
    </div>
</body>
</html>

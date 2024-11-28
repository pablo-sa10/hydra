<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        h2 {
            margin-bottom: 1.5rem;
            color: #333333;
        }

        .welcome-message {
            font-size: 1.2rem;
            color: #555555;
            margin-bottom: 1.5rem;
        }

        .logout-button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .logout-button:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sistema</h2>
        <p class="welcome-message">
            Bem-vindo, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!
        </p>
        <a href="logout.php" class="logout-button">Sair</a>
    </div>
</body>
</html>

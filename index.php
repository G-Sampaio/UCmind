<?php
include 'includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $senha_hash = md5($senha); // MD5 da senha fornecida
    $query = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha_hash'";
    $result = $conn->query($query);


    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['id_usuario'] = $user['id_usuario'];
        $_SESSION['nivel_acesso'] = $user['nivel_acesso'];
        header('Location: dashboard.php');
    } else {
        $erro = "Credenciais inválidas!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Login - UCMind</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Importando fontes e estilos -->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<!-- Link para o Font Awesome para ícones (opcional) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-XXXXXXXXXXXX" crossorigin="anonymous" />
<style>
        /* CSS incluído abaixo */
        /* Reset básico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
 
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #0d47a1, #1976d2);
            color: #e0e0e0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
 
        .login-container {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 10px;
            width: 90%;
            max-width: 400px;
            backdrop-filter: blur(10px);
        }
 
        form h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5rem;
            color: #fff;
        }
 
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }
 
        .input-group input {
            width: 100%;
            padding: 15px 20px;
            border: none;
            border-radius: 30px;
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
            font-size: 1rem;
            outline: none;
        }
 
        .input-group input::placeholder {
            color: #e0e0e0;
        }
 
        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
 
        .remember-me label {
            font-size: 0.9rem;
            color: #e0e0e0;
            cursor: pointer;
        }
 
        .remember-me input {
            margin-right: 10px;
        }
 
        button {
            width: 100%;
            padding: 15px;
            background-color: #1976d2;
            color: #fff;
            border: none;
            border-radius: 30px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.3s;
        }
 
        button:hover {
            background-color: #0d47a1;
        }
 
        .error {
            margin-top: 15px;
            color: #f44336;
            font-size: 0.9rem;
            text-align: center;
        }
 
        /* Responsividade */
        @media (max-width: 480px) {
            form h1 {
                font-size: 2rem;
            }
        }
</style>
</head>
<body>
<div class="login-container">
<form action="index.php" method="POST">
<h1>UCMind</h1>
<div class="input-group">
<input type="email" name="email" placeholder="Email" required>
</div>
<div class="input-group">
<input type="password" name="senha" placeholder="Senha" required>
</div>
<div class="remember-me">
<label>
<input type="checkbox" name="remember"> Lembrar-me
</label>
</div>
<button type="submit">Entrar</button>
<?php if (isset($erro)) echo "<p class='error'>$erro</p>"; ?>
</form>
</div>
</body>
</html>


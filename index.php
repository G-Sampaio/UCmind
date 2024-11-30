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
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <form action="index.php" method="POST">
        <h1>Login</h1>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Entrar</button>
        <?php if (isset($erro)) echo "<p>$erro</p>"; ?>
    </form>
</body>
</html>

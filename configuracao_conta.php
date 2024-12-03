<?php
include 'includes/db.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: index.php');
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Buscar nome do usuário
$query_usuario = "SELECT nome FROM usuarios WHERE id_usuario = '$id_usuario'";
$result_usuario = $conn->query($query_usuario);
$nome_usuario = $result_usuario->num_rows > 0 ? $result_usuario->fetch_assoc()['nome'] : 'Usuário';

// Processamento do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha_atual = md5($_POST['senha_atual']);
    $nova_senha = md5($_POST['nova_senha']);
    $confirmar_senha = md5($_POST['confirmar_senha']);

    // Verificar senha atual
    $query_verificar = "SELECT senha FROM usuarios WHERE id_usuario = '$id_usuario'";
    $result_verificar = $conn->query($query_verificar);
    $senha_db = $result_verificar->fetch_assoc()['senha'];

    if ($senha_atual !== $senha_db) {
        $erro = "A senha atual está incorreta.";
    } elseif ($nova_senha !== $confirmar_senha) {
        $erro = "A nova senha e a confirmação não correspondem.";
    } else {
        // Atualizar senha
        $query_atualizar = "UPDATE usuarios SET senha = '$nova_senha' WHERE id_usuario = '$id_usuario'";
        if ($conn->query($query_atualizar)) {
            $sucesso = "Senha alterada com sucesso!";
        } else {
            $erro = "Erro ao atualizar a senha: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuração da Conta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Configuração da Conta</h1>
        <h3 class="text-center">Usuário: <?php echo htmlspecialchars($nome_usuario, ENT_QUOTES, 'UTF-8'); ?></h3>
        <?php if (isset($erro)) { ?>
            <div class="alert alert-danger"><?php echo $erro; ?></div>
        <?php } ?>
        <?php if (isset($sucesso)) { ?>
            <div class="alert alert-success"><?php echo $sucesso; ?></div>
        <?php } ?>
        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="senha_atual" class="form-label">Senha Atual</label>
                <input type="password" class="form-control" id="senha_atual" name="senha_atual" required>
            </div>
            <div class="mb-3">
                <label for="nova_senha" class="form-label">Nova Senha</label>
                <input type="password" class="form-control" id="nova_senha" name="nova_senha" required>
            </div>
            <div class="mb-3">
                <label for="confirmar_senha" class="form-label">Confirmar Nova Senha</label>
                <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" required>
            </div>
            <button type="submit" class="btn btn-primary">Alterar Senha</button>
            <a href="dashboard.php" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

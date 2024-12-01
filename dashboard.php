<?php
include 'includes/db.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: index.php');
    exit;
}

$nivel_acesso = htmlspecialchars($_SESSION['nivel_acesso'], ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - UCMind</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        nav {
            background-color: #f4f4f4;
            padding: 10px;
            display: flex;
            gap: 15px;
        }
        nav a {
            text-decoration: none;
            color: #333;
            padding: 8px 12px;
            border-radius: 5px;
        }
        nav a:hover {
            background-color: #ddd;
        }
        header h1 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Bem-vindo ao UCMind</h1>
        <nav>
            <!-- Links para Admin -->
            <?php if ($nivel_acesso == 'admin') { ?>
                <a href="adicionar_turma.php">Gerenciar Turmas</a>
                <a href="cadastrar_usuario.php">Gerenciar Usuários</a>
                <a href="visualizar_alunos.php">Visualizar Alunos</a>
            <?php } ?>

            <!-- Links para Admin e Professor -->
            <?php if ($nivel_acesso == 'admin' || $nivel_acesso == 'professor') { ?>
                <a href="cadastrar_paciente.php">Cadastrar Pacientes</a>
                <a href="visualizar_pacientes.php">Visualizar Pacientes</a>
                <a href="minhas_turmas.php">Minhas Turmas</a>
                <a href="visualizar_alunos.php">Visualizar Alunos</a>
            <?php } ?>

            <!-- Links para Aluno -->
            <?php if ($nivel_acesso == 'aluno') { ?>
                <a href="cadastrar_paciente.php">Cadastrar Pacientes</a>
                <a href="visualizar_pacientes.php">Meus Pacientes</a>
            <?php } ?>

            <!-- Links para Todos -->
            <a href="cadastrar_consulta.php">Cadastrar Consulta</a>
            <a href="visualizar_consultas.php">Visualizar Consultas</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>
</body>
</html>
<?php include 'includes/footer.php'; ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'UCMind'; ?></title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>UCMind</h1>
            <nav>
                <a href="dashboard.php">Início</a>
                <?php if ($_SESSION['nivel_acesso'] == 'admin') { ?>
                    <a href="adicionar_turma.php">Gerenciar Turmas</a>
                    <a href="cadastrar_usuario.php">Gerenciar Usuários</a>
                <?php } ?>
                <?php if ($_SESSION['nivel_acesso'] == 'admin' || $_SESSION['nivel_acesso'] == 'professor') { ?>
                    <a href="cadastrar_paciente.php">Cadastrar Pacientes</a>
                    <a href="visualizar_pacientes.php">Visualizar Pacientes</a>
                <?php } ?>
                <a href="consultas.php">Gerenciar Consultas</a>
                <a href="logout.php">Sair</a>
            </nav>
        </div>
    </header>
    <main>

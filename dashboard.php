<?php
include 'includes/db.php';
session_start();
 
if (!isset($_SESSION['id_usuario'])) {
    header('Location: index.php');
    exit;
}
<<<<<<< Updated upstream
//$pageTitle = 'Detalhes do Paciente'; // Define o título da página
//include 'includes/header.php';
=======
 
>>>>>>> Stashed changes
$nivel_acesso = htmlspecialchars($_SESSION['nivel_acesso'], ENT_QUOTES, 'UTF-8');
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - UCMind</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Importando fonte do Google Fonts e ícones -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.cjs" rel="stylesheet">
    <style>
        /* Reset básico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #121212;
            color: #e0e0e0;
            line-height: 1.6;
        }

        header {
            background: linear-gradient(135deg, #0d47a1, #1976d2);
            padding: 20px;
            text-align: center;
        }

        header h1 {
            font-size: 2.5rem;
            font-weight: 700;
        }

        nav {
            background-color: #1f1f1f;
            overflow: auto;
            padding: 10px 0;
        }

        nav a {
            display: block;
            padding: 15px 20px;
            float: left;
            text-decoration: none;
            color: #e0e0e0;
            font-weight: 500;
            transition: background 0.3s;
        }

        nav a:hover {
            background-color: #333;
        }

        .content {
            padding: 40px 20px;
            text-align: center;
        }

        .content p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        .btn {
            display: inline-block;
            margin: 10px;
            padding: 15px 30px;
            font-size: 1rem;
            color: #fff;
            background-color: #1976d2;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .btn:hover {
            background-color: #0d47a1;
        }

        footer {
            background-color: #0d47a1;
            color: #e0e0e0;
            text-align: center;
            padding: 15px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        /* Calendário */
        #calendar {
            max-width: 1100px;
            margin: 40px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Responsividade */
        @media (max-width: 768px) {
            nav a {
                float: none;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Bem-vindo ao UCMind</h1>
<<<<<<< Updated upstream
        <nav>
            <!-- Links para Admin -->
            <?php if ($nivel_acesso == 'admin') { ?>
                <a href="adicionar_turma.php">Gerenciar Turmas</a>
                <a href="cadastrar_usuario.php">Gerenciar Usuários</a>
            <?php } ?>

            <!-- Links para Admin e Professor -->
            <?php if ($nivel_acesso == 'admin' || $nivel_acesso == 'professor') { ?>
                <a href="cadastrar_paciente.php">Cadastrar Pacientes</a>
                <a href="visualizar_pacientes.php">Visualizar Pacientes</a>
                <a href="minhas_turmas.php">Minhas Turmas</a>
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
=======
>>>>>>> Stashed changes
    </header>

    <nav>
        <!-- Links para Admin -->
        <?php if ($nivel_acesso == 'admin') { ?>
            <a href="adicionar_turma.php"><i class="fas fa-users"></i> Gerenciar Turmas</a>
            <a href="cadastrar_usuario.php"><i class="fas fa-user-plus"></i> Gerenciar Usuários</a>
            <a href="visualizar_alunos.php"><i class="fas fa-user-graduate"></i> Visualizar Alunos</a>
        <?php } ?>

        <!-- Links para Admin e Professor -->
        <?php if ($nivel_acesso == 'admin' || $nivel_acesso == 'professor') { ?>
            <a href="cadastrar_paciente.php"><i class="fas fa-user-injured"></i> Cadastrar Pacientes</a>
            <a href="visualizar_pacientes.php"><i class="fas fa-notes-medical"></i> Visualizar Pacientes</a>
            <a href="minhas_turmas.php"><i class="fas fa-chalkboard-teacher"></i> Minhas Turmas</a>
        <?php } ?>

        <!-- Links para Aluno -->
        <?php if ($nivel_acesso == 'aluno') { ?>
            <a href="cadastrar_paciente.php"><i class="fas fa-user-injured"></i> Cadastrar Pacientes</a>
            <a href="visualizar_pacientes.php"><i class="fas fa-notes-medical"></i> Meus Pacientes</a>
        <?php } ?>

        <!-- Links para Todos -->
        <a href="cadastrar_consulta.php"><i class="fas fa-calendar-plus"></i> Cadastrar Consulta</a>
        <a href="visualizar_consultas.php"><i class="fas fa-calendar-alt"></i> Visualizar Consultas</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
    </nav>

    <!-- <div class="content">
        <p>Escolha uma opção no menu acima para começar.</p>
        <a href="visualizar_pacientes.php" class="btn"><i class="fas fa-notes-medical"></i> Ver Pacientes</a>
        <a href="visualizar_consultas.php" class="btn"><i class="fas fa-calendar-alt"></i> Ver Consultas</a>
    </div> -->

    <div id="calendar"></div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> UCMind - Todos os direitos reservados.</p>
    </footer>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    { title: 'Consulta 1', start: '2024-12-05' },
                    { title: 'Consulta 2', start: '2024-12-07' }
                ]
            });
            calendar.render();
        });
    </script>
</body>
</html>
<<<<<<< Updated upstream
<?php include 'includes/footer.php'; ?>
=======

>>>>>>> Stashed changes

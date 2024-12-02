<?php
include 'includes/db.php';
session_start();

if (!isset($_SESSION['id_usuario']) || ($_SESSION['nivel_acesso'] != 'admin' && $_SESSION['nivel_acesso'] != 'professor')) {
    header('Location: index.php');
    exit;
}

$nivel_acesso = $_SESSION['nivel_acesso'];
$id_usuario = $_SESSION['id_usuario'];
$id_aluno = $_GET['id_aluno'] ?? null;

// Verificar acesso
if (!$id_aluno) {
    echo "Aluno não especificado.";
    exit;
}

if ($nivel_acesso === 'professor') {
    $query_verificacao = "
        SELECT COUNT(*) AS total
        FROM usuarios u
        LEFT JOIN turmas t ON u.id_turma = t.id_turma
        WHERE t.id_professor = '$id_usuario' AND u.id_usuario = '$id_aluno'
    ";
    $verificacao = $conn->query($query_verificacao)->fetch_assoc();
    if ($verificacao['total'] == 0) {
        echo "Acesso negado.";
        exit;
    }
}

// Obter pacientes atribuídos ao aluno
$query_pacientes = "
    SELECT id_paciente, nome, data_nascimento, genero
    FROM pacientes
    WHERE id_aluno = '$id_aluno'
";

$pacientes = $conn->query($query_pacientes);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pacientes do Aluno</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
        }
        .container {
            margin-top: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h1 {
            color: #0056b3;
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            margin-top: 20px;
        }
        table th {
            background-color: #0056b3;
            color: white;
        }
        table td, table th {
            border: 1px solid #dee2e6;
        }
        .btn {
            display: block;
            width: fit-content;
            margin: 20px auto 0;
            background-color: #0056b3;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #003f88;
            text-decoration: none;
        }
        .table-hover tbody tr:hover {
            background-color: #e9f5ff;
        }
        .btn-dashboard {
            display: block;
            margin: 20px auto 0;
            padding: 10px 20px;
            background-color: #0056b3;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
        .btn-dashboard:hover {
            background-color: #003f88;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pacientes do Aluno</h1>
        <?php if ($pacientes->num_rows > 0) { ?>
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="text-center">
                        <tr>
                            <!-- <th>ID</th> -->
                            <th>Nome</th>
                            <th>Data de Nascimento</th>
                            <th>Gênero</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($paciente = $pacientes->fetch_assoc()) { ?>
                        <tr>
                            <!-- <td class="text-center"><?php echo $paciente['id_paciente']; ?></td> -->
                            <td><?php echo $paciente['nome']; ?></td>
                            <td><?php echo $paciente['data_nascimento']; ?></td>
                            <td><?php echo $paciente['genero']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } else { ?>
            <p class="text-center">Nenhum paciente encontrado para este aluno.</p>
        <?php } ?>
        <a href="visualizar_alunos.php" class="btn-dashboard">Voltar</a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


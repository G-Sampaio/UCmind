<?php
include 'includes/db.php';
session_start();

if (!isset($_SESSION['id_usuario']) || ($_SESSION['nivel_acesso'] != 'admin' && $_SESSION['nivel_acesso'] != 'professor')) {
    header('Location: index.php');
    exit;
}

$nivel_acesso = $_SESSION['nivel_acesso'];
$id_usuario = $_SESSION['id_usuario'];

// Obter alunos
if ($nivel_acesso === 'admin') {
    $query = "
        SELECT u.id_usuario, u.nome AS aluno_nome, t.nome AS turma_nome, p.nome AS professor_nome
        FROM usuarios u
        LEFT JOIN turmas t ON u.id_turma = t.id_turma
        LEFT JOIN usuarios p ON t.id_professor = p.id_usuario
        WHERE u.nivel_acesso = 'aluno'
    ";
} elseif ($nivel_acesso === 'professor') {
    $query = "
        SELECT u.id_usuario, u.nome AS aluno_nome, t.nome AS turma_nome
        FROM usuarios u
        LEFT JOIN turmas t ON u.id_turma = t.id_turma
        WHERE t.id_professor = '$id_usuario' AND u.nivel_acesso = 'aluno'
    ";
}

$alunos = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Alunos</title>
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
            margin-top: 20px;
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
        <h1>Alunos Cadastrados</h1>

        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="text-center">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Turma</th>
                        <?php if ($nivel_acesso === 'admin') { ?>
                            <th>Professor</th>
                        <?php } ?>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($aluno = $alunos->fetch_assoc()) { ?>
                    <tr>
                        <td class="text-center"><?php echo $aluno['id_usuario']; ?></td>
                        <td><?php echo $aluno['aluno_nome']; ?></td>
                        <td><?php echo $aluno['turma_nome'] ?: 'N/A'; ?></td>
                        <?php if ($nivel_acesso === 'admin') { ?>
                            <td><?php echo $aluno['professor_nome'] ?: 'N/A'; ?></td>
                        <?php } ?>
                        <td class="text-center">
                            <a href="ver_pacientes.php?id_aluno=<?php echo $aluno['id_usuario']; ?>" class="btn btn-primary btn-sm">Ver Pacientes</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <a href="dashboard.php" class="btn-dashboard">Voltar para o Dashboard</a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


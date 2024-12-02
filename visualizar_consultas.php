<?php
include 'includes/db.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: index.php');
    exit;
}

$nivel_acesso = $_SESSION['nivel_acesso'];
$turma_selecionada = isset($_POST['id_turma']) ? $_POST['id_turma'] : null;
$aluno_selecionado = isset($_POST['id_aluno']) ? $_POST['id_aluno'] : null;

if ($nivel_acesso == 'aluno') {
    // Aluno: listar consultas automaticamente
    $id_aluno = $_SESSION['id_usuario'];
    $query = "
        SELECT c.id_consulta, c.data_consulta, c.observacoes, p.nome AS paciente, p.id_paciente
        FROM consultas c
        JOIN pacientes p ON c.id_paciente = p.id_paciente
        WHERE p.id_aluno = '$id_aluno'
    ";
    $consultas = $conn->query($query);
} elseif ($nivel_acesso == 'professor' || $nivel_acesso == 'admin') {
    // Listar turmas
    if ($nivel_acesso == 'professor') {
        $id_professor = $_SESSION['id_usuario'];
        $query_turmas = "
            SELECT id_turma, nome 
            FROM turmas
            WHERE id_professor = '$id_professor'
        ";
    } else {
        $query_turmas = "
            SELECT id_turma, nome 
            FROM turmas
        ";
    }
    $turmas = $conn->query($query_turmas);

    // Listar alunos de uma turma selecionada
    if ($turma_selecionada) {
        $query_alunos = "
            SELECT u.id_usuario, u.nome 
            FROM usuarios u
            WHERE u.id_turma = '$turma_selecionada' AND u.nivel_acesso = 'aluno'
        ";
        $alunos = $conn->query($query_alunos);
    }

    // Listar consultas do aluno selecionado
    if ($aluno_selecionado) {
        $query_consultas = "
            SELECT c.id_consulta, c.data_consulta, c.observacoes, p.nome AS paciente, p.id_paciente
            FROM consultas c
            JOIN pacientes p ON c.id_paciente = p.id_paciente
            WHERE p.id_aluno = '$aluno_selecionado'
        ";
        $consultas = $conn->query($query_consultas);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Consultas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .main-header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
        }
        .main-header h1 {
            font-size: 1.8rem;
            margin: 0;
        }
        .form-inline {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            align-items: center;
        }
        .table thead {
            background-color: #007bff;
            color: white;
        }
        .table-hover tbody tr:hover {
            background-color: #eef6ff;
        }
        .btn-action {
            font-size: 0.9rem;
            border-radius: 5px;
            padding: 5px 10px;
            color: white;
        }
        .btn-dashboard {
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            font-size: 1rem;
            text-align: center;
            display: block;
            padding: 10px 20px;
            border-radius: 8px;
        }
        .btn-dashboard:hover {
            background-color: #0056b3;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <header class="main-header">
            <h1>Visualizar Consultas</h1>
        </header>
        <main>
            <!-- Aluno: Consultas Automáticas -->
            <?php if ($nivel_acesso == 'aluno') { ?>
                <?php if ($consultas && $consultas->num_rows > 0) { ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle text-center">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Paciente</th>
                                    <th>Data da Consulta</th>
                                    <th>Observações</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($consulta = $consultas->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $consulta['id_consulta']; ?></td>
                                    <td><?php echo $consulta['paciente']; ?></td>
                                    <td><?php echo $consulta['data_consulta']; ?></td>
                                    <td><?php echo $consulta['observacoes']; ?></td>
                                    <td>
                                        <a href="detalhes_paciente.php?id_paciente=<?php echo $consulta['id_paciente']; ?>" class="btn btn-info btn-sm btn-action">
                                            <i class="fas fa-file-alt"></i> Detalhes
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                    <p class="text-center">Nenhuma consulta encontrada.</p>
                <?php } ?>
            <?php } ?>

            <!-- Professor/Admin: Seleção de Turma e Aluno -->
            <?php if ($nivel_acesso == 'professor' || $nivel_acesso == 'admin') { ?>
                <form method="POST" class="form-inline">
                    <label for="id_turma" class="form-label">Selecione a Turma:</label>
                    <select name="id_turma" id="id_turma" class="form-select" onchange="this.form.submit()" required>
                        <option value="">-- Escolha uma Turma --</option>
                        <?php while ($turma = $turmas->fetch_assoc()) { ?>
                            <option value="<?php echo $turma['id_turma']; ?>" <?php echo $turma_selecionada == $turma['id_turma'] ? 'selected' : ''; ?>>
                                <?php echo $turma['nome']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </form>

                <?php if ($turma_selecionada) { ?>
                    <form method="POST" class="form-inline">
                        <input type="hidden" name="id_turma" value="<?php echo $turma_selecionada; ?>">
                        <label for="id_aluno" class="form-label">Selecione o Aluno:</label>
                        <select name="id_aluno" id="id_aluno" class="form-select" onchange="this.form.submit()" required>
                            <option value="">-- Escolha um Aluno --</option>
                            <?php while ($aluno = $alunos->fetch_assoc()) { ?>
                                <option value="<?php echo $aluno['id_usuario']; ?>" <?php echo $aluno_selecionado == $aluno['id_usuario'] ? 'selected' : ''; ?>>
                                    <?php echo $aluno['nome']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </form>
                <?php } ?>

                <?php if ($aluno_selecionado && isset($consultas)) { ?>
                    <?php if ($consultas->num_rows > 0) { ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle text-center">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Paciente</th>
                                        <th>Data da Consulta</th>
                                        <th>Observações</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($consulta = $consultas->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $consulta['id_consulta']; ?></td>
                                        <td><?php echo $consulta['paciente']; ?></td>
                                        <td><?php echo $consulta['data_consulta']; ?></td>
                                        <td><?php echo $consulta['observacoes']; ?></td>
                                        <td>
                                            <a href="detalhes_paciente.php?id_paciente=<?php echo $consulta['id_paciente']; ?>" class="btn btn-info btn-sm btn-action">
                                                <i class="fas fa-file-alt"></i> Detalhes
                                            </a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } else { ?>
                        <p class="text-center">Nenhuma consulta encontrada para este aluno.</p>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        </main>
        <a href="dashboard.php" class="btn-dashboard"><i class="fas fa-arrow-left"></i> Voltar para o Dashboard</a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



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
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Visualizar Consultas</h1>
        </header>
        <main>
            <!-- Aluno: Consultas Automáticas -->
            <?php if ($nivel_acesso == 'aluno') { ?>
                <?php if ($consultas && $consultas->num_rows > 0) { ?>
                    <table class="table">
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
                                    <a href="detalhes_paciente.php?id_paciente=<?php echo $consulta['id_paciente']; ?>" class="btn-link">Detalhes</a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <p>Nenhuma consulta encontrada.</p>
                <?php } ?>
            <?php } ?>

            <!-- Professor/Admin: Seleção de Turma e Aluno -->
            <?php if ($nivel_acesso == 'professor' || $nivel_acesso == 'admin') { ?>
                <form method="POST" class="form-inline">
                    <label for="id_turma">Selecione a Turma:</label>
                    <select name="id_turma" id="id_turma" onchange="this.form.submit()" required>
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
                        <label for="id_aluno">Selecione o Aluno:</label>
                        <select name="id_aluno" id="id_aluno" onchange="this.form.submit()" required>
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
                        <table class="table">
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
                                        <a href="detalhes_paciente.php?id_paciente=<?php echo $consulta['id_paciente']; ?>" class="btn-link">Detalhes</a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <p>Nenhuma consulta encontrada para este aluno.</p>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        </main>
    </div>
</body>
</html>


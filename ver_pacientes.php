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
    <title>Pacientes do Aluno</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Pacientes do Aluno</h1>
        <?php if ($pacientes->num_rows > 0) { ?>
            <table class="table-pacientes">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Data de Nascimento</th>
                        <th>Gênero</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($paciente = $pacientes->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $paciente['id_paciente']; ?></td>
                        <td><?php echo $paciente['nome']; ?></td>
                        <td><?php echo $paciente['data_nascimento']; ?></td>
                        <td><?php echo $paciente['genero']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>Nenhum paciente encontrado para este aluno.</p>
        <?php } ?>
        <a href="visualizar_alunos.php" class="btn">Voltar</a>
    </div>
</body>
</html>

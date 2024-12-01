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
    <title>Visualizar Alunos</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Alunos Cadastrados</h1>

        <table class="table-alunos">
            <thead>
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
                    <td><?php echo $aluno['id_usuario']; ?></td>
                    <td><?php echo $aluno['aluno_nome']; ?></td>
                    <td><?php echo $aluno['turma_nome'] ?: 'N/A'; ?></td>
                    <?php if ($nivel_acesso === 'admin') { ?>
                        <td><?php echo $aluno['professor_nome'] ?: 'N/A'; ?></td>
                    <?php } ?>
                    <td>
                        <a href="ver_pacientes.php?id_aluno=<?php echo $aluno['id_usuario']; ?>" class="btn">Ver Pacientes</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>

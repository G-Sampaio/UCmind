<?php
include 'includes/db.php';
session_start();

if ($_SESSION['nivel_acesso'] != 'admin' && $_SESSION['nivel_acesso'] != 'professor' && $_SESSION['nivel_acesso'] != 'aluno') {
    header('Location: dashboard.php');
    exit;
}

// Listar pacientes com base no nível de acesso
if ($_SESSION['nivel_acesso'] == 'aluno') {
    $id_aluno = $_SESSION['id_usuario'];
    $query_pacientes = "SELECT * FROM pacientes WHERE id_aluno = '$id_aluno'";
} elseif ($_SESSION['nivel_acesso'] == 'professor') {
    $id_professor = $_SESSION['id_usuario'];
    $query_pacientes = "
        SELECT p.*, u.nome AS aluno_responsavel, t.nome AS turma_nome
        FROM pacientes p
        JOIN usuarios u ON p.id_aluno = u.id_usuario
        JOIN turmas t ON u.id_turma = t.id_turma
        WHERE t.id_professor = '$id_professor'
    ";
} else { // Admin
    $query_pacientes = "
        SELECT p.*, u.nome AS aluno_responsavel, pr.nome AS professor_responsavel, t.nome AS turma_nome
        FROM pacientes p
        JOIN usuarios u ON p.id_aluno = u.id_usuario
        LEFT JOIN turmas t ON u.id_turma = t.id_turma
        LEFT JOIN usuarios pr ON t.id_professor = pr.id_usuario
    ";
}

$pacientes = $conn->query($query_pacientes);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Pacientes</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Visualizar Pacientes</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Data de Nascimento</th>
                <th>Gênero</th>
                <th>Endereço</th>
                <th>Telefone</th>
                <th>E-mail</th>
                <th>Cidade</th>
                <th>Estado</th>
                <th>Data de Início</th>
                <th>Contato Emergência</th>
                <th>Escolaridade</th>
                <th>Ocupação</th>
                <th>Necessidades Especiais</th>
                <th>Histórico Familiar</th>
                <th>Histórico Social</th>
                <th>Observações</th>
                <?php if ($_SESSION['nivel_acesso'] != 'aluno') { ?>
                    <th>Aluno Responsável</th>
                    <th>Turma</th>
                    <th>Professor Responsável</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php while ($paciente = $pacientes->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $paciente['id_paciente']; ?></td>
                <td><?php echo $paciente['nome']; ?></td>
                <td><?php echo $paciente['data_nascimento']; ?></td>
                <td><?php echo $paciente['genero']; ?></td>
                <td><?php echo $paciente['endereco']; ?></td>
                <td><?php echo $paciente['telefone']; ?></td>
                <td><?php echo $paciente['email']; ?></td>
                <td><?php echo $paciente['cidade']; ?></td>
                <td><?php echo $paciente['estado']; ?></td>
                <td><?php echo $paciente['data_inicio']; ?></td>
                <td><?php echo $paciente['contato_emergencia']; ?></td>
                <td><?php echo $paciente['escolaridade']; ?></td>
                <td><?php echo $paciente['ocupacao']; ?></td>
                <td><?php echo $paciente['necessidade_especial']; ?></td>
                <td><?php echo $paciente['hist_familiar']; ?></td>
                <td><?php echo $paciente['hist_social']; ?></td>
                <td><?php echo $paciente['finais']; ?></td>
                <?php if ($_SESSION['nivel_acesso'] != 'aluno') { ?>
                    <td><?php echo $paciente['aluno_responsavel']; ?></td>
                    <td><?php echo $paciente['turma_nome'] ?? 'Sem Turma'; ?></td>
                    <td><?php echo $paciente['professor_responsavel'] ?? 'Sem Professor'; ?></td>
                <?php } ?>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>

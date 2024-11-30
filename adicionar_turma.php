<?php
include 'includes/db.php';
session_start();

if ($_SESSION['nivel_acesso'] != 'admin') {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $id_professor = $_POST['id_professor'];
    $id_turma = isset($_POST['id_turma']) ? $_POST['id_turma'] : null;

    if ($id_turma) {
        $query = "UPDATE turmas SET nome = '$nome', id_professor = '$id_professor' WHERE id_turma = '$id_turma'";
        $mensagem = $conn->query($query) ? "Turma atualizada com sucesso!" : "Erro ao atualizar turma: " . $conn->error;
    } else {
        $data_criacao = date('Y-m-d H:i:s');
        $query = "INSERT INTO turmas (nome, id_professor, data_criacao) VALUES ('$nome', '$id_professor', '$data_criacao')";
        $mensagem = $conn->query($query) ? "Turma adicionada com sucesso!" : "Erro ao adicionar turma: " . $conn->error;
    }
    echo "<p>$mensagem</p>";
}

$professores = $conn->query("SELECT * FROM usuarios WHERE nivel_acesso = 'professor'");

$turmas = $conn->query("SELECT t.id_turma, t.nome AS turma_nome, t.data_criacao, p.nome AS professor_nome, t.id_professor
                        FROM turmas t
                        LEFT JOIN usuarios p ON t.id_professor = p.id_usuario");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Turmas</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Gerenciar Turmas</h1>

    <form method="POST">
        <label for="nome">Nome da Turma:</label>
        <input type="text" name="nome" id="nome" placeholder="Nome da Turma" required>

        <label for="id_professor">Selecione o Professor:</label>
        <select name="id_professor" id="id_professor" required>
            <option value="">Selecione o Professor</option>
            <?php while ($professor = $professores->fetch_assoc()) { ?>
                <option value="<?php echo $professor['id_usuario']; ?>"><?php echo $professor['nome']; ?></option>
            <?php } ?>
        </select>

        <input type="hidden" name="id_turma" id="id_turma">

        <button type="submit">Salvar</button>
    </form>

    <hr>

    <h2>Turmas Cadastradas</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome da Turma</th>
                <th>Professor Responsável</th>
                <th>Data de Criação</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($turma = $turmas->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $turma['id_turma']; ?></td>
                <td><?php echo $turma['turma_nome']; ?></td>
                <td><?php echo $turma['professor_nome'] ?: "Sem Professor"; ?></td>
                <td><?php echo $turma['data_criacao']; ?></td>
                <td>
                    <button onclick="editarTurma('<?php echo $turma['id_turma']; ?>', '<?php echo htmlspecialchars($turma['turma_nome'], ENT_QUOTES); ?>', '<?php echo $turma['id_professor']; ?>')">Editar</button>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <script>
        function editarTurma(id, nome, professorId) {
            document.getElementById('id_turma').value = id;
            document.getElementById('nome').value = nome;
            document.getElementById('id_professor').value = professorId;
        }
    </script>
</body>
</html>

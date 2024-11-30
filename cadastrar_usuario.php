<?php
include 'includes/db.php';
session_start();

if (!isset($_SESSION['id_usuario']) || $_SESSION['nivel_acesso'] != 'admin') {
    header('Location: index.php');
    exit;
}

// Lógica para processar o formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = md5($_POST['senha']);
    $nivel_acesso = $_POST['nivel_acesso'];
    $id_turma = $_POST['id_turma'] ?? null; // Apenas para professor e aluno
    $id_professor = $_POST['id_professor'] ?? null; // Apenas para aluno

    // Inserção no banco de dados
    $query = "INSERT INTO usuarios (nome, email, senha, nivel_acesso, id_turma) 
              VALUES ('$nome', '$email', '$senha', '$nivel_acesso', " . ($id_turma ? "'$id_turma'" : "NULL") . ")";
    if ($conn->query($query)) {
        // Para alunos, associar ao professor
        if ($nivel_acesso == 'aluno' && $id_professor) {
            $id_aluno = $conn->insert_id; // Último ID inserido
            $query_professor_aluno = "UPDATE usuarios SET id_professor = '$id_professor' WHERE id_usuario = '$id_aluno'";
            $conn->query($query_professor_aluno);
        }
        echo "Usuário cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar usuário: " . $conn->error;
    }
}

// Obter turmas para selecionar
$turmas = $conn->query("SELECT id_turma, nome FROM turmas");

// Obter professores para selecionar
$professores = $conn->query("SELECT id_usuario, nome FROM usuarios WHERE nivel_acesso = 'professor'");

// Obter todos os usuários para listagem
$usuarios = $conn->query("
    SELECT u.id_usuario, u.nome, u.email, u.nivel_acesso, t.nome AS turma
    FROM usuarios u
    LEFT JOIN turmas t ON u.id_turma = t.id_turma
");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuários</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Cadastrar Usuários</h1>

        <!-- Formulário para Cadastrar Usuários -->
        <form method="POST" class="form-cadastro">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" required placeholder="Digite o nome completo">

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required placeholder="Digite o email">

            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required placeholder="Digite uma senha">

            <label for="nivel_acesso">Nível de Acesso:</label>
            <select name="nivel_acesso" id="nivel_acesso" required>
                <option value="">Selecione o nível</option>
                <option value="professor">Professor</option>
                <option value="aluno">Aluno</option>
                <option value="admin">Administrador</option>
            </select>

            <!-- Seleção de Turma -->
            <div id="turma-field" style="display: none;">
                <label for="id_turma">Selecione a Turma:</label>
                <select name="id_turma" id="id_turma">
                    <option value="">-- Escolha uma Turma --</option>
                    <?php while ($turma = $turmas->fetch_assoc()) { ?>
                        <option value="<?php echo $turma['id_turma']; ?>"><?php echo $turma['nome']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <!-- Seleção de Professor -->
            <div id="professor-field" style="display: none;">
                <label for="id_professor">Selecione o Professor:</label>
                <select name="id_professor" id="id_professor">
                    <option value="">-- Escolha um Professor --</option>
                    <?php while ($professor = $professores->fetch_assoc()) { ?>
                        <option value="<?php echo $professor['id_usuario']; ?>"><?php echo $professor['nome']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <button type="submit" class="btn">Cadastrar</button>
        </form>

        <hr>

        <!-- Listagem de Usuários -->
        <h2>Usuários Cadastrados</h2>
        <table class="table-usuarios">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Nível de Acesso</th>
                    <th>Turma</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($usuario = $usuarios->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $usuario['id_usuario']; ?></td>
                    <td><?php echo $usuario['nome']; ?></td>
                    <td><?php echo $usuario['email']; ?></td>
                    <td><?php echo ucfirst($usuario['nivel_acesso']); ?></td>
                    <td><?php echo $usuario['turma'] ? $usuario['turma'] : 'N/A'; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script>
        const nivelAcessoField = document.getElementById('nivel_acesso');
        const turmaField = document.getElementById('turma-field');
        const professorField = document.getElementById('professor-field');

        nivelAcessoField.addEventListener('change', function() {
            if (this.value === 'professor') {
                turmaField.style.display = 'block';
                professorField.style.display = 'none';
            } else if (this.value === 'aluno') {
                turmaField.style.display = 'block';
                professorField.style.display = 'block';
            } else {
                turmaField.style.display = 'none';
                professorField.style.display = 'none';
            }
        });
    </script>
</body>
</html>

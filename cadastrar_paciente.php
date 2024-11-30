<?php
include 'includes/db.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: index.php');
    exit;
}

// Preencher professores (para admin)
if ($_SESSION['nivel_acesso'] == 'admin') {
    $professores = $conn->query("SELECT id_usuario, nome FROM usuarios WHERE nivel_acesso = 'professor'");
}

// Preencher alunos (dinâmico para admin e professor)
if ($_SESSION['nivel_acesso'] == 'professor') {
    $id_professor = $_SESSION['id_usuario'];
    $alunos = $conn->query("
        SELECT id_usuario, nome 
        FROM usuarios 
        WHERE nivel_acesso = 'aluno' AND id_turma IN (SELECT id_turma FROM turmas WHERE id_professor = '$id_professor')
    ");
}

// Adicionar paciente
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = htmlspecialchars($_POST['nome'], ENT_QUOTES, 'UTF-8');
    $data_nascimento = htmlspecialchars($_POST['data_nascimento'], ENT_QUOTES, 'UTF-8');
    $genero = htmlspecialchars($_POST['genero'], ENT_QUOTES, 'UTF-8');
    $endereco = htmlspecialchars($_POST['endereco'], ENT_QUOTES, 'UTF-8');
    $telefone = htmlspecialchars($_POST['telefone'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $cidade = htmlspecialchars($_POST['cidade'], ENT_QUOTES, 'UTF-8');
    $estado = htmlspecialchars($_POST['estado'], ENT_QUOTES, 'UTF-8');
    $data_inicio = htmlspecialchars($_POST['data_inicio'], ENT_QUOTES, 'UTF-8');
    $contato_emergencia = htmlspecialchars($_POST['contato_emergencia'], ENT_QUOTES, 'UTF-8');
    $escolaridade = htmlspecialchars($_POST['escolaridade'], ENT_QUOTES, 'UTF-8');
    $ocupacao = htmlspecialchars($_POST['ocupacao'], ENT_QUOTES, 'UTF-8');
    $necessidade_especial = htmlspecialchars($_POST['necessidade_especial'], ENT_QUOTES, 'UTF-8');
    $hist_familiar = htmlspecialchars($_POST['hist_familiar'], ENT_QUOTES, 'UTF-8');
    $hist_social = htmlspecialchars($_POST['hist_social'], ENT_QUOTES, 'UTF-8');
    $finais = htmlspecialchars($_POST['finais'], ENT_QUOTES, 'UTF-8');

    if ($_SESSION['nivel_acesso'] == 'admin') {
        $id_professor = htmlspecialchars($_POST['id_professor'], ENT_QUOTES, 'UTF-8');
        $id_aluno = htmlspecialchars($_POST['id_aluno'], ENT_QUOTES, 'UTF-8');
    } elseif ($_SESSION['nivel_acesso'] == 'professor') {
        $id_professor = $_SESSION['id_usuario'];
        $id_aluno = htmlspecialchars($_POST['id_aluno'], ENT_QUOTES, 'UTF-8');
    } else {
        $id_aluno = $_SESSION['id_usuario'];
        $query_professor = "
            SELECT t.id_professor 
            FROM turmas t
            JOIN usuarios u ON u.id_turma = t.id_turma
            WHERE u.id_usuario = '$id_aluno'
        ";
        $result_professor = $conn->query($query_professor);
        $id_professor = $result_professor->fetch_assoc()['id_professor'];
    }

    $query = "
        INSERT INTO pacientes 
        (nome, data_nascimento, genero, endereco, telefone, email, cidade, estado, data_inicio, contato_emergencia, escolaridade, ocupacao, necessidade_especial, hist_familiar, hist_social, finais, id_aluno, id_professor) 
        VALUES 
        ('$nome', '$data_nascimento', '$genero', '$endereco', '$telefone', '$email', '$cidade', '$estado', '$data_inicio', '$contato_emergencia', '$escolaridade', '$ocupacao', '$necessidade_especial', '$hist_familiar', '$hist_social', '$finais', '$id_aluno', '$id_professor')
    ";

    if ($conn->query($query)) {
        echo "<p>Paciente cadastrado com sucesso!</p>";
    } else {
        echo "<p>Erro ao cadastrar paciente: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Paciente</title>
    <link rel="stylesheet" href="css/styles.css">
    <script>
        function fetchAlunos(idProfessor) {
            const alunoSelect = document.getElementById('id_aluno');
            alunoSelect.innerHTML = '<option value="">-- Escolha um Aluno --</option>';

            if (idProfessor) {
                fetch(`fetch_alunos.php?id_professor=${idProfessor}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            data.forEach(aluno => {
                                const option = document.createElement('option');
                                option.value = aluno.id_usuario;
                                option.textContent = aluno.nome;
                                alunoSelect.appendChild(option);
                            });
                        } else {
                            alunoSelect.innerHTML = '<option value="">Nenhum aluno disponível</option>';
                        }
                    })
                    .catch(error => console.error('Erro ao carregar alunos:', error));
            }
        }
    </script>
</head>
<body>
    <h1>Cadastrar Paciente</h1>
    <form method="POST">
        <input type="text" name="nome" placeholder="Nome do Paciente" required>
        <input type="date" name="data_nascimento" required>
        <select name="genero" required>
            <option value="">Selecione o Gênero</option>
            <option value="Masculino">Masculino</option>
            <option value="Feminino">Feminino</option>
            <option value="Outro">Outro</option>
        </select>
        <textarea name="endereco" placeholder="Endereço"></textarea>
        <input type="text" name="telefone" placeholder="Telefone">
        <input type="email" name="email" placeholder="E-mail">
        <input type="text" name="cidade" placeholder="Cidade">
        <input type="text" name="estado" placeholder="Estado" maxlength="2">
        <input type="date" name="data_inicio">
        <input type="text" name="contato_emergencia" placeholder="Contato de Emergência">
        <input type="text" name="escolaridade" placeholder="Escolaridade">
        <input type="text" name="ocupacao" placeholder="Ocupação">
        <textarea name="necessidade_especial" placeholder="Necessidades Especiais"></textarea>
        <textarea name="hist_familiar" placeholder="Histórico Familiar"></textarea>
        <textarea name="hist_social" placeholder="Histórico Social"></textarea>
        <textarea name="finais" placeholder="Observações Finais"></textarea>

        <?php if ($_SESSION['nivel_acesso'] == 'admin') { ?>
            <label for="id_professor">Selecione o Professor:</label>
            <select name="id_professor" id="id_professor" onchange="fetchAlunos(this.value)" required>
                <option value="">-- Escolha um Professor --</option>
                <?php while ($professor = $professores->fetch_assoc()) { ?>
                    <option value="<?php echo $professor['id_usuario']; ?>"><?php echo $professor['nome']; ?></option>
                <?php } ?>
            </select>

            <label for="id_aluno">Selecione o Aluno:</label>
            <select name="id_aluno" id="id_aluno" required>
                <option value="">-- Escolha um Aluno --</option>
            </select>
        <?php } elseif ($_SESSION['nivel_acesso'] == 'professor') { ?>
            <label for="id_aluno">Selecione o Aluno:</label>
            <select name="id_aluno" id="id_aluno" required>
                <option value="">-- Escolha um Aluno --</option>
                <?php while ($aluno = $alunos->fetch_assoc()) { ?>
                    <option value="<?php echo $aluno['id_usuario']; ?>"><?php echo $aluno['nome']; ?></option>
                <?php } ?>
            </select>
        <?php } else { ?>
            <input type="hidden" name="id_aluno" value="<?php echo $_SESSION['id_usuario']; ?>">
        <?php } ?>

        <button type="submit">Cadastrar Paciente</button>
    </form>
</body>
</html>

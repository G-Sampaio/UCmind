<?php
include 'includes/db.php';
session_start();

if ($_SESSION['nivel_acesso'] != 'admin' && $_SESSION['nivel_acesso'] != 'professor' && $_SESSION['nivel_acesso'] != 'aluno') {
    header('Location: dashboard.php');
    exit;
}

// Adicionar paciente
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $data_nascimento = $_POST['data_nascimento'];
    $genero = $_POST['genero'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $data_inicio = $_POST['data_inicio'];
    $contato_emergencia = $_POST['contato_emergencia'];
    $escolaridade = $_POST['escolaridade'];
    $ocupacao = $_POST['ocupacao'];
    $necessidade_especial = $_POST['necessidade_especial'];
    $hist_familiar = $_POST['hist_familiar'];
    $hist_social = $_POST['hist_social'];
    $finais = $_POST['finais'];

    if ($_SESSION['nivel_acesso'] == 'admin') {
        $id_professor = $_POST['id_professor'];
        $id_aluno = $_POST['id_aluno'];
    } elseif ($_SESSION['nivel_acesso'] == 'professor') {
        $id_professor = $_SESSION['id_usuario'];
        $id_aluno = $_POST['id_aluno'];
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

// Listar pacientes com base no nível de acesso
if ($_SESSION['nivel_acesso'] == 'aluno') {
    $id_aluno = $_SESSION['id_usuario'];
    $query_pacientes = "SELECT * FROM pacientes WHERE id_aluno = '$id_aluno'";
} elseif ($_SESSION['nivel_acesso'] == 'professor') {
    $id_professor = $_SESSION['id_usuario'];
    $query_pacientes = "
        SELECT p.*, u.nome AS aluno_responsavel, pr.nome AS professor_responsavel
        FROM pacientes p
        JOIN usuarios u ON p.id_aluno = u.id_usuario
        JOIN usuarios pr ON p.id_professor = pr.id_usuario
        WHERE p.id_professor = '$id_professor'
    ";
} else {
    $query_pacientes = "
        SELECT p.*, u.nome AS aluno_responsavel, pr.nome AS professor_responsavel
        FROM pacientes p
        JOIN usuarios u ON p.id_aluno = u.id_usuario
        LEFT JOIN usuarios pr ON p.id_professor = pr.id_usuario
    ";
}

$pacientes = $conn->query($query_pacientes);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Pacientes</title>
    <link rel="stylesheet" href="css/styles.css">
    <script>
        function fetchAlunos(idProfessor) {
            const alunoSelect = document.getElementById('id_aluno');
            alunoSelect.innerHTML = '<option value="">-- Escolha um Aluno --</option>';

            if (idProfessor) {
                fetch(`fetch_alunos.php?id_professor=${idProfessor}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(aluno => {
                            const option = document.createElement('option');
                            option.value = aluno.id_usuario;
                            option.textContent = aluno.nome;
                            alunoSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Erro ao carregar alunos:', error));
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Gerenciar Pacientes</h1>
        </header>
        <main>
            <!-- Formulário para adicionar pacientes -->
            <form method="POST" class="form">
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
                <input type="date" name="data_inicio" placeholder="Data de Início">
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

                <button type="submit" class="btn-primary">Adicionar Paciente</button>
            </form>

            <!-- Listar Pacientes -->
            <table class="table">
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
                            <td><?php echo $paciente['professor_responsavel']; ?></td>
                        <?php } ?>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>


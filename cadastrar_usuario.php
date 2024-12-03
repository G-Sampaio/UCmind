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

    // Inserção no banco de dados
    $query = "INSERT INTO usuarios (nome, email, senha, nivel_acesso, id_turma) 
              VALUES ('$nome', '$email', '$senha', '$nivel_acesso', " . ($id_turma ? "'$id_turma'" : "NULL") . ")";
    if ($conn->query($query)) {
        // Obtém o ID do último usuário inserido
        $id_usuario = $conn->insert_id;

        // Se o usuário for um professor, atualiza a tabela `turmas`
        if ($nivel_acesso === 'professor' && $id_turma) {
            $query_update_turma = "UPDATE turmas SET id_professor = '$id_usuario' WHERE id_turma = '$id_turma'";
            if (!$conn->query($query_update_turma)) {
                echo "Erro ao atualizar a turma: " . $conn->error;
            }
        }

        // Para alunos, associar ao professor
        if ($nivel_acesso === 'aluno') {
            $id_professor = $_POST['id_professor'] ?? null;
            if ($id_professor) {
                $query_associar_professor = "UPDATE usuarios SET id_professor = '$id_professor' WHERE id_usuario = '$id_usuario'";
                if (!$conn->query($query_associar_professor)) {
                    echo "Erro ao associar o professor ao aluno: " . $conn->error;
                }
            }
        }

        echo "";
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
            color: #00509e;
        }

        .form-cadastro label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #00509e;
        }

        .form-cadastro input, 
        .form-cadastro select, 
        .form-cadastro button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #d9d9d9;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-cadastro input:focus, 
        .form-cadastro select:focus {
            border-color: #00509e;
            outline: none;
        }

        .form-cadastro button {
            background-color: #00509e;
            color: white;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
        }

        .form-cadastro button:hover {
            background-color: #003f7e;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
        }

        table th {
            background-color: #00509e;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #e0f0ff;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            table, th, td {
                font-size: 14px;
            }

            .form-cadastro input, 
            .form-cadastro select, 
            .form-cadastro button {
                font-size: 14px;
            }
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
    <script>
        function atualizarProfessoresPorTurma(turmaId) {
            const professorField = document.getElementById('id_professor');
            professorField.innerHTML = '<option value="">-- Escolha um Professor --</option>';

            if (turmaId) {
                fetch(`fetch_professor_por_turma.php?id_turma=${turmaId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length) {
                            const professor = data[0];
                            const option = document.createElement('option');
                            option.value = professor.id_usuario;
                            option.textContent = professor.nome;
                            professorField.appendChild(option);
                        } else {
                            alert('Nenhum professor associado a esta turma.');
                        }
                    })
                    .catch(error => console.error('Erro ao carregar professor:', error));
            }
        }

        function atualizarCamposNivelAcesso() {
            const nivelAcesso = document.getElementById('nivel_acesso').value;
            const turmaField = document.getElementById('turma-field');
            const professorField = document.getElementById('professor-field');

            turmaField.style.display = nivelAcesso === 'professor' || nivelAcesso === 'aluno' ? 'block' : 'none';
            professorField.style.display = nivelAcesso === 'aluno' ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Cadastrar Usuários</h1>

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

            <div id="turma-field" style="display: none;">
                <label for="id_turma">Selecione a Turma:</label>
                <select name="id_turma" id="id_turma">
                    <option value="">-- Escolha uma Turma --</option>
                    <?php while ($turma = $turmas->fetch_assoc()) { ?>
                        <option value="<?php echo $turma['id_turma']; ?>"><?php echo $turma['nome']; ?></option>
                    <?php } ?>
                </select>
            </div>

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
            <a href="dashboard.php" class="btn-dashboard">Voltar para o Dashboard</a>
        </form>

        <hr>

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


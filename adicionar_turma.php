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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Turmas</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
            line-height: 1.6;
        }

        h1, h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            margin: 20px auto;
            max-width: 600px;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        form label {
            width: 100%;
            font-weight: bold;
            color: #2c3e50;
        }

        form input,
        form select,
        form button {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
            width: 100%;
            max-width: 300px;
            box-sizing: border-box;
        }

        form button {
            background-color: #3498db;
            color: white;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #2980b9;
        }

        .btn-back {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #3498db;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            width: fit-content;
            transition: background-color 0.3s ease;
        }

        .btn-back:hover {
            background-color: #2980b9;
        }

        hr {
            border: 0;
            border-top: 1px solid #ddd;
            margin: 20px 0;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background: #fff;
        }

        table th,
        table td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #3498db;
            color: white;
            font-weight: 700;
        }

        table tr:hover {
            background-color: #f9f9f9;
        }

        table tr:last-child td {
            border-bottom: none;
        }

        table td button {
            padding: 8px 12px;
            font-size: 14px;
            color: white;
            background-color: #3498db;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        table td button:hover {
            background-color: #2980b9;
        }

        @media (max-width: 768px) {
            form {
                flex-direction: column;
                padding: 15px;
            }

            table {
                width: 100%;
                font-size: 14px;
            }

            table th,
            table td {
                padding: 10px;
            }
        }

    </style>
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
    <a href="dashboard.php" class="btn-back">Voltar para o Dashboard</a>
</body>
</html>


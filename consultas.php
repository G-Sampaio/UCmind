<?php
include 'includes/db.php';
session_start();

if ($_SESSION['nivel_acesso'] != 'admin' && $_SESSION['nivel_acesso'] != 'professor' && $_SESSION['nivel_acesso'] != 'aluno') {
    header('Location: dashboard.php');
    exit;
}

// Inserir consulta no banco de dados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_paciente = $_POST['id_paciente'];
    $data_consulta = $_POST['data_consulta'];
    $observacoes = $_POST['observacoes'];

    $query = "INSERT INTO consultas (id_paciente, data_consulta, observacoes) VALUES ('$id_paciente', '$data_consulta', '$observacoes')";
    $conn->query($query);
}

// Listar consultas com base no nível de acesso
if ($_SESSION['nivel_acesso'] == 'admin') {
    $consultas = $conn->query("
        SELECT c.id_consulta, c.data_consulta, c.observacoes, p.nome AS paciente, u.nome AS aluno_responsavel, pr.nome AS professor_responsavel
        FROM consultas c
        JOIN pacientes p ON c.id_paciente = p.id_paciente
        JOIN usuarios u ON p.id_aluno = u.id_usuario
        JOIN usuarios pr ON p.id_professor = pr.id_usuario
    ");
} elseif ($_SESSION['nivel_acesso'] == 'professor') {
    $id_professor = $_SESSION['id_usuario'];
    $consultas = $conn->query("
        SELECT c.id_consulta, c.data_consulta, c.observacoes, p.nome AS paciente, u.nome AS aluno_responsavel
        FROM consultas c
        JOIN pacientes p ON c.id_paciente = p.id_paciente
        JOIN usuarios u ON p.id_aluno = u.id_usuario
        WHERE p.id_professor = '$id_professor'
    ");
} else {
    $id_aluno = $_SESSION['id_usuario'];
    $consultas = $conn->query("
        SELECT c.id_consulta, c.data_consulta, c.observacoes, p.nome AS paciente
        FROM consultas c
        JOIN pacientes p ON c.id_paciente = p.id_paciente
        WHERE p.id_aluno = '$id_aluno'
    ");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Consultas</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Gerenciar Consultas</h1>

    <!-- Formulário para adicionar consultas -->
    <form method="POST">
        <!-- Admin: Seleciona aluno e paciente -->
        <?php if ($_SESSION['nivel_acesso'] == 'admin') { ?>
            <label for="id_aluno">Selecione o Aluno:</label>
            <select name="id_aluno" id="id_aluno" onchange="fetchPacientes(this.value)" required>
                <option value="">-- Escolha um Aluno --</option>
                <?php
                $alunos = $conn->query("SELECT id_usuario, nome FROM usuarios WHERE nivel_acesso = 'aluno'");
                while ($aluno = $alunos->fetch_assoc()) {
                    echo "<option value='" . $aluno['id_usuario'] . "'>" . $aluno['nome'] . "</option>";
                }
                ?>
            </select>

            <label for="id_paciente">Selecione o Paciente:</label>
            <select name="id_paciente" id="id_paciente" required>
                <option value="">-- Escolha um Paciente --</option>
            </select>

        <!-- Professor: Seleciona paciente de seus alunos -->
        <?php } elseif ($_SESSION['nivel_acesso'] == 'professor') { ?>
            <label for="id_paciente">Selecione o Paciente:</label>
            <select name="id_paciente" id="id_paciente" required>
                <option value="">-- Escolha um Paciente --</option>
                <?php
                $id_professor = $_SESSION['id_usuario'];
                $pacientes = $conn->query("
                    SELECT p.id_paciente, p.nome
                    FROM pacientes p
                    JOIN usuarios u ON p.id_aluno = u.id_usuario
                    WHERE p.id_professor = '$id_professor'
                ");
                while ($paciente = $pacientes->fetch_assoc()) {
                    echo "<option value='" . $paciente['id_paciente'] . "'>" . $paciente['nome'] . "</option>";
                }
                ?>
            </select>

        <!-- Aluno: Apenas os próprios pacientes -->
        <?php } else { ?>
            <label for="id_paciente">Selecione o Paciente:</label>
            <select name="id_paciente" id="id_paciente" required>
                <option value="">-- Escolha um Paciente --</option>
                <?php
                $id_aluno = $_SESSION['id_usuario'];
                $pacientes = $conn->query("SELECT id_paciente, nome FROM pacientes WHERE id_aluno = '$id_aluno'");
                while ($paciente = $pacientes->fetch_assoc()) {
                    echo "<option value='" . $paciente['id_paciente'] . "'>" . $paciente['nome'] . "</option>";
                }
                ?>
            </select>
        <?php } ?>

        <input type="datetime-local" name="data_consulta" required>
        <textarea name="observacoes" placeholder="Observações" required></textarea>
        <button type="submit">Agendar Consulta</button>
    </form>

    <!-- Listar Consultas -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Paciente</th>
                <th>Data da Consulta</th>
                <th>Observações</th>
                <?php if ($_SESSION['nivel_acesso'] == 'admin') { ?>
                    <th>Aluno Responsável</th>
                    <th>Professor Responsável</th>
                <?php } elseif ($_SESSION['nivel_acesso'] == 'professor') { ?>
                    <th>Aluno Responsável</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php while ($consulta = $consultas->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $consulta['id_consulta']; ?></td>
                <td><?php echo $consulta['paciente']; ?></td>
                <td><?php echo $consulta['data_consulta']; ?></td>
                <td><?php echo $consulta['observacoes']; ?></td>
                <?php if ($_SESSION['nivel_acesso'] == 'admin') { ?>
                    <td><?php echo $consulta['aluno_responsavel']; ?></td>
                    <td><?php echo $consulta['professor_responsavel']; ?></td>
                <?php } elseif ($_SESSION['nivel_acesso'] == 'professor') { ?>
                    <td><?php echo $consulta['aluno_responsavel']; ?></td>
                <?php } ?>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <script>
        function fetchPacientes(idAluno) {
            const pacienteSelect = document.getElementById('id_paciente');
            pacienteSelect.innerHTML = '<option value="">-- Escolha um Paciente --</option>';
            
            if (idAluno) {
                fetch(`fetch_pacientes.php?id_aluno=${idAluno}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(paciente => {
                            const option = document.createElement('option');
                            option.value = paciente.id_paciente;
                            option.textContent = paciente.nome;
                            pacienteSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Erro ao buscar pacientes:', error));
            }
        }
    </script>
</body>
</html>

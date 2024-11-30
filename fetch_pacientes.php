<?php
include 'includes/db.php';

if (isset($_GET['id_aluno'])) {
    $id_aluno = $_GET['id_aluno'];
    $query = "SELECT id_paciente, nome FROM pacientes WHERE id_aluno = '$id_aluno'";
    $result = $conn->query($query);

    $pacientes = [];
    while ($row = $result->fetch_assoc()) {
        $pacientes[] = $row;
    }

    echo json_encode($pacientes);
}
?>

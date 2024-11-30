<?php
include 'includes/db.php';

if (isset($_GET['id_professor'])) {
    $id_professor = $_GET['id_professor'];
    $query = "
        SELECT id_usuario, nome 
        FROM usuarios 
        WHERE nivel_acesso = 'aluno' AND id_turma IN (SELECT id_turma FROM turmas WHERE id_professor = '$id_professor')
    ";
    $result = $conn->query($query);

    $alunos = [];
    while ($row = $result->fetch_assoc()) {
        $alunos[] = $row;
    }

    echo json_encode($alunos);
}
?>

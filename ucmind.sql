-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 30/11/2024 às 23:15
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `ucmind`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `consultas`
--

CREATE TABLE `consultas` (
  `id_consulta` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `data_consulta` datetime NOT NULL,
  `observacoes` text DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `consultas`
--

INSERT INTO `consultas` (`id_consulta`, `id_paciente`, `data_consulta`, `observacoes`, `data_criacao`, `descricao`) VALUES
(1, 1, '2024-11-13 02:13:00', 'olá teste', '2024-11-30 14:09:50', NULL),
(4, 1, '2024-11-13 02:13:00', 'olá teste', '2024-11-30 14:11:50', NULL),
(5, 4, '2024-11-29 11:41:00', 'consultado', '2024-11-30 14:41:27', NULL),
(6, 4, '2024-11-29 11:41:00', 'consultado', '2024-11-30 14:42:35', NULL),
(7, 3, '2024-11-14 11:42:00', 'constula cast', '2024-11-30 14:42:47', NULL),
(8, 4, '2024-11-20 11:58:00', 'nova consulta', '2024-11-30 14:58:36', NULL),
(9, 6, '2024-11-14 11:59:00', 'consulta xerxes', '2024-11-30 14:59:15', NULL),
(10, 7, '2024-11-13 12:29:00', 'Ele é doido', '2024-11-30 15:29:35', NULL),
(11, 8, '2024-11-05 12:33:00', 'Maninho', '2024-11-30 15:34:00', NULL),
(12, 10, '2024-11-12 15:27:00', 'Consultou', '2024-11-30 18:27:13', NULL),
(13, 11, '2024-11-13 15:28:00', 'teste', '2024-11-30 18:28:34', NULL),
(14, 14, '2024-11-05 15:34:00', 'teste', '2024-11-30 18:34:44', NULL),
(15, 12, '2024-11-11 15:34:00', 'teste', '2024-11-30 18:35:02', NULL),
(16, 10, '2024-11-07 15:35:00', 'teste', '2024-11-30 18:35:11', NULL),
(17, 6, '2024-11-05 15:35:00', 'testeq22', '2024-11-30 18:35:28', NULL),
(18, 3, '2024-11-04 15:35:00', 'teste', '2024-11-30 18:35:38', NULL),
(19, 3, '2024-11-06 15:36:00', 'teste', '2024-11-30 18:36:16', NULL),
(20, 3, '2024-11-06 15:36:00', 'teste', '2024-11-30 18:36:20', NULL),
(21, 3, '2024-11-06 15:36:00', 'teste', '2024-11-30 18:38:08', NULL),
(22, 11, '2024-11-06 15:45:00', 'teste', '2024-11-30 18:45:46', NULL),
(23, 13, '2024-11-05 15:50:00', 'rodrigo consultaou', '2024-11-30 18:50:45', NULL),
(24, 18, '2024-11-07 15:59:00', 'Ezreal veio saudáviel', '2024-11-30 18:59:58', NULL),
(25, 21, '2024-11-05 16:04:00', 'Jonas está bem', '2024-11-30 19:05:10', NULL),
(26, 23, '2024-11-06 16:07:00', 'mauricio passosbem', '2024-11-30 19:07:10', NULL),
(27, 25, '2024-11-27 17:05:00', 'testesteste', '2024-11-30 20:05:53', NULL),
(28, 8, '2024-11-06 17:16:00', 'teste', '2024-11-30 20:14:27', 'teste'),
(29, 8, '2024-11-13 17:15:00', 'asdasdasd', '2024-11-30 20:15:00', 'teste'),
(30, 26, '2024-11-01 22:28:00', '', '2024-11-30 21:24:59', ''),
(31, 26, '2024-11-30 21:28:00', 'teste', '2024-11-30 21:25:09', 'asd'),
(32, 27, '2024-10-10 21:37:00', 'teste', '2024-11-30 21:34:44', 'asd');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pacientes`
--

CREATE TABLE `pacientes` (
  `id_paciente` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `data_nascimento` date NOT NULL,
  `genero` enum('Masculino','Feminino','Outro') NOT NULL,
  `contato` varchar(50) DEFAULT NULL,
  `id_aluno` int(11) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_professor` int(11) DEFAULT NULL,
  `endereco` text DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `cidade` varchar(255) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `contato_emergencia` varchar(255) DEFAULT NULL,
  `escolaridade` varchar(255) DEFAULT NULL,
  `ocupacao` varchar(255) DEFAULT NULL,
  `necessidade_especial` text DEFAULT NULL,
  `hist_familiar` text DEFAULT NULL,
  `hist_social` text DEFAULT NULL,
  `finais` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pacientes`
--

INSERT INTO `pacientes` (`id_paciente`, `nome`, `data_nascimento`, `genero`, `contato`, `id_aluno`, `data_criacao`, `id_professor`, `endereco`, `telefone`, `email`, `cidade`, `estado`, `data_inicio`, `contato_emergencia`, `escolaridade`, `ocupacao`, `necessidade_especial`, `hist_familiar`, `hist_social`, `finais`) VALUES
(1, 'João', '2024-11-06', 'Masculino', NULL, 1, '2024-11-30 14:09:22', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'Jonas', '2024-11-28', 'Masculino', NULL, 2, '2024-11-30 14:24:51', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'Julia', '2024-11-19', 'Feminino', NULL, 3, '2024-11-30 14:26:39', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'Kalil', '2024-11-14', 'Masculino', NULL, 3, '2024-11-30 14:29:14', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'Arthur', '2024-11-25', 'Masculino', NULL, 3, '2024-11-30 14:29:58', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'Xerxes', '2024-11-12', 'Masculino', NULL, 3, '2024-11-30 14:58:58', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'Gustavo', '2024-11-27', 'Masculino', NULL, 6, '2024-11-30 15:29:24', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 'Manu', '2024-11-05', 'Feminino', NULL, 7, '2024-11-30 15:33:49', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'Vlad', '2024-11-14', 'Masculino', NULL, 6, '2024-11-30 15:41:28', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'Mordekaiser', '2024-11-20', 'Outro', NULL, 7, '2024-11-30 15:42:26', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 'Camille', '2024-11-14', 'Feminino', NULL, 7, '2024-11-30 15:42:44', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 'Vayne', '2024-11-18', 'Feminino', NULL, 3, '2024-11-30 18:11:08', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 'Vayne', '2024-11-18', 'Feminino', NULL, 3, '2024-11-30 18:15:16', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 'Jonas da Silva', '2024-11-12', 'Masculino', NULL, 7, '2024-11-30 18:28:24', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 'Rodrigo', '2019-12-29', 'Feminino', NULL, 3, '2024-11-30 18:50:25', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 'valdir oliveira', '2024-11-20', 'Masculino', NULL, 3, '2024-11-30 18:51:34', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, 'Kai Sa', '2024-11-13', 'Feminino', NULL, 3, '2024-11-30 18:58:58', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 'Ezreal', '2024-11-14', 'Masculino', NULL, 7, '2024-11-30 18:59:25', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 'Draven', '2024-11-13', 'Masculino', NULL, 7, '2024-11-30 19:00:16', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 'Violet', '2024-11-20', 'Outro', NULL, 3, '2024-11-30 19:02:24', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 'Jonas vladimr', '2024-11-05', 'Masculino', NULL, 6, '2024-11-30 19:04:50', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, 'Jinx', '2024-11-06', 'Feminino', NULL, 3, '2024-11-30 19:05:50', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 'Mauricio Passos', '2024-11-28', 'Masculino', NULL, 7, '2024-11-30 19:06:56', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 'João da Silva', '2024-11-19', 'Outro', NULL, 7, '2024-11-30 19:36:38', 4, 'rua teste', '123123123', 'h@teste', 'Curitiba', 'PR', '2008-11-29', 'Mãe', 'Cursando Superior', 'Trabalhando', 'Nenhuma', 'Problema com o pai', 'Vulnerabilidade', 'teste final pfv da certo'),
(25, 'Jorge Negão', '2024-11-26', 'Masculino', NULL, 3, '2024-11-30 19:37:50', 2, 'teste', '123123123', 't@te', 'teste', 'TE', '2024-11-10', '123123', 'alto', 'baixo', 'médio', 'teste', 'teste', 'teste'),
(26, 'Jose Santos', '2024-11-29', 'Masculino', NULL, 11, '2024-11-30 21:24:46', 9, 'teste', '123', 't@t', 'asd', 'as', '2024-11-29', '123', 'asd', 'asd', 'asd', 'asd', 'asd', 'asd'),
(27, 'teste', '2024-11-29', 'Masculino', NULL, 11, '2024-11-30 21:34:23', 9, 'asd', 'asd', 'asd@a', 'asd', 'as', '2024-11-16', 'asd', 'asd', 'asd', 'asd', 'asd', 'asd', 'asd');

-- --------------------------------------------------------

--
-- Estrutura para tabela `professor_turma`
--

CREATE TABLE `professor_turma` (
  `id_professor` int(11) NOT NULL,
  `id_turma` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `turmas`
--

CREATE TABLE `turmas` (
  `id_turma` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `id_professor` int(11) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `turmas`
--

INSERT INTO `turmas` (`id_turma`, `nome`, `id_professor`, `data_criacao`) VALUES
(1, 'Turma', 4, '2024-11-30 14:05:28'),
(3, 'Turma do turma', 2, '2024-11-30 18:23:36'),
(4, 'Turma Segunda feira', 2, '2024-11-30 19:16:49'),
(5, 'Turma B', 2, '2024-11-30 19:31:06'),
(6, 'Turma A', 9, '2024-12-01 01:19:10');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `nivel_acesso` enum('admin','professor','aluno') NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_turma` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nome`, `email`, `senha`, `nivel_acesso`, `data_criacao`, `id_turma`) VALUES
(1, 'Administrador', 'admin@ucmind.com', 'e7d80ffeefa212b7c5c55700e4f7193e', 'admin', '2024-11-30 14:03:09', NULL),
(2, 'Professor Teste', 'professor@ucmind.com', 'e7d80ffeefa212b7c5c55700e4f7193e', 'professor', '2024-11-30 14:03:18', 1),
(3, 'Aluno Teste', 'aluno@ucmind.com', 'e7d80ffeefa212b7c5c55700e4f7193e', 'aluno', '2024-11-30 14:03:26', 1),
(4, 'Guilherme', 'g@gmail.com', '202cb962ac59075b964b07152d234b70', 'professor', '2024-11-30 15:21:31', 1),
(5, 'Wanda', 'w@gmail.com', '202cb962ac59075b964b07152d234b70', 'admin', '2024-11-30 15:21:47', NULL),
(6, 'Lori', 'l@gmail.com', '202cb962ac59075b964b07152d234b70', 'aluno', '2024-11-30 15:22:23', 1),
(7, 'Wagner', 'ww@gmail.com', '202cb962ac59075b964b07152d234b70', 'aluno', '2024-11-30 15:32:33', 5),
(9, 'Lucas', 'll@gmail.com', '202cb962ac59075b964b07152d234b70', 'professor', '2024-11-30 21:17:42', 6),
(10, 'Lucas Santos', 'ls@gmail.com', '202cb962ac59075b964b07152d234b70', 'aluno', '2024-11-30 21:20:24', 6),
(11, 'José Souza', 'js@gmail.com', '202cb962ac59075b964b07152d234b70', 'aluno', '2024-11-30 21:20:52', 6),
(12, 'Administrador do Sistema', 'a@gmail.com', '202cb962ac59075b964b07152d234b70', 'admin', '2024-11-30 21:21:18', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `consultas`
--
ALTER TABLE `consultas`
  ADD PRIMARY KEY (`id_consulta`),
  ADD KEY `id_paciente` (`id_paciente`);

--
-- Índices de tabela `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id_paciente`),
  ADD KEY `id_aluno` (`id_aluno`),
  ADD KEY `fk_pacientes_professores` (`id_professor`);

--
-- Índices de tabela `professor_turma`
--
ALTER TABLE `professor_turma`
  ADD PRIMARY KEY (`id_professor`,`id_turma`),
  ADD KEY `id_turma` (`id_turma`);

--
-- Índices de tabela `turmas`
--
ALTER TABLE `turmas`
  ADD PRIMARY KEY (`id_turma`),
  ADD KEY `id_professor` (`id_professor`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_usuarios_turmas` (`id_turma`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `consultas`
--
ALTER TABLE `consultas`
  MODIFY `id_consulta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de tabela `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id_paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de tabela `turmas`
--
ALTER TABLE `turmas`
  MODIFY `id_turma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `consultas`
--
ALTER TABLE `consultas`
  ADD CONSTRAINT `consultas_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `pacientes`
--
ALTER TABLE `pacientes`
  ADD CONSTRAINT `fk_pacientes_professores` FOREIGN KEY (`id_professor`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pacientes_ibfk_1` FOREIGN KEY (`id_aluno`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `professor_turma`
--
ALTER TABLE `professor_turma`
  ADD CONSTRAINT `professor_turma_ibfk_1` FOREIGN KEY (`id_professor`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `professor_turma_ibfk_2` FOREIGN KEY (`id_turma`) REFERENCES `turmas` (`id_turma`) ON DELETE CASCADE;

--
-- Restrições para tabelas `turmas`
--
ALTER TABLE `turmas`
  ADD CONSTRAINT `turmas_ibfk_1` FOREIGN KEY (`id_professor`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_turmas` FOREIGN KEY (`id_turma`) REFERENCES `turmas` (`id_turma`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

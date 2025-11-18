-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 17/11/2025 às 05:34
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `petto`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `medicamentos`
--

CREATE TABLE `medicamentos` (
  `id_medicamento` int(11) NOT NULL,
  `id_dataset` varchar(100) DEFAULT NULL,
  `id_pet` int(11) NOT NULL,
  `nome_medicamento` varchar(100) NOT NULL,
  `data_aplicacao` date DEFAULT NULL,
  `data_desconhecida` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pets`
--

CREATE TABLE `pets` (
  `id_pet` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `especie` varchar(50) DEFAULT NULL,
  `raca` varchar(50) DEFAULT NULL,
  `idade_valor` int(11) DEFAULT NULL,
  `idade_unidade` varchar(10) DEFAULT 'anos',
  `idade_meses` int(11) DEFAULT NULL,
  `idade_dias` int(11) DEFAULT NULL,
  `peso` float DEFAULT NULL,
  `sexo` char(1) DEFAULT NULL,
  `cor` varchar(50) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `foto_url` varchar(255) DEFAULT NULL,
  `id_veterinario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pets`
--

INSERT INTO `pets` (`id_pet`, `nome`, `id_usuario`, `especie`, `raca`, `idade_valor`, `idade_unidade`, `idade_meses`, `idade_dias`, `peso`, `sexo`, `cor`, `data_nascimento`, `foto_url`, `id_veterinario`) VALUES
(122, 'Rex', 113, 'cachorro', 'vira_lata', 3, 'anos', NULL, NULL, 12, 'M', 'Caramelo', '2021-06-01', NULL, 81),
(123, 'Luna', 113, 'gato', 'siames', 2, 'anos', NULL, NULL, 4, 'F', 'Branca', '2023-04-12', NULL, 82),
(124, 'Bolt', 114, 'cachorro', 'pitbull', 1, 'anos', NULL, NULL, 20, 'M', 'Preto', '2024-02-20', NULL, 81),
(125, 'Mia', 114, 'gato', 'vira_lata', 4, 'anos', NULL, NULL, 3, 'F', 'Cinza', '2020-11-11', NULL, 82);

-- --------------------------------------------------------

--
-- Estrutura para tabela `prontuario`
--

CREATE TABLE `prontuario` (
  `id` int(11) NOT NULL,
  `id_pet` int(11) DEFAULT NULL,
  `id_veterinario` int(11) DEFAULT NULL,
  `data_consulta` date NOT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `diagnostico` text DEFAULT NULL,
  `tratamento` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `endereco` varchar(200) DEFAULT NULL,
  `foto_url` varchar(255) DEFAULT NULL,
  `pet_primario` varchar(100) DEFAULT NULL COMMENT 'Resposta para a pergunta: nome do primeiro pet',
  `cor_favorita` varchar(100) DEFAULT NULL COMMENT 'Resposta para a pergunta: cor favorita',
  `role` varchar(20) NOT NULL DEFAULT 'tutor'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `email`, `senha`, `nome`, `telefone`, `endereco`, `foto_url`, `pet_primario`, `cor_favorita`, `role`) VALUES
(104, 'a@a', '$2y$10$R83UlUWWYTXIO5FVgDESvOJXzqp4L5mryS.J.NSJDkDrgeN8dvUHe', 'uuy 555', '555555555', 'aaaaaaa', '', 'aaaaaa', 'aaaaaa', 'admin'),
(113, 'tutor1@example.com', '$2b$10$yJXLSIqYJkcH0rMKXJTTeuzrmP7mudYx/7CGNOc4i089DCABOj5CC', 'Tutor Um', '(11) 90000-0001', 'Rua Tutor 1, 10', NULL, 'Pet1', 'Azul', 'tutor'),
(114, 'tutor2@example.com', '$2b$10$yJXLSIqYJkcH0rMKXJTTeuzrmP7mudYx/7CGNOc4i089DCABOj5CC', 'Tutor Dois', '(11) 90000-0002', 'Rua Tutor 2, 20', NULL, 'Pet2', 'Verde', 'tutor'),
(115, '2@2', '$2b$10$yJXLSIqYJkcH0rMKXJTTeuzrmP7mudYx/7CGNOc4i089DCABOj5CC', 'Dr. Vet Dois', '(16) 90000-0002', 'Av Vet 2, 2', NULL, NULL, NULL, 'veterinario'),
(116, '3@3', '$2b$10$yJXLSIqYJkcH0rMKXJTTeuzrmP7mudYx/7CGNOc4i089DCABOj5CC', 'Dr. Vet Tres', '(16) 90000-0003', 'Av Vet 3, 3', NULL, NULL, NULL, 'veterinario');

-- --------------------------------------------------------

--
-- Estrutura para tabela `vacinas`
--

CREATE TABLE `vacinas` (
  `id_vacina` int(11) NOT NULL,
  `id_dataset` varchar(100) DEFAULT NULL,
  `nome` varchar(100) NOT NULL,
  `data_aplicacao` date DEFAULT NULL,
  `proxima_aplicacao` date NOT NULL,
  `data_desconhecida` tinyint(1) DEFAULT 0,
  `id_pet` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `veterinarios`
--

CREATE TABLE `veterinarios` (
  `id_veterinario` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `nome_clinica` varchar(150) DEFAULT NULL,
  `tempo_experiencia` varchar(50) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `cep_clinica` varchar(10) DEFAULT NULL,
  `bairro_clinica` varchar(100) DEFAULT NULL,
  `numero_clinica` varchar(20) DEFAULT NULL,
  `cpf` varchar(15) DEFAULT NULL,
  `crmv` varchar(30) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `veterinarios`
--

INSERT INTO `veterinarios` (`id_veterinario`, `nome`, `nome_clinica`, `tempo_experiencia`, `telefone`, `email`, `endereco`, `cep_clinica`, `bairro_clinica`, `numero_clinica`, `cpf`, `crmv`, `user_id`) VALUES
(81, 'Dr. Vet Dois', 'Clinica Dois', '4 anos', '(16) 90000-0002', '2@2', 'Av Vet 2, 2', '15900-100', 'Centro', '2', '111.111.111-11', 'CRMV-SP-0002', 115),
(82, 'Dr. Vet Tres', 'Clinica Tres', '6 anos', '(16) 90000-0003', '3@3', 'Av Vet 3, 3', '15900-200', 'Jardim', '3', '222.222.222-22', 'CRMV-SP-0003', 116);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `medicamentos`
--
ALTER TABLE `medicamentos`
  ADD PRIMARY KEY (`id_medicamento`),
  ADD KEY `id_pet` (`id_pet`);

--
-- Índices de tabela `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`id_pet`),
  ADD KEY `idx_id_veterinario` (`id_veterinario`),
  ADD KEY `fk_usuario_pet` (`id_usuario`);

--
-- Índices de tabela `prontuario`
--
ALTER TABLE `prontuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pet` (`id_pet`),
  ADD KEY `id_veterinario` (`id_veterinario`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `vacinas`
--
ALTER TABLE `vacinas`
  ADD PRIMARY KEY (`id_vacina`),
  ADD KEY `id_pet` (`id_pet`);

--
-- Índices de tabela `veterinarios`
--
ALTER TABLE `veterinarios`
  ADD PRIMARY KEY (`id_veterinario`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `medicamentos`
--
ALTER TABLE `medicamentos`
  MODIFY `id_medicamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de tabela `pets`
--
ALTER TABLE `pets`
  MODIFY `id_pet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT de tabela `prontuario`
--
ALTER TABLE `prontuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT de tabela `vacinas`
--
ALTER TABLE `vacinas`
  MODIFY `id_vacina` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de tabela `veterinarios`
--
ALTER TABLE `veterinarios`
  MODIFY `id_veterinario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `medicamentos`
--
ALTER TABLE `medicamentos`
  ADD CONSTRAINT `medicamentos_ibfk_1` FOREIGN KEY (`id_pet`) REFERENCES `pets` (`id_pet`) ON DELETE CASCADE;

--
-- Restrições para tabelas `pets`
--
ALTER TABLE `pets`
  ADD CONSTRAINT `fk_usuario_pet` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_veterinario` FOREIGN KEY (`id_veterinario`) REFERENCES `veterinarios` (`id_veterinario`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Restrições para tabelas `prontuario`
--
ALTER TABLE `prontuario`
  ADD CONSTRAINT `prontuario_ibfk_1` FOREIGN KEY (`id_pet`) REFERENCES `pets` (`id_pet`) ON DELETE CASCADE,
  ADD CONSTRAINT `prontuario_ibfk_2` FOREIGN KEY (`id_veterinario`) REFERENCES `veterinarios` (`id_veterinario`);

--
-- Restrições para tabelas `vacinas`
--
ALTER TABLE `vacinas`
  ADD CONSTRAINT `vacinas_ibfk_1` FOREIGN KEY (`id_pet`) REFERENCES `pets` (`id_pet`) ON DELETE CASCADE;

--
-- Restrições para tabelas `veterinarios`
--
ALTER TABLE `veterinarios`
  ADD CONSTRAINT `veterinarios_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

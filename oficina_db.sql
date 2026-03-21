-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 21/03/2026 às 17:36
-- Versão do servidor: 10.11.14-MariaDB-0ubuntu0.24.04.1
-- Versão do PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `oficina_db`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `agendamentos`
--

CREATE TABLE `agendamentos` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `veiculo_id` int(11) DEFAULT NULL,
  `data_agendamento` datetime NOT NULL,
  `servico_solicitado` text NOT NULL,
  `status` enum('Agendado','Confirmado','Cancelado','Concluido') DEFAULT 'Agendado',
  `observacoes` text DEFAULT NULL,
  `os_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT current_timestamp(),
  `empresa_id` int(11) NOT NULL,
  `orcamento_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `caixa`
--

CREATE TABLE `caixa` (
  `id` int(11) NOT NULL,
  `tipo` enum('Entrada','Saída') NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `forma_pagamento` enum('Dinheiro','Cartão Débito','Cartão Crédito','PIX','Transferência','Cheque') NOT NULL,
  `os_id` int(11) DEFAULT NULL,
  `compra_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `data_movimentacao` date NOT NULL,
  `data_cadastro` timestamp NULL DEFAULT current_timestamp(),
  `empresa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `caixa`
--

INSERT INTO `caixa` (`id`, `tipo`, `categoria`, `descricao`, `valor`, `forma_pagamento`, `os_id`, `compra_id`, `usuario_id`, `data_movimentacao`, `data_cadastro`, `empresa_id`) VALUES
(12, 'Entrada', 'Servicos', 'OS $ 13 - Tercio Rodrigues', 185.50, 'PIX', NULL, NULL, 1, '2026-01-26', '2026-01-26 07:17:28', 1),
(13, 'Saída', 'Compra de Produtos', 'Compra NF: 0000000000000', 150.00, 'PIX', NULL, NULL, NULL, '2026-01-26', '2026-01-26 16:29:55', 1),
(14, 'Saída', 'Compra de Produtos', 'Compra NF: ', 100.00, 'PIX', NULL, 4, NULL, '2026-01-26', '2026-01-26 19:38:03', 1),
(15, 'Saída', 'Compra de Produtos', 'Compra NF: ', 380.00, 'Cartão Débito', NULL, 5, NULL, '2026-01-26', '2026-01-26 19:49:34', 1),
(16, 'Saída', 'Compra de Produtos', 'Compra NF: ', 55.00, 'Dinheiro', NULL, 6, NULL, '2026-01-26', '2026-01-26 20:02:25', 1),
(17, 'Saída', 'Compra de Produtos', 'Compra NF: ', 715.00, 'Dinheiro', NULL, 7, NULL, '2026-01-26', '2026-01-26 20:35:30', 1),
(18, 'Entrada', 'Venda de Produtos', 'Oleos', 120.00, 'PIX', NULL, NULL, NULL, '2026-01-26', '2026-01-26 23:42:21', 1),
(20, 'Entrada', 'Servicos', 'OS $ 15 - Tercio Rodrigues', 325.50, 'PIX', NULL, NULL, 1, '2026-01-27', '2026-01-27 19:58:29', 1),
(21, 'Entrada', 'Servicos', 'OS $ 14 - Tercio Rodrigues', 325.50, 'PIX', NULL, NULL, 1, '2026-01-27', '2026-01-27 19:58:56', 1),
(22, 'Entrada', 'Servicos', 'OS $ 16 - Tercio Rodrigues', 325.50, 'PIX', NULL, NULL, 1, '2026-01-27', '2026-01-27 21:00:21', 1),
(23, 'Entrada', 'Servicos', 'OS $ 18 - Tercio Rodrigues Feitosa', 185.50, 'PIX', NULL, NULL, 1, '2026-01-28', '2026-01-28 23:16:00', 1),
(24, 'Entrada', 'Servicos', 'OS $ 19 - Tercio Rodrigues Feitosa', 545.50, 'PIX', NULL, NULL, 1, '2026-01-28', '2026-01-28 23:39:26', 1),
(25, 'Entrada', 'Servicos', 'OS $ 23 - Tercio Rodrigues Feitosa', 545.50, 'PIX', NULL, NULL, 1, '2026-01-28', '2026-01-29 00:09:53', 1),
(26, 'Entrada', 'Servicos', 'OS $ 26 - Tercio Rodrigues', 250.50, 'PIX', 26, NULL, 1, '2026-01-29', '2026-01-29 20:33:12', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `empresa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categorias`
--

INSERT INTO `categorias` (`id`, `nome`, `empresa_id`) VALUES
(2, 'Peças', 1),
(3, 'Fluidos', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `endereco` text DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT current_timestamp(),
  `empresa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`id`, `nome`, `cpf`, `telefone`, `email`, `endereco`, `data_cadastro`, `empresa_id`) VALUES
(12, 'Tercio Rodrigues', '09261645461', '09261645461', '', '', '2026-01-29 18:50:59', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `fornecedor_id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `numero_nf` varchar(50) DEFAULT NULL,
  `data_compra` date NOT NULL,
  `valor_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `observacoes` text DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT current_timestamp(),
  `empresa_id` int(11) NOT NULL,
  `status` enum('Concluido','Pendente','Cancelado') NOT NULL DEFAULT 'Pendente',
  `forma_pagamento` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `compras`
--

INSERT INTO `compras` (`id`, `fornecedor_id`, `usuario_id`, `numero_nf`, `data_compra`, `valor_total`, `observacoes`, `data_cadastro`, `empresa_id`, `status`, `forma_pagamento`) VALUES
(4, 4, 1, '', '2026-01-26', 100.00, '', '2026-01-26 18:20:28', 1, 'Concluido', 'PIX'),
(5, 4, 1, '', '2026-01-26', 380.00, '', '2026-01-26 19:44:36', 1, 'Pendente', 'Cartão Débito'),
(6, 4, 1, '', '2026-01-26', 55.00, 'Filtro de oleo', '2026-01-26 19:56:19', 1, 'Pendente', 'Dinheiro'),
(7, 4, 1, '123123456', '2026-01-26', 715.00, 'Compra de oleo e filtro de oleo', '2026-01-26 20:34:18', 1, 'Concluido', 'Dinheiro');

-- --------------------------------------------------------

--
-- Estrutura para tabela `compras_itens`
--

CREATE TABLE `compras_itens` (
  `id` int(11) NOT NULL,
  `compra_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `valor_unitario` decimal(10,2) NOT NULL,
  `valor_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `compras_itens`
--

INSERT INTO `compras_itens` (`id`, `compra_id`, `produto_id`, `quantidade`, `valor_unitario`, `valor_total`) VALUES
(6, 4, 11, 4, 20.00, 80.00),
(7, 4, 10, 2, 10.00, 20.00),
(8, 5, 10, 8, 35.00, 280.00),
(10, 6, 10, 1, 35.00, 35.00),
(11, 6, 11, 1, 20.00, 20.00),
(12, 7, 10, 11, 30.00, 330.00),
(13, 7, 11, 11, 35.00, 385.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresas`
--

CREATE TABLE `empresas` (
  `id` int(11) NOT NULL,
  `razao_social` varchar(255) NOT NULL,
  `nome_fantasia` varchar(255) NOT NULL,
  `cnpj` varchar(18) NOT NULL,
  `inscricao_estadual` varchar(50) DEFAULT NULL,
  `telefone` varchar(20) NOT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `site` varchar(150) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `horario_funcionamento` text DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `produtos_garantia` int(4) NOT NULL DEFAULT 90,
  `data_atualizacao` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `empresas`
--

INSERT INTO `empresas` (`id`, `razao_social`, `nome_fantasia`, `cnpj`, `inscricao_estadual`, `telefone`, `whatsapp`, `email`, `site`, `cep`, `endereco`, `numero`, `complemento`, `bairro`, `cidade`, `estado`, `logo_url`, `horario_funcionamento`, `observacoes`, `produtos_garantia`, `data_atualizacao`) VALUES
(1, 'Galego Mecânica LTDA', 'Gustavo Carro', '12.345.678/0001-90', '', '(81) 3456-7890', '81987441089', 'contato@oficinasilva.com.br', '', '55191700', 'Rua Júlio Ferreira de Araújo - Júlio de Biringuer', '71', '(Lot Leonor Araújo)', 'Santo Agostinho', 'Santa Cruz do Capibaribe', 'PE', NULL, '', '', 90, '2026-01-27 18:37:45');

-- --------------------------------------------------------

--
-- Estrutura para tabela `estoque`
--

CREATE TABLE `estoque` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `categoria` int(11) DEFAULT NULL,
  `quantidade` int(11) DEFAULT 0,
  `preco_custo` decimal(10,2) NOT NULL,
  `preco_venda` decimal(10,2) NOT NULL,
  `estoque_minimo` int(11) DEFAULT NULL,
  `localizacao` varchar(100) DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT current_timestamp(),
  `empresa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `estoque`
--

INSERT INTO `estoque` (`id`, `codigo`, `descricao`, `categoria`, `quantidade`, `preco_custo`, `preco_venda`, `estoque_minimo`, `localizacao`, `data_cadastro`, `empresa_id`) VALUES
(10, 'PEC001', 'Filtro de Óleo', 2, 4, 35.00, 65.50, 10, NULL, '2026-01-26 06:16:19', 1),
(11, 'FLU001', 'Óleo sintetico 1L', 3, -1, 20.00, 35.00, 5, NULL, '2026-01-26 18:58:36', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedores`
--

CREATE TABLE `fornecedores` (
  `id` int(11) NOT NULL,
  `nome_empresa` varchar(200) NOT NULL,
  `cnpj` varchar(18) NOT NULL,
  `contato` varchar(150) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `endereco` text DEFAULT NULL,
  `produtos_fornecidos` text DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT current_timestamp(),
  `empresa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `fornecedores`
--

INSERT INTO `fornecedores` (`id`, `nome_empresa`, `cnpj`, `contato`, `telefone`, `email`, `endereco`, `produtos_fornecidos`, `data_cadastro`, `empresa_id`) VALUES
(4, 'Sinesc', '58.777.620/0001-11', 'Tércio', '81987441089', '', '', '', '2026-01-26 16:29:02', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionarios`
--

CREATE TABLE `funcionarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `rg` varchar(20) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `telefone` varchar(20) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `cargo` enum('Mecânico','Auxiliar','Gerente','Recepcionista','Outro') NOT NULL,
  `especialidade` varchar(255) DEFAULT NULL,
  `salario` decimal(10,2) DEFAULT NULL,
  `data_admissao` date NOT NULL,
  `data_demissao` date DEFAULT NULL,
  `status` enum('Ativo','Inativo','Férias','Afastado') DEFAULT 'Ativo',
  `endereco` text DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `foto_url` varchar(255) DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT current_timestamp(),
  `empresa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `garantias`
--

CREATE TABLE `garantias` (
  `id` int(11) NOT NULL,
  `os_id` int(11) NOT NULL,
  `tipo` enum('Peça','Serviço') NOT NULL,
  `item_id` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `status` enum('Ativa','Utilizada','Expirada') DEFAULT 'Ativa',
  `observacoes` text DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT current_timestamp(),
  `empresa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `garantias`
--

INSERT INTO `garantias` (`id`, `os_id`, `tipo`, `item_id`, `descricao`, `data_inicio`, `data_fim`, `status`, `observacoes`, `data_cadastro`, `empresa_id`) VALUES
(15, 26, 'Peça', 10, 'Filtro de Óleo', '2026-01-29', '2026-04-29', 'Ativa', NULL, '2026-01-29 20:33:12', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `orcamentos`
--

CREATE TABLE `orcamentos` (
  `id` int(11) NOT NULL,
  `cliente_nome` varchar(200) NOT NULL,
  `cliente_telefone` varchar(20) NOT NULL,
  `cliente_email` varchar(150) DEFAULT NULL,
  `veiculo_marca` varchar(50) NOT NULL,
  `veiculo_modelo` varchar(100) NOT NULL,
  `veiculo_ano` varchar(4) NOT NULL,
  `veiculo_placa` varchar(10) DEFAULT NULL,
  `descricao_servico` text NOT NULL,
  `observacoes` text DEFAULT NULL,
  `valor_pecas` decimal(10,2) DEFAULT 0.00,
  `valor_servicos` decimal(10,2) DEFAULT 0.00,
  `valor_total` decimal(10,2) DEFAULT 0.00,
  `desconto` decimal(10,2) DEFAULT 0.00,
  `validade_dias` int(11) DEFAULT 7,
  `status` enum('Pendente','Aprovado','Expirado') DEFAULT 'Pendente',
  `usuario_id` int(11) DEFAULT NULL,
  `data_orcamento` date NOT NULL,
  `data_validade` date NOT NULL,
  `convertido_os_id` int(11) DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT current_timestamp(),
  `empresa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `orcamentos`
--

INSERT INTO `orcamentos` (`id`, `cliente_nome`, `cliente_telefone`, `cliente_email`, `veiculo_marca`, `veiculo_modelo`, `veiculo_ano`, `veiculo_placa`, `descricao_servico`, `observacoes`, `valor_pecas`, `valor_servicos`, `valor_total`, `desconto`, `validade_dias`, `status`, `usuario_id`, `data_orcamento`, `data_validade`, `convertido_os_id`, `data_cadastro`, `empresa_id`) VALUES
(8, 'Tercio', '81987441089', '', 'Volkswagen', 'gol 1.6', '2010', 'KHM8E10', 'Troca de filtro e oleo', '', 65.50, 480.00, 545.50, 0.00, 7, 'Aprovado', 1, '2026-01-28', '2026-02-04', NULL, '2026-01-28 23:37:30', 1),
(9, 'tercio', '81987441089', '', 'Volkswagen', 'gol 1.6', '2010', 'KHM8E10', 'troca de oleo', '', 65.50, 480.00, 545.50, 0.00, 7, 'Aprovado', 1, '2026-01-28', '2026-02-04', NULL, '2026-01-28 23:42:25', 1),
(10, 'Teste', '81987441089', '', 'Volkswagen', 'gol 1.6', '2010', 'KHM8E10', 'Teste', '', 65.50, 480.00, 545.50, 0.00, 7, 'Aprovado', 1, '2026-01-28', '2026-02-04', NULL, '2026-01-29 00:03:52', 1),
(11, 'Teste', '81987441089', '', 'Volkswagen', 'gol 1.6', '2010', '', 'Teste', '', 65.50, 480.00, 545.50, 0.00, 7, 'Aprovado', 1, '2026-01-28', '2026-02-04', NULL, '2026-01-29 00:07:53', 1),
(12, 'Tercio Rodrigues', '09261645461', '', 'Volkswagen', 'gol 1.6', '2010', 'KHM8E10', 'Troca de óleo', '', 65.50, 120.00, 185.50, 0.00, 7, 'Aprovado', 1, '2026-01-29', '2026-02-05', NULL, '2026-01-29 18:49:51', 1),
(13, 'teste', '8198877444', '', 'Volkswagen', 'gol 1.6', '2010', '', 'Teste', '', 65.50, 185.00, 250.50, 0.00, 7, 'Aprovado', 1, '2026-01-29', '2026-02-05', NULL, '2026-01-29 18:56:33', 1),
(14, 'teste', '81987441089', '', 'Volkswagen', 'gol 1.6', '2010', '', 'Teste', '', 65.50, 120.00, 185.50, 0.00, 7, 'Pendente', 1, '2026-01-29', '2026-02-05', NULL, '2026-01-29 20:36:32', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `orcamento_itens_produtos`
--

CREATE TABLE `orcamento_itens_produtos` (
  `id` int(11) NOT NULL,
  `orcamento_id` int(11) NOT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `descricao` varchar(255) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `valor_unitario` decimal(10,2) NOT NULL,
  `valor_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `orcamento_itens_produtos`
--

INSERT INTO `orcamento_itens_produtos` (`id`, `orcamento_id`, `produto_id`, `descricao`, `quantidade`, `valor_unitario`, `valor_total`) VALUES
(17, 8, 10, 'Filtro de Óleo', 1, 65.50, 65.50),
(18, 9, 10, 'Filtro de Óleo', 1, 65.50, 65.50),
(19, 10, 10, 'Filtro de Óleo', 1, 65.50, 65.50),
(20, 11, 10, 'Filtro de Óleo', 1, 65.50, 65.50),
(21, 12, 10, 'Filtro de Óleo', 1, 65.50, 65.50),
(22, 13, 10, 'Filtro de Óleo', 1, 65.50, 65.50),
(23, 14, 10, 'Filtro de Óleo', 1, 65.50, 65.50);

-- --------------------------------------------------------

--
-- Estrutura para tabela `orcamento_itens_servicos`
--

CREATE TABLE `orcamento_itens_servicos` (
  `id` int(11) NOT NULL,
  `orcamento_id` int(11) NOT NULL,
  `servico_id` int(11) DEFAULT NULL,
  `descricao` varchar(255) NOT NULL,
  `quantidade` int(11) DEFAULT 1,
  `valor_unitario` decimal(10,2) NOT NULL,
  `valor_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `orcamento_itens_servicos`
--

INSERT INTO `orcamento_itens_servicos` (`id`, `orcamento_id`, `servico_id`, `descricao`, `quantidade`, `valor_unitario`, `valor_total`) VALUES
(12, 8, 7, 'Torca de Óleo', 4, 120.00, 480.00),
(13, 9, 7, 'Torca de Óleo', 4, 120.00, 480.00),
(14, 10, 7, 'Torca de Óleo', 4, 120.00, 480.00),
(15, 11, 7, 'Torca de Óleo', 4, 120.00, 480.00),
(16, 12, 7, 'Torca de Óleo', 1, 120.00, 120.00),
(17, 13, 8, 'mao de obra', 1, 65.00, 65.00),
(18, 13, 7, 'Torca de Óleo', 1, 120.00, 120.00),
(19, 14, 7, 'Torca de Óleo', 1, 120.00, 120.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `ordens_servico`
--

CREATE TABLE `ordens_servico` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `veiculo_id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `mecanico_id` int(11) DEFAULT NULL,
  `data_abertura` date NOT NULL,
  `data_fechamento` date DEFAULT NULL,
  `previsao_entrega` datetime DEFAULT NULL,
  `status` enum('Aberta','Em_Andamento','Aguardando_Pecas','Aguardando_Aprovacao','Concluido','Cancelado') DEFAULT 'Aberta',
  `descricao_problema` text NOT NULL,
  `diagnostico` text DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `km_veiculo` int(11) DEFAULT NULL,
  `valor_pecas` decimal(10,2) DEFAULT 0.00,
  `valor_servicos` decimal(10,2) DEFAULT 0.00,
  `valor_total` decimal(10,2) DEFAULT 0.00,
  `desconto` decimal(10,2) DEFAULT 0.00,
  `forma_pagamento` varchar(50) DEFAULT NULL,
  `aprovado_cliente` tinyint(1) DEFAULT 0,
  `data_cadastro` timestamp NULL DEFAULT current_timestamp(),
  `empresa_id` int(11) NOT NULL,
  `pago` enum('Sim','Nao') NOT NULL DEFAULT 'Nao'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `ordens_servico`
--

INSERT INTO `ordens_servico` (`id`, `cliente_id`, `veiculo_id`, `usuario_id`, `mecanico_id`, `data_abertura`, `data_fechamento`, `previsao_entrega`, `status`, `descricao_problema`, `diagnostico`, `observacoes`, `km_veiculo`, `valor_pecas`, `valor_servicos`, `valor_total`, `desconto`, `forma_pagamento`, `aprovado_cliente`, `data_cadastro`, `empresa_id`, `pago`) VALUES
(26, 12, 17, 1, NULL, '2026-01-29', '2026-01-29', NULL, 'Concluido', 'Teste', NULL, '', NULL, 65.50, 185.00, 250.50, 0.00, 'PIX', 0, '2026-01-29 20:21:26', 1, 'Sim');

-- --------------------------------------------------------

--
-- Estrutura para tabela `os_itens_produtos`
--

CREATE TABLE `os_itens_produtos` (
  `id` int(11) NOT NULL,
  `os_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `valor_unitario` decimal(10,2) NOT NULL,
  `valor_total` decimal(10,2) NOT NULL,
  `garantia_ate` date DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `os_itens_produtos`
--

INSERT INTO `os_itens_produtos` (`id`, `os_id`, `produto_id`, `quantidade`, `valor_unitario`, `valor_total`, `garantia_ate`, `data_cadastro`) VALUES
(33, 26, 10, 1, 65.50, 65.50, NULL, '2026-01-29 20:21:26');

-- --------------------------------------------------------

--
-- Estrutura para tabela `os_itens_servicos`
--

CREATE TABLE `os_itens_servicos` (
  `id` int(11) NOT NULL,
  `os_id` int(11) NOT NULL,
  `servico_id` int(11) NOT NULL,
  `quantidade` int(11) DEFAULT 1,
  `valor_unitario` decimal(10,2) NOT NULL,
  `valor_total` decimal(10,2) NOT NULL,
  `garantia_ate` date DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `os_itens_servicos`
--

INSERT INTO `os_itens_servicos` (`id`, `os_id`, `servico_id`, `quantidade`, `valor_unitario`, `valor_total`, `garantia_ate`, `data_cadastro`) VALUES
(30, 26, 8, 1, 65.00, 65.00, NULL, '2026-01-29 20:21:26'),
(31, 26, 7, 1, 120.00, 120.00, NULL, '2026-01-29 20:21:26');

-- --------------------------------------------------------

--
-- Estrutura para tabela `servicos`
--

CREATE TABLE `servicos` (
  `id` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `descricao` text DEFAULT NULL,
  `valor` decimal(10,2) NOT NULL,
  `tempo_estimado` varchar(50) DEFAULT NULL,
  `garantia_dias` int(11) DEFAULT 90,
  `data_cadastro` timestamp NULL DEFAULT current_timestamp(),
  `empresa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `servicos`
--

INSERT INTO `servicos` (`id`, `nome`, `descricao`, `valor`, `tempo_estimado`, `garantia_dias`, `data_cadastro`, `empresa_id`) VALUES
(7, 'Torca de Óleo', 'Troca de Óleo', 120.00, '2 horas', 90, '2026-01-26 02:24:08', 1),
(8, 'mao de obra', ' troca de oleo', 65.00, '3', 90, '2026-01-27 18:48:13', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `foto_url` varchar(255) DEFAULT NULL,
  `senha` varchar(255) NOT NULL,
  `nivel` enum('Admin','Gerente','Atendente') DEFAULT 'Atendente',
  `ativo` tinyint(1) DEFAULT 1,
  `ultimo_acesso` timestamp NULL DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT current_timestamp(),
  `empresa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `telefone`, `foto_url`, `senha`, `nivel`, `ativo`, `ultimo_acesso`, `data_cadastro`, `empresa_id`) VALUES
(1, 'Administrador', 'admin@oficina.com', '', NULL, '$2y$10$h4a7gTnR36kDvvxZT1JcOOvQoog72I88xBVNcgSiW0URuUDALAEHu', 'Admin', 1, '2026-03-03 01:16:32', '2026-01-20 05:10:29', 1),
(2, 'Gerente Oficina', 'gerente@oficina.com', NULL, NULL, '$2y$10$Eej95PZVQUez8wFOcJMFteuhcUZ8gWZ99g5HPWaFsOCcn3giDXOtq', 'Gerente', 1, NULL, '2026-01-20 05:10:29', 1),
(3, 'Atendente', 'atendente@oficina.com', NULL, NULL, '$2y$10$Eej95PZVQUez8wFOcJMFteuhcUZ8gWZ99g5HPWaFsOCcn3giDXOtq', 'Atendente', 1, NULL, '2026-01-20 05:10:29', 1),
(4, 'tarcio', 'tarcio@oficina.com', '', NULL, '$2y$10$s5CZbOtNoNOuelsxvQXch.YwCXwuDsDZPm6TwbC6ZzcR19Wozvm6u', 'Atendente', 1, '2026-01-27 18:38:52', '2026-01-27 14:39:27', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `veiculos`
--

CREATE TABLE `veiculos` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `placa` varchar(10) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(100) NOT NULL,
  `ano` varchar(4) NOT NULL,
  `cor` varchar(30) DEFAULT NULL,
  `chassi` varchar(50) DEFAULT NULL,
  `km_atual` int(11) DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT current_timestamp(),
  `empresa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `veiculos`
--

INSERT INTO `veiculos` (`id`, `cliente_id`, `placa`, `marca`, `modelo`, `ano`, `cor`, `chassi`, `km_atual`, `data_cadastro`, `empresa_id`) VALUES
(17, 12, 'KHM8E10', 'Volkswagen', 'gol 1.6', '2010', NULL, NULL, 420340, '2026-01-29 18:50:59', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `whatsapp_log`
--

CREATE TABLE `whatsapp_log` (
  `id` int(11) NOT NULL,
  `destinatario` varchar(20) NOT NULL,
  `mensagem` text NOT NULL,
  `tipo` enum('OS Criada','OS Concluída','Agendamento','Aprovação','Lembrete') NOT NULL,
  `os_id` int(11) DEFAULT NULL,
  `agendamento_id` int(11) DEFAULT NULL,
  `status` enum('Pendente','Enviado','Erro') DEFAULT 'Pendente',
  `data_envio` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_data` (`data_agendamento`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `agendamentos_ibfk_1` (`cliente_id`),
  ADD KEY `agendamentos_ibfk_2` (`veiculo_id`),
  ADD KEY `agendamentos_ibfk_3` (`os_id`),
  ADD KEY `agendamentos_ibfk_4` (`usuario_id`);

--
-- Índices de tabela `caixa`
--
ALTER TABLE `caixa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `os_id` (`os_id`),
  ADD KEY `compra_id` (`compra_id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `idx_tipo` (`tipo`),
  ADD KEY `idx_data` (`data_movimentacao`);

--
-- Índices de tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_nome` (`nome`),
  ADD KEY `idx_cpf` (`cpf`),
  ADD KEY `idx_telefone` (`telefone`);

--
-- Índices de tabela `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fornecedor_id` (`fornecedor_id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `idx_data` (`data_compra`);

--
-- Índices de tabela `compras_itens`
--
ALTER TABLE `compras_itens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produto_id` (`produto_id`),
  ADD KEY `idx_compra` (`compra_id`);

--
-- Índices de tabela `estoque`
--
ALTER TABLE `estoque`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `idx_codigo` (`codigo`) USING BTREE,
  ADD KEY `estoque_ibfk_1` (`categoria`);

--
-- Índices de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_nome_empresa` (`nome_empresa`),
  ADD KEY `idx_cnpj` (`cnpj`);

--
-- Índices de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_cargo` (`cargo`);

--
-- Índices de tabela `garantias`
--
ALTER TABLE `garantias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `os_id` (`os_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_data_fim` (`data_fim`);

--
-- Índices de tabela `orcamentos`
--
ALTER TABLE `orcamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `convertido_os_id` (`convertido_os_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_data_orcamento` (`data_orcamento`);

--
-- Índices de tabela `orcamento_itens_produtos`
--
ALTER TABLE `orcamento_itens_produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produto_id` (`produto_id`),
  ADD KEY `idx_orcamento` (`orcamento_id`);

--
-- Índices de tabela `orcamento_itens_servicos`
--
ALTER TABLE `orcamento_itens_servicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `servico_id` (`servico_id`),
  ADD KEY `idx_orcamento` (`orcamento_id`);

--
-- Índices de tabela `ordens_servico`
--
ALTER TABLE `ordens_servico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_data_abertura` (`data_abertura`),
  ADD KEY `idx_cliente` (`cliente_id`),
  ADD KEY `idx_mecanico` (`mecanico_id`),
  ADD KEY `ordens_servico_ibfk_2` (`veiculo_id`);

--
-- Índices de tabela `os_itens_produtos`
--
ALTER TABLE `os_itens_produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produto_id` (`produto_id`),
  ADD KEY `idx_os` (`os_id`);

--
-- Índices de tabela `os_itens_servicos`
--
ALTER TABLE `os_itens_servicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `servico_id` (`servico_id`),
  ADD KEY `idx_os` (`os_id`);

--
-- Índices de tabela `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_nome` (`nome`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- Índices de tabela `veiculos`
--
ALTER TABLE `veiculos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_placa` (`placa`) USING BTREE,
  ADD KEY `idx_cliente` (`cliente_id`);

--
-- Índices de tabela `whatsapp_log`
--
ALTER TABLE `whatsapp_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `os_id` (`os_id`),
  ADD KEY `agendamento_id` (`agendamento_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_tipo` (`tipo`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `caixa`
--
ALTER TABLE `caixa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `compras_itens`
--
ALTER TABLE `compras_itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `estoque`
--
ALTER TABLE `estoque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `garantias`
--
ALTER TABLE `garantias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `orcamentos`
--
ALTER TABLE `orcamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `orcamento_itens_produtos`
--
ALTER TABLE `orcamento_itens_produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de tabela `orcamento_itens_servicos`
--
ALTER TABLE `orcamento_itens_servicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `ordens_servico`
--
ALTER TABLE `ordens_servico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de tabela `os_itens_produtos`
--
ALTER TABLE `os_itens_produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de tabela `os_itens_servicos`
--
ALTER TABLE `os_itens_servicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `veiculos`
--
ALTER TABLE `veiculos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `whatsapp_log`
--
ALTER TABLE `whatsapp_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD CONSTRAINT `agendamentos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `agendamentos_ibfk_2` FOREIGN KEY (`veiculo_id`) REFERENCES `veiculos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `agendamentos_ibfk_3` FOREIGN KEY (`os_id`) REFERENCES `ordens_servico` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `agendamentos_ibfk_4` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `caixa`
--
ALTER TABLE `caixa`
  ADD CONSTRAINT `caixa_ibfk_1` FOREIGN KEY (`os_id`) REFERENCES `ordens_servico` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `caixa_ibfk_2` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `caixa_ibfk_3` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedores` (`id`),
  ADD CONSTRAINT `compras_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `compras_itens`
--
ALTER TABLE `compras_itens`
  ADD CONSTRAINT `compras_itens_ibfk_1` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`),
  ADD CONSTRAINT `compras_itens_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `estoque` (`id`);

--
-- Restrições para tabelas `estoque`
--
ALTER TABLE `estoque`
  ADD CONSTRAINT `estoque_ibfk_1` FOREIGN KEY (`categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `garantias`
--
ALTER TABLE `garantias`
  ADD CONSTRAINT `garantias_ibfk_1` FOREIGN KEY (`os_id`) REFERENCES `ordens_servico` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `orcamentos`
--
ALTER TABLE `orcamentos`
  ADD CONSTRAINT `orcamentos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orcamentos_ibfk_2` FOREIGN KEY (`convertido_os_id`) REFERENCES `ordens_servico` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `orcamento_itens_produtos`
--
ALTER TABLE `orcamento_itens_produtos`
  ADD CONSTRAINT `orcamento_itens_produtos_ibfk_1` FOREIGN KEY (`orcamento_id`) REFERENCES `orcamentos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orcamento_itens_produtos_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `estoque` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `orcamento_itens_servicos`
--
ALTER TABLE `orcamento_itens_servicos`
  ADD CONSTRAINT `orcamento_itens_servicos_ibfk_1` FOREIGN KEY (`orcamento_id`) REFERENCES `orcamentos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orcamento_itens_servicos_ibfk_2` FOREIGN KEY (`servico_id`) REFERENCES `servicos` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `ordens_servico`
--
ALTER TABLE `ordens_servico`
  ADD CONSTRAINT `ordens_servico_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `ordens_servico_ibfk_2` FOREIGN KEY (`veiculo_id`) REFERENCES `veiculos` (`id`),
  ADD CONSTRAINT `ordens_servico_ibfk_3` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ordens_servico_ibfk_4` FOREIGN KEY (`mecanico_id`) REFERENCES `funcionarios` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `os_itens_produtos`
--
ALTER TABLE `os_itens_produtos`
  ADD CONSTRAINT `os_itens_produtos_ibfk_1` FOREIGN KEY (`os_id`) REFERENCES `ordens_servico` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `os_itens_produtos_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `estoque` (`id`);

--
-- Restrições para tabelas `os_itens_servicos`
--
ALTER TABLE `os_itens_servicos`
  ADD CONSTRAINT `os_itens_servicos_ibfk_1` FOREIGN KEY (`os_id`) REFERENCES `ordens_servico` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `os_itens_servicos_ibfk_2` FOREIGN KEY (`servico_id`) REFERENCES `servicos` (`id`);

--
-- Restrições para tabelas `veiculos`
--
ALTER TABLE `veiculos`
  ADD CONSTRAINT `veiculos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);

--
-- Restrições para tabelas `whatsapp_log`
--
ALTER TABLE `whatsapp_log`
  ADD CONSTRAINT `whatsapp_log_ibfk_1` FOREIGN KEY (`os_id`) REFERENCES `ordens_servico` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `whatsapp_log_ibfk_2` FOREIGN KEY (`agendamento_id`) REFERENCES `agendamentos` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

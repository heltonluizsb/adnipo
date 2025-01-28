-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 27/05/2021 às 09:47
-- Versão do servidor: 5.6.41-84.1
-- Versão do PHP: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `helton51_adnipo`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_admin`
--

CREATE TABLE `tb_admin` (
  `id` int(11) NOT NULL,
  `user` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `tb_admin`
--

INSERT INTO `tb_admin` (`id`, `user`, `senha`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_categoria.empresa`
--

CREATE TABLE `tb_categoria.empresa` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `tb_categoria.empresa`
--

INSERT INTO `tb_categoria.empresa` (`id`, `nome`, `slug`) VALUES
(25, 'Desenvolvimento de Sites', 'desenvolvimento-de-sites'),
(26, 'Marketing Digital', 'marketing-digital'),
(27, 'Sites', 'sites');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_empresa`
--

CREATE TABLE `tb_empresa` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `telefone` varchar(255) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `id_criador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `tb_empresa`
--

INSERT INTO `tb_empresa` (`id`, `nome`, `slug`, `descricao`, `endereco`, `telefone`, `imagem`, `id_criador`) VALUES
(25, 'HB Sites e Marketing Digital', 'hb-sites-e-marketing-digital', 'Desenvolvemos e criamos um site exclusivo com uma estratégia visual para tornar o seu negócio facilmente acessível e respeitado por pessoas e empresas.', 'Rua Farroupilha', '(11) 93249-4045', '60ae86af3e5c2.jpg', 18);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_empresa.categoria`
--

CREATE TABLE `tb_empresa.categoria` (
  `id` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `tb_empresa.categoria`
--

INSERT INTO `tb_empresa.categoria` (`id`, `id_empresa`, `id_categoria`) VALUES
(33, 25, 25),
(34, 25, 26),
(35, 25, 27);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_empresa.permissao`
--

CREATE TABLE `tb_empresa.permissao` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `tb_empresa.permissao`
--

INSERT INTO `tb_empresa.permissao` (`id`, `nome`) VALUES
(1, 'Administrador'),
(2, 'Parceiro');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_empresa.solicitacao`
--

CREATE TABLE `tb_empresa.solicitacao` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_empresa.user`
--

CREATE TABLE `tb_empresa.user` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `permissao_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `tb_empresa.user`
--

INSERT INTO `tb_empresa.user` (`id`, `empresa_id`, `user_id`, `permissao_id`) VALUES
(34, 25, 18, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_filial`
--

CREATE TABLE `tb_filial` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `tb_filial`
--

INSERT INTO `tb_filial` (`id`, `nome`, `slug`) VALUES
(13, 'SEDE', 'sede');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_mensagem`
--

CREATE TABLE `tb_mensagem` (
  `id` int(11) NOT NULL,
  `remetente_id` int(11) NOT NULL,
  `destinatario_id` int(11) NOT NULL,
  `mensagem` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_post`
--

CREATE TABLE `tb_post` (
  `id` int(11) NOT NULL,
  `origem_tipo` int(11) NOT NULL,
  `origem_id` int(11) NOT NULL,
  `post` text NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `categoria_outra` varchar(255) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `resposta_id` int(11) NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `tb_post`
--

INSERT INTO `tb_post` (`id`, `origem_tipo`, `origem_id`, `post`, `categoria_id`, `categoria_outra`, `imagem`, `resposta_id`, `data`) VALUES
(50, 0, 18, 'Estou inaugurando o sistema ADNIPO for Business!\n\nFocado para que os membros da igreja encontrem especialistas nós serviços desejados dentro do nosso corpo!\n\nSe cadastre e comente qual serviço você gostaria de solicitar OU cadastre sua empresa / serviços para que os nossos irmãos te ache!\n\nDúvidas é só entrar em contato por aqui mesmo através do Messenger.', 0, '', '', 0, '2021-05-26 19:20:31'),
(51, 1, 25, 'Desenvolvemos e criamos um site exclusivo com uma estratégia visual para tornar o seu negócio facilmente acessível e respeitado por pessoas e empresas.', 0, '', '60ae9ff66872e.png', 0, '2021-05-26 19:22:30');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_user`
--

CREATE TABLE `tb_user` (
  `id` int(11) NOT NULL,
  `login` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `confirma_email` varchar(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `tipo_login` varchar(255) NOT NULL,
  `cpf_cnpj` varchar(255) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `carteirinha` varchar(255) NOT NULL,
  `filial_id` int(11) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `img_perfil` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `troca_senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `tb_user`
--

INSERT INTO `tb_user` (`id`, `login`, `email`, `confirma_email`, `nome`, `tipo_login`, `cpf_cnpj`, `endereco`, `carteirinha`, `filial_id`, `senha`, `img_perfil`, `descricao`, `troca_senha`) VALUES
(18, 'heltonluizsb', 'heltonluizsb@gmail.com', 'confirmado', 'Helton Barros', 'fisico', '309.336.458-21', 'Rua Farroupilha', '2746', 13, 'G@nondorf1', '60ae7fc8c1b1a.jpg', 'Desenvolvedor do sistema ADNIPO for Business. Dúvidas sobre o funcionamento e sugestões, basta me passar uma mensagem :-D', ''),
(19, 'Mark', 'mhspdesign@gmail.com', 'confirmado', 'Marcus Henrique Ribeiro Garcia', 'fisico', '327.752.568-64', 'Rua martiniano de Carvalho 880', 'Não lembro', 13, '1234567', '60aede2855bd9.jpg', 'Design Gráfico', ''),
(20, 'Melanina Rara', 'Melaninarara@gmail.com', '60aeaf70cebdc', 'Melanina Rara ', 'juridico', '37.092.407/0001-04', 'Rua Hollywood ', 'Não tem ', 13, 'esqueci20', '60aeb13a41545.png', 'Loja de Maquiagem para pele negra, Roupas estilo afro, Touca de Cetim, Difusora de Cetim, Bolsas, Pochetes e muito mais... venha nos conhecer\nwww.melaninarara.com\n@melanina_rara', '');

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tb_categoria.empresa`
--
ALTER TABLE `tb_categoria.empresa`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tb_empresa`
--
ALTER TABLE `tb_empresa`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tb_empresa.categoria`
--
ALTER TABLE `tb_empresa.categoria`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tb_empresa.permissao`
--
ALTER TABLE `tb_empresa.permissao`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tb_empresa.solicitacao`
--
ALTER TABLE `tb_empresa.solicitacao`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tb_empresa.user`
--
ALTER TABLE `tb_empresa.user`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tb_filial`
--
ALTER TABLE `tb_filial`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tb_mensagem`
--
ALTER TABLE `tb_mensagem`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tb_post`
--
ALTER TABLE `tb_post`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tb_categoria.empresa`
--
ALTER TABLE `tb_categoria.empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de tabela `tb_empresa`
--
ALTER TABLE `tb_empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `tb_empresa.categoria`
--
ALTER TABLE `tb_empresa.categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de tabela `tb_empresa.permissao`
--
ALTER TABLE `tb_empresa.permissao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `tb_empresa.solicitacao`
--
ALTER TABLE `tb_empresa.solicitacao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de tabela `tb_empresa.user`
--
ALTER TABLE `tb_empresa.user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de tabela `tb_filial`
--
ALTER TABLE `tb_filial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `tb_mensagem`
--
ALTER TABLE `tb_mensagem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de tabela `tb_post`
--
ALTER TABLE `tb_post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de tabela `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

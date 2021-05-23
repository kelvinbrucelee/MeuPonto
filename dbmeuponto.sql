-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 23-Maio-2021 às 01:34
-- Versão do servidor: 10.4.17-MariaDB
-- versão do PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `dbmeuponto`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `calendario_evento`
--

CREATE TABLE `calendario_evento` (
  `id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `color` varchar(45) NOT NULL,
  `title` varchar(45) NOT NULL,
  `description` text NOT NULL,
  `situacao` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `especial`
--

CREATE TABLE `especial` (
  `id` int(11) NOT NULL,
  `matricula` varchar(100) NOT NULL,
  `datapresenca` date DEFAULT NULL,
  `sessionid` text NOT NULL,
  `login_time` datetime NOT NULL,
  `atual_ip` varchar(45) NOT NULL,
  `hora` time NOT NULL,
  `system_user_id` int(11) NOT NULL,
  `anocurso` varchar(50) NOT NULL,
  `curso` varchar(50) NOT NULL,
  `system_unit_id` int(11) NOT NULL,
  `dataalteracao` timestamp NULL DEFAULT NULL,
  `usuarioalteracao` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `especial`
--

INSERT INTO `especial` (`id`, `matricula`, `datapresenca`, `sessionid`, `login_time`, `atual_ip`, `hora`, `system_user_id`, `anocurso`, `curso`, `system_unit_id`, `dataalteracao`, `usuarioalteracao`) VALUES
(1, '6789', '2021-05-22', 'rklr1a8h9n7ive0176qg387q5r', '2021-05-22 14:47:12', '::1', '14:47:12', 4, '2019', 'UNP', 2, NULL, NULL),
(2, '6789', '2021-05-22', 'foh11387op1pf4h7jrqjuvr302', '2021-05-22 20:05:51', '::1', '20:05:51', 4, '2019', 'SISTEMAS', 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `justificativa`
--

CREATE TABLE `justificativa` (
  `id` int(11) NOT NULL,
  `username` varchar(45) NOT NULL,
  `datajustificativa` date NOT NULL,
  `horaponto` time NOT NULL,
  `observacao` text NOT NULL,
  `situacao` varchar(45) NOT NULL,
  `dataponto` date NOT NULL,
  `tipojustificativa_id` int(11) NOT NULL,
  `horariojustificativa` time NOT NULL,
  `semana` varchar(50) NOT NULL,
  `system_user_id` int(11) NOT NULL,
  `system_user_unit_id` int(11) NOT NULL,
  `dataalteracao` timestamp NULL DEFAULT NULL,
  `usuarioalteracao` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ponto`
--

CREATE TABLE `ponto` (
  `id` int(11) NOT NULL,
  `username` varchar(45) NOT NULL,
  `semana` varchar(45) NOT NULL,
  `dataponto` date NOT NULL,
  `justificado` varchar(50) DEFAULT NULL,
  `horaponto` time NOT NULL,
  `situacao` int(11) NOT NULL,
  `computador_ip` varchar(100) NOT NULL,
  `ponto_ip` varchar(45) NOT NULL,
  `curso` varchar(50) NOT NULL,
  `system_user_id` int(11) NOT NULL,
  `system_user_unit_id` int(11) NOT NULL,
  `matricula` varchar(50) NOT NULL,
  `dataalteracao` timestamp NULL DEFAULT NULL,
  `usuarioalteracao` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `ponto`
--

INSERT INTO `ponto` (`id`, `username`, `semana`, `dataponto`, `justificado`, `horaponto`, `situacao`, `computador_ip`, `ponto_ip`, `curso`, `system_user_id`, `system_user_unit_id`, `matricula`, `dataalteracao`, `usuarioalteracao`) VALUES
(1, 'Desenvolvedor', 'SABADO', '2021-05-22', 'P', '20:07:58', 0, '::1', '::1', 'SISTEMAS', 1, 1, '123', NULL, NULL),
(2, 'Desenvolvedor', 'SABADO', '2021-05-22', 'P', '20:08:03', 1, '::1', '::1', 'SISTEMAS', 1, 1, '123', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `system_access_log`
--

CREATE TABLE `system_access_log` (
  `id` int(11) NOT NULL,
  `sessionid` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `login` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `logout_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `system_access_log`
--

INSERT INTO `system_access_log` (`id`, `sessionid`, `login`, `login_time`, `logout_time`) VALUES
(1, 'rklr1a8h9n7ive0176qg387q5r', '123', '2021-05-22 17:46:43', '2021-05-22 17:52:07'),
(2, 'rklr1a8h9n7ive0176qg387q5r', '123', '2021-05-22 17:47:25', '2021-05-22 17:47:25'),
(3, 'foh11387op1pf4h7jrqjuvr302', '123', '2021-05-22 23:07:33', '2021-05-22 23:32:49'),
(4, 'foh11387op1pf4h7jrqjuvr302', '123', '2021-05-22 23:18:22', '2021-05-22 23:18:22');

-- --------------------------------------------------------

--
-- Estrutura da tabela `system_document`
--

CREATE TABLE `system_document` (
  `id` int(11) NOT NULL,
  `system_user_id` int(11) NOT NULL,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(11) NOT NULL,
  `submission_date` date NOT NULL,
  `archive_date` date NOT NULL,
  `filename` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `system_document_category`
--

CREATE TABLE `system_document_category` (
  `id` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `system_document_category`
--

INSERT INTO `system_document_category` (`id`, `name`) VALUES
(1, 'Documentos');

-- --------------------------------------------------------

--
-- Estrutura da tabela `system_document_group`
--

CREATE TABLE `system_document_group` (
  `id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL,
  `system_group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `system_document_user`
--

CREATE TABLE `system_document_user` (
  `id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL,
  `system_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `system_group`
--

CREATE TABLE `system_group` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `system_group`
--

INSERT INTO `system_group` (`id`, `name`) VALUES
(1, 'Developer'),
(2, 'Administrador Bolsistas'),
(3, 'Bolsista'),
(4, 'Aluno Especial'),
(5, 'Administrador Especial');

-- --------------------------------------------------------

--
-- Estrutura da tabela `system_group_program`
--

CREATE TABLE `system_group_program` (
  `id` int(11) NOT NULL,
  `system_group_id` int(11) NOT NULL,
  `system_program_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `system_group_program`
--

INSERT INTO `system_group_program` (`id`, `system_group_id`, `system_program_id`) VALUES
(90, 3, 42),
(100, 1, 3),
(101, 1, 4),
(104, 1, 8),
(105, 1, 9),
(106, 1, 11),
(107, 1, 14),
(108, 1, 15),
(112, 1, 28),
(113, 1, 29),
(114, 1, 31),
(115, 1, 32),
(116, 1, 33),
(117, 1, 34),
(125, 1, 43),
(155, 1, 52),
(157, 1, 53),
(162, 1, 55),
(164, 3, 55),
(171, 1, 56),
(173, 1, 57),
(191, 1, 21),
(201, 1, 58),
(225, 1, 62),
(227, 1, 63),
(229, 1, 64),
(231, 1, 65),
(233, 3, 65),
(235, 1, 66),
(239, 1, 68),
(248, 1, 36),
(249, 3, 36),
(259, 2, 10),
(266, 2, 30),
(272, 2, 43),
(275, 2, 52),
(276, 2, 53),
(277, 2, 55),
(278, 2, 56),
(279, 2, 57),
(284, 2, 21),
(289, 2, 58),
(295, 2, 62),
(296, 2, 63),
(297, 2, 64),
(303, 4, 70),
(304, 1, 12),
(305, 2, 12),
(306, 3, 12),
(307, 4, 12),
(308, 5, 12),
(309, 1, 13),
(310, 2, 13),
(311, 3, 13),
(312, 4, 13),
(313, 5, 13),
(314, 1, 1),
(315, 1, 2),
(318, 1, 72),
(319, 2, 72),
(320, 5, 72),
(321, 1, 73),
(322, 2, 73),
(323, 5, 73),
(326, 1, 54),
(327, 2, 54),
(328, 3, 54),
(329, 4, 54),
(330, 5, 54),
(331, 1, 17),
(332, 2, 17),
(333, 3, 17),
(334, 4, 17),
(335, 5, 17),
(336, 1, 16),
(337, 2, 16),
(338, 3, 16),
(339, 4, 16),
(340, 5, 16),
(341, 1, 19),
(342, 2, 19),
(343, 3, 19),
(344, 4, 19),
(345, 5, 19),
(346, 1, 23),
(347, 2, 23),
(348, 3, 23),
(349, 4, 23),
(350, 5, 23),
(351, 1, 24),
(352, 2, 24),
(353, 3, 24),
(354, 4, 24),
(355, 5, 24),
(356, 1, 25),
(357, 2, 25),
(358, 3, 25),
(359, 4, 25),
(360, 5, 25),
(361, 1, 18),
(362, 2, 18),
(363, 3, 18),
(364, 4, 18),
(365, 5, 18),
(366, 1, 75),
(367, 5, 75),
(368, 1, 71),
(369, 5, 71),
(372, 1, 47),
(373, 3, 47),
(374, 1, 48),
(375, 3, 48),
(379, 1, 39),
(380, 3, 39),
(381, 1, 35),
(382, 3, 35),
(383, 1, 59),
(384, 2, 59),
(385, 1, 60),
(386, 2, 60),
(387, 1, 76),
(388, 2, 76),
(389, 1, 77),
(390, 2, 77),
(391, 1, 78),
(392, 2, 78),
(395, 1, 22),
(396, 2, 22),
(397, 3, 22),
(398, 4, 22),
(399, 5, 22),
(400, 1, 20),
(401, 2, 20),
(402, 5, 20),
(403, 1, 27),
(404, 2, 27),
(405, 5, 27),
(406, 1, 26),
(407, 2, 26),
(408, 5, 26),
(409, 1, 6),
(410, 2, 6),
(411, 5, 6),
(412, 1, 5),
(413, 2, 5),
(414, 5, 5),
(415, 3, 79),
(416, 1, 80),
(417, 5, 80),
(418, 1, 81),
(419, 5, 81),
(420, 5, 82),
(421, 3, 83),
(422, 1, 74),
(423, 4, 74);

-- --------------------------------------------------------

--
-- Estrutura da tabela `system_message`
--

CREATE TABLE `system_message` (
  `id` int(11) NOT NULL,
  `system_user_id` int(11) NOT NULL,
  `system_user_to_id` int(11) NOT NULL,
  `subject` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `dt_message` datetime NOT NULL,
  `checked` varchar(45) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `system_notification`
--

CREATE TABLE `system_notification` (
  `id` int(11) NOT NULL,
  `system_user_id` int(11) NOT NULL,
  `system_user_to_id` int(11) NOT NULL,
  `subject` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `dt_message` date NOT NULL,
  `action_url` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `action_label` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `checked` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `system_program`
--

CREATE TABLE `system_program` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `controller` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `system_program`
--

INSERT INTO `system_program` (`id`, `name`, `controller`) VALUES
(1, 'System Group Form', 'SystemGroupForm'),
(2, 'System Group List', 'SystemGroupList'),
(3, 'System Program Form', 'SystemProgramForm'),
(4, 'System Program List', 'SystemProgramList'),
(5, 'System User Form', 'SystemUserForm'),
(6, 'System User List', 'SystemUserList'),
(7, 'Common Page', 'CommonPage'),
(8, 'System PHP Info', 'SystemPHPInfoView'),
(9, 'System ChangeLog View', 'SystemChangeLogView'),
(10, 'Welcome View', '	WelcomeView'),
(11, 'System Sql Log', 'SystemSqlLogList'),
(12, 'System Profile View', 'SystemProfileView'),
(13, 'System Profile Form', 'SystemProfileForm'),
(14, 'System SQL Panel', 'SystemSQLPanel'),
(15, 'System Access Log', 'SystemAccessLogList'),
(16, 'System Message Form', 'SystemMessageForm'),
(17, 'System Message List', 'SystemMessageList'),
(18, 'System Message Form View', 'SystemMessageFormView'),
(19, 'System Notification List', 'SystemNotificationList'),
(20, 'System Notification Form View', 'SystemNotificationFormView'),
(21, 'System Document Category List', 'SystemDocumentCategoryFormList'),
(22, 'System Document Form', 'SystemDocumentForm'),
(23, 'System Document Upload Form', 'SystemDocumentUploadForm'),
(24, 'System Document List', 'SystemDocumentList'),
(25, 'System Shared Document List', 'SystemSharedDocumentList'),
(26, 'System Unit Form', 'SystemUnitForm'),
(27, 'System Unit List', 'SystemUnitList'),
(28, 'System Access stats', 'SystemAccessLogStats'),
(29, 'System Preference form', 'SystemPreferenceForm'),
(30, 'System Support form', '	SystemSupportForm'),
(31, 'System PHP Error', 'SystemPHPErrorLogView'),
(32, 'System Database Browser', 'SystemDatabaseExplorer'),
(33, 'System Table List', 'SystemTableList'),
(34, 'System Data Browser', 'SystemDataBrowser'),
(35, 'Ponto Entrada Form', 'PontoEntradaForm'),
(36, 'Ponto List', 'PontoList'),
(39, 'Ponto Saida Form', 'PontoSaidaForm'),
(42, 'Consulta Ponto Bolsista List', 'ConsultaPontoBolsistaList'),
(43, 'Consulta Ponto Admin List', 'ConsultaPontoAdminList'),
(47, 'Justificativa List', 'JustificativaList'),
(48, 'Justificativa Form', 'JustificativaForm'),
(52, 'Tipo Justificativa List', 'TipoJustificativaList'),
(53, 'Tipo Justificativa Form', 'TipoJustificativaForm'),
(54, 'Calendar Database View', 'CalendarDatabaseView'),
(55, 'Calendar Event Form', '	CalendarEventForm'),
(56, 'Agendamento List', '	AgendamentoList'),
(57, 'Agendamento Form', '	AgendamentoForm'),
(58, 'Estatistica Ponto', 'EstatisticaPonto'),
(59, 'Ajustar Justificativa AdminF orm', 'AjustarJustificativaAdminForm'),
(60, 'Ajustar Justificativa Admin List', 'AjustarJustificativaAdminList'),
(62, '	Gera Relatorio Ponto', '	GeraRelatorioPonto'),
(63, 'Relatorio Ponto PDF', 'RelatorioPontoPDF'),
(64, 'Gera Ponto IP', 'GeraPontoIP'),
(65, 'Especial Record', 'EspecialRecord'),
(66, 'Ponto Especial Login', 'PontoEspecialLogin'),
(68, 'Ponto Especial Painel', 'PontoEspecialPainel'),
(70, 'Consulta Presenca Especial List', 'ConsultaPresencaEspecialList'),
(71, 'Consulta Especial Admin List', 'ConsultaEspecialAdminList'),
(72, 'Agendamento List', 'AgendamentoList'),
(73, 'Agendamento Form', 'AgendamentoForm'),
(74, 'Gera Relatorio Presenca Especial', 'GeraRelatorioPresencaEspecial'),
(75, 'Form Qrcode View', 'FormQrcodeView'),
(76, 'Ajustar Ponto Admin List', 'AjustarPontoAdminList'),
(77, 'Ajustar Ponto Admin Form', 'AjustarPontoAdminForm'),
(78, 'Consulta Justificativa Admin List', 'ConsultaJustificativaAdminList'),
(79, 'Consulta Justificativa Bolsista List', 'ConsultaJustificativaBolsistaList'),
(80, 'Ajustar Presenca Especial Admin List', 'AjustarPresencaEspecialAdminList'),
(81, 'Ajustar Presenca Especial Admin Form', 'AjustarPresencaEspecialAdminForm'),
(82, 'Gera Relatorio Admin Presenca Especial', 'GeraRelatorioAdminPresencaEspecial'),
(83, 'Gera Relatorio Bolsista', 'GeraRelatorioBolsista');

-- --------------------------------------------------------

--
-- Estrutura da tabela `system_sql_log`
--

CREATE TABLE `system_sql_log` (
  `id` int(11) NOT NULL,
  `logdate` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `login` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `database_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `sql_command` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `statement_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `system_unit`
--

CREATE TABLE `system_unit` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `system_unit`
--

INSERT INTO `system_unit` (`id`, `name`) VALUES
(1, 'Remoto'),
(2, 'Local');

-- --------------------------------------------------------

--
-- Estrutura da tabela `system_user`
--

CREATE TABLE `system_user` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `login` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `email` varchar(45) NOT NULL,
  `frontpage_id` int(11) NOT NULL,
  `system_unit_id` int(11) DEFAULT NULL,
  `active` varchar(45) NOT NULL,
  `autorizacao` varchar(45) NOT NULL,
  `periodo` varchar(50) NOT NULL,
  `curso` varchar(100) NOT NULL,
  `anocurso` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `system_user`
--

INSERT INTO `system_user` (`id`, `name`, `login`, `password`, `email`, `frontpage_id`, `system_unit_id`, `active`, `autorizacao`, `periodo`, `curso`, `anocurso`) VALUES
(1, 'Desenvolvedor', '123', 'e77989ed21758e78331b20e477fc5582', 'desenvolvedor@dev.com', 54, 1, 'Y', 'S', '1', 'SISTEMAS', '2019'),
(2, 'Administrador Ponto', '4321', 'd93591bdf7860e1e4ee2fca799911215', 'usuario@user.com', 54, 1, 'Y', 'N', '8', 'SISTEMAS', '2019'),
(3, 'Aluno Bolsista', '1234', '81dc9bdb52d04dc20036dbd8313ed055', 'bolsista@bol.com', 54, 1, 'Y', 'N', '8', 'SISTEMAS', '2019'),
(4, 'Aluno Especial', '6789', '46d045ff5190f6ea93739da6c0aa19bc', 'alunoespecial@especial.com', 54, 2, 'Y', 'S', '1', 'SISTEMAS', '2019'),
(5, 'Admin Especial', '9876', '912e79cd13c64069d91da65d62fbb78c', 'adminespecial@especial.com', 54, 1, 'Y', 'N', '1', 'SISTEMAS', '2019');

-- --------------------------------------------------------

--
-- Estrutura da tabela `system_user_group`
--

CREATE TABLE `system_user_group` (
  `id` int(11) NOT NULL,
  `system_user_id` int(11) NOT NULL,
  `system_group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `system_user_group`
--

INSERT INTO `system_user_group` (`id`, `system_user_id`, `system_group_id`) VALUES
(56, 1, 1),
(57, 1, 2),
(58, 1, 5),
(59, 2, 2),
(60, 3, 3),
(61, 4, 4),
(62, 5, 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `system_user_program`
--

CREATE TABLE `system_user_program` (
  `id` int(11) NOT NULL,
  `system_user_id` int(11) NOT NULL,
  `system_program_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `system_user_unit`
--

CREATE TABLE `system_user_unit` (
  `id` int(11) NOT NULL,
  `system_user_id` int(11) NOT NULL,
  `system_unit_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `system_user_unit`
--

INSERT INTO `system_user_unit` (`id`, `system_user_id`, `system_unit_id`) VALUES
(45, 1, 1),
(46, 1, 2),
(47, 2, 1),
(48, 2, 2),
(49, 2, 3),
(50, 3, 1),
(51, 3, 2),
(52, 4, 2),
(53, 5, 1),
(54, 5, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipojustificativa`
--

CREATE TABLE `tipojustificativa` (
  `id` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `registro_ip` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tipojustificativa`
--

INSERT INTO `tipojustificativa` (`id`, `nome`, `registro_ip`) VALUES
(2, 'Atestado', '::1'),
(3, 'Atraso', '::1'),
(4, 'Falta', '::1');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `calendario_evento`
--
ALTER TABLE `calendario_evento`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `especial`
--
ALTER TABLE `especial`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `justificativa`
--
ALTER TABLE `justificativa`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `ponto`
--
ALTER TABLE `ponto`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `system_access_log`
--
ALTER TABLE `system_access_log`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `system_document`
--
ALTER TABLE `system_document`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `system_document_category`
--
ALTER TABLE `system_document_category`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `system_document_group`
--
ALTER TABLE `system_document_group`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `system_document_user`
--
ALTER TABLE `system_document_user`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `system_group`
--
ALTER TABLE `system_group`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `system_group_program`
--
ALTER TABLE `system_group_program`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `system_message`
--
ALTER TABLE `system_message`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `system_notification`
--
ALTER TABLE `system_notification`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `system_program`
--
ALTER TABLE `system_program`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `system_sql_log`
--
ALTER TABLE `system_sql_log`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `system_unit`
--
ALTER TABLE `system_unit`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `system_user`
--
ALTER TABLE `system_user`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `system_user_group`
--
ALTER TABLE `system_user_group`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `system_user_program`
--
ALTER TABLE `system_user_program`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `system_user_unit`
--
ALTER TABLE `system_user_unit`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tipojustificativa`
--
ALTER TABLE `tipojustificativa`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `calendario_evento`
--
ALTER TABLE `calendario_evento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `especial`
--
ALTER TABLE `especial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `justificativa`
--
ALTER TABLE `justificativa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ponto`
--
ALTER TABLE `ponto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `system_access_log`
--
ALTER TABLE `system_access_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `system_document`
--
ALTER TABLE `system_document`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `system_document_category`
--
ALTER TABLE `system_document_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `system_document_group`
--
ALTER TABLE `system_document_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `system_document_user`
--
ALTER TABLE `system_document_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `system_group`
--
ALTER TABLE `system_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `system_group_program`
--
ALTER TABLE `system_group_program`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=424;

--
-- AUTO_INCREMENT de tabela `system_message`
--
ALTER TABLE `system_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `system_notification`
--
ALTER TABLE `system_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `system_program`
--
ALTER TABLE `system_program`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT de tabela `system_sql_log`
--
ALTER TABLE `system_sql_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `system_unit`
--
ALTER TABLE `system_unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `system_user`
--
ALTER TABLE `system_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `system_user_group`
--
ALTER TABLE `system_user_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT de tabela `system_user_program`
--
ALTER TABLE `system_user_program`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `system_user_unit`
--
ALTER TABLE `system_user_unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de tabela `tipojustificativa`
--
ALTER TABLE `tipojustificativa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

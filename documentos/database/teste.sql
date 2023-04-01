-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.4.14-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Copiando dados para a tabela auditoria.permissions: ~33 rows (aproximadamente)
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'view_usuario', 'Visualizar as informações do usuário do sistema Auditoria', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(2, 'create_usuario', 'Criar o usuário do sistema Auditoria', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(3, 'edit_usuario', 'Editar as informações do usuário do sistema Auditoria', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(4, 'delete_usuario', 'Excluir o usuário do sistema Auditoria', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(5, 'view_franqueado', 'Visualizar as informações do Franqueado', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(6, 'create_franqueado', 'Criar o Franqueado do sistema de Auditoria', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(7, 'edit_franqueado', 'Editar as informações do Franqueado', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(8, 'delete_franqueado', 'Excluir o Franqueado do sistema de Auditoria', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(9, 'add_usuario_franqueado', 'Adicionar um usuário a um determinado Franqueado', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(10, 'view_auditoria', 'Visualizar as informações da Auditoria', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(11, 'create_auditoria', 'Criar auditorias do sistema de Auditoria', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(12, 'edit_auditoria', 'Editar a auditoria do sistema Auditoria', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(13, 'delete_auditoria', 'Excluir a auditoria do sistema de Auditoria', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(15, 'exec_auditoria', 'Executar uma auditoria, adicionando as respostas para as perguntas da auditoria', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(16, 'view_plano_acao', 'Visualizar plano de ação da auditoria', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(17, 'view_tempo', 'Visualizar as informações dos Tempos de Correção para o Plano de Ação', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(18, 'create_tempo', 'Criar os Tempos de Correção para o Plano de Ação', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(19, 'edit_tempo', 'Editar os Tempos de Correção para o Plano de Ação', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(20, 'delete_tempo', 'Excluir os Tempos de Correção para o Plano de Ação', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(21, 'view_auditagem', 'Visualizar as informações das Auditagens do Sistema', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(22, 'create_auditagem', 'Criar as Auditagens do Sistema', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(23, 'edit_auditagem', 'Editar as Auditagens do Sistema', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(24, 'delete_auditagem', 'Excluir as Auditagens do Sistema', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(25, 'copy_auditoria', 'Criar uma nova Auditoria à partir de uma cópia', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(26, 'exec_plano_acao', 'Executar o plano de ação da auditoria', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(27, 'view_categoria', 'Visualizar as informações da Categoria', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(28, 'create_categoria', 'Criar categorias do sistema de Auditoria', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(29, 'edit_categoria', 'Editar a categoria do sistema Auditoria', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(30, 'delete_categoria', 'Excluir a categoria do sistema de Auditoria', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(31, 'view_arquivo', 'Visualizar as informações dos Arquivos da Categoria', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(32, 'create_arquivo', 'Criar arquivo para a categoria do sistema de Auditoria', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(33, 'edit_arquivo', 'Editar o arquivo da categoria do sistema Auditoria', '2021-06-22 18:53:11', '2021-06-22 18:53:11'),
	(34, 'delete_arquivo', 'Excluir o arquivo da categoria do sistema de Auditoria', '2021-06-22 18:53:11', '2021-06-22 18:53:11');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;

-- Copiando dados para a tabela auditoria.permission_role: ~49 rows (aproximadamente)
/*!40000 ALTER TABLE `permission_role` DISABLE KEYS */;
INSERT INTO `permission_role` (`id`, `permission_id`, `role_id`, `created_at`, `updated_at`) VALUES
	(83, 1, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(84, 2, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(85, 3, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(86, 4, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(87, 5, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(88, 6, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(89, 7, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(90, 8, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(91, 9, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(92, 10, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(93, 11, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(94, 12, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(95, 13, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(96, 16, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(97, 17, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(98, 18, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(99, 19, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(100, 20, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(101, 21, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(102, 22, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(103, 23, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(104, 24, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(105, 25, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(106, 1, 2, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(107, 3, 2, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(108, 5, 2, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(109, 10, 2, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(110, 15, 2, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(111, 16, 2, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(112, 21, 2, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(113, 22, 2, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(114, 23, 2, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(115, 24, 2, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(116, 1, 3, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(117, 3, 3, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(118, 5, 3, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(119, 16, 3, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(120, 21, 3, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(121, 26, 3, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(122, 27, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(123, 28, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(124, 29, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(125, 30, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(126, 31, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(127, 32, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(128, 33, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(129, 34, 1, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(130, 31, 2, '2021-06-22 18:53:18', '2021-06-22 18:53:18'),
	(131, 31, 3, '2021-06-22 18:53:18', '2021-06-22 18:53:18');
/*!40000 ALTER TABLE `permission_role` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

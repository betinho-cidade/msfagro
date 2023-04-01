-- --------------------------------------------------------
-- Servidor:                     162.241.60.162
-- Versão do servidor:           5.7.23-23 - Percona Server (GPL), Release 23, Revision 500fcf5
-- OS do Servidor:               Linux
-- HeidiSQL Versão:              11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Copiando estrutura para tabela k9fama99_gestao.anuncios
CREATE TABLE IF NOT EXISTS `anuncios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parceiro_id` bigint(20) unsigned DEFAULT NULL,
  `path_anuncio` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_anuncio` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ordem` int(11) DEFAULT NULL,
  `qtd_views` int(11) DEFAULT NULL,
  `qtd_clicks` int(11) DEFAULT NULL,
  `status` enum('A','I') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'I',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `anuncios_parceiro_id_foreign` (`parceiro_id`),
  CONSTRAINT `anuncios_parceiro_id_foreign` FOREIGN KEY (`parceiro_id`) REFERENCES `parceiros` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.anuncios: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `anuncios` DISABLE KEYS */;
INSERT INTO `anuncios` (`id`, `parceiro_id`, `path_anuncio`, `url_anuncio`, `ordem`, `qtd_views`, `qtd_clicks`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 'anuncio_1_1621894826.png', 'https://www.petlove.com.br/aplicativo', 1, 139, 2, 'A', '2021-05-24 12:22:05', '2021-05-27 00:00:54'),
	(2, NULL, 'anuncio_2_1621901117.png', 'https://shoppingpontanegra.com.br/bannersitepetfriendly/', 3, 62, 1, 'A', '2021-05-24 23:29:41', '2021-05-27 00:00:54'),
	(3, 1, 'anuncio_3_1621900086.png', NULL, 2, 61, NULL, 'A', '2021-05-24 23:31:29', '2021-05-27 00:00:54'),
	(4, NULL, 'anuncio_4_1621900862.png', NULL, 4, 60, NULL, 'A', '2021-05-24 23:33:06', '2021-05-27 00:00:54');
/*!40000 ALTER TABLE `anuncios` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.aulas
CREATE TABLE IF NOT EXISTS `aulas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `modulo_id` bigint(20) unsigned NOT NULL,
  `nome` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resumo` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordem` int(11) NOT NULL,
  `path_imagem` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_video` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('A','I') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'I',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `aulas_modulo_id_foreign` (`modulo_id`),
  CONSTRAINT `aulas_modulo_id_foreign` FOREIGN KEY (`modulo_id`) REFERENCES `modulos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.aulas: ~34 rows (aproximadamente)
/*!40000 ALTER TABLE `aulas` DISABLE KEYS */;
INSERT INTO `aulas` (`id`, `modulo_id`, `nome`, `resumo`, `descricao`, `ordem`, `path_imagem`, `url_video`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Introdução', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla congue imperdiet varius. Mauris in ante at diam iaculis convallis ac in enim aeneansa', '<p style="margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla congue imperdiet varius. Mauris in ante at diam iaculis convallis ac in enim. Aenean facilisis urna eget arcu vulputate ultrices varius lacinia turpis. Nunc in mi ut tortor euismod condimentum. Maecenas consectetur rutrum nibh hendrerit finibus. Ut dictum, est a imperdiet viverra, erat nulla condimentum arcu, at vulputate diam libero a tortor. Etiam scelerisque pulvinar mattis. Suspendisse porta sem feugiat lobortis efficitur. Sed maximus nibh sed mollis aliquam.</p>', 1, 'aula_1620173154.jpg', 'https://player.vimeo.com/video/246998411?title=0&byline=0&portrait=0', 'A', '2021-05-05 00:05:54', '2021-05-26 01:05:06'),
	(2, 1, 'Aqui o nome da aula dois do módulo 1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla congue imperdiet varius. Mauris in ante at diam iaculis convallis ac in enim aeneansa', '<p><span style="color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla congue imperdiet varius. Mauris in ante at diam iaculis convallis ac in enim. Aenean facilisis urna eget arcu vulputate ultrices varius lacinia turpis. Nunc in mi ut tortor euismod condimentum. Maecenas consectetur rutrum nibh hendrerit finibus. Ut dictum, est a imperdiet viverra, erat nulla condimentum arcu, at vulputate diam libero a tortor. Etiam scelerisque pulvinar mattis. Suspendisse porta sem feugiat lobortis efficitur. Sed maximus nibh sed mollis aliquam.</span><br></p>', 2, 'aula_1620173316.jpg', 'https://player.vimeo.com/video/246998411?title=0&byline=0&portrait=0', 'A', '2021-05-05 00:08:36', '2021-05-05 00:08:36'),
	(3, 2, 'Aqui o nome da aula do módulo 2', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla congue imperdiet varius. Mauris in ante at diam iaculis convallis ac in enim aeneansa', '<p><span style="color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla congue imperdiet varius. Mauris in ante at diam iaculis convallis ac in enim. Aenean facilisis urna eget arcu vulputate ultrices varius lacinia turpis. Nunc in mi ut tortor euismod condimentum. Maecenas consectetur rutrum nibh hendrerit finibus. Ut dictum, est a imperdiet viverra, erat nulla condimentum arcu, at vulputate diam libero a tortor. Etiam scelerisque pulvinar mattis. Suspendisse porta sem feugiat lobortis efficitur. Sed maximus nibh sed mollis aliquam.</span><br></p>', 1, 'aula_1620173370.jpg', 'https://player.vimeo.com/video/246998411?title=0&byline=0&portrait=0', 'A', '2021-05-05 00:09:30', '2021-05-05 00:09:30'),
	(4, 2, 'Aqui o nome da aula dois do módulo 2', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla congue imperdiet varius. Mauris in ante at diam iaculis convallis ac in enim aeneansa', '<p><span style="color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla congue imperdiet varius. Mauris in ante at diam iaculis convallis ac in enim. Aenean facilisis urna eget arcu vulputate ultrices varius lacinia turpis. Nunc in mi ut tortor euismod condimentum. Maecenas consectetur rutrum nibh hendrerit finibus. Ut dictum, est a imperdiet viverra, erat nulla condimentum arcu, at vulputate diam libero a tortor. Etiam scelerisque pulvinar mattis. Suspendisse porta sem feugiat lobortis efficitur. Sed maximus nibh sed mollis aliquam.</span><br></p>', 2, 'aula_1620173399.jpg', 'https://player.vimeo.com/video/246998411?title=0&byline=0&portrait=0', 'A', '2021-05-05 00:09:59', '2021-05-05 00:09:59'),
	(5, 3, 'Veneno de rato', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla congue imperdiet varius. Mauris in ante at diam iaculis convallis ac in enim aeneansa', '<p><span style="color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla congue imperdiet varius. Mauris in ante at diam iaculis convallis ac in enim. Aenean facilisis urna eget arcu vulputate ultrices varius lacinia turpis. Nunc in mi ut tortor euismod condimentum. Maecenas consectetur rutrum nibh hendrerit finibus. Ut dictum, est a imperdiet viverra, erat nulla condimentum arcu, at vulputate diam libero a tortor. Etiam scelerisque pulvinar mattis. Suspendisse porta sem feugiat lobortis efficitur. Sed maximus nibh sed mollis aliquam.</span><br></p>', 1, 'aula_1621898354.jpg', 'https://player.vimeo.com/video/246998411?title=0&byline=0&portrait=0', 'A', '2021-05-05 00:13:18', '2021-05-24 23:19:15'),
	(6, 3, 'Veneno de plantas', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla congue imperdiet varius. Mauris in ante at diam iaculis convallis ac in enim aeneansa', '<p><span style="color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla congue imperdiet varius. Mauris in ante at diam iaculis convallis ac in enim. Aenean facilisis urna eget arcu vulputate ultrices varius lacinia turpis. Nunc in mi ut tortor euismod condimentum. Maecenas consectetur rutrum nibh hendrerit finibus. Ut dictum, est a imperdiet viverra, erat nulla condimentum arcu, at vulputate diam libero a tortor. Etiam scelerisque pulvinar mattis. Suspendisse porta sem feugiat lobortis efficitur. Sed maximus nibh sed mollis aliquam.</span><br></p>', 2, 'aula_1621898407.jpg', 'https://player.vimeo.com/video/246998411?title=0&byline=0&portrait=0', 'A', '2021-05-05 00:13:47', '2021-05-24 23:20:07'),
	(7, 4, 'Aqui o nome da aula do módulo 2', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla congue imperdiet varius. Mauris in ante at diam iaculis convallis ac in enim aeneansa', '<p><span style="color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla congue imperdiet varius. Mauris in ante at diam iaculis convallis ac in enim. Aenean facilisis urna eget arcu vulputate ultrices varius lacinia turpis. Nunc in mi ut tortor euismod condimentum. Maecenas consectetur rutrum nibh hendrerit finibus. Ut dictum, est a imperdiet viverra, erat nulla condimentum arcu, at vulputate diam libero a tortor. Etiam scelerisque pulvinar mattis. Suspendisse porta sem feugiat lobortis efficitur. Sed maximus nibh sed mollis aliquam.</span><br></p>', 1, 'aula_1620173667.jpg', 'https://player.vimeo.com/video/246998411?title=0&byline=0&portrait=0', 'A', '2021-05-05 00:14:27', '2021-05-05 00:14:37'),
	(8, 4, 'Aqui o nome da aula do módulo 2', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla congue imperdiet varius. Mauris in ante at diam iaculis convallis ac in enim aeneansa', '<p><span style="color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla congue imperdiet varius. Mauris in ante at diam iaculis convallis ac in enim. Aenean facilisis urna eget arcu vulputate ultrices varius lacinia turpis. Nunc in mi ut tortor euismod condimentum. Maecenas consectetur rutrum nibh hendrerit finibus. Ut dictum, est a imperdiet viverra, erat nulla condimentum arcu, at vulputate diam libero a tortor. Etiam scelerisque pulvinar mattis. Suspendisse porta sem feugiat lobortis efficitur. Sed maximus nibh sed mollis aliquam.</span><br></p>', 2, 'aula_1620173702.jpg', 'https://player.vimeo.com/video/246998411?title=0&byline=0&portrait=0', 'A', '2021-05-05 00:15:02', '2021-05-05 00:15:02'),
	(9, 5, 'Aqui a aula 1 do novo curso', 'Aqui o resumo da nova aula', '<p style="margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque porta enim et mauris finibus, sit amet commodo nibh lacinia. Nam scelerisque neque id lectus vestibulum semper. Sed ornare felis quis laoreet commodo. Nulla facilisi. Vestibulum id interdum velit. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Suspendisse semper commodo nisl, vel lacinia neque. Sed eu ante eleifend, eleifend tellus eu, rhoncus orci. Vivamus tincidunt elit nec sapien dictum aliquam. Curabitur fermentum sapien id justo maximus efficitur. Nunc pretium est pretium condimentum vulputate. Etiam ullamcorper dui a nulla tincidunt congue. Sed ac nisl magna. Donec eget arcu nisi.</p><p style="margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px;">Maecenas commodo risus nulla. Sed sed eleifend tellus. Praesent ante justo, facilisis in odio nec, porttitor sollicitudin lectus. Aenean nulla eros, convallis et condimentum in, laoreet et nisi. Morbi at faucibus urna. Quisque eu mattis quam. Pellentesque molestie mattis libero, ac cursus quam ultricies a. Donec ultricies felis quis mollis suscipit. Mauris maximus condimentum nisi. Nulla facilisi. Suspendisse consectetur nibh ac venenatis elementum. Mauris id sapien erat. Ut quis convallis libero.</p>', 1, 'aula_1621859872.jpg', 'https://player.vimeo.com/video/246998411?title=0&byline=0&portrait=0', 'A', '2021-05-24 12:37:52', '2021-05-24 12:37:52'),
	(10, 6, 'Aqui a aula', 'resumo', '<p>descricao</p>', 1, 'aula_1621860558.jpg', 'https://adm.k9famali.com.br/', 'A', '2021-05-24 12:49:18', '2021-05-24 12:49:18'),
	(11, 7, 'Começo da art', 'Esse é o resumo da aula', '<p>Essa é a descrição enorme da qulaa</p>', 1, 'aula_1621873278.jpg', 'https://www.youtube.com/watch?v=pcRyE20UI0o', 'A', '2021-05-24 15:10:43', '2021-05-24 16:21:18'),
	(12, 7, '!. Como abrir uma conta no Canva', 'Aqui o Resumo da aula', '<p>Aqui o texto grande</p>', 1, 'aula_1621873731.jpg', 'https://www.youtube.com/watch?v=pcRyE20UI0o', 'A', '2021-05-24 16:28:51', '2021-05-24 16:28:51'),
	(13, 7, 'Como subir uma imagem sua', 'Texto Resumo', '<p>Text grande</p>', 2, 'aula_1621873944.jpg', 'https://www.youtube.com/watch?v=pcRyE20UI0o', 'A', '2021-05-24 16:32:24', '2021-05-24 16:32:24'),
	(15, 12, 'Não comer coisas do chão', 'Vamos treinar ele anao comer nada que cai no chao', '<p>Nessa aula....</p>', 1, 'aula_1621898077.jpg', 'https://www.youtube.com/watch?v=pcRyE20UI0o', 'A', '2021-05-24 23:14:37', '2021-05-24 23:14:37'),
	(18, 13, 'O que são Planos de Treinos?', 'xxxx', '<p>xxxxx</p>', 3, 'aula_1621958232.jpg', 'https://www.youtube.com/watch?v=pcRyE20UI0o', 'A', '2021-05-25 15:57:12', '2021-05-25 15:57:12'),
	(19, 13, 'O que são Niveis?', 'xxxxx', '<p>xxxxxx</p>', 1, 'aula_1621958304.jpg', 'https://www.youtube.com/watch?v=pcRyE20UI0o', 'A', '2021-05-25 15:58:24', '2021-05-25 15:58:24'),
	(20, 13, 'O que é esse símbolo de sino?', 'xxxxxx', '<p>xxxxxxx</p>', 2, 'aula_1621958377.jpg', 'https://www.youtube.com/watch?v=pcRyE20UI0o', 'A', '2021-05-25 15:59:37', '2021-05-25 15:59:37'),
	(21, 13, 'Como é as Lives?', 'xxxxxx', '<p>xxxxxxx</p>', 4, 'aula_1621958529.jpg', 'https://www.youtube.com/watch?v=pcRyE20UI0o', 'A', '2021-05-25 16:02:09', '2021-05-25 16:02:09'),
	(22, 13, 'O que é essa Medalha ao lado de minha Foto?', 'xxxxxx', '<p>xxxxxxx</p>', 5, 'aula_1621958599.jpg', 'https://www.youtube.com/watch?v=pcRyE20UI0o', 'A', '2021-05-25 16:03:19', '2021-05-25 16:03:20'),
	(23, 13, 'Como são distribuídas as Categorias?', 'xxxxxxx', '<p>xxxxxxxx</p>', 6, 'aula_1621958663.jpg', 'https://www.youtube.com/watch?v=pcRyE20UI0o', 'A', '2021-05-25 16:04:23', '2021-05-25 16:04:23'),
	(24, 13, 'Para que serve o botão Home?', 'xxxxx', '<p>xxxxxxx</p>', 7, 'aula_1621958879.jpg', 'https://www.youtube.com/watch?v=pcRyE20UI0o', 'A', '2021-05-25 16:07:59', '2021-05-25 16:07:59'),
	(26, 13, 'Como CANCELAR minha assinatura mensal?', 'xxxxxx', '<p>xxxxxxx</p>', 8, 'aula_1621958991.jpg', 'https://www.youtube.com/watch?v=pcRyE20UI0o', 'A', '2021-05-25 16:09:51', '2021-05-25 16:09:51'),
	(27, 13, 'Como fazer propaganda nos Banners?', 'xxxxx', '<p>xxxxxx</p>', 9, 'aula_1621959050.jpg', 'https://www.youtube.com/watch?v=pcRyE20UI0o', 'A', '2021-05-25 16:10:50', '2021-05-25 16:10:50'),
	(28, 13, 'Como colocar minha foto no Perfil?', 'xxxxxx', '<p>xxxxxxx</p>', 10, 'aula_1621959114.jpg', 'https://www.youtube.com/watch?v=pcRyE20UI0o', 'A', '2021-05-25 16:11:54', '2021-05-25 16:11:54'),
	(29, 13, 'Como faço para ver as Aulas Favoritas que salvei?', 'xxxxx', '<p>xxxxxx</p>', 11, 'aula_1621959174.jpg', 'https://www.youtube.com/watch?v=pcRyE20UI0o', 'A', '2021-05-25 16:12:54', '2021-05-25 16:12:54'),
	(30, 13, 'Como consigo o Certificado dos Módulos que terminei?', 'xxxx', '<p>xxxxx</p>', 12, 'aula_1621959348.jpg', 'https://www.youtube.com/watch?v=pcRyE20UI0o', 'A', '2021-05-25 16:15:48', '2021-05-25 16:15:48'),
	(31, 13, 'Para que serve o Botão AVALIAR nas aulas?', 'xxxx', '<p>xxxxx</p>', 13, 'aula_1621959499.jpg', 'https://www.youtube.com/watch?v=pcRyE20UI0o', 'A', '2021-05-25 16:18:19', '2021-05-25 16:18:19'),
	(32, 13, 'Para que serve o campo de ANOTAÇÔES?', 'xxxxxx', '<p>xxxxxxx</p>', 14, 'aula_1621959579.jpg', 'https://www.youtube.com/watch?v=pcRyE20UI0o', 'A', '2021-05-25 16:19:39', '2021-05-25 16:19:39'),
	(33, 13, 'Resumo de todo o Programa.', 'xxxxx', '<p>xxxxxx</p>', 0, 'aula_1621959617.jpg', 'https://www.youtube.com/watch?v=pcRyE20UI0o', 'A', '2021-05-25 16:20:17', '2021-05-25 16:21:15'),
	(34, 46, 'Por onde começar?', 'xxxxx', '<p>xxxxxxxxx</p>', 1, 'aula_1621993753.jpg', 'https://www.youtube.com/watch?v=pcRyE20UI0o', 'A', '2021-05-26 01:49:13', '2021-05-26 01:49:13'),
	(35, 47, 'Aula 1', 'xxxx', '<p>xxxxx</p>', 1, 'aula_1621993838.jpg', 'https://www.youtube.com/watch?v=pcRyE20UI0o', 'A', '2021-05-26 01:50:38', '2021-05-26 01:50:39'),
	(36, 47, 'Aula 2', 'xxxxx', '<p>xxxxx</p>', 2, 'aula_1621993889.jpg', 'https://www.youtube.com/watch?v=pcRyE20UI0o', 'A', '2021-05-26 01:51:29', '2021-05-26 01:51:29'),
	(37, 47, 'Aula 3', 'xxxxx', '<p>xxxxx</p>', 3, 'aula_1621993922.jpg', 'https://www.youtube.com/watch?v=pcRyE20UI0o', 'A', '2021-05-26 01:52:02', '2021-05-26 01:52:03'),
	(38, 48, 'Aula 1', 'xxxx', '<p>xxxxxx</p>', 1, 'aula_1621993967.jpg', 'https://www.youtube.com/watch?v=pcRyE20UI0o', 'A', '2021-05-26 01:52:47', '2021-05-26 01:52:48'),
	(39, 48, 'Aula 2', 'xxxx', '<p>xxxx</p>', 2, 'aula_1621994016.jpg', 'https://www.youtube.com/watch?v=pcRyE20UI0o', 'A', '2021-05-26 01:53:36', '2021-05-26 01:53:36'),
	(40, 48, 'Aula 3', 'xxxxx', '<p>xxxxxx</p>', 3, 'aula_1621994051.jpg', 'https://www.youtube.com/watch?v=pcRyE20UI0o', 'A', '2021-05-26 01:54:11', '2021-05-26 01:54:11');
/*!40000 ALTER TABLE `aulas` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.aula_anotacaos
CREATE TABLE IF NOT EXISTS `aula_anotacaos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `aula_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `anotacao` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `aula_anotacaos_aula_id_foreign` (`aula_id`),
  KEY `aula_anotacaos_user_id_foreign` (`user_id`),
  CONSTRAINT `aula_anotacaos_aula_id_foreign` FOREIGN KEY (`aula_id`) REFERENCES `aulas` (`id`),
  CONSTRAINT `aula_anotacaos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.aula_anotacaos: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `aula_anotacaos` DISABLE KEYS */;
INSERT INTO `aula_anotacaos` (`id`, `aula_id`, `user_id`, `anotacao`, `created_at`, `updated_at`) VALUES
	(1, 1, 2, 'Aqui eu posso inserir anotações', '2021-05-05 13:16:11', '2021-05-05 13:16:11'),
	(5, 13, 2, 'nhvhgvhgvhghgv', '2021-05-24 19:23:34', '2021-05-24 19:23:34'),
	(6, 11, 2, 'lkdvlkdfnclkfenc', '2021-05-26 23:59:10', '2021-05-26 23:59:10');
/*!40000 ALTER TABLE `aula_anotacaos` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.aula_assistidas
CREATE TABLE IF NOT EXISTS `aula_assistidas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `aula_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `andamento` enum('A','F') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `aula_assistida_uk` (`aula_id`,`user_id`),
  KEY `aula_assistidas_user_id_foreign` (`user_id`),
  CONSTRAINT `aula_assistidas_aula_id_foreign` FOREIGN KEY (`aula_id`) REFERENCES `aulas` (`id`),
  CONSTRAINT `aula_assistidas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.aula_assistidas: ~10 rows (aproximadamente)
/*!40000 ALTER TABLE `aula_assistidas` DISABLE KEYS */;
INSERT INTO `aula_assistidas` (`id`, `aula_id`, `user_id`, `andamento`, `created_at`, `updated_at`) VALUES
	(2, 1, 2, 'F', '2021-05-05 13:15:36', '2021-05-05 13:17:18'),
	(3, 2, 2, 'F', '2021-05-05 13:17:39', '2021-05-05 13:17:42'),
	(4, 5, 2, 'F', '2021-05-05 13:19:06', '2021-05-25 00:46:44'),
	(5, 3, 2, 'F', '2021-05-05 13:19:37', '2021-05-05 13:19:39'),
	(6, 4, 2, 'F', '2021-05-05 13:19:44', '2021-05-05 13:19:45'),
	(7, 6, 2, 'F', '2021-05-24 12:15:08', '2021-05-24 12:15:51'),
	(8, 9, 2, 'F', '2021-05-24 12:38:15', '2021-05-24 12:47:45'),
	(9, 10, 2, 'F', '2021-05-24 12:50:24', '2021-05-24 12:50:34'),
	(10, 11, 2, 'F', '2021-05-24 16:22:57', '2021-05-26 23:59:51'),
	(11, 12, 2, 'F', '2021-05-24 16:36:32', '2021-05-24 22:09:55'),
	(12, 13, 2, 'F', '2021-05-24 16:36:44', '2021-05-24 22:10:08'),
	(13, 15, 2, 'F', '2021-05-24 23:17:41', '2021-05-24 23:33:27');
/*!40000 ALTER TABLE `aula_assistidas` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.aula_avaliacaos
CREATE TABLE IF NOT EXISTS `aula_avaliacaos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `aula_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `qtd_estrelas` int(11) NOT NULL DEFAULT '0',
  `avaliacao` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `aula_avaliacao_uk` (`user_id`,`aula_id`),
  KEY `aula_avaliacaos_aula_id_foreign` (`aula_id`),
  CONSTRAINT `aula_avaliacaos_aula_id_foreign` FOREIGN KEY (`aula_id`) REFERENCES `aulas` (`id`),
  CONSTRAINT `aula_avaliacaos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.aula_avaliacaos: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `aula_avaliacaos` DISABLE KEYS */;
INSERT INTO `aula_avaliacaos` (`id`, `aula_id`, `user_id`, `qtd_estrelas`, `avaliacao`, `created_at`, `updated_at`) VALUES
	(1, 5, 2, 3, NULL, '2021-05-24 14:23:16', '2021-05-24 14:23:16'),
	(2, 11, 2, 4, 'cnsdkjcnsdkjcnksdjcksdjcksdjcnksdjncksjdnckjsdncksjncksdjcnsdkjcnskjcnskdjcsdkjcnsdkjcns', '2021-05-24 19:06:55', '2021-05-24 19:06:55');
/*!40000 ALTER TABLE `aula_avaliacaos` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.aula_favoritas
CREATE TABLE IF NOT EXISTS `aula_favoritas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `aula_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `aula_favorita_uk` (`aula_id`,`user_id`),
  KEY `aula_favoritas_user_id_foreign` (`user_id`),
  CONSTRAINT `aula_favoritas_aula_id_foreign` FOREIGN KEY (`aula_id`) REFERENCES `aulas` (`id`),
  CONSTRAINT `aula_favoritas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.aula_favoritas: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `aula_favoritas` DISABLE KEYS */;
INSERT INTO `aula_favoritas` (`id`, `aula_id`, `user_id`, `created_at`, `updated_at`) VALUES
	(1, 1, 2, '2021-05-05 13:17:07', '2021-05-05 13:17:07'),
	(3, 11, 2, '2021-05-24 19:05:30', '2021-05-24 19:05:30');
/*!40000 ALTER TABLE `aula_favoritas` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.aula_materials
CREATE TABLE IF NOT EXISTS `aula_materials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `aula_id` bigint(20) unsigned NOT NULL,
  `nome` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_material` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordem` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `aula_materials_aula_id_foreign` (`aula_id`),
  CONSTRAINT `aula_materials_aula_id_foreign` FOREIGN KEY (`aula_id`) REFERENCES `aulas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.aula_materials: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `aula_materials` DISABLE KEYS */;
INSERT INTO `aula_materials` (`id`, `aula_id`, `nome`, `url_material`, `ordem`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Material de Exemplo', 'http://cityinbag.com/famali/wp-content/uploads/2021/05/pdf-teste.pdf', 1, '2021-05-05 00:19:02', '2021-05-05 00:19:02'),
	(2, 6, 'Material de Exemplo', 'http://cityinbag.com/famali/wp-content/uploads/2021/05/pdf-teste.pdf', 1, '2021-05-05 00:20:52', '2021-05-05 00:20:52'),
	(3, 2, 'Material de Exemplo', 'http://cityinbag.com/famali/wp-content/uploads/2021/05/pdf-teste.pdf', 1, '2021-05-05 00:22:30', '2021-05-05 00:22:30'),
	(4, 9, 'Nome do Material', 'https://adm.k9famali.com.br/', 1, '2021-05-24 12:38:56', '2021-05-24 12:38:56'),
	(5, 13, 'Formulario', 'https://docs.google.com/forms/d/1Z6vztGSk5iH-fAArdQ_roTt73BKHC9Rh3PmkHqxrDj4/edit', 1, '2021-05-24 16:35:32', '2021-05-24 16:35:32');
/*!40000 ALTER TABLE `aula_materials` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.aula_moderacaos
CREATE TABLE IF NOT EXISTS `aula_moderacaos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `aula_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `post` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_post` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `replica` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_replica` timestamp NULL DEFAULT NULL,
  `situacao` enum('N','R','L') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `aula_moderacaos_aula_id_foreign` (`aula_id`),
  KEY `aula_moderacaos_user_id_foreign` (`user_id`),
  CONSTRAINT `aula_moderacaos_aula_id_foreign` FOREIGN KEY (`aula_id`) REFERENCES `aulas` (`id`),
  CONSTRAINT `aula_moderacaos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.aula_moderacaos: ~5 rows (aproximadamente)
/*!40000 ALTER TABLE `aula_moderacaos` DISABLE KEYS */;
INSERT INTO `aula_moderacaos` (`id`, `aula_id`, `user_id`, `post`, `data_post`, `replica`, `data_replica`, `situacao`, `created_at`, `updated_at`) VALUES
	(1, 5, 2, 'b vb vb vb vb', '2021-05-24 12:02:22', NULL, NULL, 'R', '2021-05-24 14:22:56', '2021-05-24 15:02:22'),
	(2, 11, 2, 'Qual o nome', '2021-05-24 13:24:02', NULL, NULL, 'R', '2021-05-24 16:23:43', '2021-05-24 16:24:02'),
	(3, 11, 2, 'nfbcvv', '2021-05-24 14:59:58', NULL, NULL, 'L', '2021-05-24 17:48:13', '2021-05-24 17:59:58'),
	(4, 11, 2, 'vnfgnfgngf', '2021-05-24 16:13:06', NULL, NULL, 'L', '2021-05-24 19:04:09', '2021-05-24 19:13:06'),
	(5, 11, 2, 'Como faço para ver a respostas?', '2021-05-24 16:18:27', NULL, NULL, 'L', '2021-05-24 19:17:45', '2021-05-24 19:18:27'),
	(6, 11, 2, 'Teste de pergunta', '2021-05-24 16:54:31', NULL, NULL, 'L', '2021-05-24 19:54:10', '2021-05-24 19:54:31'),
	(7, 11, 2, 'fktfkjtnfkj5tnk5t', '2021-05-26 23:59:29', NULL, NULL, 'N', '2021-05-26 23:59:29', '2021-05-26 23:59:29');
/*!40000 ALTER TABLE `aula_moderacaos` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.aula_relacionadas
CREATE TABLE IF NOT EXISTS `aula_relacionadas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `aula_id` bigint(20) unsigned NOT NULL,
  `aula_relacionada_id` bigint(20) unsigned NOT NULL,
  `ordem` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `aula_relacionada_uk` (`aula_id`,`aula_relacionada_id`),
  KEY `aula_relacionadas_aula_relacionada_id_foreign` (`aula_relacionada_id`),
  CONSTRAINT `aula_relacionadas_aula_id_foreign` FOREIGN KEY (`aula_id`) REFERENCES `aulas` (`id`),
  CONSTRAINT `aula_relacionadas_aula_relacionada_id_foreign` FOREIGN KEY (`aula_relacionada_id`) REFERENCES `aulas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.aula_relacionadas: ~6 rows (aproximadamente)
/*!40000 ALTER TABLE `aula_relacionadas` DISABLE KEYS */;
INSERT INTO `aula_relacionadas` (`id`, `aula_id`, `aula_relacionada_id`, `ordem`, `created_at`, `updated_at`) VALUES
	(1, 1, 3, 1, '2021-05-05 00:20:07', '2021-05-05 00:20:07'),
	(2, 6, 4, 1, '2021-05-05 00:21:18', '2021-05-05 00:21:18'),
	(4, 2, 5, 1, '2021-05-05 00:24:46', '2021-05-05 00:24:46'),
	(5, 9, 7, 1, '2021-05-24 12:40:12', '2021-05-24 12:40:12'),
	(6, 13, 13, 1, '2021-05-24 16:33:57', '2021-05-24 16:33:57'),
	(7, 13, 12, 1, '2021-05-24 16:34:15', '2021-05-24 16:34:15');
/*!40000 ALTER TABLE `aula_relacionadas` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.avaliacaos
CREATE TABLE IF NOT EXISTS `avaliacaos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `modulo_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `avaliacao_uk` (`modulo_id`,`user_id`) USING BTREE,
  KEY `FK_avaliacaos_users` (`user_id`),
  KEY `FK_avaliacaos_modulos` (`modulo_id`) USING BTREE,
  CONSTRAINT `FK_avaliacaos_modulos` FOREIGN KEY (`modulo_id`) REFERENCES `modulos` (`id`),
  CONSTRAINT `FK_avaliacaos_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.avaliacaos: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `avaliacaos` DISABLE KEYS */;
INSERT INTO `avaliacaos` (`id`, `modulo_id`, `user_id`, `created_at`, `updated_at`) VALUES
	(1, 1, 2, '2021-05-05 13:18:41', '2021-05-05 13:18:41'),
	(2, 2, 2, '2021-05-05 13:19:50', '2021-05-05 13:19:50'),
	(3, 5, 2, '2021-05-24 12:48:33', '2021-05-24 12:48:33'),
	(4, 6, 2, '2021-05-24 12:50:42', '2021-05-24 12:50:42'),
	(5, 7, 2, '2021-05-27 00:00:07', '2021-05-27 00:00:07');
/*!40000 ALTER TABLE `avaliacaos` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.banners
CREATE TABLE IF NOT EXISTS `banners` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parceiro_id` bigint(20) unsigned DEFAULT NULL,
  `path_banner` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_banner` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_video` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` enum('B','V') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'B',
  `ordem` int(11) DEFAULT NULL,
  `qtd_views` int(11) DEFAULT NULL,
  `qtd_clicks` int(11) DEFAULT NULL,
  `status` enum('A','I') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'I',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `banners_parceiro_id_foreign` (`parceiro_id`),
  CONSTRAINT `banners_parceiro_id_foreign` FOREIGN KEY (`parceiro_id`) REFERENCES `parceiros` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.banners: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
INSERT INTO `banners` (`id`, `parceiro_id`, `path_banner`, `url_banner`, `url_video`, `tipo`, `ordem`, `qtd_views`, `qtd_clicks`, `status`, `created_at`, `updated_at`) VALUES
	(2, NULL, 'banner_2_1620177210.jpg', 'https://google.com/', NULL, 'B', 1, 84, 3, 'I', '2021-05-04 23:49:38', '2021-05-24 22:31:53'),
	(3, 1, NULL, NULL, 'https://www.youtube.com/embed/pcRyE20UI0o', 'V', 2, 146, NULL, 'A', '2021-05-05 00:29:49', '2021-05-27 00:00:54');
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.banner_modulos
CREATE TABLE IF NOT EXISTS `banner_modulos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `modulo_id` bigint(20) unsigned NOT NULL,
  `parceiro_id` bigint(20) unsigned DEFAULT NULL,
  `path_banner` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_banner` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ordem` int(11) DEFAULT NULL,
  `qtd_views` int(11) DEFAULT NULL,
  `qtd_clicks` int(11) DEFAULT NULL,
  `status` enum('A','I') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'I',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `banner_modulos_modulo_id_foreign` (`modulo_id`),
  KEY `banner_modulos_parceiro_id_foreign` (`parceiro_id`),
  CONSTRAINT `banner_modulos_modulo_id_foreign` FOREIGN KEY (`modulo_id`) REFERENCES `modulos` (`id`),
  CONSTRAINT `banner_modulos_parceiro_id_foreign` FOREIGN KEY (`parceiro_id`) REFERENCES `parceiros` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.banner_modulos: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `banner_modulos` DISABLE KEYS */;
INSERT INTO `banner_modulos` (`id`, `modulo_id`, `parceiro_id`, `path_banner`, `url_banner`, `ordem`, `qtd_views`, `qtd_clicks`, `status`, `created_at`, `updated_at`) VALUES
	(1, 5, NULL, 'banner_modulo_1621860093.jpg', 'https://adm.k9famali.com.br/', 1, 5, NULL, 'A', '2021-05-24 12:41:33', '2021-05-26 02:00:29'),
	(2, 5, NULL, 'banner_modulo_1621860255.jpg', 'https://adm.k9famali.com.br/', 1, 4, NULL, 'A', '2021-05-24 12:44:15', '2021-05-26 02:00:29'),
	(5, 7, NULL, 'banner_modulo_1621894974.png', 'https://love.doghero.com.br/', 1, 7, NULL, 'A', '2021-05-24 22:22:54', '2021-05-26 23:59:52'),
	(6, 7, 1, 'banner_modulo_1621895014.png', 'https://www.petlove.com.br/aplicativo', 2, 7, NULL, 'A', '2021-05-24 22:23:34', '2021-05-26 23:59:52'),
	(7, 12, NULL, 'banner_modulo_1621898146.png', 'https://love.doghero.com.br/', 1, 3, NULL, 'A', '2021-05-24 23:15:46', '2021-05-24 23:33:27');
/*!40000 ALTER TABLE `banner_modulos` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.categorias
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.categorias: ~6 rows (aproximadamente)
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` (`id`, `nome`, `created_at`, `updated_at`) VALUES
	(1, 'Comportamentos Caninos', '2021-05-04 23:53:30', '2021-05-24 18:30:01'),
	(2, 'Empreendimento Canino', '2021-05-04 23:58:41', '2021-05-24 18:33:45'),
	(13, 'Enriquecimento Ambiental', '2021-05-24 14:43:38', '2021-05-24 18:35:08'),
	(21, 'Gasto Energético', '2021-05-24 14:45:41', '2021-05-24 18:40:02'),
	(22, 'Saúde Animal', '2021-05-24 14:46:05', '2021-05-24 18:36:04'),
	(27, 'Base do Aprendizado', '2021-05-24 14:47:47', '2021-05-24 18:44:04'),
	(31, 'Como Funciona a Plataforma', '2021-05-24 18:50:29', '2021-05-24 18:50:29');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.cursos
CREATE TABLE IF NOT EXISTS `cursos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nivel_id` bigint(20) unsigned NOT NULL,
  `categoria_id` bigint(20) unsigned NOT NULL,
  `assinatura` enum('F','K') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('A','I') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'I',
  `nome` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resumo` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `path_imagem` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cursos_nivel_id_foreign` (`nivel_id`),
  KEY `cursos_categoria_id_foreign` (`categoria_id`),
  CONSTRAINT `cursos_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  CONSTRAINT `cursos_nivel_id_foreign` FOREIGN KEY (`nivel_id`) REFERENCES `nivels` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.cursos: ~27 rows (aproximadamente)
/*!40000 ALTER TABLE `cursos` DISABLE KEYS */;
INSERT INTO `cursos` (`id`, `nivel_id`, `categoria_id`, `assinatura`, `status`, `nome`, `resumo`, `descricao`, `path_imagem`, `created_at`, `updated_at`) VALUES
	(1, 2, 1, 'F', 'A', 'Ansiedade de Separação', 'Um dos problemas que aumentou e aumentara muito Pós-Pandemia. Ela tem relação a varios fatores relacionados em sua rotina. Quer saber resolver?', '<p style="margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px;">A ansiedade acontece....</p>', 'curso_1621893766.jpg', '2021-05-05 00:02:01', '2021-05-24 22:02:47'),
	(2, 2, 22, 'F', 'A', 'Anti-Veneno', 'Ouvimos várias histórias de clientes que perderam seus cães envenenados. Não sabemos o motivo, mas queremos que nunca mais aconteça. Saiba mais.', '<p style="margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px;">Essas aulas infelizmente não protegera seus cães 100%. Mas te dara conhecimento para evitar quase 90% de comer algo. Ou voce sera capaz de prevenir ou ate reverter.</p>', 'curso_1621893958.jpg', '2021-05-05 00:12:25', '2021-05-24 22:53:40'),
	(3, 1, 22, 'F', 'A', 'Adoção Responsável', 'Um assunto sério como adoção, não poderia ficar de fora das nossas aulas. Ajudaremos receber esse cãozinho no novo lar da melhor forma. Saiba mais.', '<p style="text-align: justify;"><font color="#000000" face="Open Sans, Arial, sans-serif"><span style="font-size: 14px;">A adoção...</span></font></p>', 'curso_1621893498.jpg', '2021-05-24 12:33:06', '2021-05-24 21:58:18'),
	(4, 3, 2, 'F', 'A', 'Divulgações Area Pet', 'Vamos imaginar que voce já trabalhe com cães, independente da area Pet. Mas gostaria de se destacar nas Redes Sociais com suas publicações. Saiba mais', 'Nesse curso voce ira aprender sobre varios conteúdos', 'curso_1621868555.jpg', '2021-05-24 15:02:35', '2021-05-24 18:37:27'),
	(5, 1, 2, 'F', 'A', 'Brinquedos / Acessórios / Equipamentos', 'Hoje em dia no mercado pet existe varios tipos de brinquedos, acessórios e equipamento. Iremos analisar varios deles e ensinar como usa-los.', '<p><p>Hoje em dia com tantas atualizações. Como saber o que se adequa melhor ao meu cão?</p><br></p>', 'curso_1621894117.jpg', '2021-05-24 22:08:37', '2021-05-24 22:08:37'),
	(6, 2, 1, 'F', 'A', 'Cães Agressivos ou Medrosos', 'Para ser sincero a maioria das agressividades estão relacionadas ao medo. Vamos te ensinar a interpretar e direcionar o cão ao seu equilíbrio natural.', '<p>Esse assunto é duvidas de muita gente. Sera que ele é agressivo porque esta com medo ou ele é dominante agressivo?</p>', 'curso_1621901582.jpg', '2021-05-25 00:13:02', '2021-05-25 00:13:03'),
	(7, 3, 21, 'F', 'A', 'Cães de Alta Performace', 'Treinamentos que melhorarão a aptidão fisica e mental de seu cão. Construindo um cão saudável e equilibrado durante toda a semana. Saiba mais.', '<p>Podendo ser mais avançado para algumas pessoas. Mas existe treinos específicos para varios tipos de cães e com objetivos diferentes.</p>', 'curso_1621901731.jpg', '2021-05-25 00:15:31', '2021-05-25 00:15:32'),
	(8, 1, 22, 'F', 'A', 'Cães & Gatos', 'Cada animal tem o seu temperamento. Não é porque são espécies diferentes que não podem conviver em harmonia. Aprenda como melhorar esse convivio.', 'Sera que existe tanta guerra entre eles como tantos falam? Acreditamos que conseguimos amenizar ou ate deixa-los grandes amigos.', 'curso_1621901950.jpg', '2021-05-25 00:19:10', '2021-05-25 00:19:10'),
	(9, 1, 1, 'F', 'A', 'Coco e Xixi', 'Cansado de tentar direciona-lo para o banheiro correto? Ou de ficar limpando toda casa todos os dias? Na verdade, é mais facil quanto parece.', 'Talvez seja um dos comportamentos mais procurados a se adequar.', 'curso_1621902651.jpg', '2021-05-25 00:30:51', '2021-05-25 00:30:51'),
	(10, 3, 2, 'F', 'A', 'Consultoria Comportamental', 'Identifique comportamentos indesejados no seu cão e aprenda a direciona-lo. Podendo fazer isso para ajudar outras pessoas. Aprenda e ajude alguem.', '<p>Identificar o problema, entender porque ele ocorre e aprender como resolver.</p>', 'curso_1621902801.jpg', '2021-05-25 00:33:21', '2021-05-25 00:57:27'),
	(11, 1, 27, 'F', 'A', 'Crianças e seu Primeiro Cão', 'Tudo que voce precisa saber sobre: Apresentar um novo bebê ao cão ou preparar as crianças para receber seu primeiro cãozinho. Saiba mais.', '<p>Como poderíamos orientar as crianças antes de receber um cãozinho? Ou como ensina-los desde de cedo a direcionar o cão?</p>', 'curso_1621903100.jpg', '2021-05-25 00:38:20', '2021-05-25 00:38:21'),
	(12, 2, 1, 'F', 'A', 'Destruidor de Objetos', 'Esses comportamentos estão relacionados a várias coisas. Desde de instintos naturais a condicionamentos errôneos. Veja como direciona-los.', '<p>Ele ja comeu as plantas do jardim, o para-choque do carro, os moveis da sala, o sofá, o que mais poderia comer?</p>', 'curso_1621903306.jpg', '2021-05-25 00:41:46', '2021-05-25 00:41:46'),
	(13, 2, 13, 'F', 'A', 'Esconde- Esconde', 'Um exercício ótimo para aguçar os instintos naturais do cão, de forma lúdica e divertida. Usando um dos melhores sentidos que eles possuem, o olfato.', '<p>O olfato do cão é um dos sentidos mais importantes que eles tem. E treina-los podera ser uma ótima brincadeira tanto para voce como para ele. Além de ser um ótimo exercício de descontração e gastar muita energia acumulada.</p>', 'curso_1621903585.jpg', '2021-05-25 00:46:25', '2021-05-25 00:46:25'),
	(14, 1, 1, 'F', 'A', 'Filhotes', 'Como são fofos e adoráveis. E se os direcionar de forma correta o aprendizado sera tão facil e divertido para ambos. Se antecipe e aprenda como.', '<p>Filhotinhos são tão fofos. Mas se não se precaver as fases de seu desenvolvimento, sera dificil passa-las sem perder a cabeça.&nbsp;</p>', 'curso_1621903775.jpg', '2021-05-25 00:49:35', '2021-05-25 00:49:35'),
	(15, 2, 1, 'F', 'A', 'Fugas pelo Portão', 'Quantas Histórias ouvimos de cães que fugiram pelo portão e foram atropelados. Ou ate se perderam e nunca mais voltaram. Iremos acabar com isso.', '<p>Super importante saber controlar a ansiedade do cão sair correndo sempre quando ve um portão abrindo. Ja ouvimos varios relatos de cães que fugiram e foram atropelados. Ou ate mesmo que nunca mais voltaram.&nbsp;</p>', 'curso_1621903964.jpg', '2021-05-25 00:52:44', '2021-05-25 00:52:45'),
	(16, 3, 1, 'F', 'A', 'Guarda Territorial', 'A maioria dos cães são territorialistas, protegendo onde vivem contra estranhos. Iremos te ensinar a despertar, direcionar e controlar esse instinto.', '<p>Essa guarda já é feita quase que instintivamente pelos cães.&nbsp;</p>', 'curso_1621904122.jpg', '2021-05-25 00:55:22', '2021-05-25 00:55:22'),
	(17, 1, 13, 'F', 'A', 'Enriquecimento Ambiental', 'Saiba como melhorar o ambiente do seu cão o distraindo diariamente, trabalhando todos os sentidos que eles possuem. O deixando assim mais entretido.', 'Esses métodos além de distrair o cão e gastar sua energia o ajuda a trabalhar todos os sentidos naturais.', 'curso_1621904473.jpg', '2021-05-25 01:01:13', '2021-05-25 01:01:13'),
	(18, 1, 1, 'F', 'A', 'Latidos Excessivos', 'O cão latir é natural. Mas tantas vezes para qualquer coisa, pode ser exagero. Vamos te ajudar analisar esses latidos e ensina-lo a diminuir.', '<p>Que o Cão late, nos já sabíamos. Mas sera que tantos latidos em excesso não quer me mostrar algo de errado?</p>', 'curso_1621904635.jpg', '2021-05-25 01:03:55', '2021-05-25 01:03:55'),
	(19, 3, 22, 'F', 'A', 'Terapias Alternativas', 'Hoje já existem varias terapias alternativas tanto para prevenir como para tratar certos problemas nos cães.', '<p>Ja vimos em varios lugares que as Terapias Alternativas são inúmeras. Mas voce sabe quais são e para que serve. E mais. Não precisa estar doente para faze-las, elas podem ser usadas para relaxamento e aumentar a longevidade dos cães.</p>', 'curso_1621904911.jpg', '2021-05-25 01:08:31', '2021-05-25 01:08:31'),
	(20, 3, 21, 'F', 'A', 'Natação Dog', 'Um dos exercícios mais recomendados por não ter impactos e trabalhar todo o corpo. O cão já sabe nadar! Mas porque não prepara-lo para salvar nadando?', '<p>"Nunca se coloque em risco para salvar alguem". Esse é uma mensagem do Corpo de Bombeiros. Vamos mostrar aqui como salvar um cão que esta se afogando ou melhor. Como ensinar ele nadar. Pois não são todos que sabem ou tem tanta resistência para isso.</p>', 'curso_1621905172.jpg', '2021-05-25 01:12:52', '2021-05-25 01:12:52'),
	(21, 1, 21, 'F', 'A', 'Passeios', 'Já imaginou passear com seu cão sem ser arrastado por ele? Ensinaremos a melhorar isso e ao mesmo tempo gastar a energia dele de forma certa.', '<p>Um dos melhores treinos para gastar a energia de seu cão. Proporcionando a ele mais saúde, bem estar e tranquilidade.&nbsp;</p>', 'curso_1621905401.jpg', '2021-05-25 01:16:41', '2021-05-25 01:16:42'),
	(22, 3, 22, 'F', 'A', 'Primeiros Socorros Caninos', 'Voce aprendera o básico de procedimentos para eventuais acidentes. Sabendo como proceder ate levar em seu Veterinário de confiança. Saiba mais.', '<p>Te ensinaremos tudo que podera ser útil um dia. Tanto em sua maleta de primeiros socorros como procedimentos.</p>', 'curso_1621905555.jpg', '2021-05-25 01:19:15', '2021-05-25 01:19:15'),
	(23, 1, 1, 'F', 'A', 'Raças e S/Raças', 'Vamos mostrar as características de cães SRD até outras raças. Mostrando que determinado comportamento podera estar relacionado a sua genética.', '<p>Vamos conhecer juntos as raças mais conhecidas. Sabendo assim o motivo de terem tanta energia.</p>', 'curso_1621905708.jpg', '2021-05-25 01:21:48', '2021-05-25 01:21:48'),
	(24, 3, 21, 'F', 'A', 'Treinando Juntos', 'Já pensou em se exercitar junto com seu Pet? Vamos te ensinar a fazer alguns exercícios em parceria com ele, deixando mais divertido e saudável.', '<p>Quantas vezes fui correr com meu cão e tropecei? Ou quase passei por cima dele andando de bike.<br>Vamos aqui passar métodos de como fazer isso sem que se matem...rs</p>', 'curso_1621905865.jpg', '2021-05-25 01:24:25', '2021-05-25 01:24:25'),
	(25, 1, 27, 'F', 'A', 'Truques e Comandos', 'Esses exercícios trabalham para que o cão associe um comando a uma resposta desejada. Melhorando a concentração, agilidade e obediência.', '<p>Vamos aprender a fazer varios comandos que serviram de base para outros movimentos mais complexos.</p>', 'curso_1621906119.jpg', '2021-05-25 01:28:39', '2021-05-25 01:28:39'),
	(26, 3, 22, 'F', 'A', 'TV Dog', 'Canais para deixar seu cão distraído enquanto voce fica fora de casa.', '<p>Esses canais são configurados com imagens bonitas e sons relaxantes.</p>', 'curso_1621906333.jpg', '2021-05-25 01:32:13', '2021-05-25 01:32:13'),
	(27, 3, 27, 'F', 'A', 'Base Teórica da Aprendizagem Canina', 'Ensinamentos embasados na pesquisa científica e na historia.', '<p>XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXxx</p>', 'curso_1621906545.jpg', '2021-05-25 01:35:45', '2021-05-25 01:35:45'),
	(28, 3, 2, 'F', 'A', 'Banho e Tosa', 'Como dar um simples banho ate trabalhar em um Petshop.', '<p>Como que da banho corretamente? Ou fazer uma tosa higiênica.</p>', 'curso_1621906667.jpg', '2021-05-25 01:37:47', '2021-05-25 01:37:48'),
	(29, 3, 22, 'F', 'A', 'Entrevista com Especialistas', 'Varias entrevistas com especialistas sobre varios assuntos importantes para seu cão.', '<p>Entrevistas sobre doenças.</p>', 'curso_1621907016.jpg', '2021-05-25 01:43:36', '2021-05-25 01:43:36'),
	(30, 3, 2, 'F', 'A', 'Empreendimento Canino', 'Quais são os Empreendimento no ramo Pet?', 'Conheça alguns dos Negócios rentáveis na area Pet.&nbsp;', 'curso_1621907291.jpg', '2021-05-25 01:48:11', '2021-05-25 01:48:11'),
	(31, 1, 31, 'F', 'A', 'Como Funciona a Plataforma', 'Todas as duvidas que estiver sobre a Plataforma Club Famali voce pode tirar aqui. Temos varios videos aulas ensinando cada detalhe.', '<p>Varios video ensinando cada canto da plataforma.&nbsp;</p>', 'curso_1621944225.jpg', '2021-05-25 12:03:45', '2021-05-25 12:03:45');
/*!40000 ALTER TABLE `cursos` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.curso_realizados
CREATE TABLE IF NOT EXISTS `curso_realizados` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `curso_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `situacao` enum('I','F') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'I',
  `data_inicio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `data_fim` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `curso_realizado_uk` (`curso_id`,`user_id`),
  KEY `curso_realizados_user_id_foreign` (`user_id`),
  CONSTRAINT `curso_realizados_curso_id_foreign` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`),
  CONSTRAINT `curso_realizados_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.curso_realizados: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `curso_realizados` DISABLE KEYS */;
INSERT INTO `curso_realizados` (`id`, `curso_id`, `user_id`, `situacao`, `data_inicio`, `data_fim`, `created_at`, `updated_at`) VALUES
	(2, 1, 2, 'F', '2021-05-05 10:19:50', '2021-05-05 13:19:50', '2021-05-05 13:15:36', '2021-05-05 13:19:50'),
	(3, 2, 2, 'I', '2021-05-05 10:19:06', NULL, '2021-05-05 13:19:06', '2021-05-05 13:19:06'),
	(4, 3, 2, 'F', '2021-05-24 09:50:42', '2021-05-24 12:50:42', '2021-05-24 12:38:15', '2021-05-24 12:50:42'),
	(5, 4, 2, 'I', '2021-05-24 13:22:57', NULL, '2021-05-24 16:22:57', '2021-05-24 16:22:57');
/*!40000 ALTER TABLE `curso_realizados` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.failed_jobs: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.lives
CREATE TABLE IF NOT EXISTS `lives` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resumo` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_live` date NOT NULL,
  `hora_live` time NOT NULL,
  `path_live` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_live` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aovivo` enum('S','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `ordem` int(11) DEFAULT NULL,
  `status` enum('A','I') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'I',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.lives: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `lives` DISABLE KEYS */;
INSERT INTO `lives` (`id`, `nome`, `resumo`, `data_live`, `hora_live`, `path_live`, `url_live`, `aovivo`, `ordem`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'Nova Live', 'Resumo da Live', '2021-05-05', '11:34:00', 'live_1_1620221702.png', 'https://www.youtube.com/', 'S', 1, 'I', '2021-05-05 13:35:02', '2021-05-24 16:40:57'),
	(2, 'Live sobre Cães Adotados', 'djhasdjabduah', '2021-05-12', '14:45:00', 'live_2_1621874597.jpg', 'https://www.youtube.com/watch?v=Ynemo7cs1RU', 'N', 1, 'A', '2021-05-24 16:43:17', '2021-05-25 22:04:38');
/*!40000 ALTER TABLE `lives` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.migrations: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.moderacaos
CREATE TABLE IF NOT EXISTS `moderacaos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `post` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_post` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `replica` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_replica` timestamp NULL DEFAULT NULL,
  `situacao` enum('N','R','L') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `moderacaos_user_id_foreign` (`user_id`),
  CONSTRAINT `moderacaos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.moderacaos: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `moderacaos` DISABLE KEYS */;
/*!40000 ALTER TABLE `moderacaos` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.modulos
CREATE TABLE IF NOT EXISTS `modulos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `curso_id` bigint(20) unsigned NOT NULL,
  `nome` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordem` int(11) NOT NULL,
  `status` enum('A','I') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'I',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `modulo_uk` (`curso_id`,`nome`),
  CONSTRAINT `modulos_curso_id_foreign` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.modulos: ~37 rows (aproximadamente)
/*!40000 ALTER TABLE `modulos` DISABLE KEYS */;
INSERT INTO `modulos` (`id`, `curso_id`, `nome`, `ordem`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Módulo Numero Um', 1, 'A', '2021-05-05 00:02:22', '2021-05-05 13:25:06'),
	(2, 1, 'Módulo Numero 2', 2, 'A', '2021-05-05 00:02:34', '2021-05-05 13:25:06'),
	(3, 2, 'Tipos de Venenos', 1, 'A', '2021-05-05 00:12:39', '2021-05-24 23:10:39'),
	(4, 2, 'Precauções', 2, 'A', '2021-05-05 00:12:49', '2021-05-24 23:11:01'),
	(5, 3, 'Modulo 1 do novo curso', 1, 'A', '2021-05-24 12:33:52', '2021-05-24 12:34:48'),
	(6, 3, 'Modulo 2 do novo curso', 2, 'A', '2021-05-24 12:34:06', '2021-05-24 12:34:48'),
	(7, 4, 'Como Fazer Art no Canva', 1, 'A', '2021-05-24 15:05:25', '2021-05-24 15:05:25'),
	(8, 4, 'Como editar videos no KineMaster', 2, 'A', '2021-05-24 15:06:33', '2021-05-24 15:06:33'),
	(9, 4, 'Como Impulsionar no Facebook', 2, 'I', '2021-05-24 16:25:53', '2021-05-24 21:28:52'),
	(10, 4, 'Criando uma Fanpage no Facebook', 3, 'A', '2021-05-24 16:26:41', '2021-05-24 21:28:26'),
	(11, 2, 'Vasculhando o Perimentro', 2, 'A', '2021-05-24 23:12:05', '2021-05-24 23:12:05'),
	(12, 2, 'Treinando o Cão', 3, 'A', '2021-05-24 23:12:47', '2021-05-24 23:12:47'),
	(13, 31, 'Tudo sobre a Plataforma', 1, 'A', '2021-05-25 12:05:52', '2021-05-25 15:53:02'),
	(19, 30, 'PETSHOP', 1, 'A', '2021-05-25 22:16:51', '2021-05-25 22:16:51'),
	(20, 30, 'HOTEL CANINO', 2, 'A', '2021-05-25 22:17:11', '2021-05-25 22:17:11'),
	(21, 30, 'ADESTRAMENTO', 3, 'A', '2021-05-25 22:17:25', '2021-05-25 22:17:25'),
	(22, 30, 'CRECHE CANINA (DAYCARE)', 4, 'A', '2021-05-25 22:17:54', '2021-05-25 22:17:54'),
	(23, 30, 'CUIDADORA (PETSISTER)', 5, 'A', '2021-05-25 22:18:23', '2021-05-25 22:18:23'),
	(24, 30, 'CLINICA VETERINARIA', 6, 'A', '2021-05-25 22:18:47', '2021-05-25 22:18:47'),
	(25, 30, 'PASSEADOR DE CÃES (DOGWALKER)', 7, 'A', '2021-05-25 22:20:11', '2021-05-25 22:20:11'),
	(26, 30, 'BANHO E TOSA', 8, 'A', '2021-05-25 22:21:44', '2021-05-25 22:21:44'),
	(27, 29, 'OFTALMO', 1, 'A', '2021-05-25 22:23:32', '2021-05-25 22:23:32'),
	(28, 29, 'ORTOPEDISTA', 2, 'A', '2021-05-25 22:24:00', '2021-05-25 22:24:00'),
	(29, 29, 'DERMATOLOGISTA', 3, 'A', '2021-05-25 22:24:29', '2021-05-25 22:24:29'),
	(30, 29, 'FISIOTERAPEUTA', 4, 'A', '2021-05-25 22:24:53', '2021-05-25 22:24:53'),
	(31, 28, 'BANHOS', 1, 'A', '2021-05-25 22:25:32', '2021-05-25 22:25:32'),
	(32, 28, 'TOSA', 2, 'A', '2021-05-25 22:25:55', '2021-05-25 22:25:55'),
	(33, 26, 'VIDEO DE 1h', 1, 'A', '2021-05-25 22:27:00', '2021-05-25 22:27:00'),
	(34, 26, 'VIDEO DE 6h', 2, 'A', '2021-05-25 22:27:36', '2021-05-25 22:27:36'),
	(35, 26, 'VIDEO DE 8h', 3, 'A', '2021-05-25 22:27:58', '2021-05-25 22:27:58'),
	(36, 26, 'VIDEO DE 12h', 4, 'A', '2021-05-25 22:28:20', '2021-05-25 22:28:20'),
	(37, 25, 'Formas de Ensinar: SENTA', 1, 'A', '2021-05-25 22:28:58', '2021-05-25 22:28:58'),
	(38, 25, 'Formas de Ensinar: DEITA', 2, 'A', '2021-05-25 22:32:49', '2021-05-25 22:32:49'),
	(39, 25, 'Formas de Ensinar: DA PATA', 3, 'A', '2021-05-25 22:33:31', '2021-05-25 22:33:31'),
	(40, 25, 'Formas de Ensinar: EM PÉ', 4, 'A', '2021-05-25 22:34:06', '2021-05-25 22:34:06'),
	(41, 25, 'Formas de Ensinar: SOBE X DESCE', 5, 'A', '2021-05-25 22:34:36', '2021-05-25 22:34:36'),
	(42, 25, 'Formas de Ensinar: GIRA X VOLTA', 6, 'A', '2021-05-25 22:35:08', '2021-05-25 22:35:08'),
	(43, 25, 'Formas de Ensinar: ABRAÇO', 7, 'A', '2021-05-25 22:36:04', '2021-05-25 22:36:04'),
	(44, 25, 'Formas de Ensinar: PULA', 8, 'A', '2021-05-25 22:36:34', '2021-05-25 22:36:34'),
	(45, 25, 'Introdução', 0, 'A', '2021-05-25 22:37:13', '2021-05-26 01:01:43'),
	(46, 24, 'Introdução', 0, 'A', '2021-05-26 01:45:34', '2021-05-26 01:45:34'),
	(47, 24, 'Caminhando junto com Cão.', 1, 'A', '2021-05-26 01:46:21', '2021-05-26 01:46:21'),
	(48, 24, 'Correndo junto com o Cão.', 2, 'A', '2021-05-26 01:46:45', '2021-05-26 01:46:45'),
	(49, 24, 'Andando de Bike junto com o Cão.', 3, 'A', '2021-05-26 01:47:30', '2021-05-26 01:47:30'),
	(50, 24, 'Nadando junto com o Cão.', 4, 'A', '2021-05-26 01:47:51', '2021-05-26 01:47:51');
/*!40000 ALTER TABLE `modulos` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.nivels
CREATE TABLE IF NOT EXISTS `nivels` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.nivels: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `nivels` DISABLE KEYS */;
INSERT INTO `nivels` (`id`, `nome`, `created_at`, `updated_at`) VALUES
	(1, '1. Iniciante', '2021-05-04 23:58:21', '2021-05-24 14:54:12'),
	(2, '2. Intermediário', '2021-05-04 23:58:28', '2021-05-24 14:54:32'),
	(3, '3. Avançado', '2021-05-24 14:31:34', '2021-05-24 14:54:43');
/*!40000 ALTER TABLE `nivels` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.notificacaos
CREATE TABLE IF NOT EXISTS `notificacaos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `titulo` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` varchar(2000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prazo_dia` int(11) NOT NULL DEFAULT '1',
  `data_inicio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('A','I') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'I',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.notificacaos: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `notificacaos` DISABLE KEYS */;
INSERT INTO `notificacaos` (`id`, `titulo`, `descricao`, `prazo_dia`, `data_inicio`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'Título da notificação aqui', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam suscipit magna sed viverra egestas. Proin sed ante tincidunt turpis finibus fringilla nec ut mauris.', 5, '2021-04-20 00:00:00', 'A', '2021-05-05 00:34:37', '2021-05-24 12:25:28'),
	(2, 'Nova Notificação', 'Aqui vamos inserir a descrição da notificação.', 1, '2021-05-05 00:00:00', 'A', '2021-05-05 13:23:00', '2021-05-05 13:23:00');
/*!40000 ALTER TABLE `notificacaos` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.parceiros
CREATE TABLE IF NOT EXISTS `parceiros` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cpf_cnpj` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` enum('PF','PJ') COLLATE utf8mb4_unicode_ci NOT NULL,
  `missao` varchar(3000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_cep` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_cidade` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_uf` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_logradouro` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_numero` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_bairro` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_complemento` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('A','I') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'I',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.parceiros: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `parceiros` DISABLE KEYS */;
INSERT INTO `parceiros` (`id`, `nome`, `cpf_cnpj`, `tipo`, `missao`, `end_cep`, `end_cidade`, `end_uf`, `end_logradouro`, `end_numero`, `end_bairro`, `end_complemento`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'PETS', '01221300000164', 'PJ', 'CDSCSDCSC', '86047560', 'Londrina', 'PR', 'Rua Antero de Quental', '32', 'Conjunto Residencial Vivendas do Arvoredo', 'CASA', 'A', '2021-05-24 20:56:33', '2021-05-24 20:56:33'),
	(2, 'VETNIL', '02233125000145', 'PJ', 'CDSCSDCSDCSD', '86047560', 'Londrina', 'PR', 'Rua Antero de Quental', '23', 'Conjunto Residencial Vivendas do Arvoredo', NULL, 'A', '2021-05-24 23:27:03', '2021-05-24 23:27:03');
/*!40000 ALTER TABLE `parceiros` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.password_resets: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.perguntas
CREATE TABLE IF NOT EXISTS `perguntas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `modulo_id` bigint(20) unsigned NOT NULL,
  `descricao` varchar(3000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordem` int(11) NOT NULL,
  `status` enum('A','I') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'I',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `perguntas_modulo_id_foreign` (`modulo_id`),
  CONSTRAINT `perguntas_modulo_id_foreign` FOREIGN KEY (`modulo_id`) REFERENCES `modulos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.perguntas: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `perguntas` DISABLE KEYS */;
INSERT INTO `perguntas` (`id`, `modulo_id`, `descricao`, `ordem`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Pergunta de exemplo para questionario', 1, 'A', '2021-05-05 00:23:30', '2021-05-05 00:23:30'),
	(2, 2, 'Pergunta de Exemplo', 1, 'A', '2021-05-05 00:25:18', '2021-05-05 00:25:18'),
	(3, 5, 'Aqui a pergunta numero 1', 1, 'A', '2021-05-24 12:45:20', '2021-05-24 12:45:20'),
	(4, 5, 'Pergunta 2 do questionario', 1, 'A', '2021-05-24 12:45:51', '2021-05-24 12:45:51'),
	(5, 6, 'Pergunta 1', 1, 'A', '2021-05-24 12:49:39', '2021-05-24 12:49:39'),
	(6, 7, 'Como fazer para cadastrar?', 1, 'A', '2021-05-24 16:30:05', '2021-05-24 16:30:05'),
	(7, 12, 'Por que o cão não deve comer coisas do chão?', 1, 'A', '2021-05-24 23:16:29', '2021-05-24 23:16:29');
/*!40000 ALTER TABLE `perguntas` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.permissions: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'view_administrador', 'Acessar e gerenciar as informações do sistema como um todo', '2021-04-01 14:10:31', '2021-04-01 14:10:31'),
	(2, 'view_assinante', 'Acessar e gerenciar as informações do sistema como um todo', '2021-04-01 14:10:31', '2021-04-01 14:10:31');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.permission_role
CREATE TABLE IF NOT EXISTS `permission_role` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permission_role_permission_id_role_id_unique` (`permission_id`,`role_id`),
  KEY `permission_role_role_id_foreign` (`role_id`),
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`),
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.permission_role: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `permission_role` DISABLE KEYS */;
INSERT INTO `permission_role` (`id`, `permission_id`, `role_id`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, '2021-04-01 14:10:31', '2021-04-01 14:10:31'),
	(2, 2, 2, '2021-04-01 14:10:31', '2021-04-01 14:10:31');
/*!40000 ALTER TABLE `permission_role` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.plano_treinos
CREATE TABLE IF NOT EXISTS `plano_treinos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `path_plano` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_plano` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordem` int(11) DEFAULT NULL,
  `status` enum('A','I') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'I',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.plano_treinos: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `plano_treinos` DISABLE KEYS */;
INSERT INTO `plano_treinos` (`id`, `nome`, `descricao`, `path_plano`, `url_plano`, `ordem`, `status`, `created_at`, `updated_at`) VALUES
	(2, 'OBEDIÊNCIA 3 X Na Semana', '<p>Esse tipo de treino é para pessoas que consegue contato com o Cão em todas as suas refeições e consegue passear com ele nas Terças e Quinta a Noite.</p>', 'plano_2_1621977931.jpg', 'https://drive.google.com/file/d/194VtZyC3Str-rbcYpy86LyQNwJPGUbnR/view?usp=sharing', 1, 'A', '2021-05-24 23:36:43', '2021-05-25 22:07:34'),
	(3, 'OBEDIÊNCIA 2 X Na Semana', '<p>Este foi inventado quem tem as manhãs livres.</p>', 'plano_3_1621979078.jpg', 'https://drive.google.com/file/d/1OdsUTwUE2Qzlc1njccptzYg1DdDXQhf8/view?usp=sharing', 2, 'A', '2021-05-24 23:37:50', '2021-05-25 22:07:46'),
	(4, 'SÓ FDS', 'Não seria um dos mais indicados. Mas pela correria do dia a dia, temos que ir se adaptando. Mas continuando as atividades físicas com seu cão a noite. Podera ir treinando os comandos de obediência nos Fim de Semana.', 'plano_4_1621979579.jpg', 'https://drive.google.com/file/d/1NzSDNXFcnIMIDjjb7D_L8sfWUAH6Eur6/view?usp=sharing', 3, 'A', '2021-05-25 21:52:59', '2021-05-25 22:08:21'),
	(5, 'GASTO ENERGÉTICO FULL', '<p>Recomendado para quem tem Cães Hiperativos, que não se cansam facil. O jeito é utilizar o tempo com ele para cansa-lo o máximo que conseguir.&nbsp;</p>', 'plano_5_1621980109.jpg', 'https://drive.google.com/file/d/1BmY7lDr13Mq-jhW2EZErClaUKI5RDj_s/view?usp=sharing', 4, 'A', '2021-05-25 22:01:49', '2021-05-25 22:08:35');
/*!40000 ALTER TABLE `plano_treinos` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.respostas
CREATE TABLE IF NOT EXISTS `respostas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pergunta_id` bigint(20) unsigned NOT NULL,
  `descricao` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `correta` enum('S','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `ordem` int(11) NOT NULL,
  `status` enum('A','I') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'I',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `respostas_pergunta_id_foreign` (`pergunta_id`),
  CONSTRAINT `respostas_pergunta_id_foreign` FOREIGN KEY (`pergunta_id`) REFERENCES `perguntas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.respostas: ~18 rows (aproximadamente)
/*!40000 ALTER TABLE `respostas` DISABLE KEYS */;
INSERT INTO `respostas` (`id`, `pergunta_id`, `descricao`, `correta`, `ordem`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Resposta Errada', 'N', 1, 'A', '2021-05-05 00:24:00', '2021-05-05 00:24:00'),
	(2, 1, 'Resposta Certa', 'S', 2, 'A', '2021-05-05 00:24:00', '2021-05-05 00:24:00'),
	(3, 1, 'Resposta Errada', 'N', 3, 'A', '2021-05-05 00:24:00', '2021-05-05 00:24:00'),
	(4, 2, 'Resposta Certa', 'S', 1, 'A', '2021-05-05 00:26:09', '2021-05-05 00:26:09'),
	(5, 2, 'Resposta Errada', 'N', 2, 'A', '2021-05-05 00:26:09', '2021-05-05 00:26:09'),
	(6, 2, 'Resposta Errada', 'N', 3, 'A', '2021-05-05 00:26:09', '2021-05-05 00:26:09'),
	(7, 3, 'Aqui voce coloca a resposta 1', 'S', 1, 'A', '2021-05-24 12:46:37', '2021-05-24 12:46:37'),
	(8, 3, 'Aqui voce coloca a resposta 2', 'N', 2, 'A', '2021-05-24 12:46:37', '2021-05-24 12:46:37'),
	(9, 3, 'Aqui voce coloca a resposta 3', 'N', 3, 'A', '2021-05-24 12:46:37', '2021-05-24 12:46:37'),
	(10, 4, 'Aqui a resposta 1', 'N', 1, 'A', '2021-05-24 12:47:05', '2021-05-24 12:47:05'),
	(11, 4, 'Aqui a resposta 2', 'N', 2, 'A', '2021-05-24 12:47:05', '2021-05-24 12:47:05'),
	(12, 4, 'Aqui a resposta 3', 'S', 3, 'A', '2021-05-24 12:47:05', '2021-05-24 12:47:05'),
	(13, 5, 'Pergunta teste', 'S', 1, 'A', '2021-05-24 12:50:00', '2021-05-24 12:50:00'),
	(14, 5, 'Pergunta teste', 'N', 2, 'A', '2021-05-24 12:50:00', '2021-05-24 12:50:00'),
	(15, 5, 'Pergunta teste', 'N', 3, 'A', '2021-05-24 12:50:00', '2021-05-24 12:50:00'),
	(16, 6, 'Entre no canva', 'S', 1, 'A', '2021-05-24 16:30:43', '2021-05-24 16:30:43'),
	(17, 6, 'Entre no face', 'N', 2, 'A', '2021-05-24 16:30:43', '2021-05-24 16:30:43'),
	(18, 6, 'Nenhuma', 'N', 3, 'A', '2021-05-24 16:30:43', '2021-05-24 16:30:43');
/*!40000 ALTER TABLE `respostas` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.roles: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'Gestor', 'Gestor do sistema Famali', '2021-04-01 14:10:31', '2021-04-01 14:10:31'),
	(2, 'Assinante', 'Assinante do sistema Famali', '2021-04-01 14:10:31', '2021-04-01 14:10:31');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.role_user
CREATE TABLE IF NOT EXISTS `role_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `assinatura` enum('F','K','G') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('A','I') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'I',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_user_user_id_unique` (`user_id`),
  KEY `role_user_role_id_foreign` (`role_id`),
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.role_user: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `role_user` DISABLE KEYS */;
INSERT INTO `role_user` (`id`, `role_id`, `user_id`, `assinatura`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 'G', 'A', '2021-04-01 14:10:31', '2021-04-01 14:10:31'),
	(2, 2, 2, 'F', 'A', '2021-04-01 14:10:31', '2021-04-01 14:10:31'),
	(3, 2, 3, 'K', 'A', '2021-04-01 14:10:31', '2021-04-01 14:10:31'),
	(4, 2, 4, 'F', 'A', '2021-04-01 14:10:31', '2021-04-01 14:10:31');
/*!40000 ALTER TABLE `role_user` ENABLE KEYS */;

-- Copiando estrutura para tabela k9fama99_gestao.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path_avatar` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cpf` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_nascimento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `telefone_ddd` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefone` varchar(9) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_cep` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_cidade` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_uf` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_logradouro` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_numero` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_bairro` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_complemento` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela k9fama99_gestao.users: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `email`, `path_avatar`, `cpf`, `data_nascimento`, `telefone_ddd`, `telefone`, `end_cep`, `end_cidade`, `end_uf`, `end_logradouro`, `end_numero`, `end_bairro`, `end_complemento`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Gestor', 'gestor@famali.com.br', NULL, '111', '2010-01-01 00:00:00', '043', '111', '86047560', 'Londrina', 'PR', 'Rua Antero de Quental', '52', 'Conjunto Residencial Vivendas do Arvoredo', '', NULL, '$2y$10$TnnJQEJwfqkU8QG/z37uD.xLtAfLxPu/YkMBZ5a063kk8pRKZyfzG', NULL, '2021-04-01 14:10:31', '2021-04-01 14:10:31'),
	(2, 'Joao Lucas Canuto Cidade', 'famali@famali.com.br', NULL, '33333333309', '2021-04-27 14:00:28', '043', '333', '86047560', 'Londrina', 'PR', 'Rua Antero de Quental', '52', 'Conjunto Residencial Vivendas do Arvoredo', NULL, NULL, '$2y$10$zJhBT4VBdUIHyttRbtgfC.w7YJ5j4ZK9G3tDqvg2Iu420BeNuqt8G', NULL, '2021-04-01 14:10:31', '2021-04-25 17:32:43'),
	(3, 'assinante k9', 'k9@famali.com.br', NULL, '444', '2010-04-04 00:00:00', '043', '444', '86047560', 'Londrina', 'PR', 'Rua Antero de Quental', '52', 'Conjunto Residencial Vivendas do Arvoredo', '', NULL, '$2y$10$UvcC6855OL1FLJWPWTAfOOFU33hXl.EDQJU7kL9c6Cis/9UtTuITy', NULL, '2021-04-01 14:10:31', '2021-04-01 14:10:31'),
	(4, 'outro famali', 'outro@famali.com.br', NULL, '222', '2021-04-03 20:57:55', '043', '111', '86047560', 'Londrina', 'PR', 'Rua Antero de Quental', '52', 'Conjunto Residencial Vivendas do Arvoredo', NULL, NULL, '$2y$10$TnnJQEJwfqkU8QG/z37uD.xLtAfLxPu/YkMBZ5a063kk8pRKZyfzG', NULL, '2021-04-01 14:10:31', '2021-04-01 14:10:31');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

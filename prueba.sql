/*
Navicat MySQL Data Transfer

Source Server         : Laragon - Server
Source Server Version : 50724
Source Host           : localhost:3306
Source Database       : prueba

Target Server Type    : MYSQL
Target Server Version : 50724
File Encoding         : 65001

Date: 2020-05-31 05:07:19
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for guias
-- ----------------------------
DROP TABLE IF EXISTS `guias`;
CREATE TABLE `guias` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `productos_id` bigint(20) unsigned NOT NULL,
  `numero_guia` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guias_numero_guia_unique` (`numero_guia`),
  KEY `guias_productos_id_foreign` (`productos_id`),
  CONSTRAINT `guias_productos_id_foreign` FOREIGN KEY (`productos_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of guias
-- ----------------------------
INSERT INTO `guias` VALUES ('1', '1', 'P0001', 'Guia AC', '2020-05-31 08:58:56', '2020-05-31 08:58:56');
INSERT INTO `guias` VALUES ('2', '3', 'P0002', 'Guia CA', '2020-05-31 09:00:33', '2020-05-31 09:05:09');
INSERT INTO `guias` VALUES ('3', '2', 'P0003', 'Guia PA', '2020-05-31 09:01:14', '2020-05-31 09:05:22');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('25', '2020_05_30_222401_create_usuarios_table', '1');
INSERT INTO `migrations` VALUES ('26', '2020_05_30_222458_create_productos_table', '1');
INSERT INTO `migrations` VALUES ('27', '2020_05_30_222460_create_guias_table', '1');

-- ----------------------------
-- Table structure for productos
-- ----------------------------
DROP TABLE IF EXISTS `productos`;
CREATE TABLE `productos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of productos
-- ----------------------------
INSERT INTO `productos` VALUES ('1', 'Pollo', '14', '2020-05-31 08:58:34', '2020-05-31 09:04:23');
INSERT INTO `productos` VALUES ('2', 'Pescado', '89', '2020-05-31 09:04:09', '2020-05-31 09:04:09');
INSERT INTO `productos` VALUES ('3', 'Cerdo', '50', '2020-05-31 09:04:48', '2020-05-31 09:04:48');

-- ----------------------------
-- Table structure for usuarios
-- ----------------------------
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `documento` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuarios_documento_unique` (`documento`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of usuarios
-- ----------------------------
INSERT INTO `usuarios` VALUES ('1', '987654321', 'Pedro', 'Perez', '2020-05-31 09:02:06', '2020-05-31 09:02:06');
INSERT INTO `usuarios` VALUES ('2', '908776543', 'Luis', 'Luna', '2020-05-31 09:02:43', '2020-05-31 09:02:43');
INSERT INTO `usuarios` VALUES ('3', '876543210', 'Jose', 'Lozada', '2020-05-31 09:03:24', '2020-05-31 09:03:38');

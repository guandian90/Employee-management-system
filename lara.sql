/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50726
Source Host           : localhost:3306
Source Database       : lara

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2025-03-11 10:22:46
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for jobs
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of jobs
-- ----------------------------

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('1', '2014_10_12_000000_create_users_table', '1');
INSERT INTO `migrations` VALUES ('2', '2014_10_12_100000_create_password_resets_table', '1');
INSERT INTO `migrations` VALUES ('3', '2019_08_19_000000_create_failed_jobs_table', '1');
INSERT INTO `migrations` VALUES ('4', '2019_12_14_000001_create_personal_access_tokens_table', '1');
INSERT INTO `migrations` VALUES ('5', '2025_03_07_050336_create_jobs_table', '1');
INSERT INTO `migrations` VALUES ('7', '2025_03_10_052610_create_questionnaires_table', '1');
INSERT INTO `migrations` VALUES ('12', '2025_03_10_052641_create_questions_table', '2');
INSERT INTO `migrations` VALUES ('17', '2025_03_10_053501_add_current_step_fields_to_users_table', '3');
INSERT INTO `migrations` VALUES ('16', '2025_03_10_052730_create_user_step_progress_table', '3');
INSERT INTO `migrations` VALUES ('15', '2025_03_10_052703_create_user_answers_table', '3');
INSERT INTO `migrations` VALUES ('14', '2025_03_10_052536_create_steps_table', '3');
INSERT INTO `migrations` VALUES ('18', '2025_03_10_151206_add_role_to_users_table', '3');

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of password_resets
-- ----------------------------

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for questionnaires
-- ----------------------------
DROP TABLE IF EXISTS `questionnaires`;
CREATE TABLE `questionnaires` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `step_id` bigint(20) unsigned NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `questionnaires_step_id_foreign` (`step_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of questionnaires
-- ----------------------------
INSERT INTO `questionnaires` VALUES ('1', '1', '1', null, null);
INSERT INTO `questionnaires` VALUES ('2', '2', '1', null, null);
INSERT INTO `questionnaires` VALUES ('3', '3', '1', null, null);

-- ----------------------------
-- Table structure for questions
-- ----------------------------
DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `questionnaire_id` bigint(20) unsigned NOT NULL,
  `question_text` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('single_choice','multiple_choice','short_answer') COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` json DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `questions_questionnaire_id_foreign` (`questionnaire_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of questions
-- ----------------------------
INSERT INTO `questions` VALUES ('1', '1', '您最常使用的编程语言是？', 'single_choice', '[\"Python\", \"JavaScript\", \"Java\", \"PHP\"]', null, null);
INSERT INTO `questions` VALUES ('2', '1', '您熟悉的框架有哪些？', 'multiple_choice', '[\"Laravel\", \"React\", \"Vue\", \"Django\"]', null, null);
INSERT INTO `questions` VALUES ('3', '1', '请描述您的开发经验', 'short_answer', null, '请用100字以内描述', null);
INSERT INTO `questions` VALUES ('4', '1', '您在开发中最常用的工具或IDE是什么？', 'single_choice', '[\"Visual Studio Code\", \"PyCharm\", \"Eclipse\", \"Sublime Text\", \"其他\"]', null, null);
INSERT INTO `questions` VALUES ('5', '1', '您是否参与过开源项目？如果有，请简要说明', 'short_answer', null, '请用100字以内描述', null);
INSERT INTO `questions` VALUES ('6', '2', '您最常用的数据库管理系统是什么？', 'single_choice', '[\"MySQL\", \"PostgreSQL\", \"MongoDB\", \"SQLite\", \"Oracle\"]', null, null);
INSERT INTO `questions` VALUES ('7', '2', '您最常使用的版本控制系统是什么？', 'single_choice', '[\"Git\", \"SVN\", \"Mercurial\", \"其他\"]', null, null);
INSERT INTO `questions` VALUES ('8', '2', '请描述您的项目管理经验', 'short_answer', null, '请用100字以内描述', null);
INSERT INTO `questions` VALUES ('9', '2', '您在团队协作中更倾向于使用哪种沟通工具？', 'single_choice', '[\"Slack\", \"Microsoft Teams\", \"钉钉\", \"微信\", \"其他\"]', null, null);
INSERT INTO `questions` VALUES ('10', '2', '您是否有过移动应用开发的经验？如果有，请简要说明', 'short_answer', null, '请用100字以内描述', null);
INSERT INTO `questions` VALUES ('11', '3', '您最常用的前端框架是什么？', 'single_choice', '[\"React\", \"Vue\", \"Angular\", \"Svelte\", \"其他\"]', null, null);
INSERT INTO `questions` VALUES ('12', '3', '您最常用的后端框架是什么？', 'single_choice', '[\"Django\", \"Flask\", \"Laravel\", \"Spring\", \"其他\"]', null, null);
INSERT INTO `questions` VALUES ('13', '3', '请描述您的测试经验', 'short_answer', null, '请用100字以内描述', null);
INSERT INTO `questions` VALUES ('14', '3', '您在开发过程中遇到的最大挑战是什么？', 'short_answer', null, '请用100字以内描述', null);
INSERT INTO `questions` VALUES ('15', '3', '您是否有过跨平台开发的经验？如果有，请列举您使用的技术栈', 'short_answer', null, '请用100字以内描述', null);

-- ----------------------------
-- Table structure for steps
-- ----------------------------
DROP TABLE IF EXISTS `steps`;
CREATE TABLE `steps` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `resource_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resource_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of steps
-- ----------------------------
INSERT INTO `steps` VALUES ('1', '入职第一步', '入职第一步', 'video', 'storage/videos/onboarding.mp4', '1', null, null);
INSERT INTO `steps` VALUES ('2', '入职第二步\r\n', '入职第二步\r\n', 'document', 'storage/doc/sc.pdf', '2', null, null);
INSERT INTO `steps` VALUES ('3', '入职第三步\r\n', '入职第三步\r\n', 'image', 'storage/images/01.jpg', '3', null, null);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `current_step_id` bigint(20) unsigned DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_current_step_id_foreign` (`current_step_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'Prof. Eliza Hettinger Jr.', 'eleanore41@example.net', '3', '2025-03-10 06:56:55', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '2kXtwXk4luT0doVeLKLF1RRcPs7xBsY6pdmVyrSMeDBM2RpQ5CGMW6LoOuYq', '2025-03-10 06:56:55', '2025-03-11 02:12:27');
INSERT INTO `users` VALUES ('2', 'Gerardo Donnelly', 'thiel.nadia@example.com', '1', '2025-03-10 06:56:56', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Hajx7G7UnbZEFJrtBZByOGpu2Zo9n1L2ITjMwrDb137gOZ0ly5FluHMGxI57', '2025-03-10 06:56:56', '2025-03-10 15:29:14');
INSERT INTO `users` VALUES ('3', 'aaaaa', '123456@163.com', '0', null, '$2y$10$4GpHEQuqi.S2VJ5RW6Lz3OgEUZhfSPnF3DEAzJefRKK/SJV0ihQU.', 'user', null, '2025-03-11 02:21:55', '2025-03-11 02:21:55');

-- ----------------------------
-- Table structure for user_answers
-- ----------------------------
DROP TABLE IF EXISTS `user_answers`;
CREATE TABLE `user_answers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `question_id` bigint(20) unsigned NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_answers_user_id_foreign` (`user_id`),
  KEY `user_answers_question_id_foreign` (`question_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of user_answers
-- ----------------------------
INSERT INTO `user_answers` VALUES ('1', '1', '1', 'JavaScript', '2025-03-11 02:05:10', '2025-03-11 02:05:10');
INSERT INTO `user_answers` VALUES ('2', '1', '2', '[\"Laravel\",\"React\"]', '2025-03-11 02:05:10', '2025-03-11 02:05:10');
INSERT INTO `user_answers` VALUES ('3', '1', '3', '1111', '2025-03-11 02:05:10', '2025-03-11 02:05:10');
INSERT INTO `user_answers` VALUES ('4', '1', '4', 'Visual Studio Code', '2025-03-11 02:05:10', '2025-03-11 02:05:10');
INSERT INTO `user_answers` VALUES ('5', '1', '5', '11', '2025-03-11 02:05:10', '2025-03-11 02:05:10');
INSERT INTO `user_answers` VALUES ('6', '1', '6', 'MongoDB', '2025-03-11 02:11:35', '2025-03-11 02:11:35');
INSERT INTO `user_answers` VALUES ('7', '1', '7', 'SVN', '2025-03-11 02:11:35', '2025-03-11 02:11:35');
INSERT INTO `user_answers` VALUES ('8', '1', '8', '3333', '2025-03-11 02:11:35', '2025-03-11 02:11:35');
INSERT INTO `user_answers` VALUES ('9', '1', '9', '微信', '2025-03-11 02:11:35', '2025-03-11 02:11:35');
INSERT INTO `user_answers` VALUES ('10', '1', '10', '333', '2025-03-11 02:11:35', '2025-03-11 02:11:35');
INSERT INTO `user_answers` VALUES ('11', '1', '11', 'Angular', '2025-03-11 02:12:27', '2025-03-11 02:12:27');
INSERT INTO `user_answers` VALUES ('12', '1', '12', 'Flask', '2025-03-11 02:12:27', '2025-03-11 02:12:27');
INSERT INTO `user_answers` VALUES ('13', '1', '13', '222222', '2025-03-11 02:12:27', '2025-03-11 02:12:27');
INSERT INTO `user_answers` VALUES ('14', '1', '14', '2222222', '2025-03-11 02:12:27', '2025-03-11 02:12:27');
INSERT INTO `user_answers` VALUES ('15', '1', '15', '222222', '2025-03-11 02:12:27', '2025-03-11 02:12:27');

-- ----------------------------
-- Table structure for user_step_progress
-- ----------------------------
DROP TABLE IF EXISTS `user_step_progress`;
CREATE TABLE `user_step_progress` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `step_id` bigint(20) unsigned NOT NULL,
  `progress_percent` decimal(5,2) DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_step_progress_user_id_step_id_unique` (`user_id`,`step_id`),
  KEY `user_step_progress_step_id_foreign` (`step_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of user_step_progress
-- ----------------------------
INSERT INTO `user_step_progress` VALUES ('1', '1', '1', null, '2025-03-11 02:05:10', '2025-03-11 02:05:10', '2025-03-11 02:05:10');
INSERT INTO `user_step_progress` VALUES ('2', '1', '2', null, '2025-03-11 02:11:35', '2025-03-11 02:11:35', '2025-03-11 02:11:35');
INSERT INTO `user_step_progress` VALUES ('3', '1', '3', null, '2025-03-11 02:12:27', '2025-03-11 02:12:27', '2025-03-11 02:12:27');

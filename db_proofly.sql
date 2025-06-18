/*
 Navicat Premium Data Transfer

 Source Server         : Unioss Local
 Source Server Type    : MySQL
 Source Server Version : 80042 (8.0.42)
 Source Host           : localhost:2336
 Source Schema         : db_proofly

 Target Server Type    : MySQL
 Target Server Version : 80042 (8.0.42)
 File Encoding         : 65001

 Date: 18/06/2025 10:03:32
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for assignments
-- ----------------------------
DROP TABLE IF EXISTS `assignments`;
CREATE TABLE `assignments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `class_id` bigint UNSIGNED NOT NULL,
  `teacher_id` bigint UNSIGNED NOT NULL,
  `assignment_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `assignments_class_id_index`(`class_id` ASC) USING BTREE,
  INDEX `assignments_teacher_id_index`(`teacher_id` ASC) USING BTREE,
  INDEX `assignments_assignment_date_index`(`assignment_date` ASC) USING BTREE,
  CONSTRAINT `assignments_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `assignments_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of assignments
-- ----------------------------
INSERT INTO `assignments` VALUES (1, 1, 1, '2025-06-02', '2025-05-30 07:20:48', '2025-05-30 07:20:49', NULL);
INSERT INTO `assignments` VALUES (2, 2, 2, '2025-06-24', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `assignments` VALUES (3, 3, 3, '2025-06-10', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `assignments` VALUES (4, 4, 3, '2025-06-05', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `assignments` VALUES (5, 5, 4, '2025-06-13', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `assignments` VALUES (6, 6, 5, '2025-06-04', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `assignments` VALUES (7, 7, 5, '2025-06-10', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `assignments` VALUES (8, 8, 6, '2025-06-09', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `assignments` VALUES (9, 9, 6, '2025-06-21', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `assignments` VALUES (10, 10, 7, '2025-06-20', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `assignments` VALUES (11, 11, 8, '2025-06-14', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `assignments` VALUES (12, 12, 9, '2025-06-13', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `assignments` VALUES (13, 13, 9, '2025-06-06', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `assignments` VALUES (14, 14, 10, '2025-06-14', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `assignments` VALUES (15, 15, 10, '2025-06-27', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);

-- ----------------------------
-- Table structure for classes
-- ----------------------------
DROP TABLE IF EXISTS `classes`;
CREATE TABLE `classes`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject_id` bigint UNSIGNED NOT NULL,
  `teacher_id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `room_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NULL DEFAULT NULL,
  `end_date` date NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `classes_subject_id_foreign`(`subject_id` ASC) USING BTREE,
  INDEX `classes_teacher_id_foreign`(`teacher_id` ASC) USING BTREE,
  CONSTRAINT `classes_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `classes_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of classes
-- ----------------------------
INSERT INTO `classes` VALUES (1, 9, 1, 'Class exercitationem', '856136', '2025-06-09', '2025-09-09', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `classes` VALUES (2, 7, 2, 'Class et', '711704', '2025-06-15', '2025-07-25', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `classes` VALUES (3, 9, 3, 'Class voluptatem', '424798', '2025-06-14', '2025-09-07', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `classes` VALUES (4, 3, 3, 'Class sunt', '776665', '2025-06-15', '2025-08-05', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `classes` VALUES (5, 1, 4, 'Class odio', '854337', '2025-07-14', '2025-08-24', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `classes` VALUES (6, 6, 5, 'Class quae', '950153', '2025-07-24', '2025-11-14', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `classes` VALUES (7, 2, 5, 'Class voluptate', '923113', '2025-06-15', '2025-11-23', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `classes` VALUES (8, 7, 6, 'Class ut', '790767', '2025-07-28', '2025-10-11', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `classes` VALUES (9, 12, 6, 'Class delectus', '446560', '2025-06-28', '2025-07-01', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `classes` VALUES (10, 5, 7, 'Class neque', '966175', '2025-06-24', '2025-08-02', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `classes` VALUES (11, 7, 8, 'Class sequi', '943752', '2025-06-23', '2025-08-05', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `classes` VALUES (12, 9, 9, 'Class repellat', '961043', '2025-06-02', '2025-09-04', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `classes` VALUES (13, 11, 9, 'Class est', '169601', '2025-06-26', '2025-07-10', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `classes` VALUES (14, 9, 10, 'Class a', '409691', '2025-07-13', '2025-11-06', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `classes` VALUES (15, 7, 10, 'Class cumque', '939290', '2025-07-14', '2025-10-10', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);

-- ----------------------------
-- Table structure for document_batches
-- ----------------------------
DROP TABLE IF EXISTS `document_batches`;
CREATE TABLE `document_batches`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `media_id` bigint UNSIGNED NOT NULL,
  `media_path_id` bigint UNSIGNED NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `metadata` json NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of document_batches
-- ----------------------------

-- ----------------------------
-- Table structure for documents
-- ----------------------------
DROP TABLE IF EXISTS `documents`;
CREATE TABLE `documents`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `exam_id` bigint UNSIGNED NULL DEFAULT NULL,
  `class_id` bigint UNSIGNED NULL DEFAULT NULL,
  `subject_id` bigint UNSIGNED NOT NULL,
  `uploaded_by` bigint UNSIGNED NOT NULL,
  `media_id` bigint UNSIGNED NULL DEFAULT NULL,
  `batch_id` bigint UNSIGNED NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `original_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `metadata` json NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `documents_class_id_foreign`(`class_id` ASC) USING BTREE,
  INDEX `documents_subject_id_foreign`(`subject_id` ASC) USING BTREE,
  INDEX `documents_uploaded_by_foreign`(`uploaded_by` ASC) USING BTREE,
  INDEX `documents_exam_id_foreign`(`exam_id` ASC) USING BTREE,
  CONSTRAINT `documents_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `documents_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `documents_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `documents_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of documents
-- ----------------------------
INSERT INTO `documents` VALUES (1, NULL, 15, 1, 1, 1, NULL, 'completed', 'kubernetes.docx', 'hhh', NULL, '2025-06-09 05:40:23', '2025-06-09 05:40:38', NULL);
INSERT INTO `documents` VALUES (2, NULL, 13, 1, 1, 2, NULL, 'completed', 'docker.docx', 'Docker', NULL, '2025-06-09 05:59:24', '2025-06-09 05:59:30', NULL);
INSERT INTO `documents` VALUES (3, NULL, 14, 1, 1, 3, NULL, 'failed', 'docker.pdf', NULL, NULL, '2025-06-10 10:53:08', '2025-06-10 10:53:15', NULL);
INSERT INTO `documents` VALUES (4, NULL, 15, 1, 1, 4, NULL, 'failed', 'docker-1749552854.pdf', NULL, NULL, '2025-06-10 10:54:16', '2025-06-10 10:54:19', NULL);
INSERT INTO `documents` VALUES (5, NULL, 14, 1, 1, 4, NULL, 'failed', 'docker-1749552854.pdf', NULL, NULL, '2025-06-10 11:03:29', '2025-06-10 11:03:36', NULL);
INSERT INTO `documents` VALUES (6, NULL, 9, 1, 1, 4, NULL, 'processing', 'docker-1749552854.pdf', NULL, NULL, '2025-06-10 11:05:53', '2025-06-10 11:05:53', NULL);
INSERT INTO `documents` VALUES (7, NULL, 15, 1, 1, 4, NULL, 'completed', 'docker-1749552854.pdf', NULL, NULL, '2025-06-10 11:06:11', '2025-06-10 11:06:23', NULL);
INSERT INTO `documents` VALUES (8, NULL, 14, 1, 1, 5, NULL, 'completed', 'kubernetes.pdf', NULL, NULL, '2025-06-10 11:08:55', '2025-06-10 11:09:01', NULL);
INSERT INTO `documents` VALUES (9, NULL, 14, 1, 1, 6, NULL, 'completed', 'using-docker-or-kubernetes.pdf', NULL, NULL, '2025-06-10 11:31:41', '2025-06-10 11:31:44', NULL);

-- ----------------------------
-- Table structure for enrollments
-- ----------------------------
DROP TABLE IF EXISTS `enrollments`;
CREATE TABLE `enrollments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `class_id` bigint UNSIGNED NOT NULL,
  `enrollment_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `enrollments_student_id_class_id_unique`(`student_id` ASC, `class_id` ASC) USING BTREE,
  INDEX `enrollments_class_id_foreign`(`class_id` ASC) USING BTREE,
  INDEX `enrollments_enrollment_date_index`(`enrollment_date` ASC) USING BTREE,
  CONSTRAINT `enrollments_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `enrollments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 70 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of enrollments
-- ----------------------------
INSERT INTO `enrollments` VALUES (1, 1, 1, '2025-05-14', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (2, 1, 6, '2025-05-13', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (3, 1, 9, '2025-05-02', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (4, 2, 3, '2025-05-03', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (5, 2, 15, '2025-05-28', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (6, 3, 3, '2025-05-16', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (7, 3, 12, '2025-05-14', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (8, 4, 5, '2025-05-06', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (9, 5, 1, '2025-05-18', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (10, 5, 9, '2025-05-01', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (11, 6, 8, '2025-05-25', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (12, 6, 13, '2025-05-27', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (13, 7, 2, '2025-05-22', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (14, 7, 10, '2025-05-08', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (15, 8, 7, '2025-05-15', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (16, 8, 8, '2025-05-07', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (17, 8, 11, '2025-05-12', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (18, 9, 1, '2025-05-24', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (19, 9, 3, '2025-05-18', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (20, 10, 4, '2025-05-21', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (21, 10, 5, '2025-05-08', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (22, 11, 10, '2025-05-10', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (23, 11, 13, '2025-05-25', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (24, 12, 14, '2025-05-25', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (25, 13, 2, '2025-05-19', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (26, 13, 6, '2025-05-02', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (27, 13, 8, '2025-05-15', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (28, 14, 9, '2025-05-02', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (29, 15, 3, '2025-05-12', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (30, 16, 12, '2025-05-03', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (31, 17, 6, '2025-05-15', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (32, 18, 13, '2025-05-27', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (33, 19, 3, '2025-05-16', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (34, 19, 11, '2025-05-03', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (35, 20, 5, '2025-05-19', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (36, 20, 6, '2025-05-14', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (37, 21, 2, '2025-05-20', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (38, 22, 10, '2025-05-25', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (39, 23, 5, '2025-05-05', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (40, 23, 9, '2025-05-16', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (41, 24, 8, '2025-05-20', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (42, 24, 10, '2025-05-05', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (43, 25, 6, '2025-05-29', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (44, 25, 7, '2025-05-19', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (45, 26, 3, '2025-05-13', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (46, 26, 10, '2025-04-30', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (47, 26, 11, '2025-05-01', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (48, 27, 7, '2025-05-18', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (49, 28, 6, '2025-05-25', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (50, 28, 8, '2025-05-25', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (51, 29, 14, '2025-05-09', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (52, 30, 6, '2025-05-27', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (53, 30, 15, '2025-05-26', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (54, 31, 4, '2025-05-15', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (55, 31, 10, '2025-05-10', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (56, 32, 10, '2025-05-13', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (57, 33, 8, '2025-05-19', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (58, 34, 8, '2025-05-04', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (59, 34, 11, '2025-05-27', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (60, 35, 4, '2025-05-03', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (61, 35, 11, '2025-05-28', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (62, 35, 13, '2025-05-09', '2025-05-30 07:20:49', '2025-05-30 07:20:49', NULL);
INSERT INTO `enrollments` VALUES (63, 36, 14, '2025-05-15', '2025-05-30 07:20:50', '2025-05-30 07:20:50', NULL);
INSERT INTO `enrollments` VALUES (64, 37, 15, '2025-05-12', '2025-05-30 07:20:50', '2025-05-30 07:20:50', NULL);
INSERT INTO `enrollments` VALUES (65, 38, 13, '2025-05-14', '2025-05-30 07:20:50', '2025-05-30 07:20:50', NULL);
INSERT INTO `enrollments` VALUES (66, 39, 6, '2025-05-14', '2025-05-30 07:20:50', '2025-05-30 07:20:50', NULL);
INSERT INTO `enrollments` VALUES (67, 39, 11, '2025-05-03', '2025-05-30 07:20:50', '2025-05-30 07:20:50', NULL);
INSERT INTO `enrollments` VALUES (68, 40, 3, '2025-05-14', '2025-05-30 07:20:50', '2025-05-30 07:20:50', NULL);
INSERT INTO `enrollments` VALUES (69, 40, 9, '2025-05-22', '2025-05-30 07:20:50', '2025-05-30 07:20:50', NULL);

-- ----------------------------
-- Table structure for exams
-- ----------------------------
DROP TABLE IF EXISTS `exams`;
CREATE TABLE `exams`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `class_id` bigint UNSIGNED NOT NULL,
  `teacher_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `start_time` datetime NULL DEFAULT NULL,
  `end_time` datetime NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `exams_class_id_foreign`(`class_id` ASC) USING BTREE,
  INDEX `exams_teacher_id_foreign`(`teacher_id` ASC) USING BTREE,
  CONSTRAINT `exams_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `exams_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of exams
-- ----------------------------
INSERT INTO `exams` VALUES (1, 1, 1, 'Provident velit vitae qui non.', 'Maxime porro sunt sit. Velit nobis ipsam fugit et tenetur voluptas. Accusantium dicta voluptas dolorem alias explicabo.', '2025-08-24 08:18:30', '2025-08-24 09:18:30', '2025-06-17 11:11:50', '2025-06-17 11:11:50', NULL);
INSERT INTO `exams` VALUES (2, 2, 2, 'Consequatur consequatur optio molestiae aut.', 'Nulla rerum enim aut placeat. Est dolorum tempora corporis nisi repellat rerum. Minus et doloribus optio dolorem enim.', '2025-07-07 10:21:41', '2025-07-07 11:21:41', '2025-06-17 11:11:50', '2025-06-17 11:11:50', NULL);
INSERT INTO `exams` VALUES (3, 3, 3, 'Alias voluptatum ut possimus sunt amet.', 'Accusamus nihil facilis qui nesciunt eveniet exercitationem. Dolorem minima tenetur et est unde. Deleniti iure qui velit dolor et doloremque corporis ducimus. Ut sunt reprehenderit aliquid vero aut.', '2025-08-14 17:34:23', '2025-08-14 19:34:23', '2025-06-17 11:11:50', '2025-06-17 11:11:50', NULL);
INSERT INTO `exams` VALUES (4, 3, 3, 'Odit qui voluptatem non officiis tenetur.', 'Tenetur at beatae non rerum nihil perspiciatis. Soluta voluptatum perferendis cupiditate minus quos rerum sed recusandae. Adipisci adipisci tempore rem commodi occaecati enim ducimus. Labore iusto laudantium inventore tenetur impedit. Dolores quia facilis et blanditiis et dolore aut.', '2025-06-19 07:37:56', '2025-06-19 10:37:56', '2025-06-17 11:11:50', '2025-06-17 11:11:50', NULL);
INSERT INTO `exams` VALUES (5, 4, 3, 'Consequatur ipsam et accusamus.', 'Reprehenderit occaecati perspiciatis enim commodi voluptas optio. Atque magnam quos itaque. Nisi facere nulla qui harum eum necessitatibus.', '2025-07-24 05:01:32', '2025-07-24 08:01:32', '2025-06-17 11:11:50', '2025-06-17 11:11:50', NULL);
INSERT INTO `exams` VALUES (6, 5, 4, 'Nobis nemo tenetur.', 'Aliquam sequi ad aliquid voluptatibus dicta aspernatur occaecati. Incidunt ut soluta aut dolore. Ipsam veritatis velit omnis est assumenda est.', '2025-08-03 00:58:37', '2025-08-03 03:58:37', '2025-06-17 11:11:50', '2025-06-17 11:11:50', NULL);
INSERT INTO `exams` VALUES (7, 6, 5, 'Aut quidem enim similique.', 'Quis et quod dolor mollitia dolores. Est est repellendus optio. Ut dolores omnis libero qui laudantium voluptas neque.', '2025-08-28 15:42:15', '2025-08-28 17:42:15', '2025-06-17 11:11:50', '2025-06-17 11:11:50', NULL);
INSERT INTO `exams` VALUES (8, 6, 5, 'Natus qui ut libero quo.', 'Alias odio tempore nisi quia aliquam quod. Aut hic voluptatibus quo nisi et. Aut ducimus enim voluptas vel corrupti ut autem. Culpa atque atque laborum recusandae repudiandae corporis nemo nobis.', '2025-10-13 22:11:55', '2025-10-13 23:11:55', '2025-06-17 11:11:50', '2025-06-17 11:11:50', NULL);
INSERT INTO `exams` VALUES (9, 7, 5, 'Possimus quo ducimus.', 'Consequatur in quaerat libero earum quo provident saepe. Expedita possimus esse voluptas unde accusantium. Explicabo ad dolores quis deserunt.', '2025-06-30 00:24:34', '2025-06-30 03:24:34', '2025-06-17 11:11:50', '2025-06-17 11:11:50', NULL);
INSERT INTO `exams` VALUES (10, 7, 5, 'Accusamus cum quia voluptas non.', 'Quisquam quibusdam perspiciatis quidem ab iste qui. Debitis nihil commodi dolores autem tenetur nemo fugit. Hic nam debitis unde illo expedita. Labore et porro ut veritatis natus temporibus ducimus. Ut aliquid itaque necessitatibus dicta.', '2025-08-31 18:54:22', '2025-08-31 20:54:22', '2025-06-17 11:11:50', '2025-06-17 11:11:50', NULL);
INSERT INTO `exams` VALUES (11, 8, 6, 'Id beatae sed.', 'Deleniti officiis non eius quam dolor quidem qui. Recusandae aut labore molestiae quisquam ad. Ipsum est amet iure deleniti.', '2025-08-23 01:00:31', '2025-08-23 03:00:31', '2025-06-17 11:11:50', '2025-06-17 11:11:50', NULL);
INSERT INTO `exams` VALUES (12, 8, 6, 'Nam consectetur officia dignissimos.', 'Delectus nobis dignissimos libero necessitatibus quo. Ut in animi nostrum maiores. Perspiciatis et ipsa porro enim.', '2025-10-06 22:53:49', '2025-10-07 01:53:49', '2025-06-17 11:11:50', '2025-06-17 11:11:50', NULL);
INSERT INTO `exams` VALUES (13, 9, 6, 'Numquam exercitationem nobis optio.', 'Vero non libero quisquam qui magnam. Fuga fugiat dicta quas corrupti omnis.', '2025-06-28 12:55:28', '2025-06-28 15:55:28', '2025-06-17 11:11:50', '2025-06-17 11:11:50', NULL);
INSERT INTO `exams` VALUES (14, 9, 6, 'Reprehenderit voluptatem officiis similique.', 'Voluptatibus harum ex minus autem. Officia aperiam consequatur inventore laborum voluptatem nihil. Itaque maiores et laborum eos qui.', '2025-06-28 20:58:16', '2025-06-28 23:58:16', '2025-06-17 11:11:50', '2025-06-17 11:11:50', NULL);
INSERT INTO `exams` VALUES (15, 10, 7, 'Sunt facilis at sed vero.', 'Possimus sint sit distinctio deserunt. Quos voluptatem voluptates fuga sed. Saepe doloremque corporis perspiciatis.', '2025-07-11 07:41:46', '2025-07-11 10:41:46', '2025-06-17 11:11:50', '2025-06-17 11:11:50', NULL);
INSERT INTO `exams` VALUES (16, 10, 7, 'Architecto suscipit vero temporibus aliquid.', 'Quasi optio quam minima incidunt totam sint. Repudiandae odio debitis minima qui culpa quisquam quia. Id quisquam quia nesciunt aut omnis est ratione sint.', '2025-06-28 17:07:26', '2025-06-28 18:07:26', '2025-06-17 11:11:50', '2025-06-17 11:11:50', NULL);
INSERT INTO `exams` VALUES (17, 11, 8, 'Molestiae illum vel libero inventore.', 'Consectetur tempore voluptas voluptas ea et sed aperiam dolore. Corporis optio sed laboriosam assumenda. Rem quos similique earum molestiae aut est. Maxime qui totam consequatur quia.', '2025-07-13 03:47:50', '2025-07-13 06:47:50', '2025-06-17 11:11:50', '2025-06-17 11:11:50', NULL);
INSERT INTO `exams` VALUES (18, 12, 9, 'Sunt ea itaque.', 'Enim voluptates odio commodi necessitatibus. Similique perferendis culpa sequi et ea impedit omnis. Quia ea sint ex.', '2025-06-23 04:56:45', '2025-06-23 05:56:45', '2025-06-17 11:11:50', '2025-06-17 11:11:50', NULL);
INSERT INTO `exams` VALUES (19, 12, 9, 'Voluptas et libero.', 'Reprehenderit et suscipit dolorem nesciunt eius. Magnam quaerat rerum consequatur soluta earum. Voluptate eius quia quos saepe veritatis.', '2025-06-20 08:10:27', '2025-06-20 09:10:27', '2025-06-17 11:11:50', '2025-06-17 11:11:50', NULL);
INSERT INTO `exams` VALUES (20, 13, 9, 'Voluptate quia asperiores sunt.', 'Neque veniam quaerat ut. Quaerat excepturi veritatis nobis reiciendis numquam at necessitatibus. Itaque iure totam itaque officiis perspiciatis numquam distinctio aliquid. Dolores ex excepturi deleniti odio molestiae.', '2025-06-30 00:12:08', '2025-06-30 03:12:08', '2025-06-17 11:11:50', '2025-06-17 11:11:50', NULL);
INSERT INTO `exams` VALUES (21, 13, 9, 'Eos minima dolorem.', 'Voluptatem omnis et ut dicta cum. Quisquam eum recusandae unde quis velit nam. Alias veritatis cumque qui tempore repellendus. Minus facere distinctio et ut in dolorum possimus. Consectetur consequatur debitis odit aut eaque magnam aut.', '2025-07-07 04:00:13', '2025-07-07 06:00:13', '2025-06-17 11:11:50', '2025-06-17 11:11:50', NULL);
INSERT INTO `exams` VALUES (22, 14, 10, 'Ratione dicta ea ducimus ut.', 'Quas hic veniam voluptate dolor quis. Quis ex deleniti aut voluptas. Quas aperiam eum expedita. Rem maiores vero repellendus laboriosam aut qui.', '2025-10-12 02:35:13', '2025-10-12 03:35:13', '2025-06-17 11:11:50', '2025-06-17 11:11:50', NULL);
INSERT INTO `exams` VALUES (23, 15, 10, 'Et doloribus accusamus quibusdam consequatur aut.', 'Similique fugiat ab quisquam illum. Quia laudantium qui porro eaque deserunt. Non suscipit omnis illum. Autem reiciendis rem et consequatur qui officia eum.', '2025-08-12 06:52:14', '2025-08-12 09:52:14', '2025-06-17 11:11:50', '2025-06-17 11:11:50', NULL);
INSERT INTO `exams` VALUES (24, 15, 10, 'Commodi sunt repellendus qui dolore inventore.', 'Animi illum quos pariatur ab. Aperiam sed maiores aliquam quia. Temporibus non a nihil quia consequatur doloremque iure. Enim beatae qui incidunt officiis ut est ut.', '2025-07-27 11:36:02', '2025-07-27 12:36:02', '2025-06-17 11:11:50', '2025-06-17 11:11:50', NULL);

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for job_batches
-- ----------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches`  (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `cancelled_at` int NULL DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of job_batches
-- ----------------------------

-- ----------------------------
-- Table structure for jobs
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED NULL DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jobs_queue_index`(`queue` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jobs
-- ----------------------------

-- ----------------------------
-- Table structure for media
-- ----------------------------
DROP TABLE IF EXISTS `media`;
CREATE TABLE `media`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `disk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public',
  `directory` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'media',
  `visibility` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `width` int UNSIGNED NULL DEFAULT NULL,
  `height` int UNSIGNED NULL DEFAULT NULL,
  `size` int UNSIGNED NULL DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'image',
  `ext` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `caption` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `exif` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `curations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of media
-- ----------------------------
INSERT INTO `media` VALUES (1, 'public', 'media', 'public', 'kubernetes', 'media/kubernetes.docx', NULL, NULL, 16743, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'docx', NULL, 'Kubernetes', NULL, NULL, 'null', NULL, '2025-06-09 05:40:21', '2025-06-09 05:40:21');
INSERT INTO `media` VALUES (2, 'public', 'media', 'public', 'docker', 'media/docker.docx', NULL, NULL, 15759, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'docx', NULL, 'Docker', NULL, NULL, 'null', NULL, '2025-06-09 05:59:21', '2025-06-09 05:59:21');
INSERT INTO `media` VALUES (3, 'public', 'media', 'public', 'docker', 'media/docker.pdf', NULL, NULL, 12431, 'application/pdf', 'pdf', NULL, 'Docker', NULL, NULL, 'null', NULL, '2025-06-10 10:53:06', '2025-06-10 10:53:06');
INSERT INTO `media` VALUES (4, 'public', 'media', 'public', 'docker-1749552854', 'media/docker-1749552854.pdf', NULL, NULL, 12431, 'application/pdf', 'pdf', NULL, 'Docker', NULL, NULL, 'null', NULL, '2025-06-10 10:54:14', '2025-06-10 10:54:14');
INSERT INTO `media` VALUES (5, 'public', 'media', 'public', 'kubernetes', 'media/kubernetes.pdf', NULL, NULL, 27795, 'application/pdf', 'pdf', NULL, 'Kubernetes', NULL, NULL, 'null', NULL, '2025-06-10 11:08:53', '2025-06-10 11:08:53');
INSERT INTO `media` VALUES (6, 'public', 'media', 'public', 'using-docker-or-kubernetes', 'media/using-docker-or-kubernetes.pdf', NULL, NULL, 13502, 'application/pdf', 'pdf', NULL, 'Using Docker Or Kubernetes', NULL, NULL, 'null', NULL, '2025-06-10 11:31:39', '2025-06-10 11:31:39');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '2014_10_12_100000_create_password_reset_tokens_table', 1);
INSERT INTO `migrations` VALUES (3, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` VALUES (4, '2019_12_14_000001_create_personal_access_tokens_table', 1);
INSERT INTO `migrations` VALUES (5, '2023_12_20_012855_create_media_table', 1);
INSERT INTO `migrations` VALUES (6, '2025_01_15_072549_create_permission_tables', 1);
INSERT INTO `migrations` VALUES (7, '2025_01_16_055925_add_description_to_roles_table', 1);
INSERT INTO `migrations` VALUES (8, '2025_01_17_054440_add_group_field_into_permissions_table', 1);
INSERT INTO `migrations` VALUES (9, '2025_02_01_141110_add_avatar_to_users_table', 1);
INSERT INTO `migrations` VALUES (10, '2025_02_03_101256_create_students_table', 1);
INSERT INTO `migrations` VALUES (11, '2025_02_03_101307_create_teachers_table', 1);
INSERT INTO `migrations` VALUES (12, '2025_02_03_101310_create_subjects_table', 1);
INSERT INTO `migrations` VALUES (13, '2025_02_03_101313_create_classes_table', 1);
INSERT INTO `migrations` VALUES (14, '2025_02_03_101334_create_enrollments_table', 1);
INSERT INTO `migrations` VALUES (15, '2025_02_03_101348_create_assignments_table', 1);
INSERT INTO `migrations` VALUES (16, '2025_02_04_044447_add_soft_deleted_to_users_table', 1);
INSERT INTO `migrations` VALUES (17, '2025_03_23_000002_create_documents_table', 1);
INSERT INTO `migrations` VALUES (18, '2025_03_23_000003_create_document_batches_table', 1);
INSERT INTO `migrations` VALUES (19, '2025_03_29_060419_create_jobs_table', 1);
INSERT INTO `migrations` VALUES (20, '2025_04_18_161758_create_job_batches_table', 1);
INSERT INTO `migrations` VALUES (21, '2025_04_30_161758_create_plagiarism_checks_table', 1);
INSERT INTO `migrations` VALUES (22, '2025_06_17_173900_create_exams_table', 2);
INSERT INTO `migrations` VALUES (23, '2025_06_17_175234_add_exam_id_to_documents_table', 2);

-- ----------------------------
-- Table structure for model_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE `model_has_permissions`  (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `model_id`, `model_type`) USING BTREE,
  INDEX `model_has_permissions_model_id_model_type_index`(`model_id` ASC, `model_type` ASC) USING BTREE,
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of model_has_permissions
-- ----------------------------

-- ----------------------------
-- Table structure for model_has_roles
-- ----------------------------
DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE `model_has_roles`  (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`, `model_id`, `model_type`) USING BTREE,
  INDEX `model_has_roles_model_id_model_type_index`(`model_id` ASC, `model_type` ASC) USING BTREE,
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of model_has_roles
-- ----------------------------
INSERT INTO `model_has_roles` VALUES (1, 'App\\Models\\User', 1);
INSERT INTO `model_has_roles` VALUES (2, 'App\\Models\\User', 2);
INSERT INTO `model_has_roles` VALUES (2, 'App\\Models\\User', 3);
INSERT INTO `model_has_roles` VALUES (2, 'App\\Models\\User', 4);
INSERT INTO `model_has_roles` VALUES (2, 'App\\Models\\User', 5);
INSERT INTO `model_has_roles` VALUES (2, 'App\\Models\\User', 6);
INSERT INTO `model_has_roles` VALUES (2, 'App\\Models\\User', 7);
INSERT INTO `model_has_roles` VALUES (2, 'App\\Models\\User', 8);
INSERT INTO `model_has_roles` VALUES (2, 'App\\Models\\User', 9);
INSERT INTO `model_has_roles` VALUES (2, 'App\\Models\\User', 10);
INSERT INTO `model_has_roles` VALUES (2, 'App\\Models\\User', 11);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 12);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 13);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 14);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 15);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 16);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 17);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 18);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 19);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 20);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 21);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 22);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 23);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 24);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 25);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 26);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 27);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 28);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 29);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 30);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 31);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 32);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 33);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 34);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 35);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 36);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 37);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 38);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 39);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 40);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 41);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 42);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 43);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 44);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 45);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 46);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 47);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 48);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 49);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 50);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 51);

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of password_reset_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `resource` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `permissions_name_guard_name_unique`(`name` ASC, `guard_name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of permissions
-- ----------------------------

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `personal_access_tokens_token_unique`(`token` ASC) USING BTREE,
  INDEX `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type` ASC, `tokenable_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for plagiarism_checks
-- ----------------------------
DROP TABLE IF EXISTS `plagiarism_checks`;
CREATE TABLE `plagiarism_checks`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `document_id` bigint UNSIGNED NULL DEFAULT NULL,
  `text_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `similarity_score` decimal(5, 2) NOT NULL,
  `confidence_score` tinyint UNSIGNED NOT NULL,
  `matches` json NOT NULL,
  `metadata` json NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `plagiarism_checks_user_id_foreign`(`user_id` ASC) USING BTREE,
  INDEX `plagiarism_checks_document_id_foreign`(`document_id` ASC) USING BTREE,
  CONSTRAINT `plagiarism_checks_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `plagiarism_checks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of plagiarism_checks
-- ----------------------------

-- ----------------------------
-- Table structure for role_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions`  (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `role_id`) USING BTREE,
  INDEX `role_has_permissions_role_id_foreign`(`role_id` ASC) USING BTREE,
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of role_has_permissions
-- ----------------------------

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `roles_name_guard_name_unique`(`name` ASC, `guard_name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, 'admin', 'Administrator with full access to manage the system.', 'web', '2025-05-30 07:20:35', '2025-05-30 07:20:35');
INSERT INTO `roles` VALUES (2, 'teacher', 'Teacher role with permissions to manage students and classes.', 'web', '2025-05-30 07:20:36', '2025-05-30 07:20:36');
INSERT INTO `roles` VALUES (3, 'student', 'Student role with access to view and participate in classes.', 'web', '2025-05-30 07:20:36', '2025-05-30 07:20:36');

-- ----------------------------
-- Table structure for students
-- ----------------------------
DROP TABLE IF EXISTS `students`;
CREATE TABLE `students`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `student_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `enrollment_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `students_student_code_unique`(`student_code` ASC) USING BTREE,
  INDEX `students_user_id_index`(`user_id` ASC) USING BTREE,
  INDEX `students_student_code_index`(`student_code` ASC) USING BTREE,
  INDEX `students_enrollment_date_index`(`enrollment_date` ASC) USING BTREE,
  CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 41 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of students
-- ----------------------------
INSERT INTO `students` VALUES (1, 12, 'STU00012', '2025-01-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (2, 13, 'STU00013', '2024-06-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (3, 14, 'STU00014', '2025-03-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (4, 15, 'STU00015', '2024-10-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (5, 16, 'STU00016', '2024-06-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (6, 17, 'STU00017', '2024-07-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (7, 18, 'STU00018', '2024-10-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (8, 19, 'STU00019', '2024-12-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (9, 20, 'STU00020', '2024-09-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (10, 21, 'STU00021', '2025-04-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (11, 22, 'STU00022', '2024-06-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (12, 23, 'STU00023', '2025-04-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (13, 24, 'STU00024', '2024-10-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (14, 25, 'STU00025', '2025-03-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (15, 26, 'STU00026', '2024-07-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (16, 27, 'STU00027', '2025-01-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (17, 28, 'STU00028', '2024-05-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (18, 29, 'STU00029', '2025-01-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (19, 30, 'STU00030', '2024-11-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (20, 31, 'STU00031', '2024-10-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (21, 32, 'STU00032', '2025-03-02', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (22, 33, 'STU00033', '2024-06-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (23, 34, 'STU00034', '2025-01-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (24, 35, 'STU00035', '2025-01-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (25, 36, 'STU00036', '2025-04-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (26, 37, 'STU00037', '2024-06-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (27, 38, 'STU00038', '2025-04-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (28, 39, 'STU00039', '2024-06-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (29, 40, 'STU00040', '2025-01-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (30, 41, 'STU00041', '2025-01-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (31, 42, 'STU00042', '2024-10-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (32, 43, 'STU00043', '2024-08-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (33, 44, 'STU00044', '2025-04-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (34, 45, 'STU00045', '2024-05-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (35, 46, 'STU00046', '2024-05-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (36, 47, 'STU00047', '2025-03-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (37, 48, 'STU00048', '2024-06-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (38, 49, 'STU00049', '2024-08-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (39, 50, 'STU00050', '2024-12-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `students` VALUES (40, 51, 'STU00051', '2025-03-02', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);

-- ----------------------------
-- Table structure for subjects
-- ----------------------------
DROP TABLE IF EXISTS `subjects`;
CREATE TABLE `subjects`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `subjects_code_unique`(`code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of subjects
-- ----------------------------
INSERT INTO `subjects` VALUES (1, 'CNTT', 'Công nghệ thông tin', 'Chuyên ngành về lập trình, phát triển phần mềm, cơ sở dữ liệu và hệ thống thông tin', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `subjects` VALUES (2, 'KTPM', 'Kỹ thuật phần mềm', 'Chuyên sâu về quy trình phát triển phần mềm, kiểm thử và đảm bảo chất lượng', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `subjects` VALUES (3, 'KHMT', 'Khoa học máy tính', 'Nghiên cứu về thuật toán, trí tuệ nhân tạo và khoa học dữ liệu', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `subjects` VALUES (4, 'QTKD', 'Quản trị kinh doanh', 'Đào tạo về quản lý, marketing và phát triển doanh nghiệp', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `subjects` VALUES (5, 'TCNH', 'Tài chính ngân hàng', 'Chuyên ngành về tài chính, ngân hàng và thị trường chứng khoán', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `subjects` VALUES (6, 'KTKT', 'Kế toán kiểm toán', 'Đào tạo về kế toán, kiểm toán và phân tích tài chính', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `subjects` VALUES (7, 'YDK', 'Y đa khoa', 'Đào tạo bác sĩ đa khoa và chuyên khoa', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `subjects` VALUES (8, 'DH', 'Dược học', 'Nghiên cứu về dược phẩm và phát triển thuốc', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `subjects` VALUES (9, 'DD', 'Điều dưỡng', 'Đào tạo về chăm sóc sức khỏe và hỗ trợ y tế', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `subjects` VALUES (10, 'TKDH', 'Thiết kế đồ họa', 'Chuyên về thiết kế hình ảnh và truyền thông thị giác', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `subjects` VALUES (11, 'KT', 'Kiến trúc', 'Thiết kế kiến trúc và quy hoạch đô thị', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `subjects` VALUES (12, 'TKNT', 'Thiết kế nội thất', 'Thiết kế và trang trí không gian nội thất', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);

-- ----------------------------
-- Table structure for teachers
-- ----------------------------
DROP TABLE IF EXISTS `teachers`;
CREATE TABLE `teachers`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `joined_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `teachers_user_id_index`(`user_id` ASC) USING BTREE,
  INDEX `teachers_joined_date_index`(`joined_date` ASC) USING BTREE,
  CONSTRAINT `teachers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of teachers
-- ----------------------------
INSERT INTO `teachers` VALUES (1, 2, '2024-06-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `teachers` VALUES (2, 3, '2024-11-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `teachers` VALUES (3, 4, '2025-04-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `teachers` VALUES (4, 5, '2024-10-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `teachers` VALUES (5, 6, '2024-05-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `teachers` VALUES (6, 7, '2024-09-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `teachers` VALUES (7, 8, '2024-11-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `teachers` VALUES (8, 9, '2025-04-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `teachers` VALUES (9, 10, '2025-04-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);
INSERT INTO `teachers` VALUES (10, 11, '2024-11-30', '2025-05-30 07:20:48', '2025-05-30 07:20:48', NULL);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` date NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_admin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `profile_updated_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE,
  INDEX `users_last_login_at_index`(`last_login_at` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 52 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'admin@yopmail.com', '$2y$12$I8Y6UQb9mijijDfYUeVnNuE/FQ/jdg.rbQdqALU/STXi.O88vFCuG', 'I am', 'Admin', 'I am Admin', '1995-05-30', '1234567890', 'Admin Address', NULL, '1', NULL, NULL, NULL, NULL, '2025-05-30 07:20:36', '2025-05-30 07:20:36', NULL);
INSERT INTO `users` VALUES (2, 'myah47@example.com', '$2y$12$m8I3wUa2yaKztyIy/mS5UexSevSET5cydNWvmFvu23Of56Njb1Re6', 'Joana', 'Monahan', 'Joana Monahan', '2024-10-14', '+1-623-863-1850', NULL, NULL, '0', NULL, '1971-03-17 15:43:24', '1990-10-06 20:27:55', 'bNY7zvg6bw', '2025-05-30 07:20:36', '2025-05-30 07:20:36', NULL);
INSERT INTO `users` VALUES (3, 'donna78@example.net', '$2y$12$9Etil3evC/Dj7NDVrMk87.jvmQSiUdjcl9LMW9iFIXxko9Ag5PiIm', 'Adelle', 'Watsica', 'Adelle Watsica', '1983-06-18', '+1-540-740-9982', '273 Spencer Ford Apt. 121\nFerryton, IL 04859', NULL, '0', '2000-02-14 18:53:34', NULL, '1997-05-13 01:19:55', 'uoJGH8JzxC', '2025-05-30 07:20:36', '2025-05-30 07:20:36', NULL);
INSERT INTO `users` VALUES (4, 'felipe.grant@example.org', '$2y$12$NP5.HklqM4vnB7FUnaiX6urBPsL2KE552kk7SU4S1ELuteBU.5emC', 'Kian', 'Gulgowski', 'Kian Gulgowski', '2015-05-09', NULL, NULL, NULL, '0', NULL, '1991-02-24 13:42:01', '1987-07-13 13:24:21', 'jh2devsFGO', '2025-05-30 07:20:37', '2025-05-30 07:20:37', NULL);
INSERT INTO `users` VALUES (5, 'obosco@example.org', '$2y$12$yZO7AxT5CZY.NE.d.YuuFe3F4g2XVSYzX6ln9VwxhLl/uv6tl/w3i', 'Shawna', 'Bartoletti', 'Shawna Bartoletti', '2024-02-17', '+1.651.779.9428', '69019 Bennett Flat\nEast Shainahaven, AZ 16938-4830', NULL, '0', NULL, '1984-06-30 00:12:45', '1977-09-03 08:24:29', 'RGtb6vtmba', '2025-05-30 07:20:37', '2025-05-30 07:20:37', NULL);
INSERT INTO `users` VALUES (6, 'elnora30@example.org', '$2y$12$hebk1gPAm3pX7PJ/wVTm1.uyeJKwuyROvztF1uZIHpaGUxQiRLDum', 'Berta', 'Hermiston', 'Berta Hermiston', '2008-07-11', NULL, NULL, NULL, '0', NULL, '1999-02-18 11:57:18', '1984-04-10 05:51:34', 's9YOIhWkJd', '2025-05-30 07:20:37', '2025-05-30 07:20:37', NULL);
INSERT INTO `users` VALUES (7, 'clara35@example.org', '$2y$12$loKoa2FrhNiBXh3kY.8ijeXWCPvXA3ibb/LEdZPjbers.uxxh6eVO', 'Sigrid', 'King', 'Sigrid King', '1993-01-16', NULL, '85462 Ryann Meadow Apt. 343\nNew Zackery, ID 18795', NULL, '0', '2010-11-28 03:00:57', NULL, '2024-11-08 06:50:10', 'WdAtnmwo1y', '2025-05-30 07:20:37', '2025-05-30 07:20:37', NULL);
INSERT INTO `users` VALUES (8, 'kris.godfrey@example.org', '$2y$12$MkhJ9hklCNrpMbtZmOyZ8.RPoiUTIRUKAmWrA9.p/CO7HhRyebGnG', 'Emmy', 'Hoeger', 'Emmy Hoeger', '2005-11-08', NULL, NULL, NULL, '0', '1978-09-20 14:15:24', '1992-04-01 05:47:40', '2011-12-09 00:58:33', 'QAkuoroUrQ', '2025-05-30 07:20:37', '2025-05-30 07:20:37', NULL);
INSERT INTO `users` VALUES (9, 'wcasper@example.net', '$2y$12$WXL4VtFFtne9toMLM.YEyOH9RRe48mZ7oYfvilj.6z.aHrsQMWBjC', 'Lawrence', 'Parisian', 'Lawrence Parisian', '1973-05-04', '+1 (629) 680-8407', '71370 Samson Field\nWest Vernie, OR 37894', NULL, '0', '1997-06-12 04:28:28', '1979-04-29 05:14:08', '1979-03-22 21:15:02', 'gtp8Xd30YBTRaFembvSeCtomSWHF0LgRuzXS3GL5Z5PwmlZYtazUo726zjXJ', '2025-05-30 07:20:38', '2025-05-30 07:20:38', NULL);
INSERT INTO `users` VALUES (10, 'cbeatty@example.org', '$2y$12$P9F/ipYOo5W/UnO0e8hsp.11Yt1cdqF5rBhhMy2P0mIL1ukW3C8.y', 'Elody', 'Strosin', 'Elody Strosin', '1996-10-25', '+1-341-928-0189', NULL, NULL, '0', '2007-09-18 03:02:13', NULL, '2007-08-06 11:29:33', 'OiOk6mdWuC', '2025-05-30 07:20:38', '2025-05-30 07:20:38', NULL);
INSERT INTO `users` VALUES (11, 'yreynolds@example.net', '$2y$12$CA0ypcxyrmzWCPyw9KFXeus.8xWnwDcjhk1R0IAVfYqZTK1JNQbCe', 'Rick', 'Cole', 'Rick Cole', '2017-06-08', '+1-564-773-7173', '338 Adrienne Gateway Suite 732\nWillardville, ND 08115-9576', NULL, '0', '1996-10-14 14:01:11', '2004-06-21 13:56:26', '1985-02-11 17:29:42', 'U1JCvuPg6p', '2025-05-30 07:20:38', '2025-05-30 07:20:38', NULL);
INSERT INTO `users` VALUES (12, 'jschiller@example.com', '$2y$12$fXn3t.dHLcsRZy2J1FCOKeOCJOz60b4hctJXvoYlE1HT8ukvR9nqC', 'Whitney', 'Hane', 'Whitney Hane', '1974-04-19', '424.884.2224', '758 Wintheiser Springs Suite 307\nLake Winston, MI 30820', NULL, '0', '2020-04-03 19:35:45', NULL, NULL, '34rL7yzNUw', '2025-05-30 07:20:38', '2025-05-30 07:20:38', NULL);
INSERT INTO `users` VALUES (13, 'dejah77@example.net', '$2y$12$7D1M2TYyvfQN.crp9ylXLOS0RI420ZZmrZpUamEzH0CaW5/ng8Wfm', 'Axel', 'Kunze', 'Axel Kunze', '2004-01-15', NULL, NULL, NULL, '0', NULL, NULL, '1983-12-18 07:45:09', 'DZJ1AnnpVM', '2025-05-30 07:20:38', '2025-05-30 07:20:38', NULL);
INSERT INTO `users` VALUES (14, 'yzemlak@example.org', '$2y$12$8jEqjyBjd0pKfxGmpVi3Cez.Xtc9.NWTi7qUWdjKXbRFHXWNqee1W', 'Reginald', 'Ritchie', 'Reginald Ritchie', '1985-10-24', '+1 (814) 552-2862', NULL, NULL, '0', NULL, NULL, NULL, 'b00XHFggJF', '2025-05-30 07:20:39', '2025-05-30 07:20:39', NULL);
INSERT INTO `users` VALUES (15, 'dana.stroman@example.net', '$2y$12$7EbvCjodHoLm4K3VEjJGruC5d.yQkA2NrtnHxiI7smoBDRci/6QgC', 'Moriah', 'Considine', 'Moriah Considine', '1977-06-13', NULL, '54597 Marilyne Fords\nNew Antonetteport, MD 74677', NULL, '0', '1978-08-26 05:12:17', '2018-07-28 14:12:11', '1999-11-28 10:23:43', 'QJTZcfycZ7', '2025-05-30 07:20:39', '2025-05-30 07:20:39', NULL);
INSERT INTO `users` VALUES (16, 'schmitt.penelope@example.org', '$2y$12$R7z.iu5K2hQNVnVGEd4hVu3tT647ERH2bxnomifGrHyD4SGed5RRq', 'Rubie', 'Mills', 'Rubie Mills', '2021-05-20', '(573) 561-0119', NULL, NULL, '0', '2024-07-20 08:05:56', '1987-03-13 18:07:33', NULL, 'dNMxhxzjbv', '2025-05-30 07:20:39', '2025-05-30 07:20:39', NULL);
INSERT INTO `users` VALUES (17, 'bo.abernathy@example.com', '$2y$12$f2cJw/crbtzjU9ccVAsJhupa67vZyYu.as3jfxxsbe1o4vjePCDpe', 'Daisy', 'Wolff', 'Daisy Wolff', '2013-02-18', '+15095051479', NULL, NULL, '0', NULL, NULL, NULL, 'LgYwR9U5A3', '2025-05-30 07:20:39', '2025-05-30 07:20:39', NULL);
INSERT INTO `users` VALUES (18, 'phagenes@example.org', '$2y$12$kZcoFGHFSuv8MHwbLMnQF.x/Q.oDUrzuGyYVaFj1DDn/0WJe8jLYu', 'Estella', 'Zboncak', 'Estella Zboncak', '1977-12-20', NULL, NULL, NULL, '0', NULL, NULL, NULL, 'NzF7Gg68lr', '2025-05-30 07:20:40', '2025-05-30 07:20:40', NULL);
INSERT INTO `users` VALUES (19, 'wkub@example.net', '$2y$12$/OcUemSsAl3KRKIBnv4GEuDCoWmkbiS9Tg8mQ3Bcf6jYRKi128x7u', 'Rico', 'Reinger', 'Rico Reinger', '1980-10-03', NULL, '39884 Daisha Gateway Suite 229\nErickabury, GA 86526-3545', NULL, '0', NULL, '1988-05-20 16:59:40', NULL, 'u3BxhN0rIJ', '2025-05-30 07:20:40', '2025-05-30 07:20:40', NULL);
INSERT INTO `users` VALUES (20, 'lacy.mclaughlin@example.net', '$2y$12$HNjqk4efWiY4Q7r/9rio2ucHip/QIjUsSDlU6Ws3WNnebBqrWN0G2', 'Lizeth', 'DuBuque', 'Lizeth DuBuque', '2003-01-31', NULL, '80761 Rosamond Junction Apt. 510\nNew Otholand, IN 52161-4301', NULL, '0', '1973-06-08 22:07:48', '1977-07-06 02:39:47', '1980-08-26 02:51:56', 'nmSdp50alL', '2025-05-30 07:20:40', '2025-05-30 07:20:40', NULL);
INSERT INTO `users` VALUES (21, 'kuhlman.brando@example.org', '$2y$12$nB43K1bhu.gppdOeQ6LSKuhy/WCibwru4uR7e0cvn1UV8zH3FtzgS', 'Karianne', 'Sawayn', 'Karianne Sawayn', '2011-09-21', NULL, NULL, NULL, '0', NULL, '1993-05-30 00:57:31', '1980-08-16 19:24:31', 'DdTXRYh7Kv', '2025-05-30 07:20:40', '2025-05-30 07:20:40', NULL);
INSERT INTO `users` VALUES (22, 'xhills@example.org', '$2y$12$TvYErKybbLFYzgm/kVYZ0ul6XJj7VhqTUmFm5gQcdB369P6adrSHu', 'Dayana', 'Balistreri', 'Dayana Balistreri', '1980-09-18', NULL, NULL, NULL, '0', '1981-08-14 20:20:32', '1971-01-05 14:47:14', NULL, 'uBxOXPKUJy', '2025-05-30 07:20:40', '2025-05-30 07:20:40', NULL);
INSERT INTO `users` VALUES (23, 'marianna.mayert@example.net', '$2y$12$vfvRKzpkTZ792Q3T3r85eugbz0ckCJOyZq/Kw7HetSmoHxXC6G43a', 'Myriam', 'Hane', 'Myriam Hane', '1977-10-13', '1-828-641-6254', '26252 Bradley Glen Apt. 359\nHettingerhaven, MI 49163', NULL, '0', NULL, '2002-07-27 05:14:01', NULL, 'GuXWJFtqqM', '2025-05-30 07:20:41', '2025-05-30 07:20:41', NULL);
INSERT INTO `users` VALUES (24, 'zella82@example.com', '$2y$12$1U3ZI82FLfssveLF8nAJWOqFmag.SfriGcNEplpylFznHXN2VcWgu', 'Fatima', 'Rutherford', 'Fatima Rutherford', '1978-10-01', NULL, NULL, NULL, '0', NULL, '1972-11-20 06:28:45', NULL, 'uDRQ0n0egs', '2025-05-30 07:20:41', '2025-05-30 07:20:41', NULL);
INSERT INTO `users` VALUES (25, 'baumbach.kevon@example.net', '$2y$12$WtKPG6VaXzBeEr4QqM6km.yTNoyRfD2zh5vcLkeKwxtm0OPDen6S2', 'Immanuel', 'Murray', 'Immanuel Murray', '1970-08-31', NULL, '7699 Braxton Park Suite 642\nSouth Ashleestad, MD 95873-8345', NULL, '0', NULL, NULL, '1980-06-13 21:33:51', '9ozxjsJ6th', '2025-05-30 07:20:41', '2025-05-30 07:20:41', NULL);
INSERT INTO `users` VALUES (26, 'hermann.mylene@example.com', '$2y$12$KiYvoYI8Dlt40VMkR6dqsOjnLz3hkrP9LHLS2AbfRTDPXcSDnNRxW', 'Danny', 'Gulgowski', 'Danny Gulgowski', '2018-08-20', NULL, NULL, NULL, '0', '2018-05-22 21:59:35', '2011-11-05 18:24:14', '2013-10-03 20:54:21', 'jAvIDsUVtq', '2025-05-30 07:20:41', '2025-05-30 07:20:41', NULL);
INSERT INTO `users` VALUES (27, 'qmitchell@example.org', '$2y$12$bBKufhyrR/REOpBFudQR9e5TH0u7CRmShwLUKlF2ROXFZeV/sCktO', 'Cicero', 'Jast', 'Cicero Jast', '1997-08-19', NULL, NULL, NULL, '0', '2022-05-24 21:36:29', NULL, '2021-08-04 01:42:17', 'hv3c98WAmg', '2025-05-30 07:20:42', '2025-05-30 07:20:42', NULL);
INSERT INTO `users` VALUES (28, 'kyleigh.grimes@example.com', '$2y$12$n1jmbNtQCPeXemQHUQ2mJeo.14PoGmSmdmsXym2Tc1cCMFLLPJ54a', 'Hortense', 'Schulist', 'Hortense Schulist', '1999-06-05', '1-781-502-3838', '314 Annamarie Extension Suite 394\nLake Brandtshire, WV 84647-5357', NULL, '0', '2006-06-10 17:01:11', '2022-09-22 21:05:01', NULL, 'PFBq7Ro0cf', '2025-05-30 07:20:42', '2025-05-30 07:20:42', NULL);
INSERT INTO `users` VALUES (29, 'iwilkinson@example.com', '$2y$12$08V2WtyGDdOwrG.HHCQ13er7pTCElIxgy91UpK5uuwfV2xra8FA3e', 'Adriana', 'Yundt', 'Adriana Yundt', '2000-11-02', '406-628-2817', '4682 Hessel Place Suite 375\nDellafurt, OH 21502', NULL, '0', '1994-10-27 11:41:47', NULL, NULL, 'd97JAiD7Xf', '2025-05-30 07:20:42', '2025-05-30 07:20:42', NULL);
INSERT INTO `users` VALUES (30, 'nyah35@example.org', '$2y$12$iF8I0nt2W/gKp/fB/u8Rv.Pkwk.CDGrGPKuFQRGL/CQWQBch4K7iW', 'Beulah', 'Schinner', 'Beulah Schinner', '1979-05-11', '1-929-977-3507', '4393 Gibson Groves\nErikhaven, IN 37480', NULL, '0', '1998-06-27 14:55:46', '2015-09-30 00:32:51', NULL, 'TGJe9EicII', '2025-05-30 07:20:42', '2025-05-30 07:20:42', NULL);
INSERT INTO `users` VALUES (31, 'pking@example.org', '$2y$12$XnHkO8fdIBT/iVHwXzGiUeOtr/nl1lYmDNr8MpPIeiMUFUn9NpmLC', 'Jordy', 'Swaniawski', 'Jordy Swaniawski', '1997-05-25', NULL, NULL, NULL, '0', '1994-08-21 11:29:01', NULL, NULL, 'YvonsYUUky', '2025-05-30 07:20:42', '2025-05-30 07:20:42', NULL);
INSERT INTO `users` VALUES (32, 'ohara.willie@example.net', '$2y$12$/Bb/kvNoSX5hfM6AA1XOKObUJl2h2lij3gp4c8kdpLHXhKp/GD9Mi', 'Kirsten', 'Hermiston', 'Kirsten Hermiston', '2012-08-05', '(564) 609-6111', '6997 Schumm Ferry Apt. 467\nGretchenshire, UT 85315-4650', NULL, '0', '1972-03-03 04:46:01', '2022-01-03 12:16:24', NULL, 'jLSNE5Pjif', '2025-05-30 07:20:43', '2025-05-30 07:20:43', NULL);
INSERT INTO `users` VALUES (33, 'bailee.ullrich@example.net', '$2y$12$I/OU4s.bQYqeSFQ7bxGbbOaFU53voSnaMCfJ1.LrFcPsdLETt5/..', 'Alberto', 'Schaefer', 'Alberto Schaefer', '1972-06-07', '+1.912.824.5459', NULL, NULL, '0', NULL, '1999-03-16 19:45:43', NULL, 'iBPKvoLqxX', '2025-05-30 07:20:43', '2025-05-30 07:20:43', NULL);
INSERT INTO `users` VALUES (34, 'yosinski@example.net', '$2y$12$eg3M1LaqShO.sSLqldAMLeLASbd4gVWo5r/M0.pgbhIRr6CRiI6ma', 'Blaise', 'Welch', 'Blaise Welch', '1971-07-22', NULL, NULL, NULL, '0', NULL, '2011-12-13 07:46:03', '1984-09-10 22:55:42', 'UXEjwqwsw4', '2025-05-30 07:20:43', '2025-05-30 07:20:43', NULL);
INSERT INTO `users` VALUES (35, 'francesco.hartmann@example.com', '$2y$12$4HBDeAPrVWlvvgzvPtipgOtXpGMV9NbPOg97C.VDLGlPPNFgCMotC', 'Breanne', 'Von', 'Breanne Von', '1974-03-28', NULL, '751 Maci Fork\nSchaeferbury, MT 32931', NULL, '0', NULL, '1981-10-06 15:35:44', NULL, '3lIbYN1rIb', '2025-05-30 07:20:43', '2025-05-30 07:20:43', NULL);
INSERT INTO `users` VALUES (36, 'earl13@example.org', '$2y$12$SnQ3mI7L28G5W513KKSlqu9DVX7Abpi3k2y2lj4g6EA44fGYGnA3K', 'Kali', 'Kautzer', 'Kali Kautzer', '1988-08-11', '+19066140853', '44954 Trantow Park\nSouth Meghan, AR 90998', NULL, '0', '2016-06-26 11:56:16', NULL, '2000-09-14 03:48:52', 'rB3ACKHGmg', '2025-05-30 07:20:44', '2025-05-30 07:20:44', NULL);
INSERT INTO `users` VALUES (37, 'rebeka.cartwright@example.net', '$2y$12$gsp5RfXnMjckTzH48DK4wuQWrpWIeo2szevHdIQNr3hVjoLJfnb7W', 'Oliver', 'Hyatt', 'Oliver Hyatt', '2024-05-04', '+1-321-302-5294', NULL, NULL, '0', '2019-02-18 04:00:18', '2018-12-11 21:32:07', '2021-08-04 23:09:35', '17w7CCEW8h', '2025-05-30 07:20:44', '2025-05-30 07:20:44', NULL);
INSERT INTO `users` VALUES (38, 'arlo25@example.net', '$2y$12$9WucYzOkI3.uSCK5.DOzc.DpJsW0rnHKIXr0pbWElBQ9UjSQ4c0cy', 'Conor', 'Paucek', 'Conor Paucek', '2002-12-31', '+13524170276', NULL, NULL, '0', NULL, NULL, '1996-02-17 06:12:32', 'UyJPZ0QDFI', '2025-05-30 07:20:44', '2025-05-30 07:20:44', NULL);
INSERT INTO `users` VALUES (39, 'rebekah.legros@example.com', '$2y$12$9YZ4yd/sqFh4rNS8D6WOwOfHBbY.67/r3y93BI/uWfdmjWb3JqXae', 'Annamarie', 'Bernier', 'Annamarie Bernier', '2009-07-31', '516-468-8706', NULL, NULL, '0', '1999-08-12 16:57:30', '1976-08-11 22:19:28', NULL, '38jiUUMz0H', '2025-05-30 07:20:44', '2025-05-30 07:20:44', NULL);
INSERT INTO `users` VALUES (40, 'leila.dickinson@example.org', '$2y$12$WSj0CiDySpxbAaGrQWWVi.2.ptBiSJbk0Wpw.vhsCp6lEwuSjadVS', 'Sandy', 'Gislason', 'Sandy Gislason', '2024-11-12', NULL, NULL, NULL, '0', '1982-04-11 03:47:30', NULL, NULL, 'yt2O45HzEE', '2025-05-30 07:20:44', '2025-05-30 07:20:44', NULL);
INSERT INTO `users` VALUES (41, 'flabadie@example.net', '$2y$12$tKxKDf3rayk5f4LfYbndKuiBRCMT5ieqRMWMtrx1wUKuBmm0l2t/S', 'Kenyon', 'Ziemann', 'Kenyon Ziemann', '2017-03-09', NULL, '267 Flatley Avenue\nPort Cesarstad, VA 62218-2391', NULL, '0', '2002-04-21 19:30:08', '2013-04-18 12:57:28', NULL, 'NtmVLfOTAv', '2025-05-30 07:20:45', '2025-05-30 07:20:45', NULL);
INSERT INTO `users` VALUES (42, 'suzanne81@example.net', '$2y$12$TMIiOfx7yK8okJ3DZTU/ieLtboKOeg0lfKzDtWi/Gvs9ZzDv/aF8y', 'Zoie', 'Hoeger', 'Zoie Hoeger', '2018-10-22', NULL, '81893 Osinski Trail\nLangoshtown, NE 14358', NULL, '0', '1974-05-21 22:14:47', NULL, '1986-05-02 02:16:27', '1oO8JTv16V', '2025-05-30 07:20:45', '2025-05-30 07:20:45', NULL);
INSERT INTO `users` VALUES (43, 'lrempel@example.com', '$2y$12$imtDcl35uPwxwnJr3QSPl.8QYxXalMkuDiOSMHZsc4K.Q0rErfTOq', 'Dewitt', 'Hirthe', 'Dewitt Hirthe', '1982-01-23', NULL, NULL, NULL, '0', '1981-12-27 04:01:12', '2010-01-08 06:21:11', '1978-06-10 19:33:17', 'GecI7VEXZR', '2025-05-30 07:20:45', '2025-05-30 07:20:45', NULL);
INSERT INTO `users` VALUES (44, 'shemar53@example.net', '$2y$12$KkFpfeSUs4s7XS8HKsFMcuxS6o7CtPC8TYeIijL17.hgzGtE/VtMS', 'Lempi', 'Breitenberg', 'Lempi Breitenberg', '1976-12-23', NULL, '66878 Ankunding Garden\nSouth Moriahton, IA 51017-9676', NULL, '0', NULL, NULL, NULL, 'O2a3aWbsmZ', '2025-05-30 07:20:45', '2025-05-30 07:20:45', NULL);
INSERT INTO `users` VALUES (45, 'lubowitz.kaci@example.org', '$2y$12$8/XQRkXjbMlCEV5CPCUpUeMeGMcMDK8yNNzMjClBr/UCoSWbznAPC', 'Linwood', 'Stokes', 'Linwood Stokes', '1976-09-12', NULL, NULL, NULL, '0', '2023-11-02 21:55:38', '2006-08-19 15:51:06', NULL, 'Amj50pq9IV', '2025-05-30 07:20:45', '2025-05-30 07:20:45', NULL);
INSERT INTO `users` VALUES (46, 'christine.lehner@example.org', '$2y$12$PKDRrWPTyZhh8BbR9vBD8uKp2XsMiuDyMveb2BM4z0vyZjTg/lehK', 'Molly', 'Hartmann', 'Molly Hartmann', '2008-04-07', '+1 (763) 646-5667', NULL, NULL, '0', '1974-08-31 01:30:48', '1986-06-23 05:39:33', NULL, 'ZBN1ofYnUz', '2025-05-30 07:20:46', '2025-05-30 07:20:46', NULL);
INSERT INTO `users` VALUES (47, 'mraz.chyna@example.org', '$2y$12$1uY0BAFr/OW1LtuqT6hVZuJKm34Q01fj57sd3IHIzVmGqhu6lKXOu', 'Oral', 'Metz', 'Oral Metz', '1981-03-22', '1-660-643-1973', '265 Shanahan Plains Apt. 472\nTylershire, MO 05753', NULL, '0', NULL, '2000-11-16 20:14:57', '2005-10-08 21:35:13', 'DPVT4Dy3cD', '2025-05-30 07:20:46', '2025-05-30 07:20:46', NULL);
INSERT INTO `users` VALUES (48, 'furman56@example.com', '$2y$12$OAObIigxgutu2BDa.t8wMOGlFqau4AHlJpNgmPFOJIsS/BRprqi1m', 'Dion', 'Gerhold', 'Dion Gerhold', '1970-11-25', NULL, '2894 Waters Lake\nHarrisville, NC 38277-7593', NULL, '0', '2021-01-11 18:36:58', '2005-06-26 17:18:56', NULL, 'sXjznkUZBL', '2025-05-30 07:20:46', '2025-05-30 07:20:46', NULL);
INSERT INTO `users` VALUES (49, 'mayert.lewis@example.org', '$2y$12$LvCgaZX/rOKFEhtH4M2GCOZdbBdHi/tCzVH/ygaDm8oBbk2ZeFNiy', 'Cletus', 'Wuckert', 'Cletus Wuckert', '1999-10-22', NULL, '79912 Cummerata Points Suite 803\nLake Hermann, IA 31430-0077', NULL, '0', NULL, NULL, NULL, 'WM7WhrGnW2', '2025-05-30 07:20:46', '2025-05-30 07:20:46', NULL);
INSERT INTO `users` VALUES (50, 'vince.hauck@example.org', '$2y$12$8RPcBJII15r0DE7YNnCEp.3eJRkCvj41iy7RT4BCo0Ff7rSU2dcPC', 'Candice', 'Zieme', 'Candice Zieme', '2012-12-26', NULL, '691 Reichel Hills\nSouth Jamelstad, CT 17714-4254', NULL, '0', NULL, NULL, NULL, 'B10cQElFAB', '2025-05-30 07:20:47', '2025-05-30 07:20:47', NULL);
INSERT INTO `users` VALUES (51, 'jschinner@example.net', '$2y$12$nNh/MpnlR4teaCcLHm0V7O5RMBcjmvqsbWCx5ogOBJWYlAD9cCBzm', 'Elisabeth', 'Parker', 'Elisabeth Parker', '2018-10-01', NULL, NULL, NULL, '0', NULL, '1990-09-26 15:51:21', NULL, 'WsKCx5y9An', '2025-05-30 07:20:47', '2025-05-30 07:20:47', NULL);

SET FOREIGN_KEY_CHECKS = 1;

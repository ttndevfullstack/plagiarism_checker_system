/*
 Navicat Premium Data Transfer

 Source Server         : Proofly Local
 Source Server Type    : MySQL
 Source Server Version : 80042 (8.0.42)
 Source Host           : localhost:3306
 Source Schema         : db_proofly

 Target Server Type    : MySQL
 Target Server Version : 80042 (8.0.42)
 File Encoding         : 65001

 Date: 20/06/2025 18:28:03
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
INSERT INTO `assignments` VALUES (1, 1, 1, '2025-06-28', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `assignments` VALUES (2, 2, 1, '2025-07-03', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `assignments` VALUES (3, 3, 2, '2025-06-30', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `assignments` VALUES (4, 4, 3, '2025-07-02', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `assignments` VALUES (5, 5, 3, '2025-06-23', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `assignments` VALUES (6, 6, 4, '2025-07-15', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `assignments` VALUES (7, 7, 5, '2025-07-02', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `assignments` VALUES (8, 8, 6, '2025-07-03', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `assignments` VALUES (9, 9, 7, '2025-06-25', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `assignments` VALUES (10, 10, 8, '2025-07-08', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `assignments` VALUES (11, 11, 8, '2025-07-19', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `assignments` VALUES (12, 12, 9, '2025-07-19', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `assignments` VALUES (13, 13, 9, '2025-07-10', '2025-06-20 11:26:20', '2025-06-20 11:26:21', NULL);
INSERT INTO `assignments` VALUES (14, 14, 10, '2025-07-07', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `assignments` VALUES (15, 15, 10, '2025-07-17', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);

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
INSERT INTO `classes` VALUES (1, 9, 1, 'Class qui', '414164', '2025-08-07', '2025-10-18', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `classes` VALUES (2, 2, 1, 'Class exercitationem', '239022', '2025-08-14', '2025-10-22', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `classes` VALUES (3, 3, 2, 'Class illo', '969604', '2025-07-16', '2025-12-16', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `classes` VALUES (4, 10, 3, 'Class minus', '453116', '2025-08-10', '2025-12-08', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `classes` VALUES (5, 5, 3, 'Class sunt', '112525', '2025-08-14', '2025-10-04', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `classes` VALUES (6, 11, 4, 'Class totam', '502569', '2025-08-07', '2025-11-12', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `classes` VALUES (7, 5, 5, 'Class ad', '207049', '2025-06-22', '2025-07-08', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `classes` VALUES (8, 5, 6, 'Class saepe', '209934', '2025-07-09', '2025-11-05', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `classes` VALUES (9, 7, 7, 'Class dolor', '106546', '2025-08-10', '2025-11-16', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `classes` VALUES (10, 10, 8, 'Class cum', '935734', '2025-07-09', '2025-09-25', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `classes` VALUES (11, 11, 8, 'Class id', '475170', '2025-08-20', '2025-11-17', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `classes` VALUES (12, 10, 9, 'Class aut', '175464', '2025-07-31', '2025-08-13', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `classes` VALUES (13, 5, 9, 'Class magnam', '686915', '2025-06-25', '2025-09-10', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `classes` VALUES (14, 6, 10, 'Class assumenda', '598900', '2025-07-29', '2025-08-30', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `classes` VALUES (15, 3, 10, 'Class at', '314334', '2025-07-05', '2025-09-30', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);

-- ----------------------------
-- Table structure for document_batches
-- ----------------------------
DROP TABLE IF EXISTS `document_batches`;
CREATE TABLE `document_batches`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `media_id` bigint UNSIGNED NOT NULL,
  `media_path_id` bigint UNSIGNED NULL DEFAULT NULL,
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of documents
-- ----------------------------

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
) ENGINE = InnoDB AUTO_INCREMENT = 85 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of enrollments
-- ----------------------------
INSERT INTO `enrollments` VALUES (1, 1, 9, '2025-06-07', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (2, 1, 13, '2025-05-30', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (3, 1, 14, '2025-05-28', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (4, 2, 6, '2025-05-30', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (5, 2, 12, '2025-06-15', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (6, 3, 9, '2025-06-04', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (7, 3, 11, '2025-06-11', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (8, 4, 9, '2025-05-28', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (9, 4, 10, '2025-06-01', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (10, 4, 15, '2025-06-03', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (11, 5, 1, '2025-06-09', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (12, 5, 13, '2025-06-10', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (13, 5, 15, '2025-05-21', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (14, 6, 9, '2025-05-23', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (15, 7, 1, '2025-06-01', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (16, 7, 12, '2025-06-12', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (17, 7, 15, '2025-06-05', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (18, 8, 4, '2025-06-05', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (19, 8, 10, '2025-05-26', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (20, 8, 13, '2025-06-03', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (21, 9, 15, '2025-06-13', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (22, 10, 3, '2025-06-06', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (23, 10, 4, '2025-05-27', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (24, 10, 5, '2025-05-29', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (25, 11, 2, '2025-06-12', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (26, 11, 10, '2025-06-09', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (27, 11, 14, '2025-06-11', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (28, 12, 3, '2025-05-24', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (29, 12, 7, '2025-06-08', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (30, 12, 8, '2025-06-19', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (31, 13, 1, '2025-06-12', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (32, 13, 15, '2025-06-18', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (33, 14, 4, '2025-06-14', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (34, 15, 13, '2025-06-15', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (35, 16, 4, '2025-06-05', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (36, 16, 12, '2025-05-24', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (37, 17, 1, '2025-06-12', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (38, 17, 12, '2025-06-10', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (39, 18, 2, '2025-05-22', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (40, 18, 3, '2025-05-29', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (41, 19, 3, '2025-06-16', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (42, 19, 6, '2025-05-30', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (43, 19, 9, '2025-06-03', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (44, 20, 5, '2025-06-15', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (45, 20, 8, '2025-06-07', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (46, 20, 9, '2025-06-13', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (47, 21, 5, '2025-06-06', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (48, 22, 1, '2025-05-27', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (49, 22, 4, '2025-05-23', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (50, 22, 13, '2025-05-29', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (51, 23, 12, '2025-05-30', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (52, 24, 3, '2025-06-03', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (53, 25, 4, '2025-05-21', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (54, 26, 13, '2025-05-29', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (55, 27, 3, '2025-06-18', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (56, 27, 5, '2025-06-19', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (57, 27, 11, '2025-05-26', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (58, 28, 11, '2025-06-17', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (59, 29, 10, '2025-05-27', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (60, 29, 15, '2025-05-26', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (61, 30, 8, '2025-06-09', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (62, 30, 14, '2025-06-07', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (63, 31, 4, '2025-05-23', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (64, 31, 9, '2025-05-23', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (65, 31, 13, '2025-05-21', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (66, 32, 4, '2025-06-17', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (67, 32, 9, '2025-06-06', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (68, 32, 13, '2025-06-11', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (69, 33, 5, '2025-06-11', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (70, 33, 6, '2025-05-25', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (71, 33, 11, '2025-06-01', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (72, 34, 5, '2025-05-27', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (73, 34, 12, '2025-05-21', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (74, 34, 13, '2025-06-11', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (75, 35, 8, '2025-06-06', '2025-06-20 11:26:21', '2025-06-20 11:26:21', NULL);
INSERT INTO `enrollments` VALUES (76, 35, 12, '2025-05-21', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `enrollments` VALUES (77, 36, 11, '2025-06-16', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `enrollments` VALUES (78, 37, 8, '2025-06-17', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `enrollments` VALUES (79, 37, 14, '2025-05-28', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `enrollments` VALUES (80, 38, 1, '2025-06-08', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `enrollments` VALUES (81, 38, 14, '2025-06-11', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `enrollments` VALUES (82, 39, 3, '2025-06-04', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `enrollments` VALUES (83, 39, 8, '2025-06-10', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `enrollments` VALUES (84, 40, 5, '2025-05-29', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);

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
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of exams
-- ----------------------------
INSERT INTO `exams` VALUES (1, 1, 1, 'Rem alias et.', 'Eos sed amet dolores repellat est quia. Recusandae veniam ut et possimus voluptas quis iusto. Corporis quo adipisci et architecto id.', '2025-08-24 12:00:40', '2025-08-24 13:00:40', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `exams` VALUES (2, 1, 1, 'Quia qui doloribus voluptatibus quasi.', 'Nostrum rerum voluptate molestiae dignissimos dicta eum minus cupiditate. Id omnis dolores neque sit modi labore id totam. Voluptates et id omnis consequatur eum earum sequi placeat.', '2025-09-21 08:22:05', '2025-09-21 09:22:05', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `exams` VALUES (3, 2, 1, 'Sed blanditiis sunt rerum quod fugiat.', 'Fugit ut temporibus fuga. Iusto eum necessitatibus recusandae debitis quis. Consequuntur rerum blanditiis enim nam est qui aut. Et non ab quia quas consectetur et.', '2025-09-05 01:47:40', '2025-09-05 02:47:40', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `exams` VALUES (4, 3, 2, 'Incidunt et voluptatibus ratione debitis.', 'Dolore et tempora quis vero id sed. Consequatur dignissimos illum tenetur laboriosam delectus atque magnam. Et voluptatum fugit itaque. Quibusdam sunt occaecati exercitationem nobis molestiae.', '2025-11-16 02:13:06', '2025-11-16 03:13:06', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `exams` VALUES (5, 3, 2, 'Ut sint exercitationem quisquam.', 'Voluptatibus corrupti reprehenderit quam id voluptatibus. Aut quod fugit unde ut et fuga a. Ullam natus placeat excepturi iure optio. Voluptas corrupti ea qui tenetur.', '2025-09-21 21:03:33', '2025-09-21 22:03:33', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `exams` VALUES (6, 4, 3, 'In laudantium officia ut labore.', 'Voluptas et quae sed molestias aut vero sequi. Incidunt distinctio assumenda quam nesciunt sunt et deleniti corrupti. Reprehenderit ea est quos porro ea autem aut. Et quia vitae soluta ex aliquid minus quaerat. Sit aliquam quia rerum.', '2025-10-30 23:49:14', '2025-10-31 02:49:14', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `exams` VALUES (7, 5, 3, 'Reiciendis rem est qui.', 'Consequuntur blanditiis et beatae sed. Quibusdam quia explicabo hic et officia. Rerum distinctio excepturi qui.', '2025-08-22 14:35:33', '2025-08-22 17:35:33', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `exams` VALUES (8, 5, 3, 'Praesentium culpa officiis ea voluptatibus.', 'Molestiae voluptatum in temporibus possimus consequatur et dolores. Iste consequuntur illo praesentium itaque. Aut recusandae exercitationem cumque. Perspiciatis provident et quae eos ipsa.', '2025-09-06 08:12:30', '2025-09-06 09:12:30', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `exams` VALUES (9, 6, 4, 'Possimus amet rerum aliquid.', 'Fugit delectus alias non nihil possimus. Qui nisi molestiae rem rerum eos alias sequi quia. Odit odio ab itaque accusamus veniam et. Est sit qui facilis ratione quia.', '2025-09-04 13:45:18', '2025-09-04 15:45:18', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `exams` VALUES (10, 7, 5, 'Vel illum harum laudantium est.', 'Accusantium quas sit perferendis consequatur aut est. Omnis officiis aut hic perspiciatis voluptatem eos qui. Voluptatum perferendis laboriosam repellendus et. Asperiores labore recusandae ipsum occaecati. Hic consequatur reiciendis est quia.', '2025-06-29 11:01:49', '2025-06-29 13:01:49', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `exams` VALUES (11, 7, 5, 'Placeat exercitationem ut praesentium.', 'Qui fugit provident et qui qui et. Et aliquid assumenda ducimus porro ut. Nam voluptas minima rerum dolorum quidem est rerum. Enim ipsa aspernatur ut.', '2025-07-04 20:48:14', '2025-07-04 23:48:14', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `exams` VALUES (12, 8, 6, 'Ut aut error.', 'Sapiente quo harum commodi. Quis possimus soluta nobis. Eos quibusdam explicabo excepturi recusandae et officia quia.', '2025-09-08 19:49:30', '2025-09-08 22:49:30', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `exams` VALUES (13, 8, 6, 'Tempore laudantium dolores enim.', 'Ut culpa quo nihil itaque. Dolor beatae sed aspernatur laboriosam et. Cum numquam commodi accusamus sunt laborum. Quia voluptas voluptatem itaque eos. Est nesciunt possimus repudiandae velit.', '2025-09-02 08:56:13', '2025-09-02 10:56:13', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `exams` VALUES (14, 9, 7, 'Assumenda consectetur odio omnis.', 'Quae quo quae quibusdam minus porro magni. Alias libero possimus magnam sit ea explicabo. Velit error enim omnis qui doloremque ipsum.', '2025-09-29 18:46:31', '2025-09-29 19:46:31', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `exams` VALUES (15, 9, 7, 'Fuga occaecati asperiores.', 'Rerum non sit ea consequatur dolor placeat. Ab architecto porro ea unde earum. Deserunt eos unde totam. Deleniti alias excepturi occaecati.', '2025-11-05 17:49:36', '2025-11-05 19:49:36', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `exams` VALUES (16, 10, 8, 'Suscipit est suscipit nemo.', 'Et enim sunt quod rerum vel reprehenderit. Magnam incidunt dignissimos ut porro consequuntur. Aliquam omnis et sint quas. Odit blanditiis maxime laudantium et voluptate nemo necessitatibus.', '2025-08-18 12:03:32', '2025-08-18 13:03:32', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `exams` VALUES (17, 10, 8, 'Provident temporibus porro qui.', 'Fugiat voluptate consectetur ea in. Dolorem consectetur quia in et nobis et unde. Eius delectus iste nihil sapiente. Molestiae deleniti ipsam quisquam velit aliquid magni adipisci est.', '2025-07-09 08:43:13', '2025-07-09 10:43:13', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `exams` VALUES (18, 11, 8, 'Est suscipit perspiciatis voluptatem sed esse.', 'Tempore consectetur voluptate quibusdam iste. Culpa aut sit explicabo et eius debitis. Sequi aliquam aliquam molestias quia sapiente necessitatibus molestiae.', '2025-11-02 22:09:00', '2025-11-02 23:09:00', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `exams` VALUES (19, 11, 8, 'Voluptatem aliquid voluptatibus voluptatem officiis.', 'Porro maiores facilis laborum eos. Et itaque magni corporis ut. Et sunt odio non qui. Id in quia dolor excepturi rerum eligendi.', '2025-10-31 12:22:00', '2025-10-31 14:22:00', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `exams` VALUES (20, 12, 9, 'Facilis rerum omnis soluta.', 'Blanditiis neque nesciunt voluptate sequi. Accusantium eos quasi et voluptatem voluptatem illo aut. Magnam natus sit ab voluptatem. Aperiam quae consectetur et quia.', '2025-08-05 21:05:11', '2025-08-05 22:05:11', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `exams` VALUES (21, 13, 9, 'Beatae voluptas dicta ut quia.', 'Occaecati repellendus dignissimos accusantium. Et voluptatem ipsa ea praesentium sed sint ea. Esse ut hic molestiae. Blanditiis voluptate sint quod possimus dolor officia.', '2025-07-04 12:52:14', '2025-07-04 15:52:14', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `exams` VALUES (22, 14, 10, 'Quae aut magnam quia.', 'Perferendis fugit architecto non sequi non non quibusdam. Est officia porro et cumque. Dolorum nisi vitae qui magnam adipisci rerum. Nihil asperiores iste veniam velit.', '2025-08-16 02:46:52', '2025-08-16 04:46:52', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `exams` VALUES (23, 14, 10, 'Atque architecto et blanditiis et.', 'Delectus mollitia quam nesciunt. Perspiciatis dolores laudantium eos ipsum hic placeat. Eum molestiae quibusdam et veniam sapiente et.', '2025-07-30 02:03:43', '2025-07-30 03:03:43', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `exams` VALUES (24, 15, 10, 'Est et sit et neque.', 'Omnis consequatur eius sed consequatur ut et enim. Et similique omnis deserunt placeat possimus totam. Veniam ad iure optio sint sit voluptas maiores. Eum earum accusamus consequatur rerum suscipit aperiam et.', '2025-09-02 03:32:54', '2025-09-02 06:32:54', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);
INSERT INTO `exams` VALUES (25, 15, 10, 'Autem qui pariatur.', 'Est accusamus eveniet unde cum qui nemo. Recusandae labore consequatur ad in sunt enim similique totam. Et est blanditiis eveniet facilis odio dolorum dignissimos dolor. Delectus in voluptas atque dolorem inventore doloremque suscipit. Aliquid quod magni quis laudantium dolores.', '2025-07-20 04:21:01', '2025-07-20 07:21:01', '2025-06-20 11:26:22', '2025-06-20 11:26:22', NULL);

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of media
-- ----------------------------

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 47 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (24, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (25, '2014_10_12_100000_create_password_reset_tokens_table', 1);
INSERT INTO `migrations` VALUES (26, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` VALUES (27, '2019_12_14_000001_create_personal_access_tokens_table', 1);
INSERT INTO `migrations` VALUES (28, '2023_12_20_012855_create_media_table', 1);
INSERT INTO `migrations` VALUES (29, '2025_01_15_072549_create_permission_tables', 1);
INSERT INTO `migrations` VALUES (30, '2025_01_16_055925_add_description_to_roles_table', 1);
INSERT INTO `migrations` VALUES (31, '2025_01_17_054440_add_group_field_into_permissions_table', 1);
INSERT INTO `migrations` VALUES (32, '2025_02_01_141110_add_avatar_to_users_table', 1);
INSERT INTO `migrations` VALUES (33, '2025_02_03_101256_create_students_table', 1);
INSERT INTO `migrations` VALUES (34, '2025_02_03_101307_create_teachers_table', 1);
INSERT INTO `migrations` VALUES (35, '2025_02_03_101310_create_subjects_table', 1);
INSERT INTO `migrations` VALUES (36, '2025_02_03_101313_create_classes_table', 1);
INSERT INTO `migrations` VALUES (37, '2025_02_03_101334_create_enrollments_table', 1);
INSERT INTO `migrations` VALUES (38, '2025_02_03_101348_create_assignments_table', 1);
INSERT INTO `migrations` VALUES (39, '2025_02_04_044447_add_soft_deleted_to_users_table', 1);
INSERT INTO `migrations` VALUES (40, '2025_03_23_000002_create_documents_table', 1);
INSERT INTO `migrations` VALUES (41, '2025_03_23_000003_create_document_batches_table', 1);
INSERT INTO `migrations` VALUES (42, '2025_03_29_060419_create_jobs_table', 1);
INSERT INTO `migrations` VALUES (43, '2025_04_18_161758_create_job_batches_table', 1);
INSERT INTO `migrations` VALUES (44, '2025_04_30_161758_create_plagiarism_checks_table', 1);
INSERT INTO `migrations` VALUES (45, '2025_06_17_173900_create_exams_table', 1);
INSERT INTO `migrations` VALUES (46, '2025_06_17_175234_add_exam_id_to_documents_table', 1);

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
INSERT INTO `roles` VALUES (1, 'admin', 'Administrator with full access to manage the system.', 'web', '2025-06-20 11:26:06', '2025-06-20 11:26:06');
INSERT INTO `roles` VALUES (2, 'teacher', 'Teacher role with permissions to manage students and classes.', 'web', '2025-06-20 11:26:06', '2025-06-20 11:26:06');
INSERT INTO `roles` VALUES (3, 'student', 'Student role with access to view and participate in classes.', 'web', '2025-06-20 11:26:06', '2025-06-20 11:26:06');

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
INSERT INTO `students` VALUES (1, 12, 'STU00012', '2024-09-20', '2025-06-20 11:26:19', '2025-06-20 11:26:19', NULL);
INSERT INTO `students` VALUES (2, 13, 'STU00013', '2025-05-20', '2025-06-20 11:26:19', '2025-06-20 11:26:19', NULL);
INSERT INTO `students` VALUES (3, 14, 'STU00014', '2024-07-20', '2025-06-20 11:26:19', '2025-06-20 11:26:19', NULL);
INSERT INTO `students` VALUES (4, 15, 'STU00015', '2025-05-20', '2025-06-20 11:26:19', '2025-06-20 11:26:19', NULL);
INSERT INTO `students` VALUES (5, 16, 'STU00016', '2025-03-20', '2025-06-20 11:26:19', '2025-06-20 11:26:19', NULL);
INSERT INTO `students` VALUES (6, 17, 'STU00017', '2024-09-20', '2025-06-20 11:26:19', '2025-06-20 11:26:19', NULL);
INSERT INTO `students` VALUES (7, 18, 'STU00018', '2025-03-20', '2025-06-20 11:26:19', '2025-06-20 11:26:19', NULL);
INSERT INTO `students` VALUES (8, 19, 'STU00019', '2025-02-20', '2025-06-20 11:26:19', '2025-06-20 11:26:19', NULL);
INSERT INTO `students` VALUES (9, 20, 'STU00020', '2024-12-20', '2025-06-20 11:26:19', '2025-06-20 11:26:19', NULL);
INSERT INTO `students` VALUES (10, 21, 'STU00021', '2025-01-20', '2025-06-20 11:26:19', '2025-06-20 11:26:19', NULL);
INSERT INTO `students` VALUES (11, 22, 'STU00022', '2025-04-20', '2025-06-20 11:26:19', '2025-06-20 11:26:19', NULL);
INSERT INTO `students` VALUES (12, 23, 'STU00023', '2025-05-20', '2025-06-20 11:26:19', '2025-06-20 11:26:19', NULL);
INSERT INTO `students` VALUES (13, 24, 'STU00024', '2025-04-20', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `students` VALUES (14, 25, 'STU00025', '2025-01-20', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `students` VALUES (15, 26, 'STU00026', '2024-08-20', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `students` VALUES (16, 27, 'STU00027', '2025-04-20', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `students` VALUES (17, 28, 'STU00028', '2025-02-20', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `students` VALUES (18, 29, 'STU00029', '2024-06-20', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `students` VALUES (19, 30, 'STU00030', '2024-12-20', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `students` VALUES (20, 31, 'STU00031', '2024-12-20', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `students` VALUES (21, 32, 'STU00032', '2024-11-20', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `students` VALUES (22, 33, 'STU00033', '2024-06-20', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `students` VALUES (23, 34, 'STU00034', '2024-08-20', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `students` VALUES (24, 35, 'STU00035', '2024-12-20', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `students` VALUES (25, 36, 'STU00036', '2024-11-20', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `students` VALUES (26, 37, 'STU00037', '2025-04-20', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `students` VALUES (27, 38, 'STU00038', '2024-07-20', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `students` VALUES (28, 39, 'STU00039', '2025-03-20', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `students` VALUES (29, 40, 'STU00040', '2024-09-20', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `students` VALUES (30, 41, 'STU00041', '2024-08-20', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `students` VALUES (31, 42, 'STU00042', '2024-06-20', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `students` VALUES (32, 43, 'STU00043', '2025-03-20', '2025-06-20 11:27:19', '2025-06-20 11:27:19', NULL);
INSERT INTO `students` VALUES (33, 44, 'STU00044', '2024-06-20', '2025-06-20 11:27:19', '2025-06-20 11:27:19', NULL);
INSERT INTO `students` VALUES (34, 45, 'STU00045', '2024-06-20', '2025-06-20 11:27:19', '2025-06-20 11:27:19', NULL);
INSERT INTO `students` VALUES (35, 46, 'STU00046', '2024-11-20', '2025-06-20 11:27:19', '2025-06-20 11:27:19', NULL);
INSERT INTO `students` VALUES (36, 47, 'STU00047', '2024-08-20', '2025-06-20 11:27:19', '2025-06-20 11:27:19', NULL);
INSERT INTO `students` VALUES (37, 48, 'STU00048', '2024-07-20', '2025-06-20 11:27:19', '2025-06-20 11:27:19', NULL);
INSERT INTO `students` VALUES (38, 49, 'STU00049', '2024-10-20', '2025-06-20 11:27:19', '2025-06-20 11:27:19', NULL);
INSERT INTO `students` VALUES (39, 50, 'STU00050', '2024-08-20', '2025-06-20 11:27:19', '2025-06-20 11:27:19', NULL);
INSERT INTO `students` VALUES (40, 51, 'STU00051', '2024-09-20', '2025-06-20 11:27:19', '2025-06-20 11:27:19', NULL);

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
INSERT INTO `subjects` VALUES (1, 'CNTT', 'Công nghệ thông tin', 'Chuyên ngành về lập trình, phát triển phần mềm, cơ sở dữ liệu và hệ thống thông tin', '2025-06-20 11:27:19', '2025-06-20 11:27:19', NULL);
INSERT INTO `subjects` VALUES (2, 'KTPM', 'Kỹ thuật phần mềm', 'Chuyên sâu về quy trình phát triển phần mềm, kiểm thử và đảm bảo chất lượng', '2025-06-20 11:27:19', '2025-06-20 11:27:19', NULL);
INSERT INTO `subjects` VALUES (3, 'KHMT', 'Khoa học máy tính', 'Nghiên cứu về thuật toán, trí tuệ nhân tạo và khoa học dữ liệu', '2025-06-20 11:27:19', '2025-06-20 11:27:19', NULL);
INSERT INTO `subjects` VALUES (4, 'QTKD', 'Quản trị kinh doanh', 'Đào tạo về quản lý, marketing và phát triển doanh nghiệp', '2025-06-20 11:27:19', '2025-06-20 11:27:19', NULL);
INSERT INTO `subjects` VALUES (5, 'TCNH', 'Tài chính ngân hàng', 'Chuyên ngành về tài chính, ngân hàng và thị trường chứng khoán', '2025-06-20 11:27:19', '2025-06-20 11:27:19', NULL);
INSERT INTO `subjects` VALUES (6, 'KTKT', 'Kế toán kiểm toán', 'Đào tạo về kế toán, kiểm toán và phân tích tài chính', '2025-06-20 11:27:19', '2025-06-20 11:27:19', NULL);
INSERT INTO `subjects` VALUES (7, 'YDK', 'Y đa khoa', 'Đào tạo bác sĩ đa khoa và chuyên khoa', '2025-06-20 11:27:19', '2025-06-20 11:27:19', NULL);
INSERT INTO `subjects` VALUES (8, 'DH', 'Dược học', 'Nghiên cứu về dược phẩm và phát triển thuốc', '2025-06-20 11:27:19', '2025-06-20 11:27:19', NULL);
INSERT INTO `subjects` VALUES (9, 'DD', 'Điều dưỡng', 'Đào tạo về chăm sóc sức khỏe và hỗ trợ y tế', '2025-06-20 11:27:19', '2025-06-20 11:27:19', NULL);
INSERT INTO `subjects` VALUES (10, 'TKDH', 'Thiết kế đồ họa', 'Chuyên về thiết kế hình ảnh và truyền thông thị giác', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `subjects` VALUES (11, 'KT', 'Kiến trúc', 'Thiết kế kiến trúc và quy hoạch đô thị', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);
INSERT INTO `subjects` VALUES (12, 'TKNT', 'Thiết kế nội thất', 'Thiết kế và trang trí không gian nội thất', '2025-06-20 11:26:20', '2025-06-20 11:26:20', NULL);

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
INSERT INTO `teachers` VALUES (1, 2, '2025-05-20', '2025-06-20 11:26:19', '2025-06-20 11:26:19', NULL);
INSERT INTO `teachers` VALUES (2, 3, '2025-02-20', '2025-06-20 11:26:19', '2025-06-20 11:26:19', NULL);
INSERT INTO `teachers` VALUES (3, 4, '2025-01-20', '2025-06-20 11:26:19', '2025-06-20 11:26:19', NULL);
INSERT INTO `teachers` VALUES (4, 5, '2024-06-20', '2025-06-20 11:26:19', '2025-06-20 11:26:19', NULL);
INSERT INTO `teachers` VALUES (5, 6, '2025-05-20', '2025-06-20 11:26:19', '2025-06-20 11:26:19', NULL);
INSERT INTO `teachers` VALUES (6, 7, '2024-10-20', '2025-06-20 11:26:19', '2025-06-20 11:26:19', NULL);
INSERT INTO `teachers` VALUES (7, 8, '2025-02-20', '2025-06-20 11:26:19', '2025-06-20 11:26:19', NULL);
INSERT INTO `teachers` VALUES (8, 9, '2024-11-20', '2025-06-20 11:26:19', '2025-06-20 11:26:19', NULL);
INSERT INTO `teachers` VALUES (9, 10, '2024-11-20', '2025-06-20 11:26:19', '2025-06-20 11:26:19', NULL);
INSERT INTO `teachers` VALUES (10, 11, '2025-03-20', '2025-06-20 11:26:19', '2025-06-20 11:26:19', NULL);

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
INSERT INTO `users` VALUES (1, 'admin@yopmail.com', '$2y$12$/ClYBzpHW5kKMaVZCU0Rw.a9CCrJKyE0thb7gGJapmMra/kraT17O', 'I am', 'Admin', 'I am Admin', '1995-06-20', '1234567890', 'Admin Address', NULL, '1', NULL, NULL, NULL, NULL, '2025-06-20 11:26:06', '2025-06-20 11:26:06', NULL);
INSERT INTO `users` VALUES (2, 'berge.lucinda@example.com', '$2y$12$Uxp5oTJ8EpXvgWLyh66KgOCb4P0HTwvKMLjTRhzfiJRiO09o5O4Km', 'Laurie', 'Fadel', 'Laurie Fadel', '1990-01-31', NULL, NULL, NULL, '0', NULL, '1994-04-02 15:28:08', NULL, 'qqlUFFoWne', '2025-06-20 11:26:06', '2025-06-20 11:26:06', NULL);
INSERT INTO `users` VALUES (3, 'hpollich@example.org', '$2y$12$NmSZSuxhU1URP/.iYuDUb.Hn6kXwRCLXeodbIIP9cZQ/vdVc5Iyb.', 'Lurline', 'Fisher', 'Lurline Fisher', '1974-06-18', NULL, '8343 Mraz Ports\nJenkinsville, AR 30454-9130', NULL, '0', NULL, '1990-08-17 03:28:33', NULL, 'sFbDjzjr7e', '2025-06-20 11:26:07', '2025-06-20 11:26:07', NULL);
INSERT INTO `users` VALUES (4, 'ian.mueller@example.com', '$2y$12$eEvfxF2.HduW/tauYtxNUOuEv9pSO69zEhVDApH04ZQMRdvkYsdfi', 'Melany', 'Denesik', 'Melany Denesik', '1993-01-05', NULL, NULL, NULL, '0', '1980-01-07 21:53:06', '1988-03-13 10:34:22', NULL, 'K7752pkkJU', '2025-06-20 11:26:07', '2025-06-20 11:26:07', NULL);
INSERT INTO `users` VALUES (5, 'mvolkman@example.net', '$2y$12$qp6z9r75X9s3ghWr/bZHJuKAsJ2uJsLISe3Uq6hhzmHhbErcHqh2C', 'Gaetano', 'Mohr', 'Gaetano Mohr', '2010-06-25', '+1.717.435.8847', '8446 Harber Land\nTanyaland, ND 11992', NULL, '0', '2023-01-20 10:35:13', '1984-02-10 01:14:40', NULL, 'v86WjSk3Op', '2025-06-20 11:26:07', '2025-06-20 11:26:07', NULL);
INSERT INTO `users` VALUES (6, 'pierre.collins@example.com', '$2y$12$TQ28dA3lbwkpcYxRuOGVpupn2dp2CzVyxuqZB2fxKu.VZmSyfSi1O', 'Thora', 'Gerhold', 'Thora Gerhold', '2019-03-06', NULL, NULL, NULL, '0', '2016-03-21 10:42:15', NULL, NULL, 'UpPmIJqziM', '2025-06-20 11:26:07', '2025-06-20 11:26:07', NULL);
INSERT INTO `users` VALUES (7, 'steve18@example.net', '$2y$12$V8d3VQPJQxauP27Bxa.HSOLSTt/qOoBXjne7uOIZRexRiqHMSTo8u', 'Mohammed', 'Schultz', 'Mohammed Schultz', '2023-05-13', NULL, NULL, NULL, '0', NULL, '2022-08-29 01:08:16', NULL, '9HRSv7XTfV', '2025-06-20 11:26:08', '2025-06-20 11:26:08', NULL);
INSERT INTO `users` VALUES (8, 'kessler.prudence@example.net', '$2y$12$Hp77PTne88iA4f7dsjIyvuawPOtPmuK/bA2dUDf4NZse9wIc/bR1q', 'Dina', 'Kessler', 'Dina Kessler', '2016-12-30', '+1-973-992-4827', NULL, NULL, '0', '1978-04-07 17:15:43', '1999-07-22 07:17:41', NULL, 'yraRV0L80m', '2025-06-20 11:26:08', '2025-06-20 11:26:08', NULL);
INSERT INTO `users` VALUES (9, 'rafael13@example.com', '$2y$12$CoJ1d82HF6KGy3NykUHIRuNzB8tIehLKBURJbjxDo62QTgzm.44V.', 'Torrey', 'Ratke', 'Torrey Ratke', '1981-09-08', '+1 (364) 778-5117', '91367 Gislason Plains\nVonmouth, MN 94524-7281', NULL, '0', '2018-01-30 06:19:55', NULL, NULL, 'HIWlvpXibc', '2025-06-20 11:26:08', '2025-06-20 11:26:08', NULL);
INSERT INTO `users` VALUES (10, 'owen02@example.com', '$2y$12$tR7HT.y8Spl7lJ8GYCPtmeduoHWRrKp3j5hUNXTN7qqKeOsFcBSny', 'Mason', 'Muller', 'Mason Muller', '1998-12-29', '(551) 203-5819', '443 Garth Fort\nBryanaview, ID 79394', NULL, '0', '1970-03-08 22:59:53', '1987-05-08 22:55:55', NULL, 'HYbcet1PhT', '2025-06-20 11:26:08', '2025-06-20 11:26:08', NULL);
INSERT INTO `users` VALUES (11, 'aschuppe@example.net', '$2y$12$dgZvvfetn7cS82yZeTT3G.CCz9IyDnHHcZDH3JtlM66plY7lhlnD.', 'Kory', 'Keeling', 'Kory Keeling', '2006-07-28', NULL, NULL, NULL, '0', '1971-08-06 05:41:50', '2024-12-08 06:43:38', NULL, 'OOvwNtEnZN', '2025-06-20 11:26:09', '2025-06-20 11:26:09', NULL);
INSERT INTO `users` VALUES (12, 'stefan.zemlak@example.net', '$2y$12$Rb0O35ayioglXWATwCEvzOMZu3yB0NlmCwm7hGoMCKeezpNowRg8y', 'Corine', 'Kuvalis', 'Corine Kuvalis', '2001-05-06', NULL, '425 Noah Unions\nHeathcoteburgh, VA 08522', NULL, '0', '1982-12-10 22:54:12', '2003-12-21 00:36:14', '2008-07-19 00:05:07', '6baswafwKo', '2025-06-20 11:26:09', '2025-06-20 11:26:09', NULL);
INSERT INTO `users` VALUES (13, 'eichmann.horace@example.com', '$2y$12$exTMsQ4mRj4SSctGElt6p.rkT9e1QSiFRP7gcuy2H5iUw3DXZ4hxC', 'Loraine', 'Bode', 'Loraine Bode', '2013-10-01', NULL, NULL, NULL, '0', NULL, '1971-05-14 17:10:37', '1995-08-19 10:32:37', 'o7FL2gyui6', '2025-06-20 11:26:09', '2025-06-20 11:26:09', NULL);
INSERT INTO `users` VALUES (14, 'swaniawski.antone@example.com', '$2y$12$gz4zGT7Dof1eSZ8PMwN.Quyg3RO0DDsjc.9C77HZDs9Z5FjdIXZSG', 'Jarret', 'Dach', 'Jarret Dach', '1990-07-28', NULL, '4679 Daugherty Extension Suite 113\nLake Brettfort, CA 64965-4134', NULL, '0', '1989-05-24 12:04:47', NULL, '1974-11-10 08:15:10', 'FcqkefL586', '2025-06-20 11:26:09', '2025-06-20 11:26:09', NULL);
INSERT INTO `users` VALUES (15, 'wkub@example.org', '$2y$12$rdCaYSbwOQMQss0VV/K5jO14Y53bVAt94NwT8TMzEjfRM89E9jRZC', 'Afton', 'Bashirian', 'Afton Bashirian', '1986-06-17', '+1-424-565-0890', '45322 Anthony Stravenue Suite 495\nWest Coltmouth, AL 38090', NULL, '0', NULL, NULL, NULL, 'pAf2tb9MPw', '2025-06-20 11:26:09', '2025-06-20 11:26:09', NULL);
INSERT INTO `users` VALUES (16, 'harber.granville@example.org', '$2y$12$n7UU37pvk9o0Fu9oBtZ7NOwjcFyJnXfV4QvtdLmRpk0r0Y7mop2ra', 'Melyna', 'Strosin', 'Melyna Strosin', '1992-10-08', '+1 (520) 850-6697', NULL, NULL, '0', '2012-12-06 02:09:51', NULL, NULL, 'A9UvTNQxb2', '2025-06-20 11:26:10', '2025-06-20 11:26:10', NULL);
INSERT INTO `users` VALUES (17, 'torphy.chelsea@example.com', '$2y$12$o0CCF8whV/v0ayIfxvA20Ob7HwgIhSHltAgc.0AGAIevXyleSCf7y', 'Geoffrey', 'Pacocha', 'Geoffrey Pacocha', '1998-04-10', '847-937-6382', NULL, NULL, '0', NULL, NULL, '1999-06-16 08:13:51', 'aT4cz3Tzv7', '2025-06-20 11:27:09', '2025-06-20 11:27:09', NULL);
INSERT INTO `users` VALUES (18, 'sziemann@example.com', '$2y$12$.ozqdnE600WLTmxgGyZaDO7wRidX8dnTkmtvSfhL2ua/EGIJYs/3S', 'Dixie', 'Bruen', 'Dixie Bruen', '1978-03-27', NULL, NULL, NULL, '0', '1978-01-18 17:58:22', '2011-04-16 11:22:51', '2014-02-21 12:31:26', 'yUCfsrjOxA', '2025-06-20 11:26:10', '2025-06-20 11:26:10', NULL);
INSERT INTO `users` VALUES (19, 'vincenza.mohr@example.net', '$2y$12$2HaphJR8U6nEDUZhcuatLurOSE8RUW1koYyu3fo4SIgSMMVoP36d.', 'Christine', 'Breitenberg', 'Christine Breitenberg', '2014-06-09', NULL, NULL, NULL, '0', '1979-11-04 03:45:05', '1972-10-06 11:33:00', NULL, 'pocpIfP5gQ', '2025-06-20 11:26:10', '2025-06-20 11:26:10', NULL);
INSERT INTO `users` VALUES (20, 'adella.lubowitz@example.com', '$2y$12$kib/Cvu0NkWn8VPe90WMPe1iQTHRcn4hCSBjU08FIADDEOGivs54u', 'Emmanuel', 'Hoeger', 'Emmanuel Hoeger', '1980-09-23', NULL, NULL, NULL, '0', '1973-07-06 19:16:41', '2004-05-22 05:07:27', '2014-06-20 02:23:13', 'C7RSNCBR9y', '2025-06-20 11:26:11', '2025-06-20 11:26:11', NULL);
INSERT INTO `users` VALUES (21, 'alvena.ziemann@example.net', '$2y$12$QQqV90RvqV9PHNqONpvFIetotlvgAYb0DcRNaYVGfYlfFvC7iECDe', 'Maggie', 'O\'Connell', 'Maggie O\'Connell', '1990-01-04', '412.245.2904', NULL, NULL, '0', NULL, '1990-05-18 14:00:31', NULL, 'IeUkhzsSf7', '2025-06-20 11:26:11', '2025-06-20 11:26:11', NULL);
INSERT INTO `users` VALUES (22, 'ashley57@example.org', '$2y$12$Mqe.U0aBjshlpdrgd53dPOixMlEZ4xd7kCUgfCK106xio7pTgmI3y', 'Christine', 'Wilkinson', 'Christine Wilkinson', '2019-04-24', '435-633-1217', NULL, NULL, '0', '1988-11-20 09:45:14', NULL, NULL, '3ioz66gUCg', '2025-06-20 11:26:11', '2025-06-20 11:26:11', NULL);
INSERT INTO `users` VALUES (23, 'jaclyn.jerde@example.com', '$2y$12$heI68Pct9JLNncYkEA7S4e7aH7x4pEpja797fMIQTxpwdU0i0YB1S', 'Rozella', 'Denesik', 'Rozella Denesik', '1979-09-24', NULL, NULL, NULL, '0', '2023-11-29 15:06:29', NULL, NULL, 'oUhrzPy3kX', '2025-06-20 11:26:11', '2025-06-20 11:26:11', NULL);
INSERT INTO `users` VALUES (24, 'daniel.michele@example.net', '$2y$12$g.fnsJkOSgk4B3dTQvXonO/dx7smvweHMxIIq7VP7JfaCSChopYM6', 'Dessie', 'Hessel', 'Dessie Hessel', '1993-07-16', NULL, NULL, NULL, '0', NULL, NULL, NULL, 'Z72KEPkIVb', '2025-06-20 11:26:12', '2025-06-20 11:26:12', NULL);
INSERT INTO `users` VALUES (25, 'pjakubowski@example.com', '$2y$12$e3tf4Z.Lj95s6wLl/B48JevLyaA0Yapv1duyNHylPXeTNTAZAwG2O', 'Simone', 'Mante', 'Simone Mante', '1998-07-17', NULL, '12936 Bechtelar Road\nNorth Lexie, HI 92715', NULL, '0', '2018-09-14 08:49:58', NULL, NULL, 'nkXLvbMhIn', '2025-06-20 11:26:12', '2025-06-20 11:26:12', NULL);
INSERT INTO `users` VALUES (26, 'demond77@example.net', '$2y$12$XNj3QxXzKyvmsx18kaJiiuS/jlun//Kw2tUUqBDQa8cOHVBKdbOLW', 'Cletus', 'Smith', 'Cletus Smith', '1979-12-03', NULL, NULL, NULL, '0', '2014-04-15 07:25:31', NULL, NULL, 'XzaahBpBkH', '2025-06-20 11:26:12', '2025-06-20 11:26:12', NULL);
INSERT INTO `users` VALUES (27, 'ohara.jeanne@example.org', '$2y$12$YgPDprd//yi/xCiqxR5bEO2wHhR0CHrZavSfQhoyFPKQaYbJls16q', 'Magdalena', 'Gulgowski', 'Magdalena Gulgowski', '2021-03-27', '(660) 703-3793', NULL, NULL, '0', '2008-05-02 00:01:16', NULL, NULL, 'pjXaDUtzeb', '2025-06-20 11:26:12', '2025-06-20 11:26:12', NULL);
INSERT INTO `users` VALUES (28, 'ferry.kaylee@example.com', '$2y$12$g5qBt3Miwqr1NXQlHNxoNuDJ7lGKKQ3Nrkg7jxA6v5VeYdokgU9ZC', 'Darion', 'Ratke', 'Darion Ratke', '2006-02-01', '+1-551-978-8995', '26713 Ferry Union Apt. 135\nLarkinhaven, AR 60924', NULL, '0', NULL, NULL, '1991-07-15 05:34:02', 'ptrhC1n15d', '2025-06-20 11:26:13', '2025-06-20 11:26:13', NULL);
INSERT INTO `users` VALUES (29, 'corrine30@example.net', '$2y$12$8yI1qkCE/kMuo7oSmwl8seBelgDTSQO.BlPMsy5.ly9A4bdzvrjCu', 'Salvador', 'Hamill', 'Salvador Hamill', '2015-02-11', '1-707-691-8029', '269 Mikel Shores Suite 592\nEast Augustus, VA 02631', NULL, '0', '1971-04-03 09:08:19', NULL, '1996-07-17 03:05:25', 'YxKwjFyg1I', '2025-06-20 11:26:13', '2025-06-20 11:26:13', NULL);
INSERT INTO `users` VALUES (30, 'reynold94@example.com', '$2y$12$dYSgt4EDZV6oqY8DKaslL.qnLXhXHYvbxHmSVXv4/1iIGJaulXN6i', 'Daren', 'Deckow', 'Daren Deckow', '2022-05-08', NULL, '781 Palma Orchard\nNew Aliyah, SD 83726', NULL, '0', '2008-01-28 06:47:15', NULL, NULL, 'ByJ6dtCbpb', '2025-06-20 11:26:13', '2025-06-20 11:26:13', NULL);
INSERT INTO `users` VALUES (31, 'vskiles@example.org', '$2y$12$sVrB1cKH6KQW5bH2wrJ9lu7wmFK4M1lJARaO1kh/T1KN9WUM0YuiC', 'Kristofer', 'Marquardt', 'Kristofer Marquardt', '1987-07-09', NULL, NULL, NULL, '0', NULL, '2021-04-05 13:12:45', NULL, '8Da6uxCmrI', '2025-06-20 11:26:13', '2025-06-20 11:26:13', NULL);
INSERT INTO `users` VALUES (32, 'elyse.grant@example.net', '$2y$12$JlTwxS/zg8Q8jmW.iplDAexLwFY7VDQfOjBEHOx3RJxnUUUN17pra', 'Rhea', 'Grant', 'Rhea Grant', '1977-04-17', NULL, '4226 Wilkinson Highway\nLake Evastad, NJ 24717-1711', NULL, '0', NULL, NULL, '2010-12-11 10:29:52', 'RQFI6GtwRx', '2025-06-20 11:26:13', '2025-06-20 11:26:13', NULL);
INSERT INTO `users` VALUES (33, 'hreinger@example.com', '$2y$12$T/jakAYjYSDOjHEqy6y0ROcCtimGX4063zAeGyUGFfIaTg/Z037Fa', 'Rosina', 'VonRueden', 'Rosina VonRueden', '1994-04-14', NULL, NULL, NULL, '0', '1980-03-14 12:14:25', '2015-08-31 16:37:39', '1974-07-19 01:47:36', 'ZtTyq0lqR9', '2025-06-20 11:26:14', '2025-06-20 11:26:14', NULL);
INSERT INTO `users` VALUES (34, 'rachael21@example.org', '$2y$12$znpU52CagJTk176JWBPQpOz3ldqCXy.cJ/JNZCGy4mZ7lmMqbUDQS', 'Clement', 'Farrell', 'Clement Farrell', '2011-11-20', NULL, '5139 Sabrina Prairie\nSouth Cora, AL 22980-2280', NULL, '0', NULL, '1973-10-26 00:43:44', '1989-07-30 10:38:38', 'gyDtSjRT5B', '2025-06-20 11:26:14', '2025-06-20 11:26:14', NULL);
INSERT INTO `users` VALUES (35, 'hane.mathias@example.com', '$2y$12$gEipnP9NevBk1YMTjLD5vOx9HyzdZMqPitthZrEbRhiAMD8YJxvRi', 'Jillian', 'Waelchi', 'Jillian Waelchi', '1983-10-18', NULL, '2859 Waters Brook\nZulaside, NJ 54637', NULL, '0', NULL, NULL, NULL, 'lEaLXhpTA3', '2025-06-20 11:26:14', '2025-06-20 11:26:14', NULL);
INSERT INTO `users` VALUES (36, 'lauren31@example.net', '$2y$12$h0IbdlHdL9UtygYaWqzmiuzuaz3NX3Wafb4863IwR2U6sd.08lP7u', 'Daphnee', 'Reichel', 'Daphnee Reichel', '1979-02-04', '+1-986-654-3508', NULL, NULL, '0', '1971-08-10 22:31:01', NULL, NULL, 'Y6d1kV2uvN', '2025-06-20 11:26:14', '2025-06-20 11:26:14', NULL);
INSERT INTO `users` VALUES (37, 'fritsch.colby@example.com', '$2y$12$7NkMfYWbix94IcUicQiysefzDO.yYDwJvGSKX1nmVmEq8/.maES2y', 'Drake', 'Schoen', 'Drake Schoen', '2018-05-22', '1-504-804-3593', '55260 Burnice Island\nNorth Vivamouth, MN 86830-4253', NULL, '0', '2020-03-01 20:04:27', NULL, NULL, '5krBKJ43QJ', '2025-06-20 11:26:15', '2025-06-20 11:26:15', NULL);
INSERT INTO `users` VALUES (38, 'corbin.auer@example.com', '$2y$12$hvvQH/sfFNrbuQAvgpfnDei7NosS6lOIw92OyW/LgNEZg3N9rTnny', 'Brayan', 'Gerlach', 'Brayan Gerlach', '2000-08-18', '(248) 640-1196', '71413 Jacobson Viaduct\nEast Nelleport, SD 78983', NULL, '0', NULL, '2000-12-27 15:07:57', NULL, 'CmLp1pshVt', '2025-06-20 11:27:14', '2025-06-20 11:27:14', NULL);
INSERT INTO `users` VALUES (39, 'mmayert@example.net', '$2y$12$A2.AdFmIpInq8jSV5FciDuBhK.LBYAtksJh0FrRq6bw388MVKd.qO', 'Benedict', 'Fahey', 'Benedict Fahey', '2009-01-01', NULL, NULL, NULL, '0', '2019-05-21 05:25:56', NULL, '2005-07-14 03:30:50', 'N7kCQwSFMo', '2025-06-20 11:26:15', '2025-06-20 11:26:15', NULL);
INSERT INTO `users` VALUES (40, 'kozey.kayley@example.org', '$2y$12$MQ3EjnJ4NxFgvhw2jlMmg.6b7z8ic8SzlCQS1dcwG2UBLUySOqHUu', 'Theodore', 'Feil', 'Theodore Feil', '2006-12-06', '(561) 601-5351', NULL, NULL, '0', '2009-09-11 18:37:32', '2000-11-15 10:56:12', NULL, '98UyXAg5ed', '2025-06-20 11:26:15', '2025-06-20 11:26:15', NULL);
INSERT INTO `users` VALUES (41, 'chaz14@example.org', '$2y$12$P0aQf/ZCKnReOWHn0vDDQ.BnS.3Z9Y6RDTkB51QgwP4S1rhZ3ub.u', 'Forrest', 'Romaguera', 'Forrest Romaguera', '2001-09-25', NULL, '1968 Rylan Summit Suite 275\nRollinborough, RI 63151-6977', NULL, '0', NULL, '1984-02-04 15:29:27', NULL, 'TC9QDZxP0D', '2025-06-20 11:26:16', '2025-06-20 11:26:16', NULL);
INSERT INTO `users` VALUES (42, 'ewald84@example.com', '$2y$12$Q4HO81fcxrScbIzhS3mFYOdqbgwt7vfniHPgq6DeWxBWCJBdszt7i', 'Salvatore', 'Rau', 'Salvatore Rau', '2001-09-27', '+18587907273', '911 Funk Fields\nVictormouth, CO 64455-6522', NULL, '0', NULL, '1977-09-11 06:26:44', NULL, '16suE6Kcaz', '2025-06-20 11:26:16', '2025-06-20 11:26:16', NULL);
INSERT INTO `users` VALUES (43, 'mjohnston@example.net', '$2y$12$Hisj3sL4Rg8X4u/8KQ5uC.9U8IqimWKwKCxdljSEaAnwehi8aCoHe', 'Nicola', 'Quigley', 'Nicola Quigley', '1994-11-04', NULL, NULL, NULL, '0', '2021-08-21 20:21:35', NULL, '2008-01-08 17:26:56', '75ASMhL9OV', '2025-06-20 11:26:16', '2025-06-20 11:26:16', NULL);
INSERT INTO `users` VALUES (44, 'uerdman@example.com', '$2y$12$VWtgW/pAWaO9.gQRQuOdnuiw5.JE5z96TsJgaAWbRLdD5b7p0Tuo.', 'Katlynn', 'Hessel', 'Katlynn Hessel', '2009-06-02', '+1 (531) 542-8785', '596 Nolan Drives\nNew Russellville, ME 25292-7210', NULL, '0', '1988-11-03 02:27:13', NULL, NULL, 'scQjLtz4vR', '2025-06-20 11:26:16', '2025-06-20 11:26:16', NULL);
INSERT INTO `users` VALUES (45, 'bechtelar.anabelle@example.com', '$2y$12$6PM53UauGGW2kDuzAXM.Ueohwaq2/rpA5uVhDv2TBIk8G5Rcijx8K', 'Jillian', 'Wilderman', 'Jillian Wilderman', '1983-01-07', '1-283-642-4434', NULL, NULL, '0', NULL, '1993-01-03 19:27:07', NULL, 'yIWd68FtkO', '2025-06-20 11:26:17', '2025-06-20 11:26:17', NULL);
INSERT INTO `users` VALUES (46, 'lueilwitz.alf@example.org', '$2y$12$HuRIiZ/GERv6bHR1BGZpo.HtmXcKnamCkbbNmodN4tUwXt5sySVTW', 'Helen', 'Paucek', 'Helen Paucek', '2016-05-26', NULL, NULL, NULL, '0', NULL, NULL, '1992-06-01 04:25:06', 'Xgg1Y3V4C7', '2025-06-20 11:26:17', '2025-06-20 11:26:17', NULL);
INSERT INTO `users` VALUES (47, 'maggie31@example.com', '$2y$12$pJitjT7ilEVWT0AcCQjuOO.2PBmF8mcP4fJWwRNY9S3bYdsrJCqG.', 'Nils', 'Schroeder', 'Nils Schroeder', '1998-08-25', NULL, NULL, NULL, '0', '1981-04-04 12:22:50', NULL, '1979-10-06 06:55:52', '16dt4BAXsU', '2025-06-20 11:26:17', '2025-06-20 11:26:17', NULL);
INSERT INTO `users` VALUES (48, 'rutherford.miles@example.org', '$2y$12$OmWeSLqJIjYXVGR2HM.vduj.yUCV/5vdJmNYAF3J6gbNdY8FA108i', 'Mckenna', 'Bernhard', 'Mckenna Bernhard', '2019-05-09', '283-742-6729', '8708 Juwan Parkways\nNew Murphy, MD 01406', NULL, '0', '2005-03-07 23:06:11', '1978-08-07 12:12:49', NULL, 'XNkwPdUMuP', '2025-06-20 11:26:17', '2025-06-20 11:26:17', NULL);
INSERT INTO `users` VALUES (49, 'clair.barrows@example.com', '$2y$12$7xQlM5Jnd5KxXwipXzVTdOZiuJwREt89CmQbpUcSamUV.M4ZhB.xC', 'Russel', 'Littel', 'Russel Littel', '2002-08-30', NULL, NULL, NULL, '0', '2000-05-27 03:01:03', '2024-04-27 13:14:57', NULL, 'Fa5YfLpELJ', '2025-06-20 11:26:18', '2025-06-20 11:26:18', NULL);
INSERT INTO `users` VALUES (50, 'johns.nico@example.org', '$2y$12$OSTaoFCniZYBWCgae1BZj.s94jU.c1Whta5URDl25r0h68hbBHIgS', 'Hazel', 'Wuckert', 'Hazel Wuckert', '1980-10-17', '626.618.4549', '547 Barrett Skyway\nStarkberg, AR 94761', NULL, '0', NULL, NULL, NULL, 'kONCEmAhHW', '2025-06-20 11:26:18', '2025-06-20 11:26:18', NULL);
INSERT INTO `users` VALUES (51, 'lesly91@example.net', '$2y$12$uS5DaKRsKWVzip8gHbAtcuwQHfQFNIw4DYB3.1NBeXTffgSrD5dVK', 'Magali', 'Bergnaum', 'Magali Bergnaum', '1979-09-20', '1-713-392-3733', NULL, NULL, '0', NULL, NULL, NULL, 'dAFVF1xodA', '2025-06-20 11:26:18', '2025-06-20 11:26:18', NULL);

SET FOREIGN_KEY_CHECKS = 1;

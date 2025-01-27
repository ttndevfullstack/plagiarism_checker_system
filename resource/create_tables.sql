CREATE TABLE `user` (
  `user_id` int PRIMARY KEY,
  `username` string UNIQUE,
  `password_hash` string,
  `email` string UNIQUE,
  `full_name` string,
  `role_id` int,
  `last_login_timestamp` datetime,
  `profile_updated` datetime,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `role` (
  `role_id` int PRIMARY KEY,
  `role_name` string UNIQUE,
  `permissions` json
);

CREATE TABLE `user_report` (
  `report_id` int PRIMARY KEY,
  `user_id` int,
  `document_id` int,
  `submission_timestamp` datetime,
  `status` string,
  `plagiarism_percentage` float,
  `report_type` string
);

CREATE TABLE `plagiarism_check` (
  `check_id` int PRIMARY KEY,
  `report_id` int,
  `check_type` string,
  `plagiarism_percentage` float,
  `checked_timestamp` datetime
);

CREATE TABLE `document` (
  `document_id` int PRIMARY KEY,
  `user_id` int,
  `title` string,
  `file_path` string,
  `upload_timestamp` datetime,
  `last_modified` datetime,
  `document_type` string
);

CREATE TABLE `plagiarism_report` (
  `report_id` int PRIMARY KEY,
  `user_id` int,
  `report_date` datetime,
  `total_plagiarism_percentage` float,
  `class_id` int,
  `lecturer_id` int,
  `subject_id` int
);

CREATE TABLE `class` (
  `class_id` int PRIMARY KEY,
  `class_name` string,
  `lecturer_id` int
);

CREATE TABLE `lecturer` (
  `lecturer_id` int PRIMARY KEY,
  `full_name` string,
  `email` string
);

CREATE TABLE `subject` (
  `subject_id` int PRIMARY KEY,
  `subject_name` string,
  `subject_code` string
);

CREATE TABLE `feedback` (
  `feedback_id` int PRIMARY KEY,
  `user_id` int,
  `feedback_text` string,
  `submitted_at` datetime
);

CREATE TABLE `complaint` (
  `complaint_id` int PRIMARY KEY,
  `user_id` int,
  `complaint_text` string,
  `submitted_at` datetime,
  `resolved` boolean
);

CREATE TABLE `notification` (
  `notification_id` int PRIMARY KEY,
  `user_id` int,
  `message` string,
  `read_status` boolean,
  `created_at` datetime,
  `scheduled_at` datetime,
  `event_type` string
);

CREATE TABLE `notification_settings` (
  `user_id` int,
  `email_notifications` boolean,
  `push_notifications` boolean,
  `sms_notifications` boolean,
  `daily_digest` boolean
);

CREATE TABLE `permission` (
  `permission_id` int PRIMARY KEY,
  `permission_name` string
);

CREATE TABLE `role_permission` (
  `role_id` int,
  `permission_id` int
);

CREATE TABLE `access_request` (
  `request_id` int PRIMARY KEY,
  `user_id` int,
  `requested_permission_id` int,
  `request_date` datetime,
  `status` string
);

CREATE TABLE `report_statistics` (
  `statistic_id` int PRIMARY KEY,
  `class_id` int,
  `lecturer_id` int,
  `subject_id` int,
  `total_reports` int,
  `total_plagiarism_count` int,
  `average_plagiarism_percentage` float,
  `report_date` datetime
);

CREATE TABLE `export_data` (
  `export_id` int PRIMARY KEY,
  `user_id` int,
  `export_type` string,
  `export_date` datetime,
  `report_id` int
);

ALTER TABLE `user_report` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

ALTER TABLE `user_report` ADD FOREIGN KEY (`document_id`) REFERENCES `document` (`document_id`);

ALTER TABLE `plagiarism_check` ADD FOREIGN KEY (`report_id`) REFERENCES `user_report` (`report_id`);

ALTER TABLE `document` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

ALTER TABLE `plagiarism_report` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

ALTER TABLE `plagiarism_report` ADD FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`);

ALTER TABLE `plagiarism_report` ADD FOREIGN KEY (`lecturer_id`) REFERENCES `lecturer` (`lecturer_id`);

ALTER TABLE `plagiarism_report` ADD FOREIGN KEY (`subject_id`) REFERENCES `subject` (`subject_id`);

ALTER TABLE `class` ADD FOREIGN KEY (`lecturer_id`) REFERENCES `lecturer` (`lecturer_id`);

ALTER TABLE `feedback` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

ALTER TABLE `complaint` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

ALTER TABLE `notification` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

ALTER TABLE `notification_settings` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

ALTER TABLE `role_permission` ADD FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`);

ALTER TABLE `role_permission` ADD FOREIGN KEY (`permission_id`) REFERENCES `permission` (`permission_id`);

ALTER TABLE `access_request` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

ALTER TABLE `access_request` ADD FOREIGN KEY (`requested_permission_id`) REFERENCES `permission` (`permission_id`);

ALTER TABLE `report_statistics` ADD FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`);

ALTER TABLE `report_statistics` ADD FOREIGN KEY (`lecturer_id`) REFERENCES `lecturer` (`lecturer_id`);

ALTER TABLE `report_statistics` ADD FOREIGN KEY (`subject_id`) REFERENCES `subject` (`subject_id`);

ALTER TABLE `export_data` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

ALTER TABLE `export_data` ADD FOREIGN KEY (`report_id`) REFERENCES `plagiarism_report` (`report_id`);

CREATE TABLE users (
  id INT AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE training_centers (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  address VARCHAR(255),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE students (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  phone VARCHAR(20),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE teachers (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  phone VARCHAR(20),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE training_schedules (
  id INT AUTO_INCREMENT,
  date DATE NOT NULL,
  time TIME NOT NULL,
  training_center_id INT,
  teacher_id INT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (training_center_id) REFERENCES training_centers(id),
  FOREIGN KEY (teacher_id) REFERENCES teachers(id)
);

CREATE TABLE user_permissions (
  user_id INT,
  page_name VARCHAR(255) NOT NULL,
  PRIMARY KEY (user_id, page_name),
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE training_enrollments (
  id INT AUTO_INCREMENT,
  student_id INT,
  training_schedule_id INT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (student_id) REFERENCES students(id),
  FOREIGN KEY (training_schedule_id) REFERENCES training_schedules(id)
);

CREATE TABLE evaluations (
  id INT AUTO_INCREMENT,
  training_schedule_id INT,
  rating INT NOT NULL,
  comment TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (training_schedule_id) REFERENCES training_schedules(id)
);

INSERT INTO users (username, email, password, role)
VALUES ('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO training_centers (name, address)
VALUES ('مركز تدريب 1', 'شارع 1'),
       ('مركز تدريب 2', 'شارع 2');

INSERT INTO students (name, email, phone)
VALUES ('طالب 1', 'student1@example.com', '0123456789'),
       ('طالب 2', 'student2@example.com', '0987654321');

INSERT INTO teachers (name, email, phone)
VALUES ('معلم 1', 'teacher1@example.com', '0123456789'),
       ('معلم 2', 'teacher2@example.com', '0987654321');

INSERT INTO training_schedules (date, time, training_center_id, teacher_id)
VALUES ('2024-01-01', '09:00:00', 1, 1),
       ('2024-01-02', '10:00:00', 2, 2);

INSERT INTO user_permissions (user_id, page_name)
VALUES (1, 'الرئيسية'),
       (1, 'قائمة الطلاب'),
       (1, 'قائمة المعلمين'),
       (1, 'قائمة مواعيد التدريب'),
       (1, 'التسجيل في التدريب'),
       (1, 'التقييم');
CREATE DATABASE doingsdone
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE doingsdone;

CREATE TABLE users (
   id int AUTO_INCREMENT PRIMARY KEY,
   reg_date datetime NOT NULL,
   email char(128) NOT NULL,
   user_name char(50),
   user_pass char(64),
   contacts text
);

CREATE TABLE projects (
   id int AUTO_INCREMENT PRIMARY KEY,
   project_name char(20) NOT NULL,
   user_id int NOT NULL,
   FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE tasks (
   id int AUTO_INCREMENT PRIMARY KEY,
   creation_date datetime NOT NULL, 
   done_date datetime NULL DEFAULT NULL,
   task_name char(60) NOT NULL,
   file_name char(255),
   completion_date datetime,
   user_id int NOT NULL,
   project_id int NOT NULL,
   FOREIGN KEY (user_id) REFERENCES users(id),
   FOREIGN KEY (project_id) REFERENCES projects(id)
);

CREATE UNIQUE INDEX email ON users(email);
CREATE UNIQUE INDEX usrprj ON projects(project_name, user_id);

CREATE INDEX u_mailpass ON users(email, user_pass);
CREATE INDEX p_user ON projects(user_id);
CREATE INDEX t_description ON tasks(task_name);
CREATE INDEX t_completion ON tasks(completion_date);
CREATE INDEX t_proj_user ON tasks(project_id, user_id);

CREATE FULLTEXT INDEX task_ft_search ON tasks(task_name);

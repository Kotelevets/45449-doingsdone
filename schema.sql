CREATE DATABASE doingsdone
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE doingsdone;

CREATE TABLE users (
   user_id int AUTO_INCREMENT PRIMARY KEY,
   reg_date datetime,
   email char(128),
   name char(50),
   user_pass char(64),
   contacts text
);

CREATE TABLE projects (
   project_id int AUTO_INCREMENT PRIMARY KEY,
   project_name char(20),
   user_id int
);

CREATE TABLE tasks (
   task_id int AUTO_INCREMENT PRIMARY KEY,
   creation_date datetime, 
   done_date datetime,
   description char(60),
   file_name char(255),
   completion_date datetime,
   user_id int,
   project_id int
);

CREATE UNIQUE INDEX user_id ON users(user_id);
CREATE UNIQUE INDEX email ON users(email);
CREATE UNIQUE INDEX project_id ON projects(project_id);
CREATE UNIQUE INDEX task_id ON tasks(task_id);

CREATE INDEX u_mail ON users(email);
CREATE INDEX u_pass ON users(user_pass);
CREATE INDEX p_user ON projects(user_id);
CREATE INDEX t_description ON tasks(description);
CREATE INDEX t_completion ON tasks(completion_date);
CREATE INDEX t_proj_user ON tasks(project_id, user_id);

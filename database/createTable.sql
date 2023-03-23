CREATE TABLE Users (
  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  username varchar(255) NOT NULL,
  email varchar(255) NOT NULL,
  password varchar(255) NOT NULL,
  role varchar(255) NOT NULL,
  created_at datetime NOT NULL,
  updated_at datetime NOT NULL
);


CREATE TABLE Categories (
id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
name varchar(255) NOT NULL,
description varchar(255) NOT NULL,
created_at datetime NOT NULL,
updated_at datetime NOT NULL
);


CREATE TABLE Articles (
id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
title varchar(255) NOT NULL,
content text NOT NULL,
user_id int(11) NOT NULL,
FOREIGN KEY (user_id) REFERENCES Users(id),
category_id int(11) NOT NULL,
FOREIGN KEY (category_id) REFERENCES Categories(id),
upvotes int(11) NOT NULL DEFAULT 0,
created_at datetime NOT NULL,
updated_at datetime NOT NULL
);


CREATE TABLE Comments (
id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
content text NOT NULL,
user_id int(11) NOT NULL,
FOREIGN KEY (user_id) REFERENCES Users(id),
article_id int(11) NOT NULL,
FOREIGN KEY (article_id) REFERENCES Articles(id),
created_at datetime NOT NULL,
updated_at datetime NOT NULL
);


CREATE TABLE Votes (
id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
user_id int(11) NOT NULL,
FOREIGN KEY (user_id) REFERENCES Users(id),
article_id int(11) NOT NULL,
FOREIGN KEY (article_id) REFERENCES Articles(id),
vote int(11) NOT NULL,
created_at datetime NOT NULL,
updated_at datetime NOT NULL
);


CREATE TABLE Tags (
  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name varchar(255) NOT NULL,
  created_at datetime NOT NULL,
  updated_at datetime NOT NULL
);

CREATE TABLE ArticleTags (
  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  article_id int(11) NOT NULL,
  FOREIGN KEY (article_id) REFERENCES Articles(id),
  tag_id int(11) NOT NULL,
  FOREIGN KEY (tag_id) REFERENCES Tags(id),
  created_at datetime NOT NULL,
  updated_at datetime NOT NULL
);

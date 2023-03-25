CREATE TABLE Users (
  id SERIAL PRIMARY KEY,
  username VARCHAR(255),
  email VARCHAR(255),
  password VARCHAR(255),
  role VARCHAR(255) DEFAULT 'registered',
  created_at TIMESTAMP DEFAULT now(),
  updated_at TIMESTAMP DEFAULT now(),
  liked_articles TEXT DEFAULT ''
);

CREATE TABLE Articles (
  id SERIAL PRIMARY KEY,
  title VARCHAR(255),
  content TEXT,
  tags VARCHAR(255),
  user_id INTEGER REFERENCES Users(id),
  category_id INTEGER REFERENCES Categories(id),
  likes INTEGER DEFAULT 0,
  created_at TIMESTAMP DEFAULT now(),
  updated_at TIMESTAMP DEFAULT now()
);

CREATE TABLE Categories (
  id SERIAL PRIMARY KEY,
  name VARCHAR(255),
  description VARCHAR(255),
  created_at TIMESTAMP DEFAULT now(),
  updated_at TIMESTAMP DEFAULT now()
);

CREATE TABLE Comments (
  id SERIAL PRIMARY KEY,
  content TEXT,
  user_id INTEGER REFERENCES Users(id),
  article_id INTEGER REFERENCES Articles(id),
  created_at TIMESTAMP DEFAULT now(),
  updated_at TIMESTAMP DEFAULT now()
);

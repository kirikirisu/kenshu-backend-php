CREATE TABLE users(
  id SERIAL PRIMARY KEY,
  name varchar(100) NOT NULL,
  email varchar(100) NOT NULL,
  password varchar(200) NOT NULL CONSTRAINT valid_password_length CHECK(LENGTH(password) > 4),
  icon_url varchar(10000) NOT NULL
);

CREATE TABLE posts(
  id SERIAL PRIMARY KEY,
  user_id integer REFERENCES users (id) ON DELETE CASCADE,
  title varchar(100) NOT NULL,
  body varchar(8000) NOT NULL,
  thumbnail_id integer NOT NULL
);

CREATE TABLE images(
  id SERIAL PRIMARY KEY,
  post_id integer REFERENCES posts (id) ON DELETE CASCADE,
  url varchar(10000) NOT NULL 
);

CREATE TABLE tags(
  id SERIAL PRIMARY KEY,
  name varchar(100) NOT NULL
);

CREATE TABLE post_tags(
  post_id integer REFERENCES posts (id) ON DELETE CASCADE,
  tag_id integer REFERENCES tags (id),
  PRIMARY KEY(post_id, tag_id)
);

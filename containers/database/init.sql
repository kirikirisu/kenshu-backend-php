CREATE TABLE users(
  id SERIAL PRIMARY KEY,
  name varchar(100),
  email varchar(100),
  password varchar(200),
  icon_url varchar(10000)
);

CREATE TABLE posts(
  id SERIAL PRIMARY KEY,
  user_id integer REFERENCES users (id),
  title varchar(100),
  body varchar(8000),
  image_id integer
);

CREATE TABLE images(
  id SERIAL PRIMARY KEY,
  post_id integer REFERENCES posts (id),
  url varchar(10000) 
);

CREATE TABLE post_tags(
  post_id integer REFERENCES posts (id),
  tag_id integer REFERENCES tags (id),
  PRIMARY KEY(post_id, tag_id)
);

CREATE TABLE tags(
  id SERIAL PRIMARY KEY,
  name varchar(100)
);

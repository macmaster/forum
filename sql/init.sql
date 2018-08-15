CREATE TABLE users (
    user_id 	INT(8) NOT NULL AUTO_INCREMENT,
	user_name	VARCHAR(30) NOT NULL,
	user_pass 	VARCHAR(255) NOT NULL,
	user_email	VARCHAR(255) NOT NULL,
	user_date	DATETIME NOT NULL,
	user_level	INT(8) NOT NULL,
	user_img	 VARCHAR(300) DEFAULT "https://i.ytimg.com/vi/TIwqn2HXvNg/hqdefault.jpg" NOT NULL,
	UNIQUE INDEX user_name_unique (user_name),
	PRIMARY KEY (user_id)
);

CREATE TABLE categories (
	cat_id		INT(8) NOT NULL AUTO_INCREMENT,
	cat_name	VARCHAR(255) NOT NULL,
	cat_description	VARCHAR(255) NOT NULL,
	UNIQUE INDEX cat_name_unique (cat_name),
	PRIMARY KEY (cat_id)
);

CREATE TABLE topics( 
	topic_id 		INT(8) NOT NULL AUTO_INCREMENT,
	topic_subject 	VARCHAR(255) NOT NULL,
	topic_date		DATETIME NOT NULL,
	topic_cat		INT(8) NOT NULL,
	topic_by 		INT(8) NOT NULL,
	PRIMARY KEY (topic_id)
);

CREATE TABLE posts ( 
	post_id			INT(8) NOT NULL AUTO_INCREMENT,
	post_content 	VARCHAR(255) NOT NULL,
	post_date		DATETIME NOT NULL,
	post_topic		INT(8) NOT NULL,
	post_by 		INT(8) NOT NULL,
	post_user	 	VARCHAR(30) NOT NULL,
	PRIMARY KEY (post_id)
);

/* Each topic has a category */
ALTER TABLE topics ADD FOREIGN KEY (topic_cat) REFERENCES categories(cat_id) 
ON DELETE CASCADE ON UPDATE CASCADE;

/* Each topic has a user. User never deleted beforehand. */
ALTER TABLE topics ADD FOREIGN KEY (topic_by) REFERENCES users(user_id) 
ON DELETE RESTRICT ON UPDATE CASCADE;

/* Every post has a topic. */
ALTER TABLE posts ADD FOREIGN KEY (post_topic) REFERENCES topics(topic_id) 
ON DELETE CASCADE ON UPDATE CASCADE;

/* Each post linked to user that made it */
ALTER TABLE posts ADD FOREIGN KEY (post_by) REFERENCES users(user_id)
ON DELETE RESTRICT ON UPDATE CASCADE;

/* Default Category */
INSERT INTO `categories` (`cat_id`, `cat_name`, `cat_description`) 
VALUES (NULL, 'trash', 'shit talking at its finest...');

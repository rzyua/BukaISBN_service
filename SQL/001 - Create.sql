DROP TABLE tagToBook;
DROP TABLE authorToBook;
DROP TABLE tag;
DROP TABLE author;
DROP TABLE book;
DROP TABLE publisher;
DROP TABLE users;

CREATE TABLE users (
	id INT NOT NULL AUTO_INCREMENT,
	login NVARCHAR(16) NOT NULL,
	password NVARCHAR(60) NOT NULL,
	firstName NVARCHAR(50) NOT NULL,
	lastName NVARCHAR(200),
	email NVARCHAR(50),
	adminRole BOOL NOT NULL,
	createdDate DATETIME NOT NULL,
	createdBy INT NOT NULL,
	modifiedDate DATETIME NOT NULL,
	modifiedBy INT NOT NULL,
	PRIMARY KEY (id),
	UNIQUE (login),
	FOREIGN KEY (createdBy) REFERENCES users(id),
	FOREIGN KEY (modifiedBy) REFERENCES users(id)
);

INSERT INTO users (login, password, firstName, lastName, email, adminRole, createdDate, createdBy, modifiedDate, modifiedBy) 
	VALUES ('zyla', '$2y$10$VapbzTJ/7foJ5Ib/Svc6J.rMvb6e1kAFmvlTV1YhkE0gkclW2Y1By', 'Marcin', NULL, 'lord.wajda@gmail.com', 1, NOW(), 1, NOW(), 1);

CREATE TABLE publisher (
	id INT NOT NULL AUTO_INCREMENT,
	name NVARCHAR(200) NOT NULL,
	createdDate DATETIME NOT NULL,
	createdBy INT NOT NULL,
	modifiedDate DATETIME NOT NULL,
	modifiedBy INT NOT NULL,
	PRIMARY KEY ( id ),
	FOREIGN KEY (createdBy) REFERENCES users(id),
	FOREIGN KEY (modifiedBy) REFERENCES users(id)
	);

CREATE TABLE book (
	id INT NOT NULL AUTO_INCREMENT,
	isbn NVARCHAR(13) NOT NULL,
	title NVARCHAR(255) NOT NULL,
	publisherId INT,
	year INT,
	other NVARCHAR(255),
	createdDate DATETIME NOT NULL,
	createdBy INT NOT NULL,
	modifiedDate DATETIME NOT NULL,
	modifiedBy INT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (publisherId) REFERENCES publisher(id),
	FOREIGN KEY (createdBy) REFERENCES users(id),
	FOREIGN KEY (modifiedBy) REFERENCES users(id)
	);

CREATE TABLE author (
	id INT NOT NULL AUTO_INCREMENT,
	firstName NVARCHAR(50) NOT NULL,
	lastName NVARCHAR(200) NOT NULL,
	createdDate DATETIME NOT NULL,
	createdBy INT NOT NULL,
	modifiedDate DATETIME NOT NULL,
	modifiedBy INT NOT NULL,
	PRIMARY KEY ( id ),
	FOREIGN KEY (createdBy) REFERENCES users(id),
	FOREIGN KEY (modifiedBy) REFERENCES users(id)
	);
	
CREATE TABLE tag (
	id INT NOT NULL AUTO_INCREMENT,
	name NVARCHAR(20) NOT NULL,
	createdDate DATETIME NOT NULL,
	createdBy INT NOT NULL,
	modifiedDate DATETIME NOT NULL,
	modifiedBy INT NOT NULL,
	PRIMARY KEY ( id ),
	FOREIGN KEY (createdBy) REFERENCES users(id),
	FOREIGN KEY (modifiedBy) REFERENCES users(id)
	);
	
CREATE TABLE authorToBook (
	authorId INT NOT NULL,
	bookId INT NOT NULL,
	createdDate DATETIME NOT NULL,
	createdBy INT NOT NULL,
	modifiedDate DATETIME NOT NULL,
	modifiedBy INT NOT NULL,
	FOREIGN KEY (authorId) REFERENCES author(id),
	FOREIGN KEY (bookId) REFERENCES book(id),
	FOREIGN KEY (createdBy) REFERENCES users(id),
	FOREIGN KEY (modifiedBy) REFERENCES users(id)
);

CREATE TABLE tagToBook (
	tagId INT NOT NULL,
	bookId INT NOT NULL,
	createdDate DATETIME NOT NULL,
	createdBy INT NOT NULL,
	modifiedDate DATETIME NOT NULL,
	modifiedBy INT NOT NULL,
	FOREIGN KEY (tagId) REFERENCES tag(id),
	FOREIGN KEY (bookId) REFERENCES book(id),
	FOREIGN KEY (createdBy) REFERENCES users(id),
	FOREIGN KEY (modifiedBy) REFERENCES users(id)
);

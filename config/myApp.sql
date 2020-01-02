
drop table if exists users;
drop table if exists products;
drop table if exists orders;
drop table if exists order_details;
drop table if exists favorites;
drop table if exists comments;

create table users (
	id int unsigned primary key auto_increment,
	name varchar(40),
	address varchar(255),
	email varchar(255),
	password varchar(255),
	CCNumber varchar(16)
);

create table products (
	id int unsigned primary key auto_increment,
	name varchar(20),
	image text,
	introduction varchar(255),
	price int unsigned
);

create table orders (
	order_id int unsigned primary key auto_increment,
	user_id int,
	total int,
	address varchar(255),
	date datetime
);

create table order_details (
	order_id int unsigned primary key auto_increment,
	product_id int,
	quantity int,
	subtotal int
);

create table favorites (
	product_id int,
	user_id int
);

create table comments (
	comment_id int unsigned primary key auto_increment,
	product_id int,
	user_id int,
	nickname varchar(50),
	comment varchar(140)
);

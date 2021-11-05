create table Products (
    id smallint unsigned AUTO_INCREMENT,
    name varchar(20) NOT NULL,
    price decimal(10,2) NOT NULL,
    qt mediumint unsigned NOT NULL,
    Primary key(id)
);

create table Sales (
    id BIGINT unsigned AUTO_INCREMENT,
    paid Decimal(10,2) NOT NULL,
    customer_name varchar(15) not null,
    customer_phone int(10) unsigned NOT NULL,
    customer_address varchar(30) NOT NULL,
    primary key(id)
);

create table Sales_items(
    sales_id BIGINT unsigned not null,
    product_id smallint unsigned not null,
    sales_price decimal(10,2) NOT NULL,
    qt mediumint unsigned not null,
    UNIQUE(sales_id, product_id),
    foreign key(sales_id) references Sales(id)
		ON DELETE CASCADE,
    foreign key(product_id) references Products(id)
);
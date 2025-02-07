CREATE DATABASE IF NOT EXISTS users;
use users;
CREATE TABLE accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    company_name VARCHAR(150),
    position VARCHAR(100),
    phone1 VARCHAR(20),
    phone2 VARCHAR(20),
    phone3 VARCHAR(20)
); 
insert into accounts(id, first_name, last_name, email, company_name, position, phone1,phone2,phone3)
values(2,"Wolter","White","Pinkman@gmail.com","Carwash","Boss",8788421,777777772,8777777773);



USE pbi_thermohygrometer_db;

-- Create user_tbl table
CREATE TABLE user_tbl (
    id INT IDENTITY(1,1) PRIMARY KEY,
    username varchar(50) UNIQUE,
    password varchar(50),
    currentloc VARCHAR(50),
    timestamp DATETIME DEFAULT GETDATE()
);

-- Create activity table
CREATE TABLE activity (
    id INT IDENTITY(1,1) PRIMARY KEY,
    name varchar(250),
    lat float,
    lng float,
    timestamp DATETIME DEFAULT GETDATE()
);

-- Create location table with foreign key constraints
CREATE TABLE location (
    id INT IDENTITY(1,1) PRIMARY KEY,
    user_id INT,
    lat VARCHAR(50),
    lon VARCHAR(50),
    distance VARCHAR(50),
    accuracy varchar(50),
    heading varchar(50),
    timestamp DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (user_id) REFERENCES user_tbl(id),
);

CREATE TABLE user_activity (
    id INT IDENTITY(1,1) PRIMARY KEY,
    user_id INT,
	activity_id INT, 
	what varchar(50),
	event varchar(50),
    timestamp DATETIME DEFAULT GETDATE(),
	FOREIGN KEY (user_id) REFERENCES user_tbl(id),
	FOREIGN KEY (activity_id) REFERENCES activity(id)
);


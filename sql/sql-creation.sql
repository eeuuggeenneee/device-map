USE pbi_thermohygrometer_db;

-- Create user_tbl table
CREATE TABLE user_tbl (
    id INT IDENTITY(1,1) PRIMARY KEY,
    username varchar(50) UNIQUE,
    password varchar(50),
    currentloc VARCHAR(50),
	fl_type varchar(50),
    timestamp DATETIME DEFAULT GETDATE()
);

-- Create activity table
CREATE TABLE activity (
    id INT IDENTITY(1,1) PRIMARY KEY,
	fl_type varchar(50),
    name varchar(250),
    lat float,
    lng float,
    timestamp DATETIME DEFAULT GETDATE()
);


-- Create location table with foreign key constraints
CREATE TABLE location (
    id INT IDENTITY(1,1) PRIMARY KEY,
	activity_id INT,
    user_id INT,
    lat VARCHAR(50),
    lon VARCHAR(50),
	fl_type VARCHAR(50),
    distance VARCHAR(50),
    accuracy varchar(50),
    heading varchar(50),
	time_epoch varchar(50) unique,
    timestamp DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (user_id) REFERENCES user_tbl(id),
	FOREIGN KEY (activity_id) REFERENCES activity(id),
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

CREATE TABLE login_history (
    id INT IDENTITY(1,1) PRIMARY KEY,
    user_id INT,
	type varchar(50),
	fl_type varchar(50),
    timestamp DATETIME DEFAULT GETDATE(),
	FOREIGN KEY (user_id) REFERENCES user_tbl(id),
);


CREATE TABLE dummy_location (
   id INT IDENTITY(1,1) PRIMARY KEY,
	activity_id INT,
    user_id INT,
    lat VARCHAR(50),
    lon VARCHAR(50),
	fl_type VARCHAR(50),
    distance VARCHAR(50),
    accuracy varchar(50),
    heading varchar(50),
	time_epoch varchar(50) unique,
    timestamp DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (user_id) REFERENCES user_tbl(id),
	FOREIGN KEY (activity_id) REFERENCES activity(id),
);
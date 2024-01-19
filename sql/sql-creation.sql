USE pbi_thermohygrometer_db;

-- Create user_tbl table
CREATE TABLE user_tbl (
    id INT IDENTITY(1,1) PRIMARY KEY,
	f_name varchar(50),
	l_name varchar(50),
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
    start_time DATETIME DEFAULT GETDATE(),
	end_time DATETIME,
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


CREATE VIEW user_duration AS
SELECT
    a.id,
    a.user_id,
    a.activity_id,
    a.start_time,
    a.end_time,
	b.name,
	CONCAT(c.f_name,' ',c.l_name) as fullname,
	FORMAT(a.start_time, 'yyyy-MM-dd') AS date,
    FORMAT(a.start_time, 'HH:mm:ss') AS formatted_start_time,
    FORMAT(a.end_time, ' HH:mm:ss') AS formatted_end_time,
    CASE
        WHEN DATEDIFF(SECOND, a.start_time, a.end_time) < 60 THEN
            CONCAT('0:', RIGHT('0' + CAST(DATEDIFF(SECOND, a.start_time, a.end_time) AS NVARCHAR), 2))
        ELSE
            CONCAT(CAST(DATEDIFF(MINUTE, a.start_time, a.end_time) AS NVARCHAR), ':', RIGHT('0' + CAST(DATEDIFF(SECOND, a.start_time, a.end_time) % 60 AS NVARCHAR), 2))
    END AS duration
FROM user_activity a
JOIN activity b ON a.activity_id = b.id
JOIN user_tbl c ON a.user_id = c.id;
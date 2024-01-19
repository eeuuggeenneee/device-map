use pbi_thermohygrometer_db

drop table user_tbl

drop table location

drop table activity
drop table login_history
drop table user_activity

INSERT INTO user_tbl (username, password)
VALUES ('admin', 'admin');

truncate table location
truncate table activity
truncate table user_activity

INSERT INTO user_tbl (username, password,f_name,l_name)
VALUES ('admin', 'admin','Eugene', 'Eugenio');

-- Inserting fake data into the activity table
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Idle','Moving', 40.7128, -74.0060, '2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Others','Others', 40.7128, -74.0060, '2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Reach Truck (Planter)','Tanggalin ang punong paleta sa filling line', 40.7128, -74.0060, '2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Reach Truck (Planter)','Itanim ang paleta sa charging circuit', 40.7128, -74.0060,'2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Reach Truck (Planter)','Maglagay ng paleta sa filling line', 40.7128, -74.0060,'2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Reach Truck (Planter)','Irefill ang mga condenser bottles at wire connectors sa filling line', 40.7128, -74.0060,'2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Reach Truck (Planter)','Ilipat ang naharvest na baterya sa staging area at ilagay ang slip sa pigeon hole', 40.7128, -74.0060,'2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Reach Truck (Harvester)','I-fork ang na-charge na baterya mula sa rack', 40.7128, -74.0060,'2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Reach Truck (Harvester)','Pumunta sa harvesting area', 40.7128, -74.0060,'2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Reach Truck (Harvester)','I-stack ang mga naharvest na baterya', 40.7128, -74.0060,'2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Reach Truck (Harvester)','Kunin ang condensers na may stillage', 40.7128, -74.0060,'2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Reach Truck (Harvester)','Kunin ang mist pads', 40.7128, -74.0060,'2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Reach Truck (Harvester)','Magtanim ng baterya sa Line 3', 40.7128, -74.0060,'2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Reach Truck (Harvester)','Magharvest ng baterya sa Line 3', 40.7128, -74.0060,'2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Forklift operator','I-load ang mga GB sa mga filling line', 40.7128, -74.0060,'2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Forklift operator','Linisin ang mga nagamit na paleta at spacers', 40.7128, -74.0060,'2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Forklift operator','Ilipat ang mga baterya mula sa staging area papunta sa endline', 40.7128, -74.0060,'2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Forklift operator','Ilipat ang mga baterya mula sa staging area papunta sa endline', 40.7128, -74.0060,'2024-01-16T10:30:00');
truncate table activity

select * from user_tbl
SELECT * FROM activity WHERE fl_type = 'Others' OR fl_type = 'Reach Truck (Planter)'
select * from activity
select * from user_activity
SET IDENTITY_INSERT activity ON;

UPDATE activity
SET id = 100
WHERE fl_type = 'Others';

UPDATE user_activity
SET end_time = 
WHERE end_time IS NULL AND user_id = 1;

SELECT * FROM user_activity
WHERE end_time IS NULL AND user_id = 1;

select * from login_history
	select * from location order by id desc

truncate table location
truncate table user_activity
truncate table login_history

SELECT * FROM user_activity

SELECT
    a.id,
    a.user_id,
    a.activity_id,
    a.start_time,
    a.end_time,
	b.name,
    FORMAT(a.start_time, 'HH:mm:ss') AS s_time,
    FORMAT(a.end_time, 'HH:mm:ss') AS e_time,
	FORMAT(a.end_time, 'HH:mm:ss') AS e_time,
    CASE
        WHEN DATEDIFF(SECOND, a.start_time, a.end_time) < 60 THEN
            CONCAT('0:', RIGHT('0' + CAST(DATEDIFF(SECOND, a.start_time, a.end_time) AS NVARCHAR), 2))
        ELSE
            CONCAT(CAST(DATEDIFF(MINUTE, a.start_time, a.end_time) AS NVARCHAR), ':', RIGHT('0' + CAST(DATEDIFF(SECOND, a.start_time, a.end_time) % 60 AS NVARCHAR), 2))
    END AS formatted_duration
FROM user_activity a
JOIN activity b ON a.activity_id = b.id
WHERE a.user_id = 1;


SELECT * FROM user_duration where user_id = 1;


SELECT * FROM dummy_location order by id desc

SELECT
    a.id,
    a.user_id,
    a.activity_id,
    a.start_time,
    a.end_time,
	b.name,
	CONCAT(c.f_name,' ',c.l_name) as fullname,
    FORMAT(a.start_time, 'yyyy-MM-dd HH:mm:ss') AS formatted_start_time,
    FORMAT(a.end_time, 'yyyy-MM-dd HH:mm:ss') AS formatted_end_time,
    CASE
        WHEN DATEDIFF(SECOND, a.start_time, a.end_time) < 60 THEN
            CONCAT('0:', RIGHT('0' + CAST(DATEDIFF(SECOND, a.start_time, a.end_time) AS NVARCHAR), 2))
        ELSE
            CONCAT(CAST(DATEDIFF(MINUTE, a.start_time, a.end_time) AS NVARCHAR), ':', RIGHT('0' + CAST(DATEDIFF(SECOND, a.start_time, a.end_time) % 60 AS NVARCHAR), 2))
    END AS formatted_duration
FROM user_activity a
JOIN activity b ON a.activity_id = b.id
JOIN user_tbl c ON a.user_id = c.id;
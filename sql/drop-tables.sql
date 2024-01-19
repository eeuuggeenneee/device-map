use pbi_thermohygrometer_db

drop table user_tbl

drop table location

drop table activity
drop table login_history
drop table user_activity

INSERT INTO user_tbl (username, password)
VALUES ('admin', 'admin');

truncate table user_tbl
INSERT INTO user_tbl (username, password,f_name,l_name)
VALUES ('admin', 'admin','Eugene', 'Eugenio');

-- Inserting fake data into the activity table
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('IDLE','Moving', 40.7128, -74.0060, '2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Reach Truck (Planter)','	Remove the completely filled pallet at acid filling endline', 40.7128, -74.0060, '2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Reach Truck (Planter)','	Load the pallet to the specified charging circuit as per Planter', 40.7128, -74.0060,'2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Reach Truck (Planter)','	Refill the empty pallets in the Acid filling endline (up to 5 pallets only)', 40.7128, -74.0060,'2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Reach Truck (Planter)','	Refill the condenser bottles and wire connectors in the acid filler endline', 40.7128, -74.0060,'2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Reach Truck (Planter)','	Transfer the harvested batteries to the harvest staging area and drop the slip on the pigeon hole', 40.7128, -74.0060,'2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Reach Truck (Harvester)','Fork the charged batteries from the rack', 40.7128, -74.0060,'2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Reach Truck (Harvester)','Travel going to harvesting area', 40.7128, -74.0060,'2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Reach Truck (Harvester)','Stack all the harvested batteries (wires and condensers are already removed)', 40.7128, -74.0060,'2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Reach Truck (Harvester)','Get the stillage containing condensers', 40.7128, -74.0060,'2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Reach Truck (Harvester)','Get the mist pads', 40.7128, -74.0060,'2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Reach Truck (Harvester)','Plant batteries on Line 3', 40.7128, -74.0060,'2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Reach Truck (Harvester)','Harvest batteries on Line 3', 40.7128, -74.0060,'2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Forklift operator','Loading the GB onto lines 2, 3, 4, 5, 6, and 8.', 40.7128, -74.0060,'2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Forklift operator','Retrieving used pallets and used cardboard spacers as part of the GHK.', 40.7128, -74.0060,'2024-01-16T10:30:00');
INSERT INTO activity (fl_type,name, lat, lng, timestamp) VALUES
('Forklift operator','Transferring the harvested items from the staging area to the end line.', 40.7128, -74.0060,'2024-01-16T10:30:00');

truncate table activity

select * from user_tbl

select * from activity
select * from user_activity

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
use pbi_thermohygrometer_db

drop table user_tbl

drop table location

drop table activity
drop table login_history
drop table user_activity

INSERT INTO user_tbl (username, password)
VALUES ('admin', 'admin');

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
select * from login_history
	select * from location order by id desc

truncate table activity
truncate table location
truncate table user_activity

SELECT
a.id,
a.user_id,
a.activity_id,
b.name,
a.what,
a.event,
a.timestamp,
c.id,
c.username
FROM
user_activity a
JOIN activity b 
ON a.activity_id = b.id
JOIN user_tbl c 
ON c.id =a.user_id 
where a.user_id = 1


truncate table activity

SELECT 
id,
user_id,
activity_id,
event,
timestamp
FROM user_activity

CREATE VIEW viewview AS
SELECT
id,
user_id
FROM
user_activity

SELECT * FROM dummy_location order by id desc
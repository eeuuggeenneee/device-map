use pbi_thermohygrometer_db

drop table user_tbl

drop table location

drop table activity

drop table user_activity

INSERT INTO user_tbl (username, password)
VALUES ('admin', 'admin');

-- Inserting fake data into the activity table
INSERT INTO activity (name, lat, lng, timestamp) VALUES
('Idle', 40.7128, -74.0060, '2024-01-16T10:30:00'),
('Running', 40.7128, -74.0060, '2024-01-16T10:30:00'),
('Cycling', 34.0522, -118.2437, '2024-01-16T11:15:00'),
('Swimming', 41.8781, -87.6298, '2024-01-16T12:00:00'),
('Hiking', 37.7749, -122.4194, '2024-01-16T13:45:00'),
('Yoga', 51.5074, -0.1278, '2024-01-16T14:30:00');

select * from user_tbl

select * from activity
select * from user_activity
select * from location order by id desc

SELECT
a.id,
a.user_id,
a.activity_id,
b.name,
a.what,
a.event,
a.timestamp
FROM
user_activity a
JOIN activity b
ON a.activity_id = b.id where a.user_id = 1

truncate table location

truncate table user_activity
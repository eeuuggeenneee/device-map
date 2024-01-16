use pbi_thermohygrometer_db

drop table user_tbl

drop table location

drop table activity

INSERT INTO user_tbl (user_id, username, password, currentloc)
VALUES (12345, 'admin', 'admin', 'Fake City');

-- Inserting fake data into the activity table
INSERT INTO activity (name, lat, lng, timestamp) VALUES
('Running', 40.7128, -74.0060, '2024-01-16T10:30:00'),
('Cycling', 34.0522, -118.2437, '2024-01-16T11:15:00'),
('Swimming', 41.8781, -87.6298, '2024-01-16T12:00:00'),
('Hiking', 37.7749, -122.4194, '2024-01-16T13:45:00'),
('Yoga', 51.5074, -0.1278, '2024-01-16T14:30:00');

select * from user_tbl

select * from activity

select * from location
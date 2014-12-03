DROP TABLE IF EXISTS pins;
DROP TABLE IF EXISTS countries;

CREATE TABLE pins (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	lat FLOAT(10, 6) NOT NULL,
	lng FLOAT(10, 6) NOT NULL,
	ip_address VARCHAR(40) NOT NULL,
	fk_country INT NOT NULL
);

CREATE TABLE countries (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255) NOT NULL
);

INSERT INTO countries (name) VALUES ('Österreich');

INSERT INTO pins (lat, lng, ip_address, fk_country) VALUES (47.608941, -122.340145, '127.0.0.1', 1);
INSERT INTO pins (lat, lng, ip_address, fk_country) VALUES (47.605961, -122.34036, '127.0.0.1', 1);
INSERT INTO pins (lat, lng, ip_address, fk_country) VALUES (47.617215, -122.326584, '127.0.0.1', 1);
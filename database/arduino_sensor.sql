-- Set SQL mode and time zone
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET time_zone = '+00:00';

-- Start a transaction
START TRANSACTION;

-- Create the logging table
CREATE TABLE logging (
  No INT(255) NOT NULL AUTO_INCREMENT,
  tag VARCHAR(1000) NOT NULL,
  in_time TIME NOT NULL,
  out_time TIME NOT NULL,
  duration INT(255) GENERATED ALWAYS AS (TIME_TO_SEC(TIMEDIFF(out_time, in_time))) STORED,
  date DATE NOT NULL DEFAULT CURRENT_DATE(),
  PRIMARY KEY (No)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create the tambah table
CREATE TABLE tambah (
  No INT(255) NOT NULL AUTO_INCREMENT,
  tag VARCHAR(1000) NOT NULL,
  time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
  PRIMARY KEY (No)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create the availability table
CREATE TABLE availability (
  No INT(255) NOT NULL AUTO_INCREMENT,
  tag VARCHAR(1000) NOT NULL UNIQUE,
  in_time TIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  out_time TIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),  
  status VARCHAR(255) NOT NULL DEFAULT 'available',
  PRIMARY KEY (No)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create the get_status procedure
DELIMITER //
CREATE PROCEDURE get_status(IN p_tag VARCHAR(255))
BEGIN
    DECLARE availability_status VARCHAR(50);

    SELECT status INTO availability_status FROM availability WHERE tag = p_tag;

    IF availability_status = 'available' THEN
        SET availability_status = 'available';
    ELSE
        SET availability_status = 'reserved';
    END IF;

    SELECT availability_status AS availability_status;
END //
DELIMITER ;

-- Create the update_availability procedure
DELIMITER //
CREATE PROCEDURE update_availability(IN p_tag VARCHAR(255))
BEGIN
    DECLARE current_status VARCHAR(50);
    DECLARE get_time TIME;

    SELECT status, in_time INTO current_status, get_time FROM availability WHERE tag = p_tag;

    IF current_status = 'available' THEN
        UPDATE availability SET status = 'reserved', in_time = CURRENT_TIMESTAMP() WHERE tag = p_tag;
    ELSE
        INSERT INTO logging (tag, in_time, out_time) VALUES (p_tag, get_time, CURRENT_TIMESTAMP());
        UPDATE availability SET status = 'available', out_time = CURRENT_TIMESTAMP() WHERE tag = p_tag;
    END IF;

    SELECT status AS availability_status FROM availability WHERE tag = p_tag;
END //
DELIMITER ;

-- Commit the transaction
COMMIT;

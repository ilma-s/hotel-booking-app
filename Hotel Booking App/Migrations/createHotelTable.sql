CREATE TABLE `hotel` (
                         `HID` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
                         `nameOfHotel` varchar(50) NOT NULL,
                         `adressName` varchar(50) NOT NULL,
                         `adressNumber` int(3) NOT NULL,
                         `city` varchar(50) NOT NULL,
                         `freeParking` int(1) NOT NULL,
                         `petFriendly` int(1) NOT NULL,
                         `firstEntryPerson` int(11) NOT NULL,
                         `lastEntryPerson` int(11) DEFAULT NULL,
                         `firstEntryDate` timestamp NOT NULL DEFAULT current_timestamp(),
                         `lastEntryDate` timestamp NOT NULL,
                         `freeWiFi` int(1) NOT NULL,
                         `nonSmokingRooms` int(1) NOT NULL,
                         `roomService` int(11) NOT NULL,
                         `restaurant` int(11) NOT NULL,
                         `bar` int(11) NOT NULL,
                         `lift` int(11) NOT NULL,
                         `24hourFrontDesk` int(11) NOT NULL,
                         `description` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE hotel
    ADD FOREIGN KEY (firstEntryPerson) REFERENCES users(UID);

ALTER TABLE hotel
    ADD FOREIGN KEY (lastEntryPerson) REFERENCES users(UID);

ALTER TABLE hotel
    ADD COLUMN email varchar(150);

ALTER TABLE hotel
    ADD COLUMN adminEmail varchar(150);

ALTER TABLE hotel
    ADD COLUMN stars integer;

ALTER TABLE hotel
    RENAME COLUMN city TO municipality;

ALTER TABLE hotel
    RENAME COLUMN email TO contactEmail;

ALTER TABLE hotel
    RENAME COLUMN adminEmail TO admin;

ALTER TABLE hotel
    MODIFY admin integer;

ALTER TABLE hotel
    ADD FOREIGN KEY (admin) REFERENCES users (UID);

ALTER TABLE hotel
    RENAME COLUMN adressName TO addressName;

ALTER TABLE hotel
    RENAME COLUMN adressNumber TO addressNumber;

ALTER TABLE hotel
    MODIFY addressNumber varchar(10);

ALTER TABLE hotel
    DROP COLUMN 24HourFrontDesk;

ALTER TABLE hotel
    RENAME COLUMN lift TO elevator;

ALTER TABLE hotel
    ADD COLUMN gym INT(1);

ALTER TABLE hotel
    ADD COLUMN pool INT(1);

ALTER TABLE hotel
    ADD COLUMN disabilityFriendly INT(1);

ALTER TABLE hotel
    ADD averageRating double;
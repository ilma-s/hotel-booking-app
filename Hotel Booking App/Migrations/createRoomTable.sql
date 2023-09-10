CREATE TABLE `room` (
                        `RID` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
                        `belongsToHotel` int(11) NOT NULL,
                        `description` varchar(150) NOT NULL,
                        `roomType` int(11) NOT NULL,
                        `price` double NOT NULL,
                        `firstEntryPerson` int(11) NOT NULL,
                        `lastEntryPerson` int(11) DEFAULT NULL,
                        `firstEntryDate` timestamp NOT NULL DEFAULT current_timestamp(),
                        `lastEntryDate` timestamp NOT NULL,
                        `cityView` int(1) NOT NULL,
                        `AC` int(1) NOT NULL,
                        `heating` int(1) NOT NULL,
                        `mountainView` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE room
    ADD FOREIGN KEY (belongsToHotel) REFERENCES hotel(HID);

ALTER TABLE room
    ADD FOREIGN KEY (roomType) REFERENCES roomtype(RID);

ALTER TABLE room
    ADD FOREIGN KEY (firstEntryPerson) REFERENCES users(UID);

ALTER TABLE room
    ADD FOREIGN KEY (lastEntryPerson) REFERENCES users(UID);

ALTER TABLE room
    ADD roomNumber int(4) NOT NULL;

ALTER TABLE room
    ADD roomName varchar(100) NOT NULL;

ALTER TABLE room
    DROP 'roomName';

ALTER TABLE room
    DROP 'description';

ALTER TABLE room
    DROP 'mountainView';

ALTER TABLE room
    DROP 'cityView';

ALTER TABLE room
    ADD miniBar int(1) NOT NULL;

ALTER TABLE room
    ADD TV int(1) NOT NULL;
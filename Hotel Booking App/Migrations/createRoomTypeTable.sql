CREATE TABLE `roomtype` (
                            `RID` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
                            `nameOfRoomType` varchar(50) NOT NULL,
                            `numberOfBeds` int(2) NOT NULL,
                            `firstEntryPerson` int(11) NOT NULL,
                            `lastEntryPerson` int(11) DEFAULT NULL,
                            `firstEntryDate` timestamp NOT NULL DEFAULT current_timestamp(),
                            `dastEntryDate` date DEFAULT NULL,
                            `balcony` int(1) NOT NULL,
                            `minibar` int(1) NOT NULL,
                            `flatScreenTV` int(1) NOT NULL,
                            `privateBathroom` int(1) NOT NULL,
                            `size` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
ALTER TABLE roomtype
    ADD FOREIGN KEY (firstEntryPerson) REFERENCES users(UID);

ALTER TABLE roomtype
    ADD FOREIGN KEY (lastEntryPerson) REFERENCES users(UID);

ALTER TABLE roomtype
    DROP COLUMN minibar;

ALTER TABLE roomtype
    DROP COLUMN flatScreenTV;

ALTER TABLE roomtype
    DROP COLUMN privateBathroom;

ALTER TABLE roomtype
    ADD COLUMN cityView int(1) NOT NULL;

ALTER TABLE roomtype
    ADD COLUMN mountainView int(1) NOT NULL;
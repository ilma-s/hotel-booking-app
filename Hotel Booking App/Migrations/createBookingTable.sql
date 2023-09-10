CREATE TABLE `booking` (
                           `BID` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
                           `customer` int(11) NOT NULL,
                           `room` int(11) NOT NULL,
                           `fromDate` date NOT NULL,
                           `toDate` date NOT NULL,
                           `firstEntryPerson` int(11) NOT NULL,
                           `lastEntryPerson` int(11) DEFAULT NULL,
                           `firstEntryDate` timestamp NOT NULL DEFAULT current_timestamp(),
                           `lastEntryDate` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE booking
    ADD FOREIGN KEY (customer) REFERENCES users(UID);

ALTER TABLE booking
    ADD FOREIGN KEY (room) REFERENCES room(RID);

ALTER TABLE booking
    ADD FOREIGN KEY (firstEntryPerson) REFERENCES users(UID);

ALTER TABLE booking
    ADD FOREIGN KEY (lastEntryPerson) REFERENCES users(UID);

ALTER TABLE booking
    ADD COLUMN price double;
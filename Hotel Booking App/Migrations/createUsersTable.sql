CREATE TABLE `users` (
                         `UID` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
                         `firstName` varchar(25) NOT NULL,
                         `lastName` varchar(50) NOT NULL,
                         `dateOfBirth` date NOT NULL,
                         `username` varchar(50) NOT NULL,
                         `password` varchar(30) NOT NULL,
                         `email` varchar(75) NOT NULL,
                         `isAdmin` int(2) NOT NULL DEFAULT 0,
                         `firstEntryPerson` int(11) DEFAULT NULL,
                         `lastEntryPerson` int(11) DEFAULT NULL,
                         `firstEntryDate` date NOT NULL DEFAULT current_time,
                         `lastEntryDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE users
    ADD FOREIGN KEY (firstEntryPerson) REFERENCES users(UID);

ALTER TABLE users
    ADD FOREIGN KEY (lastEntryPerson) REFERENCES users(UID);


ALTER TABLE users
    MODIFY password varchar(999);

ALTER TABLE users
    MODIFY dateOfBirth DATE DEFAULT NULL;
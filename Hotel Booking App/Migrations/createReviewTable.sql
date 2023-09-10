CREATE TABLE Review (
                        RID integer PRIMARY KEY AUTO_INCREMENT,
                        booking integer(1) NOT NULL,
                        staff integer(1) NOT NULL,
                        comfort integer(1) NOT NULL,
                        freeWifi integer(1) NOT NULL,
                        facilities integer(1) NOT NULL,
                        valueForMoney integer(1) NOT NULL,
                        cleanliness integer(1) NOT NULL,
                        location integer(1) NOT NULL,
                        comment varchar(500),
                        dateSubmitted date,
                        FOREIGN KEY (booking) REFERENCES booking(BID)
);
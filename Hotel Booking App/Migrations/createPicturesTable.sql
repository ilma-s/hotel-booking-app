CREATE TABLE pictures (
                          PID integer PRIMARY KEY,
                          roomType integer,
                          picture varchar(500),
                          hotel integer
);

ALTER TABLE pictures
    ADD FOREIGN KEY (roomType) REFERENCES roomtype(RID);

ALTER TABLE pictures
    ADD FOREIGN KEY (hotel) REFERENCES hotel(HID);
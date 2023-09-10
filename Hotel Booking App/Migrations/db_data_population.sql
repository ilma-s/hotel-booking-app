insert into users (firstName, lastName, dateOfBirth, username, password, email, isAdmin)
values ('i1', 's1', STR_TO_DATE('01-01-2001','%d-%m-%Y'), 'i1', 'i1', 'i1@test', 0);

insert into users (firstName, lastName, dateOfBirth, username, password, email, isAdmin)
values ('i2', 's2', STR_TO_DATE('02-02-2002','%d-%m-%Y'), 'i2', 'i2', 'i2@test', 1);

insert into users (firstName, lastName, dateOfBirth, username, password, email, isAdmin)
values ('i3', 's3', STR_TO_DATE('03-03-2003','%d-%m-%Y'), 'i3', 'i3', 'i3@test', 1);

insert into users (firstName, lastName, dateOfBirth, username, password, email, isAdmin)
values ('i4', 's4', STR_TO_DATE('04-04-2004','%d-%m-%Y'), 'i4', 'i4', 'i4@test', 0);

insert into users (firstName, lastName, dateOfBirth, username, password, email, isAdmin)
values ('i5', 's5', STR_TO_DATE('05-05-2005','%d-%m-%Y'), 'i5', 'i5', 'i1@test', 1);


insert into hotel (nameOfHotel, adressName, adressNumber, city, freeParking, petFriendly, freeWiFi, nonSmokingRooms, roomService, restaurant, bar, lift, `24hourFrontDesk`, firstEntryPerson, lastEntryPerson, description)
values ('hotel1', 'h1add', 1, 'city1', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, '');

insert into hotel (nameOfHotel, adressName, adressNumber, city, freeParking, petFriendly, freeWiFi, nonSmokingRooms, roomService, restaurant, bar, lift, `24hourFrontDesk`, firstEntryPerson, lastEntryPerson, description)
values ('hotel2', 'h2add', 2, 'city2', 0, 0, 1, 0, 1, 0, 0, 2, 0, 2, 2, '');

insert into hotel (nameOfHotel, adressName, adressNumber, city, freeParking, petFriendly, freeWiFi, nonSmokingRooms, roomService, restaurant, bar, lift, `24hourFrontDesk`, firstEntryPerson, lastEntryPerson, description)
values ('hotel3', 'h3add', 3, 'city3', 0, 1, 0, 1, 0, 0, 1, 0, 0, 3, 3, '');

insert into hotel (nameOfHotel, adressName, adressNumber, city, freeParking, petFriendly, freeWiFi, nonSmokingRooms, roomService, restaurant, bar, lift, `24hourFrontDesk`, firstEntryPerson, lastEntryPerson, description)
values ('hotel4', 'h4add', 4, 'city4', 0, 0, 1, 0, 0, 1, 0, 0, 1, 4, 4, '');

insert into hotel (nameOfHotel, adressName, adressNumber, city, freeParking, petFriendly, freeWiFi, nonSmokingRooms, roomService, restaurant, bar, lift, `24hourFrontDesk`, firstEntryPerson, lastEntryPerson, description)
values ('hotel5', 'h5add', 5, 'city5', 1, 1, 0, 0, 1, 0, 1, 0, 1, 5, 5, '');

insert into roomtype (nameOfRoomType, numberOfBeds, firstEntryPerson, balcony, minibar, flatScreenTV, privateBathroom, size)
values ('roomtype1', 1, 1, 1, 1, 0, 1, 1);

insert into roomtype (nameOfRoomType, numberOfBeds, firstEntryPerson, balcony, minibar, flatScreenTV, privateBathroom, size)
values ('roomtype2', 2, 2, 0, 1, 0, 1, 2);

insert into roomtype (nameOfRoomType, numberOfBeds, firstEntryPerson, balcony, minibar, flatScreenTV, privateBathroom, size)
values ('roomtype3', 3, 3, 0, 0, 0, 1, 3);

insert into roomtype (nameOfRoomType, numberOfBeds, firstEntryPerson, balcony, minibar, flatScreenTV, privateBathroom, size)
values ('roomtype4', 4, 4, 4, 1, 1, 1, 4);

insert into roomtype (nameOfRoomType, numberOfBeds, firstEntryPerson, balcony, minibar, flatScreenTV, privateBathroom, size)
values ('roomtype5', 5, 5, 1, 0, 0, 1, 5);

insert into room (belongsToHotel, description, roomType, price, firstEntryPerson, lastEntryPerson, cityView, AC, heating, mountainView)
values (1, 'desc1', 1 ,12.5, 1, 1, 1, 0, 1, 1);

insert into room (belongsToHotel, description, roomType, price, firstEntryPerson, lastEntryPerson, cityView, AC, heating, mountainView)
values (2, 'desc2', 2 ,52.9, 2, 2, 1, 1, 1, 1);

insert into room (belongsToHotel, description, roomType, price, firstEntryPerson, lastEntryPerson, cityView, AC, heating, mountainView)
values (3, 'desc3', 3 ,120.5, 3, 3, 0, 0, 0, 0);

insert into room (belongsToHotel, description, roomType, price, firstEntryPerson, lastEntryPerson, cityView, AC, heating, mountainView)
values (4, 'desc4', 4 ,102.1, 4, 4, 0, 1, 0, 1);

insert into room (belongsToHotel, description, roomType, price, firstEntryPerson, lastEntryPerson, cityView, AC, heating, mountainView)
values (5, 'desc5', 5 ,42.4, 5, 5, 1, 1, 0, 0);

insert into booking (BID, customer, room, fromDate, toDate, firstEntryPerson, lastEntryPerson)
values (1, 1, 1, STR_TO_DATE('15-05-2023','%d-%m-%Y'), STR_TO_DATE('18-05-2023','%d-%m-%Y'), 1, 1);

insert into booking (BID, customer, room, fromDate, toDate, firstEntryPerson, lastEntryPerson)
values (2, 2, 2, STR_TO_DATE('25-05-2023','%d-%m-%Y'), STR_TO_DATE('28-05-2023','%d-%m-%Y'), 2, 2);

insert into booking (BID, customer, room, fromDate, toDate, firstEntryPerson, lastEntryPerson)
values (3, 3, 3, STR_TO_DATE('24-05-2023','%d-%m-%Y'), STR_TO_DATE('29-05-2023','%d-%m-%Y'), 3, 3);

insert into booking (BID, customer, room, fromDate, toDate, firstEntryPerson, lastEntryPerson)
values (4, 4, 3, STR_TO_DATE('28-05-2023','%d-%m-%Y'), STR_TO_DATE('01-06-2023','%d-%m-%Y'), 4, 4);

insert into booking (BID, customer, room, fromDate, toDate, firstEntryPerson, lastEntryPerson)
values (5, 5, 5, STR_TO_DATE('01-06-2023','%d-%m-%Y'), STR_TO_DATE('18-06-2023','%d-%m-%Y'), 5, 5);
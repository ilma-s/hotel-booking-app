/* BookingInformationView */
CREATE VIEW BookingInformationView AS
SELECT
    b.BID,
    h.HID,
    CONCAT(u.firstName, ' ', u.lastName) AS customerName,
    rt.nameOfRoomType,
    r.RID AS roomID,
    r.roomNumber,
    b.fromDate,
    b.toDate,
    DATEDIFF(b.toDate, b.fromDate) AS nightCount,
    r.price,
    (DATEDIFF(b.toDate, b.fromDate) * r.price) AS totalCost
FROM
    booking b
        JOIN
    users u ON b.customer = u.UID
        JOIN
    room r ON b.room = r.RID
        JOIN
    roomtype rt ON r.roomType = rt.RID
        JOIN
    hotel h ON r.belongsToHotel = h.HID;

/* CountRoomBookings  */
create
    definer = root@localhost function bookingapp.CountRoomBookings(roomId int) returns int reads sql data
BEGIN
    DECLARE bookingCount INT;

    -- Count the number of times the room ID appears in the bookings table
    SELECT COUNT(*) INTO bookingCount
    FROM booking b
    WHERE b.room = roomId;

    RETURN bookingCount;
END;

/* GetBookedRoomsCount */
create
    definer = root@localhost function bookingapp.GetBookedRoomsCount(selectedDate date, hotelID int) returns int
    deterministic
BEGIN
    DECLARE booked_rooms_count INT;

    SELECT COUNT(*) INTO booked_rooms_count
    FROM booking b
             JOIN room r ON b.room = r.RID
    WHERE r.belongsToHotel = hotelID
      AND selectedDate BETWEEN b.fromDate AND b.toDate;

    RETURN booked_rooms_count;
END;
/* GetRoomDetails */
create
    definer = root@localhost procedure bookingapp.GetRoomDetails(IN hotelId int)
BEGIN
    SELECT r.roomNumber, rt.nameOfRoomType, IsRoomAvailable(r.RID) AS availability, GetBookedRoomsCount(CURDATE(), hotelId) AS numberOfRoomsBooked
    FROM room r
             JOIN roomtype rt ON r.roomType = rt.RID
    WHERE r.belongsToHotel = hotelId;
END;
 /* IsRoomAvailable */
create
    definer = root@localhost function bookingapp.IsRoomAvailable(roomId int) returns tinyint(1) reads sql data
BEGIN
    DECLARE isAvailable BOOLEAN;

    -- Check if the room is available based on the current date
    SET isAvailable = NOT EXISTS (
            SELECT 1
            FROM booking b
            WHERE b.room = roomId
              AND CURDATE() BETWEEN fromDate AND toDate
        );
    RETURN isAvailable;
END;


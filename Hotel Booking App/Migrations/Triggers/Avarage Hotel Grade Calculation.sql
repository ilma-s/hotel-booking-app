CREATE TRIGGER `average Hotel Grade Calculation Delete` AFTER DELETE ON `review`
 FOR EACH ROW BEGIN
    UPDATE hotel h
    SET h.averageRating = ROUND((
        SELECT IFNULL(AVG(r.staff + r.comfort + r.freeWifi + r.facilities + r.valueForMoney + r.cleanliness + r.location)/7/ (SELECT COUNT(*) FROM review r WHERE r.booking = b.BID), 0)
        FROM review r, booking b, room rr
        WHERE r.booking = b.BID AND b.room = rr.RID AND rr.belongsToHotel = h.HID
    ),2);
END

CREATE TRIGGER `average Hotel Grade Calculation Insert` AFTER INSERT ON `review`
 FOR EACH ROW BEGIN
    UPDATE hotel h
    SET h.averageRating = ROUND((
        SELECT IFNULL(AVG(r.staff + r.comfort + r.freeWifi + r.facilities + r.valueForMoney + r.cleanliness + r.location)/7/ (SELECT COUNT(*) FROM review r WHERE r.booking = b.BID), 0)
        FROM review r, booking b, room rr
        WHERE r.booking = b.BID AND b.room = rr.RID AND rr.belongsToHotel = h.HID
    ),2);
END

CREATE TRIGGER `average Hotel Grade Calculation Update` AFTER UPDATE ON `review`
 FOR EACH ROW BEGIN
    UPDATE hotel h
    SET h.averageRating = ROUND((
        SELECT IFNULL(AVG(r.staff + r.comfort + r.freeWifi + r.facilities + r.valueForMoney + r.cleanliness + r.location)/7/ (SELECT COUNT(*) FROM review r WHERE r.booking = b.BID), 0)
        FROM review r, booking b, room rr
        WHERE r.booking = b.BID AND b.room = rr.RID AND rr.belongsToHotel = h.HID
    ),2);
END

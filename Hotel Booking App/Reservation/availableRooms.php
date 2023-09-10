<?php
function getAvailableRooms($fromDate, $toDate, $numPeople, $municipality)
{
    global $connection;

    $query = "SELECT DISTINCT r.RID
              FROM room r
              JOIN hotel h ON r.belongsToHotel = h.HID
              JOIN roomtype rt ON r.roomType = rt.RID
              WHERE rt.numberOfBeds = ?
                AND NOT EXISTS (
                  SELECT 1
                  FROM booking b
                  WHERE r.RID = b.room
                    AND (
                      (b.fromDate <= ? AND b.toDate >= ?)  -- Booking overlaps the trip
                      OR (b.fromDate <= ? AND b.toDate >= ?)  -- Booking starts during the trip
                      OR (b.fromDate <= ? AND b.toDate >= ?)  -- Booking ends during the trip
                      OR (? <= b.fromDate AND ? >= b.toDate)  -- Trip includes the entire booking
                    )
                )";

    $params = array($numPeople, $fromDate, $fromDate, $toDate, $toDate, $fromDate, $toDate, $fromDate, $toDate);

    /*if (!empty($municipality)) {
        //$query .= " AND LOWER(h.municipality) LIKE CONCAT('%', REPLACE(LOWER(?), ' ', ''), '%')";
        $query .= " AND SOUNDEX(LOWER(h.municipality)) = SOUNDEX(LOWER(?))";
        $params[] = $municipality;
        //$params[] = strtolower($municipality);
        var_dump($query);
    }*/

    if (!empty($municipality)) {
        $query .= " AND (LOWER(h.municipality) LIKE LOWER(?) OR LOWER(REPLACE(h.municipality, ' ', '')) LIKE LOWER(?))";
        $params[] = '%' . $municipality . '%';
        $params[] = '%' . str_replace(' ', '', $municipality) . '%';
    }

    $stmt = mysqli_prepare($connection, $query);
    $paramTypes = str_repeat("s", count($params));
    mysqli_stmt_bind_param($stmt, $paramTypes, ...$params);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $rooms = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $roomId = $row['RID'];
            $rooms[] = $roomId;
        }

        return $rooms;
    }

    return false;
}

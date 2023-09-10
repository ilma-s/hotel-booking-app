<?php
// Check if the room types data is available in the session
if (isset($_SESSION['roomTypes'])) {
    $roomTypes = $_SESSION['roomTypes'];

    // Create an empty array to hold the form values
    $formValues = [];

    // Check if the form has been submitted and populate the form values
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $formValues = $_POST;
    }

    // Loop through each room type and display the form
    foreach ($roomTypes as $key => $roomType) {
        $roomId = $key;
        $_SESSION['roomId'] = $roomId;

        $editMode = isset($_GET['edit']) && $_GET['edit'] === $roomId;

        // Get the form values based on the edit mode or from the submitted form values
        $nameOfRoomType = $editMode ? $formValues['nameOfRoomType'][$roomId] : $roomType['name'];
        $numberOfBeds = $editMode ? $formValues['numberOfBeds'][$roomId] : $roomType['numberOfBeds'];
        $size = $editMode ? $formValues['size'][$roomId] : $roomType['size'];
        $balconyOption = $editMode ? $formValues['balconyOption'][$roomId] : $roomType['balconyOption'];
        $view = $editMode ? $formValues['view'][$roomId] : $roomType['view'];
        $bathroomOption = $editMode ? $formValues['bathroomOption'][$roomId] : $roomType['bathroomOption'];

        ?>
        <form action="" method="post">
            <div>
                <label for="nameOfRoomType">Name of the room type:</label>
                <input type="text" name="nameOfRoomType[<?php echo $roomId; ?>]" value="<?php echo $nameOfRoomType; ?>" <?php if (!$editMode) echo 'readonly'; ?>>
                <br>

                <label for="numberOfBeds">Number of beds:</label>
                <input type="number" min="1" name="numberOfBeds[<?php echo $roomId; ?>]" value="<?php echo $numberOfBeds; ?>" <?php if (!$editMode) echo 'readonly'; ?>>
                <br>

                <label for="size">What is the size of this room type, in square meters?</label>
                <input type="number" min="1" name="size[<?php echo $roomId; ?>]" value="<?php echo $size; ?>" <?php if (!$editMode) echo 'readonly'; ?>>
                <br>

                <label for="balconyOption">Does this room type come with a balcony?</label>
                <label for="yesBalcony">Yes:</label>
                <input type="radio" name="balconyOption[<?php echo $roomId; ?>]" value="1" <?php if ($balconyOption === '1') echo 'checked'; ?> <?php if (!$editMode) echo 'disabled'; ?>>
                <label for="noBalcony">No:</label>
                <input type="radio" name="balconyOption[<?php echo $roomId; ?>]" value="0" <?php if ($balconyOption === '0') echo 'checked'; ?> <?php if (!$editMode) echo 'disabled'; ?>>
                <br>

                <label for="view">View:</label>
                <input type="radio" name="view[<?php echo $roomId; ?>]" value="1" <?php if ($view === 'cityView') echo 'checked'; ?> <?php if (!$editMode) echo 'disabled'; ?>>
                <label for="cityView">City View</label>

                <input type="radio" name="view[<?php echo $roomId; ?>]" value="0" <?php if ($view === 'mountainView') echo 'checked'; ?> <?php if (!$editMode) echo 'disabled'; ?>>
                <label for="mountainView">Mountain View</label>
                <br>

                <label for="bathroomOption">Does this room type have a private bathroom?</label>
                <label for="yesBathroom">Yes:</label>
                <input type="radio" name="bathroomOption[<?php echo $roomId; ?>]" value="1" <?php if ($bathroomOption === '1') echo 'checked'; ?> <?php if (!$editMode) echo 'disabled'; ?>>
                <label for="noBathroom">No:</label>
                <input type="radio" name="bathroomOption[<?php echo $roomId; ?>]" value="0" <?php if ($bathroomOption === '0') echo 'checked'; ?> <?php if (!$editMode) echo 'disabled'; ?>>
                <br>

                <?php  ?>
                    <a href="./editRoomType.php?roomId=<?php echo $_SESSION['roomId']; ?>">Edit</a>
                <?php ?>
            </div>
        </form>
        <?php
    }
}
?>

<?php
    require "../dev/./config.php";
// Establish a connection to the MySQL database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the delete button was clicked
if (isset($_POST['delete'])) {
    $field_name = mysqli_real_escape_string($conn, $_POST['field_name']);
    $sql = "ALTER TABLE post DROP COLUMN $field_name";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "Field deleted successfully";
    } else {
        echo "Error deleting field: " . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!-- HTML form with button input -->
<form method="post">
    <label for="field_name">Field name:</label>
    <input type="text" name="field_name" id="field_name">
    <input type="submit" name="delete" value="Delete">
</form>
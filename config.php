
<?php
$conn = new mysqli(
    "localhost",
    "root",
    "",
    "security_incident_db"
);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
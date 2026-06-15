<?php
$conn = new mysqli(
    "localhost",
    "incident_user",
    "incident123",
    "security_incident_db"
);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
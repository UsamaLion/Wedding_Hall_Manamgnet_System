<?php
include 'header.php';
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = $_POST['customer-name'];
    $event_date = $_POST['event-date'];
    $start_time = $_POST['start-time'];
    $end_time = $_POST['end-time'];
    $total_guests = $_POST['total-guests'];
    $total_amount = $_POST['total-amount'];

    // Insert data into bookings table
    $sql = "INSERT INTO bookings (customer_name, event_date, start_time, end_time, total_guests, total_amount)
            VALUES ('$customer_name', '$event_date', '$start_time', '$end_time', '$total_guests', '$total_amount')";

    if ($conn->query($sql) === TRUE) {
        echo "<p>Booking added successfully!</p>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

include 'footer.php';
?>

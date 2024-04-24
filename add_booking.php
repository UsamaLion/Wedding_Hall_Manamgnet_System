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
        echo "<div class='success'>Booking added successfully!</div>";
    } else {
        echo "<div class='error'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}
?>

<div class="form-container">
    <h2>Add Booking</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="customer-name">Customer Name:</label>
        <input type="text" id="customer-name" name="customer-name" required>
        <label for="event-date">Event Date:</label>
        <input type="date" id="event-date" name="event-date" required>
        <label for="start-time">Start Time:</label>
        <input type="time" id="start-time" name="start-time" required>
        <label for="end-time">End Time:</label>
        <input type="time" id="end-time" name="end-time" required>
        <label for="total-guests">Total Guests:</label>
        <input type="number" id="total-guests" name="total-guests" required>
        <label for="total-amount">Total Amount:</label>
        <input type="text" id="total-amount" name="total-amount" required>
        <button type="submit">Add Booking</button>
    </form>
</div>

<!-- Show bookings -->

<?php

// Fetch existing bookings
$sql = "SELECT * FROM bookings";
$result = $conn->query($sql);
?>

<div class="booking-list">
    <h2>Existing Bookings</h2>
    <?php
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Customer Name</th><th>Event Date</th><th>Start Time</th><th>End Time</th><th>Total Guests</th><th>Total Amount</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["customer_name"] . "</td><td>" . $row["event_date"] . "</td><td>" . $row["start_time"] . "</td><td>" . $row["end_time"] . "</td><td>" . $row["total_guests"] . "</td><td>" . $row["total_amount"] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "No bookings available.";
    }
    ?>
</div>


<?php include 'footer.php'; ?>

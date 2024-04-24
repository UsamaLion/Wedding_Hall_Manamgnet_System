<?php
include 'header.php';
include 'db_connection.php';

// Function to retrieve staff list from the database
function getStaffList($conn) {
    $sql = "SELECT * FROM staff";
    $result = $conn->query($sql);
    $staff_list = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $staff_list[] = $row;
        }
    }
    return $staff_list;
}

// Function to add new staff member
function addStaffMember($conn, $name, $email, $phone, $department, $position) {
    $sql = "INSERT INTO staff (name, email, phone, department, position) VALUES ('$name', '$email', '$phone', '$department', '$position')";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Function to record attendance
function recordAttendance($conn, $staff_id, $date, $status) {
    $sql = "INSERT INTO attendance (staff_id, date, status) VALUES ($staff_id, '$date', '$status')";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Handle form submission to add new staff member
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_staff'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $department = $_POST['department'];
    $position = $_POST['position'];

    if (addStaffMember($conn, $name, $email, $phone, $department, $position)) {
        echo "<div class='success'>New staff member added successfully!</div>";
    } else {
        echo "<div class='error'>Failed to add new staff member. Please try again.</div>";
    }
}


// Handle form submission to record attendance
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['record_attendance'])) {
    $staff_id = $_POST['staff_id'];
    $date = $_POST['date'];
    $status = $_POST['status'];

    if (recordAttendance($conn, $staff_id, $date, $status)) {
        echo "<div class='success'>Attendance recorded successfully!</div>";
    } else {
        echo "<div class='error'>Failed to record attendance. Please try again.</div>";
    }
}

// Function to retrieve attendance records for a specific staff member
function getAttendanceRecords($conn, $staff_id) {
    $sql = "SELECT * FROM attendance WHERE staff_id = $staff_id ORDER BY date DESC";
    $result = $conn->query($sql);
    $attendance_records = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $attendance_records[] = $row;
        }
    }
    return $attendance_records;
}
// Retrieve staff list from the database
$staff_list = getStaffList($conn);
?>

<div class="container">
    <h2>Staff Management System</h2>

    <!-- Form to add new staff member -->
    <div class="add-staff-form">
        <h3>Add New Staff Member</h3>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required>
            <label for="department">Department:</label>
            <input type="text" id="department" name="department" required>
            <label for="position">Position:</label>
            <input type="text" id="position" name="position" required>
            <button type="submit" name="add_staff">Add Staff</button>
        </form>
    </div>


    <!-- Form to record attendance -->
    <div class="attendance-form">
        <h3>Record Attendance</h3>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="staff_id">Staff:</label>
            <select id="staff_id" name="staff_id" required>
                <?php foreach ($staff_list as $staff): ?>
                    <option value="<?php echo $staff['id']; ?>"><?php echo $staff['name']; ?></option>
                <?php endforeach; ?>
            </select>
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>
            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="present">Present</option>
                <option value="absent">Absent</option>
            </select>
            <button type="submit" name="record_attendance">Record Attendance</button>
        </form>
    </div>

    <!-- Staff List -->
    <div class="staff-list">
        <h3>Staff List</h3>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Department</th>
                    <th>Position</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($staff_list as $staff): ?>
                    <tr>
                        <td><?php echo $staff['name']; ?></td>
                        <td><?php echo $staff['email']; ?></td>
                        <td><?php echo $staff['phone']; ?></td>
                        <td><?php echo $staff['department']; ?></td>
                        <td><?php echo $staff['position']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

                    <br>
    <!-- Display attendance records for each staff member -->
    <div class="attendance-records">
        <h3>Attendance Records</h3>
        <?php foreach ($staff_list as $staff): ?>
            <h4><?php echo $staff['name']; ?></h4>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $attendance_records = getAttendanceRecords($conn, $staff['id']);
                    foreach ($attendance_records as $record):
                    ?>
                    <tr>
                        <td><?php echo $record['date']; ?></td>
                        <td><?php echo ucfirst($record['status']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

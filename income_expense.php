<?php
include 'header.php';
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];

    if ($type === 'income') {
        if (isset($_POST['income-type'])) {
            $income_type = $_POST['income-type'];
            // Insert data into income table
            $sql = "INSERT INTO income (income_type, amount, date)
                    VALUES ('$income_type', '$amount', '$date')";
        } else {
            echo "<div class='error'>Income type is required!</div>";
        }
    } elseif ($type === 'expense') {
        if (isset($_POST['expense-type'])) {
            $expense_type = $_POST['expense-type'];
            // Insert data into expenses table
            $sql = "INSERT INTO expenses (expense_type, amount, date)
                    VALUES ('$expense_type', '$amount', '$date')";
        } else {
            echo "<div class='error'>Expense type is required!</div>";
        }
    }

    if (isset($sql) && $conn->query($sql) === TRUE) {
        echo "<div class='success'>Entry added successfully!</div>";
    } elseif (isset($sql)) {
        echo "<div class='error'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}
?>

<div class="form-container">
    <h2>Add Income/Expense Entry</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="type">Type:</label>
        <select id="type" name="type" required>
            <option value="income">Income</option>
            <option value="expense">Expense</option>
        </select>
        <label for="amount">Amount:</label>
        <input type="text" id="amount" name="amount" required>
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required>
        <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['type'] === 'income'): ?>
            <label for="income-type">Income Type:</label>
            <input type="text" id="income-type" name="income-type" required>
        <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['type'] === 'expense'): ?>
            <label for="expense-type">Expense Type:</label>
            <input type="text" id="expense-type" name="expense-type" required>
        <?php endif; ?>
        <button type="submit">Add Entry</button>
    </form>
</div>




<!-- Show income and expense summary -->

<?php

// Fetch total income
$sql_income = "SELECT SUM(amount) AS total_income FROM income";
$result_income = $conn->query($sql_income);
$total_income = ($result_income->num_rows > 0) ? $result_income->fetch_assoc()['total_income'] : 0;

// Fetch total expense
$sql_expense = "SELECT SUM(amount) AS total_expense FROM expenses";
$result_expense = $conn->query($sql_expense);
$total_expense = ($result_expense->num_rows > 0) ? $result_expense->fetch_assoc()['total_expense'] : 0;

// Calculate profit
$profit = $total_income - $total_expense;

?>

<div class="income-expense">
    <h2>Income and Expense Summary</h2>
    <div class="summary">
        <div class="item">
            <h3>Total Income</h3>
            <p><?php echo "PKr " . number_format($total_income, 2); ?></p>
        </div>
        <div class="item">
            <h3>Total Expense</h3>
            <p><?php echo "PKr " . number_format($total_expense, 2); ?></p>
        </div>
        <div class="item">
            <h3>Profit</h3>
            <p><?php echo "PKr " . number_format($profit, 2); ?></p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

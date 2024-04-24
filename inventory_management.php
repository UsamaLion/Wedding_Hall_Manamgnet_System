<?php
include 'header.php';
include 'db_connection.php';

// Handle form submission to add new inventory item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_item'])) {
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];
    $price_per_item = $_POST['price_per_item'];

    // Insert new item into the inventory table
    $sql = "INSERT INTO inventory (item_name, quantity, price_per_item) VALUES ('$item_name', $quantity, $price_per_item)";
    if ($conn->query($sql) === TRUE) {
        echo "<div class='success'>New inventory item added successfully!</div>";
    } else {
        echo "<div class='error'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}

// Function to calculate total valuation of inventory
function calculateInventoryTotalValue($conn) {
    // Retrieve inventory items from the database
    $sql = "SELECT * FROM inventory";
    $result = $conn->query($sql);

    // Initialize total valuation
    $total_valuation = 0;

    // Calculate total valuation
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $total_valuation += $row['quantity'] * $row['price_per_item'];
        }
    }

    return $total_valuation;
}

// Retrieve inventory items from the database
$sql = "SELECT * FROM inventory";
$result = $conn->query($sql);
?>
<style>
    .container {
    max-width: 800px;
    margin: 0 auto;
    margin-top: 50px;
    padding: 20px;
    background-color: #f9f9f900;
    border: 1px solid #ddd;
    border-radius: 5px;
}

h2 {
    margin-top: 0;
}

.add-item-form {
    margin-bottom: 20px;
}

.add-item-form input[type="text"],
.add-item-form input[type="number"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.add-item-form button {
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.add-item-form button:hover {
    background-color: #45a049;
}

.inventory-list table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.inventory-list th,
.inventory-list td {
    padding: 8px;
    border: 1px solid #ddd;
    text-align: left;
}

.inventory-list th {
    background-color: #f2f2f250;
}

.total-valuation {
    text-align: right;
    font-weight: bold;
}

</style>
<div class="container">
    <h2>Wedding Hall Inventory Management</h2>

    <!-- Form to add new inventory item -->
    <div class="add-item-form">
        <h3>Add New Inventory Item</h3>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="item_name">Item Name:</label>
            <input type="text" id="item_name" name="item_name" required>
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" required>
            <label for="price_per_item">Price per Item (PKr):</label>
            <input type="text" id="price_per_item" name="price_per_item" required>
            <button type="submit" name="add_item">Add Item</button>
        </form>
    </div>

    <!-- Display existing inventory items -->
    <div class="inventory-list">
        <h3>Inventory Items</h3>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Price per Item (PKr)</th>
                        <th>Total Value (PKr)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['item_name']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td><?php echo $row['price_per_item']; ?></td>
                            <td><?php echo $row['quantity'] * $row['price_per_item']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No inventory items found.</p>
        <?php endif; ?>
    </div>

    <!-- Display total valuation of inventory -->
    <div class="total-valuation">
        <h3>Total Valuation of Inventory</h3>
        <p><?php echo "PKr " . number_format(calculateInventoryTotalValue($conn), 2); ?></p>
    </div>
</div>

<?php include 'footer.php'; ?>
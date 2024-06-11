<?php
$servername = "localhost";
$username = "root";        //database username
$password = "";               //database password
$dbname = "FarmFresh";    //database name

// Create connection
$conID = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conID->connect_error) {
    die("Connection failed: " . $conID->connect_error);
}

// Check that form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];
   //$username = $_POST['username'];

    // Prepare and execute SQL query to insert user
    $sql_insert = "INSERT INTO users (email, password) VALUES (?, ?)";
    $stmt_insert = $conID->prepare($sql_insert);
    $stmt_insert->bind_param("ss", $email, $password);

    if($stmt_insert->execute()) {

    } else {
        echo "ERROR: Hush! Sorry " . $sql_insert . ". " . mysqli_error($conID);
    }

    $stmt_insert->close();

    // Prepare and execute SQL query to select user
    $sql_select = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt_select = $conID->prepare($sql_select);
    $stmt_select->bind_param("ss", $email, $password);
    $stmt_select->execute();
    $result = $stmt_select->get_result();

    // Check that user exists
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $name = $data['name'];
        echo "<script>alert('$name - Logged in successfully.'); window.location.href='home.html';</script>";
    } else {
        echo "<script>alert('Invalid Email or Password.'); window.location.href='index.html';</script>";
    }

    // Close statement and database connection
    $stmt_select->close();
    $conID->close();
} else {
    echo "<script>alert('Invalid request method.'); window.location.href='index.html';</script>";
}
?>

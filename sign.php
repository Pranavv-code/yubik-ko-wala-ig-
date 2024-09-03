<?php
include 'db;og.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $password = $_POST['password'];

    // Check if the username or email already exists
    $sql = "SELECT * FROM tbl_login WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Username or email already exists. Please choose another.";
    } else {
        // Insert new user
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO tbl_login (username, email, last_name, gender, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $username, $email, $last_name, $gender, $hashed_password);

        if ($stmt->execute()) {
            echo "Sign-up successful!";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $stmt->close();
    $conn->close();
}
?>

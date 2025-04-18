<?php
require 'db.php'; 
$_SESSION['usrid'] = $row['Userid'];  
$_SESSION['username'] = $row['UserName']; 


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; // User-submitted password (plain text)

    $sql = "SELECT password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            // Password is correct
            // Set session variables and redirect user to a new page
            session_start();
            $_SESSION['username'] = $username;
            header("Location: index.php"); // Redirect to a dashboard or home page
        } else {
            // Invalid password
            echo "Invalid password.";
        }
    } else {
        // No user found
        echo "No user found with that username.";
    }
    $stmt->close();
    $conn->close();
}
?>
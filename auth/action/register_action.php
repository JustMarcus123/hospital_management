<?php
require_once '../../common/simple_db.php';

try {
    $obj = new simple_db();

    if (isset($_POST['submit'])) {
        // Sanitize and validate input
        $name = htmlspecialchars($_POST['name']);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

        if (empty($name) || empty($email) || empty($_POST['password'])) {
            throw new Exception("All fields are required");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        $table_name = 'tbl_users';
        $data = array(
            'name' => $name,
            'password' => $password,
            'email' => $email
        );

        $register = $obj->inserttbl($data, $table_name);

        if ($register) {
            // Registration successful
            header("Location: ../index.php?success=1");
            exit();
        } else {
            throw new Exception("Registration failed");
        }
    }
} catch (Exception $e) {
    // Registration failed
    header("Location: ../index.php?error=" . urlencode($e->getMessage()));
    exit();
}
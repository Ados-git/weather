<?php 

session_start();
require_once 'config.php';

if (isset($_POST['register'])) {
    // Validate and sanitize input
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $county = $_POST['county'];
    $img = $_POST['img'];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash the password
    
    // Check if username or email already exists
   $checkEmail = $conn -> query("SELECT email FROM users WHERE email = '$email'");
   if ($checkEmail ->num_rows > 0) {
    $_SESSION['register_error'] = 'Email is already registered!';
    $_SESSION['active_form'] = 'register';
   } else {
    $conn->query("INSERT INTO users (name, email, username, phone, gender, county, img, password ) VALUES ('$name', '$email', '$username', '$phone', '$gender', '$county', '$img',  '$password')");
   }

   header("Location: login.php");
   exit();
}


if (isset($_POST['login'])) {
    // Validate and sanitize input
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE  email = '$email' ");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];

            header("Location: index.html");
            exit();


        }

    }

    $_SESSION['login_error'] = 'Incorrect email or password';
    $_SESSION['active_form'] = 'login';
    header("Location: login.php");
    
    exit();


}
?>
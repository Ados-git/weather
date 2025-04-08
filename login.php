<?php 

session_start();

 $errors = [
    'login' => $_SESSION['login_error'] ?? '',
    'register' => $_SESSION['register_error'] ?? ''
 ];
$activeForm = $_SESSION['active_form'] ?? 'login';

session_unset();

function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}

function isActiveForm($formName, $activeForm) {
    return $formName === $activeForm ? 'active' : '';

}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Login and SignUp</title>
</head>
<body>

    <div class="container">
        <div class="form-box  <?= isActiveForm('login', $activeForm); ?>" id="login-form">
            <form action="login_register.php"  method="POST">
                <h2>Login</h2>
                <?= showError($errors['login']); ?>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
                <p>Don't have an account <a href="#" onclick="showForm('register-form')">Register</a></p>
            </form>
        </div>

        <div class="form-box  <?= isActiveForm('register', $activeForm); ?>" id="register-form">
            <form action="login_register.php" method="POST">
                <h2>Register</h2>
                <?= showError($errors['register']); ?>
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" id="username" name="username"  placeholder="Username"  required>
                <input type="tel" id="phone" name="phone"  placeholder="Phone" required> <br>
                <div class="radio-group">
                    <input type="radio" id="male" name="gender" value="male" checked>
                    <label for="male">Male</label>
                    
                    <input type="radio" id="female" name="gender" value="female">
                    <label for="female">Female</label>
                    
                    <input type="radio" id="other" name="gender" value="other">
                    <label for="other">Other</label>
                </div>

<br><br>
                    <label for="county">County</label>
                    <select id="county" name="county" required>
                        <option value="">Select your county</option>
                        <option value="Mombasa">Mombasa</option>
                        <option value="Kwale">Kwale</option>
                        <option value="Kilifi">Kilifi</option>
                        <option value="Tana River">Tana River</option>
                        <option value="Lamu">Lamu</option>
                        <option value="Taita-Taveta">Taita-Taveta</option>
                        <option value="Garissa">Garissa</option>
                        <option value="Wajir">Wajir</option>
                        <option value="Mandera">Mandera</option>
                        <option value="Marsabit">Marsabit</option>
                        <option value="Isiolo">Isiolo</option>
                        <option value="Meru">Meru</option>
                        <option value="Tharaka-Nithi">Tharaka-Nithi</option>
                        <option value="Embu">Embu</option>
                        <option value="Kitui">Kitui</option>
                        <option value="Machakos">Machakos</option>
                        <option value="Makueni">Makueni</option>
                        <option value="Nyandarua">Nyandarua</option>
                        <option value="Nyeri">Nyeri</option>
                        <option value="Kirinyaga">Kirinyaga</option>
                        <option value="Murang'a">Murang'a</option>
                        <option value="Kiambu">Kiambu</option>
                        <option value="Turkana">Turkana</option>
                        <option value="West Pokot">West Pokot</option>
                        <option value="Samburu">Samburu</option>
                        <option value="Trans Nzoia">Trans Nzoia</option>
                        <option value="Uasin Gishu">Uasin Gishu</option>
                        <option value="Elgeyo-Marakwet">Elgeyo-Marakwet</option>
                        <option value="Nandi">Nandi</option>
                        <option value="Baringo">Baringo</option>
                        <option value="Laikipia">Laikipia</option>
                        <option value="Nakuru">Nakuru</option>
                        <option value="Narok">Narok</option>
                        <option value="Kajiado">Kajiado</option>
                        <option value="Kericho">Kericho</option>
                        <option value="Bomet">Bomet</option>
                        <option value="Kakamega">Kakamega</option>
                        <option value="Vihiga">Vihiga</option>
                        <option value="Bungoma">Bungoma</option>
                        <option value="Busia">Busia</option>
                        <option value="Siaya">Siaya</option>
                        <option value="Kisumu">Kisumu</option>
                        <option value="Homa Bay">Homa Bay</option>
                        <option value="Migori">Migori</option>
                        <option value="Kisii">Kisii</option>
                        <option value="Nyamira">Nyamira</option>
                        <option value="Nairobi">Nairobi</option>
                    </select>
                    <br>
                    <br>
                    <label>Select Image</label>
          <input type="file" name="image" accept="image/x-png,image/gif,image/jpeg,image/jpg" required>
                <input type="password" name="password" placeholder="Password" required>
                <br>
                <button type="submit" name="register">Register</button>
                <p>Already have an account?<a href="#" onclick="showForm('login-form')">Login</a></p>
            </form>
        </div>
    </div>
    <script src="login.js"></script>
</body>
</html>
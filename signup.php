<?php
require 'config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
$success = '';
$currentPage = 'signup.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $fullName        = trim($_POST['fullname'] ?? '');
    $username        = trim($_POST['username'] ?? '');
    $password        = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($fullName === '' || $username === '' || $password === '' || $confirmPassword === '') {
        $error = "Please fill in all fields.";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        $check = $pdo->prepare("SELECT UserID FROM tblusers WHERE UserName = ?");
        $check->execute([$username]);
        if ($check->fetch()) {
            $error = "Username is already taken.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare("INSERT INTO tblusers (FullName, UserName, Password, RoleID) VALUES (?, ?, ?, ?)");
            if ($insert->execute([$fullName, $username, $hashedPassword, 2])) {
                $success = "Account created successfully. You can sign in now.";
            } else {
                $error = "Unable to create account. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MegazenTrack – Create Account</title>
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Force remove the yellow background from autofill */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active{
            -webkit-box-shadow: 0 0 0 30px white inset !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
</head>
<body class="login-page">

    <div id="container" class="container sign-up">
      <!-- FORM SECTION -->
      <div class="row">
        <!-- SIGN UP (Action submits to signup.php) -->
        <div class="col align-items-center flex-col sign-up">
          <div class="form-wrapper align-items-center">
            <form class="form sign-up" action="signup.php" method="POST" autocomplete="off">
              <?= csrf_field() ?>
              
              <div class="auth-brand" style="margin-bottom: 12px;">
                  <span style="font-size: 26px; font-weight: 800; color: #e51c24;">Megazen</span>
                  <span style="font-size: 26px; font-weight: 800; color: #0f3e58;">Track</span>
              </div>
              
              <h2>Create Account</h2>
              <p style="color:#8a95a6; font-size:13px; margin-bottom: 20px;">Join MegazenTrack to manage your inventory</p>

              <?php if (!empty($error)): ?>
                  <div class="login-error"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
              <?php endif; ?>
              <?php if (!empty($success)): ?>
                  <div class="login-success"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($success) ?></div>
              <?php endif; ?>

              <div class="input-group">
                <i class="fa-solid fa-signature"></i>
                <input type="text" name="fullname" placeholder="Full Name" value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>" required />
              </div>
              <div class="input-group">
                <i class="fa-solid fa-user"></i>
                <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" autocomplete="off" required />
              </div>
              <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" placeholder="Password" autocomplete="off" required />
              </div>
              <div class="input-group">
                <i class="fa-solid fa-shield-halved"></i>
                <input type="password" name="confirm_password" placeholder="Confirm Password" autocomplete="off" required />
              </div>
              
              <button type="submit">Sign Up</button>
              
              <p class="switch-para">
                <span>Already have an account? </span>
                <b onclick="toggle()" class="pointer">Sign in here</b>
              </p>
            </form>
          </div>
        </div>
        <!-- END SIGN UP -->
        
        <!-- SIGN IN (Action submits to login.php) -->
        <div class="col align-items-center flex-col sign-in">
          <div class="form-wrapper align-items-center">
            <form class="form sign-in" action="login.php" method="POST" autocomplete="off">
              <?= csrf_field() ?>
              
              <!-- Browser autofill honeypot -->
              <input type="text" name="b_user" style="display:none" aria-hidden="true">
              <input type="password" name="b_pass" style="display:none" aria-hidden="true">

              <div class="auth-brand" style="margin-bottom: 12px;">
                  <span style="font-size: 26px; font-weight: 800; color: #e51c24;">Megazen</span>
                  <span style="font-size: 26px; font-weight: 800; color: #0f3e58;">Track</span>
              </div>

              <h2>Welcome Back</h2>
              <p style="color:#8a95a6; font-size:13px; margin-bottom: 20px;">Sign in to access your inventory</p>

              <div class="input-group">
                <i class="fa-solid fa-user"></i>
                <input type="text" name="username" placeholder="Username" autocomplete="off" required />
              </div>
              <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" placeholder="Password" autocomplete="off" required />
              </div>
              
              <button type="submit">Sign In</button>
              
              <p class="switch-para">
                <span>Don't have an account? </span>
                <b onclick="toggle()" class="pointer">Sign up here</b>
              </p>
            </form>
          </div>
        </div>
        <!-- END SIGN IN -->
      </div>
      <!-- END FORM SECTION -->
      
      <!-- CONTENT SECTION -->
      <div class="row content-row">
        <!-- SIGN IN CONTENT -->
        <div class="col align-items-center flex-col">
          <div class="text sign-in">
            <h2>Welcome to MegazenTrack</h2>
            <p>Your ultimate cloud-based warehouse fulfillment logistics ecosystem. Optimize margin valuations in real-time.</p>
          </div>
        </div>
        <!-- END SIGN IN CONTENT -->
        
        <!-- SIGN UP CONTENT -->
        <div class="col align-items-center flex-col">
          <div class="text sign-up">
            <h2>Join Our Platform</h2>
            <p>Access distributed inventories, coordinate supplier purchase workflows, and generate live executive dashboards instantly.</p>
          </div>
        </div>
        <!-- END SIGN UP CONTENT -->
      </div>
      <!-- END CONTENT SECTION -->
    </div>

    <script>
    function toggle() {
        const container = document.getElementById('container');
        container.classList.toggle('sign-up');
        
        const isSignUp = container.classList.contains('sign-up');
        const targetUrl = isSignUp ? 'signup.php' : 'login.php';
        window.history.pushState({ path: targetUrl }, '', targetUrl);
        
        document.title = isSignUp ? 'MegazenTrack – Create Account' : 'MegazenTrack – Sign In';
    }

    // Handle initial state if popstate back/forward is used
    window.onpopstate = function(event) {
        const container = document.getElementById('container');
        if (window.location.pathname.includes('signup.php')) {
            container.classList.add('sign-up');
            document.title = 'MegazenTrack – Create Account';
        } else {
            container.classList.remove('sign-up');
            document.title = 'MegazenTrack – Sign In';
        }
    };
    </script>
</body>
</html>

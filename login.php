<?php 
require 'config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
$currentPage = 'login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM tblusers WHERE UserName = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if (!$user) {
        $error = "User not found";
    } elseif (password_verify($password, $user['Password'])) {
        $_SESSION['user_id']    = $user['UserID'];
        $_SESSION['fullname']   = $user['FullName'];
        $_SESSION['role_id']    = (int)($user['RoleID'] ?? ROLE_ADMIN);
        $_SESSION['company_id'] = (int)($user['CompanyID'] ?? 0);
        
        $_SESSION['role']       = match((int)($user['RoleID'] ?? 1)) {
            ROLE_ADMIN    => 'admin',
            ROLE_MANAGER  => 'manager',
            ROLE_STAFF    => 'staff',
            ROLE_CUSTOMER => 'customer',
            default       => 'user',
        };

        if ((int)($user['RoleID'] ?? 1) === ROLE_CUSTOMER) {
            header("Location: customer_portal.php");
        } else {
            header("Location: dashboard.php");
        }
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MegazenTrack – Sign In</title>
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

    <div id="container" class="container">
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

              <div class="input-group">
                <i class="fa-solid fa-signature"></i>
                <input type="text" name="fullname" placeholder="Full Name" required />
              </div>
              <div class="input-group">
                <i class="fa-solid fa-user"></i>
                <input type="text" name="username" placeholder="Username" autocomplete="off" required />
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

              <?php if (!empty($error)): ?>
                  <div class="login-error"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
              <?php endif; ?>

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

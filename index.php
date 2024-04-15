<?php
// if (isset($_SESSION)) {
//   session_destroy();
// }

// require_once '/HireMe/classes/user.php';

// $user = new User($conn);

// if ($user->autoLogin()) {
//     header("Location: dashboard.php");
//     exit();
// }
// else{
//     header("Location: /HireMe/auth-login-basic.php");
//     exit();
// }
?>

<?php
if (true){
  header("Location: /HireMe/auth-login-basic.php");
  exit();
}
else{
    header("Location: ./error.php");
    exit();
}
?>

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to HireMe</title>
</head>
<body>
    <iframe src="./README.md" width="100%" height="650px"></iframe>
    <a href = './auth-login-basic.php'>Proceed to the site.</a>
</body>
</html> -->

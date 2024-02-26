<?php
  session_start();
 
  if(!$_SESSION['user']){
    header('Refresh:0 , url=/');
    exit();
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../global.css" />
    <link rel="stylesheet" href="dashboard.css" />
    <link
      rel="shortcut icon"
      href="../assets/favicon.ico"
      type="image/x-icon"
    />
  <title>Dashboard</title>
</head>
<body>
  <img src="../assets/logo.png" alt="Academic" class="logo" />
  <h1><?php echo $_SESSION['user']['name'] ?>, seja bem-vindo!</h1>
  <form action="../logout" method="post">
    <button type="submit">Sair</button>
  </form>
</body>
</html>
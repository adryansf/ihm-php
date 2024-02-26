<?php
  session_start();

  if($_SESSION['user']){
    header("Refresh:0 , url=/dashboard");
    exit();
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Dados de conexão com o banco de dados
    $host = 'db';
    $dbname = 'academic';
    $username = 'postgres';
    $password = 'postgres';

    // Conexão com o banco de dados
    try {
      $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      die("Erro na conexão com o banco de dados: " . $e->getMessage());
    }

    // Recebendo dados do formulário
    $email = $_POST['email'];
    $password = $_POST['pass'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($user && password_verify($password, $user['password'])){
      $_SESSION['user'] = $user;
      header('Location: dashboard');
      exit();
    } else{
      $error = "Email ou senha incorretos.";
    }
  }
?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="global.css" />
    <link rel="stylesheet" href="index.css" />
    <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon" />
    <title>Login</title>
  </head>
  <body>
    <?php if (isset($_GET['register'])): ?>
      <div class="register-success">
        <span>Cadastrado com Sucesso!</span>
      </div>
    <?php endif; ?>
    <aside>
      <img src="assets/logo.png" alt="Academic" class="logo" />
    </aside>
    <main>
      <div>
        <h1>Acessar</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <span>
            <label for="email">Email</label>
            <input
              name="email"
              type="email"
              placeholder="Digite o seu email"
              required
            />
          </span>
          <span>
            <label for="pass">Senha</label>
            <input
              name="pass"
              type="password"
              placeholder="Digite a sua senha"
              required
              minlength="8"
            />
          </span>
          <span class="error">
            <?php if (isset($error)): ?>
              <p><?php echo $error; ?></p>
            <?php endif; ?>
          </span>
          <button type="submit">Entrar</button>
          <a class="cadastro" href="cadastro">Cadastrar-se</a>
        </form>
      </div>

      <small>© Todos os direitos reservados por Academic</small>
    </main>
  </body>
</html>

<?php
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
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['pass'], PASSWORD_DEFAULT);

    // Verificar Email
    $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
      $error = "Esse email já está em uso.";
    }else{
      $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
      $stmt = $pdo->prepare($sql);

      // Bind dos parâmetros
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':password', $password);

      // Executando a query
      if ($stmt->execute()) {
        header("Refresh:0 , url=/?register=true");
      }
      else{
        $error="Não foi possível realizar o cadastro! Entre em contato com o administrador";
      }
    }    
  }
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../global.css" />
    <link rel="stylesheet" href="cadastro.css" />
    <link
      rel="shortcut icon"
      href="../assets/favicon.ico"
      type="image/x-icon"
    />
    <title>Cadastro</title>
  </head>
  <body>
    <aside>
      <img src="../assets/logo.png" alt="Academic" class="logo" />
    </aside>
    <main>
      <div>
        <h1>Cadastro</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <span>
            <label for="name">Nome</label>
            <input
              name="name"
              type="text"
              placeholder="Digite o seu nome"
              required
              minlength="3"
            />
          </span>
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
              minlength="8"
              required
            />
          </span>
          <span class="error">
            <?php if (isset($error)): ?>
              <p><?php echo $error; ?></p>
            <?php endif; ?>
          </span>
          <button type="submit">Cadastrar-se</button>
        </form>
      </div>

      <small>© Todos os direitos reservados por Academic</small>
    </main>
  </body>
</html>

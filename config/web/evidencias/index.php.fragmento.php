FRAGMENTO DOCUMENTAL DEL INICIO DE /var/www/html/index.php EN W-N02
<?php
session_start();
mysqli_report(MYSQLI_REPORT_OFF);

$host  = "192.168.20.20";
$bd    = "equipaments";
$error = "";

// Cerrar sesión
if (isset($_GET["salir"])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Login: se valida contra MySQL
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    c=@newmysqli(host, $_POST["usuario"] ?? "", $_POST["clave"] ?? "", $bd);
    if ($c->connect_errno) {
        $error = "Usuario o contraseña incorrectos";
    } else {
        session_regenerate_id(true);
        $_SESSION["usuario"] = $_POST["usuario"];
        $_SESSION["clave"]   = $_POST["clave"];
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Equipamientos de educación</title>
<style>
  * { box-sizing: border-box; }
  body {
    margin: 0;
    font-family: "Segoe UI", Roboto, Arial, sans-serif;
    background: linear-gradient(135deg, #1e3c72, #2a5298);
    min-height: 100vh;
    color: #1f2937;
  }

  /* ---------- LOGIN ---------- */
  .login-wrap {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }
  .login-card {
    background: #fff;
    width: 100%;
    max-width: 380px;
    padding: 36px 32px;
    border-radius: 14px;
    box-shadow: 0 15px 40px rgba(0,0,0,.3);
    text-align: center;
  }
  .login-card .icono { font-size: 48px; margin-bottom: 6px; }
  .login-card h1 { margin: 0 0 4px; font-size: 24px; }
  .login-card p.sub { margin: 0 0 24px; color: #6b7280; font-size: 14px; }
  .campo { text-align: left; margin-bottom: 16px; }
  .campo label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; }
  .campo input {
    width: 100%;
    padding: 11px 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 15px;
  }
  .campo input:focus {
    outline: none;
    border-color: #2a5298;
    box-shadow: 0 0 0 3px rgba(42,82,152,.2);
  }
  .btn {
    width: 100%;
    padding: 12px;
    border: 0;
    border-radius: 8px;
    background: #2a5298;
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
  }
  .btn:hover { background: #1e3c72; }
  .error {
    background: #fee2e2;
    color: #b91c1c;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 16px;
    font-size: 14px;
  }

  /* ---------- TABLA ---------- */
  .panel {
    max-width: 1300px;
    margin: 0 auto;
    padding: 24px 16px;
  }
  .barra {
    background: #fff;
    border-radius: 12px 12px 0 0;
    padding: 18px 20px;
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    align-items: center;
    justify-content: space-between;
  }
  .barra h1 { margin: 0; font-size: 22px; }
  .barra .info { color: #6b7280; font-size: 14px; }
  .barra .acciones { display: flex; gap: 10px; align-items: center; }
  .barra input {
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
  }
  .salir {
    background: #ef4444;
    color: #fff;
    padding: 8px 14px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
  }
  .salir:hover { background: #dc2626; }
  .tabla-wrap {
    background: #fff;
    max-height: 70vh;
    overflow: auto;
    border-radius: 0 0 12px 12px;
  }
  table { border-collapse: collapse; width: 100%; font-size: 13px; }
  th {
    position: sticky;
    top: 0;
    background: #1e3c72;
    color: #fff;
    text-align: left;
    padding: 10px 12px;
    white-space: nowrap;
  }
  td { padding: 8px 12px; border-bottom: 1px solid #e5e7eb; }
  tbody tr:nth-child(even) { background: #f3f4f6; }
  tbody tr:hover { background: #dbeafe; }
</style>
</head>
<body>

<?php if (!isset($_SESSION["usuario"])) { ?>

  <!-- ========== PANTALLA DE ACCESO ========== -->
  <div class="login-wrap">
    <div class="login-card">
      <div class="icono">🏫</div>
      <h1>Equipamientos de educación</h1>
      <p class="sub">Inicia sesión para ver los datos</p>

      <?php if ($error != "") { ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
      <?php } ?>

      <form method="post">
        <div class="campo">
          <label for="usuario">Usuario</label>
          <input id="usuario" name="usuario" autofocus autocomplete="username">
        </div>
        <div class="campo">
          <label for="clave">Contraseña</label>
          <input id="clave" name="clave" type="password" autocomplete="current-password">
        </div>
        <button class="btn">Entrar</button>
      </form>
    </div>
  </div>

<?php } else {
    // ========== PANTALLA CON LA TABLA ==========
    c=newmysqli(host, $_SESSION["usuario"], $_SESSION["clave"], $bd);
    $c->set_charset("utf8mb4");
    $r = $c->query("SELECT * FROM equipaments LIMIT 200");
?>
  <div class="panel">
    <div class="barra">
      <div>
        <h1>Equipamientos de educación</h1>
        <div class="info">
          Usuario: <?= htmlspecialchars($_SESSION["usuario"]) ?> ·
          <span id="contador"><?= $r->num_rows ?></span> filas
        </div>
      </div>
      <div class="acciones">
        <input id="buscador" type="search" placeholder="Buscar en la tabla...">
        <a class="salir" href="?salir=1">Salir</a>
      </div>
    </div>

    <div class="tabla-wrap">
      <table id="tabla">
        <thead>
          <tr>
            <?php foreach ($r->fetch_fields() as $campo) { ?>
              <th><?= htmlspecialchars($campo->name) ?></th>
            <?php } ?>
          </tr>
        </thead>
        <tbody>
          <?php while ($fila = $r->fetch_row()) { ?>
            <tr>
              <?php foreach ($fila as $valor) { ?>
                <td><?= htmlspecialchars($valor ?? "") ?></td>
              <?php } ?>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    // Filtro de búsqueda: oculta las filas que no contienen el texto escrito
    const buscador = document.getElementById("buscador");
    const filas = document.querySelectorAll("#tabla tbody tr");
    const contador = document.getElementById("contador");
    buscador.addEventListener("input", () => {
      const texto = buscador.value.toLowerCase();
      let visibles = 0;
      filas.forEach(f => {
        const coincide = f.textContent.toLowerCase().includes(texto);
        f.style.display = coincide ? "" : "none";
        if (coincide) visibles++;
      });
      contador.textContent = visibles;
    });
  </script>
<?php } ?>

</body>
</html>


<?php
session_start();
?>

<p>Obtendo dados da sessão. Mostrando a quantidade de visitas em outra página:</p>
<h1>Você acessou o site <?php echo $_SESSION["visitas"]; ?> vezes</h1>


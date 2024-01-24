<?php
session_start();

if (isset($_SESSION['visitas'])){
    $_SESSION['visitas'] = $_SESSION['visitas'] + 1;
}else{
    $_SESSION['visitas'] = 1;
}
    

?>

<h1>Contador de Visitas</h1>
<p>Quantas vezes vocês visitou o site:</p> <?php echo $_SESSION['visitas']; ?>
<br>
<a href="mostrar_visitas.php">Entre em outra página para obter os dados dessas sessão :]</a>
<?PHP
session_start();

include 'condiviso_sql.php';
connetti();

if (isset($_SESSION["user"])) // l'utente ha eseguito il logout
{
	session_unset();
	session_destroy();
}
$err="";
if (isset($_POST["idu"])) // l'utente ha eseguito il login
{
	$utente=mysql_query("select id, nome from utenti where id='".$_POST["idu"]."' and pass='".$_POST["pwd1"]."'");
	if (is_resource($utente) && mysql_num_rows($utente)==1)
	{
		$utente=mysql_fetch_array($utente);
		$_SESSION["user"]=$utente["id"];
		$_SESSION["nome"]=$utente["nome"];
		header("Location: home.php");
	}
	else // login fallito
	{
		$err="E-mail o password sbagliati. Ritenta.";
		session_unset();
		session_destroy();
	}
}
if (isset($_POST["nome_u"])) // l'utente si sta registrando
{
	$res=@mysql_query("insert into `".$db."`.`utenti` (`ID`, `nome`, `cognome`, `genere_preferito`, `pass`) VALUES ('".$_POST["mail_u"]."', '".$_POST["nome_u"]."', '".$_POST["cognome_u"]."', '".$_POST["genere_u"]."', '".$_POST["pwd2"]."')");
	if ($res) // registrazione riuscita
	{
		$_SESSION["user"]=$_POST["mail_u"];
		$_SESSION["nome"]=$_POST["nome_u"];
		header("Location: home.php?");
	}
	else // registrazione fallita
	{
		$err="Utente gia esistente. Usa una e-mail diversa.";
		session_unset();
		session_destroy();
	}
}
// se arrivo fin qua allora visualizzo la prima pagina
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html lang="it">
<head>
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Expires" content="-1">
	<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1">
	<title>Registrazione</title>
	<link rel="stylesheet" type="text/css" media="screen" href="./css/style_screen.css">
	<script type="text/javascript" src="./js/controlli_index.js"></script>
</head>
<body>
	<p class="p_bold p_inline i_no_interlinea" id="istruzioni"><a href="istruzioni.html" id="istr2">Istruzioni</a></p>
	<div id="entra" class="div_centrato sfondo_index"> <!-- form per entrare -->
		<p class="p_bold p_large p_no_interlinea titoli">Accedi alla tua libreria</p>
		<p class="p_no_interlinea p_inline">Id utente:</p>
		<p id="lab" class="p_no_interlinea p_inline label_password">Password:</p>

		<form action="index.php" method="post" id="form_entra">
			<p class="p_inline p_no_interlinea"><input type="text" name="idu" tabindex="1"></p>
			<p class="p_inline p_no_interlinea"><input type="password" name="pwd1" tabindex="2"></p>
			<p class="p_inline p_no_interlinea"><input type="button" value="Entra" name="entra" onclick="entrata()" tabindex="3"></p>
			<p id="err_gen" class="errore p_bold">
				<?PHP
					echo $err;
				?>
			</p>
		</form>
	</div>
	<div id="registrazione" class="div_centrato sfondo_index"> <!-- form per registrarsi -->
		<p class="p_bold p_large p_no_interlinea titoli">Registrati</p>
		<form action="index.php" method="post" id="registrati">
			<p class="top_index p_no_interlinea">E-mail:</p>
			<p class="p_inline p_no_interlinea"><input type="text" name="mail_u"></p><p class="errore p_inline  p_bold" id="err_mail"></p>
			<p class="top_index p_no_interlinea">Nome:</p>
			<p class="p_inline p_no_interlinea"><input type="text" name="nome_u"></p><p class="errore p_inline  p_bold" id="err_nome"></p>
			<p class="top_index p_no_interlinea">Cognome</p>
			<p class="p_inline p_no_interlinea"><input type="text" name="cognome_u"></p><p class="errore p_inline  p_bold" id="err_cognome"></p>
			<p class="top_index p_no_interlinea">Genere preferito</p>
			<p class="i_no_interlinea"><select name="genere_u">
  				<option value="orror">Orror</option>
  				<option value="avventura" selected="selected">Avventura</option>
				<option value="romantico">Romantico</option>
				<option value="fantasy">Fantasy</option>
				<option value="giallo">Giallo</option>
				<option value="fumetto">Fumetto</option>
			</select></p>
			<p class="top_index p_no_interlinea">Password:</p>
			<p class="p_inline p_no_interlinea"><input type="password" name="pwd2"></p><p class="errore p_inline  p_bold" id="err_pwd"></p>
			<p class="top_index p_no_interlinea">Conferma password:</p>
			<p class="p_inline p_no_interlinea"><input type="password" name="pwd3"></p>
			<p><input type="button" value="registrati" name="registrati" onclick="registra()"></p>
		</form>
	</div>
</body>
</html>
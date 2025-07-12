<?PHP
session_start();
if (!isset($_SESSION['user'])) // se nessun utente ha eseguito l'accesso, rimanda alla prima pagina
	header("Location: index.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html lang="it">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1">
	<title>Aggiungi libro</title>
	<link rel="stylesheet" type="text/css" media="screen" href="./css/style_screen.css">
	<script type="text/javascript" src="./js/controlli_nuovo.js"></script>
</head>
<body>
<h1 class="titoli">Nuovo libro</h1>
<div class="campi_nuovo"> <!-- contiene i campi: titolo, autore, ecc... -->
	<form action="libreria.php" enctype="multipart/form-data" method="post" name="novita">
		<div class="copertina">
			<p class="p_bold">Seleziona un'immagine.</p>
		 	<p class="p_inline p_no_interlinea"><input type="file" name="img"></p><p class="errore p_imm p_bold" id="err_img"></p>
		</div>
		<p class="p_no_interlinea p_bold">Titolo:</p>
		<p class="p_inline p_no_interlinea"><input type="text" name="titolo"></p><p class="errore p_bold p_no_interlinea p_inline" id="err_titolo"></p>
		<p class="p_no_interlinea p_bold">ISBN:</p>
		<p class="p_inline p_no_interlinea"><input type="text" name="isbn" maxlength="13"></p><p class="errore p_bold p_no_interlinea  p_inline" id="err_isbn"></p>
		<p class="p_no_interlinea p_bold">Autore:</p>
		<p class="p_inline p_no_interlinea"><input type="text" name="autore"></p><p class="errore p_bold p_no_interlinea  p_inline" id="err_autore"></p>
		<p class="p_no_interlinea p_bold">Anno:</p>
		<p class="p_inline p_no_interlinea"><input type="text" name="anno" maxlength="4"></p><p class="errore p_bold p_no_interlinea  p_inline" id="err_anno"></p>
		<p class="p_no_interlinea p_bold">Genere:</p>
		<p class="i_no_interlinea"><select name="genere">
  			<option value="orror">Orror</option>
  			<option value="avventura">Avventura</option>
			<option value="romantico">Romantico</option>
			<option value="fantasy">Fantasy</option>
			<option value="giallo">Giallo</option>
			<option value="fumetto" selected="selected">Fumetto</option>
		</select></p>
		<p class="p_no_interlinea p_bold">Editore:</p>
		<p class="p_inline p_no_interlinea"><input type="text" name="editore"></p><p class="errore p_bold p_no_interlinea  p_inline" id="err_editore"></p>
		<p class="p_no_interlinea p_bold">Voto:</p>
		<p class="i_no_interlinea"><select name="voto">
  			<option value="1">1</option>
  			<option value="2">2</option>
  			<option value="3">3</option>
  			<option value="4">4</option>
			<option value="5">5</option>
			<option value="0" selected="selected"></option>
		</select></p>
		<p class="p_no_interlinea p_bold">Commento:</p>
		<p class="p_no_interlinea p_left"><textarea name="commento" cols=40 rows=6 class="no_resize"></textarea></p><p class="errore p_bold p_no_interlinea  p_inline" id="er_commento"></p>
		<p class="bottone2 p_inline">
			<input type="button" value="Salva" onclick="controlla()">
  			<input type="submit" value="Annulla" onclick="annulla()">
		</p>
	</form>
</div>
</body>
</html>
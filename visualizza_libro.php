<?PHP
session_start();
if (!isset($_SESSION['user'])) // se nessun utente ha eseguito l'accesso, rimanda alla prima pagina
	header("Location: index.php");
$user_id=$_SESSION['user'];
if (!isset($_GET['ISBN'])) // se non è stato passato il parametro necessario per il corretto funzionamento
	header("Location: home.php"); // home.php e non index.php perchè so gia che c'è un utente attivo
include 'condiviso_sql.php';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html lang="it">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1">
	<title>Aggiungi libro</title>
	<link rel="stylesheet" type="text/css" media="screen" href="./css/style_screen.css">
	<script type="text/javascript" src="./js/controlli_visualizza.js"></script>
</head>
<body>
	<h1 class="titoli">Dettagli libro</h1>
	<div class="campi"> <!-- contiene i campi: titolo, autore, ecc... -->
		<?php
		connetti();
		if (isset($_GET['s'])) // devo visualizzare un libro suggerito da s
		{
			$result=null;
			if (strcmp($_SESSION['user'], $_GET['s'])!=0)
			{
				$query = mysql_query("select * from suggeriti, libri, commenti
 where id1='".$_GET['s']."' and id2='".$user_id."' and isbn_libro=".$_GET['ISBN']." and suggeriti.isbn_libro=libri.isbn
 and commenti.id='".$_GET['s']."' and commenti.isbn=suggeriti.isbn_libro") or die(mysql_error());
				$result = mysql_fetch_array($query);
			}
			if ($result == null) // se chi ha suggerito non possiede il libro suggerito, si usano solo i dati generali del libro
			{
				$query=mysql_query("select * from libri where isbn=".$_GET['ISBN']) or die(mysql_error());
				$result=mysql_fetch_array($query);
			}
			echo '<img class="box_laterale"  src="'.$result["url_img"].'" alt="isbn: '.$result["isbn"].'">
				<p class="p_contrasto2 p_bold p_no_interlinea">Titolo:</p><p class="i_no_interlinea">'.$result["titolo"].'</p>
				<p class="p_contrasto2 p_bold p_no_interlinea">Autore:</p><p class="i_no_interlinea">'.$result["autore"].'</p>
				<p class="p_contrasto2 p_bold p_no_interlinea">Anno:</p><p class="i_no_interlinea">'.$result["anno"].'</p>
				<p class="p_contrasto2 p_bold p_no_interlinea">Genere:</p><p class="i_no_interlinea">'.$result["genere"].'</p>
				<p class="p_contrasto2 p_bold p_no_interlinea">Editore:</p><p class="i_no_interlinea">'.$result["editore"].'</p>';
			if (isset($result["voto"])) // se è stato votato, visualizzo il voto
			{
				echo '<p class="p_contrasto2 p_bold p_no_interlinea">Voto:</p><p class="i_no_interlinea">'.$result["voto"].'</p>';
				if (strlen($result["commento"]) > 0) // se è stato commentato, visualizzo il commento
					echo '<p class="p_contrasto2 p_bold p_no_interlinea">Commento:</p><p class="i_no_interlinea">'.$result["commento"].'</p>';
			}
		}
		else // devo visualizzare un mio libro
		{
			$query = mysql_query("select * from libri, commenti where libri.isbn=commenti.isbn and libri.isbn=".$_GET['ISBN']." and id='".$user_id."'") or die(mysql_error());
			if (mysql_num_rows($query) > 0) // solo se il libro è nella mia libreria
			{
				$result = mysql_fetch_array($query);
				echo '<img class="box_laterale"  src="'.$result["url_img"].'" alt="isbn: '.$result["isbn"].'">
					<p class="p_contrasto2 p_bold p_no_interlinea">Titolo:</p><p class="i_no_interlinea">'.$result["titolo"].'</p>
					<p class="p_contrasto2 p_bold p_no_interlinea">Autore:</p><p class="i_no_interlinea">'.$result["autore"].'</p>
					<p class="p_contrasto2 p_bold p_no_interlinea">Anno:</p><p class="i_no_interlinea">'.$result["anno"].'</p>
					<p class="p_contrasto2 p_bold p_no_interlinea">Genere:</p><p class="i_no_interlinea">'.$result["genere"].'</p>
					<p class="p_contrasto2 p_bold p_no_interlinea">Editore:</p><p class="i_no_interlinea">'.$result["editore"].'</p>';
				if(($result["voto"]==0) || ($result["commento"]=="")) // il libro non è stato ancora valutato/commentato
				{
					echo '<form method="post" action="home.php" name="salva_commento">';
					if ($result["voto"]==0)
						echo '<p class="p_contrasto2 p_bold p_no_interlinea" id="v_n">Voto:</p><p class="i_no_interlinea"><select name="votazione">
  							<option value="1_'.$_GET['ISBN'].'" selected="selected">1</option>
  							<option value="2_'.$_GET['ISBN'].'">2</option>
  							<option value="3_'.$_GET['ISBN'].'">3</option>
  							<option value="4_'.$_GET['ISBN'].'">4</option>
							<option value="5_'.$_GET['ISBN'].'">5</option>
							</select></p>';
					else
						echo '<p class="p_contrasto2 p_bold p_no_interlinea">Voto:</p><p class="i_no_interlinea">'.$result["voto"].'</p>';
					if ($result["commento"]=="")
						echo '<p class="p_contrasto2 p_bold p_no_interlinea" id="c_n">Commento:</p><p id="nascosto">'.$_GET['ISBN'].'</p>
							<p class="p_inline p_no_interlinea"><textarea name="commento" cols=40 rows=6 class="no_resize"></textarea></p><p class="errore p_inline" id="err_commento"></p>';
					echo '<p class="bottone3 p_inline p_no_interlinea"><input type="button" value="Salva" onclick="controlla_commento()"></p>
						</form>';
				}
				else
					echo '<p class="p_contrasto2 p_bold p_no_interlinea">Voto:</p><p class="i_no_interlinea">'.$result["voto"].'</p>
						<p class="p_bold p_contrasto2 p_no_interlinea">Commento:</p><p class="i_no_interlinea">'.$result["commento"].'</p>';
			}
		}
		if (isset($_GET['s']) && strcmp($user_id, $_GET['s'])==0)
			echo '<form action="./libreria.php">';
		else
			echo '<form action="./home.php">';

	    	echo '<p class="p_inline p_no_interlinea bottone"><input type="submit" value="Indietro"></p></form>';
		mysql_close();
		?>
	</div> <!-- fine div campi -->
</body>
</html>
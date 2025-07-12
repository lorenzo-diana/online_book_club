<?PHP
session_start();

if (!isset($_SESSION["user"])) // se nessun utente ha eseguito l'accesso, rimanda alla prima pagina
{
	header("Location: index.php");
}

if (isset($_SESSION['ricorda'])) // non si ricordera piu la ricerca effettuata nella pagina libreria.php
{
	unset($_SESSION['ricorda']);
}

function presente($valore, $lista) // ritorna true se valore è presente nell'array lista
{
	while ($temp = mysql_fetch_array($lista))
		if ($temp['isbn'] == $valore)
			return true;
	return false;
}

$user_id=$_SESSION['user'];
include 'condiviso_sql.php';
connetti();

if (isset($_POST['Aggiungi']) && isset($_POST['elementi'])) // devi aggiungere gli elementi alla libreria, passati da libreria.php o dai libri suggeriti
{
	foreach ($_POST['elementi'] as $indice => $valore)
	{
		mysql_query("insert into `".$db."`.`commenti` (`id`, `isbn`, `commento`, `voto`) values ('".$_SESSION['user']."', '".$indice."', '', '0')");
	}
}

if (isset($_POST['Aggiungi2']) && isset($_POST['elementi'])) // devi aggiungere gli elementi alla libreria, passati da libreria.php o dai libri suggeriti
{
	foreach ($_POST['elementi'] as $indice_tot => $valore)
	{
		$indice=explode("_", $indice_tot);
		mysql_query("insert into `".$db."`.`commenti` (`id`, `isbn`, `commento`, `voto`) values ('".$_SESSION['user']."', '".$indice[0]."', '', '0')");
		mysql_query("delete from `".$db."`.`suggeriti` where `id1`='".$indice[1]."' and `id2`='".$_SESSION['user']."' and `isbn_libro`='".$indice[0]."'");
	}
}

if (isset($_POST['Suggerisci'])) // devi suggerire dei libri
{
	if (strcmp($_SESSION['user'], $_POST['utente_scelto'])) // non posso suggerirmi libri da solo
		foreach ($_POST['elementi'] as $indice2 => $valore2)
		{
			mysql_query("insert into `".$db."`.`suggeriti` (`id1`, `id2`, `isbn_libro`) values ('".$_SESSION['user']."', '".$_POST['utente_scelto']."', '".$indice2."')");
		}
}
if (isset($_POST['votazione'])) // l'utente ha aggiornato il voto di un libro, viene da visualizza libro.php
{
	$Voto_ISBN=explode("_", $_POST['votazione']);
	mysql_query("update `".$db."`.`commenti` set `voto`='".$Voto_ISBN[0]."' where `id`='".$user_id."' and`isbn`='".$Voto_ISBN[1]."'");
}
if (isset($_POST['commento'])) // l'utente ha aggiornato il commento di un libro, viene da visualizza libro.php
{
	$co=explode("#", $_POST['commento']);
	mysql_query("update `".$db."`.`commenti` set `commento`='".$co[0]."' where `id`='".$user_id."' and`isbn`='".$co[1]."'");
}
if (isset($_POST['Rimuovi'])) // Rimuove tutti gli elementi selezionati o (se non ce ne sono) tutti quelli non selezionabili dai suggeriti, viene da home.php
{
	if (isset($_POST['elementi'])) // devo eliminare tutti i libri suggeriti che sono stati selezionati
	{
		foreach ($_POST['elementi'] as $indice_tot => $valore)
		{
			$indice=explode("_", $indice_tot);
			mysql_query("delete from `".$db."`.`suggeriti` where `id1`='".$indice[1]."' and `id2`='".$_SESSION['user']."' and `isbn_libro`='".$indice[0]."'");
		}
	}
	else // devo eliminare tutti i libri suggeriti che gia possiedo
	{
		$rimuovere=mysql_query("select id1, isbn from commenti, suggeriti where suggeriti.id2='".$_SESSION['user']."' and isbn_libro=isbn and id='".$_SESSION['user']."'");
		while($result = mysql_fetch_array($rimuovere))
			mysql_query("delete from `".$db."`.`suggeriti` where `id1`='".$result['id1']."' and`id2`='".$_SESSION['user']."' and`isbn_libro`='".$result['isbn']."'");
		
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html lang="it">
<head>
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Expires" content="-1">
	<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1">
	<title>Home</title>
	<link rel="stylesheet" type="text/css" media="screen" href="./css/style_screen.css">
</head>
<body>
<?PHP
	echo '<h1 class="titoli">'.ucwords($_SESSION["nome"]).', la tua collezione</h1>';
?>
	<div class="primo"> <!-- contiene l'elenco dei tuoi libri -->
		<p class="i_no_interlinea p_no_interlinea p_bold"><a href="libreria.php" class="titoli_link">Libreria</a><a href="index.php" class="bottone2 titoli_link">Esci</a></p>
		<div class="contenitore" id="contenitore_home">
		<?PHP
			$query1 = mysql_query("select * from libri, commenti where libri.isbn=commenti.isbn and id='".$user_id."'") or die(mysql_error());
			if (mysql_num_rows($query1)==0)
				echo '<p>La tua libreria è vuota. Riempila!</p>';
			$i=0;
			while($result = mysql_fetch_array($query1))
			{
				echo '<div class="elemento el_home">
					<a class="descrizione_libri" href="visualizza_libro.php?ISBN='.$result["isbn"].'">
					<img class="img_el"  src="'.$result["url_img"].'" alt="isbn: '.$result["isbn"].'">
					</a>
            				<h4><a class="descrizione_libri" href="visualizza_libro.php?ISBN='.$result["isbn"].'">'.$result["titolo"].'</a></h4>
            				<p class="p_autore_anno"><a class="descrizione_libri" href="visualizza_libro.php?ISBN='.$result["isbn"].'">'.$result["autore"].' - '.$result["anno"].'</a></p>
				</div>';
				$i++;
			}
		?>
		</div> <!-- fine div contenitore -->
	</div>
	<div class="secondo"> <!-- contiene i libri suggeriti -->
		<p class="i_no_interlinea p_bold" id="label_suggeriti">Libri suggeriti</p>
		<form action="home.php" method="post">
			<div class="top_home">
				<p class="p_inline p_no_interlinea"><input type="submit" value="Aggiungi" name="Aggiungi2"></p>
				<p class="p_inline p_no_interlinea"><input type="submit" value="Rimuovi" name="Rimuovi"></p>
			</div>
			<div class="contenitore">
			<?PHP
				$supporto=mysql_query("select libri.isbn from libri, commenti where libri.isbn=commenti.isbn and id='".$user_id."'");
				$q = mysql_query("select isbn, url_img, titolo, autore, anno, nome, id1 from libri, suggeriti, utenti
 where libri.isbn=suggeriti.isbn_libro and id1=utenti.id and id2='".$user_id."'") or die(mysql_error());
				if (mysql_num_rows($q)==0)
					echo '<p>Non ci sono libri suggeriti.</p>';
				while($r = mysql_fetch_array($q))
				{
					echo '<div class="elemento el_lib">
						<a class="descrizione_libri" href="visualizza_libro.php?ISBN='.$r["isbn"].'&amp;s='.$r["id1"].'">
						<img class="img_el"  src="'.$r["url_img"].'" alt="isbn: '.$r["isbn"].'">
						</a>
						<input type="checkbox" name="elementi['.$r["isbn"].'_'.$r["id1"].']"';

					echo (presente($r["isbn"], $supporto)) ? ' disabled="disabled">' : '>';
					if (mysql_num_rows ($supporto) > 0)
						mysql_data_seek($supporto, 0);

					echo '<h4><a class="descrizione_libri" href="visualizza_libro.php?ISBN='.$r["isbn"].'&amp;s='.$r["id1"].'">'.$r["titolo"].'</a></h4>
        	    				<p class="p_autore_anno"><a class="descrizione_libri" href="visualizza_libro.php?ISBN='.$r["isbn"].'&amp;s='.$r["id1"].'">'.$r["autore"].' - '.$r["anno"].'</a></p>
						<p>Suggerito da: '.$r['nome'].'</p>
						</div>';
				}
				mysql_close();
			?>
			</div>
		</form>
	</div>
</body>
</html>
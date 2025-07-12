<?PHP
session_start();
if (!isset($_SESSION['user'])) // se nessun utente ha eseguito l'accesso, rimanda alla prima pagina
	header("Location: index.php");
$MAX_ROWS=3; // numero di righe da visualizzare quando non si è eseguita nessuna ricerca
function condizioni_ricerca($tipo, $valore)
{
	switch ($tipo)
	{
		case 2: // anno 
			return "anno=".$valore;
		case 3: // voto
			return "voto=".$valore;
		case 6: // anno >= di valore
			return "anno>=".$valore;
		case 7: // anno <= di valore
			return "anno<=".$valore;
		case 8: // voto >= di valore
			return "voto>=".$valore;
		case 9: // voto <= di valore
			return "voto<=".$valore;
		case 0: // valore compare in titolo
			return "titolo like '%".$valore."%'";
		case 1: // valore compare in autore
			return "autore like '%".$valore."%'";
		case 5: // isbn==valore
			return "isbn=".$valore;
	}
}
function pulisci_voto($v)
{
	$t=strpos($v, ".");
	return ($v[$t+1]=="0") ? substr($v, 0, $t) : substr($v, 0, $t+3);
}

$user_id=$_SESSION['user'];
$cartella_img="./img/";
include 'condiviso_sql.php';
connetti();

if (isset($_FILES["img"]))
	if (isset($_POST['isbn']))
		if(!((trim($_FILES["img"]["name"]) == '') or !is_uploaded_file($_FILES["img"]["tmp_name"]) or $_FILES["img"]["error"]>0))
			if (move_uploaded_file($_FILES["img"]["tmp_name"], $cartella_img.$_FILES["img"]["name"]))
			{
				mysql_query("INSERT INTO `".$db."`.`libri` (`isbn`, `titolo`, `autore`, `editore`, `anno`, `genere`, `url_img`) VALUES ('".$_POST['isbn']."', '".$_POST['titolo']."', '".$_POST['autore']."', '".$_POST['editore']."', ".$_POST['anno'].", '".$_POST['genere']."', '".$cartella_img.$_FILES["img"]["name"]."')") or die(mysql_error());
				mysql_query("INSERT INTO `".$db."`.`commenti` (`id`, `isbn`, `commento`, `voto`) VALUES ('".$user_id."', '".$_POST['isbn']."', '".$_POST['commento']."', '".$_POST['voto']."')
") or die(mysql_error());
			}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html lang="it">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1">
	<title>Libreria</title>
	<link rel="stylesheet" type="text/css" media="screen" href="./css/style_screen.css">
	<script type="text/javascript" src="./js/libreria.js"></script>
</head>
<?PHP
if (isset($_POST["ricerca"]))
{
	echo '<body onload="parametri('.$_POST["campo_ricerca"].', \''.$_POST["ricerca"].'\', \''.$_POST["genere"].'\')" >';
	$_SESSION['ricorda']=$_POST["campo_ricerca"]."#".$_POST["ricerca"]."#".$_POST["genere"];
}
else
	if (isset($_SESSION['ricorda']))
	{
		$ricordati=explode("#", $_SESSION['ricorda']);
		echo '<body onload="parametri('.$ricordati[0].', \''.$ricordati[1].'\', \''.$ricordati[2].'\')" >';
	}
	else
		echo '<body onload="parametri(-1, \'\', -1)">';
?>
	<h1 class="titoli">Libreria</h1>
	<div class="primo" id="primo_lib"> <!-- contiene l'elenco di tutti i libri -->
		<p class="i_no_interlinea p_bold"><a href="nuovo.php" class="titoli_link">Nuovo libro</a>
			<a href="home.php" class="bottone2 titoli_link p_bold">Home</a></p>
		<div class="ricerca p_left p_inline top2">
			<form action="libreria.php" method="post" accept-charset="utf-8" name="form_ricerca">
				<p class="p_no_interlinea p_inline p_contrasto">Genere:</p><p class="p_no_interlinea p_inline"><select name="genere" class="sposta_genere" onchange="cercalo()">
  					<option value="orror">Orror</option>
  					<option value="avventura">Avventura</option>
					<option value="romantico">Romantico</option>
					<option value="fantasy">Fantasy</option>
					<option value="giallo">Giallo</option>
					<option value="fumetto">Fumetto</option>
					<option value="" selected="selected"></option>
				</select></p>
				<p class="i_no_interlinea">
					<select name="campo_ricerca">
						<option value="0">Titolo</option>
  						<option value="1">Autore</option>
  						<option value="2">Anno</option>
						<option value="3">Voto</option>
						<option value="5">ISBN</option>
						<option value="6">Anno >=</option>
						<option value="7">Anno <=</option>
						<option value="8">Voto >=</option>
						<option value="9">Voto <=</option>
					</select>
					<input type="text" name="ricerca" id="ricerca" tabindex="1"><input type="button" value="cerca" name="cerca" onclick="cercalo()" tabindex="2">
				</p>
			</form>
		</div>
		<form action="home.php" method="post">
			<div class="bottone2 top1">
				<p class="p_inline p_no_interlinea"><input type="submit" value="Aggiungi" name="Aggiungi"></p>
				<p class="p_inline p_no_interlinea"><input type="submit" value="Suggerisci a" name="Suggerisci"></p>
				<p class="p_inline p_no_interlinea"><select name="utente_scelto">
				<?PHP
				$que = mysql_query("select id, nome
 from utenti") or die(mysql_error());
				while($res = mysql_fetch_array($que))
					if (strcmp($user_id, $res['id'])!=0) // non posso suggerire a mestesso
						echo '<option value="'.$res['id'].'">'.$res['nome'].'</option>';
				?>
				</select></p>
			</div>
			<div class="contenitore">
				<?PHP
				$query_base="select commenti.isbn, avg(voto) as voto, titolo, autore, anno, url_img
, genere, timestamp_libro from commenti, libri 
where commenti.isbn=libri.isbn
 group by isbn";
				if (isset($_POST["ricerca"]) && (($_POST["ricerca"]!="") || ($_POST["genere"]!=""))) // esegue la ricerca e filtra
				{
					$ricerca=" where ";
					if ($_POST["ricerca"]!="")
						$ricerca=$ricerca.condizioni_ricerca($_POST["campo_ricerca"], $_POST["ricerca"]);
					if ($_POST["ricerca"]!="" && $_POST["genere"]!="")
						$ricerca=$ricerca." and ";
					if ($_POST["genere"]!="")
						$ricerca=$ricerca."genere='".$_POST["genere"]."'";
					$query = mysql_query("select * from (".$query_base.") as base".$ricerca) or die(mysql_error());
				}
				else
					if (isset($_SESSION['ricorda']))
					{
						$ricordati=explode("#", $_SESSION['ricorda']);
						$ricerca=" where ";
						if ($ricordati[1]!="")
							$ricerca=$ricerca.condizioni_ricerca($ricordati[0], $ricordati[1]);
						if ($ricordati[1]!="" && $ricordati[2]!="")
							$ricerca=$ricerca." and ";
						if ($ricordati[2]!="")
							$ricerca=$ricerca."genere='".$ricordati[2]."'";
						$query = mysql_query("select * from (".$query_base.") as base".$ricerca) or die(mysql_error());
					}
					else
						$query = mysql_query("select *, (CURRENT_TIMESTAMP - timestamp_libro) as recente from (".$query_base.") as base order by recente limit ".$MAX_ROWS);

				while($result = mysql_fetch_array($query))
					echo '<div class="elemento el_lib">
						<a class="descrizione_libri" href="visualizza_libro.php?ISBN='.$result["isbn"].'&amp;s='.$user_id.'"> <!-- il loro script modifica &amp; in & generando cosi degli errori al momento della validazione. Usando invece &; si evitano errori ma s risulta indefinito -->
						<img class="img_el"  src="'.$result["url_img"].'" alt="isbn: '.$result["isbn"].'">
						</a>
            					<input type="checkbox" name="elementi['.$result["isbn"].']">
						<h4><a class="descrizione_libri" href="visualizza_libro.php?ISBN='.$result["isbn"].'">'.$result["titolo"].'</a></h4>
        	    				<p class="p_autore_anno i_no_interlinea"><a class="descrizione_libri i_no_interlinea" href="visualizza_libro.php?ISBN='.$result["isbn"].'">'.$result["autore"].' - '.$result["anno"].'</a></p>
						<p class="p_genere i_no_interlinea"><a class="descrizione_libri i_no_interlinea" href="visualizza_libro.php?ISBN='.$result["isbn"].'">'.$result["genere"].'</a></p>
						<p class="p_voto">Voto medio: '.pulisci_voto($result["voto"]).'</p>
						</div>';
				mysql_close();
				?>
			</div> <!-- fine div contenitore -->
		</form>
	</div>
</body>
</html>
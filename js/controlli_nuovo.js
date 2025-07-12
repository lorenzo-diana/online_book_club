var MAX_SIZE_IMG=1048576; // grandezza massima dell'immagine da utilizzare come copertina, 1 MB
function controlla() // controlla che i dati inseriti nei campi per aggiungere un nuovo libro siano corretti
{
	var elementi=new Array(7); // conterra il valore degli elementi input della pagina
	elementi[0] = document.getElementsByName("titolo")[0].value; // titolo
	elementi[1] = document.getElementsByName("isbn")[0].value; // isbn
	elementi[2] = document.getElementsByName("autore")[0].value; // autore
	elementi[3] = document.getElementsByName("anno")[0].value; // anno
	elementi[4] = document.getElementsByName("editore")[0].value; // editore
	elementi[5] = document.getElementsByName("img")[0].files; // img
	elementi[6] = document.getElementsByName("commento")[0].value; // commento, può essere vuoto ma non può contenere ' " #

	var errori=new Array(7); // conterra i campi di errore degli elementi input sopra citati
	errori[0] = document.getElementById("err_titolo");
	errori[1] = document.getElementById("err_isbn");
	errori[2] = document.getElementById("err_autore");
	errori[3] = document.getElementById("err_anno");
	errori[4] = document.getElementById("err_editore");
	errori[5] = document.getElementById("err_img");
	errori[6] = document.getElementById("er_commento");

	pulisci(errori); // rimuove eventuali errori vecchi
	if (!vuoti(elementi, errori)) // se non ci sono elementi vuoti
		if (caratteri_validi(elementi, errori)) // se i campi non contengono carateri speciali
			if (isbn_valido(elementi[1], errori[1]) && anno_valido(elementi[3], errori[3])) // se i campi isbn e anno sono validi
				if (immagine_selezionata(elementi[5], errori[5])) // se l'immagine è stata scelta
					document.forms["novita"].submit();
}
function isbn_valido(dato, e) // true -> il valore di ISBN è valido, false -> altrimenti
{
	var regex=new RegExp("^([0-9]{13})$"); // deve essere un numero di 13 cifre
	if (dato.match(regex))
		return true;
	e.appendChild(document.createTextNode("ISBN deve essere un numero di 13 cifre!"));
	return false;
}
function anno_valido(dato, e) // true -> dato è un numero di 4 cifre compreso tra 0 e l'anno attuale, false -> altrimenti
{
	var regex=new RegExp("^([0-9]{4})$");
	if (dato.match(regex)) // il dato è composto da 4 cifre numeriche
		if (parseInt(dato)<(new Date).getFullYear()) // il dato è entro i limiti
			return true;
		else
			e.appendChild(document.createTextNode("Anno può valere al massimo "+(new Date).getFullYear()+"!"));
	else
		e.appendChild(document.createTextNode("Anno deve essere un numero di 4 cifre!"));
	return false;
}
function immagine_selezionata(file, e) // true -> i parametri dell'immagine selezionata sono validi
{
	if (file.length==0) // nessun file caricato
	{
		e.appendChild(document.createTextNode("Devi selezionare un'immagine per la copertina del libro!"));
		return false;
	}
	if (file[0].size > MAX_SIZE_IMG) // dimensione maggiore di quella consentita
	{
		e.appendChild(document.createTextNode("L'immagine selezionata è troppo grande! La dimensione massima è: "+(MAX_SIZE_IMG/1048576)+" Mb"));
		return false;
	}
	var estensione=file[0].name.substring(file[0].name.lastIndexOf(".")+1, file[0].name.length);
	if (estensione!="png" && estensione!="bmp" && estensione!="jpg" && estensione!="jpeg" && estensione!="tif" && estensione!="tiff") // estensione del file non corretta
	{
		e.appendChild(document.createTextNode("I formati ammessi per l'immagine sono jpeg, png, bmp e tiff!"));
		return false;
	}
	return true;
}
function annulla() // torna alla pagina libreria.php
{
	window.location = "./libreria.php";
}



function caratteri_validi(el, er) // true -> nessun campo contiene i caratteri " ' #, false -> altrimenti, e l'errore viene visualizzato
{
	var valido=true;
	for (var i=0;i<el.length;i++)
		if (i!=5) // il file salta questo controllo
		if ((el[i].search("#")!=-1) || (el[i].search("\"")!=-1) || (el[i].search("\'")!=-1))
		{
			er[i].appendChild(document.createTextNode("I caratteri # \" \' non sono ammessi!"));
			valido=false;
		}
	return valido;
}
function vuoti(campi, e) // false -> nessun campo vuoto, true -> almeno un campo vuoto, e l'errore viene visualizzato
{
	var vuoto=false;
	for (var i=0;i<campi.length-1;i++) // il campo commento salta questo controllo
		if (campi[i]=="") // controlla se i campi sono vuoti
		{
			e[i].appendChild(document.createTextNode("Questo campo non può essere lasciato vuoto!"));
			vuoto=true;
		}
	return vuoto;
}
function pulisci(err) // elimina il primo figlio di ogni elemento dell'array del paramentro passato
{
	for (var i=0;i<err.length;i++)
		if (err[i].hasChildNodes())
			err[i].removeChild(err[i].firstChild);
}
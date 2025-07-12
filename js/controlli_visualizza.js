function controlla_commento() // controlla che il commento inserito nel campo commento non contenga caratteri proibiti # " '
{
	var isbn=document.getElementById("nascosto");
	if (!(typeof isbn === "undefined")) // c'è il commento da salvare
	{
		var isbn=isbn.firstChild.data;
		var commento=document.getElementsByName("commento")[0].value;
		var errore=document.getElementById("err_commento");
		if (errore.hasChildNodes()) // rimuove eventuale vecchio errore
			errore.removeChild(errore.firstChild);
		if ((commento.search("#")!=-1) || (commento.search("\"")!=-1) || (commento.search("\'")!=-1)) // se c'è un carattere proibito
			errore.appendChild(document.createTextNode("I caratteri # \" \' non sono ammessi!"));
		else
		{
			document.getElementsByName("commento")[0].value=commento+"#"+isbn;
			document.forms["salva_commento"].submit();
		}
	}
}
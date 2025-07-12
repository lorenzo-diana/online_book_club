function parametri(tipo_ricerca, ricerca, genere) // imposta nei corrispondenti campi il tipo di ricerca effettuata, il valore ricercato, e l'eventuale filtro che è stato applicato dalla ricerca appena effettuata
{
	if (tipo_ricerca!=-1) // se è stato cercato qualcosa aggiorna gli elementi "campo_ricerca" e "ricerca"
	{
		var campo_ricerca = document.getElementsByName("campo_ricerca")[0].options;
		campo_ricerca[trova_indice(campo_ricerca, tipo_ricerca)].setAttribute("selected", "selected");
		var t=document.getElementsByName("ricerca")[0];
		t.value=ricerca;
	}
	if (genere!=-1) // se è stato applicato un filtro aggiorna l'elemento "genere"
	{
		var gen=document.getElementsByName("genere")[0].options;
		gen[trova_indice(gen, genere)].setAttribute("selected", "selected");
	}
}
function trova_indice(lista, val) // restituisce l'indice dell'array lista che corrisponde a val, -1 se val non è presente nell'array
{
	for (var i=0;i<lista.length;i++)
		if (lista[i].value==val)
			return i;
	return -1;
}
function cercalo() // controlla che nel campo ricerca non siano presenti i caratteri proibiti # " '
{
	var campo=document.getElementsByName("ricerca")[0].value;
	if ((campo.search("#")!=-1) || (campo.search("\"")!=-1) || (campo.search("\'")!=-1))
		window.alert("I caratteri # \" \' non sono ammessi!");
	else
		document.forms["form_ricerca"].submit();
}
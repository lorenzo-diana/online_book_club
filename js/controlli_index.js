function entrata() // controlla che i dati inseriti nei campi per effettuare il login siano corretti
{
	var user = document.getElementsByName("idu")[0].value;
	var pwd = document.getElementsByName("pwd1")[0].value;
	var p=document.getElementById("err_gen");
	rimuovi_errore(p); // rimuovo un errore precedentemente visualizzato
	if (user=="" || pwd=="")
		p.appendChild(document.createTextNode("Devi riempire i campi Email e Password!"));
	else
		if (!mail_valida(user))
			p.appendChild(document.createTextNode("Il campo Email deve contenere un indirizzo di posta valido!"));
		else
			if (!pwd_valida(pwd))
				p.appendChild(document.createTextNode("Password errata!"));
			else // invia la richiesta di login
				var x=document.forms["form_entra"].submit();
}
function rimuovi_errore(p) // rimuove il primo figlio dell'elemento passato come parametro
{
	if (p.firstChild) // se ha un figlio allora
		p.removeChild(p.firstChild);
}
function mail_valida(mail) // controlla che il parametro passato corrisponda ad un indirizzo e-mail valido: true-> corrisponde, false-> altrimenti
{
	var regex=new RegExp("^([a-z])([a-z0-9\_\.\-]+)@([a-z0-9\_\.\-]+)([\.])([a-z]{2,3})$");
	return (mail.match(regex)) ? true: false;
}
function registra() // controlla che i dati inseriti nei campi per effettuare una registrazione siano corretti
{
	rimuovi_errore(document.getElementById("err_gen")); // rimuove un eventuale messaggio di errore vecchio lasciato dal php
	var err_reg=new Array(4);
	err_reg[0]=document.getElementById("err_mail");
	err_reg[1]=document.getElementById("err_nome");
	err_reg[2]=document.getElementById("err_cognome");
	err_reg[3]=document.getElementById("err_pwd");
	pulisci(err_reg); // cancella eventuali errori precedenti
	
	var campi=new Array(4);
	campi[0]=document.getElementsByName("mail_u")[0].value; // e-mail
	campi[1]=document.getElementsByName("nome_u")[0].value; // nome
	campi[2]=document.getElementsByName("cognome_u")[0].value; // cognome
	campi[3]=document.getElementsByName("pwd2")[0].value; // pass1
	var pass2=document.getElementsByName("pwd3")[0].value;

	if(!vuoti(campi, err_reg))
		if (campi[3]!=pass2) // se le due password non corrispondono
			err_reg[3].appendChild(document.createTextNode("Le due password non corrispondono!"));
		else
			if (!mail_valida(campi[0])) // se l'e-mail non è valida
				err_reg[0].appendChild(document.createTextNode("L'indirizzo e-mail inserito non è valido!"));
			else
				if (!pwd_valida(pass2)) // se la password contiene caratteri speciali
					err_reg[3].appendChild(document.createTextNode("La password non può contenere i caratteri # \" \'"));
				else // tutto ok
					document.forms["registrati"].submit();
}
function pwd_valida(pwd) // true -> pwd non contiene ' " #, false -> altrimenti
{
	if ((pwd.search("#")!=-1) || (pwd.search("\"")!=-1) || (pwd.search("\'")!=-1))
		return false;
	return true;
}




function vuoti(campi, err_reg) // false -> nessun campo vuoto, true -> almeno un campo vuoto e tale errore viene visualizzato
{
	var vuoto=false;
	for (var i=0;i<campi.length;i++) // controlla se i campi sono vuoti
		if (campi[i]=="")
		{
			err_reg[i].appendChild(document.createTextNode("Questo campo non può essere lasciato vuoto!"));
			vuoto=true;
		}
	return vuoto;
}
function pulisci(err) // rimuove il primo figlio di ogni elemento dell'array passato come parametro
{
	for (var i=0;i<err.length;i++)
		if (err[i].hasChildNodes())
			err[i].removeChild(err[i].firstChild);
}
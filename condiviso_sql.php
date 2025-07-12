<?PHP
	$host = "localhost";
	$user = "root";
	$password = "";
	$db = "tia";
	function connetti()
	{
		global $host;
		global $user;
		global $password;
		global $db;
		$connessione = mysql_connect($host,$user,$password) or die("err 1");
		mysql_select_db($db,$connessione) or die("err 2");
	}
?>
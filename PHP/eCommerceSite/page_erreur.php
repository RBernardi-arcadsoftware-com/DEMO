<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Error</title>
<link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
<p class='erreur'>Il est impossible de donner suite � la demande.</p>
<?php
$code = $_GET['code'];
switch ($code)
{
	case 1 :
		echo "Address unknown."; //Address inconnu Mauvais code MD5
		break; 
	case 2 : 
		echo "Deadline for downloading has been exceeded, please contact ARCAD Software support."; //Date limite d�pass�e pour le t�l�chargement
		break;
	case 3 :
		echo "Invalid session. You need to activate the cookies."; //Session invalide. Vous devez activer les cookies
		break;
	case 12 : 
		echo "Error during mail send.";
		break;
	case 13 :
		echo "Error inserting in the database.";
		break;
	case 15 :
		echo "Unable to connect to MySql DBMS.";
		break;
	case 16 :
		echo "Unable to connect to the database."; //base de donn�e Download
		break;
	case 50 :
		echo "The query has produced no results !";
		break;
	case 66 :
		echo "Error uploading file.";
		break;
	default :
		echo "Error occurred. Please retry.";
		break;
}

/*
switch ...

1 // Mauvais code MD5...
2 // Date limite d�pass�e.
3 // Session invalide (le client n'a pas activ� les cookies).
12 // Erreur � l'envois d'un Email.
13 // Erreur lors d'une insertion dans la base de donn�es.
*/

//echo $_GET['code'];

?>
</body>
</html>
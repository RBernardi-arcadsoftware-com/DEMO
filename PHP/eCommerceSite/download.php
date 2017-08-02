<?php
/*
 * Created on 29 sept. 06
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
session_start();
if ($_SESSION['produit'] == '') {
	header("Location: page_erreur.php?code=3");
	exit;
}

/**********************************************************************
**
** Classe de t�l�chargement de fichiers
** Version 1.0
** Fonctions : 
**      - cacher le vrai chemin du fichier
**      - permettre ou non la reprise du t�l�chargement
**      - T�l�chargement partiel (pratique pour les download managers)
**      - renommer le fichier � t�l�charger
**      - limiter ou non la vitesse de t�l�chargement
**
** Auteur: Mourad Boufarguine / EPT <mourad.boufarguine@gmail.com>
**
** License: Public Domain
** Warranty: None
**
***********************************************************************/

class Bf_Download{
    
    // juste un tableau pour regrouper les prpri�t�s du t�l�chargement
    private $properties = array("path" => "",       // le chemin r��l du fichier
                                "name" => "",       // pour renommer le fichier on the fly
                                "extension" => "",  // extension du fichier
                                "type" => "",       // type du fichier
                                "size" => "",       // taille du fichier
                                "resume" => "",     // permettre ou non la reprise du t�l�chargement
                                "max_speed" => ""   // limite de la vitesse de t�l�chargement (en ko) ( 0 = pas de limite)
                                );          

    // le constructeur
    public function __construct($path, $name="", $resume="off", $max_speed=0){  // par d�faut, la reprise n'est pas autoris�e, aucune limite de vitesse
        $name = ($name == "") ? substr(strrchr("/".$path,"/"),1) : $name; // si "name" n'est pas d�fini, le fichier ne sera pas renomm�
        $file_extension = strtolower(substr(strrchr($path,"."),1));       // extension
        switch( $file_extension ) {                                       // type
            case "mp3": $content_type="audio/mpeg"; break;
            case "mpg": $content_type="video/mpeg"; break;
            case "avi": $content_type="video/x-msvideo"; break;
            case "wmv": $content_type="video/x-ms-wmv";break;
            case "wma": $content_type="audio/x-ms-wma";break; 
            default: $content_type="application/force-download";
        }
        $file_size = filesize($path);                                     // taille 
        $this->properties =  array(
                                    "path" => $path, 
                                    "name" => $name, 
                                    "extension" =>$file_extension,
                                    "type"=>$content_type, 
                                    "size" => $file_size, 
                                    "resume" => $resume, 
                                    "max_speed" => $max_speed
                                    );
    }
    
    // m�thode publique pour avoir la valeur d'une propri�t�
    public function get_property ($property){
        if ( array_key_exists($property,$this->properties) )   // v�rifier si la propri�t� existe d�j� 
            return $this->properties[$property];               // retourner sa valeur
        else
            return null;                                       // sinon, retourner null
    }
    
    // m�thode publique pour changer la valeur d'une propri�t�        
    public function set_property ($property, $value){
        if ( array_key_exists($property, $this->properties) ){ // v�rifier si la propri�t� existe d�j�
            $this->properties[$property] = $value;             // changer sa valeur
            return true;
        } else
            return false;
    }
    
    // m�thode publique pour commencer le t�l�chargement
    public function download_file(){
        if ( $this->properties['path'] == "" )                 // si le chemin n'est pas indiqu�, erreur !
            echo "Nothing to download!";
        else {
            // si la reprise est permise ...
            if ($this->properties["resume"] == "on") {
                if(isset($_SERVER['HTTP_RANGE'])) {            // v�rifier si http_range est envoy� par le navigateur (ou download manager)
                    list($a, $range)=explode("=",$_SERVER['HTTP_RANGE']);  
                    ereg("([0-9]+)-([0-9]*)/?([0-9]*)",$range,$range_parts); // parsing Range header
                    $byte_from = $range_parts [1];     // l'intervalle de t�l�chargement: de $byte_from ...
                    $byte_to = $range_parts [2];       // ... � $byte_to 
                } else
                    if(isset($_ENV['HTTP_RANGE'])) {       // quelques serveurs web utilisent pl�tot $_ENV['HTTP_RANGE']
                        list($a, $range)=explode("=",$_ENV['HTTP_RANGE']);
                        ereg("([0-9]+)-([0-9]*)/?([0-9]*)",$range,$range_parts); // parsing Range header
                        $byte_from = $range_parts [1];     // l'intervalle de t�l�chargement: de $byte_from ... 
                        $byte_to = $range_parts [2];       // ... � $byte_to
                    }else{
                        $byte_from = 0;                         // si aucun ent�te http_range n'est envoy�, envoyer tout le fichier de l'octet 0 ...
                        $byte_to = $this->properties["size"] -1;   // ... au dernier octet
                    }
                if ($byte_to == "")                             // si l'octet de fin n'est pas sp�cifi� ...
                    $byte_to = $this->properties["size"] -1;    // ... lui affecter le dernier octet
                header("HTTP/1.1 206 Patial Content");          // envoyer l'ent�te de t�l�chargement partiel
            // ... sinon, t�l�charger tout le fichier
         } else {
                $byte_from = 0;
                $byte_to = $this->properties["size"] - 1;
            }
            
            $download_range = $byte_from."-".$byte_to."/".$this->properties["size"]; // l'intervalle de t�l�chargement
            $download_size = $byte_to - $byte_from +1;                                  // la taille de t�l�chargement
            
            // download speed limitation
            if (($speed = $this->properties["max_speed"]) > 0)                       // determiner la vitesse maximale ...
                $sleep_time = (8 / $speed) * 1e6;                                    // ... si "max_speed" = 0, pas de limite (par d�faut)
            else
                $sleep_time = 0;
            
            // envoyer les ent�tes   
            header("Pragma: public");                                                // vider le cache du navigateur
            header("Expires: 0");                                                    // ...
            header("Cache-Control:");                                                // ...
            header("Cache-Control: public");                                         // ... 
            header("Content-Description: File Transfer");                            //  
            header("Content-Type: ".$this->properties["type"]);                     // type
            header('Content-Disposition: attachment; filename="'.$this->properties["name"].'";');
            header("Content-Transfer-Encoding: binary");                             // methode du transfert 
            header("Content-Range: $download_range");                                // intervalle de t�l�chargement 
            header("Content-Length: $download_size");                                // taille de t�l�chargement
            
            // envoyer le contenu du fichier        
            $fp=fopen($this->properties["path"],"r");       // ouvrir le fichier 
            fseek($fp,$byte_from);                          // placer le pointeur au premier octet du t�l�chargement
            while(!feof($fp)){                              //   
                set_time_limit(240);                           // reinitialiser la limite du temps d'ex�cution pour les grands fichiers (sans aucun effet si php est ex�cut� en safe mode)
                print(fread($fp,1024*8));                   // envoyer 8ko 
                flush();
                usleep($sleep_time);                        // attendre (limitation de la vitesse)
            }
            fclose($fp);                                    // fermer le fichier
            exit;  
        }
    }
}

 $produit = $_SESSION['produit'];
 $outil_download = new Bf_Download("download/$produit",'','on');
 $sql = "SELECT * FROM ARTICLE,HSPCUST";
 $outil_download->download_file();
?>
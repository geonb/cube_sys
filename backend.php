<?php
	session_start();
	if (!isset($_GET['id'])) { 
	    $_GET['id'] = 1;                 // Wenn id nicht gesetzt, auf 1 setzen 
	} else { 
	    $_GET['id'] = (int) $_GET['id']; // Ansonsten von String nach Integer casten 
	} 	

   // Funktionen
   $res = 0.0;
   function cgi_param ($feld, $default) {
      $var = $default;
      $rmeth = $_SERVER['REQUEST_METHOD'];
      if ($rmeth == "GET") {
         if (isset ($_GET[$feld]) && $_GET[$feld] != "") {
            $var = $_GET[$feld];
         }
      } elseif ($rmeth == "POST") {
         if (isset ($_POST[$feld]) && $_POST[$feld] != "") {
            $var = $_POST[$feld];
         }
      }
      return $var;
   }
   
	include("config/configure.inc.php");

   // Verbindung zum MySQL-Server herstellen
   $connID = mysql_connect (DB_HOST, DB_USER, DB_PASSWORD);
   // Datenbank wählen
   $link = '';
   mysql_select_db (DB_NAME, $connID); 

/* check connection */
    if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
   // Datenbank w�hle
   // Steuerparameter auslesen
   $list = (isset($_GET['list'])) ? $_GET['list'] : false;
   $save = (isset($_GET['save'])) ? $_GET['save'] : false;
   //todo: delete '$delete'     $delete = cgi_param('delete', 0); // del
   $val = (isset($_GET['val'])) ? $_GET['val'] : false;
   $dwnl = (isset($_GET['dwnl'])) ? $_GET['dwnl'] : false;;

      /* if(isset($delete)) {  
   	 mysql_query("DELETE FROM phaidra_repo WHERE cmpid='$delete'");
      } */  
      
      
	function echoResultAsJson($result) {
	   
		if(mysql_num_rows($result) > 0){ //implies not 0
			$dataArr = array();
			while($data = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$row = Array();
				foreach ($data as $key => $value) {
					$row[$key] = $value;
				} 
				
				array_push($dataArr, $row);
			} 
			echo json_encode($dataArr); 
				
	   	} else {
			echo "{error:'no results were found'}";
		}
	}
   
   if($_GET['table'] == 'choose_mod') {
	   $result = mysql_query ("SELECT * FROM sys_001");
	   if(mysql_num_rows($result) > 0){ //implies not 0
			$dataArr = array();
			while($data = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$row = Array();
				foreach ($data as $key => $value) {
					$row[$key] = $value;
				} 
				
				array_push($dataArr, $row);
			} 
			echo json_encode($dataArr); 
				
	   	} else {
			echo "{error:'no results were found'}";
		}
   }
   if($_GET['table'] == 'select_mod') {
	   $result = mysql_query ("SELECT * FROM sys_001 WHERE incr=". $_GET['id'] ."");
	   if(mysql_num_rows($result) > 0){ //implies not 0
			$dataArr = array();
			while($data = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$row = Array();
				foreach ($data as $key => $value) {
					$row[$key] = $value;
				} 
				
				array_push($dataArr, $row);
			} 
			echo json_encode($dataArr); 
				
	   	} else {
			echo "{error:'no results were found'}";
		}
   }
   if($_GET['table'] == 'csv_file') {
	  if (!isset($_FILES['file'])) echo 'Dokument fehlt';
				if ($stream = fopen($_FILES['file']['tmp_name'], 'r')) {
				// print all the page starting at the offset 10
				$name = $_FILES['file']['name'];
				$axis = stream_get_contents($stream, -1, 0);
				$moved = mysql_query ("INSERT INTO sys_001 (name, Axis) VALUES ('$name','$axis')");
				if( $moved ) {
		  echo "Successfully read";         
		} else {
		  echo "Not uploaded";
		}
		}
		
		fclose($stream);
   }

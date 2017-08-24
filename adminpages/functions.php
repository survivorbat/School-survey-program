<?php	
	function addLog($person,$action) {
		$datum = date('c');
		$logfile = fopen("log/activity.log",'a+');
		$content = $datum." > ".$person.": ".$action."\r\n";
		fwrite($logfile,$content);
		fclose($logfile);
	}
?>
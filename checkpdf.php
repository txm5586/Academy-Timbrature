<html>
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Timbrature Hours</title>

        <style type="text/css">
            dummydeclaration { padding-left: 4em; } /* Firefox ignores first declaration for some reason */
            tab1 { padding-left: 4em; }
            tab2 { padding-left: 8em; }
            tab3 { padding-left: 12em; }
            tab4 { padding-left: 16em; }
            tab5 { padding-left: 20em; }
            tab6 { padding-left: 24em; }
            tab7 { padding-left: 28em; }
            tab8 { padding-left: 32em; }
            tab9 { padding-left: 36em; }
            tab10 { padding-left: 40em; }
            tab11 { padding-left: 44em; }
            tab12 { padding-left: 48em; }
            tab13 { padding-left: 52em; }
            tab14 { padding-left: 56em; }
            tab15 { padding-left: 60em; }
            tab16 { padding-left: 64em; }

            body {
            	margin: 0
            }

            div#grad1{
            	text-align: center;
				margin: 0;
				color: #f3f3f3;
				font-size: 30px;
				font-weight: 550;
				padding-top: 105px;
				bottom: 0px;
            }

            #grad1 {
            	height: 100px;
				width: 100%;
				background: linear-gradient(141deg, #0fb8ad 0%, #1fc8db 51%, #2cb5e8 75%);
				color: white;
				opacity: 1;
            }

            #maillink {
            	color: white;
            }

            #main_info {
            	margin-left: 10px;
            	margin-bottom: 25px;
            	margin-top: 0px;
            }

            #topdiv {
            	text-align: center;
            	background: #DCE35B;  /* fallback for old browsers */
				background: -webkit-linear-gradient(to right, #45B649, #DCE35B);  /* Chrome 10-25, Safari 5.1-6 */
				background: linear-gradient(to right, #45B649, #DCE35B); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
            	color: white;
            	top: 0;
            	height: 50px;
            	vertical-align: middle;
            	line-height: 50px;
            	font-weight: bold;
            }

            a {
            	text-decoration: none;
            }

        </style>

    </head>

<a href="index.php"> 
<div id="topdiv">
	Try another file 
</div>
</a>
<div id="main_info">
<br><br>
<?php
 
// Include Composer autoloader if not already done.
include 'vendor/autoload.php';

$targetfolder = "./";
$targetfolder = $targetfolder . "/document.pdf" ;


if (empty($_FILES['file']) || $_FILES['file']["size"] == 0) {
	header("Location: index.php?error=nofile");
	die();
}

if (empty($_POST["cohort"])) {
	header("Location: index.php?error=nocohort");
	die();
}

$cohort = $_POST["cohort"];

if ($_FILES['file']['type'] != "application/pdf") {
	echo "<p>The file must be uploaded in PDF format.</p>";
	exit();
}

if(move_uploaded_file($_FILES['file']['tmp_name'], $targetfolder)) {
	//echo "The file ". basename( $_FILES['file']['name']). " is uploaded<br><br>";
} else {
	echo "Problem uploading file";
	exit();
}

?>

<hr>
<p><b>IMPORTANT</b></p>
<p>1. Don't take the information you see here as absolutely accurate or consistent. I did try to be the most accurate as possible, but errors can happen. (If you find any problem, <a href="mailto:tassiomm@icloud.com?Subject=Timbrature%20Error" target="_top">let me know</a>)</p>
<p>2. It might not work on your pdf (again, <a href="mailto:tassiomm@icloud.com?Subject=Timbrature%20Error" target="_top">let me know</a>)</p>
<p>3. Probably not the ideal, but right now, it is considering the first badge in and the last badge out of the day to count the hours.</p>
<p>4. Yes, it takes on consideration all the 6 hours days as official hours.</p>
<p>5. It also takes on consideration the 10 minutes tolerance rule to count the hours. And the lunch hour is not counted.</p>
<p>6. The "Expected Official" hours is the max of official hours you can achieve in a certain period. It what you are expected to do.</p>
<hr>

<h2> Here are your results: </h2>

<?php

const MONTHS = ["gennaio", "febbraio", "marzo", "aprile", "maggio", "giugno", "luglio", "agosto", "settembre", "ottobre", "novembre", "dicembre"];
const WEEKDAYS = ["lun", "mar", "mer", "gio", "ven"];
const ENTRATA = "E";
const USCITA = "U";
const ABSENT = "Assente";
const AFTERNOON = "Afternoon";
const MORNING = "Morning";
const MASTER = "Master";

const SIXHOURDAYS = [
	"17/11/2017","24/11/2017",
	"01/12/2017","07/12/2017","15/12/2017","22/12/2017",
	"08/01/2018","09/01/2018","10/01/2018","11/01/2018","12/01/2018","19/01/2018","26/01/2018",
	"02/02/2018","09/02/2018","16/02/2018","23/02/2018",
	"01/03/2018","09/03/2018","16/03/2018","23/03/2018","30/02/2018",
	"06/04/2018","13/04/2018","20/04/2018","27/04/2018",
	"04/05/2018","11/05/2018","18/05/2018","25/05/2018",
	"01/06/2018","08/06/2018","15/06/2018","22/06/2018","29/06/2018"
];

const HOLIDAYS = [
	"01/11/2017","28/11/2017","08/12/2017","25/12/2017","26/12/2017","27/12/2017","28/12/2017","29/12/2017",
	"01/01/2018","02/01/2018","03/01/2018","04/01/2018","05/01/2018",
	"02/04/2018","25/04/2018",
	"01/05/2018","30/06/2018"];

const EXCEPTION = "16/10/2017";

class TimbratureDay {
	public $week_day;
	public $date;
	public $entries = array();
	public $exits = array();
	public $isAbsent;

	public $officialHours;
	public $extraHours;

	public $expectedHours;

	function __construct($week_day, $date) {
		$this->week_day = $week_day;
		$this->date = $date;
		$this->officialHours = 0;
		$this->extraHours = 0;
		$this->expectedHours = 4;
	}

	function printValue() {
		echo "<tab1>";
		echo $this->date . " ";

		if (count($this->entries) == 0) echo "(No Entries)";
		foreach ($this->entries as $value) {
			echo $value->format("\EH:i") . " ";
			break;
		}

		echo " | ";

		if (count($this->exits) == 0) echo "(No Exits)";
		foreach ($this->exits as $value) {
			echo $value->format("\UH:i") . " ";
			break;
		}

		echo "</tab1><br>";
	}

	function checkIfIsAbsent($string) {
		if (strpos($string, ABSENT) !== FALSE) { // Yoshi version
        	return true;
    	} else {
    		return false;
    	}
	}

	function checkIfEntry($string) {
		if (strpos($string, ENTRATA) !== FALSE) { // Yoshi version
        	return true;
    	} else {
    		return false;
    	}
	}

	function checkIfExit($string) {
		if (strpos($string, USCITA) !== FALSE) { // Yoshi version
        	return true;
    	} else {
    		return false;
    	}
	}

	function checkIfIsSixHourDay() {
		foreach (SIXHOURDAYS as $day) {
		    if (strpos($this->date, $day) !== FALSE) {
        		return true;
    		}
		}
		return false;
	}

	function checkIfIsHoliday() {
		foreach (HOLIDAYS as $day) {
		    if (strpos($this->date, $day) !== FALSE) {
        		return true;
    		}
		}
		return false;
	}

	function isInconsistent() {
		if ($this->isAbsent == false) {
			if (count($this->entries) == 0 || count($this->exits) == 0) {
				return true;
			}
		}

		return false;
	}

	function checkTolerance($time, $type) {
		$time = str_replace(ENTRATA,"",$time);
		$time = str_replace(USCITA,"",$time);
		
		$value = explode(":", $time);

		if (strcmp($type, ENTRATA) == 0) {
			if (intval($value[1]) <= 10) {
				return ENTRATA . $value[0] . ":00";
			} else {
				$hour = intval($value[0]) + 1;
				return ENTRATA . sprintf("%02d",$hour) . ":00";
			}
		} else if (strcmp($type, USCITA) == 0) {
			if (intval($value[1]) < 50) {
				return USCITA . $value[0] . ":00";
			} else {
				$hour = intval($value[0]) + 1;
				return USCITA . sprintf("%02d",$hour) . ":00";
			}
		}
	}


	function decodifyLine($line) {
		if ($this->checkIfIsAbsent($line)) {
			$this->isAbsent = true;
		} else {
			$this->isAbsent = false;

			$registers = explode(" ", $line);

			foreach ($registers as $key => $value) {
				if ($this->date == EXCEPTION) {
					$dateEntry = date_create_from_format('d/m/Y \EH:i', $this->date . " E14:00");
					$dateExit = date_create_from_format('d/m/Y \UH:i', $this->date . " U18:00");
					array_push($this->entries, $dateEntry);
					array_push($this->exits, $dateExit);
				} else if ($this->checkIfEntry($value)) {				
					$value = $this->checkTolerance($value, ENTRATA);

					$date = date_create_from_format('d/m/Y \EH:i', $this->date . " " . $value);
					array_push($this->entries, $date);
				} else if ($this->checkIfExit($value)) {
					$value = $this->checkTolerance($value, USCITA);

					$date = date_create_from_format('d/m/Y \UH:i', $this->date . " " . $value);
					array_push($this->exits, $date);
				}
			}

			$this->calcHours();
			
		}
	}

	function calcHours() {
		$cohort = $GLOBALS['cohort'];
		$this->expectedHours = 4;
		$this->officialHours = 0;
		$this->extraHours = 0;

		if ($cohort == MASTER) {
			$this->expectedHours = 8;
		} else if ($this->checkIfIsSixHourDay()) {
			$this->expectedHours = 6;
		}
		
		if (($this->isInconsistent() || $this->isAbsent)) {
			return;
		}

		$startOfficial = $this->entries[0];
		$countEnd = count($this->exits);
		$endOfficial = $this->exits[$countEnd-1];

		$OfficialEntryHour = "09";
		$OfficialExitHour = "13";	
		if ($this->checkIfIsSixHourDay()) {
			$OfficialExitHour = "16";
		}

		switch ($cohort) {
			case MORNING:
				$OfficialEntryHour = "09";
				$OfficialExitHour = "13";	
				if ($this->checkIfIsSixHourDay()) {
					$OfficialExitHour = "16";
				}
				break;
			case AFTERNOON:
				$OfficialEntryHour = "14";
				$OfficialExitHour = "18";
				if ($this->checkIfIsSixHourDay()) {
					$OfficialEntryHour = "11";
				}
				break;
			case MASTER:
				$OfficialEntryHour = "09";
				$OfficialExitHour = "18";
				break;
			
			default:
				$OfficialEntryHour = "09";
				$OfficialExitHour = "13";	
				if ($this->checkIfIsSixHourDay()) {
					$OfficialExitHour = "16";
				}
				break;
		}

		$entryHour = $this->entries[0]->format("H");
		$exitHour = $this->exits[$countEnd-1]->format("H");

		if (intval($entryHour) >= intval($OfficialExitHour) || intval($exitHour) <= intval($OfficialEntryHour)) {
			// Is All Extra
			$interval = date_diff($startOfficial, $endOfficial);
			$this->extraHours = intval($interval->format("%H"));
		} else {
			if (intval($entryHour) < intval($OfficialEntryHour)) {
				$startOfficial = date_create_from_format('d/m/Y H:i', $this->date . " " . $OfficialEntryHour . ":00");
			}
		
			if (intval($exitHour) > intval($OfficialExitHour)) {
				$endOfficial = date_create_from_format('d/m/Y H:i', $this->date . " " . $OfficialExitHour . ":00");
			}

			$interval = date_diff($startOfficial, $endOfficial);
			$this->officialHours = intval($interval->format("%H"));
			
			$totalHoursInterval = date_diff($this->entries[0], $this->exits[$countEnd-1]);
			$totalHours = intval($totalHoursInterval->format("%H"));
			$this->extraHours = $totalHours - $this->officialHours;

			if (intval($entryHour) <= 13 && intval($exitHour) > 13) {
				if ($this->checkIfIsSixHourDay() || $cohort == MASTER) {
					$this->officialHours--;
				} else {
					$this->extraHours--;
				}
			}
		}
	}

}

class TimbratureMonth {
	public $month;
	public $year;
	public $position_start;
	public $position_end;
	public $timbratureDays = array();

	function __construct($month, $year, $position_start) {
		$this->month = $month;
		$this->year = $year;
		$this->position_start = $position_start;
	}

	function setPositionEnd($position_end) {
		$this->position_end = $position_end;
	}

	function printValue() {
		echo $this->month . ": " . $this->year . "<br>";
	}

	function addTimbratureDay($timbratureDay) {
		array_push($this->timbratureDays, $timbratureDay);
	}

	function getAbsences() {
		$absences = array();
		foreach ($this->timbratureDays as $timbDay) {
			if ($timbDay->isAbsent) {
				array_push($absences, $timbDay);
			}
		}

		return $absences;
	}

	function getInconsistences() {
		$inconsistences = array();
		foreach ($this->timbratureDays as $timbDay) {
			if ($timbDay->isInconsistent()) {
				array_push($inconsistences, $timbDay);
			}
		}

		return $inconsistences;
	}

	function getOfficialHours() {
		$hours = 0;
		foreach ($this->timbratureDays as $timbDay) {
			$hours += $timbDay->officialHours;
		}

		return $hours;
	}

	function getExtraHours() {
		$hours = 0;
		foreach ($this->timbratureDays as $timbDay) {
			$hours += $timbDay->extraHours;
		}

		return $hours;
	}

	function getExpectedHours() {
		$hours = 0;
		foreach ($this->timbratureDays as $timbDay) {
			$hours += $timbDay->expectedHours;
		}

		return $hours;
	}
}

class Timbrature {
	public $cohort;
	private $text = array();
	private $months_positions = array();
	private $timbratureMonths = array();

	function __construct($textArray,$cohort) {
		foreach ($textArray as $key => $value) {
			$textArray[$key] = strip_tags($value);
		}

		$this->text = $textArray;
		$this->cohort = $cohort;
		$this->searchForMonths();
	}
   	
   	function printTimb() {
   		foreach ($this->text as $key => $value) {
			echo $key . " - " . $value . "<br>";
		}
   	}

   	function printTimbMonths() {
   		foreach ($this->timbratureMonths as $key => $timbMonth) {
   			$timbMonth->printValue();
   		}
   	}

   	function checkIfHasMonth($string) {
   		foreach (MONTHS as $month) {
		    //if (strstr($string, $url)) { // mine version
		    if (strpos($string, $month) !== FALSE) { // Yoshi version
        		return true;
    		}
		}
		return false;
   	}

   	function checkIfHasDay($string) {
   		foreach (WEEKDAYS as $weekdays) {
		    //if (strstr($string, $url)) { // mine version
		    if (strpos($string, $weekdays) !== FALSE) { // Yoshi version
        		return true;
    		}
		}
		return false;
   	}

   	function searchForMonths() {
   		foreach ($this->text as $key => $value) {
   			if ($this->checkIfHasMonth($value)) {
   				array_push($this->months_positions, $key);

   				$explodedMonth = explode(" ", $value);
   				$timbMonth = new TimbratureMonth($explodedMonth[0],intval($explodedMonth[1]),$key);
   				array_push($this->timbratureMonths, $timbMonth);
   			}
   		}

   		foreach ($this->timbratureMonths as $key => $timbmonth) {
   			if ($key == (count($this->timbratureMonths) - 1)) {
   				$timbmonth->setPositionEnd(count($this->text) - 3);
   			} else {
   				$timbmonth->setPositionEnd($this->timbratureMonths[$key+1]->position_start - 1);
   			}
   		}

   		$this->searchForDays();
   	}

   	function searchForDays() {
   		foreach ($this->timbratureMonths as $key => $timbMonth) {
   			for ($p = $timbMonth->position_start + 1; $p < $timbMonth->position_end; $p = $p + 2) { 
   				if ($this->checkIfHasDay($this->text[$p]) == false) {
   					$p++;
   				}

   				if ($this->text[$p+1] == "") {
   					$p++;
   				}

   				$dateweek = explode(" ", $this->text[$p]);

   				$timbDay = new TimbratureDay($dateweek[0], $dateweek[1]);
   				$timbDay->decodifyLine($this->text[$p+1]);
   				//$timbDay->printValue();

   				// MARK: Test piece of code
   				if ($timbDay->checkIfIsHoliday() == false) {
					$timbMonth->addTimbratureDay($timbDay);
				}
   			}
   		}	
   	}

   	function printBasicData() {
   		$nameCorso = explode("Corso", $this->text[3]);
   		echo "<p><b>Name: </b>" . $nameCorso[0] . "</p>";
   		echo "<p><b>Cohort: </b>" . $this->cohort . "<p>";
   	}

   	function runHoursCheck() {
   		$officialHours = 0;
   		$extraHours = 0;
   		$inconsistences = array();
   		$absences = array();
   		$officialHours = 0;
   		$extraHours = 0;
   		$expectedHours = 0;

   		foreach ($this->timbratureMonths as $key => $timbMonth) {
   			$inconsistences = array_merge($inconsistences, $timbMonth->getInconsistences());
   			$absences = array_merge($absences, $timbMonth->getAbsences());
   			$officialHours += $timbMonth->getOfficialHours();
   			$extraHours += $timbMonth->getExtraHours();
   			$expectedHours += $timbMonth->getExpectedHours();
   		}

   		echo "<p><b>- You were absence " . count($absences) . " time(s) on these day(s): </b></p>";
   		if (count($absences) == 0) {
   			echo "<tab1>No records</tab1>";
   		}

   		foreach ($absences as $timbDay) {
   			$timbDay->printValue();
   		}

   		echo "<p><b>- You have " . count($inconsistences) . " inconsistence(s) on the following day(s): </b>";
   		echo "<br>Ps: An inconsistence means that there was an error on the system, or your forgot to Badge In/Out.
   		<br>These hours are not counted for obvious reasons.</p>";
   		if (count($inconsistences) == 0) {
   			echo "<tab1>No records</tab1>";
   		}
   		foreach ($inconsistences as $timbDay) {
   			$timbDay->printValue();
   		}

   		echo "<p><b>- All the hours (until the most recent record): </b></p>";
   		echo "<p><tab1><b>Official: </b> ". $officialHours ." hours<tab1></p>";
   		echo "<p><tab1><b>Extra: </b> ". $extraHours ." hours<tab1></p>";
   		echo "<p><tab1><b>Total: </b> ". ($officialHours + $extraHours) ." hours<tab1></p>";
   		echo "<p><tab1><b>Expected Official: </b> ". $expectedHours ." hours";
   		if (($officialHours) < $expectedHours) {
   			echo " <font style='color:red;']> Not so good :( </font><br> <tab2> + Extra ";
   			if (($officialHours + $extraHours) < $expectedHours) {
   				echo " <font style='color:red;']> Sorry :( </font><br> <tab2>";
   			} else {
   				echo " <font style='color:green;']> All good :) </font><br> <tab2>";
   			}
   		} else {
   			echo " &nbsp&nbsp;<font style='color:green;']> You nailed! :D </font>";
   		}
   		echo "</tab1></p>";

   		echo "<hr>";
   		echo "<p><h2>Monthly hours:</h2></p>";
   		foreach ($this->timbratureMonths as $key => $timbMonth) {
   			$officialHours = $timbMonth->getOfficialHours();
   			$extraHours = $timbMonth->getExtraHours();
   			$expectedHours = $timbMonth->getExpectedHours();
   			echo "<p><h3>" . $timbMonth->month . " / ". $timbMonth->year . "</h3></p>";
   			echo "<p><tab1><b>Official: </b> ". $officialHours ." hours<tab1></p>";
   			echo "<p><tab1><b>Extra: </b> ". $extraHours ." hours<tab1></p>";
   			echo "<p><tab1><b>Expected Official: </b> ". $expectedHours ." hours";
   			
   			if (($officialHours) < $expectedHours) {
	   			echo " <font style='color:red;']> Not so good :( </font><br> <tab2> + Extra ";
	   			if (($officialHours + $extraHours) < $expectedHours) {
	   				echo " <font style='color:red;']> Sorry :( </font><br> <tab2>";
	   			} else {
	   				echo " <font style='color:green;']> All good :) </font><br> <tab2>";
	   			}
	   		} else {
	   			echo " &nbsp&nbsp;<font style='color:green;']> You nailed! :D </font>";
	   		}
	   		echo "</tab1></p>";



   		}

   	}
}

// Parse pdf file and build necessary objects.
$parser = new \Smalot\PdfParser\Parser();
$pdf    = $parser->parseFile('document.pdf');
 
$text = $pdf->getText();
$pdfText = nl2br($text);
$timbrature = explode("\n", $pdfText);

// Logic variables
$timb = new Timbrature($timbrature, $cohort);

$timb->printBasicData();
$timb->runHoursCheck();

//$timb->printTimb();
?>

<br>
</div>
<div id="grad1" style="text-align:center;margin:auto;color:#f3f3f3;font-size:15px;font-weight:550;padding-top:20px;">
<p>By Tassio Marques</p>
<p>Contact information: <a href="mailto:tassiomm@icloud.com" id="maillink">
tassiomm@icloud.com</a></p>
</div>

</html>
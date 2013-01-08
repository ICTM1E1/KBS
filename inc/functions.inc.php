<?php

// function author: caspar crop
function selectquery($sql, $db)
{
	$sth = $db->prepare($sql);
	$sth->execute();
	$result = $sth->fetchAll(PDO::FETCH_ASSOC);
	return $result;
}

function sortArticles($dbh)
{
	if (! $dbh)
	{
		$dbh = connectToDatabase();
	}
	
	$sth = $dbh->query("SELECT ID,title,date_added FROM article WHERE published='1' ORDER BY date_added");
	$sth->execute();
	
	$res = $sth->fetchAll(PDO::FETCH_ASSOC);
	
	$years = Array();
	
	foreach ($res as $row)
	{
		$date = new DateTime($row['date_added']);
		$year = $date->format("Y");
		$month = $date->format("F");
		$smonth = $date->format("m");
		
		if (! isset($years[$year]))
		{
			$years[$year] = Array();
			
			if (! isset($years[$year][$month]))
			{
				$years[$year][$month] = Array();
			}
		}
		
		$years[$year][$month][$row['ID']] = $row['title'];
	}
	$content = '';
	foreach ($years as $key => $val)
	{
		$content .= "<a rel=\"" . $year . "\" id=\"fold-year\" class=\"no-underline zipper\" href=\"#\"><span class='symbol'>&#x25B6;</span>" . $year . " </a>";
		$content .= "<ul id=" . $year . ">";
		
		foreach ($val as $month => $articles)
		{
			foreach ($articles as $art => $title)
			{
				$content .= "<li><a href=\"/artikel/" . $art . "\">" . $title . "</a></li>";
			}
		}
	}
	
	return $content;
	// echo("<a rel=\"".$year."-".$smonth."\" id=\"fold-month\" href=\"#\"
	// class=\"no-underline zipper\"> ></a> ");
	// echo("<a href=\"/artikel/".$row2['ID']."\"");
}

function connectToDatabase()
{
	$db = new PDO("mysql: host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_DATABASE, DB_USER, DB_PASS);
	
	return $db;
}

function isAjax()
{
	
	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
	{
		return true;
	}
	else
	{
		return false;
	}
}


/*
 * @author Robert-John van Doesburg
 * @desc Haal de maand op voor de agenda met eventueel gemaakte afspraken
 */
function getAgendaMonth($month = false, $year = false)
{	
	if (! $month)
	{
		$month = date('m');
		// $month = 1;
	}
	if (! $year)
	{
		$year = date('Y');
		// $year = 2013;
	}
	
	$total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
	$first_day = date("N", mktime(0, 0, 0, $month, 1, $year));
	$last_day = date("N", mktime(0, 0, 0, $month, $total_days, $year));
	$day_today = date('d');
	$month_today = date('m');
	
	/*
	 * Data vorige maand
	 */
	$previous_days = array();
	
	if ($month == 1)
	{
		$previous_month = 12;
		$previous_year = $year - 1;
	}
	else
	{
		$previous_month = $month - 1;
		$previous_year = $year;
	}
	
	$total_days_previous_month = cal_days_in_month(CAL_GREGORIAN, $previous_month, $previous_year);
	
	if ($first_day > 1)
	{
		for ($days = 0; $days < ($first_day - 1); $days++)
		{
			$previous_days[] = ($total_days_previous_month - $days);
		}
	}
	sort($previous_days);
	
	/*
	 * Data volgende maand
	 */
	$next_days = array();
	
	if ($month == 12)
	{
		$next_month = 1;
		$next_year = $year + 1;
	}
	else
	{
		$next_month = $month + 1;
		$next_year = $year;
	}
	
	$total_days_next_month = cal_days_in_month(CAL_GREGORIAN, $next_month, $next_year);
	
	$days_so_far = count($previous_days) + $total_days;
	
	if ($days_so_far < 42)
	{
		$days_remain = 42 - $days_so_far;
		for ($days = 1; $days < $days_remain + 1; $days++)
		{
			$next_days[] = $days;
		}
	}
	
	$start_date = $year . '-' . $month . '-' . 1;
	
	if(count($previous_days) > 0)
	{
		$start_date = $previous_year . '-' .  $previous_month . '-' . $previous_days[0];
	}
	
	$end_date = $year . '-' . $month . '-' . $total_days;
	
	if(count($next_days) > 0)
	{
		$end_date = $next_year . '-' . $next_month . '-' . end($next_days);
	}
	
	$db = connectToDatabase();
	
	/*
	 * Haal de agendapunten op gemaakt door de beheerder
	 */
	$sql = "
		SELECT *
		FROM agenda
		WHERE `start_datum` BETWEEN :start_date AND :end_date
		ORDER BY `start_datum`
	";
	
	$parameters = array(
		':start_date' => $start_date,
		':end_date' => $end_date,		
	);
	$sth = $db->prepare($sql);
	$sth->execute($parameters);
	$appointments = $sth->fetchAll(PDO::FETCH_ASSOC);
	
	/*
	 * Haal de aangevraagde diensten op
	 */
	$sql = "
		SELECT *
		FROM `dienst_aanvragen`
		WHERE `datum` BETWEEN :start_date AND :end_date
		ORDER BY `datum`
	";
	
	$parameters = array(
		':start_date' => $start_date,
		':end_date' => $end_date,		
	);
	$sth = $db->prepare($sql);
	$sth->execute($parameters);
	$requested_appointments = $sth->fetchAll(PDO::FETCH_ASSOC);
	
	/*
	 * Opbouw maand
	 */
	$cal = array();
	$curr_day = 1;
	$continue = false;
	$finished_month = false;
	
	for ($week = 0; $week <= 5; $week++)
	{
		$cal[$week] = array();
		for ($day = 1; $day <= 7; $day++)
		{
			$cal[$week][$day] = array();
			if (! $continue && $day == $first_day && $week == 0)
			{
				$cal_day = $curr_day;
				$cal_month = $month;
				$cal_year = $year;
				$continue = true;
				$curr_day++;
			}
			else if ($continue && $curr_day < $total_days && ! $finished_month)
			{
				$cal_day = $curr_day;
				$cal_month= $month;
				$cal_year = $year;
				$curr_day++;
			}
			else if ($continue && $curr_day == $total_days && ! $finished_month)
			{
				$cal_day = $curr_day;
				$cal_month = $month;
				$cal_year = $year;
				$curr_day = 1;
				$finished_month = true;
			}
			else if ($finished_month)
			{
				$cal_day = $next_days[$curr_day - 1];
				$cal_month = $next_month;
				$cal_year = $next_year;
				$curr_day++;
			}
			else
			{
				$cal_day = $previous_days[($day - 1)];
				$cal_month = $previous_month;
				$cal_year = $previous_year;
			}
			
			$cal[$week][$day]['day'] = $cal_day;
			$cal[$week][$day]['month'] = $cal_month;
			$cal[$week][$day]['year'] = $cal_year;
			
			$date_month = date('m', mktime(0,0,0, $cal_month, $cal_day, $cal_year));
			$date_day = date('d', mktime(0,0,0, $cal_month, $cal_day, $cal_year));
			$date = $cal_year . '-' . $date_month . '-' . $date_day;
			
			foreach($appointments as $key => $row)
			{
				if($row['start_datum'] == $date)
				{
					$cal[$week][$day]['appointments'][] = $row;
				}
				else if(strtotime($row['start_datum']) < strtotime($date) && strtotime($date) <= strtotime($row['eind_datum']) && $row['eind_datum'] != '0000-00-00')
				{
					
					$cal[$week][$day]['appointments'][] = $row;
				}
			}
			
			foreach($requested_appointments as $key => $row)
			{
				if($row['datum'] == $date)
				{
					$cal[$week][$day]['requested_appointments'][] = $row;
					unset($requested_appointments[$key]);
				}
			}
		}
	}
	
	$counter = 0;
	$data = '';
	$current_month_name = ucFirst(strftime('%B', mktime(0, 0, 0, $month, 1, $year)));
	$is_current_month = false;
	$is_next_month = false;
	foreach ($cal as $week)
	{
		$height = 16.6;
		$top = $counter * $height;
		$data .= '<div class="ag-month-row" style="height:' . $height . '%;top:' . $top . '%">';
		$data .= '<table class="ag-grid">';
		$data .= '<tbody>';
		$data .= '<tr>';
		foreach ($week as $date => $day)
		{
			$is_current_month = ($day['month'] == $month) ? true : false;
			$is_next_month = ($day['month'] == $next_month) ? true : false;
			
			$day_name = ucFirst(strftime('%A', mktime(0, 0, 0, $day['month'], $day['day'], $day['year'])));
			$month_name = ucFirst(strftime('%B', mktime(0, 0, 0, $day['month'], $day['day'], $day['year'])));
			$today = '';
			
			if ($day['day'] == $day_today && $is_current_month)
			{
				$today = ' day-today';
			}
			
			$non_month = '';
			if($day['month'] != $month)
			{
				$non_month = ' ag-non-month';
			}
			$data .= '<td class="ag-day' . $today  . $non_month . '">';
			$data .= '<div class="ag-day-row"><span>' . $day['day'] . '</span></div>';
			
			/*
			 * appointments
			 */
			if(isset($day['appointments']) || isset($day['requested_appointments']))
			{
				$appointments_count = 0;
				if(isset($day['appointments']))
				{
					$appointments_count += count($day['appointments']);
				}
				if(isset($day['requested_appointments']))
				{
					$appointments_count += count($day['requested_appointments']);
				}
				$appointments_counter = 1;
				$limit = 4;
				if($appointments_count > 4)
				{
					$limit = 3;
				}
				$data .= '<div class="ag-appointments">';
				if(isset($day['appointments']))
				{
					foreach($day['appointments'] as $key => $value)
					{
						$appointment_start = $value['start_tijd'] != null ? date('H:i',strtotime($value['start_tijd'])) : '';
						$appointment_end = $value['eind_tijd'] != null ? date('H:i',strtotime($value['eind_tijd'])) : '';
						if($value['hele_dag'] == 'true')
						{
							$appointment_start = '';
							$appointment_end = '';
						}
						
						$name = substr($value['naam'], 0, 30);
						$name = strlen($value['naam']) > 30 ? $name . '...':$name;
						
						$name_long = substr($value['naam'], 0, 75);
						$name_long = strlen($value['naam']) > 75 ? $name_long . '...':$name_long;
						
						
						$hidden = $appointments_counter > $limit ? ' hidden':''; 
						$data .= '<div class="ag-day-row ag-day-appointment' . $hidden . '">';
						$data .= '<span>' . $name . '</span>';
						$data .= '<input type="hidden" value="' . $name_long . '" class="ag-appointment-name"/>';
						$data .= '<input type="hidden" value="' . $value['locatie'] . '" class="ag-appointment-location"/>';
						$data .= '<input type="hidden" value="' . $appointment_start . '" class="ag-appointment-start"/>';
						$data .= '<input type="hidden" value="' . $appointment_end . '" class="ag-appointment-end"/>';
						$data .= '<input type="hidden" value="' . $value['id'] . '" class="ag-appointment-id"/>';
						$data .= '</div>';
						
						$appointments_counter++;
					}
				}
				
				if(isset($day['requested_appointments']))
				{
					foreach($day['requested_appointments'] as $key => $value)
					{
						$appointment_start = $value['start_tijd'] != null ? date('H:i',strtotime($value['start_tijd'])) : '';
						$appointment_end = $value['eind_tijd'] != null ? date('H:i',strtotime($value['eind_tijd'])) : '';
						$status = ' appointment-status-' . $value['status'];
						
						$name = substr($value['naam'], 0, 30);
						$name = strlen($value['naam']) > 30 ? $name . '...':$name;
						
						$name_long = substr($value['naam'], 0, 75);
						$name_long = strlen($value['naam']) > 75 ? $name_long . '...':$name_long;
						
						$hidden = $appointments_counter > $limit ? ' hidden':''; 
						$data .= '<div class="ag-day-row ag-day-appointment ag-day-requested-appointment' . $hidden . $status . '">';
						$data .= '<span>' . $value['naam'] . '</span>';
						$data .= '<input type="hidden" value="' . $value['naam'] . '" class="ag-appointment-name"/>';
						$data .= '<input type="hidden" value="' . $value['locatie'] . '" class="ag-appointment-location"/>';
						$data .= '<input type="hidden" value="' . $appointment_start . '" class="ag-appointment-start"/>';
						$data .= '<input type="hidden" value="' . $appointment_end . '" class="ag-appointment-end"/>';
						$data .= '<input type="hidden" value="' . $value['id'] . '" class="ag-appointment-id"/>';
						$data .= '<input type="hidden" value="' . $value['status'] . '" class="ag-appointment-status"/>';
						$data .= '</div>';
						
						$appointments_counter++;
					}				
				}
				
				if($appointments_count > 4)
				{
					$data .= '<div class="ag-day-row ag-day-appointment-more">';
					$data .= '<span>+' . ($appointments_count - 3) . ' extra</span>';
					$data .= '</div>';
				}
				$data .= '</div>';
			}
			/*
			 * End appointments
			 * Start rest data
			 */
			
			$data .= '<input type="hidden" value="' . $day['day'] . '" class="ag-date-day"/>';
			$data .= '<input type="hidden" value="' . $day['month'] . '" class="ag-date-month"/>';
			$data .= '<input type="hidden" value="' . $day['year'] . '" class="ag-date-year"/>';
			$data .= '<input type="hidden" value="' . $day_name . ', ' . $day['day'] . ' ' . $month_name . '" class="ag-date-display" />';
			$data .= '</td>';
		}
		$data .= '</tr>';
		$data .= '</tbody>';
		$data .= '</table>';
		$data .= '</div>';
		$counter++;
	}
	
	$result = array(
		'data' => $data, 'month' => $month, 'month_name' => $current_month_name, 'year' => $year
	);
	
	return $result;
}
// Erik de Vries
function upload($files)
{
	ini_set("post_max_size", "30M");
	ini_set("upload_max_filesize", "30M");
	$dbh = connectToDatabase();
	$file = $files["file"]["name"];
	$size = ($files["file"]["size"] / 1024);
	// bestanden die upgeload mogen worden.
	$allowedExts = array(
		"jpg", "jpeg", "gif", "png", "doc", "docx", "pdf", "pjpeg", "xls", "txt", "pptx", "ppt", "xml", "xlsx", "JPG", "JPEG"
	);
	$explode = explode(".", $files["file"]["name"]);
	$extension = end($explode);
	// de size van hoe groot het bestand maximaal mag worden in kb.
	if ($files["file"]["size"] < 8000000 && in_array($extension, $allowedExts))
	{
		if ($files["file"]["error"] > 0)
		{
			return "Return Code: " . $files["file"]["error"] . "<br />";
		}
		else
		{
			// echo "Upload: " . $files["file"]["name"] . "<br />";
			// echo "Type: " . $files["file"]["type"] . "<br />";
			// echo "Size: " . ($files["file"]["size"] / 1024) . " Kb<br />";
			// echo "Temp file: " . $files["file"]["tmp_name"] . "<br />";
			// upload
			if (file_exists(DOCROOT . 'uploads/' . $files["file"]["name"]))
			{
				return $files["file"]["name"] . " bestaat al. ";
			}
			else
			{
				if (move_uploaded_file($files["file"]["tmp_name"], DOCROOT . 'uploads/' . $files["file"]["name"]))
				{
					// db
					$sth = $dbh->prepare("INSERT INTO downloads (file, size) 
                                 VALUES('$file' , '$size')");
					$sth->execute();
					return true;
				}
			}
		}
	}
	else
	{
		return "Invalid file";
	}
}

// function author: caspar crop
function archivemonths($dmonth)
{
	// create archive
	$month = array();
	// fill the array with the months
	$month[1] = 'Januari';
	$month[2] = 'Februari';
	$month[3] = 'Maart';
	$month[4] = 'April';
	$month[5] = 'Mei';
	$month[6] = 'Juni';
	$month[7] = 'Juli';
	$month[8] = 'Augustus';
	$month[9] = 'September';
	$month[10] = 'Oktober';
	$month[11] = 'November';
	$month[12] = 'December';
	
	// make the function return the months name
	return $month[$dmonth];
}

// function author: caspar crop
function retreivearchive($dyear, $dmonth, $dbh)
{
	// sql statement (retreiving the published news and order it by date it was
	// last changed at)
	$sql = "SELECT A.ID, A.date_edited, A.title, A.TEXT, A.published
	    FROM article A JOIN category C ON A.cat_id = C.cat_id
	    WHERE (name='Actualiteiten' AND A.published =1)
		    AND (A.date_edited LIKE  '%$dyear-$dmonth-%')
	    ORDER BY date_edited DESC";
	// executing the query
	$sth = $dbh->prepare($sql);
	$sth->execute();
	// getting results in from the query
	$result = $sth->fetchAll(PDO::FETCH_ASSOC);
	// make the function retreive the articles asked for
	return $result;
}

// function author: caspar crop
function retreivenewsarticle($dbh)
{
	// sql statement (retreiving the published news and order it by date it was
	// last changed at)
	$sql = "SELECT title, TEXT
 	    FROM article
 	    WHERE (cat_id =11 AND published =1)
 	    ORDER BY date_added
 	    LIMIT 0, 3";
	// executing the query
	$sth = $dbh->prepare($sql);
	$sth->execute();
	// getting results in from the query
	$result = $sth->fetchAll(PDO::FETCH_ASSOC);
	// make the function retreive the articles asked for
	return $result;
}

function validEmail($email, $skipDNS = false)
{
	$isValid = true;
	$atIndex = strrpos($email, "@");
	if (is_bool($atIndex) && !$atIndex)
	{
		$isValid = false;
	}
	else
	{
		$domain = substr($email, $atIndex+1);
		$local = substr($email, 0, $atIndex);
		$localLen = strlen($local);
		$domainLen = strlen($domain);
		if ($localLen < 1 || $localLen > 64)
		{
		 // local part length exceeded
		 $isValid = false;
		}
		else if ($domainLen < 1 || $domainLen > 255)
		{
		 // domain part length exceeded
		 $isValid = false;
		}
		else if ($local[0] == '.' || $local[$localLen-1] == '.')
		{
		 // local part starts or ends with '.'
		 $isValid = false;
		}
		else if (preg_match('/\\.\\./', $local))
		{
		 // local part has two consecutive dots
		 $isValid = false;
		}
		else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
		{
		 // character not valid in domain part
		 $isValid = false;
		}
		else if (preg_match('/\\.\\./', $domain))
		{
		 // domain part has two consecutive dots
		 $isValid = false;
		}
		else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local)))
		{
		 // character not valid in local part unless
		 // local part is quoted
		 if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local)))
		 {
		 	$isValid = false;
		 }
		}

		if(!$skipDNS)
		{
			if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
			{
			 // domain not found in DNS
			 $isValid = false;
			}
		}
	}
	return $isValid;
}

function idarticle($dbh){
    
    $sql='SELECT * FROM article';
    $sth=$dbh->prepare($sql);
    $sth-> execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    $n = count($result);
    $num = $n+1;
    return $num;
}

/*
 * @author laughing-buddha.net 
 * @desc   Maak een willekeurig wachtwoord aan.
 */
 function generatePassword ($length = 8)
  {

    // start with a blank password
    $password = "";

    // define possible characters - any character in this string can be
    // picked for use in the password, so if you want to put vowels back in
    // or add special characters such as exclamation marks, this is where
    // you should do it
    $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";

    // we refer to the length of $possible a few times, so let's grab it now
    $maxlength = strlen($possible);
  
    // check for length overflow and truncate if necessary
    if ($length > $maxlength) {
      $length = $maxlength;
    }
	
    // set up a counter for how many characters are in the password so far
    $i = 0; 
    
    // add random characters to $password until $length is reached
    while ($i < $length) { 

      // pick a random character from the possible ones
      $char = substr($possible, mt_rand(0, $maxlength-1), 1);
        
      // have we already used this character in $password?
      if (!strstr($password, $char)) { 
        // no, so it's OK to add it onto the end of whatever we've already got...
        $password .= $char;
        // ... and increase the counter by one
        $i++;
      }

    }

    // done!
    return $password;

  }
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
	
	foreach ($years as $key => $val)
	{
		echo ("<a rel=\"" . $year . "\" id=\"fold-year\" class=\"no-underline zipper\" href=\"#\"> >" . $year . " </a>");
		echo ("<ul id=" . $year . "><li>");
		
		foreach ($val as $month => $articles)
		{
			echo ("<a rel=\"" . $year . "-" . $smonth . "\" id=\"fold-month\" href=\"#\" class=\"no-underline zipper\"> ></a> ");
			foreach ($articles as $art => $title)
			{
				echo ("<a href=\"/artikel/" . $art . "\">" . $title . "</a>");
			}
		}
	}
	
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

function getNextMonth()
{

}

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
	//var_dump($appointments);
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
					
					$cal[$week][$day]['appointments'][] = $row['naam'];
					unset($appointments[$key]);
					
				}
			}
		}
	}
	
	$counter = 0;
	$data = '';
	$current_month_name = ucFirst(strftime('%B', mktime(0, 0, 0, $month, 1, $year)));
	foreach ($cal as $week)
	{
		$top = $counter * 16.6;
		$data .= '<div class="ag-month-row" style="height:16.6%;top:' . $top . '%">';
		$data .= '<table class="ag-grid">';
		$data .= '<tbody>';
		$data .= '<tr>';
		foreach ($week as $date => $day)
		{
			$day_name = ucFirst(strftime('%A', mktime(0, 0, 0, $day['month'], $day['day'], $day['year'])));
			$month_name = ucFirst(strftime('%B', mktime(0, 0, 0, $day['month'], $day['day'], $day['year'])));
			$today = '';
			if ($day['day'] == $day_today && $month == $month_today)
			{
				$today = ' day-today';
			}
			
			$non_month = '';
			if($day['month'] != $month)
			{
				$non_month = ' class="ag-non-month"';
			}
			$data .= '<td class="ag-day' . $today . '">';
			$data .= '<div class="ag-day-row"><span' . $non_month . '>' . $day['day'] . '</span></div>';
			
			if(isset($day['appointments']))
			{
				foreach($day['appointments'] as $key => $value)
				{
					$data .= '<div class="ag-day-row ag-day-appointment"><span>' . $value . '</span></div>';
				}
			}
			
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
			echo "Return Code: " . $files["file"]["error"] . "<br />";
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
				echo $files["file"]["name"] . " bestaat al. ";
			}
			else
			{
				if (move_uploaded_file($files["file"]["tmp_name"], DOCROOT . 'uploads/' . $files["file"]["name"]))
				{
					// db
					$sth = $dbh->prepare("INSERT INTO downloads (file, size) 
                                 VALUES('$file' , '$size')");
					$sth->execute();
				}
			}
		}
	}
	else
	{
		echo "Invalid file";
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
	$sql = "SELECT ID, date_edited, title, TEXT, published
	    FROM article
	    WHERE (cat_id =10 AND published =1)
		    AND (date_edited LIKE  '%$dyear-$dmonth-%')
	    ORDER BY date_edited";
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
 	    WHERE (cat_id =10 AND published =1)
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
?>

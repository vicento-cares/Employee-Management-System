<?php 
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

include '../../conn.php';

$method = $_GET['method'];

// Home

if ($method == 'get_attendance_date') {
	// DS
	// if ($server_time >= '03:00:00' && $server_time <= '23:59:59') {
	// 	echo $server_date_only;
	// } else if ($server_time >= '00:00:00' && $server_time < '03:00:00') {
	// 	echo $server_date_only_yesterday;
	// }
	// NS
	// if ($server_time >= '15:00:00' && $server_time <= '23:59:59') {
	// 	echo $server_date_only;
	// } else if ($server_time >= '00:00:00' && $server_time < '15:00:00') {
	// 	echo $server_date_only_yesterday;
	// }
	$day_view_ds = '';
	$day_view_ns = '';
	$day_view_ads = '';

	if ($server_time >= '05:00:00' && $server_time <= '23:59:59') {
		$day_view_ds = $server_date_only;
		$day_view_ns = $server_date_only;
		$day_view_ads = $server_date_only;
	} else if ($server_time >= '00:00:00' && $server_time < '05:00:00') {
		$day_view_ds = $server_date_only_yesterday;
		$day_view_ns = $server_date_only_yesterday;
		$day_view_ads = $server_date_only_yesterday;
	}

	$response_arr = array(
		"day_view_ds" => $day_view_ds,
		"day_view_ns" => $day_view_ds,
		"day_view_ads" => $day_view_ds
	);

	echo json_encode($response_arr, JSON_FORCE_OBJECT);
}

if ($method == 'get_recent_time_in_out') {
	// REMOTE IP ADDRESS
	$ip = $_SERVER['REMOTE_ADDR'];

	$section = $_SESSION['section'];
	$line_no = $_SESSION['line_no'];
	$shift_group = $_GET['shift_group'];
	$c = 0;
	/*$sql = "SELECT tio.emp_no, emp.full_name, tio.time_in, tio.time_out, 
		HOUR(TIMEDIFF(DATE_FORMAT(tio.time_in, '%Y-%m-%d %H:%i'), DATE_FORMAT(tio.time_out, '%Y-%m-%d %H:%i'))) as hr_diff,
		MINUTE(TIMEDIFF(DATE_FORMAT(tio.time_in, '%Y-%m-%d %H:%i'), DATE_FORMAT(tio.time_out, '%Y-%m-%d %H:%i'))) as min_diff,
		HOUR(TIMEDIFF(DATE_FORMAT(tio.time_in, '%Y-%m-%d %H:%i'), DATE_FORMAT(tio.time_out, '%Y-%m-%d %H:%i'))) - 8 as hr_excess
		FROM t_time_in_out tio
		JOIN m_employees emp
		ON tio.emp_no = emp.emp_no
		WHERE emp.section = '$section' AND emp.line_no = '$line_no' AND tio.shift = '$shift'";*/

	// MySQL
	// $sql = "SELECT tio.emp_no, emp.full_name, tio.time_in, tio.time_out, 
	// 	HOUR(TIMEDIFF(DATE_FORMAT(tio.time_in, '%Y-%m-%d %H:%i'), DATE_FORMAT(tio.time_out, '%Y-%m-%d %H:%i'))) as hr_diff,
	// 	MINUTE(TIMEDIFF(DATE_FORMAT(tio.time_in, '%Y-%m-%d %H:%i'), DATE_FORMAT(tio.time_out, '%Y-%m-%d %H:%i'))) as min_diff,
	// 	HOUR(TIMEDIFF(DATE_FORMAT(tio.time_in, '%Y-%m-%d %H:%i'), DATE_FORMAT(tio.time_out, '%Y-%m-%d %H:%i'))) - 8 as hr_excess
	// 	FROM t_time_in_out tio
	// 	JOIN m_employees emp
	// 	ON tio.emp_no = emp.emp_no
	// 	WHERE";

	// MS SQL Server
	$sql = "SELECT tio.emp_no, emp.full_name, tio.time_in, tio.time_out, 
		(DATEDIFF(MINUTE, tio.time_in, tio.time_out) / 60) as hr_diff,
		(DATEDIFF(MINUTE, tio.time_in, tio.time_out) % 60) as min_diff,
		(DATEDIFF(MINUTE, tio.time_in, tio.time_out) / 60) - 8 as hr_excess
		FROM t_time_in_out tio
		JOIN m_employees emp
		ON tio.emp_no = emp.emp_no
		WHERE";
	
	if (!empty($section)) {
		$sql = $sql . " emp.section = '$section'";
	} else {
		$sql = $sql . " emp.section IS NULL";
	}
	if (!empty($line_no)) {
		$sql = $sql . " AND emp.line_no = '$line_no'";
	} else {
		$sql = $sql . " AND emp.line_no IS NULL";
	}
	$sql = $sql . " AND emp.shift_group = '$shift_group'";

	// Search by IP
	// if ($ip != '172.25.112.131') {
	// 	$sql = $sql . " AND tio.ip = '$ip'";
	// } else {
	// 	$sql = $sql . " AND tio.ip = '172.25.112.131'";
	// }

	// DS
	// if ($server_time >= '03:00:00' && $server_time <= '23:59:59') {
	// 	$sql = $sql . " AND tio.day = '$server_date_only'";
	// } else if ($server_time >= '00:00:00' && $server_time < '03:00:00') {
	// 	$sql = $sql . " AND tio.day = '$server_date_only_yesterday'";
	// }
	// NS
	// if ($server_time >= '15:00:00' && $server_time <= '23:59:59') {
	// 	$sql = $sql . " AND tio.day = '$server_date_only'";
	// } else if ($server_time >= '00:00:00' && $server_time < '15:00:00') {
	// 	$sql = $sql . " AND tio.day = '$server_date_only_yesterday'";
	// }
	if ($server_time >= '05:00:00' && $server_time <= '23:59:59') {
		$sql = $sql . " AND tio.day = '$server_date_only'";
	} else if ($server_time >= '00:00:00' && $server_time < '05:00:00') {
		$sql = $sql . " AND tio.day = '$server_date_only_yesterday'";
	}
	$sql = $sql . " ORDER BY tio.date_updated DESC";

	//Temporary
	//$sql = $sql . " LIMIT 0, 100";

	$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $row){
			$c++;
			$hr_diff = intval($row['hr_diff']);
			$min_diff = intval($row['min_diff']);
			$hr_excess = intval($row['hr_excess']);
			$diff = "";
			$excess = "";
			echo '<tr>';
				echo '<td>'.$c.'</td>';
				echo '<td>'.$row['emp_no'].'</td>';
				echo '<td>'.$row['full_name'].'</td>';
				echo '<td>'.$row['time_in'].'</td>';
				echo '<td>'.$row['time_out'].'</td>';
				// Time Diff
				if ($hr_diff > 1) {
					$diff = $hr_diff . " hrs";
				} else if ($hr_diff == 1) {
					$diff = $hr_diff . " hr";
				}
				if ($min_diff > 1) {
					$diff = $diff . " " .$min_diff. " mins";
				} else if ($min_diff == 1) {
					$diff = $diff . " " .$min_diff. " min";
				}
				echo '<td>'.$diff.'</td>';
				// Excess
				if ($hr_excess > 1) {
					$excess = $hr_excess . " hrs";
				} else if ($hr_excess == 1) {
					$excess = $hr_excess . " hr";
				}
				if ($hr_excess >= 8) {
					if ($min_diff > 1) {
						$excess = $excess . " " .$min_diff. " mins";
					} else if ($min_diff == 1) {
						$excess = $excess . " " .$min_diff. " min";
					}
				}
				echo '<td>'.$excess.'</td>';
			echo '</tr>';
		}
	}
}

if ($method == 'get_time_out_counting') {
	$day = $_GET['day'];

	$c = 0;

	$sql = "DECLARE @day DATETIME = ?;
			DECLARE @day_tomorrow DATETIME = DATEADD(DAY, 1, CAST(@day AS DATETIME2));

			WITH AttendanceData AS (
			SELECT 
				emp.dept, 
				emp.section,
				COUNT(CASE WHEN (tio.time_out BETWEEN CONVERT(DATETIME, @day + ' 15:00:00') AND CONVERT(DATETIME, @day +' 15:59:59')) 
							OR (tio.time_out BETWEEN CONVERT(DATETIME, @day_tomorrow + ' 03:00:00') AND CONVERT(DATETIME, @day_tomorrow + ' 03:59:59')) 
							OR (tio.time_out IS NULL AND tio.day = @day) THEN 1 END) AS total_0,
				COUNT(CASE WHEN (tio.time_out BETWEEN CONVERT(DATETIME, @day + ' 16:00:00') AND CONVERT(DATETIME, @day + ' 16:59:59')) 
							OR (tio.time_out BETWEEN CONVERT(DATETIME, @day_tomorrow + ' 04:00:00') AND CONVERT(DATETIME, @day_tomorrow + ' 04:59:59')) THEN 1 END) AS total_1,
				COUNT(CASE WHEN (tio.time_out BETWEEN CONVERT(DATETIME, @day + ' 17:00:00') AND CONVERT(DATETIME, @day + ' 17:59:59')) 
							OR (tio.time_out BETWEEN CONVERT(DATETIME, @day_tomorrow + ' 05:00:00') AND CONVERT(DATETIME, @day_tomorrow + ' 05:59:59')) THEN 1 END) AS total_2,
				COUNT(CASE WHEN (tio.time_out BETWEEN CONVERT(DATETIME, @day + ' 18:00:00') AND CONVERT(DATETIME, @day + ' 18:59:59')) 
							OR (tio.time_out BETWEEN CONVERT(DATETIME, @day_tomorrow + ' 06:00:00') AND CONVERT(DATETIME, @day_tomorrow + ' 06:59:59')) THEN 1 END) AS total_3,
							-- Calculate total
				COUNT(CASE WHEN (tio.time_out BETWEEN CONVERT(DATETIME, @day + ' 15:00:00') AND CONVERT(DATETIME, @day + ' 15:59:59')) 
							OR (tio.time_out BETWEEN CONVERT(DATETIME, @day_tomorrow + ' 03:00:00') AND CONVERT(DATETIME, @day_tomorrow + ' 03:59:59')) 
							OR (tio.time_out IS NULL AND tio.day = @day) THEN 1 END) +
				COUNT(CASE WHEN (tio.time_out BETWEEN CONVERT(DATETIME, @day + ' 16:00:00') AND CONVERT(DATETIME, @day + ' 16:59:59')) 
							OR (tio.time_out BETWEEN CONVERT(DATETIME, @day_tomorrow + ' 04:00:00') AND CONVERT(DATETIME, @day_tomorrow + ' 04:59:59')) THEN 1 END) +
				COUNT(CASE WHEN (tio.time_out BETWEEN CONVERT(DATETIME, @day + ' 17:00:00') AND CONVERT(DATETIME, @day + ' 17:59:59')) 
							OR (tio.time_out BETWEEN CONVERT(DATETIME, @day_tomorrow + ' 05:00:00') AND CONVERT(DATETIME, @day_tomorrow + ' 05:59:59')) THEN 1 END) +
				COUNT(CASE WHEN (tio.time_out BETWEEN CONVERT(DATETIME, @day + ' 18:00:00') AND CONVERT(DATETIME, @day + ' 18:59:59')) 
							OR (tio.time_out BETWEEN CONVERT(DATETIME, @day_tomorrow + ' 06:00:00') AND CONVERT(DATETIME, @day_tomorrow + ' 06:59:59')) THEN 1 END) AS total,
				(
					(COUNT(CASE WHEN (tio.time_out BETWEEN CONVERT(DATETIME, @day + ' 15:00:00') AND CONVERT(DATETIME, @day + ' 15:59:59')) 
								OR (tio.time_out BETWEEN CONVERT(DATETIME, @day_tomorrow + ' 03:00:00') AND CONVERT(DATETIME, @day_tomorrow + ' 03:59:59')) 
								OR (tio.time_out IS NULL AND tio.day = @day) THEN 1 END) * 0) 
								+
					(COUNT(CASE WHEN (tio.time_out BETWEEN CONVERT(DATETIME, @day + ' 16:00:00') AND CONVERT(DATETIME, @day + ' 16:59:59')) 
								OR (tio.time_out BETWEEN CONVERT(DATETIME, @day_tomorrow + ' 04:00:00') AND CONVERT(DATETIME, @day_tomorrow + ' 04:59:59')) THEN 1 END) * 1) 
								+
					(COUNT(CASE WHEN (tio.time_out BETWEEN CONVERT(DATETIME, @day + ' 17:00:00') AND CONVERT(DATETIME, @day + ' 17:59:59')) 
								OR (tio.time_out BETWEEN CONVERT(DATETIME, @day_tomorrow + ' 05:00:00') AND CONVERT(DATETIME, @day_tomorrow + ' 05:59:59')) THEN 1 END) * 2) 
								+
					(COUNT(CASE WHEN (tio.time_out BETWEEN CONVERT(DATETIME, @day + ' 18:00:00') AND CONVERT(DATETIME, @day + ' 18:59:59')) 
								OR (tio.time_out BETWEEN CONVERT(DATETIME, @day_tomorrow + ' 06:00:00') AND CONVERT(DATETIME, @day_tomorrow + ' 06:59:59')) THEN 1 END) * 3) 
				) AS total_times,
							-- Calculate average_ot
				FORMAT(
				CASE WHEN (
					COUNT(CASE WHEN (tio.time_out BETWEEN CONVERT(DATETIME, @day + ' 15:00:00') AND CONVERT(DATETIME, @day + ' 15:59:59')) 
							OR (tio.time_out BETWEEN CONVERT(DATETIME, @day_tomorrow + ' 03:00:00') AND CONVERT(DATETIME, @day_tomorrow + ' 03:59:59')) 
							OR (tio.time_out IS NULL AND tio.day = @day) THEN 1 END) +
					COUNT(CASE WHEN (tio.time_out BETWEEN CONVERT(DATETIME, @day + ' 16:00:00') AND CONVERT(DATETIME, @day + ' 16:59:59')) 
								OR (tio.time_out BETWEEN CONVERT(DATETIME, @day_tomorrow + ' 04:00:00') AND CONVERT(DATETIME, @day_tomorrow + ' 04:59:59')) THEN 1 END) +
					COUNT(CASE WHEN (tio.time_out BETWEEN CONVERT(DATETIME, @day + ' 17:00:00') AND CONVERT(DATETIME, @day + ' 17:59:59')) 
								OR (tio.time_out BETWEEN CONVERT(DATETIME, @day_tomorrow + ' 05:00:00') AND CONVERT(DATETIME, @day_tomorrow + ' 05:59:59')) THEN 1 END) +
					COUNT(CASE WHEN (tio.time_out BETWEEN CONVERT(DATETIME, @day + ' 18:00:00') AND CONVERT(DATETIME, @day + ' 18:59:59')) 
								OR (tio.time_out BETWEEN CONVERT(DATETIME, @day_tomorrow + ' 06:00:00') AND CONVERT(DATETIME, @day_tomorrow + ' 06:59:59')) THEN 1 END)
				) > 0 
				THEN
				(
					CAST((
						(COUNT(CASE WHEN (tio.time_out BETWEEN CONVERT(DATETIME, @day + ' 15:00:00') AND CONVERT(DATETIME, @day + ' 15:59:59')) 
									OR (tio.time_out BETWEEN CONVERT(DATETIME, @day_tomorrow + ' 03:00:00') AND CONVERT(DATETIME, @day_tomorrow + ' 03:59:59')) 
									OR (tio.time_out IS NULL AND tio.day = @day) THEN 1 END) * 0) 
									+
						(COUNT(CASE WHEN (tio.time_out BETWEEN CONVERT(DATETIME, @day + ' 16:00:00') AND CONVERT(DATETIME, @day + ' 16:59:59')) 
									OR (tio.time_out BETWEEN CONVERT(DATETIME, @day_tomorrow + ' 04:00:00') AND CONVERT(DATETIME, @day_tomorrow + ' 04:59:59')) THEN 1 END) * 1) 
									+
						(COUNT(CASE WHEN (tio.time_out BETWEEN CONVERT(DATETIME, @day + ' 17:00:00') AND CONVERT(DATETIME, @day + ' 17:59:59')) 
									OR (tio.time_out BETWEEN CONVERT(DATETIME, @day_tomorrow + ' 05:00:00') AND CONVERT(DATETIME, @day_tomorrow + ' 05:59:59')) THEN 1 END) * 2) 
									+
						(COUNT(CASE WHEN (tio.time_out BETWEEN CONVERT(DATETIME, @day + ' 18:00:00') AND CONVERT(DATETIME, @day + ' 18:59:59')) 
									OR (tio.time_out BETWEEN CONVERT(DATETIME, @day_tomorrow + ' 06:00:00') AND CONVERT(DATETIME, @day_tomorrow + ' 06:59:59')) THEN 1 END) * 3) 
					) AS FLOAT)
					/ 
					CAST((
						COUNT(CASE WHEN (tio.time_out BETWEEN CONVERT(DATETIME, @day + ' 15:00:00') AND CONVERT(DATETIME, @day + ' 15:59:59')) 
								OR (tio.time_out BETWEEN CONVERT(DATETIME, @day_tomorrow + ' 03:00:00') AND CONVERT(DATETIME, @day_tomorrow + ' 03:59:59')) 
								OR (tio.time_out IS NULL AND tio.day = @day) THEN 1 END) +
						COUNT(CASE WHEN (tio.time_out BETWEEN CONVERT(DATETIME, @day + ' 16:00:00') AND CONVERT(DATETIME, @day + ' 16:59:59')) 
									OR (tio.time_out BETWEEN CONVERT(DATETIME, @day_tomorrow + ' 04:00:00') AND CONVERT(DATETIME, @day_tomorrow + ' 04:59:59')) THEN 1 END) +
						COUNT(CASE WHEN (tio.time_out BETWEEN CONVERT(DATETIME, @day + ' 17:00:00') AND CONVERT(DATETIME, @day + ' 17:59:59')) 
									OR (tio.time_out BETWEEN CONVERT(DATETIME, @day_tomorrow + ' 05:00:00') AND CONVERT(DATETIME, @day_tomorrow + ' 05:59:59')) THEN 1 END) +
						COUNT(CASE WHEN (tio.time_out BETWEEN CONVERT(DATETIME, @day + ' 18:00:00') AND CONVERT(DATETIME, @day + ' 18:59:59')) 
									OR (tio.time_out BETWEEN CONVERT(DATETIME, @day_tomorrow + ' 06:00:00') AND CONVERT(DATETIME, @day_tomorrow + ' 06:59:59')) THEN 1 END)
					) AS FLOAT)
				) ELSE 0 END, 'N2') AS average_ot,
				0 AS table_order
			FROM 
				m_employees emp
			LEFT JOIN 
				t_time_in_out tio ON tio.emp_no = emp.emp_no AND tio.day = @day
			GROUP BY 
				emp.dept, emp.section
			)

			SELECT * FROM AttendanceData

			UNION ALL

			SELECT 
				'Total' AS dept, 
				NULL AS section,
				SUM(total_0) AS total_0,
				SUM(total_1) AS total_1,
				SUM(total_2) AS total_2,
				SUM(total_3) AS total_3,
				SUM(total) AS total,
				SUM(total_times) AS total_times,
				FORMAT(CASE 
					WHEN SUM(total) > 0 THEN 
						CAST(((SUM(total_0) * 0) + (SUM(total_1) * 1) + (SUM(total_2) * 2) + (SUM(total_3) * 3)) AS FLOAT) / CAST(SUM(total) AS FLOAT)
					ELSE 0 
				END, 'N2') AS average_ot,
				1 AS table_order
			FROM
				AttendanceData
			ORDER BY 
				table_order ASC, dept ASC";
				
	$params[] = $day;

	$stmt = $conn->prepare($sql);
	$stmt->execute($params);
	
	while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
		$tr_class = "";
		$c_label = "";
		$total_class = "";
		if ($row['dept'] != 'Total') {
			$c++;
			$c_label = $c;
		} else {
			$tr_class = " class='bg-black'";
			$total_class = " class='text-bold'";
		}

		echo '<tr'.$tr_class.'>';

		echo '<td>'.$c_label.'</td>';
		echo '<td'.$total_class.'>'.$row['dept'].'</td>';
		echo '<td>'.$row['section'].'</td>';
		echo '<td>Manpower</td>';
		echo '<td'.$total_class.'>'.$row['total_0'].'</td>';
		echo '<td>0</td>';
		echo '<td'.$total_class.'>'.$row['total_1'].'</td>';
		echo '<td>0</td>';
		echo '<td'.$total_class.'>'.$row['total_2'].'</td>';
		echo '<td'.$total_class.'>'.$row['total_3'].'</td>';
		echo '<td'.$total_class.'>'.$row['total'].'</td>';
		echo '<td'.$total_class.'>'.$row['average_ot'].'</td>';
		
		echo '</tr>';
	} 
}

$conn = NULL;

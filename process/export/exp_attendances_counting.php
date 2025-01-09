<?php
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

require('../conn.php');

function count_attendance_list($search_arr, $conn) {
	$sql = "SELECT COUNT(emp_no) AS total 
			FROM m_employees
			WHERE dept != ''";

	$params = [];

	if (!empty($search_arr['shift_group'])) {
		$sql = $sql . " AND shift_group = ?";
		$params[] = $search_arr['shift_group'];
	} else {
		$sql = $sql . " AND (shift_group = '' OR shift_group IS NULL)";
	}
	if (!empty($search_arr['dept'])) {
		$sql = $sql . " AND dept LIKE ?";
		$dept_search = $search_arr['dept'] . "%";
		$params[] = $dept_search;
	}
	if (!empty($search_arr['section'])) {
		$sql = $sql . " AND section LIKE ?";
		$section_search = $search_arr['section'] . "%";
		$params[] = $section_search;
	}
	if (!empty($search_arr['line_no'])) {
		$sql = $sql . " AND line_no LIKE ?";
		$line_no_search = $search_arr['line_no'] . "%";
		$params[] = $line_no_search;
	}

	$sql = $sql . " AND (resigned_date IS NULL OR resigned_date >= ?)";

	$params[] = $search_arr['day'];
	
	$stmt = $conn->prepare($sql);
	$stmt->execute($params);

	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

	if (count($results) > 0) {
		foreach ($results as $row) {
			$total = intval($row['total']);
		}
	}else{
		$total = 0;
	}

	return $total;
}

function count_emp_tio($search_arr, $conn) {
	$sql = "SELECT COUNT(emp.emp_no) AS total FROM m_employees emp
			LEFT JOIN t_time_in_out tio ON tio.emp_no = emp.emp_no
			WHERE tio.day = ?";
	
	$params = [];

	$params[] = $search_arr['day'];
	
	if (!empty($search_arr['shift_group'])) {
		$sql = $sql . " AND emp.shift_group = ?";
		$params[] = $search_arr['shift_group'];
	} else {
		$sql = $sql . " AND (emp.shift_group = '' OR emp.shift_group IS NULL)";
	}
	if (!empty($search_arr['dept'])) {
		$sql = $sql . " AND emp.dept LIKE ?";
		$dept_search = $search_arr['dept'] . "%";
		$params[] = $dept_search;
	} else {
		$sql = $sql . " AND emp.dept != ''";
	}
	if (!empty($search_arr['section'])) {
		$sql = $sql . " AND emp.section LIKE ?";
		$section_search = $search_arr['section'] . "%";
		$params[] = $section_search;
	}
	if (!empty($search_arr['line_no'])) {
		$sql = $sql . " AND emp.line_no LIKE ?";
		$line_no_search = $search_arr['line_no'] . "%";
		$params[] = $line_no_search;
	}

	$sql = $sql . " AND (emp.resigned_date IS NULL OR emp.resigned_date >= ?)";

	$params[] = $search_arr['day'];

	$stmt = $conn->prepare($sql);
	$stmt->execute($params);

	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

	if (count($results) > 0) {
		foreach ($results as $row) {
			$total = intval($row['total']);
		}
	}else{
		$total = 0;
	}

	return $total;
}

switch (true) {
    case !isset($_GET['day']):
    case !isset($_GET['shift_group']):
    case !isset($_GET['dept']):
        echo 'Query Parameters Not Set';
        exit();
}

$day = $_GET['day'];
$shift_group = $_GET['shift_group'];

if (!empty($_SESSION['emp_no_hr'])) {
	if (!empty($_GET['dept'])) {
		$dept = $_GET['dept'];
	} else {
		$dept = '';
	}
	if (!empty($_GET['section'])) {
		$section = $_GET['section'];
	} else {
		$section = '';
	}
	if (!empty($_GET['line_no'])) {
		$line_no = $_GET['line_no'];
	} else {
		$line_no = '';
	}
} else {
	if (!empty($_GET['dept'])) {
		$dept = $_GET['dept'];
	} else {
		$dept = '';
	}
	if (!empty($_SESSION['section'])) {
		$section = $_SESSION['section'];
	} else if (isset($_GET['section']) && !empty($_GET['section'])) {
		$section = $_GET['section'];
	} else {
		$section = '';
	}
	if (!empty($_SESSION['line_no'])) {
		$line_no = $_SESSION['line_no'];
	} else if (isset($_GET['line_no']) && !empty($_GET['line_no'])) {
		$line_no = $_GET['line_no'];
	} else {
		$line_no = '';
	}
}

$search_arr = array(
    "day" => $day,
    "shift_group" => $shift_group,
    "dept" => $dept,
	"section" => $section,
    "line_no" => $line_no
);

$total_mp = count_attendance_list($search_arr, $conn);
$total_present_mp = count_emp_tio($search_arr, $conn);
$total_absent_mp = $total_mp - $total_present_mp;

$c = 0;

$delimiter = ","; 

$filename = "EmpMgtSys_AttendanceCounting_";

if (!empty($dept)) {
	$filename = $filename . $dept . "-";
}
if (!empty($section)) {
	$filename = $filename . $section . "-";
}
if (!empty($line_no)) {
	$filename = $filename . $line_no;
}

$filename = $filename . "_" . $day;

if (!empty($shift_group)) {
	$filename = $filename . "-" . $shift_group;
}

$filename = $filename . ".csv";
 
// Create a file pointer 
$f = fopen('php://memory', 'w'); 

// UTF-8 BOM for special character compatibility
fputs($f, "\xEF\xBB\xBF");
 
// Set column headers 
$fields = array('#', 'Process', 'Present', 'Absent', 'Total MP'); 
fputcsv($f, $fields, $delimiter); 

$results = array();

//MS SQL Server
$sql = "SELECT 
			ISNULL(emp.process, 'No Process') AS process, 
			COUNT(emp.emp_no) AS total, 
			COUNT(tio.emp_no) AS total_present, 
			COUNT(emp.emp_no) - COUNT(tio.emp_no) AS total_absent 
		FROM 
			m_employees emp 
		LEFT JOIN 
			t_time_in_out tio ON emp.emp_no = tio.emp_no AND tio.day = ? 
		WHERE 
			emp.dept != ''";

$params = [];

$params[] = $day;

if (!empty($shift_group)) {
	$sql = $sql . " AND emp.shift_group = ?";
	$params[] = $shift_group;
} else {
	$sql = $sql . " AND (emp.shift_group = '' OR emp.shift_group IS NULL)";
}
if (!empty($dept)) {
	$sql = $sql . " AND emp.dept LIKE ?";
	$dept_search = $dept . "%";
	$params[] = $dept_search;
}
if (!empty($section)) {
	$sql = $sql . " AND emp.section LIKE ?";
	$section_search = $section . "%";
	$params[] = $section_search;
}
if (!empty($line_no)) {
	$sql = $sql . " AND emp.line_no LIKE ?";
	$line_no_search = $line_no . "%";
	$params[] = $line_no_search;
}

$sql = $sql . " AND 
					(emp.resigned_date IS NULL OR emp.resigned_date >= ?) 
				GROUP BY 
					emp.process";

$params[] = $day;

$stmt = $conn->prepare($sql);
$stmt->execute($params);

// Output each row of the data, format line as csv and write to file pointer 
while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
	$c++;
        
	$lineData = array($c, $row['process'], $row['total_present'], $row['total_absent'], $row['total']); 
	fputcsv($f, $lineData, $delimiter);
}

$lineData = array("Total MP :", "", $total_present_mp, $total_absent_mp, $total_mp); 
fputcsv($f, $lineData, $delimiter);

// Move back to beginning of file 
fseek($f, 0); 
 
// Set headers to download file rather than displayed 
header('Content-Type: text/csv'); 
header('Content-Disposition: attachment; filename="' . $filename . '";'); 
 
//output all remaining data on a file pointer 
fpassthru($f); 

$conn = null;

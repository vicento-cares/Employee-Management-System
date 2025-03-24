<?php
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

require '../../conn.php';

$method = $_POST['method'];

if ($method == 'individual_attendance_list') {
    $day_from = $_POST['day_from'];
    $day_to = $_POST['day_to'];
    $page = $_POST['page'];
    $emp_no = '';

    if ($page == 'hr') {
        $emp_no = $_POST['emp_no'];
    } else if ($page == 'user') {
        $emp_no = $_SESSION['emp_no_user'];
    }

    $c = 0;
    $row_class_arr = array('', 'bg-success', 'bg-danger');
	$row_class = $row_class_arr[0];

    $query = "
                DECLARE @StartDate DATE = ?;
                DECLARE @EndDate DATE = ?;
                DECLARE @EmpNo NVARCHAR(255) = ?;

                WITH Dates AS (
                    SELECT @StartDate AS day
                    UNION ALL
                    SELECT DATEADD(day, 1, day)
                    FROM Dates
                    WHERE day < @EndDate
                )
                SELECT 
                    FORMAT(c.day, 'dddd') AS day_name,
                    c.day,
                    t.shift, 
                    t.emp_no, 
                    CONVERT(VARCHAR, t.date_updated, 120) AS date_updated,
                    CONVERT(VARCHAR, t.time_in, 120) AS time_in,
                    CONVERT(VARCHAR, t.time_out, 120) AS time_out,
                    t.ip
                FROM 
                    Dates c
                LEFT JOIN t_time_in_out t ON CONVERT(date, c.day) = CONVERT(date, t.day) AND t.emp_no = @EmpNo
                WHERE 
                    t.emp_no = @EmpNo OR t.emp_no IS NULL
                OPTION (MAXRECURSION 0)
            ";

    $params = [];

    if (!empty($day_from) && !empty($day_to)) {
        $params[] = $day_from;
        $params[] = $day_to;
    }

    if (!empty($emp_no)) {
        $params[] = $emp_no;
    }

    $stmt = $conn->prepare($query);

    $stmt->execute($params);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) > 0) {
        foreach ($results as $row) {
            $c++;

            if (!empty($row['date_updated'])) {
				$row_class = $row_class_arr[1];
			} else {
				$row_class = $row_class_arr[2];
			}

            echo '<tr class="'.$row_class.'">';
            echo '<td>' . $c . '</td>';
            echo '<td>' . $row['day_name'] . '</td>';
            echo '<td>' . $row['day'] . '</td>';
            echo '<td>' . $row['shift'] . '</td>';
            echo '<td>' . $row['emp_no'] . '</td>';
            echo '<td>' . $row['date_updated'] . '</td>';
            echo '<td>' . $row['time_in'] . '</td>';
            echo '<td>' . $row['time_out'] . '</td>';
            echo '<td>' . $row['ip'] . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr>';
        echo '<td colspan="9" style="text-align:center; color:red;">No Result !!!</td>';
        echo '</tr>';
    }
}

$conn = NULL;

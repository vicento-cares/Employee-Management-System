<?php
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

include '../../conn.php';

$method = $_GET['method'];

if ($method == 'month_attendance_mon') {
    $year = '';
    if (isset($_GET['year'])) {
        $year = $_GET['year'];
    }

    $month = '';
    if (isset($_GET['month'])) {
        $month = $_GET['month'];
    }

    $sql = "DECLARE @Year INT = ?;
            DECLARE @Month INT = ?;

            DECLARE @StartDate DATE = DATEFROMPARTS(@Year, @Month, 1);
            DECLARE @EndDate DATE = EOMONTH(@StartDate);
            
            SELECT 
                STRING_AGG(QUOTENAME(FORMAT(d, 'dd-MMM-yy')), ',') AS cols,
                STRING_AGG('SUM(' + QUOTENAME(FORMAT(d, 'dd-MMM-yy')) + ') AS ' + QUOTENAME(FORMAT(d, 'dd-MMM-yy')), ',') AS sumcols,
                STRING_AGG('AVG(' + QUOTENAME(FORMAT(d, 'dd-MMM-yy')) + ') AS ' + QUOTENAME(FORMAT(d, 'dd-MMM-yy')), ',') AS avgcols
            FROM (
                SELECT DATEADD(DAY, number, @StartDate) AS d
                FROM master.dbo.spt_values
                WHERE type = 'P' AND number <= DATEDIFF(DAY, @StartDate, @EndDate)
            ) AS DateList;";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($sql);
	$stmt->execute($params);

    // Fetch the results
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $cols = $row['cols'];
    $sumcols = $row['sumcols'];
    $avgcols = $row['avgcols'];

    $sql = "DECLARE @Year INT = ?;
            DECLARE @Month INT = ?;

            WITH DateRange AS (
                SELECT 
                    DATEADD(DAY, number, DATEFROMPARTS(@Year, @Month, 1)) AS report_date
                FROM 
                    master.dbo.spt_values
                WHERE 
                    type = 'P' AND 
                    number < DAY(EOMONTH(DATEFROMPARTS(@Year, @Month, 1)))
            ),
            AttendanceData AS (
                SELECT 
                    FORMAT(dr.report_date, 'dd-MMM-yy') AS report_date_str,  -- Convert to string
                    emp.section AS Section,
                    COUNT(emp.emp_no) - COUNT(tio.emp_no) AS total_absent 
                FROM 
                    DateRange dr
                LEFT JOIN 
                    m_employees emp ON (emp.resigned_date IS NULL OR emp.resigned_date >= dr.report_date)
                LEFT JOIN 
                    t_time_in_out tio ON emp.emp_no = tio.emp_no AND tio.day = dr.report_date 
                WHERE 
                    emp.dept IN ('PD1', 'PD2', 'PD3', 'QA') AND 
                    emp.section NOT IN ('CQA', 'QC', 'QA', 'QM', 'QAE') 
                GROUP BY 
                    dr.report_date, emp.section
            )

            -- Absent Data Pivot using the string version of the date
            SELECT 
                *
            FROM 
                AttendanceData
            PIVOT (
                SUM(total_absent) 
                FOR report_date_str IN ($cols)
            ) AS PivotTable

            UNION ALL

            -- Total row
            SELECT 
                'Total' AS Section, $sumcols 
            FROM AttendanceData
            PIVOT (
                SUM(total_absent) 
                FOR report_date_str IN ($cols)
            ) AS TotalPivot;
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $absent_mon_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = "DECLARE @Year INT = ?;
            DECLARE @Month INT = ?;

            WITH DateRange AS (
                SELECT 
                    DATEADD(DAY, number, DATEFROMPARTS(@Year, @Month, 1)) AS report_date
                FROM 
                    master.dbo.spt_values
                WHERE 
                    type = 'P' AND 
                    number < DAY(EOMONTH(DATEFROMPARTS(@Year, @Month, 1)))
            ),
            AttendanceData AS (
                SELECT 
                    FORMAT(dr.report_date, 'dd-MMM-yy') AS report_date_str,  -- Convert to string
                    emp.section AS Section,
                    COUNT(tio.emp_no) AS total_present 
                FROM 
                    DateRange dr
                LEFT JOIN 
                    m_employees emp ON (emp.resigned_date IS NULL OR emp.resigned_date >= dr.report_date)
                LEFT JOIN 
                    t_time_in_out tio ON emp.emp_no = tio.emp_no AND tio.day = dr.report_date 
                WHERE 
                    emp.dept IN ('PD1', 'PD2', 'PD3', 'QA') AND 
                    emp.section NOT IN ('CQA', 'QC', 'QA', 'QM', 'QAE') 
                GROUP BY 
                    dr.report_date, emp.section
            )
             
            -- Present Data Pivot using the string version of the date
            SELECT 
                *
            FROM 
                AttendanceData
            PIVOT (
                SUM(total_present) 
                FOR report_date_str IN ($cols)
            ) AS PivotTable

            UNION ALL

            -- Total row
            SELECT 
                'Total' AS Section, $sumcols 
            FROM AttendanceData
            PIVOT (
                SUM(total_present) 
                FOR report_date_str IN ($cols)
            ) AS TotalPivot;
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $present_mon_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = "DECLARE @Year INT = ?;
            DECLARE @Month INT = ?;

            WITH DateRange AS (
                SELECT 
                    DATEADD(DAY, number, DATEFROMPARTS(@Year, @Month, 1)) AS report_date
                FROM 
                    master.dbo.spt_values
                WHERE 
                    type = 'P' AND 
                    number < DAY(EOMONTH(DATEFROMPARTS(@Year, @Month, 1)))
            ),
            AttendanceData AS (
                SELECT 
                    FORMAT(dr.report_date, 'dd-MMM-yy') AS report_date_str,  -- Convert to string
                    emp.section AS Section, 
                    CAST(
                        CASE 
                            WHEN COUNT(emp.emp_no) > 0 THEN ((COUNT(emp.emp_no) - COUNT(tio.emp_no)) * 100.0 / COUNT(emp.emp_no)) 
                            ELSE 0 
                        END AS DECIMAL(10, 2)
                    ) AS absent_rate 
                FROM 
                    DateRange dr
                LEFT JOIN 
                    m_employees emp ON (emp.resigned_date IS NULL OR emp.resigned_date >= dr.report_date)
                LEFT JOIN 
                    t_time_in_out tio ON emp.emp_no = tio.emp_no AND tio.day = dr.report_date 
                WHERE 
                    emp.dept IN ('PD1', 'PD2', 'PD3', 'QA') AND 
                    emp.section NOT IN ('CQA', 'QC', 'QA', 'QM', 'QAE') 
                GROUP BY 
                    dr.report_date, emp.section
            )
             
            -- Absent Rate Data Pivot using the string version of the date
            SELECT 
                *
            FROM 
                AttendanceData
            PIVOT (
                SUM(absent_rate) 
                FOR report_date_str IN ($cols)
            ) AS PivotTable

            UNION ALL

            -- Total row
            SELECT 
                'Total' AS Section, $avgcols 
            FROM AttendanceData
            PIVOT (
                SUM(absent_rate) 
                FOR report_date_str IN ($cols)
            ) AS TotalPivot;
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($sql);
	$stmt->execute($params);

    $absent_rate_mon_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $final_data = [
        'absent_mon_rows' => $absent_mon_rows,
        'present_mon_rows' => $present_mon_rows,
        'absent_rate_mon_rows' => $absent_rate_mon_rows
    ];

    // Output the data as JSON
    header('Content-Type: application/json');
    echo json_encode($final_data);
}

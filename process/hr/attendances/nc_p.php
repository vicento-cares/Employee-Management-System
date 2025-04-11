<?php
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

require '../../conn.php';

$method = $_POST['method'];

if ($method == 'get_non_compliance_year_dropdown_search') {
    $sql = "SELECT 
                DISTINCT YEAR(day) AS Year
            FROM 
                t_time_in_out
            ORDER BY 
                Year";

    $stmt = $conn->prepare($sql);

    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) > 0) {
        echo '<option selected value="">Select Year</option>';
        foreach ($results as $row) {
            echo '<option value="' . htmlspecialchars($row['Year']) . '">' . htmlspecialchars($row['Year']) . '</option>';
        }
    } else {
        echo '<option selected value="">Select Year</option>';
    }
}

if ($method == 'non_compliance_list') {
    $year = $_POST['year'];
    $month = $_POST['month'];

    $c = 0;

    $query = "
                -- Get Manpower Non-Compliance (No Time Out Only)
                DECLARE @Year INT = ?;  -- Specify the year
                DECLARE @Month INT = ?;    -- Specify the month

                WITH TimeRecords AS (
                    SELECT 
                        emp.emp_no, 
                        emp.full_name, 
                        emp.dept, 
                        emp.section, 
                        emp.line_no, 
                        emp.process, 
                        COUNT(CASE WHEN tio.time_out IS NULL THEN 1 END) AS NullTimeOutCount
                    FROM 
                        m_employees emp
                    LEFT JOIN
                        t_time_in_out tio ON emp.emp_no = tio.emp_no 
                        AND emp.resigned = 0 
                        AND MONTH(tio.day) = @Month 
                        AND YEAR(tio.day) = @Year 
                    WHERE
                        CAST(tio.day AS DATE) <> CAST(GETDATE() AS DATE)  -- Exclude today's date
                    GROUP BY 
                        emp.emp_no, 
                        emp.full_name, 
                        emp.dept, 
                        emp.section, 
                        emp.line_no, 
                        emp.process 
                )

                SELECT 
                    emp_no,
                    full_name, 
                    dept, 
                    section, 
                    line_no, 
                    process, 
                    NullTimeOutCount,
                    CASE 
                        WHEN NullTimeOutCount > 2 THEN 'Audit' 
                        ELSE 'Warning' 
                    END AS Status
                FROM 
                    TimeRecords
                WHERE 
                    NullTimeOutCount > 2  -- Filter to show only employees with null time records (0 to see warning)
                ORDER BY 
                    NullTimeOutCount DESC, 
                    emp_no ASC;
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($query);

    $stmt->execute($params);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $c++;

        echo '<tr style="cursor:pointer;" class="modal-trigger" 
                    data-toggle="modal" data-target="#non_compliance_details" 
                    onclick="get_non_compliance_details(&quot;' . 
                    $row['emp_no'] . '~!~' . 
                    $row['full_name'] . '~!~' . 
                    $row['dept'] . '~!~' . 
                    $row['section'] . '~!~' . 
                    $row['line_no'] . '~!~' . 
                    $row['process'] . '~!~' . 
                    $row['NullTimeOutCount'] . '&quot;)">';
        echo '<td>' . $c . '</td>';
        echo '<td>' . $row['emp_no'] . '</td>';
        echo '<td>' . $row['full_name'] . '</td>';
        echo '<td>' . $row['dept'] . '</td>';
        echo '<td>' . $row['section'] . '</td>';
        echo '<td>' . $row['line_no'] . '</td>';
        echo '<td>' . $row['process'] . '</td>';
        echo '<td>' . $row['NullTimeOutCount'] . '</td>';
        echo '</tr>';
    }
}

if ($method == 'get_emp_monthly_no_time_out_chart') {
    $year = $_POST['year'];
    $emp_no = $_POST['emp_no'];

    $categories = [];

    $sql = "
                DECLARE @Year INT = ?;  -- Specify the year
                DECLARE @EmpNo NVARCHAR(255) = ?;  -- Place your specific Employee Number here

                WITH DateRange AS (
                    SELECT 
                        FORMAT(DATEADD(MONTH, number, DATEFROMPARTS(@Year, 1, 1)), 'yyyy-MM') AS report_month
                    FROM 
                        master.dbo.spt_values
                    WHERE 
                        type = 'P' AND 
                        number < 12  -- Generate months from January (0) to December (11)
                ),
                EmployeeTimeOut AS (
                    SELECT 
                        emp.emp_no,
                        tio.day,
                        tio.shift,
                        tio.time_in,
                        tio.time_out,
                        DATEADD(MONTH, DATEDIFF(MONTH, 0, tio.day), 0) AS report_month  -- Get the first day of the month for the day
                    FROM  
                        m_employees emp  
                    LEFT JOIN 
                        t_time_in_out tio ON emp.emp_no = tio.emp_no 
                        AND emp.resigned = 0 
                        AND YEAR(tio.day) = @Year  -- Adjusted to include past years
                        AND CAST(tio.day AS DATE) <> CAST(GETDATE() AS DATE)  -- Exclude today's date
                    WHERE
                        emp.emp_no = @EmpNo  -- Filter by specific employee number
                        AND tio.time_out IS NULL  -- This condition checks for employees without time records
                )

                SELECT 
                    dr.report_month,
                    COUNT(et.emp_no) AS null_time_out_count  -- Count the number of null time outs per month
                FROM 
                    DateRange dr
                LEFT JOIN 
                    EmployeeTimeOut et ON dr.report_month = FORMAT(et.report_month, 'yyyy-MM')
                GROUP BY 
                    dr.report_month
                ORDER BY 
                    dr.report_month;
            ";

    $params = [];

    $params[] = $year;
    $params[] = $emp_no;

    $stmt = $conn->prepare($sql);

    $stmt->execute($params);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Add unique report_month to categories
        if (!in_array($row['report_month'], $categories)) {
            $categories[] = $row['report_month'];
        }

        $data['TotalNoTimeOut'][] = (int)$row['null_time_out_count'];
    }

    // Create the final data structure
    $finalData = [
        'categories' => $categories,
        'data' => [
            [
                'name' => 'Total No Time Out Count',
                'data' => $data['TotalNoTimeOut']
            ]
        ]
    ];

    // Encode the categories and data as JSON
    echo json_encode($finalData);
}

if ($method == 'non_compliance_details_list') {
    $year = $_POST['year'];
    $month = $_POST['month'];
    $emp_no = $_POST['emp_no'];

    $c = 0;

    $query = "
                DECLARE @Year INT = ?;  -- Specify the year
                DECLARE @Month INT = ?;    -- Specify the month

                SELECT 
                    emp.emp_no,
                    tio.emp_no,
                    tio.day,
                    tio.shift,
                    CONVERT(VARCHAR, tio.date_updated, 120) AS date_updated,
                    CONVERT(VARCHAR, tio.time_in, 120) AS time_in,
                    CONVERT(VARCHAR, tio.time_out, 120) AS time_out
                FROM 
                    m_employees emp
                LEFT JOIN
                    t_time_in_out tio ON emp.emp_no = tio.emp_no 
                    AND emp.resigned = 0 
                    AND MONTH(tio.day) = @Month 
                    AND YEAR(tio.day) = @Year
                WHERE
                    tio.emp_no = ?  -- Specific Employee Number Here
                    AND tio.emp_no IS NOT NULL 
                    AND tio.time_out IS NULL  -- This condition checks for employees without time records
                    AND CAST(tio.day AS DATE) <> CAST(GETDATE() AS DATE);  -- Exclude today's date
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;
    $params[] = $emp_no;

    $stmt = $conn->prepare($query);

    $stmt->execute($params);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $c++;

        echo '<tr>';
        echo '<td>' . $c . '</td>';
        echo '<td>' . $row['emp_no'] . '</td>';
        echo '<td>' . $row['day'] . '</td>';
        echo '<td>' . $row['shift'] . '</td>';
        echo '<td>' . $row['date_updated'] . '</td>';
        echo '<td>' . $row['time_in'] . '</td>';
        echo '<td>' . $row['time_out'] . '</td>';
        echo '</tr>';
    }
}

if ($method == 'past_no_time_out_record_list') {
    $month = $_POST['month'];
    $emp_no = $_POST['emp_no'];

    $c = 0;

    $query = "
                DECLARE @Month INT = ?;    -- Specify the month

                SELECT 
                    emp.emp_no,
                    tio.emp_no,
                    tio.day,
                    tio.shift,
                    CONVERT(VARCHAR, tio.date_updated, 120) AS date_updated,
                    CONVERT(VARCHAR, tio.time_in, 120) AS time_in,
                    CONVERT(VARCHAR, tio.time_out, 120) AS time_out
                FROM 
                    m_employees emp
                LEFT JOIN
                    t_time_in_out tio ON emp.emp_no = tio.emp_no 
                    AND emp.resigned = 0 
                    AND (MONTH(tio.day) < @Month OR YEAR(tio.day) < YEAR(GETDATE()))  -- Adjusted to include past years
                WHERE
                    tio.emp_no = ?  -- Place your specific Employee Number here
                    AND tio.emp_no IS NOT NULL 
                    AND tio.time_out IS NULL  -- This condition checks for employees without time records
                ORDER BY
	                tio.day DESC;
            ";

    $params = [];

    $params[] = $month;
    $params[] = $emp_no;

    $stmt = $conn->prepare($query);

    $stmt->execute($params);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $c++;

        echo '<tr>';
        echo '<td>' . $c . '</td>';
        echo '<td>' . $row['emp_no'] . '</td>';
        echo '<td>' . $row['day'] . '</td>';
        echo '<td>' . $row['shift'] . '</td>';
        echo '<td>' . $row['date_updated'] . '</td>';
        echo '<td>' . $row['time_in'] . '</td>';
        echo '<td>' . $row['time_out'] . '</td>';
        echo '</tr>';
    }
}

$conn = NULL;

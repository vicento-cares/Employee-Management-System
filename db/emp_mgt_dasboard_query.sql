-- Get Manpower Non-Compliance (No Time Out Only)
DECLARE @Year INT = 2025;  -- Specify the year
DECLARE @Month INT = 4;    -- Specify the month

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


-- Get Manpower Non-Compliance (Both No Time In or No Time Out)
DECLARE @Year INT = 2025;  -- Specify the year
DECLARE @Month INT = 4;    -- Specify the month

WITH TimeRecords AS (
    SELECT 
        emp.emp_no, 
		emp.full_name, 
        emp.dept, 
		emp.section, 
		emp.line_no, 
		emp.process, 
        COUNT(CASE WHEN tio.time_in IS NULL THEN 1 END) AS NullTimeInCount,
        COUNT(CASE WHEN tio.time_out IS NULL THEN 1 END) AS NullTimeOutCount,
        COUNT(CASE WHEN tio.time_in IS NULL THEN 1 END) + COUNT(CASE WHEN tio.time_out IS NULL THEN 1 END) AS TotalNullCount
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
    NullTimeInCount,
    NullTimeOutCount,
    TotalNullCount,
    CASE 
        WHEN TotalNullCount > 2 THEN 'Audit' 
        ELSE 'Warning' 
    END AS Status
FROM 
    TimeRecords
WHERE 
    TotalNullCount > 2  -- Filter to show only employees with null time records (0 to see warning)
ORDER BY 
	TotalNullCount DESC, 
    emp_no ASC;


-- Get Manpower Non-Compliance on Specific Employee Time In Out Records (Both No Time In or No Time Out)
DECLARE @Year INT = 2025;  -- Specify the year
DECLARE @Month INT = 4;    -- Specify the month

;WITH EmployeeTimeRecords AS (
    SELECT 
        emp.emp_no,
        tio.emp_no AS time_emp_no,
        tio.day,
        tio.shift,
        tio.time_in,
        tio.time_out
    FROM 
        m_employees emp
    LEFT JOIN
        t_time_in_out tio ON emp.emp_no = tio.emp_no 
        AND emp.resigned = 0 
        AND MONTH(tio.day) = @Month 
        AND YEAR(tio.day) = @Year 
    WHERE
		CAST(tio.day AS DATE) <> CAST(GETDATE() AS DATE)  -- Exclude today's date
)

SELECT 
    emp_no,
    time_emp_no,
    day,
    shift,
    time_in,
    time_out
FROM 
    EmployeeTimeRecords
WHERE
    time_emp_no = ''  -- Specific Employee Number Here
    AND time_emp_no IS NOT NULL 
    AND time_out IS NULL  -- This condition checks for employees without time records

UNION ALL

SELECT 
    emp_no,
    time_emp_no,
    day,
    shift,
    time_in,
    time_out
FROM 
    EmployeeTimeRecords
WHERE
    emp_no = ''  -- Specific Employee Number Here
    AND time_emp_no IS NULL;  -- This condition checks for employees without time records

-- Get Manpower Non-Compliance on Specific Employee Time In Out Records (No Time Out Only)
DECLARE @Year INT = 2025;  -- Specify the year
DECLARE @Month INT = 4;    -- Specify the month

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
    tio.emp_no = ''  -- Specific Employee Number Here
    AND tio.emp_no IS NOT NULL 
    AND tio.time_out IS NULL  -- This condition checks for employees without time records
    AND CAST(tio.day AS DATE) <> CAST(GETDATE() AS DATE)  -- Exclude today's date
ORDER BY
	tio.day DESC;

-- Get Manpower Non-Compliance on Specific Employee Time In Out Records (Past No Time Out Only)
DECLARE @Year INT = 2024;  -- Specify the year
DECLARE @Month INT = 12;    -- Specify the month

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
WHERE
    tio.emp_no = ''  -- Place your specific Employee Number here
    AND tio.emp_no IS NOT NULL 
    AND tio.time_out IS NULL  -- This condition checks for employees without time records
    AND (
        YEAR(tio.day) < @Year OR 
        (YEAR(tio.day) = @Year AND MONTH(tio.day) < @Month)
    )  -- Filter for records before the specified year and month
    AND CAST(tio.day AS DATE) <> CAST(GETDATE() AS DATE)  -- Exclude today's date
ORDER BY
	tio.day DESC;


-- Get Manpower Non-Compliance Count by Line No. (No Time Out Only)
DECLARE @Year INT = 2025;  -- Specify the year
DECLARE @Month INT = 4;    -- Specify the month

WITH TimeRecords AS (
    SELECT 
        emp.emp_no,
        emp.line_no,
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
        emp.emp_no, emp.line_no 
)

SELECT 
    line_no,
    COUNT(CASE 
        WHEN NullTimeOutCount > 2 THEN 1 
    END) AS AuditCount,
    COUNT(CASE 
        WHEN NullTimeOutCount <= 2 AND NullTimeOutCount > 0 THEN 1 
    END) AS WarningCount
FROM 
    TimeRecords
WHERE 
    NullTimeOutCount > 2  -- Filter to show only employees with null time records (0 to see warning)
GROUP BY 
    line_no
ORDER BY 
    AuditCount DESC, line_no ASC;


-- Get Manpower Non-Compliance Count by TOP 10 Line No. (No Time Out Only)
DECLARE @Year INT = 2025;  -- Specify the year
DECLARE @Month INT = 4;    -- Specify the month

WITH TimeRecords AS (
    SELECT 
        emp.emp_no,
        emp.line_no,
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
        emp.emp_no, emp.line_no 
)

SELECT TOP 10
    line_no,
    COUNT(CASE 
        WHEN NullTimeOutCount > 2 THEN 1 
    END) AS AuditCount,
    COUNT(CASE 
        WHEN NullTimeOutCount <= 2 AND NullTimeOutCount > 0 THEN 1 
    END) AS WarningCount
FROM 
    TimeRecords
WHERE 
    NullTimeOutCount > 2  -- Filter to show only employees with null time records (0 to see warning)
GROUP BY 
    line_no
ORDER BY 
    AuditCount DESC, line_no ASC;


-- Get Manpower Non-Compliance Count by TOP 10 Process (No Time Out Only)
DECLARE @Year INT = 2025;  -- Specify the year
DECLARE @Month INT = 4;    -- Specify the month

WITH TimeRecords AS (
    SELECT 
        emp.emp_no,
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
        emp.emp_no, emp.process 
)

SELECT TOP 10 
    process,
    COUNT(CASE 
        WHEN NullTimeOutCount > 2 THEN 1 
    END) AS AuditCount,
    COUNT(CASE 
        WHEN NullTimeOutCount <= 2 AND NullTimeOutCount > 0 THEN 1 
    END) AS WarningCount
FROM 
    TimeRecords
WHERE 
    NullTimeOutCount > 2  -- Filter to show only employees with null time records (0 to see warning)
GROUP BY 
    process
ORDER BY 
    AuditCount DESC, process ASC;


-- Get Manpower Non-Compliance Count by TOP 10 Section (No Time Out Only)
DECLARE @Year INT = 2025;  -- Specify the year
DECLARE @Month INT = 4;    -- Specify the month

WITH TimeRecords AS (
    SELECT 
        emp.emp_no,
        emp.section,
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
        emp.emp_no, emp.section 
)

SELECT TOP 10 
    section,
    COUNT(CASE 
        WHEN NullTimeOutCount > 2 THEN 1 
    END) AS AuditCount,
    COUNT(CASE 
        WHEN NullTimeOutCount <= 2 AND NullTimeOutCount > 0 THEN 1 
    END) AS WarningCount
FROM 
    TimeRecords
WHERE 
    NullTimeOutCount > 2  -- Filter to show only employees with null time records (0 to see warning)
GROUP BY 
    section
ORDER BY 
    AuditCount DESC, section ASC;


-- Get Month Manpower Count by Section (No Time Out Only)
DECLARE @Year INT = YEAR(GETDATE());  -- Get the current year
DECLARE @Month INT = MONTH(GETDATE()); -- Get the current month

WITH DateRange AS (
    SELECT 
        DATEADD(DAY, number, DATEFROMPARTS(@Year, @Month, 1)) AS report_date
    FROM 
        master.dbo.spt_values
    WHERE 
        type = 'P' AND 
        number < DAY(EOMONTH(DATEFROMPARTS(@Year, @Month, 1)))  -- Generate dates for the month
),
EmployeeTimeInOut AS (
    SELECT 
        emp.emp_no,
        emp.section,
        CAST(tio.day AS DATE) AS report_date
    FROM 
        m_employees emp
    LEFT JOIN
        t_time_in_out tio ON emp.emp_no = tio.emp_no 
    WHERE
        emp.resigned = 0 AND 
        (tio.day >= DATEADD(HOUR, 6, CAST(DATEFROMPARTS(@Year, @Month, 1) AS DATETIME2)) AND 
        tio.day < DATEADD(HOUR, 6, DATEADD(DAY, 1, CAST(EOMONTH(DATEFROMPARTS(@Year, @Month, 1)) AS DATETIME2)))) 
        AND CAST(tio.day AS DATE) <> CAST(GETDATE() AS DATE)  -- Exclude today's date
        AND tio.time_out IS NULL
)

SELECT 
    dr.report_date,
    eio.section,
    COUNT(eio.emp_no) AS emp_no_count
FROM 
    DateRange dr
LEFT JOIN
    EmployeeTimeInOut eio ON dr.report_date = eio.report_date  -- Join on the report_date
GROUP BY 
    dr.report_date, eio.section
ORDER BY 
    dr.report_date ASC, eio.section ASC;


-- Get Manpower Non-Compliance Count by Dept, Section, Line No & Process (No Time Out Only)
DECLARE @Year INT = 2025;  -- Specify the year
DECLARE @Month INT = 4;    -- Specify the month

WITH TimeRecords AS (
    SELECT 
        emp.emp_no,
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
        emp.dept,
        emp.section,
        emp.line_no,
        emp.process
)

SELECT 
    dept,
    section,
    line_no,
    process,
    COUNT(CASE 
        WHEN NullTimeOutCount > 2 THEN 1 
    END) AS AuditCount,
    COUNT(CASE 
        WHEN NullTimeOutCount <= 2 AND NullTimeOutCount > 0 THEN 1 
    END) AS WarningCount
FROM 
    TimeRecords
WHERE 
    NullTimeOutCount > 2  -- Filter to show only employees with null time records (0 to see warning)
GROUP BY 
    dept,          -- Include dept in GROUP BY
    section,       -- Include section in GROUP BY
    line_no,       -- Include line_no in GROUP BY
    process        -- Include process in GROUP BY
ORDER BY 
    dept ASC,
    section ASC,
    line_no ASC,
    process ASC;




DECLARE @Year INT = YEAR(GETDATE());  -- Get the current year
DECLARE @Month INT = MONTH(GETDATE()); -- Get the current month

-- Step 2: Create a dynamic SQL for pivoting
DECLARE @cols NVARCHAR(MAX);
DECLARE @query NVARCHAR(MAX);

-- Step 1: Generate the list of dates for the current month and prepare the column names
SELECT @cols = STRING_AGG(QUOTENAME(CONVERT(VARCHAR, DATEADD(DAY, number, DATEFROMPARTS(@Year, @Month, 1)), 23)), ', ')
FROM master.dbo.spt_values
WHERE type = 'P' AND 
      number < DAY(EOMONTH(DATEFROMPARTS(@Year, @Month, 1)));  -- Generate dates for the month

-- Step 4: Create the dynamic SQL query
SET @query = '
SELECT *
FROM (
    SELECT 
        DATEADD(DAY, number, DATEFROMPARTS(' + CAST(@Year AS VARCHAR) + ', ' + CAST(@Month AS VARCHAR) + ', 1)) AS report_date,
        1 AS Value  -- You can replace this with any value you want to display
    FROM 
        master.dbo.spt_values
    WHERE 
        type = ''P'' AND 
        number < DAY(EOMONTH(DATEFROMPARTS(' + CAST(@Year AS VARCHAR) + ', ' + CAST(@Month AS VARCHAR) + ', 1)))
) AS SourceTable
PIVOT (
    MAX(Value)
    FOR report_date IN (' + @cols + ')
) AS PivotTable;';

-- Step 5: Execute the dynamic SQL
EXEC sp_executesql @query;


-- Define the start and end dates
DECLARE @Year INT = 2025;  -- Get the current year
DECLARE @Month INT = 4; -- Get the current month

WITH DateRange AS (
    SELECT 
        DATEADD(DAY, number, DATEFROMPARTS(@Year, @Month, 1)) AS report_date
    FROM 
        master.dbo.spt_values
    WHERE 
        type = 'P' AND 
        number < DAY(EOMONTH(DATEFROMPARTS(@Year, @Month, 1)))  -- Generate dates for the month
)

SELECT 
    COUNT(emp.emp_no) AS total, 
    COUNT(CASE WHEN tio.shift = 'DS' THEN 1 END) AS total_present_ds, 
    COUNT(CASE WHEN tio.shift = 'NS' THEN 1 END) AS total_present_ns, 
    COUNT(tio.emp_no) AS total_present, 
    COUNT(emp.emp_no) - COUNT(tio.emp_no) AS total_absent, 
    FORMAT(CASE 
        WHEN COUNT(emp.emp_no) > 0 THEN (COUNT(tio.emp_no) * 100.0 / COUNT(emp.emp_no)) 
        ELSE 0 
    END, 'N2') AS attendance_percentage,
    FORMAT(CASE 
        WHEN COUNT(emp.emp_no) > 0 THEN ((COUNT(emp.emp_no) - COUNT(tio.emp_no)) * 100.0 / COUNT(emp.emp_no)) 
        ELSE 0 
    END, 'N2') AS absent_rate,
    dr.report_date AS day
FROM 
    DateRange dr
LEFT JOIN 
    m_employees emp ON (emp.resigned_date IS NULL OR emp.resigned_date >= dr.report_date)
LEFT JOIN 
    t_time_in_out tio ON emp.emp_no = tio.emp_no AND tio.day = dr.report_date 
WHERE 
    emp.line_no = '5101' 
GROUP BY 
    dr.report_date 
ORDER BY 
	dr.report_date 
OPTION (MAXRECURSION 0);  -- Allow recursion to go beyond the default limit if needed

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
    AND (MONTH(tio.day) < @Month OR YEAR(tio.day) < YEAR(GETDATE()))  -- Adjusted to include past years
WHERE
    tio.emp_no = ''  -- Place your specific Employee Number here
    AND tio.emp_no IS NOT NULL 
    AND tio.time_out IS NULL;  -- This condition checks for employees without time records


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
    line_no ASC;


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

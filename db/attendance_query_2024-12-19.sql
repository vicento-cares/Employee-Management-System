SELECT 
	emp.shift_group, 
	emp.dept, 
	emp.section, 
	ISNULL(emp.line_no, 'No Line') AS line_no1, 
	COUNT(emp.emp_no) AS total, 
	COUNT(tio.emp_no) AS total_present, 
	COUNT(emp.emp_no) - COUNT(tio.emp_no) AS total_absent, 
	FORMAT(CASE 
        WHEN COUNT(emp.emp_no) > 0 THEN (COUNT(tio.emp_no) * 100.0 / COUNT(emp.emp_no)) 
        ELSE 0 
    END, 'N2') AS attendance_percentage
FROM 
	m_employees emp 
LEFT JOIN 
	t_time_in_out tio ON emp.emp_no = tio.emp_no AND tio.day = '2024-12-11' 
WHERE 
	(emp.resigned_date IS NULL OR emp.resigned_date >= '2024-12-11') 
GROUP BY 
	emp.dept, emp.section, emp.line_no, emp.shift_group
ORDER BY 
	emp.shift_group;

SELECT 
    ISNULL(emp.process, 'No Process') AS process, 
    COUNT(emp.emp_no) AS total, 
    COUNT(tio.emp_no) AS total_present, 
	COUNT(emp.emp_no) - COUNT(tio.emp_no) AS total_absent 
FROM 
    m_employees emp 
LEFT JOIN 
    t_time_in_out tio ON emp.emp_no = tio.emp_no AND tio.day = '2024-12-20' 
WHERE 
    emp.dept != '' 
    AND emp.shift_group = '' 
	AND emp.dept LIKE 'PD2%' 
    AND emp.section LIKE 'FAP2%' 
    AND emp.line_no LIKE '4127%' 
	AND (emp.resigned_date IS NULL OR emp.resigned_date >= '2024-12-20') 
GROUP BY 
    emp.process;

WITH AttendanceData AS (
    SELECT 
        emp.shift_group, 
        emp.dept, 
        emp.section, 
        ISNULL(emp.line_no, 'No Line') AS line_no, 
        COUNT(emp.emp_no) AS total, 
        COUNT(tio.emp_no) AS total_present, 
        COUNT(emp.emp_no) - COUNT(tio.emp_no) AS total_absent, 
        FORMAT(CASE 
            WHEN COUNT(emp.emp_no) > 0 THEN (COUNT(tio.emp_no) * 100.0 / COUNT(emp.emp_no)) 
            ELSE 0 
        END, 'N2') AS attendance_percentage,
		0 AS table_order
    FROM 
        m_employees emp 
    LEFT JOIN 
        t_time_in_out tio ON emp.emp_no = tio.emp_no AND tio.day = '2024-12-11' 
    WHERE 
		emp.shift_group IN ('A', 'B') AND 
		emp.dept IN ('PD1', 'PD2', 'QA') AND 
		emp.section IN ('FAP1 Mazda', 'First Process', 'Gemba Compliance') AND 
		emp.line_no IN ('1146', 'Mazda J12 Initial', 'Repair') AND 
        (emp.resigned_date IS NULL OR emp.resigned_date >= '2024-12-11') 
    GROUP BY 
        emp.dept, emp.section, emp.line_no, emp.shift_group
)

SELECT * FROM AttendanceData

UNION ALL

SELECT 
    'Total' AS shift_group, 
    NULL AS dept, 
    NULL AS section, 
    NULL AS line_no, 
    SUM(total) AS total, 
    SUM(total_present) AS total_present, 
    SUM(total_absent) AS total_absent, 
    FORMAT(CASE 
        WHEN SUM(total) > 0 THEN (SUM(total_present) * 100.0 / SUM(total)) 
        ELSE 0 
    END, 'N2') AS attendance_percentage,
	1 AS table_order
FROM 
    AttendanceData

ORDER BY 
    table_order ASC, shift_group ASC;

-- Attendance Summary Report with Date Range filter 
-- Define the start and end dates
DECLARE @StartDate DATE = '2024-12-01';
DECLARE @EndDate DATE = '2024-12-11';

-- CTE to generate a list of dates
WITH DateRange AS (
    SELECT @StartDate AS ReportDate
    UNION ALL
    SELECT DATEADD(DAY, 1, ReportDate)
    FROM DateRange
    WHERE ReportDate < @EndDate
)

SELECT 
    COUNT(emp.emp_no) AS total, 
    COUNT(tio.emp_no) AS total_present, 
    COUNT(emp.emp_no) - COUNT(tio.emp_no) AS total_absent, 
    FORMAT(CASE 
        WHEN COUNT(emp.emp_no) > 0 THEN (COUNT(tio.emp_no) * 100.0 / COUNT(emp.emp_no)) 
        ELSE 0 
    END, 'N2') AS attendance_percentage,
    dr.ReportDate AS day
FROM 
    DateRange dr
LEFT JOIN 
    m_employees emp ON (emp.resigned_date IS NULL OR emp.resigned_date >= dr.ReportDate)
LEFT JOIN 
    t_time_in_out tio ON emp.emp_no = tio.emp_no AND tio.day = dr.ReportDate
WHERE 
    emp.shift_group IN ('A', 'B', 'ADS') AND 
    emp.dept IN ('PD1', 'PD2', 'QA') AND 
    emp.section IN ('FAP1 Mazda', 'First Process', 'Gemba Compliance') AND 
    emp.line_no IN ('1146', 'Mazda J12 Initial', 'Repair')
GROUP BY 
    dr.ReportDate
OPTION (MAXRECURSION 0);  -- Allow recursion to go beyond the default limit if needed

-- Time Out Counting
DECLARE @day DATETIME = '2024-12-11';
DECLARE @day_tomorrow DATETIME = DATEADD(DAY, 1, CAST(@day AS DATETIME2));

DECLARE @day_15 DATETIME = DATEADD(HOUR, 15, CAST(@day AS DATETIME2));
DECLARE @day_16 DATETIME = DATEADD(HOUR, 16, CAST(@day AS DATETIME2));
DECLARE @day_17 DATETIME = DATEADD(HOUR, 17, CAST(@day AS DATETIME2));
DECLARE @day_18 DATETIME = DATEADD(HOUR, 18, CAST(@day AS DATETIME2));

DECLARE @day_15_59_59 DATETIME = DATEADD(SECOND, 15 * 3600 + 59 * 60 + 59, CAST(@day AS DATETIME2));
DECLARE @day_16_59_59 DATETIME = DATEADD(SECOND, 16 * 3600 + 59 * 60 + 59, CAST(@day AS DATETIME2));
DECLARE @day_17_59_59 DATETIME = DATEADD(SECOND, 17 * 3600 + 59 * 60 + 59, CAST(@day AS DATETIME2));
DECLARE @day_18_59_59 DATETIME = DATEADD(SECOND, 18 * 3600 + 59 * 60 + 59, CAST(@day AS DATETIME2));

DECLARE @day_tomorrow_3 DATETIME = DATEADD(HOUR, 3, @day_tomorrow);
DECLARE @day_tomorrow_4 DATETIME = DATEADD(HOUR, 4, @day_tomorrow);
DECLARE @day_tomorrow_5 DATETIME = DATEADD(HOUR, 5, @day_tomorrow);
DECLARE @day_tomorrow_6 DATETIME = DATEADD(HOUR, 6, @day_tomorrow);

DECLARE @day_tomorrow_3_59_59 DATETIME = DATEADD(SECOND, 3 * 3600 + 59 * 60 + 59, @day_tomorrow);
DECLARE @day_tomorrow_4_59_59 DATETIME = DATEADD(SECOND, 4 * 3600 + 59 * 60 + 59, @day_tomorrow);
DECLARE @day_tomorrow_5_59_59 DATETIME = DATEADD(SECOND, 5 * 3600 + 59 * 60 + 59, @day_tomorrow);
DECLARE @day_tomorrow_6_59_59 DATETIME = DATEADD(SECOND, 6 * 3600 + 59 * 60 + 59, @day_tomorrow);

WITH AttendanceData AS (
	SELECT 
		emp.dept, 
		emp.section,
		COUNT(CASE WHEN (tio.time_out BETWEEN @day_15 AND @day_15_59_59) 
					OR (tio.time_out BETWEEN @day_tomorrow_3 AND @day_tomorrow_3_59_59) 
					OR (tio.time_out IS NULL AND tio.day = @day) THEN 1 END) AS total_0,
		COUNT(CASE WHEN (tio.time_out BETWEEN @day_16 AND @day_16_59_59) 
					OR (tio.time_out BETWEEN @day_tomorrow_4 AND @day_tomorrow_4_59_59) THEN 1 END) AS total_1,
		COUNT(CASE WHEN (tio.time_out BETWEEN @day_17 AND @day_17_59_59) 
					OR (tio.time_out BETWEEN @day_tomorrow_5 AND @day_tomorrow_5_59_59) THEN 1 END) AS total_2,
		COUNT(CASE WHEN (tio.time_out BETWEEN @day_18 AND @day_18_59_59) 
					OR (tio.time_out BETWEEN @day_tomorrow_6 AND @day_tomorrow_6_59_59) THEN 1 END) AS total_3,
					-- Calculate total
		COUNT(CASE WHEN (tio.time_out BETWEEN @day_15 AND @day_15_59_59) 
					OR (tio.time_out BETWEEN @day_tomorrow_3 AND @day_tomorrow_3_59_59) 
					OR (tio.time_out IS NULL AND tio.day = @day) THEN 1 END) +
		COUNT(CASE WHEN (tio.time_out BETWEEN @day_16 AND @day_16_59_59) 
					OR (tio.time_out BETWEEN @day_tomorrow_4 AND @day_tomorrow_4_59_59) THEN 1 END) +
		COUNT(CASE WHEN (tio.time_out BETWEEN @day_17 AND @day_17_59_59) 
					OR (tio.time_out BETWEEN @day_tomorrow_5 AND @day_tomorrow_5_59_59) THEN 1 END) +
		COUNT(CASE WHEN (tio.time_out BETWEEN @day_18 AND @day_18_59_59) 
					OR (tio.time_out BETWEEN @day_tomorrow_6 AND @day_tomorrow_6_59_59) THEN 1 END) AS total,
		(
			(COUNT(CASE WHEN (tio.time_out BETWEEN @day_15 AND @day_15_59_59) 
						OR (tio.time_out BETWEEN @day_tomorrow_3 AND @day_tomorrow_3_59_59) 
						OR (tio.time_out IS NULL AND tio.day = @day) THEN 1 END) * 0) 
						+
			(COUNT(CASE WHEN (tio.time_out BETWEEN @day_16 AND @day_16_59_59) 
						OR (tio.time_out BETWEEN @day_tomorrow_4 AND @day_tomorrow_4_59_59) THEN 1 END) * 1) 
						+
			(COUNT(CASE WHEN (tio.time_out BETWEEN @day_17 AND @day_17_59_59) 
						OR (tio.time_out BETWEEN @day_tomorrow_5 AND @day_tomorrow_5_59_59) THEN 1 END) * 2) 
						+
			(COUNT(CASE WHEN (tio.time_out BETWEEN @day_18 AND @day_18_59_59) 
						OR (tio.time_out BETWEEN @day_tomorrow_6 AND @day_tomorrow_6_59_59) THEN 1 END) * 3) 
		) AS total_times,
					-- Calculate average_ot
		FORMAT(
		CASE WHEN (
			COUNT(CASE WHEN (tio.time_out BETWEEN @day_15 AND @day_15_59_59) 
					OR (tio.time_out BETWEEN @day_tomorrow_3 AND @day_tomorrow_3_59_59) 
					OR (tio.time_out IS NULL AND tio.day = @day) THEN 1 END) +
			COUNT(CASE WHEN (tio.time_out BETWEEN @day_16 AND @day_16_59_59) 
						OR (tio.time_out BETWEEN @day_tomorrow_4 AND @day_tomorrow_4_59_59) THEN 1 END) +
			COUNT(CASE WHEN (tio.time_out BETWEEN @day_17 AND @day_17_59_59) 
						OR (tio.time_out BETWEEN @day_tomorrow_5 AND @day_tomorrow_5_59_59) THEN 1 END) +
			COUNT(CASE WHEN (tio.time_out BETWEEN @day_18 AND @day_18_59_59) 
						OR (tio.time_out BETWEEN @day_tomorrow_6 AND @day_tomorrow_6_59_59) THEN 1 END)
		) > 0 
		THEN
		(
			CAST((
				(COUNT(CASE WHEN (tio.time_out BETWEEN @day_15 AND @day_15_59_59) 
							OR (tio.time_out BETWEEN @day_tomorrow_3 AND @day_tomorrow_3_59_59) 
							OR (tio.time_out IS NULL AND tio.day = @day) THEN 1 END) * 0) 
							+
				(COUNT(CASE WHEN (tio.time_out BETWEEN @day_16 AND @day_16_59_59) 
							OR (tio.time_out BETWEEN @day_tomorrow_4 AND @day_tomorrow_4_59_59) THEN 1 END) * 1) 
							+
				(COUNT(CASE WHEN (tio.time_out BETWEEN @day_17 AND @day_17_59_59) 
							OR (tio.time_out BETWEEN @day_tomorrow_5 AND @day_tomorrow_5_59_59) THEN 1 END) * 2) 
							+
				(COUNT(CASE WHEN (tio.time_out BETWEEN @day_18 AND @day_18_59_59) 
							OR (tio.time_out BETWEEN @day_tomorrow_6 AND @day_tomorrow_6_59_59) THEN 1 END) * 3) 
			) AS FLOAT)
			/ 
			CAST((
				COUNT(CASE WHEN (tio.time_out BETWEEN @day_15 AND @day_15_59_59) 
						OR (tio.time_out BETWEEN @day_tomorrow_3 AND @day_tomorrow_3_59_59) 
						OR (tio.time_out IS NULL AND tio.day = @day) THEN 1 END) +
				COUNT(CASE WHEN (tio.time_out BETWEEN @day_16 AND @day_16_59_59) 
							OR (tio.time_out BETWEEN @day_tomorrow_4 AND @day_tomorrow_4_59_59) THEN 1 END) +
				COUNT(CASE WHEN (tio.time_out BETWEEN @day_17 AND @day_17_59_59) 
							OR (tio.time_out BETWEEN @day_tomorrow_5 AND @day_tomorrow_5_59_59) THEN 1 END) +
				COUNT(CASE WHEN (tio.time_out BETWEEN @day_18 AND @day_18_59_59) 
							OR (tio.time_out BETWEEN @day_tomorrow_6 AND @day_tomorrow_6_59_59) THEN 1 END)
			) AS FLOAT)
		) ELSE 0 END, 'N2') AS average_ot,
		0 AS table_order
	FROM 
		m_employees emp
	LEFT JOIN 
		t_time_in_out tio ON tio.emp_no = emp.emp_no AND tio.day = @day 
	WHERE
		emp.dept != '' AND (emp.resigned_date IS NULL OR emp.resigned_date >= @day) AND 
		emp.shift_group = '' AND 
		emp.dept = '' AND 
		emp.section = '' AND 
		emp.line_no = '' 
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
	table_order ASC, dept ASC;

-- Time Out Counting (Search Multiple)
DECLARE @day DATETIME = '2024-12-11';
DECLARE @day_tomorrow DATETIME = DATEADD(DAY, 1, CAST(@day AS DATETIME2));

WITH AttendanceData AS (
	SELECT 
		emp.dept, 
		emp.section,
		COUNT(CASE WHEN (tio.time_out BETWEEN @day_15 AND @day_15_59_59) 
					OR (tio.time_out BETWEEN @day_tomorrow_3 AND @day_tomorrow_3_59_59) 
					OR (tio.time_out IS NULL AND tio.day = @day) THEN 1 END) AS total_0,
		COUNT(CASE WHEN (tio.time_out BETWEEN @day_16 AND @day_16_59_59) 
					OR (tio.time_out BETWEEN @day_tomorrow_4 AND @day_tomorrow_4_59_59) THEN 1 END) AS total_1,
		COUNT(CASE WHEN (tio.time_out BETWEEN @day_17 AND @day_17_59_59) 
					OR (tio.time_out BETWEEN @day_tomorrow_5 AND @day_tomorrow_5_59_59) THEN 1 END) AS total_2,
		COUNT(CASE WHEN (tio.time_out BETWEEN @day_18 AND @day_18_59_59) 
					OR (tio.time_out BETWEEN @day_tomorrow_6 AND @day_tomorrow_6_59_59) THEN 1 END) AS total_3,
					-- Calculate total
		COUNT(CASE WHEN (tio.time_out BETWEEN @day_15 AND @day_15_59_59) 
					OR (tio.time_out BETWEEN @day_tomorrow_3 AND @day_tomorrow_3_59_59) 
					OR (tio.time_out IS NULL AND tio.day = @day) THEN 1 END) +
		COUNT(CASE WHEN (tio.time_out BETWEEN @day_16 AND @day_16_59_59) 
					OR (tio.time_out BETWEEN @day_tomorrow_4 AND @day_tomorrow_4_59_59) THEN 1 END) +
		COUNT(CASE WHEN (tio.time_out BETWEEN @day_17 AND @day_17_59_59) 
					OR (tio.time_out BETWEEN @day_tomorrow_5 AND @day_tomorrow_5_59_59) THEN 1 END) +
		COUNT(CASE WHEN (tio.time_out BETWEEN @day_18 AND @day_18_59_59) 
					OR (tio.time_out BETWEEN @day_tomorrow_6 AND @day_tomorrow_6_59_59) THEN 1 END) AS total,
		(
			(COUNT(CASE WHEN (tio.time_out BETWEEN @day_15 AND @day_15_59_59) 
						OR (tio.time_out BETWEEN @day_tomorrow_3 AND @day_tomorrow_3_59_59) 
						OR (tio.time_out IS NULL AND tio.day = @day) THEN 1 END) * 0) 
						+
			(COUNT(CASE WHEN (tio.time_out BETWEEN @day_16 AND @day_16_59_59) 
						OR (tio.time_out BETWEEN @day_tomorrow_4 AND @day_tomorrow_4_59_59) THEN 1 END) * 1) 
						+
			(COUNT(CASE WHEN (tio.time_out BETWEEN @day_17 AND @day_17_59_59) 
						OR (tio.time_out BETWEEN @day_tomorrow_5 AND @day_tomorrow_5_59_59) THEN 1 END) * 2) 
						+
			(COUNT(CASE WHEN (tio.time_out BETWEEN @day_18 AND @day_18_59_59) 
						OR (tio.time_out BETWEEN @day_tomorrow_6 AND @day_tomorrow_6_59_59) THEN 1 END) * 3) 
		) AS total_times,
					-- Calculate average_ot
		FORMAT(
		CASE WHEN (
			COUNT(CASE WHEN (tio.time_out BETWEEN @day_15 AND @day_15_59_59) 
					OR (tio.time_out BETWEEN @day_tomorrow_3 AND @day_tomorrow_3_59_59) 
					OR (tio.time_out IS NULL AND tio.day = @day) THEN 1 END) +
			COUNT(CASE WHEN (tio.time_out BETWEEN @day_16 AND @day_16_59_59) 
						OR (tio.time_out BETWEEN @day_tomorrow_4 AND @day_tomorrow_4_59_59) THEN 1 END) +
			COUNT(CASE WHEN (tio.time_out BETWEEN @day_17 AND @day_17_59_59) 
						OR (tio.time_out BETWEEN @day_tomorrow_5 AND @day_tomorrow_5_59_59) THEN 1 END) +
			COUNT(CASE WHEN (tio.time_out BETWEEN @day_18 AND @day_18_59_59) 
						OR (tio.time_out BETWEEN @day_tomorrow_6 AND @day_tomorrow_6_59_59) THEN 1 END)
		) > 0 
		THEN
		(
			CAST((
				(COUNT(CASE WHEN (tio.time_out BETWEEN @day_15 AND @day_15_59_59) 
							OR (tio.time_out BETWEEN @day_tomorrow_3 AND @day_tomorrow_3_59_59) 
							OR (tio.time_out IS NULL AND tio.day = @day) THEN 1 END) * 0) 
							+
				(COUNT(CASE WHEN (tio.time_out BETWEEN @day_16 AND @day_16_59_59) 
							OR (tio.time_out BETWEEN @day_tomorrow_4 AND @day_tomorrow_4_59_59) THEN 1 END) * 1) 
							+
				(COUNT(CASE WHEN (tio.time_out BETWEEN @day_17 AND @day_17_59_59) 
							OR (tio.time_out BETWEEN @day_tomorrow_5 AND @day_tomorrow_5_59_59) THEN 1 END) * 2) 
							+
				(COUNT(CASE WHEN (tio.time_out BETWEEN @day_18 AND @day_18_59_59) 
							OR (tio.time_out BETWEEN @day_tomorrow_6 AND @day_tomorrow_6_59_59) THEN 1 END) * 3) 
			) AS FLOAT)
			/ 
			CAST((
				COUNT(CASE WHEN (tio.time_out BETWEEN @day_15 AND @day_15_59_59) 
						OR (tio.time_out BETWEEN @day_tomorrow_3 AND @day_tomorrow_3_59_59) 
						OR (tio.time_out IS NULL AND tio.day = @day) THEN 1 END) +
				COUNT(CASE WHEN (tio.time_out BETWEEN @day_16 AND @day_16_59_59) 
							OR (tio.time_out BETWEEN @day_tomorrow_4 AND @day_tomorrow_4_59_59) THEN 1 END) +
				COUNT(CASE WHEN (tio.time_out BETWEEN @day_17 AND @day_17_59_59) 
							OR (tio.time_out BETWEEN @day_tomorrow_5 AND @day_tomorrow_5_59_59) THEN 1 END) +
				COUNT(CASE WHEN (tio.time_out BETWEEN @day_18 AND @day_18_59_59) 
							OR (tio.time_out BETWEEN @day_tomorrow_6 AND @day_tomorrow_6_59_59) THEN 1 END)
			) AS FLOAT)
		) ELSE 0 END, 'N2') AS average_ot,
		0 AS table_order
	FROM 
		m_employees emp
	LEFT JOIN 
		t_time_in_out tio ON tio.emp_no = emp.emp_no AND tio.day = @day
	WHERE 
		emp.dept != '' AND (emp.resigned_date IS NULL OR emp.resigned_date >= @day) AND 
		emp.shift_group IN ('A', 'B', 'ADS') AND 
		emp.dept IN ('PD1', 'PD2', 'QA') AND 
		emp.section IN ('FAP1 Mazda', 'First Process', 'Gemba Compliance') AND 
		emp.line_no IN ('1146', 'Mazda J12 Initial', 'Repair')
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
	table_order ASC, dept ASC;

-- Time Out Counting (Search Multiple) with Date Range filter 
-- Define the start and end dates
DECLARE @StartDate DATE = '2024-12-11';
DECLARE @EndDate DATE = '2024-12-14';

-- CTE to generate a list of dates
WITH DateRange AS (
    SELECT @StartDate AS ReportDate
    UNION ALL
    SELECT DATEADD(DAY, 1, ReportDate)
    FROM DateRange
    WHERE ReportDate < @EndDate
)

SELECT 
	dr.ReportDate AS day,
    COUNT(CASE WHEN (tio.time_out BETWEEN DATEADD(HOUR, 15, CAST(dr.ReportDate AS DATETIME2)) AND DATEADD(SECOND, 15 * 3600 + 59 * 60 + 59, CAST(dr.ReportDate AS DATETIME2))) 
                OR (tio.time_out BETWEEN DATEADD(HOUR, 3, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2))) AND DATEADD(SECOND, 3 * 3600 + 59 * 60 + 59, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2)))) 
                OR (tio.time_out IS NULL AND tio.day = dr.ReportDate) THEN 1 END) AS total_0,
    COUNT(CASE WHEN (tio.time_out BETWEEN DATEADD(HOUR, 16, CAST(dr.ReportDate AS DATETIME2)) AND DATEADD(SECOND, 16 * 3600 + 59 * 60 + 59, CAST(dr.ReportDate AS DATETIME2))) 
                OR (tio.time_out BETWEEN DATEADD(HOUR, 4, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2))) AND DATEADD(SECOND, 4 * 3600 + 59 * 60 + 59, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2)))) THEN 1 END) AS total_1,
    COUNT(CASE WHEN (tio.time_out BETWEEN DATEADD(HOUR, 17, CAST(dr.ReportDate AS DATETIME2)) AND DATEADD(SECOND, 17 * 3600 + 59 * 60 + 59, CAST(dr.ReportDate AS DATETIME2))) 
                OR (tio.time_out BETWEEN DATEADD(HOUR, 5, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2))) AND DATEADD(SECOND, 5 * 3600 + 59 * 60 + 59, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2)))) THEN 1 END) AS total_2,
    COUNT(CASE WHEN (tio.time_out BETWEEN DATEADD(HOUR, 18, CAST(dr.ReportDate AS DATETIME2)) AND DATEADD(SECOND, 18 * 3600 + 59 * 60 + 59, CAST(dr.ReportDate AS DATETIME2))) 
                OR (tio.time_out BETWEEN DATEADD(HOUR, 6, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2))) AND DATEADD(SECOND, 6 * 3600 + 59 * 60 + 59, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2)))) THEN 1 END) AS total_3,
				-- Calculate total
    COUNT(CASE WHEN (tio.time_out BETWEEN DATEADD(HOUR, 15, CAST(dr.ReportDate AS DATETIME2)) AND DATEADD(SECOND, 15 * 3600 + 59 * 60 + 59, CAST(dr.ReportDate AS DATETIME2))) 
                OR (tio.time_out BETWEEN DATEADD(HOUR, 3, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2))) AND DATEADD(SECOND, 3 * 3600 + 59 * 60 + 59, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2)))) 
                OR (tio.time_out IS NULL AND tio.day = dr.ReportDate) THEN 1 END) +
    COUNT(CASE WHEN (tio.time_out BETWEEN DATEADD(HOUR, 16, CAST(dr.ReportDate AS DATETIME2)) AND DATEADD(SECOND, 16 * 3600 + 59 * 60 + 59, CAST(dr.ReportDate AS DATETIME2))) 
                OR (tio.time_out BETWEEN DATEADD(HOUR, 4, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2))) AND DATEADD(SECOND, 4 * 3600 + 59 * 60 + 59, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2)))) THEN 1 END) +
    COUNT(CASE WHEN (tio.time_out BETWEEN DATEADD(HOUR, 17, CAST(dr.ReportDate AS DATETIME2)) AND DATEADD(SECOND, 17 * 3600 + 59 * 60 + 59, CAST(dr.ReportDate AS DATETIME2))) 
                OR (tio.time_out BETWEEN DATEADD(HOUR, 5, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2))) AND DATEADD(SECOND, 5 * 3600 + 59 * 60 + 59, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2)))) THEN 1 END) +
    COUNT(CASE WHEN (tio.time_out BETWEEN DATEADD(HOUR, 18, CAST(dr.ReportDate AS DATETIME2)) AND DATEADD(SECOND, 18 * 3600 + 59 * 60 + 59, CAST(dr.ReportDate AS DATETIME2))) 
                OR (tio.time_out BETWEEN DATEADD(HOUR, 6, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2))) AND DATEADD(SECOND, 6 * 3600 + 59 * 60 + 59, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2)))) THEN 1 END) AS total,
	(
		(COUNT(CASE WHEN (tio.time_out BETWEEN DATEADD(HOUR, 15, CAST(dr.ReportDate AS DATETIME2)) AND DATEADD(SECOND, 15 * 3600 + 59 * 60 + 59, CAST(dr.ReportDate AS DATETIME2))) 
					OR (tio.time_out BETWEEN DATEADD(HOUR, 3, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2))) AND DATEADD(SECOND, 3 * 3600 + 59 * 60 + 59, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2)))) 
					OR (tio.time_out IS NULL AND tio.day = dr.ReportDate) THEN 1 END) * 0) 
					+
		(COUNT(CASE WHEN (tio.time_out BETWEEN DATEADD(HOUR, 16, CAST(dr.ReportDate AS DATETIME2)) AND DATEADD(SECOND, 16 * 3600 + 59 * 60 + 59, CAST(dr.ReportDate AS DATETIME2))) 
					OR (tio.time_out BETWEEN DATEADD(HOUR, 4, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2))) AND DATEADD(SECOND, 4 * 3600 + 59 * 60 + 59, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2)))) THEN 1 END) * 1) 
					+
		(COUNT(CASE WHEN (tio.time_out BETWEEN DATEADD(HOUR, 17, CAST(dr.ReportDate AS DATETIME2)) AND DATEADD(SECOND, 17 * 3600 + 59 * 60 + 59, CAST(dr.ReportDate AS DATETIME2))) 
					OR (tio.time_out BETWEEN DATEADD(HOUR, 5, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2))) AND DATEADD(SECOND, 5 * 3600 + 59 * 60 + 59, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2)))) THEN 1 END) * 2) 
					+
		(COUNT(CASE WHEN (tio.time_out BETWEEN DATEADD(HOUR, 18, CAST(dr.ReportDate AS DATETIME2)) AND DATEADD(SECOND, 18 * 3600 + 59 * 60 + 59, CAST(dr.ReportDate AS DATETIME2))) 
					OR (tio.time_out BETWEEN DATEADD(HOUR, 6, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2))) AND DATEADD(SECOND, 6 * 3600 + 59 * 60 + 59, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2)))) THEN 1 END) * 3) 
	) AS total_times,
				-- Calculate average_ot
	FORMAT(
	CASE WHEN (
		COUNT(CASE WHEN (tio.time_out BETWEEN DATEADD(HOUR, 15, CAST(dr.ReportDate AS DATETIME2)) AND DATEADD(SECOND, 15 * 3600 + 59 * 60 + 59, CAST(dr.ReportDate AS DATETIME2))) 
                OR (tio.time_out BETWEEN DATEADD(HOUR, 3, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2))) AND DATEADD(SECOND, 3 * 3600 + 59 * 60 + 59, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2)))) 
                OR (tio.time_out IS NULL AND tio.day = dr.ReportDate) THEN 1 END) +
		COUNT(CASE WHEN (tio.time_out BETWEEN DATEADD(HOUR, 16, CAST(dr.ReportDate AS DATETIME2)) AND DATEADD(SECOND, 16 * 3600 + 59 * 60 + 59, CAST(dr.ReportDate AS DATETIME2))) 
					OR (tio.time_out BETWEEN DATEADD(HOUR, 4, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2))) AND DATEADD(SECOND, 4 * 3600 + 59 * 60 + 59, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2)))) THEN 1 END) +
		COUNT(CASE WHEN (tio.time_out BETWEEN DATEADD(HOUR, 17, CAST(dr.ReportDate AS DATETIME2)) AND DATEADD(SECOND, 17 * 3600 + 59 * 60 + 59, CAST(dr.ReportDate AS DATETIME2))) 
					OR (tio.time_out BETWEEN DATEADD(HOUR, 5, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2))) AND DATEADD(SECOND, 5 * 3600 + 59 * 60 + 59, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2)))) THEN 1 END) +
		COUNT(CASE WHEN (tio.time_out BETWEEN DATEADD(HOUR, 18, CAST(dr.ReportDate AS DATETIME2)) AND DATEADD(SECOND, 18 * 3600 + 59 * 60 + 59, CAST(dr.ReportDate AS DATETIME2))) 
					OR (tio.time_out BETWEEN DATEADD(HOUR, 6, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2))) AND DATEADD(SECOND, 6 * 3600 + 59 * 60 + 59, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2)))) THEN 1 END)
	) > 0 
	THEN
	(
		CAST((
			(COUNT(CASE WHEN (tio.time_out BETWEEN DATEADD(HOUR, 15, CAST(dr.ReportDate AS DATETIME2)) AND DATEADD(SECOND, 15 * 3600 + 59 * 60 + 59, CAST(dr.ReportDate AS DATETIME2))) 
						OR (tio.time_out BETWEEN DATEADD(HOUR, 3, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2))) AND DATEADD(SECOND, 3 * 3600 + 59 * 60 + 59, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2)))) 
						OR (tio.time_out IS NULL AND tio.day = dr.ReportDate) THEN 1 END) * 0) 
						+
			(COUNT(CASE WHEN (tio.time_out BETWEEN DATEADD(HOUR, 16, CAST(dr.ReportDate AS DATETIME2)) AND DATEADD(SECOND, 16 * 3600 + 59 * 60 + 59, CAST(dr.ReportDate AS DATETIME2))) 
						OR (tio.time_out BETWEEN DATEADD(HOUR, 4, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2))) AND DATEADD(SECOND, 4 * 3600 + 59 * 60 + 59, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2)))) THEN 1 END) * 1) 
						+
			(COUNT(CASE WHEN (tio.time_out BETWEEN DATEADD(HOUR, 17, CAST(dr.ReportDate AS DATETIME2)) AND DATEADD(SECOND, 17 * 3600 + 59 * 60 + 59, CAST(dr.ReportDate AS DATETIME2))) 
						OR (tio.time_out BETWEEN DATEADD(HOUR, 5, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2))) AND DATEADD(SECOND, 5 * 3600 + 59 * 60 + 59, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2)))) THEN 1 END) * 2) 
						+
			(COUNT(CASE WHEN (tio.time_out BETWEEN DATEADD(HOUR, 18, CAST(dr.ReportDate AS DATETIME2)) AND DATEADD(SECOND, 18 * 3600 + 59 * 60 + 59, CAST(dr.ReportDate AS DATETIME2))) 
						OR (tio.time_out BETWEEN DATEADD(HOUR, 6, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2))) AND DATEADD(SECOND, 6 * 3600 + 59 * 60 + 59, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2)))) THEN 1 END) * 3) 
		) AS FLOAT)
		/ 
		CAST((
			COUNT(CASE WHEN (tio.time_out BETWEEN DATEADD(HOUR, 15, CAST(dr.ReportDate AS DATETIME2)) AND DATEADD(SECOND, 15 * 3600 + 59 * 60 + 59, CAST(dr.ReportDate AS DATETIME2))) 
					OR (tio.time_out BETWEEN DATEADD(HOUR, 3, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2))) AND DATEADD(SECOND, 3 * 3600 + 59 * 60 + 59, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2)))) 
					OR (tio.time_out IS NULL AND tio.day = dr.ReportDate) THEN 1 END) +
			COUNT(CASE WHEN (tio.time_out BETWEEN DATEADD(HOUR, 16, CAST(dr.ReportDate AS DATETIME2)) AND DATEADD(SECOND, 16 * 3600 + 59 * 60 + 59, CAST(dr.ReportDate AS DATETIME2))) 
						OR (tio.time_out BETWEEN DATEADD(HOUR, 4, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2))) AND DATEADD(SECOND, 4 * 3600 + 59 * 60 + 59, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2)))) THEN 1 END) +
			COUNT(CASE WHEN (tio.time_out BETWEEN DATEADD(HOUR, 17, CAST(dr.ReportDate AS DATETIME2)) AND DATEADD(SECOND, 17 * 3600 + 59 * 60 + 59, CAST(dr.ReportDate AS DATETIME2))) 
						OR (tio.time_out BETWEEN DATEADD(HOUR, 5, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2))) AND DATEADD(SECOND, 5 * 3600 + 59 * 60 + 59, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2)))) THEN 1 END) +
			COUNT(CASE WHEN (tio.time_out BETWEEN DATEADD(HOUR, 18, CAST(dr.ReportDate AS DATETIME2)) AND DATEADD(SECOND, 18 * 3600 + 59 * 60 + 59, CAST(dr.ReportDate AS DATETIME2))) 
						OR (tio.time_out BETWEEN DATEADD(HOUR, 6, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2))) AND DATEADD(SECOND, 6 * 3600 + 59 * 60 + 59, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2)))) THEN 1 END)
		) AS FLOAT)
	) ELSE 0 END, 'N2') AS average_ot
FROM 
    DateRange dr
LEFT JOIN 
    m_employees emp ON (emp.resigned_date IS NULL OR emp.resigned_date >= dr.ReportDate)
LEFT JOIN 
    t_time_in_out tio ON tio.emp_no = emp.emp_no AND tio.day = dr.ReportDate
WHERE 
    emp.shift_group IN ('A', 'B', 'ADS') AND 
    emp.dept IN ('PD1', 'PD2', 'QA') AND 
    emp.section IN ('FAP1 Mazda', 'First Process', 'Gemba Compliance') AND 
    emp.line_no IN ('1146', 'Mazda J12 Initial', 'Repair')
GROUP BY 
    dr.ReportDate
OPTION (MAXRECURSION 0);  -- Allow recursion to go beyond the default limit if needed

-- Brainstorming pa ako sa query ng search multiple time out counting

DECLARE @day_15 DATETIME = DATEADD(HOUR, 15, CAST(@day AS DATETIME2));
DECLARE @day_16 DATETIME = DATEADD(HOUR, 16, CAST(@day AS DATETIME2));
DECLARE @day_17 DATETIME = DATEADD(HOUR, 17, CAST(@day AS DATETIME2));
DECLARE @day_18 DATETIME = DATEADD(HOUR, 18, CAST(@day AS DATETIME2));

DECLARE @day_15_59_59 DATETIME = DATEADD(SECOND, 15 * 3600 + 59 * 60 + 59, CAST(@day AS DATETIME2));
DECLARE @day_16_59_59 DATETIME = DATEADD(SECOND, 16 * 3600 + 59 * 60 + 59, CAST(@day AS DATETIME2));
DECLARE @day_17_59_59 DATETIME = DATEADD(SECOND, 17 * 3600 + 59 * 60 + 59, CAST(@day AS DATETIME2));
DECLARE @day_18_59_59 DATETIME = DATEADD(SECOND, 18 * 3600 + 59 * 60 + 59, CAST(@day AS DATETIME2));

DECLARE @day_tomorrow_3 DATETIME = DATEADD(HOUR, 3, @day_tomorrow);
DECLARE @day_tomorrow_4 DATETIME = DATEADD(HOUR, 4, @day_tomorrow);
DECLARE @day_tomorrow_5 DATETIME = DATEADD(HOUR, 5, @day_tomorrow);
DECLARE @day_tomorrow_6 DATETIME = DATEADD(HOUR, 6, @day_tomorrow);

DECLARE @day_tomorrow_3_59_59 DATETIME = DATEADD(SECOND, 3 * 3600 + 59 * 60 + 59, @day_tomorrow);
DECLARE @day_tomorrow_4_59_59 DATETIME = DATEADD(SECOND, 4 * 3600 + 59 * 60 + 59, @day_tomorrow);
DECLARE @day_tomorrow_5_59_59 DATETIME = DATEADD(SECOND, 5 * 3600 + 59 * 60 + 59, @day_tomorrow);
DECLARE @day_tomorrow_6_59_59 DATETIME = DATEADD(SECOND, 6 * 3600 + 59 * 60 + 59, @day_tomorrow);

@day_15 AND @day_15_59_59
@day_16 AND @day_16_59_59
@day_17 AND @day_17_59_59
@day_18 AND @day_18_59_59

@day_tomorrow_3 AND @day_tomorrow_3_59_59
@day_tomorrow_4 AND @day_tomorrow_4_59_59
@day_tomorrow_5 AND @day_tomorrow_5_59_59
@day_tomorrow_6 AND @day_tomorrow_6_59_59


DECLARE @StartDate DATE = '2024-12-01';
DECLARE @EndDate DATE = '2024-12-11';

DATEADD(HOUR, 15, CAST(dr.ReportDate AS DATETIME2)) AND DATEADD(SECOND, 15 * 3600 + 59 * 60 + 59, CAST(dr.ReportDate AS DATETIME2))
DATEADD(HOUR, 16, CAST(dr.ReportDate AS DATETIME2)) AND DATEADD(SECOND, 16 * 3600 + 59 * 60 + 59, CAST(dr.ReportDate AS DATETIME2))
DATEADD(HOUR, 17, CAST(dr.ReportDate AS DATETIME2)) AND DATEADD(SECOND, 17 * 3600 + 59 * 60 + 59, CAST(dr.ReportDate AS DATETIME2))
DATEADD(HOUR, 18, CAST(dr.ReportDate AS DATETIME2)) AND DATEADD(SECOND, 18 * 3600 + 59 * 60 + 59, CAST(dr.ReportDate AS DATETIME2))

DATEADD(HOUR, 3, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2))) AND DATEADD(SECOND, 3 * 3600 + 59 * 60 + 59, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2)))
DATEADD(HOUR, 4, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2))) AND DATEADD(SECOND, 4 * 3600 + 59 * 60 + 59, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2)))
DATEADD(HOUR, 5, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2))) AND DATEADD(SECOND, 5 * 3600 + 59 * 60 + 59, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2)))
DATEADD(HOUR, 6, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2))) AND DATEADD(SECOND, 6 * 3600 + 59 * 60 + 59, DATEADD(DAY, 1, CAST(dr.ReportDate AS DATETIME2)))
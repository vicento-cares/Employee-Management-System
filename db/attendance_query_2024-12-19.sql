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
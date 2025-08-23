-- Insert Into Select for m_employees_h (MS SQL Server Agent - Jobs / Cron Jobs)
-- Time of execution: 06:05:00 / 6:05 AM

INSERT INTO 
    m_employees_h 
    (emp_no, full_name, dept, section, car_model, sub_section, process, skill_level, line_no, 
    position, provider, gender, shift, shift_group, date_hired, address, contact_no, emp_status, shuttle_route, 
    emp_js_s, emp_js_s_no, emp_sv, emp_sv_no, emp_approver, emp_approver_no, emp_ack, emp_ack_no, reason, 
    mp_analysis_code, ref_eff, class_eff, resigned, resigned_date, date_updated, h_date) 
SELECT 
    emp_no, full_name, dept, section, car_model, sub_section, process, skill_level, line_no, 
    position, provider, gender, shift, shift_group, date_hired, address, contact_no, emp_status, shuttle_route, 
    emp_js_s, emp_js_s_no, emp_sv, emp_sv_no, emp_approver, emp_approver_no, emp_ack, emp_ack_no, reason, 
    mp_analysis_code, ref_eff, class_eff, resigned, resigned_date, date_updated, 
    CAST(CONVERT(VARCHAR, DATEADD(DAY, -1, GETDATE()), 112) + ' 06:05:00' AS DATETIME) AS h_date 
FROM 
    m_employees 
WHERE 
    resigned = 0 AND 
    resigned_date IS NULL;

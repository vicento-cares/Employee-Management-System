-- m_employees_h
INSERT INTO emp_mgt_backup.dbo.m_employees_h 
    ([emp_no],[full_name],[dept],[section],[car_model],[sub_section],[process],[skill_level],[line_no], 
    [position],[provider],[gender],[shift],[shift_group],[date_hired],[address],[contact_no],[emp_status],
    [shuttle_route],[emp_js_s],[emp_js_s_no],[emp_sv],[emp_sv_no],[emp_approver],[emp_approver_no],[emp_ack],
    [emp_ack_no],[reason],[mp_analysis_code],[ref_eff],[class_eff],[resigned],[resigned_date],[date_updated],[h_date]) 
SELECT [emp_no],[full_name],[dept],[section],[car_model],[sub_section],[process],[skill_level],[line_no], 
    [position],[provider],[gender],[shift],[shift_group],[date_hired],[address],[contact_no],[emp_status],
    [shuttle_route],[emp_js_s],[emp_js_s_no],[emp_sv],[emp_sv_no],[emp_approver],[emp_approver_no],[emp_ack],
    [emp_ack_no],[reason],[mp_analysis_code],[ref_eff],[class_eff],[resigned],[resigned_date],[date_updated],GETDATE() AS h_date 
FROM emp_mgt_db.dbo.m_employees;

-- t_absences
INSERT INTO emp_mgt_backup.dbo.t_absences 
    ([emp_no],[day],[shift_group],[absent_category],[absent_type],[reason],[submitted_by_no],[updated_by_no],[date_updated]) 
SELECT [emp_no],[day],[shift_group],[absent_category],[absent_type],[reason],[submitted_by_no],[updated_by_no],[date_updated] 
FROM emp_mgt_db.dbo.t_absences WHERE [day] < GETDATE();

DELETE FROM emp_mgt_db.dbo.t_absences WHERE [day] < GETDATE();

-- t_employee_transfer_history
INSERT INTO emp_mgt_backup.dbo.t_employee_transfer_history 
    ([emp_transfer_id],[emp_transfer_batch_id],[approve_key],[emp_no],[emp_transfer_type],[dept_from],[section_from], 
    [line_no_from],[dept_to],[section_to],[line_no_to],[issued_by],[issued_by_no],[date_issued_by],[checked_by],
    [checked_by_no],[date_checked_by],[approved_by],[approved_by_no],[date_approved_by],[r_noted_by],[r_noted_by_no], 
    [r_date_noted_by],[r_acknowledged_by],[r_acknowledged_by_no],[r_date_acknowledged_by],[r_approved_by],[r_approved_by_no], 
    [r_date_approved_by],[hr_ack],[hr_ack_no],[hr_date_ack],[reason],[is_approved],[date_effectivity],[date_updated]) 
SELECT [emp_transfer_id],[emp_transfer_batch_id],[approve_key],[emp_no],[emp_transfer_type],[dept_from],[section_from], 
    [line_no_from],[dept_to],[section_to],[line_no_to],[issued_by],[issued_by_no],[date_issued_by],[checked_by],
    [checked_by_no],[date_checked_by],[approved_by],[approved_by_no],[date_approved_by],[r_noted_by],[r_noted_by_no], 
    [r_date_noted_by],[r_acknowledged_by],[r_acknowledged_by_no],[r_date_acknowledged_by],[r_approved_by],[r_approved_by_no], 
    [r_date_approved_by],[hr_ack],[hr_ack_no],[hr_date_ack],[reason],[is_approved],[date_effectivity],[date_updated] 
FROM emp_mgt_db.dbo.t_employee_transfer_history WHERE [is_approved] = 1;

DELETE FROM emp_mgt_db.dbo.t_employee_transfer_history WHERE [is_approved] = 1;

-- t_leave_form_history
INSERT INTO emp_mgt_backup.dbo.t_leave_form_history 
    ([leave_form_id],[emp_no],[date_filed],[address],[contact_no],[leave_type],[leave_date_from],[leave_date_to], 
    [total_leave_days],[irt_phone_call],[irt_letter],[irb],[reason],[issued_by],[js_s],[sv],[approver],[disapproved_by], 
    [leave_form_status],[sl_r1_1_hrs],[sl_r1_1_date],[sl_r1_1_time_in],[sl_r1_1_time_out],[sl_r1_2_days],[sl_r1_3_date], 
    [sl_rc_1_days],[sl_rc_2_from],[sl_rc_2_to],[sl_rc_3_oc],[sl_rc_4_hm],[sl_rc_mgh],[sl_r2],[sl_dr_name],[sl_dr_date], 
    [date_updated]) 
SELECT [leave_form_id],[emp_no],[date_filed],[address],[contact_no],[leave_type],[leave_date_from],[leave_date_to], 
    [total_leave_days],[irt_phone_call],[irt_letter],[irb],[reason],[issued_by],[js_s],[sv],[approver],[disapproved_by], 
    [leave_form_status],[sl_r1_1_hrs],[sl_r1_1_date],[sl_r1_1_time_in],[sl_r1_1_time_out],[sl_r1_2_days],[sl_r1_3_date], 
    [sl_rc_1_days],[sl_rc_2_from],[sl_rc_2_to],[sl_rc_3_oc],[sl_rc_4_hm],[sl_rc_mgh],[sl_r2],[sl_dr_name],[sl_dr_date], 
    [date_updated] 
FROM emp_mgt_db.dbo.t_leave_form_history WHERE [approver] != '';

DELETE FROM emp_mgt_db.dbo.t_leave_form_history WHERE [approver] != '';

-- t_line_shifting
INSERT INTO emp_mgt_backup.dbo.t_line_shifting 
    ([dept],[section],[line_no],[shift],[shift_group],[schedule_date],[is_reflected],[date_updated]) 
SELECT [dept],[section],[line_no],[shift],[shift_group],[schedule_date],[is_reflected],[date_updated] 
FROM emp_mgt_db.dbo.t_line_shifting WHERE [schedule_date] < GETDATE() AND [is_reflected] = 1;

DELETE FROM emp_mgt_db.dbo.t_line_shifting WHERE [schedule_date] < GETDATE() AND [is_reflected] = 1;

-- t_line_support_history
INSERT INTO emp_mgt_backup.dbo.t_line_support_history 
    ([line_support_id],[emp_no],[day],[shift],[line_no_from],[line_no_to],[assigned_process],[skill_level], 
    [assigned_station],[assigned_station_no],[set_by],[set_by_no],[set_status_by],[set_status_by_no],[status], 
    [start_date],[end_date],[date_updated]) 
SELECT [line_support_id],[emp_no],[day],[shift],[line_no_from],[line_no_to],[assigned_process],[skill_level], 
    [assigned_station],[assigned_station_no],[set_by],[set_by_no],[set_status_by],[set_status_by_no],[status], 
    [start_date],[end_date],[date_updated] 
FROM emp_mgt_db.dbo.t_line_support_history WHERE [day] < GETDATE();

DELETE FROM emp_mgt_db.dbo.t_line_support_history WHERE [day] < GETDATE();

-- t_shuttle_allocation
INSERT INTO emp_mgt_backup.dbo.t_shuttle_allocation 
    ([emp_no],[dept],[section],[line_no],[day],[shift],[shift_group],[shuttle_route], 
    [out_5],[out_6],[out_7],[out_8],[set_by],[date_updated]) 
SELECT [emp_no],[dept],[section],[line_no],[day],[shift],[shift_group],[shuttle_route], 
    [out_5],[out_6],[out_7],[out_8],[set_by],[date_updated] 
FROM emp_mgt_db.dbo.t_shuttle_allocation WHERE [day] < GETDATE();

DELETE FROM emp_mgt_db.dbo.t_shuttle_allocation WHERE [day] < GETDATE();

-- t_shuttle_allocation_sh
INSERT INTO emp_mgt_backup.dbo.t_shuttle_allocation_sh 
    ([dept],[section],[day],[shift],[shuttle_route],[total_count],[sched_type],[set_by],[date_uploaded],[date_updated]) 
SELECT [dept],[section],[day],[shift],[shuttle_route],[total_count],[sched_type],[set_by],[date_uploaded],[date_updated] 
FROM emp_mgt_db.dbo.t_shuttle_allocation_sh WHERE [day] < GETDATE();

DELETE FROM emp_mgt_db.dbo.t_shuttle_allocation_sh WHERE [day] < GETDATE();

-- t_shuttle_allocation_w
INSERT INTO emp_mgt_backup.dbo.t_shuttle_allocation_w 
    ([dept],[section],[date_from],[date_to],[shift],[shuttle_route],[sched_type],[total_count], 
    [set_by],[date_uploaded],[date_updated]) 
SELECT [dept],[section],[date_from],[date_to],[shift],[shuttle_route],[sched_type],[total_count], 
    [set_by],[date_uploaded],[date_updated] 
FROM emp_mgt_db.dbo.t_shuttle_allocation_w WHERE [date_to] < GETDATE();

DELETE FROM emp_mgt_db.dbo.t_shuttle_allocation_w WHERE [date_to] < GETDATE();

-- t_time_in_out
INSERT INTO emp_mgt_backup.dbo.t_time_in_out 
    ([emp_no],[day],[shift],[time_in],[time_out],[ip],[date_updated]) 
SELECT [emp_no],[day],[shift],[time_in],[time_out],[ip],[date_updated] 
FROM emp_mgt_db.dbo.t_time_in_out WHERE [day] < GETDATE();

DELETE FROM emp_mgt_db.dbo.t_time_in_out WHERE [day] < GETDATE();

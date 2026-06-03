<?php
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

require '../conn.php';

$method = $_POST['method'];

if ($method == 'backup_empmgtsys_data') {
    $bak_date_from = $_POST['bak_date_from'];
    $bak_date_to = $_POST['bak_date_to'];

    $bak_date_time_from = $bak_date_from . ' 00:00:00';
    $bak_date_time_to = $bak_date_to . ' 23:59:59';

    $backup_by = '';

    if (isset($_SESSION['full_name'])) {
        $backup_by = $_SESSION['full_name'];
    }

    $isTransactionActive = false;
	
	try {
		if (!$isTransactionActive) {
			$conn->beginTransaction();
			$isTransactionActive = true;
		}

        // t_absences

        $query = "INSERT INTO emp_mgt_backup.dbo.t_absences 
                        ([emp_no],[day],[shift_group],[absent_category],[absent_type],[reason],[submitted_by_no],[updated_by_no],[date_updated]) 
                    SELECT [emp_no],[day],[shift_group],[absent_category],[absent_type],[reason],[submitted_by_no],[updated_by_no],[date_updated] 
                    FROM emp_mgt_db.dbo.t_absences 
                    WHERE [day] BETWEEN ? AND ?";

		$stmt = $conn->prepare($query);
		$stmt->execute([$bak_date_from, $bak_date_to]);

        $query = "DELETE FROM 
                        emp_mgt_db.dbo.t_error_monitoring 
                    WHERE 
                        [day] BETWEEN ? AND ?";

		$stmt = $conn->prepare($query);
		$stmt->execute([$bak_date_from, $bak_date_to]);

        // t_line_shifting

        $query = "INSERT INTO emp_mgt_backup.dbo.t_line_shifting 
                        ([dept],[section],[line_no],[shift],[shift_group],[schedule_date],[is_reflected],[date_updated]) 
                    SELECT [dept],[section],[line_no],[shift],[shift_group],[schedule_date],[is_reflected],[date_updated] 
                    FROM emp_mgt_db.dbo.t_line_shifting 
                    WHERE 
                        ([schedule_date] >= ? AND [schedule_date] <= ?) AND 
                        [is_reflected] = 1";

		$stmt = $conn->prepare($query);
		$stmt->execute([$bak_date_time_from, $bak_date_time_to]);

        $query = "DELETE FROM 
                        emp_mgt_db.dbo.t_line_shifting 
                    WHERE 
                        ([schedule_date] >= ? AND [schedule_date] <= ?) AND 
                        [is_reflected] = 1";

		$stmt = $conn->prepare($query);
		$stmt->execute([$bak_date_time_from, $bak_date_time_to]);

        // t_line_support_history

        $query = "INSERT INTO emp_mgt_backup.dbo.t_line_support_history 
                        ([line_support_id],[emp_no],[day],[shift],[line_no_from],[line_no_to],[assigned_process],[skill_level], 
                        [assigned_station],[assigned_station_no],[set_by],[set_by_no],[set_status_by],[set_status_by_no],[status], 
                        [start_date],[end_date],[date_updated]) 
                    SELECT [line_support_id],[emp_no],[day],[shift],[line_no_from],[line_no_to],[assigned_process],[skill_level], 
                        [assigned_station],[assigned_station_no],[set_by],[set_by_no],[set_status_by],[set_status_by_no],[status], 
                        [start_date],[end_date],[date_updated] 
                    FROM emp_mgt_db.dbo.t_line_support_history 
                    WHERE [day] BETWEEN ? AND ?";

		$stmt = $conn->prepare($query);
		$stmt->execute([$bak_date_from, $bak_date_to]);

        $query = "DELETE FROM 
                        emp_mgt_db.dbo.t_line_support_history 
                    WHERE 
                        [day] BETWEEN ? AND ?";

		$stmt = $conn->prepare($query);
		$stmt->execute([$bak_date_from, $bak_date_to]);

        // t_time_in_out

        $query = "INSERT INTO emp_mgt_backup.dbo.t_time_in_out 
                        ([emp_no],[day],[shift],[time_in],[time_out],[ip],[date_updated]) 
                    SELECT [emp_no],[day],[shift],[time_in],[time_out],[ip],[date_updated] 
                    FROM emp_mgt_db.dbo.t_time_in_out 
                    WHERE 
                        [day] BETWEEN ? AND ?";

		$stmt = $conn->prepare($query);
		$stmt->execute([$bak_date_from, $bak_date_to]);

        $query = "DELETE FROM 
                        emp_mgt_db.dbo.t_time_in_out 
                    WHERE 
                        [day] BETWEEN ? AND ?";

		$stmt = $conn->prepare($query);
		$stmt->execute([$bak_date_from, $bak_date_to]);

        // Log Transfer Employee Management System Data to Backup Database

        $query = "INSERT INTO emp_mgt_backup.dbo.t_backup_logs 
                        (
                            date_from
                            ,date_to
                            ,backup_by 
                        ) 
                    VALUES 
                        (?, ?, ?)";

		$stmt = $conn->prepare($query);
		$stmt->execute([$bak_date_from, $bak_date_to, $backup_by]);
        
        $conn->commit();
		$isTransactionActive = false;
		echo 'success';
	} catch (Exception $e) {
		if ($isTransactionActive) {
			$conn->rollBack();
			$isTransactionActive = false;
		}
		echo 'Failed. Please Try Again or Call IT Personnel Immediately!: ' . $e->getMessage();
		exit();
	}
}

if ($method == 'get_recent_backup_logs') {
    $c = 0;

    $sql = "SELECT TOP 10 
                date_from, 
                date_to, 
                date_backup, 
                backup_by 
            FROM 
                emp_mgt_backup.dbo.t_backup_logs 
            ORDER BY 
                date_backup DESC";

    $stmt = $conn->prepare($sql);
	$stmt->execute();

	while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) { 
		$c++;

        echo '<tr>';
		echo '<td>'.$c.'</td>';
		echo '<td>'.$row['date_from'].'</td>';
		echo '<td>'.$row['date_to'].'</td>';
		echo '<td>'.$row['date_backup'].'</td>';
		echo '<td>'.$row['backup_by'].'</td>';
		echo '</tr>';
    }
}

$conn = null;

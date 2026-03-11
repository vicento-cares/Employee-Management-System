<?php
    function approve_email($submission_id, $approve_key) {
        $system = '/emp_mgt';
        return <<<HTML
            <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Employee Management System - Employee Transfer Approval</title>
                    <style>
                        body {
                            font-family: "Helvetica Neue", Arial, sans-serif;
                            margin: 0;
                            padding: 0;
                            background-color: #f4f6f9;
                        }
                        .container {
                            width: 100%;
                            max-width: 600px;
                            margin: 0 auto;
                            background-color: #ffffff;
                            padding: 20px;
                            border-radius: 5px;
                            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                        }
                        .row {
                        	padding: 1px;
                        	background-color: #343a40;
                            margin-right: -7.5px;
                            margin-left: -7.5px;
                            flex-wrap: wrap;
                            justify-content: center;
                            align-items: center;
                        }
                        h1 {
                            color: #f8f9fa;
                            text-align: center;
                        }
                        p {
                            color: #6c757d;
                            line-height: 1.25;
                        }
                        .footer {
                            margin-top: 20px;
                            font-size: 12px;
                            color: #888888;
                        }
                    </style>
                </head>
                <div class="container">
                    <div class="row">
                    	<h1>Employee Management System - <br> Employee Transfer Approval</h1>
                    </div>
                    <p>
                        Good day, Health and Safety First!
                    </p>
                    <p>
                        You are receiving this email because your Employee Management System account / email address has a pending employee transfer approval.
                    </p>
                    <p>
                        To view and approve pending employee transfer, please click the link below:
                    </p>
                    <p>
                        <a href="http://{$_SERVER['HTTP_HOST']}{$system}/approval/employee_transfer/index.php?submission_id={$submission_id}&approve_key={$approve_key}">View Employee Transfer</a>
                    </p>
                    <hr>
                    <div class="footer">
                        <p>This is an auto generated email. Do not reply</p>
                        <p>Do not share this email as it contains a custom key for approving employee transfer under your email address.</p>
                        <br>
                        <p>submission_id:{$submission_id}</p>
                        <p>approve_key:{$approve_key}</p>
                        <p>IT - SYSTEM GROUP</p>
                        <p>Furukawa Automotive Systems, Lima, Philippines</p>
                    </div>
                </div>
            </html>
        HTML;
    }

    function receiving_mp_email($submission_id, $approve_key) {
        $system = '/emp_mgt';
        return <<<HTML
            <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Employee Management System - Employee Transferred List</title>
                    <style>
                        body {
                            font-family: "Helvetica Neue", Arial, sans-serif;
                            margin: 0;
                            padding: 0;
                            background-color: #f4f6f9;
                        }
                        .container {
                            width: 100%;
                            max-width: 600px;
                            margin: 0 auto;
                            background-color: #ffffff;
                            padding: 20px;
                            border-radius: 5px;
                            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                        }
                        .row {
                        	padding: 1px;
                        	background-color: #343a40;
                            margin-right: -7.5px;
                            margin-left: -7.5px;
                            flex-wrap: wrap;
                            justify-content: center;
                            align-items: center;
                        }
                        h1 {
                            color: #f8f9fa;
                            text-align: center;
                        }
                        p {
                            color: #6c757d;
                            line-height: 1.25;
                        }
                        .footer {
                            margin-top: 20px;
                            font-size: 12px;
                            color: #888888;
                        }
                    </style>
                </head>
                <div class="container">
                    <div class="row">
                    	<h1>Employee Management System - <br> Employee Transferred List</h1>
                    </div>
                    <p>
                        Good day, Health and Safety First!
                    </p>
                    <p>
                        You are receiving this email because some manpower from other department or section filed employee transfer on Employee Management System to transfer on your department/section.
                    </p>
                    <p>
                        To view all employee transferred, please click the link below:
                    </p>
                    <p>
                        <a href="http://{$_SERVER['HTTP_HOST']}{$system}/approval/employee_transfer/transferred.php?submission_id={$submission_id}&approve_key={$approve_key}">View Employee Transfer</a>
                    </p>
                    <hr>
                    <div class="footer">
                        <p>This is an auto generated email. Do not reply</p>
                        <p>Do not share this email as it contains a custom key for approving employee transfer under your email address.</p>
                        <br>
                        <p>submission_id:{$submission_id}</p>
                        <p>approve_key:{$approve_key}</p>
                        <p>IT - SYSTEM GROUP</p>
                        <p>Furukawa Automotive Systems, Lima, Philippines</p>
                    </div>
                </div>
            </html>
        HTML;
    }

    function complete_approval_email() {
        return <<<HTML
            <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Employee Management System - Employee Transfer Approval</title>
                    <style>
                        body {
                            font-family: "Helvetica Neue", Arial, sans-serif;
                            margin: 0;
                            padding: 0;
                            background-color: #f4f6f9;
                        }
                        .container {
                            width: 100%;
                            max-width: 600px;
                            margin: 0 auto;
                            background-color: #ffffff;
                            padding: 20px;
                            border-radius: 5px;
                            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                        }
                        .row {
                        	padding: 1px;
                        	background-color: #343a40;
                            margin-right: -7.5px;
                            margin-left: -7.5px;
                            flex-wrap: wrap;
                            justify-content: center;
                            align-items: center;
                        }
                        h1 {
                            color: #f8f9fa;
                            text-align: center;
                        }
                        p {
                            color: #6c757d;
                            line-height: 1.25;
                        }
                        .footer {
                            margin-top: 20px;
                            font-size: 12px;
                            color: #888888;
                        }
                    </style>
                </head>
                <div class="container">
                    <div class="row">
                    	<h1>Employee Management System - <br> Employee Transfer Approval</h1>
                    </div>
                    <p>
                        Good day, Health and Safety First!
                    </p>
                    <p>
                        You are receiving this email because an employee transfer approval have completed its signatories and is successfully transferred to destinated line on Employee Management System.
                    </p>
                    <hr>
                    <div class="footer">
                        <p>This is an auto generated email. Do not reply</p>
                        <p>Do not share this email as it contains a custom key for approving employee transfer under your email address.</p>
                        <br>
                        <p>IT - SYSTEM GROUP</p>
                        <p>Furukawa Automotive Systems, Lima, Philippines</p>
                    </div>
                </div>
            </html>
        HTML;
    }

    function complete_disapproval_email() {
        return <<<HTML
            <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Employee Management System - Employee Transfer Approval</title>
                    <style>
                        body {
                            font-family: "Helvetica Neue", Arial, sans-serif;
                            margin: 0;
                            padding: 0;
                            background-color: #f4f6f9;
                        }
                        .container {
                            width: 100%;
                            max-width: 600px;
                            margin: 0 auto;
                            background-color: #ffffff;
                            padding: 20px;
                            border-radius: 5px;
                            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                        }
                        .row {
                        	padding: 1px;
                        	background-color: #343a40;
                            margin-right: -7.5px;
                            margin-left: -7.5px;
                            flex-wrap: wrap;
                            justify-content: center;
                            align-items: center;
                        }
                        h1 {
                            color: #f8f9fa;
                            text-align: center;
                        }
                        p {
                            color: #6c757d;
                            line-height: 1.25;
                        }
                        .footer {
                            margin-top: 20px;
                            font-size: 12px;
                            color: #888888;
                        }
                    </style>
                </head>
                <div class="container">
                    <div class="row">
                    	<h1>Employee Management System - <br> Employee Transfer Approval</h1>
                    </div>
                    <p>
                        Good day, Health and Safety First!
                    </p>
                    <p>
                        You are receiving this email because an employee transfer approval have been disapproved. See more details under the Employee Management System.
                    </p>
                    <hr>
                    <div class="footer">
                        <p>This is an auto generated email. Do not reply</p>
                        <p>Do not share this email as it contains a custom key for approving employee transfer under your email address.</p>
                        <br>
                        <p>IT - SYSTEM GROUP</p>
                        <p>Furukawa Automotive Systems, Lima, Philippines</p>
                    </div>
                </div>
            </html>
        HTML;
    }

    function send_mail($mail_arr, $conn_mailer) {
        $email_code = 'EMP-MGT-SYS';
        $email_subject = 'Employee Management System';
        $email_subject_title = 'Employee Transfer Approval';
        $email_body = '';
        $approve_email_opt = intval($mail_arr['approve_email_opt']);

        switch ($approve_email_opt) {
            case 0:
                $email_body = complete_disapproval_email();
                break;
            case 1:
                $email_body = complete_approval_email();
                break;
            case 2:
                $email_body = approve_email($mail_arr['emp_transfer_batch_id'], $mail_arr['approve_key']);
                break;
            case 3:
                $email_body = receiving_mp_email($mail_arr['emp_transfer_batch_id'], $mail_arr['approve_key']);
                $email_subject_title = 'Employee Transferred List';
                break;
            
            default:
                $email_body = approve_email($mail_arr['emp_transfer_batch_id'], $mail_arr['approve_key']);
                break;
        }

        $data = [
            "system_name" => $email_code,
            "send_to" => $mail_arr['sendto'],
            "cc" => "vince.dale.alcantara@furukawaelectric.com",
            "subject" => $email_subject . " : " . $email_subject_title,
            "body" => $email_body
        ];
        $stmt = $conn_mailer -> prepare("EXEC mail_send_mail_basic
            :system_name,
            :send_to,
            :cc,
            :subject,
            :body
        ");
        $stmt -> execute($data);
    }
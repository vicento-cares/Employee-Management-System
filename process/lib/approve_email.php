<?php
    $email_code = 'EMP-MGT-SYS';
    $email_subject = 'Employee Management System';

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
                            font-family: Arial, sans-serif;
                            margin: 0;
                            padding: 0;
                            background-color: #f4f4f4;
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
                        h1 {
                            color: #333333;
                        }
                        p {
                            color: #555555;
                            line-height: 1.5;
                        }
                        .footer {
                            margin-top: 20px;
                            font-size: 12px;
                            color: #888888;
                        }
                    </style>
                </head>
                <div class="container">
                    <h1>Employee Management System - Employee Transfer Approval</h1>
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
                            font-family: Arial, sans-serif;
                            margin: 0;
                            padding: 0;
                            background-color: #f4f4f4;
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
                        h1 {
                            color: #333333;
                        }
                        p {
                            color: #555555;
                            line-height: 1.5;
                        }
                        .footer {
                            margin-top: 20px;
                            font-size: 12px;
                            color: #888888;
                        }
                    </style>
                </head>
                <div class="container">
                    <h1>Employee Management System - Employee Transfer Approval</h1>
                    <p>
                        Good day, Health and Safety First!
                    </p>
                    <p>
                        You are receiving this email because an employee transfer approval have completed its signatories and is successfully transferred to destinated line on Employee Management System.
                    </p>
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
                            font-family: Arial, sans-serif;
                            margin: 0;
                            padding: 0;
                            background-color: #f4f4f4;
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
                        h1 {
                            color: #333333;
                        }
                        p {
                            color: #555555;
                            line-height: 1.5;
                        }
                        .footer {
                            margin-top: 20px;
                            font-size: 12px;
                            color: #888888;
                        }
                    </style>
                </head>
                <div class="container">
                    <h1>Employee Management System - Employee Transfer Approval</h1>
                    <p>
                        Good day, Health and Safety First!
                    </p>
                    <p>
                        You are receiving this email because an employee transfer approval have been disapproved. See more details under the Employee Management System.
                    </p>
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
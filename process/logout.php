<?php
session_name("emp_mgt");
session_start();

session_unset();
session_destroy();
header('location:/emp_mgt/admin');
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code



//User defined Constants
define('YEAR', '2026-27');
define('YEAR_START', '2026');
define('YEAR_START_DATE', '2026-04-01');
define('YEAR_FIRST_MONTH_END', '2026-04-30');
define('YEAR_END', '2027');
define('YEAR_END_DATE', '2027-03-31');

define('COMPANY_NAME', 'SHILPA OVERSEAS PVT. LTD.');
define('COMPANY_ADDRESS', 'KAIKHALI, CHIRIAMORE,P.O. : R.GOPALPUR, KOLKATA - 700 136');
define('COMPANY_PHONE', '+91 2573-3470/71/72/2405');

define('WEBSITE_NAME', 'Shilpa Overseas');
define('WEBSITE_NAME_SHORT', 'SOPL');

define('BANK_DETAILS', 'BANK NAME : HDFC BANK LIMITED, BANK ADDRESS : 9B, HINDUSTHAN ROAD, KOLKATA-700029 WEST BENGAL , INDIA ACCOUNT NO. : 50200041679309 AUTHORISED DEALER CODE : 0512619 1000009 , SWIFT CODE : HDFCINBB');

// invoice + packing list details
define('HEADER_ADDRESS', '51, MAHANIRBAN ROAD, KOLKATA-700029, INDIA');
define('HEADER_FACTORY_ADDRESS', 'KAIKHALI, CHIRIAMORE, GOPALPUR, KOLKATA - 700136, WEST BENGAL, INDIA');
define('HEADER_TEL', '+91-33-40031411, 40031412');
define('HEADER_FAX', '+91-33-40012865');
define('HEADER_EMAIL', 'anurupa.sengupta@shilpaoverseas.com');
define('HEADER_CIN', 'U19116WB1992PTC055524');


//mailing details
define('default_smtp_host', 'shilpaoverseas.com');
define('default_smtp_port', '465');
define('default_smtp_user', 'noreply@shilpaoverseas.com');
define('default_smtp_pass', 'x;92qOB_*Q};');
define('default_mail_from', 'noreply@shilpaoverseas.com');
define('default_mailer_name', 'Shilpa Overseas Pvt. Ltd.');
define('default_mail_sub', 'System Notification');

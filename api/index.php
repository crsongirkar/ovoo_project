<?php

/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 */
define('ENVIRONMENT', 'production');

// Redirect to installer if pre-installation
if (ENVIRONMENT === 'pre_installation') {
    $domain = $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
    $domain = preg_replace('/index.php.*/', '', $domain); // Remove everything after index.php
    $protocol = (!empty($_SERVER['HTTPS'])) ? 'https://' : 'http://';
    header("Location: " . $protocol . $domain . 'install/index.php');
    exit;
}

/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 */
switch (ENVIRONMENT)
{
    case 'development':
		error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED & ~E_NOTICE & ~E_WARNING);
		ini_set('display_errors', 0);
        break;

    case 'testing':
    case 'production':
        ini_set('display_errors', 0);
        if (version_compare(PHP_VERSION, '5.3', '>=')) {
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        } else {
            error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
        }
        break;

    default:
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        echo 'The application environment is not set correctly.';
        exit(1); // EXIT_ERROR
}

/*
 *---------------------------------------------------------------
 * SYSTEM FOLDER NAME
 *---------------------------------------------------------------
 */
$system_path = 'system';

/*
 *---------------------------------------------------------------
 * APPLICATION FOLDER NAME
 *---------------------------------------------------------------
 */
$application_folder = 'application';

/*
 *---------------------------------------------------------------
 * VIEW FOLDER NAME
 *---------------------------------------------------------------
 */
$view_folder = '../application/views';

/*
 *---------------------------------------------------------------
 * Resolve system path
 *---------------------------------------------------------------
 */
if (defined('STDIN')) {
    chdir(dirname(__FILE__));
}

if (($_temp = realpath($system_path)) !== FALSE) {
    $system_path = $_temp . '/';
} else {
    $system_path = rtrim($system_path, '/') . '/';
}

if (!is_dir($system_path)) {
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo 'Your system folder path is not set correctly. Please fix: ' . pathinfo(__FILE__, PATHINFO_BASENAME);
    exit(3); // EXIT_CONFIG
}

/*
 *---------------------------------------------------------------
 * Main path constants
 *---------------------------------------------------------------
 */
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('BASEPATH', str_replace('\\', '/', $system_path));
define('FCPATH', dirname(__FILE__) . '/');
define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));

// Application path
if (is_dir($application_folder)) {
    if (($_temp = realpath($application_folder)) !== FALSE) {
        $application_folder = $_temp;
    }
    define('APPPATH', $application_folder . DIRECTORY_SEPARATOR);
} else {
    if (!is_dir(BASEPATH . $application_folder . DIRECTORY_SEPARATOR)) {
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        echo 'Your application folder path is not set correctly. Please fix: ' . SELF;
        exit(3); // EXIT_CONFIG
    }
    define('APPPATH', BASEPATH . $application_folder . DIRECTORY_SEPARATOR);
}

// View path
if (!is_dir($view_folder)) {
    if (!empty($view_folder) && is_dir(APPPATH . $view_folder . DIRECTORY_SEPARATOR)) {
        $view_folder = APPPATH . $view_folder;
    } elseif (!is_dir(APPPATH . 'views' . DIRECTORY_SEPARATOR)) {
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        echo 'Your view folder path is not set correctly. Please fix: ' . SELF;
        exit(3); // EXIT_CONFIG
    } else {
        $view_folder = APPPATH . 'views';
    }
}

if (($_temp = realpath($view_folder)) !== FALSE) {
    $view_folder = $_temp . DIRECTORY_SEPARATOR;
} else {
    $view_folder = rtrim($view_folder, '/\\') . DIRECTORY_SEPARATOR;
}

define('VIEWPATH', $view_folder);

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 *---------------------------------------------------------------
 */
require_once BASEPATH . 'core/CodeIgniter.php';

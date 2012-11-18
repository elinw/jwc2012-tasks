<?php
/**
 * Distribution bootstrap file for the Demo Services application.
 *
 * @package    Demo.Services
 *
 * @copyright  Copyright (C) 2012 OpenSourceMatters. All rights reserved.
 */

error_reporting(-1);
ini_set('display_errors', 1);


// Define the application home directory.
$DEMOHOME = getenv('DEMO_HOME') ? getenv('DEMO_HOME') : dirname(__DIR__);

// This should be the standard runtime configuration.
if (file_exists($DEMOHOME . '/lib/joomla.phar'))
{
	require $DEMOHOME . '/lib/joomla.phar';
}
// Let's go looking for the Joomla Platform in a few possible locations.
else
{
	$JPLATFORMHOME = getenv('JPLATFORM_HOME') ? getenv('JPLATFORM_HOME') : dirname(dirname(__DIR__)) . '/joomla/libraries';

	// It's more than likely we are dealing with the expanded platform in this situation.
	if (file_exists($JPLATFORMHOME . '/import.php'))
	{
		require $JPLATFORMHOME . '/import.php';
	}
	// Check to make sure we aren't dealing with a Phar location though.
	elseif (file_exists($JPLATFORMHOME . '/joomla.phar'))
	{
		require $JPLATFORMHOME . '/joomla.phar';
	}
}

// Ensure that required path constants are defined.
if (!defined('JPATH_BASE'))
{
	define('JPATH_BASE', realpath(__DIR__));
}
if (!defined('JPATH_SITE'))
{
	define('JPATH_SITE', JPATH_BASE);
}
if (!defined('JPATH_CACHE'))
{
	define('JPATH_CACHE', '/tmp/cache');
}
if (!defined('JPATH_CONFIGURATION'))
{
	define('JPATH_CONFIGURATION', $DEMOHOME . '/etc');
}

try
{
	// This should be the standard runtime configuration.
	if (file_exists($DEMOHOME . '/bin/services.phar'))
	{
		require $DEMOHOME . '/bin/services.phar';
	}
	// Alternatively we may have the expanded filesystem option.
	elseif (file_exists($DEMOHOME . '/src/import.php'))
	{
		require $DEMOHOME . '/src/import.php';
	}

	// Instantiate the application.
	$application = JApplicationWeb::getInstance('DemoApplicationWeb');

	// Store the application.
	JFactory::$application = $application;

	// Execute the application.
	$application->loadSession()
		->loadDatabase()
		->loadRouter()
		->execute();
}
catch (Exception $e)
{
	// Set the server response code.
	header('Status: 500', true, 500);

	// An exception has been caught, echo the message and exit.
	echo json_encode(array('message' => $e->getMessage(), 'code' => $e->getCode(), 'type' => get_class($e)));
	exit();
}

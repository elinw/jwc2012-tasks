<?php
/**
 * Application stub file for the demo application.
 *
 * @package    Demo.Services
 *
 * @copyright  Copyright (C) 2012 OpenSourceMatters. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// Make sure that the Joomla Platform has been successfully loaded.
if (!class_exists('JLoader'))
{
	throw new RuntimeException('Joomla Platform not loaded.');
}

// Setup the autoloader for the application classes.
JLoader::registerPrefix('Demo', __DIR__ . '/demo');


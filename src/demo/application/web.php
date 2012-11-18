<?php
/**
 * @package    Demo.Services
 *
 * @copyright  Copyright (C) 2012 OpenSourceMatters. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Demo Web Application.
 *
 * @package  Demo.Services
 * @since    1.0
 */
class DemoApplicationWeb extends JApplicationWeb
{
	/**
	 * @var    JDatabaseDriver  A database object for the application to use.
	 * @since  1.0
	 */
	protected $db;

	/**
	 * @var    JApplicationWebRouter  A router object for the application to use.
	 * @since  1.0
	 */
	protected $router;

	/**
	 * @var    JCache  The application cache object.
	 * @since  1.0
	 */
	protected $cache;

	/**
	 * The start time for measuring the execution time.
	 *
	 * @var    float
	 * @since  1.0
	 */
	private $_startTime;

	/**
	 * Overrides the parent constructor to set the execution start time.
	 *
	 * @param   mixed  $input   An optional argument to provide dependency injection for the application's
	 *                          input object.  If the argument is a JInput object that object will become
	 *                          the application's input object, otherwise a default input object is created.
	 * @param   mixed  $config  An optional argument to provide dependency injection for the application's
	 *                          config object.  If the argument is a JRegistry object that object will become
	 *                          the application's config object, otherwise a default config object is created.
	 * @param   mixed  $client  An optional argument to provide dependency injection for the application's
	 *                          client object.  If the argument is a JApplicationWebClient object that object will become
	 *                          the application's client object, otherwise a default client object is created.
	 *
	 * @since   11.3
	 */
	public function __construct(JInput $input = null, JRegistry $config = null, JApplicationWebClient $client = null)
	{
		$this->_startTime = microtime(true);

		parent::__construct($input, $config, $client);
	}

	/**
	 * Permits retrieval of the database connection for this application.
	 *
	 * @return  JDatabaseDriver  The database driver.
	 *
	 * @since   12.1
	 */
	public function getDatabase()
	{
		return $this->db;
	}

	/**
	 * Allows the application to load a custom or default database driver.
	 *
	 * @param   JDatabaseDriver  $driver  An optional database driver object. If omitted, the application driver is created.
	 *
	 * @return  JApplicationBase This method is chainable.
	 *
	 * @since   12.1
	 */
	public function loadDatabase(JDatabaseDriver $driver = null)
	{
		if ($driver === null)
		{
			$this->db = JDatabaseDriver::getInstance(
				array(
					'driver' => $this->get('db_driver'),
					'host' => $this->get('db_host'),
					'user' => $this->get('db_user'),
					'password' => $this->get('db_pass'),
					'database' => $this->get('db_name'),
					'prefix' => $this->get('db_prefix'),
					'schema' => $this->get('db_schema'),
					'port' => $this->get('db_port')
				)
			);

			// Select the database.
			$this->db->select($this->get('db_name'));
		}
		// Use the given database driver object.
		else
		{
			$this->db = $driver;
		}

		// Set the database to our static cache.
		JFactory::$database = $this->db;

		return $this;
	}

	/**
	 * Allows the application to load a custom or default router.
	 *
	 * @param   JApplicationWebRouter  $router  An optional router object. If omitted, the standard router is created.
	 *
	 * @return  JApplicationWeb This method is chainable.
	 *
	 * @since   1.0
	 */
	public function loadRouter(JApplicationWebRouter $router = null)
	{
		$this->router = ($router === null) ? new JApplicationWebRouterBase($this, $this->input) : $router;

		return $this;
	}

	/**
	 * Execute the application.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function doExecute()
	{
		try
		{
			// Set the controller prefix, add maps, and execute the appropriate controller.
			$this->mimeType = 'application/json';
			$this->input = new JInputJson;
			$this->router = new JApplicationWebRouterRest($this, $this->input);
			$this->router->setControllerPrefix('DemoService')
				->setDefaultController('Test')
				->addMaps(json_decode(file_get_contents(JPATH_CONFIGURATION . '/services.json'), true))
				->execute($this->get('uri.route'));
		}
		catch (Exception $e)
		{
			$this->setHeader('status', '400', true);
			$message = $e->getMessage();
			$body = array('message' => $message, 'code' => $e->getCode(), 'type' => get_class($e));

			$this->setBody(json_encode($body));
		}
	}

	/**
	 * Method to get the application configuration data to be loaded.
	 *
	 * @param   string  $file   The path and filename of the configuration file. If not provided, configuration.php
	 *                          in JPATH_BASE will be used.
	 * @param   string  $class  The class name to instantiate.
	 *
	 * @return  object An object to be loaded into the application configuration.
	 *
	 * @since   1.0
	 */
	protected function fetchConfigurationData($file = '', $class = 'JConfig')
	{
		// Instantiate variables.
		$config = array();

		// Ensure that required path constants are defined.
		if (!defined('JPATH_CONFIGURATION'))
		{
			$path = getenv('DEMO_CONFIG');
			if ($path)
			{
				define('JPATH_CONFIGURATION', realpath($path));
			}
			else
			{
				define('JPATH_CONFIGURATION', realpath(dirname(JPATH_BASE) . '/config'));
			}
		}

		// Set the configuration file path for the application.
		if (file_exists(JPATH_CONFIGURATION . '/config.json'))
		{
			$file = JPATH_CONFIGURATION . '/config.json';
		}
		else
		{
			$file = JPATH_CONFIGURATION . '/config.dist.json';
		}

		if (!is_readable($file))
		{
			throw new RuntimeException('Configuration file does not exist or is unreadable.');
		}

		// Load the configuration file into an object.
		$config = json_decode(file_get_contents($file));

		return $config;
	}

	/**
	 * Method to send the application response to the client.  All headers will be sent prior to the main
	 * application output data.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function respond()
	{
		$runtime = microtime(true) - $this->_startTime;

		// Set the Server and X-Powered-By Header.
		$this->setHeader('Server', '', true);
		$this->setHeader('X-Powered-By', 'Awesome/1.0', true);
		$this->setHeader('X-Runtime', $runtime, true);

		parent::respond();
	}
}

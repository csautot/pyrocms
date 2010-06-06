<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author Yorick Peterse - PyroCMS Development Team
 * @package PyroCMS 
 * @subpackage Libraries
 * @category Libraries
 *
 * The plugins library handles the plugins system.
 */
class Plugins
{
	/**
	 * A copy of the CI instance
	 * 
	 * @var mixed
	 * @access public
	 */
	public $ci;
	
	/**
	 * Variable containing all active plugins
	 * 
	 * @var array
	 * @access private
	 */
	private $active_plugins;
	
	/**
	 * Constructor method 
	 * 
	 * @author Yorick Peterse
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		// Set the instance
		$this->ci =& get_instance();
		
		// Load all required classes
		$this->ci->load->model('plugins/plugins_m');
		
		// Get all activated plugins
		$this->active_plugins = $this->ci->plugins_m->get_many_by('active', 1);
	}
	
	/**
	 * Initialize all activated plugins
	 * 
	 * @author Yorick Peterse
	 * @access public
	 * @return array
	 */
	public function initialize_plugins()
	{
		// Array containing all initialized plugins
		$plugins = array();
		
		// Initialize each activated plugin
		foreach ( $this->active_plugins as $active_plugin )
		{
			$path = 'third_party/plugins/' . $active_plugin->slug . '/' . $active_plugin->slug . EXT;
			
			// Load the class if it exists
			if ( file_exists($path) )
			{
				require_once($path);
				$class_name = ucfirst($active_plugin->slug);
				
				// Add the class to the array
				$plugins[] = new $class_name;
			}		
			// File does not exist, log it to the logfiles
			else
			{
				log_message('error', 'The following plugin path does not exist: ' . $path);
			}	
		}
		
		// Return the array containing the initialized classes
		return $plugins;
	}
	
	/**
	 * Run all plugins for the specified hook
	 *
	 * @author Yorick Peterse
	 * @access public
	 * @param string $hook The hook of the plugin
	 * @param mixed $data The data sent to the plugin and then returned back to the system
	 * @return mixed
	 */
	public function run($hook, $data)
	{
		$plugins 		= $this->initialize_plugins();
		$return_data 	= array();

		// Any active plugins at all?
		if ( !empty($plugins) )
		{
			foreach ( $plugins as $plugin )
			{
				// Only continue if any hooks have been defined at all
				if ( empty($plugin->hooks) )
				{
					return $data;
				}
				
				// Loop through each hook and execute it
				foreach ( $plugin->hooks as $hook_name => $hook_method )
				{
					// The hook matches, execute it
					if ( $hook_name === $hook )
					{
						if ( !empty($return_data) )
						{
							// In case there are multiple plugins running on the same hook the system should return the data from the previous plugin, instead of the raw data
							$key 		= count($return_data) -1;
							$send_data 	= $return_data[$key];
						}
						else
						{
							// Use the raw data
							$send_data  = $data;
						}

						if ( method_exists($plugin, $hook_method) )
						{
							// Each method has it's return data stored in a new key in the return_data array.
							$result_data = call_user_func_array( array($plugin, $hook_method), array($send_data) );
							
							// Got anything returned?
							if ( !empty($result_data) )
							{
								$return_data[]	= $result_data;
							}
							else
							{
								$return_data[] = $data;
							}
						}			
						else
						{
							log_message('error', "The method \"$hook_method\" does not exist in plugin \"" . $plugin->title . "\"");
						}			
					} 
					// End of: if ( $hook_name === $hook )
					
				}
				// End of: foreach ( $plugin->hooks... )
			}
			
			// No plugins have been run for the current hook(s)
			if ( empty($return_data) )
			{
				return $data;
			}

			// Return the last key of the return_data array
			$key = count($return_data) -1;
			return $return_data[$key];
		}
		
		// Let's just return the normal data
		else
		{
			return $data;
		}
	}
	
	/**
	 * Get all options for the specified plugin
	 * 
	 * @author Yorick Peterse
	 * @access public
	 * @return mixed
	 */	
	public function get_options()
	{
		// Generate the slug based on the plugin's class name
		$slug = strtolower(str_replace('Plugins','', get_class($this)));
		
		// Loop through the array containing all activated plugins and see if there's one that matches the slug
		foreach ( $this->active_plugins as $plugin )
		{
			if ( $plugin->slug === $slug )
			{
				// Unserialize the options and return them
				return unserialize($plugin->options);
			}
		}
		
		// No options have been set so far
		return FALSE;
	}
	
	/**
	 * Update the options for a specific plugin
	 * 
	 * @author Yorick Peterse
	 * @access public
	 * @param mixed $data The data to serialize and store in the DB
	 * @return bool
	 */
	public function update_options($data)
	{
		// Generate the slug based on the plugin's class name
		$slug = strtolower(str_replace('Plugins','', get_class($this)));
		$data = serialize($data);
		
		// Store the data
		return $this->ci->db->where('slug', $slug)
							->update('plugins', array('options' => $data));
	}
}
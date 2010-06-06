<?php
/**
 * @package Hello World
 * @author Yorick Peterse
 * @license Apache License v2.0
 */
class Hello_world extends Plugins
{
	// Plugin information
	public $title 		= 'Hello World';
	public $description = 'An example of how plugins work';
	public $author 		= 'Yorick Peterse';
	public $website 	= 'http://yorickpeterse.com/';
	public $version 	= '1.0';
	
	// Which methods should be run and at what point?
	public $hooks		= array();
	
	/**
	 * Constructor method, called when the class is created
	 *
	 * @author Yorick Peterse
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		// Don't forget to call the parent's constructor first
		parent::__construct();
		
		$this->hooks = array(
			'galleries_index' => 'index_method'
		);
	}
	
	/**
	 * Example method
	 * 
	 * @author Yorick Peterse
	 * @access public
	 * @param mixed $data
	 * @return mixed
	 */
	public function index_method($data)
	{
		$return = array();
		
		foreach ( $data->galleries as $item )
		{
			$item->title = strtoupper($item->title);
			
			$return[] 	= $item;
		} 
		
		return $return;
	}
}
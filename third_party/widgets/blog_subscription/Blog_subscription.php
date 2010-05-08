<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @package 		PyroCMS
 * @subpackage 		Twitter Feed Widget
 * @author			Phil Sturgeon - PyroCMS Development Team
 * 
 * Show Twitter feeds in your site
 */

class Blog_subscription extends Widgets
{
	public $title = 'Blog Subscription';
	public $description = 'Display tweets from a user on Twitter.';
	public $author = 'Phil Sturgeon';
	public $website = 'http://philsturgeon.co.uk/';
	public $version = '1.0';
	
	public function run()
	{
		$this->load->model('blog/blog_entries_m');
		$this->lang->load('blog/blog');
				
	}
}
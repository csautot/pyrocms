<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @package 		PyroCMS
 * @subpackage 		Twitter Feed Widget
 * @author			Phil Sturgeon - PyroCMS Development Team
 * 
 * Show Twitter feeds in your site
 */

class Blog_archive extends Widgets
{
	public $title = 'Blog Archive';
	public $description = 'Display a list of previous blog posts.';
	public $author = 'Zack Kitzmiller';
	public $website = 'http://getcloudigniter.com/';
	public $version = '1.0';
	
	public function run($options)
	{
        $this->load->model('blog/blog_entries_m');
        $this->lang->load('blog/blog');
        		
		$archive_months = $this->blog_entries_m->get_archive_months(); 
		
		// Store the feed items
		return array(
			'archive_months'
		);
		
	}
}
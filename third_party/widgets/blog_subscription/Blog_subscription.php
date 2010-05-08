<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @package 		PyroCMS
 * @subpackage 		Blog Subscription Widget
 * @author			Zack Kitzmiller
 * 
 * RSS Link on your site
 */

class Blog_subscription extends Widgets
{
	public $title = 'Blog Subscription';
	public $description = 'Display RSS from blog module.';
	public $author = 'Zack Kitzmiller';
	public $website = 'http://getcloudigniter.com/';
	public $version = '1.0';
	
	public function run()
	{
		$this->load->model('blog/blog_entries_m');
		$this->lang->load('blog/blog');
				
	}
}
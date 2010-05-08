<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends Public_Controller
{
	public $limit = 10; // TODO: PS - Make me a settings option
	
	function __construct()
	{
		parent::Public_Controller();		
		$this->load->model('blog_entries_m');
		$this->load->model('categories/categories_m');
		$this->load->model('comments/comments_m');        
		$this->load->helper('text');
		$this->lang->load('blog');
		
		// All pages within news will display an archive list
		$this->data->archive_months = $this->blog_entries_m->get_archive_months();        
	}
	
	// news/page/x also routes here
	function index()
	{	
		$this->data->pagination = create_pagination('blog/page', $this->blog_entries_m->count_by(array('status' => 'live')), $this->limit, 3);	
		$this->data->entries = $this->blog_entries_m->limit($this->data->pagination['limit'])->get_many_by(array('status' => 'live'));	

		// Set meta description based on article titles
		$meta = $this->_entries_metadata($this->data->entries);

		$this->template->set_metadata('description', $meta['description']);
		$this->template->set_metadata('keywords', $meta['keywords']);
		$this->template->build('index', $this->data);
	}
	
	function category($slug = '')
	{	
		if(!$slug) redirect('blog');
		
		// Get category data
		$category = $this->categories_m->get_by('slug', $slug);
		
		if(!$category) show_404();
		
		$this->data->category =& $category;
		
		// Count total news articles and work out how many pages exist
		$this->data->pagination = create_pagination('blog/category/'.$slug, $this->blog_entries_m->count_by(array(
			'category'=>$slug,
			'status' => 'live'
		)), $this->limit, 4);
		
		// Get the current page of news articles
		$this->data->entries = $this->blog_entries_m->limit($this->data->pagination['limit'])->get_many_by(array(
			'category'=>$slug,
			'status' => 'live'
		));
		
		// Set meta description based on article titles
		$meta = $this->_entries_metadata($this->data->entries);
		
		// Build the page
		$this->template->title( lang('news_news_title'), $category->title )		
			->set_metadata('description', $category->title.'. '.$meta['description'] )
			->set_metadata('keywords', $category->title )
			->set_breadcrumb( lang('news_news_title'), 'blog')
			->set_breadcrumb( $category->title )		
			->build( 'category', $this->data );
	}	
	
	function archive($year = NULL, $month = '01')
	{	
		if(!$year) $year = date('Y');		
		$month_date = new DateTime($year.'-'.$month.'-01');
		$this->data->pagination = create_pagination('blog/archive/'.$year.'/'.$month, $this->blog_entries_m->count_by(array('year'=>$year,'month'=>$month)), $this->limit, 5);
		$this->data->entries = $this->blog_entries_m->limit($this->data->pagination['limit'])->get_many_by(array('year'=> $year,'month'=> $month));
		$this->data->month_year = $month_date->format("F 'y");
		
		// Set meta description based on article titles
		$meta = $this->_entries_metadata($this->data->entries);

		$this->template->title( $this->data->month_year, $this->lang->line('news_archive_title'), $this->lang->line('news_news_title'))		
			->set_metadata('description', $this->data->month_year.'. '.$meta['description'])
			->set_metadata('keywords', $this->data->month_year.', '.$meta['keywords'])
			->set_breadcrumb($this->lang->line('news_news_title'), 'blog')
			->set_breadcrumb($this->lang->line('news_archive_title').': '.$month_date->format("F 'y"))
			->build('archive', $this->data);
	}
	
	// Public: View an article
	function view($slug = '')
	{	
		if (!$slug or !$entry = $this->blog_entries_m->get_by('slug', $slug))
		{
			redirect('blog');
		}
		
		if($entry->status != 'live' && !$this->ion_auth->is_admin())
		{
			redirect('blog');
		}
		
		// IF this article uses a category, grab it
		if($entry->category_id > 0)
		{
			$entry->category = $this->categories_m->get( $entry->category_id );
		}
		
		// Set some defaults
		else
		{
			$entry->category->id = 0;
			$entry->category->slug = '';
			$entry->category->title = '';
		}
		
		$this->session->set_flashdata(array('referrer'=>$this->uri->uri_string));	
		
		$this->data->entry =& $entry;

		$this->template->title($entry->title, $this->lang->line('news_news_title'))
			->set_metadata('description', $this->data->entry->intro)
			->set_metadata('keywords', $this->data->entry->category->title.' '.$this->data->entry->title)	
			->set_breadcrumb($this->lang->line('news_news_title'), 'blog');
		
		if($entry->category_id > 0)
		{
			$this->template->set_breadcrumb($entry->category->title, 'blog/category/'.$entry->category->slug);
		}
		
		$this->template->set_breadcrumb($entry->title, 'blog/' . $entry->slug);
		$this->template->build('view', $this->data);
	}	
	
	// Private methods not used for display
	private function _entries_metadata(&$articles = array())
	{
		$keywords = array();
		$description = array();
		
		// Loop through articles and use titles for meta description
		if(!empty($articles))
		{
			foreach($articles as &$article)
			{
				if($article->category_title)
				{
					$keywords[$article->category_id] = $article->category_title .', '. $article->category_slug;
				}
				$description[] = $article->title; 
			}
		}
		
		return array(
			'keywords' => implode(', ', $keywords),
			'description' => implode(', ', $description)
		);
	}
}
?>
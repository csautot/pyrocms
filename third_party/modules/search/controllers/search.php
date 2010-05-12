<?php
class Search extends Public_Controller {

	public function q($query)
	{
		$this->load->library('search_lib');

		$results = $this->search_lib->search($query);

		foreach($results as &$result)
		{
			$result->search_desc = $this->search_lib->fix_desc($result->search_desc, $query);
			$result->search_desc = preg_replace("/($query)/i", "<strong>$1</strong>", $result->search_desc);
			$result->search_title = preg_replace("/($query)/i", "<strong>$1</strong>", $result->search_title);
		}

		$this->data->results = $results;

		$this->template->enable_parser_body(FALSE);

		$this->template->build('results', $this->data);
	}

}
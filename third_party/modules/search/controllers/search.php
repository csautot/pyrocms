<?php
class Search extends Public_Controller {

	public function q($query)
	{
		$this->load->library('search_lib');

		$results = $this->search_lib->search($query);

		foreach($results as &$result)
		{
			$result->search_desc = str_replace($query, '<strong>' . $query . '</strong>', htmlentities($result->search_desc));
		}

		$this->data->results = $results;

		$this->template->enable_parser_body(FALSE);

		$this->template->build('results', $this->data);
	}

}
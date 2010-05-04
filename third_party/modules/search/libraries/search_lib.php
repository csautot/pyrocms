<?php

class Search_lib
{
	private $ci;

	public function  __construct()
	{
		$this->ci =& get_instance();
	}

	public function search($q)
	{
		$sql = "SELECT slug AS id, title AS search_title, body AS search_desc, CONCAT('/',slug) AS search_link FROM pages WHERE body LIKE '%:query%'\n";
		$sql .= "UNION ALL\n";
		$sql .= "SELECT id, title AS search_title, content AS search_desc, CONCAT('/forums/posts/view_reply/',id) AS search_link FROM forum_posts WHERE content LIKE '%:query%'";

		$sql = str_replace(':query', $q, $sql);
		
		return $this->ci->db->query($sql)->result();
		
	}

}
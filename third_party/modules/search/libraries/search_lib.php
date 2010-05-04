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
		$sql = "(SELECT slug AS id,
						title AS search_title,
						body AS search_desc,
						CONCAT('/',slug) AS search_link,
						((1.3 * (MATCH(title) AGAINST (':query' IN BOOLEAN MODE)))
						 + (0.6 * (MATCH(body) AGAINST (':query' IN BOOLEAN MODE)))
						) AS relevance
						FROM pages
						WHERE MATCH (title,body)
						AGAINST (':query' IN BOOLEAN MODE))\n";

		$sql .= "UNION ALL\n";
		$sql .= "(SELECT id,
						 title AS search_title,
						 content AS search_desc,
						 CONCAT('/forums/posts/view_reply/',id) AS search_link,
						 ((1.3 * (MATCH(title) AGAINST (':query' IN BOOLEAN MODE)))
						  + (0.6 * (MATCH(content) AGAINST (':query' IN BOOLEAN MODE)))
						 ) AS relevance
						 FROM forum_posts
						 WHERE MATCH (title,content)
						 AGAINST (':query' IN BOOLEAN MODE))\n";
		$sql .= "UNION ALL\n";
		$sql .= "(SELECT slug AS id,
						 title AS search_title,
						 body AS search_desc,
						 CONCAT('/news/', YEAR(FROM_UNIXTIME(created_on)),'/',MONTH(FROM_UNIXTIME(created_on)),'/',slug) AS search_link,
						 ((1.3 * (MATCH(title) AGAINST (':query' IN BOOLEAN MODE)))
						  + (0.6 * (MATCH(body) AGAINST (':query' IN BOOLEAN MODE)))
						 ) AS relevance
						 FROM news
						WHERE (MATCH (title,body)
						AGAINST (':query' IN BOOLEAN MODE)) AND status = 'live')\n";
		$sql .= "ORDER BY relevance DESC;";

		$sql = str_replace(':query', '+' . $q, $sql);
		echo $sql;
		return $this->ci->db->query($sql)->result();
		
	}

	public function fix_desc($desc, $query)
	{
		$desc = html_entity_decode(strip_tags($desc));

		return $this->excerpt(htmlentities($desc), $query, 120);
	}

	function excerpt($text, $phrase, $radius = 100, $ending = "...")
	{

		$phrase_len = strlen($phrase);
		if ($radius < $phrase_len)
		{
			$radius = $phrase_len;
		}

		$pos = stripos($text, $phrase);

		$start = 0;
		if ($pos > $radius)
		{
			$start = $pos - $radius;
		}

		$text_len = strlen($text);

		$end = $pos + $phrase_len + $radius;

		if ($end >= $text_len)
		{
			$end = $text_len;
		}

		$excerpt = substr($text, $start, $end - $start);

		if ($start != 0)
		{
			$excerpt = substr_replace($excerpt, $ending, 0, $phrase_len);
		}

		if ($end != $text_len) {
			$excerpt = substr_replace($excerpt, $ending, -$phrase_len);
		}

		return $excerpt;
	}

}
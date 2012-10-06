<?
require_once "Utils.php";

 class Topic{
	 	private $topic_id;
		private $wpdb;
		private $utils;
        public function Topic($topic_id="",$wpdb){
			$this->topic_id = $topic_id;
			$this->wpdb = $wpdb;
			$this->utils = new Utils();
			}

        public function getTopicList($site_id = 1, $category_id="",$alert="",$twitter="",$need_topic_id=false,$has_article="-1"){
			$append_where = "";
			if($category_id!=""){ $append_where .= " AND category_id=".$category_id; } 
			if($twitter!=""){ $append_where .= " AND twitter=".$twitter; } 
			if($alert!=""){ $append_where .= " AND alert=".$alert; } 
			if($need_topic_id){ $add_topic_id = ", topic_id";}
			if($has_article>-1){ 
				$append_where .= " AND has_article=".$has_article;
			}
			$query = "SELECT DISTINCT topic_title ".$add_topic_id." FROM topic WHERE site=$site_id  $append_where  and active=1 ORDER BY topic_title";
			
			
			$results = $this->wpdb->get_results( $query);
			$results = $this->utils->convertMysqlResultToArray($results);
			return $results;
        }
		
        public function getTopicCategoryIDsBySite($site_id = 1){
			$query = "SELECT * FROM topic_category WHERE site=$site_id  $append_where  and active=1";
			//echo $query;
			
			$results = $this->wpdb->get_results( $query);
			$results = $this->utils->convertMysqlResultToArray($results);
			return $results;
        }
        public function getTopicCategoryByCategory($category_id = 1){
			$query = "SELECT * FROM topic_category WHERE topic_category_id=$category_id  $append_where  and active=1";
			//echo $query;
			
			$results = $this->wpdb->get_results( $query);
			$results = $this->utils->convertMysqlResultToArray($results);
			return $results;
        }
		
        public function getTopicCategoryAttr($topic_category_id = 1, $name="keywords"){
			$query = "SELECT * FROM topic_category WHERE topic_category_id=$topic_category_id and active=1 LIMIT 0,1";
			$results = $this->wpdb->get_results( $query);
			$results = $this->utils->convertMysqlResultToArray($results);
			return $results[0][$name];
        }
		
		public function updateTopicCategoryCache($topic_category_id,$last_cache,$post_ids){
			// 	 	
			$next_cache  = mktime(date("H"), date("i") , date("s"), date("m")  , date("d")+1, date("Y"));
			$next_cache  = date("Y-m-d H:i:s", $next_cache );
					$this->wpdb->query( "UPDATE topic_category SET last_cache = '".$last_cache."', next_cache = '".$next_cache."', cached_ids = '".$post_ids."' WHERE topic_category_id = $topic_category_id ");
			}

 }
?>
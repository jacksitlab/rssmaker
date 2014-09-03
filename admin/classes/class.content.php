<?php

class ContentCategory
{
	public $id;
	public $title;
	
	public function __construct($id = 0,$title='')
	{
		$this->id = $id;
		$this->title = $title;
	}
	public static function GetAll()
	{
		$db = &JFactory::getDBO();
		$query=$db->getQuery(true);
		$query="SELECT id,title FROM #__categories WHERE extension='com_content'";
		$db->setQuery($query);
		return $db->LoadObjectList();
	}
}

class Content
{
	public $title;
	public $link;
	public $text;
	public $publish_up;
	public function __construct($title='',$link='',$text='',$pub)
	{
		$this->title = $title;
		$this->link = $link;
		$this->text = $this->rssclean($text);
		$this->publish_up = $pub;
	}
	public static function GetContent($cat_id=0,$num=0,$orderby='publish_up DESC')
	{
		$db = &JFactory::getDBO();
		$query=$db->getQuery(true);
		$query='SELECT id,title,introtext AS text,publish_up '.
		'FROM #__content '.
		'WHERE catid = '.$cat_id.' '.
		'AND publish_up<=CURRENT_TIMESTAMP '.
		'AND (CURRENT_TIMESTAMP<=publish_down OR publish_down=\'0000-00-00 00:00:00\') '.
		'AND STATUS = 1 '.
		'ORDER BY '.$orderby;
		if($num>0)
		$query.=' LIMIT '.$num;	
		$db->setQuery($query);
		$data= $db->LoadObjectList();
		$outp=array();
		if(is_array($data) && count($data)>0)
		{
			foreach($data as $row)
			{
				$outp[]=new Content($row->title,
					JURI::base().'index.php?option=com_content&id='.$row->id,
					$row->text,
					$row->publish_up);
			}
		
		}
		return $outp;
	}
	private function rssclean($text)
	{
		$s= preg_replace('/(<[^>]+) style=".*?"/i', '$1',$text);
		$s=$this->relToAbs2($s,JURI::base());
		return $s;
	}
	private function relToAbs($text, $base)
	{
		if (empty($base))
		return $text;
		// base url needs trailing /
		if (substr($base, -1, 1) != "/")
			$base .= "/";
		// Replace links
		$pattern = "/<a([^>]*) href=\"[^http|ftp|https|mailto]([^\"]*)\"/";
		$replace = "<a\${1} href=\"" . $base . "\${2}\"";
		$text = preg_replace($pattern, $replace, $text);
		// Replace images
		//
		$pattern = '/<img.+?src=[\"\'](.+?)[\"\'].*?>/';
		$replace = "<img\${1} src=\"" . $base . "\${2}\"";
		$text = preg_replace($pattern, $replace, $text);
		// Done
		return $text;
	}
	private function relToAbs2($text,$base)
	{
		if (empty($base))
			return $text;
		$pattern = "#(<\s*a\s+[^>]*href\s*=\s*[\"'])(?!http)([^\"'>]+)([\"'>]+)#";
		$text=preg_replace($pattern, '$1'.$base.'$2$3', $text);
		$pattern = "#(<\s*img\s+[^>]*src\s*=\s*[\"'])(?!http)([^\"'>]+)([\"'>]+)#";
		$text=preg_replace($pattern, '$1'.$base.'$2$3', $text);
	
		return $text;
	}
}
?>

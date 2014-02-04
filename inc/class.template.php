<?php
/**
	Credit http://www.addedbytes.com/for-beginners/output-caching-for-beginners/ for the CACHING METHODS
**/
class Template {
	var $metatags = '';
	var $output = '';
	var $content = '';
	var $error = '';
	var $notice = '';
	var $success = '';
	var $info = '';
	var $widget = '';
	var $ADD_WIDGET = true;
	var $CONTENT_GRID = 9;
	// Settings
    var $cachedir = 'cache/'; // Directory to cache files in (keep outside web root)
    var $cachetime = 300; // Seconds to cache files for
    var $cacheext = 'cache'; // Extension to give cached files (usually cache, htm, txt)
    // Ignore List
    var $ignore_list = array(
		'ucp.php',
		'profile.php'
    );
	var $page = '';
	var $ignore_page = true;
	var $cachefile_created = 0;
	
	public function __construct() {
		$this->page = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; // Requested page
		$this->cachefile = $this->cachedir . md5($this->page) . '.' . $this->cacheext; // Cache file to either load or create
		
		$count = count($this->ignore_list);
		for ($i = 0; $i < $count; $i++) {
			$this->ignore_page = (strpos($this->page, $this->ignore_list[$i]) !== false) ? true : $this->ignore_page;
		}
		
		$this->cachefile_created = ((file_exists($this->cachefile)) and ($this->ignore_page === false)) ? filemtime($this->cachefile) : 0;
		clearstatcache();
	}
	
	function header($title) {
		// Check cache files
		if (time() - $this->cachetime < $this->cachefile_created) {
			$lastmod = gmdate('D, d M Y H:i:s', filemtime($this->cachefile)) . 'GMT';
			header("Last-Modified: $lastmod");
			ob_start('ob_gzhandler'); #Disable if GZip is disabled.
			readfile($this->cachefile);
			ob_end_flush();
			exit();
		}
		ob_start();
		require PSZ_FILE_PATH."temp/template.header.php";
		$this->output .= ob_get_clean();
	}
	
	function add_error($type, $error) {
		if($type == "Notice") {
			$this->notice .= "$error<br />";
		} else if ($type == "Warning") {
			$this->error .= "$error<br />";
		} else if ($type == "Success") {
			$this->success .= "$error<br />";
		} else if ($type == "Info") {
			$this->info .= "$error<br />";
		}
	}
	
	function add_post($id, $username, $head, $sub, $text, $date) {
		global $root;
		
		$date = date('F d, Y h:i:s a', strtotime($date));
		$this->content .= '<div class="user-post" id="post-'.$id.'">
			<div class="left">
				<img class="user-avie" src="'.$root->get_avatar($username).'" alt="'.$username.'" width="100" height="100" /><br /><a href="profile.php?u='.$username.'">'.$username.'</a>
			</div>
			<div class="right">
				<span class="post-head">'.$head.'</span>
				<span class="post-sub">'.$sub.'</span>
				<span class="post-date">'.$date.'</span>
				<br clear="all" />
				<span class="post-content">'.$text.'</span>
			</div>
		</div>
		<div class="clear"></div>';
	}
	
	function add_form($header, $left = Array(), $right = Array(), $button = "Submit:submit:submit", $div = "", $uri = "#") {
		$b = explode("||", $button);
		$this->content .= '<!-- '.$b[0].' --><form action="'.$uri.$div.'" method="post" name="form1" class="nice" id="form1"><h2>'.$header.'</h2><p class="left">';
		/** Add the left inputs **/
		foreach($left as $key=>$value) {
			$w = explode("||", $value);
			if($w[1] != "submit")
				$this->content .= "<label>{$w[0]}</label>";
			if($w[1] == "textarea")
				$this->content .= "<textarea name=\"{$w[2]}\" class=\"inputText\" rows=\"{$w[3]}\">{$w[4]}</textarea>";
			else if($w[1] == "submit")
				$this->content .= "<button class=\"inputText brown\" name=\"{$w[2]}\" type=\"{$w[1]}\">{$w[0]}</button>";
			else
				$this->content .= "<input type=\"{$w[1]}\" name=\"{$w[2]}\" value=\"{$w[3]}\" class=\"inputText\" />";
		}
		$this->content .='</p><p class="right">';
		/** Add the right inputs **/
		foreach($right as $key=>$value) {
			$w = explode("||", $value);
			if($w[1] != "submit")
				$this->content .= "<label>{$w[0]}</label>";
			if($w[1] == "textarea")
				$this->content .= "<textarea name=\"{$w[2]}\" class=\"inputText_wide\" rows=\"{$w[3]}\">{$w[4]}</textarea>";
			else if($w[1] == "submit")
				$this->content .= "<button class=\"inputText_wide brown\" name=\"{$w[2]}\" type=\"{$w[1]}\">{$w[0]}</button>";
			else
				$this->content .= "<input type=\"{$w[1]}\" name=\"{$w[2]}\" value=\"{$w[3]}\" class=\"inputText_wide\" />";
		}
		$this->content .= '<br clear="all" />';
		
		$this->content .= "<button class=\"inputText_wide brown\" name=\"{$b[2]}\" type=\"{$b[1]}\">{$b[0]}</button>";
		$this->content .= '</p><div class="clear"></div></form>';
	}
	
	function begin_div($id = "", $class = "") {
		$id = ($id != "") ? " id=\"$id\"" : "";
		$class = ($class != "") ? " class=\"$class\"" : "";
		$this->content .= "<div{$id}{$class}>";
	}
	
	function end_div() {	
		$this->content .= '</div>';
	}
	
	function begin_table() {
		$this->content .= '<table width="100%" border="0" cellpadding="0" cellspacing="0" id="data">';
	}
	
	# link:width:align
	function create_table_headers($columns = Array()) {
		$this->content .= '<tr>';
		$ct = count($columns);
		for($i = 0; $i < $ct; ++$i) {
			$w = explode('||', $columns[$i]);
			$html = ($w[0] !== '') ? $w[0] : 'undefined';
			$width = ($w[1] !== null && $w[1] !='') ? ' width="'.$w[1].'"' : '';
			$align = ($w[2] !== null && $w[2] !='') ? ' align="'.$w[2].'"' : '';
			$this->content .= '<th'.$width.$align.' scope="col">'.$html.'</th>';
		}
		$this->content .= '</tr>';
	}
	
	function create_table_rows($rows = Array(), $odd) {
		$class = ($odd === true) ? ' class="odd"' : "";
		$this->content .= '<tr>';
		$ct = count($rows);
		for($i = 0; $i < $ct; ++$i) {
			$w = explode('||', $rows[$i]);
			$html = ($w[0] !== '') ? $w[0] : 'undefined';
			$width = ($w[1] !== null && $w[1] !='') ? ' width="'.$w[1].'"' : '';
			$align = ($w[2] !== null && $w[2] !='') ? ' align="'.$w[2].'"' : '';
			$this->content .= '<td'.$class.$width.$align.' scope="col">'.$html.'</td>';
		}
		$this->content .= '</tr>';
	}
	
	function end_table() {
		$this->content .= '<tr><th colspan="100"></th></tr></table>';
	}
	
	function add_list($list = Array(), $type = "ul") {
		$this->content .= "<$type>";
		foreach($list as $key=>$value) {
			$this->content .= "<li>$value</li>";
		}
		$this->content .= "</$type>";
	}
	
	function add_inbox_message($subject, $fromuser, $datetime, $message) {
		$this->content .= "<h2>Private Message</h2><p>Subject: $subject<br />From User: $fromuser<br />Sent: $datetime<br />Message Content:</p><div class=\"nice\">$message</div>";
	}
	
	function add_inventory_list($items, $header = "") {
		$header = ($header != "") ? "<h1>$header</h1>" : "";
		$weps = "";
		$armr = "";
		
		$this->content .= $header.'<p class="left">';
		for ($ii = 0; $ii < count($items); $ii++) {
			if($items[$ii]['coin'] > 0) {
				$class = "acItem";
			} else if($items[$ii]['upgr'] > 0) {
				$class = "memItem";
			} else {
				$class = "normItem";
			}	
			$items[$ii]['type'] = preg_replace('/\s+/', '', $items[$ii]['type']); 
			$class .= " ".$items[$ii]['type'];
			if($items[$ii]['type'] == "armor" || $items[$ii]['eses'] == "hi" || $items[$ii]['type'] == "class") {
				$armr .= "<span class=\"item-row $class\"><a href=\"#\">{$items[$ii]['name']}</a></span>";
			} else {
				$weps .= "<span class=\"item-row $class\"><a href=\"#\">{$items[$ii]['name']}</a></span>";
			}
		}
		$this->content .= $weps;
		$this->content .= '</p><p class="right">'.$armr.'</p><div class="clear"></div>';
	}
	
	function generate_widget($head = "", $content="" ,$id = "") {
		$id = ($id !== '') ? $id : '';
		$this->widget .= '<div class="widget" id="'.$id.'"><h3 class="'.$id.'">'.$head.'</h3>'.$content.'</div><br />';
	}
	
	function generate_ranking_widget() {
		$this->widget .= '<div class="widget"><h3 class="ranking">Ranking</h3><p>Top 20 Players in the server</p><ol>';

		global $mysql;
		$q = $mysql->query("SELECT strUsername FROM etl_users WHERE iAccess < 40 ORDER BY iLvl DESC LIMIT 20");
		while($data = $mysql->fetch($q)) {
			$data['strUsername'] = ucfirst($data['strUsername']);
			$this->widget .= "<li><a href=\"profile.php?u={$data['strUsername']}\">{$data['strUsername']}</a></li>";
		}
			
		$this->widget .= '</ol></div>';
	}
	
	function footer() {
		$this->output .= '<!-- CONTENT START --><div class="grid_'.$this->CONTENT_GRID.' cnt" id="left">';
		$this->output .= '<div class="emsg"></div>';
		if(strlen($this->error) > 0)
			$this->output .= "<p class=\"error\">".$this->error."<span>X</span></p>";
		if(strlen($this->notice) > 0)
			$this->output .= "<p class=\"notice\">".$this->notice."<span>X</span></p>";
		if(strlen($this->success) > 0)
			$this->output .= "<p class=\"success\">".$this->success."<span>X</span></p>";
		if(strlen($this->info) > 0)
			$this->output .= "<p class=\"info\">".$this->info."<span>X</span></p>";
			
		$this->output .= $this->content.'</div><!-- CONTENT END -->';
		ob_start();
		if($this->ADD_WIDGET) {
			$this->output .= '<!-- WIDGETS START --><div class="grid_3">';
			$this->output .= '<div class="widget"><object width="250" height="250"><param name="movie" value="http://widget.chipin.com/widget/id/6089b4c5e86ba79d"></param><param name="allowScriptAccess" value="always"></param><param name="wmode" value="transparent"></param><param name="event_title" value="Buy%20some%20ACs%21%20%281500%29"></param><param name="event_desc" value="Each%201%24%20You%20donate%20it%20will%20be%201500%20ACs%21%21%21"></param><param name="color_scheme" value="blue"></param><embed src="http://widget.chipin.com/widget/id/6089b4c5e86ba79d" flashVars="event_title=Buy%20some%20ACs%21%20%281500%29&event_desc=Each%201%24%20You%20donate%20it%20will%20be%201500%20ACs%21%21%21&color_scheme=blue" type="application/x-shockwave-flash" allowScriptAccess="always" wmode="transparent" width="250" height="250"></embed></object>
Now you can buy AC coins!!!!</embed></object></div></br>';
			$this->output .= $this->widget;
			$this->output .= '</div><!-- WIDGETS END -->';
		}
		require PSZ_FILE_PATH."temp/template.footer.php";
		$this->output .= ob_get_clean();
	}
	
	function flush() {
		ob_start('ob_gzhandler');
		header('Content-Type: text/html; charset=utf-8');
		if(file_exists($this->cachefile)) {
			$lastmod = gmdate('D, d M Y H:i:s', filemtime($this->cachefile)) . 'GMT';
			header("Last-Modified: $lastmod");
		}
		$s = $this->clean_html_code($this->output);
		echo $s;

		ob_end_flush(); 
		// Now the script has run, generate a new cache file
		$fp = @fopen($this->cachefile, 'w'); 

		// save the contents of output buffer to the file
		@fwrite($fp, $s);
		@fclose($fp); 
	}
	
	//Function to seperate multiple tags one line
	function fix_newlines_for_clean_html($fixthistext)
	{
		$fixthistext_array = explode("\n", $fixthistext);
		foreach ($fixthistext_array as $unfixedtextkey => $unfixedtextvalue)
		{
			//Makes sure empty lines are ignores
			if (!preg_match("/^(\s)*$/", $unfixedtextvalue))
			{
				$fixedtextvalue = preg_replace("/>(\s|\t)*</U", ">\n<", $unfixedtextvalue);
				$fixedtext_array[$unfixedtextkey] = $fixedtextvalue;
			}
		}
		return implode("\n", $fixedtext_array);
	}
	
	function clean_html_code($uncleanhtml, $compress = false)
	{
		//Set wanted indentation
		$indent = "    ";

		//Uses previous function to seperate tags
		$fixed_uncleanhtml = $this->fix_newlines_for_clean_html($uncleanhtml);
		$uncleanhtml_array = explode("\n", $fixed_uncleanhtml);
		//Sets no indentation
		$indentlevel = 0;
		foreach ($uncleanhtml_array as $uncleanhtml_key => $currentuncleanhtml) {
			//Removes all indentation
			$currentuncleanhtml = preg_replace("/\t+/", "", $currentuncleanhtml);
			$currentuncleanhtml = preg_replace("/^\s+/", "", $currentuncleanhtml);
			
			if($compress)
				echo $currentuncleanhtml;
			
			$replaceindent = "";
			
			//Sets the indentation from current indentlevel
			for ($o = 0; $o < $indentlevel; $o++) {
				$replaceindent .= $indent;
			}
			
			//If self-closing tag, simply apply indent
			if (preg_match("/<(.+)\/>/", $currentuncleanhtml)) { 
				$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
			}
			//If doctype declaration, simply apply indent
			else if (preg_match("/<!(.*)>/", $currentuncleanhtml))
			{ 
				$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
			}
			//If opening AND closing tag on same line, simply apply indent
			else if (preg_match("/<[^\/](.*)>/", $currentuncleanhtml) && preg_match("/<\/(.*)>/", $currentuncleanhtml))
			{ 
				$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
			}
			//If closing HTML tag or closing JavaScript clams, decrease indentation and then apply the new level
			else if ((preg_match("/<\/(.*)>/", $currentuncleanhtml) || preg_match("/^(\s|\t)*\}{1}(\s|\t)*$/", $currentuncleanhtml)))
			{
				$indentlevel--;
				$replaceindent = "";
				for ($o = 0; $o < $indentlevel; $o++)
				{
					$replaceindent .= $indent;
				}
				
				// fix for textarea whitespace and in my opinion nicer looking script tags	
				if($currentuncleanhtml == '</textarea>' || $currentuncleanhtml == '</style>' || $currentuncleanhtml == '</script>')
				{
					$cleanhtml_array[$uncleanhtml_key] = $cleanhtml_array[($uncleanhtml_key - 1)] . $currentuncleanhtml;
					unset($cleanhtml_array[($uncleanhtml_key - 1)]);
				}
				else
				{
					$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
				}
			}
			//If opening HTML tag AND not a stand-alone tag, or opening JavaScript clams, increase indentation and then apply new level
			else if ((preg_match("/<[^\/](.*)>/", $currentuncleanhtml) && !preg_match("/<(link|meta|base|br|img|hr)(.*)>/", $currentuncleanhtml)) || preg_match("/^(\s|\t)*\{{1}(\s|\t)*$/", $currentuncleanhtml))
			{
				$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
				
				$indentlevel++;
				$replaceindent = "";
				for ($o = 0; $o < $indentlevel; $o++)
				{
					$replaceindent .= $indent;
				}
			}
			else
			//Else, only apply indentation
			{$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;}
		}
		//Return single string seperated by newline
		if(!$compress)
			return implode("\n", $cleanhtml_array);	
	}
}
?>

<?php
class plugins {
	public function __construct() {
		$this->classes = array();
		$dir = $GLOBALS['dr']."/modules/helpdesk/plugins/tickets/";
		//echo $dir;
		// READ ALL THE PLUGINS INTO A DIRECTORY
		if ($handle = opendir($dir)) {
	  	while (false !== ($file = readdir($handle))) {
	    	if ($file != "." && $file != "..") {
	      	require_once "$dir$file";

	      	// INSTANTIATE ALL THE PLUGIN OBJECTS
	      	$obj = strtolower($file);
	      	$obj = str_replace(".php","",$obj);
	      	$this->$obj = new $obj;

	      	// STORE EACH PLUGIN IN AN ARRAY, E.G DETAILS;TWITTER;ETC
	      	$this->classes[] = $obj;
	      }
	    }
	    closedir($handle);
		}
	}

	public function GetMenus() {
		$arr = array();
		$this->arr_plugins = array();
		foreach ($this->classes as $obj) {
			//echo $obj."<br />";
			$plugin_arr = $this->$obj->Menu();
			//print_r($this->$obj->Menu());
			if (is_array($plugin_arr)) {
				foreach ($plugin_arr as $pa) {
					//echo $pa;
					//$arr[][$obj] = $pa;
					$arr[] = $pa;
					$this->arr_plugins[] = $obj;
				}
			}
		}
		//print_r($arr);
		return $arr;
	}

	public function GetMenuOrderPlugins() {
		return $this->arr_plugins;
	}

	public function GetMenuContent($plugin,$item) {
		//echo $plugin;
		//echo $item;
		return $this->$plugin->MenuClicked();

	}

	public function NewTicketAction() {
		$arr = array();
		foreach ($this->classes as $obj) {
			if (method_exists($this->$obj,"NewTicket")) {
				$arr[] = $this->$obj->NewTicket();
			}
		}
		return $arr;
	}

	public function EditTicketAction() {
		$arr = array();
		foreach ($this->classes as $obj) {
			if (method_exists($this->$obj,"EditTicket")) {
				$arr[] = $this->$obj->EditTicket();
			}
		}
		return $arr;
	}
}
?>
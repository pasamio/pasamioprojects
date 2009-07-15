<?php
/**
 * JUpgrader Common Functions
 */

define('JUPDATEMAN_DLMETHOD_FOPEN', 0);
define('JUPDATEMAN_DLMETHOD_CURL', 1);

// Part of my 1.1 work :D
function downloadFile($url,$target) {
	$php_errormsg = 'Error Unknown';			// Set the error message
	ini_set('track_errors',true);				// Set track errors
	ini_set('user_agent', generateUAString());	// Set the user agent
	$error_object = new stdClass();

	$params = JComponentHelper::getParams('com_jupdateman');

	switch($params->get('download_method', 0)) {
		case JUPDATEMAN_DLMETHOD_FOPEN:
		default:
			return downloadFile_fopen($url, $target, $params);
			break;
		case JUPDATEMAN_DLMETHOD_CURL:
			return downloadFile_curl($url, $target, $params);
			break;
	}
}

function downloadFile_curl($url, $target, &$params) {
	if(!function_exists('curl_init')) {
		$error_object = new stdClass();
		$error_object->number = 40;
		$error_object->message = 'cURL support not available on this host. Use fopen instead.';
		return $error_object;
	}
	$error_object = new stdClass();
	$error_object->number = 41;
	$error_object->message = 'cURL not implemented!';
	return $error_object;
}

function downloadFile_fopen($url, $target, &$params) {
	// this isn't intelligent some times
	$error_object = new stdClass();
	$proxy = false;
	$php_errormsg = '';
	// If:
	// - the proxy is enabled,
	// - the host is set and the port are set
	// - and if we're on a version of PHP that supports contexts
	// Set the proxy settings and create a stream context
	if($params->get('use_proxy', 0)
	&& strlen($params->get('proxy_host', '')) && strlen($params->get('proxy_port', ''))
	&& version_compare(PHP_VERSION, '5.0.0', '>'))
	{
		$proxy = true;
		// I hate eclipse sometimes
		// If the user has a proxy username set fill this in as well
		$http_opts = array();
		$http_opts['proxy'] = 'tcp://'. $params->get('proxy_host') . ':'. $params->get('proxy_port');
		$http_opts['request_fulluri'] = 'true'; // play nicely with squid
		if(strlen($params->get('proxy_user', ''))) {
			$credentials = base64_encode($params->get('proxy_user', '').':'.$params->get('proxy_pass',''));
			$http_opts['header'] = "Proxy-Authorization: Basic $credentials\r\n";
		}
		$opts = array('http'=>$http_opts);
		$context = stream_context_create($opts);
		$input_handle = @fopen($url, 'r', false, $context);
	}
	else
	{
		// Open remote server
		$input_handle = @fopen($url, "r"); // or die("Remote server connection failed");
	}

	if (!$input_handle) {
		$error_object->number = 42;
		$error_object->message = 'Remote Server connection failed: ' . $php_errormsg .'; Using Proxy: '. ($proxy ? 'Yes' : 'No');
		return $error_object;
	}

	$output_handle = fopen($target, "wb"); // or die("Local output opening failed");
	if (!$output_handle) {
		$this->setError(43, 'Local output opening failed: ' . $php_errormsg);
		$error_object->number = 43;
		$error_object->message = 'Local output opening failed: ' . $php_errormsg;
		return $error_object;
	}

	$contents = '';
	$downloaded = 0;

	while (!feof($input_handle)) {
		$contents = fread($input_handle, 1024);
		if($contents === false) {
			$error_object->number = 44;
			$error_object->message = 'Failed reading network resource at '.$downloaded.' bytes: ' . $php_errormsg;
			return $error_object;
		} else if(strlen($contents)) {
			$write_res = fwrite($output_handle, $contents);
			if($write_res == false) {
				$error_object->number = 45;
				$error_object->message = 'Cannot write to local target: ' . $php_errormsg;
				return $error_object;
			}
			$downloaded += 1024;
		}
	}

	fclose($output_handle);
	fclose($input_handle);
	return true;
}

// Generate a nice progress bar
function buildProgressBar($current, $target) {
	$percent = round($current/$target*100);
	$data  = '<div id="container" style="border: 3px solid black; width: 160px; height: 40px;">';
	$data .= '	<div id="bar" style="float: left; border: 1px solid black; width: 100px; height: 40px; background: black">';
	$data .= '		<div id="greenbit" style="width: <?php echo $percent; ?>px; background: green; height: 40px">&nbsp;</div>';
	$data .= '	</div>';
	$data .= '	<div id="marker" style="float: left; padding: 5px; valign: middle; width: 40px;">'.$percent.'%</div>';
	$data .= '</div>';
	return $data;
}

// Meta Refresh
function metaRefresh($url,$delay=1) {
	return '<meta HTTP-EQUIV="refresh" content="'.$delay.';url='.$url.'">';
}

function generateUAString() {
	$version = new JVersion();
	$lang =& JFactory::getLanguage();
	$string  = 'Mozilla/5.0 (Joomla; PHP; '. PHP_OS .'; '. $lang->getTag() .'; rv:1.9.1)';
	$string .= ' Joomla/'. $version->getShortVersion();
	$string .= ' JUpdateMan/'. getComponentVersion();
	return $string;
}

function getComponentVersion() {
	return "1.5.1";
}

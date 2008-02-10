<?php
/**
 * JUpgrader Common Functions
 */
	// Part of my 1.1 work :D
	function downloadFile($url,$target) {
		$php_errormsg = 'Error Unknown';
		ini_set('track_errors',true);
		ini_set('user_agent','Mozilla');
		$error_object = new stdClass();
		
		// Open remote server
		$input_handle = @fopen($url, "r"); // or die("Remote server connection failed");
		if (!$input_handle) {
			$error_object->number = 42;
			$error_object->message = 'Remote Server connection failed: ' . $php_errormsg;
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
			if($contents == false) { 
				$error_object->number = 44;
				$error_object->message = 'Failed reading network resource at '.$downloaded.' bytes: ' . $php_errormsg; 
				return $error_object; 
			}
			if($contents) {
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

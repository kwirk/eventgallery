<?php
/**
 * This class contains helper methods to assist in including the multi bit shift flash applet.
 * 
 * @package multi_bit_shift_php_plugin
 */
class MultiBitShiftHelper {
	
	/**
	 * Sets up a Multi Bit Shift flash applet for use in a web form.
	 * 
	 * Returns a multi bit shift field input with the specified field name.  A hidden field is used to persist the unique identifier
	 * over multiple validation attempts. The width and height options are set to 420 and 240 by default, and the applicationToken is 
	 * automatically set to unique_identifier/guid of the field.  In addition, the actions for the uploadURL and removeFileURL are 
	 * automatically set, to /multi_bit_shift_php_plugin/upload_file.php, and /multi_bit_shift_php_plugin/remove_files.php respectively.  
	 * A validation object can be passed using the validation_object key of the options_array.
	 * 
	 * By default, this helper is used with the Multi Bit Shift applet.  To use with the advanced applet, mbs_version option should be set 
	 * to 'Advanced', and the width and height should be set to pixel values.  Additionally, the done button should be disabled.
	 * 
	 * The other options that can be specified using the options hash can be found in the queryStringLoader parameters 
	 * documentation of the flash applet, which can be located at http://multibitshift.com/documentation/view.  These options will be 
	 * passed to the flash applet and can be used to customize the applet at runtime.
	 * 
	 * You will probably want to take a look at the documentation for the multi_bit_shift_flash function as well, as it further
	 * enumerates the options that can be passed to this function.  NOTE: default values in this function over-ride defaults in that
	 * function.
	 * 
	 * @param string Name of the file upload field.
	 * @param string Unique Identifier used to identify the set of uploaded files.
	 * @param array Associative array of options for the upload.
	 * @return string Code that will include the applet in an html page.
	 */
	function multi_bit_shift_field($field_name, $unique_identifier, $options_array = null) {
		if ($options_array==null) {
			$options_array = array();
		} else {
			if (!is_array($options_array)) {
				trigger_error('$options_array must be a HashMap type array.', E_USER_ERROR);
				return;
			}			
		}
		
		if (!isset($options_array['width'])) {
			$options_array['width'] = '405';
		}
		if (!isset($options_array['height'])) {
			$options_array['height'] = '230';
		}
		if (!isset($options_array['applicationToken'])) {
			$options_array['applicationToken'] = $unique_identifier;
		}
		if (!isset($options_array['uploadURL'])) {
			$options_array['uploadURL'] = '/multi_bit_shift_php_plugin/upload_file.php';
		}
		if (!isset($options_array['removeFileURL'])) {
			$options_array['removeFileURL'] = '/multi_bit_shift_php_plugin/remove_files.php';
		}
		
		if (isset($options_array['validation_object'])) {
			$options_array = array_merge($options_array['validation_object']->convert_to_flash_params(), $options_array);			
      		unset($options_array['validation_object']);
		}
		
		$output = array();
		array_push($output, "<input type=\"hidden\" name=\"{$field_name}\" value=\"{$unique_identifier}\">");
		array_push($output, $this->multi_bit_shift_flash($options_array));
		return implode($output);
	}
	
	/**
	 * Returns the javascript and HTML that will insert the Multi Bit Shift flash applets.
	 * 
	 * Inserts the javascript and HTML to present the multi bit shift flash file.  The options hash contains the options to pass to the
	 * flash file, as well as additional options as listed below:
	 * 
	 * - <b>'width'</b> - Width of the applet, defaults to 100%
	 * - <b>'uploaded_files_array'</b> - Files that exist on the server, this is a associative array with file name, file size key
	 *   value pairs.  File size is in bytes.
	 * - <b>'height'</b> - Height of the applet, defaults to 100%
	 * - <b>'ajax_safe'</b> - Determines if the applet will be inserted via ajax.  If set to true, the applet does not make use 
	 *   of javascript to load, and instead is directly embedded.  Defaults to false.
	 * - <b>'mbs_version'</b> - What version of the Multi Bit Shift Applet to include.  This method sets the file_name attribute 
	 *   to flashFileHelperAdvanced when "Advanced" is set.  Accepted values are "Regular" and "Advanced", defaults to "Regular".
	 * - <b>'file_name'</b> - Sets the file name of the swf file to load.  This parameter should not be modified directly, 
	 *   instead the mbs_version property should be set.  Defaults to "flashFileHelper".
	 * - <b>'uploadURL'</b> - URL that files should be uploaded to.  Defaults to '/multi_bit_shift_php_plugin/upload_file.php'.
	 * - <b>'removeFileURL'</b> - URL that files should be removed with.  Defaults to '/multi_bit_shift_php_plugin/remove_files.php'.
	 * - <b>'fileListURL'</b> - URL that lists files.  Defaults to '/multi_bit_shift_php_plugin/files_on_server.php'.
	 * - <b>'renameFileURL'</b> - URL that files should be renamed with.  Defaults to '/multi_bit_shift_php_plugin/rename_file.php'.
	 * - <b>'doneURL'</b> - URL that Done button redirects to.  Defaults to '/index.php'.
	 * - <b>'flashCSS'</b> - URL of compiled CSS to use.  Defaults to '/multi_bit_shift_php_plugin/flash/css/siu.swf'.
	 *   
	 * The other options can be found in the queryStringLoader parameters documentation of the flash applet, which can be 
	 * located at http://multibitshift.com/documentation/view.
	 * 
	 * The code that is generated is based entirely on output from Macromedia Flex.
	 * 
	 * @param array Associative array of options for the upload.  Should contain all parameters to pass to the MBS applet.
	 * @return string Code that will include the applet in an html page.
	 */
	function multi_bit_shift_flash($options_array = null) {
		if ($options_array==null) {
			$options_array = array();
		} else {
			if (!is_array($options_array)) {
				trigger_error('$options_array must be a HashMap type array.', E_USER_ERROR);
				return;
			}			
		}
		
		if (!isset($options_array['width'])) {
			$width = '100%';
		} else {
			$width = $options_array['width'];
		}
		
		if (!isset($options_array['height'])) {
			$height = '100%';
		} else {
			$height = $options_array['height'];
		}
		
		if (!isset($options_array['ajax_safe'])) {
			$ajax_safe = 'false';
		} else {
			$ajax_safe = $options_array['ajax_safe'];
		}
		
		if (isset($options_array['mbs_version'])&&$options_array['mbs_version']=="Advanced") {
			$options_array['file_name'] = 'flashFileHelperAdvanced';
		}
		
		if (!isset($options_array['file_name'])) {
			$file_name = 'flashFileHelper';
		} else {
			$file_name = $options_array['file_name'];
			unset($options_array['file_name']);
		}
		
		if (!isset($options_array['flash_path'])) {
			$flash_path = '/multi_bit_shift_php_plugin/flash/';
		} else {
			$flash_path = $options_array['flash_path'];
			unset($options_array['flash_path']);
		}
		
		if (!isset($options_array['uploadURL'])) {
			$options_array['uploadURL'] = '/multi_bit_shift_php_plugin/upload_file.php';
		}
		if (!isset($options_array['removeFileURL'])) {
			$options_array['removeFileURL'] = '/multi_bit_shift_php_plugin/remove_files.php';
		}
		if (!isset($options_array['fileListURL'])) {
			$options_array['fileListURL'] = '/multi_bit_shift_php_plugin/files_on_server.php';
		}
		if (!isset($options_array['renameFileURL'])) {
			$options_array['renameFileURL'] = '/multi_bit_shift_php_plugin/rename_file.php';
		}
		if (!isset($options_array['doneURL'])) {
			$options_array['doneURL'] = '/index.php';
		}
		if (!isset($options_array['flashCSS'])) {
			$options_array['flashCSS'] = '/multi_bit_shift_php_plugin/flash/css/siu.swf';
		}
		
		if (isset($options_array['validation_object'])) {
			$options_array = array_merge($options_array['validation_object']->convert_to_flash_params(), $options_array);			
      		unset($options_array['validation_object']);
		}	
		
		if (isset($options_array['uploaded_files_array'])) {
			if (is_array($options_array['uploaded_files_array'])) {
				$count = 1;
				while (list($key, $val) = each($options_array['uploaded_files_array'])) {
					$options_array["uploadedFileName".$count] = $key;
					$options_array["uploadedFileSize".$count] = $value;
					$count = $count + 1;
				}
				unset($options_array['uploaded_files_array']);
			}
		}
		
		$flash_file = $flash_path . $file_name . ".swf";
		$js_file = $flash_path . "AC_OETags.js";
		$install_flash = $flash_path . "playerProductInstall";
		$flash_file_no_swf = $flash_path . $file_name;
		
		array_walk($options_array, create_function('&$v,$k', '$v = urlencode($v);'));
		array_walk($options_array, create_function('&$v,$k', '$v = $k."=".$v;'));
		
		$parameters = implode('&', array_values($options_array));
		
		if ($ajax_safe=="true") {
      		$insert_string = <<<END
    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
      id="{$file_name}" width="{$width}" height="{$height}"
      codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">
      <param name="movie" value="{$flash_file}?{$parameters}" />
      <param name="quality" value="high" />
      <param name="bgcolor" value="#ffffff" />
      <param name="flashvars" value="{$parameters}" />
      <param name="allowScriptAccess" value="sameDomain" />
      <embed src="{$flash_file}" quality="high" bgcolor="#ffffff"
        width="{$width}" height="{$height}" name="{$file_name}" align="middle" flashvars="{$parameters}"
        play="true"
        loop="false"
        quality="high"
        allowScriptAccess="sameDomain"
        type="application/x-shockwave-flash"
        pluginspage="http://www.adobe.com/go/getflashplayer">
      </embed>
  </object>
END;
    	} else {
        	$insert_string = <<<END
<script language="JavaScript" type="text/javascript">
<!--
// -----------------------------------------------------------------------------
// Globals
// Major version of Flash required
var requiredMajorVersion = 9;
// Minor version of Flash required
var requiredMinorVersion = 0;
// Minor version of Flash required
var requiredRevision = 0;
// -----------------------------------------------------------------------------
// -->
</script>
<script src="{$js_file}" language="javascript"></script>
<script language="JavaScript" type="text/javascript">
<!--
// Version check for the Flash Player that has the ability to start Player Product Install (6.0r65)
var hasProductInstall = DetectFlashVer(6, 0, 65);

// Version check based upon the values defined in globals
var hasRequestedVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);


// Check to see if a player with Flash Product Install is available and the version does not meet the requirements for playback
if ( hasProductInstall && !hasRequestedVersion ) {
  // MMdoctitle is the stored document.title value used by the installation process to close the window that started the process
  // This is necessary in order to close browser windows that are still utilizing the older version of the player after installation has completed
  // DO NOT MODIFY THE FOLLOWING FOUR LINES
  // Location visited after installation is complete if installation is required
  var MMPlayerType = (isIE == true) ? "ActiveX" : "PlugIn";
  var MMredirectURL = window.location;
    document.title = document.title.slice(0, 47) + " - Flash Player Installation";
    var MMdoctitle = document.title;

  AC_FL_RunContent(
    "src", "{$install_flash}",
    "FlashVars", "MMredirectURL="+MMredirectURL+'&MMplayerType='+MMPlayerType+'&MMdoctitle='+MMdoctitle+"",
    "width", "{$width}",
    "height", "{$height}",
    "align", "middle",
    "id", "{$file_name}",
    "quality", "high",
    "bgcolor", "#ffffff",
    "name", "{$file_name}",
    "allowScriptAccess","sameDomain",
    "type", "application/x-shockwave-flash",
    "pluginspage", "http://www.adobe.com/go/getflashplayer"
  );
} else if (hasRequestedVersion) {
  // if we've detected an acceptable version
  // embed the Flash Content SWF when all tests are passed
  AC_FL_RunContent(
      "src", "{$flash_file_no_swf}",
      "width", "{$width}",
      "height", "{$height}",
      "align", "middle",
      "id", "{$file_name}",
      "quality", "high",
      "bgcolor", "#ffffff",
      "name", "{$file_name}",
      "flashvars",'{$parameters}',
      "allowScriptAccess","sameDomain",
      "type", "application/x-shockwave-flash",
      "pluginspage", "http://www.adobe.com/go/getflashplayer"
  );
  } else {  // flash is too old or we can't detect the plugin
    var alternateContent = 'Alternate HTML content should be placed here. '
    + 'This content requires the Adobe Flash Player. '
    + '<a href=http://www.adobe.com/go/getflash/>Get Flash</a>';
    document.write(alternateContent);  // insert non-flash content
  }
// -->
</script>
<noscript>
    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
      id="{$file_name}" width="{$width}" height="{$height}"
      codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">
      <param name="movie" value="{$flash_file}?{$parameters}" />
      <param name="quality" value="high" />
      <param name="bgcolor" value="#ffffff" />
      <param name="flashvars" value="{$parameters}" />
      <param name="allowScriptAccess" value="sameDomain" />
      <embed src="{$flash_file}" quality="high" bgcolor="#ffffff"
        width="{$width}" height="{$height}" name="{$file_name}" align="middle" flashvars="{$parameters}"
        play="true"
        loop="false"
        quality="high"
        allowScriptAccess="sameDomain"
        type="application/x-shockwave-flash"
        pluginspage="http://www.adobe.com/go/getflashplayer">
      </embed>
  </object>
</noscript>
END;
		}				
		return $insert_string;		
	}
}
?>
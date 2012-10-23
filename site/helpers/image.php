<?php
	
		define('_JEXEC', 1);


		if (file_exists(dirname(__FILE__) . '/defines.php')) {
			include_once dirname(__FILE__) . '/defines.php';
		}


		if (!defined('_JDEFINES')) {
			define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
			require_once JPATH_BASE.'/includes/defines.php';
		}



		require_once JPATH_BASE.'/includes/framework.php';

		$file=JRequest::getString('file');
		$folder=JRequest::getString('folder');
		$width=JRequest::getInt('width',-1);
		$height=JRequest::getInt('height',-1);
		$mode=JRequest::getString('mode','crop');


		$file = STR_REPLACE("\.\.","",$file);
		$folder = STR_REPLACE("\.\.","",$folder);
		$width = STR_REPLACE("\.\.","",$width);
		$height = STR_REPLACE("\.\.","",$height);
		$mode = STR_REPLACE("\.\.","",$mode);
		
		$file = STR_REPLACE("/","",$file);
		$folder = STR_REPLACE("/","",$folder);
		$width = STR_REPLACE("/","",$width);
		$height = STR_REPLACE("/","",$height);
		$mode = STR_REPLACE("/","",$mode);

		
		$file = STR_REPLACE("\\","",$file);
		$folder = STR_REPLACE("\\","",$folder);
		$width = STR_REPLACE("\\","",$width);
		$height = STR_REPLACE("\\","",$height);
		$mode = STR_REPLACE("\\","",$mode);


		$basedir=JPATH_BASE.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'eventgallery'.DIRECTORY_SEPARATOR ;
		$sourcedir=$basedir.$folder;
		$cachebasedir=JPATH_CACHE.DIRECTORY_SEPARATOR.'com_eventgallery'.DIRECTORY_SEPARATOR ;
		$cachedir=$cachebasedir.$folder;
		$cachedir_thumbs=$cachebasedir.$folder.DIRECTORY_SEPARATOR.'thumbs';
		
		$image_file = $sourcedir.DIRECTORY_SEPARATOR.$file;
		$image_thumb_file = $cachedir_thumbs.DIRECTORY_SEPARATOR.$mode.$width.'_'.$height.$file;
		$last_modified = gmdate('D, d M Y H:i:s T', filemtime ($image_file));


		if (strcmp('full',$mode)==0 && strtolower(substr($image_file, -3)) == "jpg")
		{
			header("Content-Type: image/jpeg");
			echo readfile($image_file);		
			$app = JFactory::getApplication();
            $app->close();
            die();
		}

		$debug = false;
		if ($debug || !file_exists($image_thumb_file)) 
		{
		
		 	// Mark afterLoad in the profiler.
			JDEBUG ? $_PROFILER->mark('afterLoad') : null;

			// Instantiate the application.
			$app = JFactory::getApplication('site');

			// Initialise the application.
			$app->initialise();

			// Mark afterIntialise in the profiler.
			JDEBUG ? $_PROFILER->mark('afterInitialise') : null;

			// Route the application.
			$app->route();

			// Mark afterRoute in the profiler.
			JDEBUG ? $_PROFILER->mark('afterRoute') : null;

			// Dispatch the application.
			$app->dispatch();

			// Mark afterDispatch in the profiler.
			JDEBUG ? $_PROFILER->mark('afterDispatch') : null;

			// Render the application.
			$app->render();

			// Mark afterRender in the profiler.
			JDEBUG ? $_PROFILER->mark('afterRender') : null;

			// Return the response.
			echo $app;
		}
		
		
		if (!$debug)
		{
			header("Last-Modified: $last_modified");			
			header("Content-Type: image/jpeg");
		}
		
		echo readfile($image_thumb_file);

?>
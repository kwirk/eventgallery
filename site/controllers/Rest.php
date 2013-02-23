<?php

defined('_JEXEC') or die;

class RestController extends JControllerLegacy
{
	public function display($cachable = false, $urlparams = false)
	{			
		parent::display($cachable, $urlparams);		
	}

	public function add2cart() {


		$file = JRequest::getString( 'file' , null );
		$folder = JRequest::getString( 'folder' , null );
		if ($file==null || $folder==null) {
			return;
		}

		$app =& JFactory::getApplication();
		 
		// store the variable that we would like to keep for next time
		// function syntax is setUserState( $key, $value );
		$option = $app->input->get('option');

		$cartJson = $app->getUserState("$option.cart","");

		$cart = array();
		if (strlen($cartJson)>0) {
			$cart = json_decode($cartJson, true);
		}
		

		$model = & $this->getModel('Singleimage', 'EventgalleryModel');			
		$model->getData(JRequest::getString('folder'),JRequest::getString('file'));
		
		$imagetag =  $model->file->getLazyThumbImgTag(100,100, "", true);


		$imagetag = '<a class="thumbnail" href="'.$model->file->getImageUrl(null, null, true).'" title="'.$model->file->caption.'"  rel="lightbo2[cart]"> '.$model->file->getThumbImgTag(100,100).'</a>';
		

		$item = array('file'=>$file, 'folder'=>$folder, 'count'=>1, 'imagetag' => $imagetag);

		if (!in_array($item, $cart)) {
			array_push($cart, $item);
		}
		
		$app->setUserState( "$option.cart", json_encode($cart) );

		echo "done";
		
	}

	public function getCart() {
		$app =& JFactory::getApplication();		 
		// store the variable that we would like to keep for next time
		// function syntax is setUserState( $key, $value );
		$option = $app->input->get('option');

		$cartJson = $app->getUserState("$option.cart","");

		$cart = array();
		if (strlen($cartJson)>0) {
			$cart = json_decode($cartJson, true);
		}		

		print_r($cartJson);		
	}

	public function removeFromCart() {

		$file = JRequest::getString( 'file' , null );
		$folder = JRequest::getString( 'folder' , null );
		if ($file==null || $folder==null) {
			return;
		}

		$app =& JFactory::getApplication();		 
		// store the variable that we would like to keep for next time
		// function syntax is setUserState( $key, $value );
		$option = $app->input->get('option');

		$cartJson = $app->getUserState("$option.cart","");

		$cart = array();
		if (strlen($cartJson)>0) {
			$cart = json_decode($cartJson, true);
		}

		// do this to avoid getting an array like {1=>value, 2=>value} since the javascript
		// expects an array like {value, value}
		$newCart = array();
		foreach ($cart as $item) {
			if (strcmp($item['folder'],$folder)==0 && strcmp($item['file'],$file)==0) {
				
			} else {
				array_push($newCart, $item);
			}
		}		
		

		$app->setUserState( "$option.cart", json_encode($newCart) );
	}
}

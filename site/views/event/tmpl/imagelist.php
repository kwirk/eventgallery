<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<?php 
	
/*
220x220 220x220 220x220 220x220
300x140 300x140 300x140
300x100 140x100 140x100 300x100
460x200 460x200
940x250

*/	

include 'components/com_eventgallery/views/cart.php';

?>


<script type="text/javascript">

	var eventgalleryImageList;
	var lazyloader;
	
	window.addEvent("domready", function() {
		var options = {
			rowHeight: 150,
			rowHeightJitter: 50,
			firstImageRowHeight: 2,
			eventgallerySelector: '.thumbnails',
			eventgalleryImageSelector: '.thumbnail',
			initComplete: function() {
				lazyloader = new LazyLoad({ 
				    range: 100, 
				    elements: 'img.lazyme',
				    image: 'components/com_eventgallery/media/images/blank.gif', 
						onScroll: function() { 
							//console.log('scrolling'); 
						},
						onLoad: function(img) { 
							//console.log('image loaded'); 	
							setTimeout(function(){img.setStyle('opacity',0).fade(1);},500); 
						},
						onComplete:function() { 
							//console.log('all images loaded'); 
						}
				    
				});
			},
			resizeStart: function() {
				$$('.thumbnails thumbnail.img').setStyle('opacity',0);
			
			
			},
			resizeComplete: function() {
				lazyloader.initialize();
				window.fireEvent('scroll');
			}
		};
		
		// initialize the imagelist
 		eventgalleryImageList= new EventgalleryImagelist(options);
		
	});
</script>

<div class="event">
	<h4 class="date">
		<?php echo JHTML::Date($this->folder->date);?>
	</h4>
	<h1 class="description">
		<?php echo $this->folder->description; ?>
	</h1>
	<div class="text">
		<?php echo $this->folder->text; ?>
	</div>
	
	<div style="clear:both"></div>

	<?php 
		
		// process the array;
		$layout = Array();
		$this->image_array = "50x50";
		foreach(explode("\n",$this->image_array) as $line) {
			if (strlen(trim($line))==0) continue;
			$items = explode(" ",trim($line));
				
			
			$itemsArray = Array();
			foreach($items as $item) {
				if (strlen(trim($item))==0) continue;
				$tempItem = explode("x",trim($item));
				$itemArray = Array();
				$itemArray['w'] = $tempItem[0];
				$itemArray['h'] = $tempItem[1];
				array_push($itemsArray, $itemArray);
			}
			array_push($layout, $itemsArray);
		}
		
		$layout_row=0;
		$layout_col=0;
	?>
	
	<div class="thumbnails">
		<?php foreach($this->entries as $entry) :?>
		    <?php $this->assign('entry',$entry)?>
		    <div class="thumbnail-container">
	    	<a class="thumbnail" href="<?php echo $this->entry->getImageUrl(null, null, true); ?>"
	            title="<?php echo $entry->caption?><?PHP IF(isset($entry->exif)):?><br /><?php echo $entry->exif->model?>, <?php echo $entry->exif->focallength?> mm, f/<?php echo $entry->exif->fstop?>, ISO <?php echo $entry->exif->iso?><?php ENDIF ?>";
	            rel="lightbo2[gallery]"><?php echo $this->entry->getLazyThumbImgTag($layout[$layout_row][$layout_col]['w'], $layout[$layout_row][$layout_col]['h']);?>
			    </a><a href="#" title="<?php echo JText::_('COM_EVENTGALLERY_CART_ITEM_ADD2CART')?>" class="button-add2cart eventgallery-add2cart" data-id="folder=<?php echo $this->entry->folder."&file=".$this->entry->file ?>"><i class="big"></i></a></div>
	        <?php 
	    		$layout_col++;
	    		if ($layout_col>=sizeof($layout[$layout_row])) {
	    			$layout_col=0;
	    			$layout_row++;
	    		}
	    		if ($layout_row>=sizeof($layout)) {
	    			$layout_row=0;
	    		} 
	    	?>
		<?php endforeach?>
		<div style="clear: both"></div>
	</div>
</div>
<div style="clear:both"></div>


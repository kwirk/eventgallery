<?php // no direct access
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access'); ?>

<script type="text/javascript">
	/* <![CDATA[ */

	var myGallery;
	window.addEvent("domready", function(){
		myGallery = new JSGallery2($$('.thumbnail'), $('bigImage'), $('pageContainer'), 
			{	'prevHandle': $('prev'), 
				'nextHandle': $('next'), 
				'countHandle': $('count'),
				'prev_image' : '<?php echo JURI::base().'components/com_eventgallery/media/images/prev_button.png'?>',
				'next_image' : '<?php echo JURI::base().'components/com_eventgallery/media/images/next_button.png'?>',
				'zoom_image' : '<?php echo JURI::base().'components/com_eventgallery/media/images/zoom_button.png'?>',
				'titleTarget': 'bigImageDescription',
				'showCartButton' : <?php echo $this->folder->cartable==1?'true':'false'; ?>,
			});
		
	});
	/* ]]> */

	var resizePage = function() {
		var size = $$('.ajaxpaging .navigation').getLast().getSize();
		$$('.navigation .page').setStyle('width',size.x+"px");
		if (myGallery != undefined) {
			myGallery.gotoPage(myGallery.currentPageNumber);
		}
		
	};

	window.addEvent('load', resizePage);
	window.addEvent('resize', resizePage);



</script>

<?php include 'components/com_eventgallery/views/cart.php'; ?>
	
<div class="ajaxpaging">
	

	<?php 
		$pageCount = 0;
		$imageCount = 0;
		$imagesOnPage = 0;
		$imagesFirstPage = $this->params->get('event_ajax_list_number_of_thumbnail_on_first_page',11);
		$imagesPerPage = $this->params->get('event_ajax_list_number_of_thumbnail_per_page',22);
		
		$pagesCount = ceil( (count($this->entries) - $imagesFirstPage) / $imagesPerPage) + 1;
	?>
	<div class="navigation">
	
		<div id="pagerContainer">
			<div id="thumbs">
				<div id="pageContainer">

					<div id="page<?php echo $pageCount++; ?>" class="page">

						<?php foreach($this->entries as $entry) :?>
			
							<?php IF ($pageCount == 1 && $imageCount == 0): ?>
								<h4>
									<?php echo JHTML::Date($this->folder->date);?>
								</h4>
								<h1>
									<?php echo $this->folder->description; ?>
								</h1>
								<div>	
									<?php echo $this->folder->text; ?>
								</div>
							<?php ENDIF; ?>				
													
							<?php $this->assign('entry',$entry)?>
							<?php $imagesOnPage++ ?>
							
							<div class="thumbnail" id="image<?php echo $imageCount++;?>">				
								 <a longdesc="<?php echo $entry->getImageUrl(null, null, true);?>" 
									 href="<?php echo $entry->getImageUrl(null, null, true);?>"
									 title="<?php echo htmlentities($entry->getPlainTextTitle()); ?>"
								     rel="<?php echo $entry->getImageUrl(1100, 1100, false, false); ?>"
								     data-id="folder=<?php echo $entry->folder ?>&amp;file=<?php echo $entry->file ?>"
								     data-description="<?php echo JHTML::Date($this->folder->date).' - '.$this->folder->description."&lt;br /&gt; ".JText::_('COM_EVENTGALLERY_EVENT_AJAX_IMAGE_CAPTION_IMAGE')." $imageCount ".JText::_('COM_EVENTGALLERY_EVENT_AJAX_IMAGE_CAPTION_OF')." $this->entriesCount" ?>
										<br /><?php echo rawurlencode($entry->getTitle()); ?>"				  
									 data-title="<?php echo rawurlencode($entry->getLightBoxTitle()); ?>"
									 >
								    <?php echo $entry->getThumbImgTag(75, 75);?>
								 </a>
							</div>		    
				
							<?php IF (  ($imagesOnPage % $imagesPerPage == 0) || ($pageCount==1 && ($imagesOnPage % $imagesFirstPage == 0))  ): ?>
								</div>
								<div id="page<?php echo $pageCount++; ?>" class="page">
								<?php $imagesOnPage = 0; ?>
							<?php ENDIF; ?>									
				
						<?php endforeach?>
					</div>
						
				</div>
			</div>
			<div class="clear"></div>
		</div>
		
		<!--<a style="" href="#" onclick="myGallery.prevPage(); return false;" id="prev"><img src="<?php echo JURI::base().'components/com_eventgallery/media/images/prev_button.png'?>" alt="back" style="border: 0px;"/></a>
		<a style="" href="#" onclick="myGallery.nextPage(); return false;" id="next"><img src="<?php echo JURI::base().'components/com_eventgallery/media/images/next_button.png'?>" alt="next" style="border: 0px;"/></a>-->
		<div class="pagination"><ul id="count"></ul></div>
		
	</div>

	<div class="image">	
		
			<div id="bigimageContainer">
				<img src="<?php echo JURI::base().'components/com_eventgallery/media/images/loading_s.gif'?>" alt="" id="bigImage"/>
				<span id="bigImageDescription" class="img_overlay img_overlay_fotos overlay_3"><?php echo JText::_('COM_EVENTGALLERY_EVENT_AJAX_LOADING') ?></span>
			</div>
		
		</div>	
	<div style="clear:both"></div>
</div>

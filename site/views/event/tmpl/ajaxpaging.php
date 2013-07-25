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

	/* Method to bring the thumb rel attribute to the right size */
	var adjustImageSize = function() {
			var imageContainerSize = $('bigimageContainer').getSize();
			var sizeCalculator = new SizeCalculator();
	    	var width = imageContainerSize.x;
	    	 
			$$('#thumbs .ajax-thumbnail').each(function(item){
				var ratio = item.getAttribute('data-width')/item.getAttribute('data-height');
				var height = Math.round(width/ratio);
				var googleWidth = sizeCalculator.getSize(width, height, ratio);
				item.setAttribute('rel', sizeCalculator.adjustImageURL(item.getAttribute('rel'), googleWidth));
			});
	}

	/* start the eventgallery*/
	window.addEvent("domready", function(){
		adjustImageSize();
		myGallery = new JSGallery2($$('.thumbnail'), $('bigImage'), $('pageContainer'), 
			{	'prevHandle': $('prev'), 
				'nextHandle': $('next'), 
				'countHandle': $('count'),
				'prev_image' : '<?php echo JURI::base().'components/com_eventgallery/media/images/prev_button.png'?>',
				'next_image' : '<?php echo JURI::base().'components/com_eventgallery/media/images/next_button.png'?>',
				'zoom_image' : '<?php echo JURI::base().'components/com_eventgallery/media/images/zoom_button.png'?>',
				'titleTarget': 'bigImageDescription',
				'showSocialMediaButton': <?php echo $this->params->get('use_social_sharing_button', 0)==1?'true':'false'?>,
				'showCartButton' : <?php echo $this->folder->cartable==1?'true':'false'; ?>,
				'showCartConnector': <?php echo $this->params->get('show_cart_connector', 0)==1&&$this->folder->cartable==1?'true':'false'; ?>,
				'cartConnectorLinkRel' : '<?php echo $this->params->get('cart_connector_link_rel', 'nofollow')?>',
				'lightboxRel' : 'lightbo2[gallery<?php echo $this->params->get('use_fullscreen_lightbox',0)==1?'fullscreen':''; ?>]'
			});
		
	});	

	/* Method which handles the case the window got resized */
	var resizePage = function() {

		window.clearTimeout(eventgalleryAjaxResizeTimer);
			  
		var eventgalleryAjaxResizeTimer = (function(){
			var size = $$('.ajaxpaging .navigation').getLast().getSize();

			$$('.navigation .page').setStyle('width',size.x+2+"px");
			if (myGallery != undefined) {
				adjustImageSize();
				myGallery.resetThumbs();
				myGallery.gotoPage(myGallery.currentPageNumber);
			}
		}.bind(this)).delay(500);
	};

	window.addEvent('load', resizePage);
	window.addEvent('resize', resizePage);
/* ]]> */
</script>


<?php 
include 'components/com_eventgallery/views/cart.php';
include 'components/com_eventgallery/views/social.php'; 
?>
	
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
								<?php IF($this->params->get('show_date',1)==1):?>
									<h4 class="date">
										<?php echo JHTML::Date($this->folder->date);?>
									</h4>
								<?php ENDIF ?>
								<h1 class="description">
									<?php echo $this->folder->description; ?>
								</h1>
								<div class="text">	
									<?php echo $this->folder->text; ?>
								</div>
							<?php ENDIF; ?>				
													
							<?php $this->assign('entry',$entry)?>
							<?php $imagesOnPage++ ?>

							<div class="thumbnail" id="image<?php echo $imageCount++;?>">				
								 <a longdesc="<?php echo $entry->getImageUrl(null, null, true);?>" 
								 	 class="ajax-thumbnail"	
									 href="<?php echo $entry->getImageUrl(null, null, true);?>"
									 title="<?php echo htmlspecialchars($entry->getPlainTextTitle(), ENT_COMPAT, 'UTF-8'); ?>"
								     rel="<?php echo $entry->getImageUrl(50, 50, false, false); ?>"
								     data-folder="<?php echo $entry->folder; ?>"
								     data-file="<?php echo $entry->file; ?>"
								     <?php IF ($this->params->get('show_cart_connector', 0)==1):?>
								     	data-cart-connector-link="<?php echo rawurlencode(EventgalleryHelpersCartconnector::getLink($this->entry->folder, $this->entry->file));?>"
								     <?php ENDIF ?>
								     data-id="folder=<?php echo $entry->folder ?>&amp;file=<?php echo $entry->file ?>"
								     data-width="<?php echo $entry->width; ?>"
								     data-height="<?php echo $entry->height; ?>"
								     data-description="<?php if($this->params->get('show_date',1)==1) {echo JHTML::Date($this->folder->date).' - ';} echo $this->folder->description."&lt;br /&gt; ".JText::_('COM_EVENTGALLERY_EVENT_AJAX_IMAGE_CAPTION_IMAGE')." $imageCount ".JText::_('COM_EVENTGALLERY_EVENT_AJAX_IMAGE_CAPTION_OF')." $this->entriesCount" ?>
										<br /><?php echo rawurlencode($entry->getTitle()); ?>"				  
									 data-title="<?php echo rawurlencode($entry->getLightBoxTitle()); ?>"
									 <?php IF ($this->params->get('use_social_sharing_button', 0)==1):?>
									 	data-social-sharing-link="<?php echo rawurlencode(JRoute::_('index.php?option=com_eventgallery&view=singleimage&layout=share&folder='.$this->entry->folder.'&file='.$this->entry->file.'&format=raw') ); ?>"
									 <?php ENDIF ?>
									 >
								    <?php echo $entry->getThumbImgTag($this->params->get('event_ajax_list_thumbnail_size', 75), $this->params->get('event_ajax_list_thumbnail_size', 75));?>
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

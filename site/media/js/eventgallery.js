
	/*
	Class to manage an image. This can be the img tag or a container. It has to manage glue itself. 
	*/	
	var EventgalleryImage = new Class({

		initialize: function(image, index){
			this.tag = image;
	        this.index = index;
	        this.calcSize();
	        
			// to be able to handle internal and google picasa images, we need to restrict the availabe image sizes.
			this.availableSizes = new Array(32,48,64,72,94,104,110,128,144,150,160,200,220,288,320,400,512,576,640,720,800,912,1024,1152,1280,1440);
			 
			
	    },
	    calcSize: function() {
	    	// glue includes everything but the image width/heigt: margin, padding, border
	    	var image = this.tag.getElement('img');
	    	
	    	this.glueLeft = 	this.tag.getStyle('padding-left').toInt() + 	this.tag.getStyle('margin-left').toInt() + 		this.tag.getStyle('border-width').toInt() + image.getStyle('margin-left').toInt() + 	image.getStyle('padding-left').toInt() + 	image.getStyle('border-width').toInt();
	        this.glueRight = 	this.tag.getStyle('padding-right').toInt() + 	this.tag.getStyle('margin-right').toInt() + 	this.tag.getStyle('border-width').toInt() + image.getStyle('margin-right').toInt() +	image.getStyle('padding-right').toInt()+ 	image.getStyle('border-width').toInt();
	        this.glueTop = 		this.tag.getStyle('padding-top').toInt() + 		this.tag.getStyle('margin-top').toInt() + 		this.tag.getStyle('border-width').toInt() + image.getStyle('margin-top').toInt() + 		image.getStyle('padding-top').toInt()+ 		image.getStyle('border-width').toInt();
	        this.glueBottom = 	this.tag.getStyle('padding-bottom').toInt() + 	this.tag.getStyle('margin-bottom').toInt() +	this.tag.getStyle('border-width').toInt() + image.getStyle('margin-bottom').toInt() + 	image.getStyle('padding-bottom').toInt()+ 	image.getStyle('border-width').toInt();
			
			// get image size from data- attributes
	        this.width = image.getProperty("data-width");//  - this.glueLeft - this.glueRight;
	        this.height = image.getProperty("data-height");// - this.glueTop  - this.glueBottom;

			// fallback of data- attributes are not there
	        if (this.width == null) {
   	        	this.width = this.tag.getSize().x - this.glueLeft - this.glueRight;
   	        }
   	        
   	        if (this.height == null) {
	        	this.height = this.tag.getSize().y - this.glueTop  - this.glueBottom;	        	
	        }
	    },
	    setSize: function(width, height) {
	    	
	    	var newWidth =  width - this.glueLeft - this.glueRight;
	    	var newHeight = height - this.glueTop  - this.glueBottom;
	    	
	    	var ratio = this.width/this.height;
	    	
	    	//console.log("the size of the image should be: "+width+"x"+height+" so I have to set it to: "+newWidth+"x"+newHeight);
	    	
	    	// adjust the width for goolge
	    	var googleWidth = 32;
	    	var normalWidth = 32;
	    	var normalHeight = 32;
	    	
	    	this.availableSizes.each(function(item, index){
	    		if (googleWidth>32) return;
	    		
	    		if (ratio>=1) {
	    		
		    		var widthOkay = item > newWidth;
		    		var heightOkay = item/ratio>newHeight
		    			
		    		if (widthOkay && heightOkay) {
		    			
		    				googleWidth = item;
		    				normalWidth = googleWidth;
		    				normalHeight = Math.round(item/ratio);
		    			
		    		} 
	    		} else {
	    			var heightOkay = item > newHeight;
  	    		    var widthOkay = item*ratio> newWidth;
		    			
		    		if (widthOkay && heightOkay) {
		    			
		    				googleWidth = item;
		    				normalHeight = googleWidth;
		    				normalWidth = Math.round(item*ratio);
		    			
		    		} 

	    		}
	    		
			}.bind(this)); 
	    	

	    	//adjust background images
	    	var image = this.tag.getElement('img');

			// set a new background image
	    	var backgroundImageStyle = image.getStyle('background-image');
	    	var longdesc = image.get('longdesc');
	    	if (!longdesc) {
	    		longdesc = "";
	    	}
	    	backgroundImageStyle = backgroundImageStyle.replace(/width=(\d*)/,'width='+normalWidth);
	    	backgroundImageStyle = backgroundImageStyle.replace(/height=(\d*)/,'height='+normalHeight);
	    	backgroundImageStyle = backgroundImageStyle.replace(/\/s(\d*)\//,'/s'+googleWidth+'/');
	    	longdesc = longdesc.replace(/width=(\d*)/,'width='+normalWidth);
	    	longdesc = longdesc.replace(/height=(\d*)/,'height='+normalHeight);
	    	longdesc = longdesc.replace(/\/s(\d*)\//,'/s'+googleWidth+'/');
	    	image.setStyle('background-image', backgroundImageStyle);
	    	image.set('longdesc',longdesc);
	    	image.setStyle('background-position', '50% 50%');
	    	
	    	
	    	image.setStyle('width', newWidth);
	    	image.setStyle('height', newHeight);	    	
	    	
	    	
	    }
	    
	});
	
	/* processes a row is a image list */
	var EventgalleryRow = new Class({
		Implements: [Options],
		
		options: {
			maxWidth: 960,
			maxHeight: 150,
			heightJitter: 0,
			adjustHeight: true,
			cropLastImage: true
		},
		
	
		initialize: function(options) {
		    this.setOptions(options);
			this.images = new Array();
			this.width = 0;
			if (this.options.heightJitter>0) {
				this.options.maxHeight = Math.floor(this.options.maxHeight + (Math.random()*2*this.options.heightJitter) - this.options.heightJitter);
			}
			
		},
		add: function(eventgalleryImage) {
			var imageWidth = Math.floor(eventgalleryImage.width / eventgalleryImage.height * this.options.maxHeight);

			// determine the number of images per line. return false if the row if full.
			if (this.width+imageWidth<=this.options.maxWidth || this.images.length==0) {
				this.images.push(eventgalleryImage);
				this.width = this.width + imageWidth;
				return true;
			} else {
				return false;
			}
			
		},
		processRow: function() {
			
			// calc the diff
			var diff = this.options.maxWidth - this.width;
			var diffWidth = Math.floor(diff/this.images.length);
			
			if (diffWidth>this.options.rowWidth/this.images.length) {
				diffWidth =0;
			}
			
			//console.log("process row. DiffWidth="+diffWidth);
			
			// determine a common height for the images
			var diffHeight = Math.floor(diff/this.images.length);
			if (this.options.adjustHeight == false) {
				diffHeight = 0;
			}
			
			// do not shrink a line
			if (diffHeight<0) {
				diffHeight = 0;
			}

			var diffWidthBalance = diff-(diffWidth*(this.images.length-1));

			// handle the last image differently if it should not be cropped. Be aware that a vertial image will appear in full height!

			if (this.images.length==1 && !this.options.cropLastImage) {
					var image = this.images[0];
					var height = Math.round(image.height / image.width * this.options.maxWidth);
					image.setSize(this.options.maxWidth, height);

			} else {

				this.images.each(function(image, index) {
					
					var imageWidth = Math.floor(image.width / image.height * this.options.maxHeight);
					
					if (index==this.images.length-1) {
						image.setSize(imageWidth + diffWidthBalance, this.options.maxHeight + diffHeight);
					} else {
					    image.setSize(imageWidth + diffWidth, this.options.maxHeight + diffHeight);
					}
					
				}.bind(this));
			}
			
		}
	});
	
	/* processes an image list*/
	var EventgalleryImagelist = new Class({
		Implements: [Options],
		options: {
			rowHeight: 150,
			rowHeightJitter: 0,
			minImageWidth: 150,
			cropLastImage: true,
			eventgallerySelector: '.imagelist',
			eventgalleryImageSelector: '.imagelist a',
			firstImageRowHeight: 2,
			initComplete: function() {},
			resizeStart: function() {},
			resizeComplete: function() {}
		},
		
		initialize: function(options) {
		    this.setOptions(options);
		    var images_tags = $$(this.options.eventgalleryImageSelector);
			this.images = Array();

			images_tags.each(function(item, index, obj) {
				this.images.push(new EventgalleryImage(item, index));
			}.bind(this));
			
			
			window.addEvent('resize', function(){
			  	window.clearTimeout(this.eventgalleryTimer);
			  
				this.eventgalleryTimer = (function(){
				    var new_width = $$(this.options.eventgallerySelector).getLast().getSize().x;
				    if (this.eventgalleryPageWidth!=new_width) {
				    	this.options.resizeStart();
				    	this.eventgalleryPageWidth = new_width;
						this.processList();
						this.options.resizeComplete();
					}
				}.bind(this)).delay(500);
			  
			}.bind(this));
			
			this.processList();
			this.options.initComplete();
		},
		
		/* processes the image list*/
		processList: function() {
			
			/* find out how much space we have*/
			var rowWidth = $$(this.options.eventgallerySelector).getLast().getSize().x;
			//console.log("will adjust for width "+rowWidth);
			
			/* get a copy of the image list because we will pop the image during iteration*/
			var imagesToProcess = Array.clone(this.images);
			
			/* display the first image larger */
			if (this.options.firstImageRowHeight>1) {
				var image = imagesToProcess.shift();
				
				/*if we have a large image, we have to hide it to get the real available space*/
				image.tag.setStyle('display', 'none');
				rowWidth = $$(this.options.eventgallerySelector).getLast().getSize().x;
				image.tag.setStyle('display', 'inline');
				
				var imageHeight  = this.options.firstImageRowHeight * this.options.rowHeight;
				var imageWidth   = Math.floor(image.width / image.height * imageHeight);
				
				if (imageWidth+this.options.minImageWidth>=rowWidth) {
					imageWidth = rowWidth;
					rowsLeft = 0;
				}
				
				image.setSize(imageWidth, imageHeight);
				
				var options = {
					maxWidth: rowWidth-imageWidth, 
					maxHeight: this.options.rowHeight,
					adjustHeight: false
				};
				
				if (options.maxWidth>0) {
					this.generateRows(imagesToProcess, this.options.firstImageRowHeight, options);
				}
			}
			
			var options = {
				maxWidth: rowWidth, 
				maxHeight: this.options.rowHeight,
				heightJitter: this.options.rowHeightJitter,
				cropLastImage: this.options.cropLastImage
			};
			
			this.generateRows(imagesToProcess, 99999, options);

		},
			
		generateRows: function(imagesToProcess, numberOfRowsToCreate, options) {
			

				var currentRow = new EventgalleryRow(options);

				while (imagesToProcess.length>0 && numberOfRowsToCreate>0) {
					var addSuccessfull = currentRow.add(imagesToProcess[0]);
					if (addSuccessfull) {
						imagesToProcess.shift();
					} else {
						currentRow.processRow();
						numberOfRowsToCreate--;
						if (numberOfRowsToCreate == 0) break;
						currentRow = new EventgalleryRow(options);
					}
				}
				
				currentRow.processRow();
			
		}
	
	});

	/*
		This is out cart class. 
	*/
	var EventgalleryCart = new Class({
		Implements: [Options],
		cart : new Array(),
		options: {
			cartSelector: '.eventgallery-cart',
			cartItemContainerSelector: '.cart-items-container',
			cartItemsSelector: '.eventgallery-cart .cart-items',
			cartCountSelector: '.itemscount',
			buttonDownSelector: '.toggle-down',
			buttonUpSelector: '.toggle-up',
			cartItemsMinHeight: 130,
			removeUrl :  "",
			add2cartUrl : "",
			getCartUrl: "",
			removeLinkTitle : "Remove",
	
		},
		
		initialize: function(options) {
		    this.setOptions(options);

		    this.myVerticalSlide = new Fx.Tween($$(this.options.cartItemContainerSelector).getLast(),{
			    duration: 'short',
			    transition: 'quad:in',
			    link: 'cancel',
			    property: 'height'
			});

			$$(this.options.buttonDownSelector).addEvent('click', function(event){
				event.stop();    
				this.myVerticalSlide.start($$(this.options.cartItemsSelector).getLast().getSize().y);
				$$(this.options.buttonDownSelector).hide();
				$$(this.options.buttonUpSelector).show();
			}.bind(this));

			$$(this.options.buttonUpSelector).addEvent('click', function(event){
				event.stop();
				this.myVerticalSlide.start(120);	
				$$(this.options.buttonUpSelector).hide();
				$$(this.options.buttonDownSelector).show();
			}.bind(this));

			$(document.body).addEvent('click:relay(.eventgallery-add2cart)', function(e) {this.add2cart(e)}.bind(this));
			$(document.body).addEvent('click:relay(.eventgallery-removeFromCart)', function(e) {this.removeFromCart(e)}.bind(this));
			$(document.body).addEvent('updatecartlinks', function(e) {
				this.populateCart(true);
			}.bind(this));

			this.updateCart();

		},

		updateCartItemContainer: function() {
			if ($$(this.options.cartItemsSelector).getLast().getSize().y>this.options.cartItemsMinHeight) {
				$$(this.options.buttonDownSelector).show();	
			} else {  		
				this.myVerticalSlide.start(120);	
				$$(this.options.buttonUpSelector).hide();
				$$(this.options.buttonDownSelector).hide();	
			}
		},

		/* Populate the cart element on the page with the data we used */
		populateCart: function(linksOnly) {

			if (this.cart.length==0) {
				$$(this.options.cartSelector).hide();
			} else {
				$$(this.options.cartSelector).show();
			}
				// define where all the cart html items are located

			var cartHTML = $$(this.options.cartItemsSelector).getLast();
			if (cartHTML == null) {
				return;
			}
			// clear the html showing the current cart
			if (!linksOnly) {				
				cartHTML.set('html',"");
			}

				// reset cart button icons
			$$('a.eventgallery-add2cart').addClass('button-add2cart').removeClass('button-alreadyInCart');
	        	

			for (var i = this.cart.length - 1; i >= 0; i--) {
				//create the id. It's always folder=foo&file=bar
				var id = 'folder='+this.cart[i].folder+'&file='+this.cart[i].file;					
				//add the item to the cart. Currently we simple refresh the whole cart.
				if (!linksOnly) {
					cartHTML.set('html',cartHTML.get('html')+'<div class="cart-item">'+this.cart[i].imagetag+'<a href="#" title="'+this.options.removeLinkTitle+'" class="eventgallery-removeFromCart button-removeFromCart" data-id="'+id+'"><i class="big"></i></a></div>');
				}
				// mark the add2cart link to show the item is already in the cart
				$$('a.eventgallery-add2cart[data-id="'+id+'"]').addClass('button-alreadyInCart').removeClass('button-add2cart');				
				
			};		

			if (!linksOnly) {
				cartHTML.set('html',cartHTML.get('html')+'<div style="clear:both"></div>');
				this.updateCartItemContainer();
			}

			$$('.itemscount').set('html',this.cart.length);
			Mediabox.scanPage();
		},

		/* Get the current version of the cart from the server */
		updateCart: function() {		
			var jsonReq = new Request.JSON({
				url: this.options.getCartUrl,
				method: 'post',
				data: {
					json: 'yes'
				},
				onComplete: function(response){
					if (response !== undefined) {
						this.cart = response;
					}
					this.populateCart();
				}.bind(this)
			}).send();

		}, 

		/* Send a request to the server to remove an item from the cart */
		removeFromCart: function(e) {
			var data;
			if (e.target.tagName=='A') {
				data = $(e.target).get('data-id');
			} else {
				data = $(e.target).getParent('A').get('data-id');
			}
			var myRequest = new Request(
				{
	        		url: this.options.removeUrl,
	        		method: "POST",
	        		data: data,
		        	onSuccess: function(msg){
	                    this.updateCart();
	               	}.bind(this)
	        }).send();
	        e.stopPropagation();
	        e.preventDefault();
		},

		/* Send a request to the server to add an item to the cart */
		add2cart: function(e)  {
			var data;
			if (e.target.tagName=='A') {
				data = $(e.target).get('data-id');
			} else {
				data = $(e.target).getParent('A').get('data-id');
			}
			var myRequest = new Request(
				{
	        		url: this.options.add2cartUrl,
	        		method: "POST",
	        		data: data,
		        	onSuccess: function(msg){
	                    this.updateCart();
	               	}.bind(this)
	        }).send();
	        e.stopPropagation();
	        e.preventDefault();
	        return true;
		}

	});

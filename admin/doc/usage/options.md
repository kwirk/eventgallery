# Configuration options {#configuration}

There are only a few things globally manageable. Especially the appearance of the gallery is configured at the menu item.

## General

![Configuration options](img/backend/backend_configuration_general.jpg)

- Notification User Group

	This user group is used to send notification about comment and orders to. All users in this group which can receive system mails will get a mail upon a event.

- Use Comments

	Defines if comments are enabled. 


## Image

![Configuration options](img/backend/backend_configuration_image.jpg)

- image quality

	Defines the output quality of every generated image. Default is 85% which is pretty good. Lower values will result in lower quality.

- use sharpening

	Enables or disables the sharping functionality. If image sharping is enabled it uses the matrix defined below.

- Sharpen Matrix

	You can define a matrix which is used to sharpen the generated images. By default this is [[-1,-1,-1],[-1,16,-1],[-1,-1,-1]] and it will do a good job. In case you want to have blurry or even sharper images this is the configuration parameter you'll have to touch. Please find the possible values here: http://php.net/manual/en/function.imageconvolution.php . In case you have no clue what this is about, don't touch it. After a change delete the cache otherwise you will see no result.

- Use Rendering Fallback

	On some servers the execution of script is not allowed in subfolders. The execution is necessary since images are delivered by a script and we don't want to run the whole joomla framework for every request. If you encounter issues with images that do not appear, you can use the fallback to use index.php for image delivering. This is `slow and should be not your long term solution`. Contact your provider to make sure php scripts can be executed in component folders. At least this would be great for the following scripts: components/com_eventgallery/helpers/image.php and blank.php.


## Cart

![Configuration options](img/backend/backend_configuration_cart.jpg)

- use cart

	Defines if the cart functionality is visible in the front end.

- Use components cart

	The component can display the current cart above each site. If you use the cart module you can disable the internal cart.

- show external cart link

	Defines if we show a link for each image which can direct the user to a product detail page. This will cause conflicts with the internal cart buttons so you should disable to internal cart feature or do some css magic to align both buttons in the right way.

- external cart link

	Defines a pattern for an external link. You can use the following placeholder: ${folder},${file} and ${fileBase} like this: http://www.foo.bar?category=${folder}&amp;sku=${fileBase}

- external cart link rel
	
	Defines the rel attribute of this link. By default this is nofollow so the search engine crawlers will not follow your external cart links.


## Checkout

![Configuration options](img/backend/backend_configuration_checkout.jpg)

- Currency Symbol

	The currency symbol you want to show in your store. 

- Currency Code
	
	The three letter currency code. Entering a wrong value here might cause issues with services like PayPal. While all money values allow to enter a separate currency the value configured in this field is used for now. So don't get confused. 

- Show T&C check box

	If set to yes the order submission requires the customer to set the T&C check box.

- Disclaimer

	The disclaimer of your store. It is displayed during the checkout and in the order confirmation mail. If this field is empty, the default text kicks in. 

- Address

	You merchant address data

- Footer Disclaimer

	If you want to display a message on every page which is produced by the component enter it here.

- Privacy Policy

	A simple link to the privacy policy page.

- Terms and Conditions

	A simple link to the terms & conditions page.

- Impress

	A simple link to the impress page.

## Social

![Configuration options](img/backend/backend_configuration_social.jpg)

- show social sharing button

	You can enable your visitors to post links to images in social media. In addition you can enable different sharing options with the dedicated buttons for each service. They should work out of the box, only the Facebook button needs additional configuration.

- Facebook App Id

	Specify a App Id for your Facebook application which is responsible for sharing the links to your images. Make sure the App is configured to work with your domain. Make sure the App is configured to work with your domain. The App need to have the permission photo_upload. 











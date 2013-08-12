# Configuration options {#configuration}

There are only a few things globally manageable. 

- Admin-Mail

	This address is used to send notification about comment and image request to. Don't forget to and an address here if you activate at least one of the options below. Otherwise your users will see errors while posing comments and requesting images. 

- use comments

	Defines if comments are enabled. 

- use cart

	Defines if the cart functionality is visible in the front end.

- show external cart link

	Defines if we show a link for each image which can direct the user to a product detail page. This will cause conflicts with the internal cart buttons so you should disable to internal cart feature or do some css magic to align both buttons in the right way.

- show social sharing button

	You can enable your visitors to post links to images in social media. In addition you can enable different sharing options with the dedicated buttons for each service. They should work out of the box, only the Facebook button needs additional configuration.

- Facebook App Id

	Specify a App Id for your Facebook application which is responsible for sharing the links to your images. Make sure the App is configured to work with your domain. Make sure the App is configured to work with your domain. The app need to have the permission photo_upload. 

- external cart link

	Defines a pattern for an external link. You can use the following placeholder: ${folder},${file} and ${fileBase} like this: http://www.foo.bar?category=${folder}&amp;sku=${fileBase}

- external cart link rel
	
	Defines the rel attribute of this link. By default this is nofollow so the search engine crawlers will not follow your external cart links.

- image quality

	Defines the output quality of every generated image. Default is 85% which is pretty good. Lower values will result in lower quality.

- use sharpening

	Enables or disables the sharping functionality. If image sharping is enabled it uses the matrix defined below.

- Sharpen Matrix

	You can define a matrix which is used to sharpen the generated images. By default this is [[-1,-1,-1],[-1,16,-1],[-1,-1,-1]] and it will do a good job. In case you want to have blurry or even sharper images this is the configuration parameter you'll have to touch. Please find the possible values here: http://php.net/manual/en/function.imageconvolution.php . In case you have no clue what this is about, don't touch it. After a change delete the cache otherwise you will see no result.

- Use Rendering Fallback

	On some servers the execution of script is not allowed in subfolders. The execution is necessary since images are delivered by a script and we don't want to run the whole joomla framework for every request. If you encounter issues with images that do not appear, you can use the fallback to use index.php for image delivering. This is `slow and should be not your long term solution`. Contact your provider to make sure php scripts can be executed in component folders. At least this would be great for the following scripts: components/com_eventgallery/helpers/image.php and blank.php.


![Configuration options](img/backend/configuration.jpg)
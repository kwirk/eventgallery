## Thumbs do not show up {#thumbs}

There are a couple of reasons why thumbs don't show up. At first you have to check if the thumbs are visible in the back end. Of so you it's most likely an issue which occurs in your browser since the server can render images. The following lists point you so some of the most common issues.

### Server

- `mod_secure`: Please `ask your provider` about possible issues with that. Doing this solved 99% of the issue.
- Error 500: PHP memory size too low. I have good experience with 128M. Depends on the size of your images
- do not use special characters in your file names like Umlaute, +, or things like this. Make your file names safe for the web.
- Error 500: the PHP function imageconvolution does not work on your server. For now one user reported this using PHP 5.3.8. After commenting out the line of code the gallery worked fine.
- install GD library for image processing
- issues with image sharping. Try to disable it using the components configuration dialog.
- PHP should have write permission to /images, /cache and /logs
- Picasa Images do not show up because the method get\_file\_content is not working. Check with your hosting provider to solve this issue. allow_url_fopen should be enabled.
- Error 500: PHP should be able to execute the script /components/com_eventgallery/helpers/image.php in order to display thumbs.
- try to use Use Rendering Fallback and contact your provider if this works for you so he can change the server settings.
- log file is too large: delete /logs/com_eventgallery.log.php

### Joomla Configuration

- Picasa albums do not work because your SEO-component strips out the @-sign from the URLs


### Browser

- JavaScript error occurred which prevents the whole site from executing JavaScript. Without JavaScript it no image will appear.
- To verify that there are no JavaScript errors on your page do this:
	- open the site in your browser
	- Chrome: press F12 to open the developer tools
	- switch to the Console tab
	- check if there are read warnings
	- if the console window is empty you're fine
- MooTools JavaScript library is not available
- your template loads old libraries like MooTools 1.2.5
- you have a fancy plugin installed which does not care about Joomla an pulls in jQuery without calling jQuery.noConlict() afterwards [jQuery noConlict Documentation](http://api.jquery.com/jQuery.noConflict/)
- conflict between original LazyLoad and the custom one which is shipped with event gallery (fixed with 2.6.3)
- conflict between original advanced MediaBox and the custom one (fixed with 2.6.4)


## Lightbox does not work {#lightbox}

- instead of opening an image in a lightbox it opens in a new windows: check for JavaScript errors on your page. Maybe you Joomla Template filters out MooTools, does not load jQuery in no conflict mode or is doing even worse stuff. 


## Images are not properly aligned {#imagealign}

- the CSS of your Joomla Template might influence the images 
- your images are too small to spread to the necessary width


## Folders get lost with the sync job {#folderlost}

- make sure your files&folder do not contain a dot in the name. This is not supported. Even if I would, this would cause issue with enabled URL rewriting.
- folder should contain at least one file


## Image-Upload does not work {#imageupload}

- 100% of the time the upload is refused by the server because of restrictive security configurations. Please contact your provider. They can fix it. In the meantime you can use FTP to upload the images to `/images/eventgallery/[your folder]` and hit the "Sync Database" button in the main tool bar of the Event Gallery component in the back end.

## Character Encoding does not work

- the database tables of event gallery are probably set to the wrong format. Use the following sql script to fix this: 

		alter table #__eventgallery_file convert to character set utf8 collate utf8_unicode_ci;
	    alter table #__eventgallery_folder convert to character set utf8 collate utf8_unicode_ci;
	    alter table #__eventgallery_comment convert to character set utf8 collate utf8_unicode_ci;
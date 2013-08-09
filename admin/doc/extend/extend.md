## Modify Ajax List {#ajaxlist}

If you want to display the thumbnails beside the big image you can simply let them float the way you need them. 

![Switch from two rows to two columns](img/frontend/event_ajax_list_css_custom.jpg)

## Modify Events List {#eventslist}

The number of elements per row is something you can change by simple CSS. There is not configuration option because with css you can use Media Queries to adjust the tiles based on the current view port.

A simple css definition would look like this. It will give you 4 item per row.

	div#events .item-container {
		width: 25%
	}
	
You can even use media queries to adapt the number of tiles per row based on the current view port. 

	div#events .item-container {
		width: 33.3333333333%;
	}


	@media (max-width: 900px) {
		div#events .item-container {
			width: 50%;		
		}
	}

	@media (max-width: 450px) {
		div#events .item-container {
			width: 100%;		
		}
	}

## Language Files {#language}

First of all install the necessary languages using the Language Manager of Joomla. This is independent from this component and a requirement for the next steps. To translate the gallery into your language you have several options. Let's assume you want to have the gallery translated to Spanish with the locale es-ES. The following options are available

- create/reuse the file `language/overrides/es-ES.override.ini` and put your translations into this file. You can change the content of this file using Joomlas language management in the back end.
- create a file `language\overrides\language\es-ES\es-ES.com_eventgallery.ini` and add your translations in there. (available since 2.6.4) 

With each new version it might be necessary to add new translation keys to your custom file. If you choose option 1 this would be possible without touching any file in the file system manually. You can use the Joomla back end to add the missing keys. Starting with 2.6.4 each installation or update will delete translation files from /language/xx_YY/. If you added your changes there make sure you migrate them to one of the options mentioned above. 


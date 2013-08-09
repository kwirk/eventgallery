# Link to the component {#LinkIt}

Creating links to the component is simple. Open the menu manager, create a new menu item and select the right view. The name of the component you want to link to is _Eventgallery_ so choose the right section and the available targets will appear. 

![Create a new menu item](img/backend/create_menu_item.jpg)

There are several views available:

- Cart

	Shows a page which contains the cart of the current user
	
- Checkout

	Displays the checkout page where the user can request his cart.

- Event - Ajax List

	Shows a specific event with the Ajax List view

	Basic Options
	- _Event_: the event you want to display

	Advanced Options
	- _show exif_ If EXIF information for an image are available this toggle switches them on or off. 
	- _show date_ You can toggle the appearance of the date for the events show within this menu item	
	- _Height of the thumbnails_ Defines the height of the thumbnails in the navigation bar.
	- _Number of thumbs per page_ defines how many thumbs you want to see on a page
	- _Number of thumbs on first page_ defines how many thumbs you want to see on the first page. Since there might be some additional text this number can be smaller than on other pages.

- Event - Pageable List

	Shows a specific event with the Pageable List view

	Basic Options
	- _Event_: the event you want to display

	Advanced Options
	- _show exif_ If EXIF information for an image are available this toggle switches them on or off. 
	- _show date_ You can toggle the appearance of the date for the events show within this menu item
	- _show hits_ I you use local images each visit of a single image page will be counted as a hit. This toggle switches the visibility of the current hit counter on or off.
	- _Height of the thumbnails_ defines the initial height of each row in the grid in pixel.
	- _Height jitter_ defines the maximum amount of pixel the row height can vary.
	- _Height of the first thumbnails_ defines the height in rows of the first photo in the list.

- Event - Image List

	Shows a specific event with the Image List view

	Basic Options
	- _Event_: the event you want to display

	Advanced Options
	- _show exif_ If EXIF information for an image are available this toggle switches them on or off. 
	- _show date_ You can toggle the appearance of the date for the events show within this menu item
	- _Height of the thumbnails_ defines the initial height of each row in the grid in pixel.
	- _Height jitter_ defines the maximum amount of pixel the row height can vary.
	- _Height of the first thumbnails_ defines the height in rows of the first photo in the list.

- Events - List

	Displays a list of events. 
	
	Basic Options
	- none

	Advanced Options
	- _Layout_ defines which layout to use for an event. You can configure the appearance of the event page with the options described above.
	- _Sort By_ defines the direction and the attribute which is used the sort the events.
	- _Intro Text_ defines a text for the events page. Usually displayed on top of the page. 
	- _Tags_ defines which tag a event need to get displayed on this list of events.
	- _Max events per Page_ How many events should we show per page until the paging bar will appear. 
	- _show exif_ If EXIF information for an image are available this toggle switches them on or off. 
	- _show event text_ Defines to show the text for an event on the event list. 
	- _show date_ You can toggle the appearance of the date for the events show within this menu item
	- _show hits_ I you use local images each visit of a single image page will be counted as a hit. This toggle switches the visibility of the current hit counter on or off.
	- _show image count_ Defines to display the overall number if images for an event on the events list
	- _show comment count_ Defines to display the overall number if comments for an image on the events list
	- _use full screen lightbox_ The normal lightbox would show up depending on the size of the image with a little border around. A full screen lightbox turn the screen completely black and shows the images.


	![Create a new menu item](img/backend/create_menu_item_advanced_options.jpg)
- Events - Simple List

	Displays a list of links to events
	
	- see _Events - List_ for configuration options.

This gallery component supports just a flat list of folders. If you need a navigation tree you're out of luck. But you can achieve a tree structure using tags and Joomla menu items. Create a menu structure which represents the structure you would like to have. Each menu item might link to the view of the event gallery you need. This is simple if you link directly to an event. If you want to have different list you can use tags to separate the lists. 

Example for such a structure: 

- Event1 -> Tags = nature
- Event2 -> Tags = nature
- Event3 -> Tags = architecture
- Event4 -> Tags = people, men
- Event4 -> Tags = people, men
- Event4 -> Tags = people, women
- Event4 -> Tags = people, children

You can arrange the events within the following menu structure:

- Menu Item 1 -> Event List with tag "nature"
- Menu Item 2 -> Event List with tag "architecture"
- Menu Item 3 -> Event List with tag "people"
	- Menu Item 4 -> Event List with tag "men"
	- Menu Item 5 -> Event List with tag "women"
	- Menu Item 6 -> Event List with tag "children"

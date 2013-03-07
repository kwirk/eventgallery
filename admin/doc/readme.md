# Eventgallery 

## Intro
It's time to introduce a new gallery component for Joomla. I guess you're already waiting for something like this since there is a real lack of such kind of components ;-)
Initially I build this component to show photos of different events to the audience. To where other components have folders this component calls them events. Because of this this component is called Event Gallery. 

Let's list all the features this gallery component provides:

- works with Joomla 3.0 and Joomla 2.5
- displays images from local folders and **Google Picasa**
- **cart & checkout** to allow the user to create a collection of images and request them from the photographer
- three different views for an image list
	- Image List

		This list type presents all images of an event as a simple long list. You can zoom into images using a lightbox.

	- Page able Image List

		While nearly identical to the Image List view this view provides a paging bar. Instead of opening each photo in a lightbox there is a dedicated page for every image. This page allows you to browser within the current event.

	- Ajax Image List

		If there is limited space the Ajax Image List is for you. It provides a split interface where you have a large image on one side and a pageable list of thumbs on the other side. 
- lightbox with integrated paging and support for *swipe gestures* on mobile

- comments - a user can comment a photo
- **deep links** - you can link from a menu directly to a specific event
- **tags** - you can assign tags to an event and use them to show events with specific tags in your event list only. 
- **responsive support** - the size of a image list changes, it'll recalculate the image layout to fit into the new page width

## Link to the component

Creating links to the component is simple. Open the menu manager, create a new menu item and select the right view. The name of the component you want to link to is _Eventgallery_ so choose the right section and the available targets will appear. 

![Create a new menu item](img/backend/create_menu_item.jpg)

There are several views available:

- Cart

	Shows a page which contains the cart of the current user
	
		Configuration options:
		- none

- Checkout

	Displays the checkout page where the user can request his cart.

		Configuration options:
		- none	

- Event - Ajax List

	Shows a specific event with the Ajax List view

		Configuration options:
		Basic Options
		- _Event_: the event you want to display

		Advanced Options
		- _Number of thumbs per page_ defines how many thumbs you want to see on a page
		- _Number of thumbs on first page_ defines how many thumbs you want to see on the first page. Since there might be some additional text this number can be smaller than on other pages.

- Event - Pageable List

	Shows a specific event with the Pageable List view

		Configuration options:

		Basic Options
		- _Event_: the event you want to display

		Advanced Options
		- _Height of the thumbnails_ defines the initial height of each row in the grid in pixel.
		- _Height jitter_ defines the maximum amount of pixel the row height can vary.
		- _Height of the first thumbnails_ defines the height in rows of the first photo in the list.

- Event - Image List

	Shows a specific event with the Image List view

		Configuration options:

		Basic Options
		- _Event_: the event you want to display

		Advanced Options
		- _Height of the thumbnails_ defines the initial height of each row in the grid in pixel.
		- _Height jitter_ defines the maximum amount of pixel the row height can vary.
		- _Height of the first thumbnails_ defines the height in rows of the first photo in the list.

- Events - List

	Displays a list of events. 

		Configuration options:
		
		Basic Options
		- none

		Advanced Options
		- _Layout_ defines which layout to use for an event. You can configure the appearance of the event page with the options described above.
		- _Intro Text_ defines a text for the events page. Usually displayed on top of the page. 
		- _Tags_ defines which tag a event need to get displayed on this list of events.
		- _Max events on Page_ How many events should we show? If you have 456 and set the number to 20, it'll show only 20. 
		- _Max big events_ Defines who many events we show as big event tiles.
		- _Number of Thumbnails_ how many thumbnails do we want to show per event?
		- _Thumbnail width_ defines the width of a thumbnail for each type of element
		- _Max middle events_ how many of the events should we show medium sized?
		- _Show Thumbnails_ guess what...
		- _show more link_ Show a link to the simple list where show all the available events. 


- Events - Simple List

	Displays a list of links to events

		Configuration options:
		- see _Events - List_


## Manage Events

## Manage Event

## Manage Files

## Manage Comments

## Configuration options
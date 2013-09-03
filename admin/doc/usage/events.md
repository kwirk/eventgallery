# Manage Events {#ManageEvents}

You can manage your events here. Aside of creating a new event you can set an event offline/online, upload files, open the file list and open the edit view. In addition there are three more buttons in the toolbar:

- Options
- Sync Database
- Clear Cache

For details check the overview section.



![Events page](img/backend/backend_events.jpg)


## Edit Event {#EditEvent}

The view let's you edit the details of an event. 

- Folder

	Specifies the folders name. If you want to add a Google Picasa album, use this syntax user@albumid. Avoid special characters in the folder name. Any name containing an @ will be interpreted as a Picasa album. 
	
- Picasa Key

	If you want to include a Picasa album which is not public but accessible using a special Picasa key, then add this key here. In case you pulled the URL from Google Plus prefix it with `Gv1sRg`. Otherwise the album will not appear. 

- User Groups

	Defines a set of user groups. If a user wants to see an event he needs to be part of one of the defined user groups. Otherwise he'll get a login page or a access denied screen.

- Password

	Set a password for each folder if you want to protect it somehow. If a user tries to access such a folder he need to enter a password. If you want to send out links to a password protected folder you can add this password directly to the link and nobody needs to be bothered by a password page. 

- Tags

	A comma separated list of tags you want the event to have.

- Date

	This date is shown in the front end for this event.

- Description

	This description is shown in the front end as the events name.

- Published

	Is this event visible in the front end?

- Cartable
	
	Defines if users can add images from this folder to the cart. Useful if you want to display image where it makes no sense for getting requests for.

- Image Set

	If you want to sell images from this event you need to select the image set you want to use. 

- Text

	You can add additional text to an event. This text is usually shown above the thumbnails in the front end.

- Others
	
	Use this tab to controll more settings for this event like the options for social sharing.

![Event page](img/backend/backend_event_create.jpg)


## Manage Files {#ManageFiles}

Once you created your event and uploaded some photos you may want to manage those files. Here is the right place for it. On this page you can sort, delete and modify the status of an image. If you want to change the title or the description of an image just click on "Title" or "Description" and enter your data to the two fields which appear. Hit the save button to store it in the database.

- published

	Defines if this image appears in the front end.

- comments allowed

	Defines if comments are allowed for this image.

- main image

	Should this image be used for the overview page in the front end? Multiple selections are possible, the image with the highest ranking will win.

- show in image list

	Sometimes you want show an image on the overview page but not in the actual image list. With this option you can hide the image on the list. It'll only appear on the overview page if it is defined as a main image.
	

The status of an photo can be changed by the button in the status column. By using the check boxes and the buttons in the toolbar you can perform an operation for multiple photos.

![Event page](img/backend/backend_files.jpg)


## Search Plugin

The Search Plugin integrates with the Joomla Search component and finds events. It indexes the following fields:

- event description
- event text
- event tags

## Smart Search Plugin

The Smart Search Plugin provides an integration with the Joomla Smart Search component. It helps to fill the search index with information about your events. For that it uses the following fields: 

- event description
- event text
- event tags

Make sure the content plugin is enabled to update the index once you change events in the back end. If you sync your file system with the database make sure you rebuild the search index manually. 

## Content Plugin

This plugin is useful is you want to add some images of an event to an article.

Syntax: ```{eventgallery event="foo" max_images=4 thumb_width=75}``` 

```max_images``` and ```thumb_width``` are optional. The value for the ```event``` parameter has to be the value of the folder name field of an event. Set ```max_images=-1``` to show all images of an event.


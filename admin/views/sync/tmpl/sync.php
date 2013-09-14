<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access'); 
$document = JFactory::getDocument();
$version =  new JVersion();
if ($version->isCompatible('3.0')) {

} else {
    $css=JURI::base().'components/com_eventgallery/media/css/legacy.css';
    $document->addStyleSheet($css);
}
?>

<progress id="syncprogress" value="0" max="100"></progress>

<div>
<?php
    foreach($this->folders as $folder) {
        echo '<div class="eventgallery-folder" data-status="open" data-folder="'.$folder.'">'.$folder.'</div>';
}
?>
</div>

<form action="index.php" method="post" name="adminForm" id="adminForm">
    <input type="hidden" name="option" value="com_eventgallery" />
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>


<style>
    .eventgallery-folder {
        width: 25%;
        float: left;
    }

    .success {
        color: green;
    }

    .deleted {
        color: red;
    }
</style>

<script>

(function() {

    var folderContainers  = new Array();
    var max = 0;

    function syncFolder() {

        updateProcess();

        if (folderContainers.length==0) {
            done();
            return;
        }

        var myElement = folderContainers.pop();
        var myRequest = new Request({
            url: '<?php echo JRoute::_('index.php?option=com_eventgallery&format=raw&task=sync.process&'.JSession::getFormToken().'=1', false);?>',
            method: 'get',
            onRequest: function(){
                myElement.set('html', 'loading...');
            },
            onSuccess: function(responseText){
                myElement.addClass('done');
                myElement.set('html', responseText);
                syncFolder();
            },
            onFailure: function(){
                myElement.addClass('failed');
                myElement.set('html', 'Sorry, your request failed :(');
            }
        });
        myRequest.send('folder='+myElement.getAttribute('data-folder'));
    }

    function start() {
        $$(".eventgallery-folder").each(function(item){
            folderContainers.push(item);
        }.bind(this));

        max = folderContainers.length;
        $('syncprogress').setAttribute('max', max);
        $('syncprogress').setAttribute('value', 0);


        syncFolder();

    }

    function updateProcess() {

        $('syncprogress').setAttribute('value', max-folderContainers.length);
    }

    function done() {
        alert('Done.');
    }

    window.addEvent('domready', function() {
        start();
    });


})();
</script>
<?php echo $this->file->folder?>/<?php echo $this->file->file?>
<br>
<?php echo JHTML::image(JURI::base().JRoute::_("../index.php?view=thumbnail&folder=".$this->file->folder."&file=".$this->file->file."&option=com_eventgallery"),'image');?>
<br>
Status des Bildes: <?php echo $this->file->published?"freigegeben":"gesperrt"?>
	

<script LANGUAGE="JavaScript">
<!--
    var count = 1;
    ID=window.setTimeout("update();",1000);
    function update()
    {
        if (count==0) {
        window.close();
        }
        document.form1.count.value = count--;
        ID=window.setTimeout("update();",1000);
    }
// -->
</script>

    <form name="form1">
    Fenster schlie�t in
        <input style="text-align:right ;background-color:#FFFF99; width:10px; font-size=16px; border:0px solid #333333;" type="text" name="count" value="4" size="4">
    Sekunden
    </form>

		
<?php $this->load->view('busycms/includes/head') ?>
<script type="text/javascript" src="<?php echo base_url() ?>busycms/editor/ckeditor.js"></script>
<script src="<?php echo base_url() ?>busycms/static/scripts/app.js"></script>
<script src="<?php echo base_url() ?>busycms/ui/jquery.ui.core.js"></script>
<script src="<?php echo base_url() ?>busycms/ui/jquery.ui.widget.js"></script>
<script src="<?php echo base_url() ?>busycms/ui/jquery.ui.mouse.js"></script>
<script src="<?php echo base_url() ?>busycms/ui/jquery.ui.sortable.js"></script>
<script>
    $(document).ready(function() {
        photos(<?php echo $news->id ?>);
        $.initialize();
        $.demo();        
    });
    
    function newcolorshow() {
        $('.colorsavebutton').fadeOut(1);
        $('#editcolor').fadeOut(500);        
        $('#editcontent').fadeOut(500,function(){ $('.eventbutton').fadeOut(1); $('.newcolorsavebutton').fadeIn(1); $('#addnewcolor').fadeIn(500); })
    }
    
    function eventinfo() {
        $('.colorsavebutton').fadeOut(1);
        $('ul.colorlist li b').css('font-weight','normal');
        $('.newcolorsavebutton').fadeOut(1);
        $('.eventbutton').fadeIn(1); 
        $('#addnewcolor').fadeOut(500);
        $('#editcolor').fadeOut(500);
        $('#editcontent').fadeIn(500);
        $('.leftmenu ul li a').css('font-weight','normal');
        $('#eventinfolink a').css('font-weight','bold');
    }
    
    function deleteimageapproval(id) {
        $('.notification .hide').click();
        txt='<br/>Delete is image <br/><br/><a style="color:#000;" href="javascript:void(0)" onclick="deleteimage('+id+')">Yes</a> or \n\
            <a style="color:#000;" href="javascript:void(0)" onclick="$(\'.notification .hide\').click();">No</a>';
                    $.notification ( 
                            {
                                title:      'Confirm',
                                content:    txt,
                                icon:       '!',
                                color:      '#000'
                            }
                        )
    }
    
    function deleteimage(id) {
        $.ajax({
        url: '<?php echo base_url() ?>busycms/deleteimagenews/'+id,
		  success: function(data) { 
                       $('#sezgin').html(data);
              }
		});
    }
    
    function photos(id) {
        $.ajax({
        url: '<?php echo base_url() ?>busycms/newsphotos/'+id,
		  success: function(data) {
                       $('#gallery').html(data);  
                       imagesortable();
              }
        });
    }    
    
    function edit(id) {
        $('#menu'+id).fadeOut(200,function() {
            $('#edit'+id).fadeIn(200);
        })
    }
    
    function imagesortable() {
        $(".images").sortable({ 
                opacity: 0.6, 
                cursor: 'move',
                update: function() {                
                var order = $(this).sortable("serialize") + '&action=updateRecordsListings&id=<?php echo $news->id ?>'; 
                $.post("<?php echo base_url() ?>busycms/newsimageorder", order, function(theResponse){
                        $("#sezgin").html(theResponse);
                }); 		
        }								  
        });
    }
</script>
</head>
<body>
    <?php $this->load->view('busycms/includes/header') ?>
    <div id="dashboard">
        <div class="scroll con eventslist" style="background:none;">
            <table style="display:none;">
                <tr>
                    <td>

                    </td>
                </tr>
            </table>
            <div class="top-title">    
                <div class="detail-title">
                    <?php
                    echo "<span>" . $news->title . "</span>";
                    ?>
                </div>
            </div>
            <div id="editnewsform_back"></div>
            <div class="editevent-left">
                <div id="eventimage">
                    <div align="center" style="padding:5px 0;">
                       Main Image
                    </div>
                    <?php if (strlen($news->image) == 0) { ?>
                        <img id="bigimage" src="<?php echo base_url() ?>busycms/images/add-photo-big.png" width="300" onclick="$('#myfile').click();" style="cursor:pointer;" />
                    <?php } else { ?>
                        <div class="eventmainimage" onclick="$('#myfile').click();" style="cursor:pointer;">
                            <img id="bigimage" src="<?php echo base_url() ?>uploads/<?php echo $news->image ?>" onclick="$('#myfile').click();" width="300" />                            
                        </div>
                    <?php } ?>
                    <div style="width:0px;height:0px;overflow:hidden;">
                        <form id="uploadimage" action="<?php echo base_url() ?>busycms/newsmainimageupload/<?php echo $news->id ?>" method="post" enctype="multipart/form-data" target="ajax">
                            <input name="resim" type="file" id="myfile" style="height:30px;" onchange="$('#uploadimage').submit();" />
                        </form>
                        <iframe id="ajax" name="ajax"></iframe>     
                    </div>
                </div>
                <!--                <div class="leftmenu">
                                    <ul>
                                        <li id="eventinfolink">
                                            <a href="javascript:void(0);" onclick="eventinfo();" style="font-weight:bold;">Product Info</a>
                                        </li>
                                        <li id="editcolorlink">
                                            <a href="javascript:void(0);">Colors</a>
                                            <span onclick="newcolorshow()">+ Add color</span>
                                        </li>
                                    </ul>
                                </div>
                                <div id="colors">                    
                                </div>-->
            </div>
            <div class="editevent-right">
                <div id="editcontent">
                    <form name="editnewsform" id="editnewsform" onSubmit="return false;">
                        <h1>Edit News</h1>
                        <div class="relative" style="height:20px;z-index:99999;">
                            <!-- <div style="position: absolute;right:200px;top:-40px;">
                                Left Image&nbsp;&nbsp;&nbsp;<input type="checkbox" name="left_image"<?php if ($news->left_image==1) { echo " checked"; } ?> />
                            </div> -->
                            <div style="position: absolute;right:0;top:-40px;">
                                Publish&nbsp;&nbsp;&nbsp;<input type="checkbox" name="publish"<?php if ($news->publish==1) { echo " checked"; } ?> />
                            </div>
                        </div>                                      
                        <b>Title of News</b><br/>
                        <span>Please enter the title of the news </span>
                        <input type="text" name="title" value="<?php echo $news->title ?>" />
                        <input type="hidden" name="id" value="<?php echo $news->id ?>" />
                        <input type="hidden" name="saveclose" id="saveclose" value="0">                                                                                               
                        <div class="clear"></div>
                        <br/>
                        <b>Subtitle of News</b><br/>
                        <span>Please enter the subtitle of the news </span>
                        <input type="text" name="subtitle" value="<?php echo $news->subtitle ?>" />
                        <br/>
                        <b>Date of News</b><br/>
                        <span>Please enter the date of the news </span>
                        <br/>
                        <div style="width:100px;display:inline-block;">
                        <select name="month">
                            <?php for($i=1;$i<=12;$i++) { ?>
                            <option<?php if ($i==$news->month) { echo ' selected'; } ?>><?php echo $i ?></option>
                            <?php }?>
                        </select>
                        </div>
                        <div style="width:100px;display:inline-block;">
                        <select name="year">
                            <?php for($i=date('Y');$i>=date('Y')-2;$i--) { ?>
                            <option<?php if ($i==$news->year) { echo ' selected'; } ?>><?php echo $i ?></option>
                            <?php }?>
                        </select>
                        </div>
                        <br/>                 
                        <br/>                           
                        <b>Link of News</b><br/>
                        <span>Please enter the link of the news </span>
                        <input type="text" name="link" value="<?php echo $news->link ?>" />                        
                        <br/>
                        <div style="display:none;">
                            <div class="clear"><hr/></div>
                            <br/>
                            <b>Title of News English</b><br/>
                            <span>Please enter the title of the news </span>
                            <input type="text" name="title2" value="<?php echo $news->title2 ?>" />
                            <div class="clear"></div>
                            <br/>
                            <b>Date of News English</b><br/>
                            <span>Please enter the date of the news </span>
                            <input type="text" name="date_view2" value="<?php echo $news->date_view2 ?>" />
                            <br/>                                            
                            
                        </div>
                        <div class="clear"></div>
                    </form><br/><br/>
                    <div align="right">
                        <button style="margin-right:20px;" onclick="location.href='<?php echo base_url() ?>busycms/news/'">Close</button>
                        <button class="eventbutton" onClick="submitform('busycms/','editnewsform'); return false;" style="right:150px;">Update</button>
                        <button class="eventbutton" onClick="$('#saveclose').val(1);submitform('busycms/','editnewsform'); return false;" style="right:150px;">Update and Close</button>
                    </div>                    
                </div>
                <div id="gallery" style="display:none;">
                </div>                
            </div>
            <div class="clear"></div>
            <div id="sezgin"></div>
        </div>     
    </div>
<?php $this->load->view('busycms/includes/footer') ?>
</body>
</html>
<?php $this->load->view('busycms/includes/head') ?>
<script type="text/javascript" src="<?php echo base_url() ?>busycms/editor/ckeditor.js"></script>
<script src="<?php echo base_url() ?>busycms/static/scripts/app.js"></script>
<script src="<?php echo base_url() ?>busycms/ui/jquery.ui.core.js"></script>
<script src="<?php echo base_url() ?>busycms/ui/jquery.ui.widget.js"></script>
<script src="<?php echo base_url() ?>busycms/ui/jquery.ui.mouse.js"></script>
<script src="<?php echo base_url() ?>busycms/ui/jquery.ui.sortable.js"></script>
<script>
    $(document).ready(function() {
        photos(<?php echo $people->id ?>);
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
        url: '<?php echo base_url() ?>busycms/deleteimageproject/'+id,
		  success: function(data) { 
                       $('#sezgin').html(data);
              }
		});
    }
    
    function photos(id) {
        $.ajax({
        url: '<?php echo base_url() ?>busycms/projectphotos/'+id,
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
                var order = $(this).sortable("serialize") + '&action=updateRecordsListings&id=<?php echo $people->id ?>'; 
                $.post("<?php echo base_url() ?>busycms/projectimageorder", order, function(theResponse){
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
                    echo "<span>" . $people->title . "</span>";
                    ?>
                </div>
            </div>
            <div id="editpeopleform_back"></div>
            <div class="editevent-left">
                <div id="eventimage">
                    <div align="center" style="padding:5px 0;">
                       Image
                    </div>
                    <?php if (strlen($people->image) == 0) { ?>
                        <img id="bigimage" src="<?php echo base_url() ?>busycms/images/add-photo-big.png" width="300" onclick="$('#myfile').click();" style="cursor:pointer;" />
                    <?php } else { ?>
                        <div class="eventmainimage" onclick="$('#myfile').click();" style="cursor:pointer;">
                            <img id="bigimage" src="<?php echo base_url() ?>uploads/<?php echo $people->image ?>" onclick="$('#myfile').click();" width="300" />                            
                        </div>
                    <?php } ?>                    
                </div>
                <div style="width:0px;height:0px;overflow:hidden;">
                    <form id="uploadimage" action="<?php echo base_url() ?>busycms/peoplemainimageupload/<?php echo $people->id ?>" method="post" enctype="multipart/form-data" target="ajax">
                        <input name="resim" type="file" id="myfile" style="height:30px;" onchange="$('#uploadimage').submit();" />
                    </form>
                    <iframe id="ajax" name="ajax"></iframe>     
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
                    <form name="editpeopleform" id="editpeopleform" onSubmit="return false;">
                        <h1>General</h1>
                        <div class="relative" style="height:20px;z-index:99999;">
                            <div style="position: absolute;right:0;top:-40px;">
                                Publish&nbsp;&nbsp;&nbsp;<input type="checkbox" name="publish"<?php if ($people->publish==1) { echo " checked"; } ?> />
                            </div>
                        </div>              
                        <!-- <b>Categories</b><br/>
                        <span>Select the Categories</span>
                        <div>
                        <select name="categories[]" multiple="multiple">
                            <option selected value="0">Select category</option>
                                <?php
                                $categories=explode(',',$people->categories);
                                $sql2 = $this->db->query("select * from customer_categories order by id asc");
                                if ($sql2->num_rows() > 0) { echo "<ul>"; }
                                foreach ($sql2->result() as $category) { ?>
                                <option value="<?php echo $category->id ?>" <?php if (in_array($category->id,$categories)) { echo "selected=\"selected\""; } ?>>
                                    <?php echo $category->title ?>
                                </option>
                                <?php }  ?>
                        </select>
                        </div>
                        <br/> -->
                        
                        <b>Name of People</b><br/>
                        <span>Please enter the name of the people </span>
                        <input type="text" name="title" value="<?php echo $people->title ?>" />
                        <input type="hidden" name="id" value="<?php echo $people->id ?>" />
                        <input type="hidden" name="saveclose" id="saveclose" value="0">                                                                                                                       
                        <div class="clear"></div>
                        <br/>
                        <b>Instagram link of People</b><br/>
                        <span>Please enter the instagram link of the people </span>
                        <input type="text" name="instagram" value="<?php echo $people->instagram ?>" />
                    </form><br/><br/>
                    <div align="right">
                        <button style="margin-right:20px;" onclick="location.href='<?php echo base_url() ?>busycms/projects/'">Close</button>
                        <button class="eventbutton" onClick="submitform('busycms/','editpeopleform'); return false;" style="right:150px;">Update</button>
                        <button class="eventbutton" onClick="$('#saveclose').val(1);submitform('busycms/','editpeopleform'); return false;" style="right:150px;">Update and Close</button>
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
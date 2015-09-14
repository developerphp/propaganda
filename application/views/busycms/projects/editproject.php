<?php $this->load->view('busycms/includes/head') ?>
<script type="text/javascript" src="<?php echo base_url() ?>busycms/editor/ckeditor.js"></script>
<script src="<?php echo base_url() ?>busycms/static/scripts/app.js"></script>
<script src="<?php echo base_url() ?>busycms/ui/jquery.ui.core.js"></script>
<script src="<?php echo base_url() ?>busycms/ui/jquery.ui.widget.js"></script>
<script src="<?php echo base_url() ?>busycms/ui/jquery.ui.mouse.js"></script>
<script src="<?php echo base_url() ?>busycms/ui/jquery.ui.sortable.js"></script>
<script>
    $(document).ready(function() {
        photos(<?php echo $project->id ?>);
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
                var order = $(this).sortable("serialize") + '&action=updateRecordsListings&id=<?php echo $project->id ?>'; 
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
                    echo "<span>" . $project->title . "</span>";
                    ?>
                </div>
            </div>
            <div id="editprojectform_back"></div>
            <div class="editevent-left">
                <div id="eventimage">

                    <div align="center" style="padding:5px 0;">
                       Cover Image 1024px - 576px
                    </div>
                    <?php if (strlen($project->cover_image) == 0) { ?>
                        <img id="cover_image" src="<?php echo base_url() ?>busycms/images/add-photo-big.png" width="300" onclick="$('#myfile2').click();" style="cursor:pointer;" />
                    <?php } else { ?>
                        <div class="eventmainimage" onclick="$('#myfile').click();" style="cursor:pointer;">
                            <img id="cover_image" src="<?php echo base_url() ?>uploads/<?php echo $project->cover_image ?>" onclick="$('#myfile2').click();" width="300" />                            
                        </div>
                    <?php } ?>
                    <br/><br/>
                    <div align="center" style="padding:5px 0;">
                       Detail Image 1000px - 1000px
                    </div>
                    <?php if (strlen($project->image) == 0) { ?>
                        <img id="bigimage" src="<?php echo base_url() ?>busycms/images/add-photo-big.png" width="300" onclick="$('#myfile').click();" style="cursor:pointer;" />
                    <?php } else { ?>
                        <div class="eventmainimage" onclick="$('#myfile').click();" style="cursor:pointer;">
                            <img id="bigimage" src="<?php echo base_url() ?>uploads/<?php echo $project->image ?>" onclick="$('#myfile').click();" width="300" />                            
                        </div>
                    <?php } ?>
                    <div style="width:0px;height:0px;overflow:hidden;">
                        <form id="uploadimage" action="<?php echo base_url() ?>busycms/projectmainimageupload/<?php echo $project->id ?>" method="post" enctype="multipart/form-data" target="ajax">
                            <input name="resim" type="file" id="myfile" style="height:30px;" onchange="$('#uploadimage').submit();" />
                        </form>
                        <iframe id="ajax" name="ajax"></iframe>     

                        <form id="uploadimage2" action="<?php echo base_url() ?>busycms/projectcoverimageupload/<?php echo $project->id ?>" method="post" enctype="multipart/form-data" target="ajax2">
                            <input name="resim" type="file" id="myfile2" style="height:30px;" onchange="$('#uploadimage2').submit();" />
                        </form>
                        <iframe id="ajax2" name="ajax2"></iframe>     
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
                    <form name="editprojectform" id="editprojectform" onSubmit="return false;">
                        <h1>General</h1>
                        <div class="relative" style="height:20px;z-index:99999;">
                            <div style="position: absolute;right:0;top:-40px;">
                                Publish&nbsp;&nbsp;&nbsp;<input type="checkbox" name="publish"<?php if ($project->publish==1) { echo " checked"; } ?> />
                            </div>
                        </div>              
                        <div class="relative" style="height:100px;">
                        <b>Categories</b><br/>
                        <span>Select the Categories</span>
                        <div style="position:absolute;width:100%;z-index:10;">
                        <select name="categories[]" multiple="multiple">
                            <option selected value="0">Select category</option>
                                <?php
                                $categories=explode(',',$project->categories);
                                $sql2 = $this->db->query("select * from project_categories order by id asc");
                                if ($sql2->num_rows() > 0) { echo "<ul>"; }
                                foreach ($sql2->result() as $category) { ?>
                                <option value="<?php echo $category->id ?>" <?php if (in_array($category->id,$categories)) { echo "selected=\"selected\""; } ?>>
                                    <?php echo $category->title ?>
                                </option>
                                <?php }  ?>
                        </select>
                        </div>
                        </div>                        
                        <div>                            
                            <div style="width:40%;float:left;">
                                <b>Select customer</b><br/>
                                <span>Customer</span>
                                <select name="customer">
                                    <option value="">Select Customer</option>
                                    <?php 
                                    $sql=$this->db->query("select * from customers order by title asc");
                                    foreach($sql->result() as $customer) { ?>
                                        <option value="<?php echo $customer->id ?>"<?php if ($customer->id==$project->customer) { echo ' selected="selected"'; } ?>><?php echo $customer->title ?></option>
                                    <?php }?>
                                </select>
                            </div>                            
                            <div style="width:25%;float:left;margin-left:5%;">
                                <b>Project Year</b><br/>
                                <span>Year</span>
                                <select name="year">
                                    <?php for ($i=1980;$i<=date("Y");$i++) {?>
                                    <option<?php if ($project->project_year==$i) { echo " selected"; } ?>><?php echo $i ?></option>
                                    <?php }?>
                                </select>
                            </div> 
                            <div class="clear"></div>
                        </div>
                        <!-- <br/>
                        <b>Sub Brand</b><br/>
                        <span>Brand</span>
                        <input type="text" name="subbrand" value="<?php echo $project->subbrand ?>" />                                            -->
                        <br/>
                        <b>Name of Project</b><br/>
                        <span>Please enter the name of the project </span>
                        <input type="text" name="title" value="<?php echo $project->title ?>" />
                        <input type="hidden" name="id" value="<?php echo $project->id ?>" />
                        <input type="hidden" name="saveclose" id="saveclose" value="0">                                                                                               
                        <div class="clear"></div>
                        <br/>                         
                        <b>Content of Project</b><br/>
                        <span>Please enter the project content</span>
                        <textarea id="editor2" name="content"><?php echo $project->content ?></textarea>
                        <script type="text/javascript">
                            CKEDITOR.replace( 'editor2',
                            {
                                extraPlugins : 'uicolor',
                                uiColor: '#dee9e9',
                                toolbar :
                                    [
                                    [ 'Format', 'Bold', 'Italic', '-', 'NumberedList' , 'BulletedList', '-', 'Link', 'Unlink' ,'-', 'PageBreak', 'RemoveFormat']
                                ]
                            });
                        </script>
                        <br/>
                        <div class="clear"><hr/></div>
                        <br/>
                        <b>Name of Project English</b><br/>
                        <span>Please enter the name of the project </span>
                        <input type="text" name="title2" value="<?php echo $project->title2 ?>" />                        
                        <div class="clear"></div>
                        <br/>
                        <b>Content of Project English</b><br/>
                        <span>Please enter the project content</span>
                        <textarea id="editor1" name="content2"><?php echo $project->content2 ?></textarea>
                        <script type="text/javascript">
                            CKEDITOR.replace( 'editor1',
                            {
                                extraPlugins : 'uicolor',
                                uiColor: '#dee9e9',
                                toolbar :
                                    [
                                    [ 'Format', 'Bold', 'Italic', '-', 'NumberedList' , 'BulletedList', '-', 'Link', 'Unlink' ,'-', 'PageBreak', 'RemoveFormat']
                                ]
                            });
                        </script>
                        <div class="clear"></div>
                        <br/><br/>
                        <h2>Videos</h2>
                        <?php 
                        $sql=$this->db->query("select * from projectvideos where project_id=".$project->id." order by id asc");
                        foreach($sql->result() as $video) { 
                        
                        // $url = $video->video_url;
                        // preg_match(
                        //         '/\/\/(www\.)?vimeo.com\/(\d+)($|\/)/',
                        //         $url,
                        //         $matches
                        //     );
                        // $id = $matches[2];  
                        // $width = '640';
                        // $height = '360';    
                        // echo '<div class="vimeo-article"><iframe src="http://player.vimeo.com/video/'.$id.'?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;color=ffffff" width="'.$width.'" height="'.$height.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>';

                        ?>
                            <div class="video" style="margin:10px 0;">
                                <input type="text" name="videos[]" value="<?php echo $video->video_url ?>" />
                            </div>
                        <?php }?>
                        <div id="videoslast"></div>
                        <div align="right">
                            <a href="javascript:void(0)" onclick="$('#videoslast').before($('#videoClone').clone())">+ add video</a>
                        </div>
                        <div style="display:none;">                            
                            <div class="video" id="videoClone" style="margin:10px 0;">
                                <input type="text" name="videos[]" />
                            </div>
                        </div>
                        <br/><br/>
                        <h2>Sounds</h2>
                        <?php 
                        $sql=$this->db->query("select * from projectsounds where project_id=".$project->id." order by id asc");
                        foreach($sql->result() as $sound) { ?>
                        <!-- <iframe width="100%" height="166" scrolling="no" frameborder="no"src="https://w.soundcloud.com/player/?url=<?php echo $sound->sound_url ?>&amp;color=ff5500&amp;auto_play=false&amp;hide_related=false&amp;show_artwork=true&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false"></iframe> -->
                            <div class="video" style="margin:10px 0;">
                                <input type="text" name="sounds[]" value="<?php echo $sound->sound_url ?>" />
                            </div>
                        <?php }?>
                        <div id="soundlast"></div>
                        <div align="right">
                            <a href="javascript:void(0)" onclick="$('#soundlast').before($('#soundClone').clone())">+ add sound</a>
                        </div>
                        <div style="display:none;">                            
                            <div class="video" id="soundClone" style="margin:10px 0;">
                                <input type="text" name="sounds[]" />
                            </div>
                        </div>
                    </form><br/><br/>
                    <div align="right">
                        <button style="margin-right:20px;" onclick="location.href='<?php echo base_url() ?>busycms/projects/'">Close</button>
                        <button class="eventbutton" onClick="CKEDITOR.instances['editor1'].updateElement();CKEDITOR.instances['editor2'].updateElement();submitform('busycms/','editprojectform'); return false;" style="right:150px;">Update</button>
                        <button class="eventbutton" onClick="CKEDITOR.instances['editor1'].updateElement();CKEDITOR.instances['editor2'].updateElement();$('#saveclose').val(1);submitform('busycms/','editprojectform'); return false;" style="right:150px;">Update and Close</button>
                    </div>                    
                </div>
                <div id="gallery">
                </div>                
            </div>
            <div class="clear"></div>
            <div id="sezgin"></div>
        </div>     
    </div>
<?php $this->load->view('busycms/includes/footer') ?>
</body>
</html>
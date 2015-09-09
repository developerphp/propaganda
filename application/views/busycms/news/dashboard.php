<?php $this->load->view('busycms/includes/head') ?>
<script src="<?php echo base_url() ?>busycms/ui/jquery.ui.core.js"></script>
<script src="<?php echo base_url() ?>busycms/ui/jquery.ui.widget.js"></script>
<script src="<?php echo base_url() ?>busycms/ui/jquery.ui.mouse.js"></script>
<script src="<?php echo base_url() ?>busycms/ui/jquery.ui.sortable.js"></script>

<script>
    
    function newsadd()
    {
        $.fn.modal({
            theme:      "white",
            width:      "100px",
            height:     "100px",
            layout:     "elastic",
            url:        "<?php echo base_url() ?>busycms/newsadd/",
            padding:    "50px"
            //animation:  "flipInX"
        });
    }

    $(function() {
        
        $(".news").sortable({ 
                opacity: 0.6, 
                cursor: 'move',
                update: function() {                
                var order = $(this).sortable("serialize") + '&action=updateRecordsListings'; 
                $.post("<?php echo base_url() ?>busycms/newsorder", order, function(theResponse){
                        $("#sezgin").html(theResponse);
                }); 		
        }								  
        });
        
    });

    $(document).ready(function() {
            $.initialize();
            $.demo();
    });

    function deletedo(id)
    {
        $.ajax({
        url: '<?php echo base_url() ?>busycms/newsdelete/'+id,
        success: function(data) { $('#sezgin').html(data); }
                });
    }
    
    function deletenews(id) {
        $('.notification .hide').click();
        txt='<br/>Delete is news <br/><br/><a style="color:#000;" href="javascript:void(0)" onclick="deletedo('+id+')">Yes</a> or \n\
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

</script>

<script>
    function deletebutton(id) {
        $('.notification .hide').click();
        txt='<br/>Delete is post <br/><br/><a style="color:#000;" href="javascript:void(0)" onclick="deletemenu('+id+')">Yes</a> or \n\
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
</script>

</head>
<body>
    <?php $this->load->view('busycms/includes/header') ?>
    <div id="dashboard">
        <div class="scroll con">
            <div class="section padding" title="User Interface" id="ui" style="display: block;padding-bottom:1px;">
            </div>            
                <div class="editevent-right" style="padding:0px 0px;width:100%;">
                    <div class="product-section projects_list_title" id="list">
                        <div class="top-title" align="right">
    <!--                        <input type="text" name="search" class="search_user" />-->
                            &nbsp;
                            <button onclick="newsadd()">Add News</button>                            
                            <div class="clear"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                    <div id="projectslist">
                        <form id="scheduleform" name="scheduleform" onsubmit="return false;">
                            <ul class="news">
                                <?php 
                                $sql=$this->db->query("select * from news order by reorder asc");
                                foreach($sql->result() as $news) { ?>
                               <li id="news_<?php echo $news->id ?>" class="<?php if ($news->publish==1) { echo "publish"; } else { echo "unpublish"; } ?>">
                                   <div class="image">
                                            <img src="<?php echo base_url() ?>uploads/thumb_<?php echo $news->image ?>" />
                                   </div>
                                   <div class="project_menu">
                                       <div>
                                           <span><?php echo $news->title ?></span>
                                           <a href="<?php echo base_url() ?>busycms/newsedit/<?php echo $news->id ?>">8</a>
                                           <a href="javascript:void(0)" onclick="deletenews(<?php echo $news->id ?>)">X</a>                                           
                                       </div>
                                   </div>
                               </li> 
                               <?php }?>
                            </ul>
                        </form>
                    </div>
                </div>
                <div class="clear" style="height:50px;"></div>
            </div>
            <input type="hidden" name="orderkatid" value="" id="orderkatid" />
        </div>
    <div id="sezgin"></div>   
<?php $this->load->view('busycms/includes/footer') ?>
</body>
</html>
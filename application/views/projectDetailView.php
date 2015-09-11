<?php $this->load->view('includes/head') ?>

</head>

<body>
   <?php $this->load->view('includes/header') ?>

   <div class="fixed_top"></div>
    <div class="fixed_bottom"></div>
    
    <div class="fixed_box">
        <div class="bg" style="background-image: url('<?php echo base_url("uploads/".$project['image']) ?>');">
        </div>
    </div>
    
    <div class="right_box">

        <div class="boxes">
            <div class="txt">

                <div class="logo"> 
                    <?php 
                    $sql=$this->db->query("select * from customers where id=".$this->db->escape($project['customer'])."");
                    foreach($sql->result() as $customer) {
                        $customer_name=$customer->title; ?>
                    <!-- <img src="<?php echo base_url('uploads/'.$customer->image) ?>"> -->
                    <?php }?>
                </div>
                <span class="year"><?php echo $project['project_year'] ?></span>
                <span class="title"><?php echo $customer_name ?></span>
                <span class="title"><?php echo $project['subbrand'.$this->lang->line('dil')] ?></span>
                <span class="alt_title"><?php echo $project['title'.$this->lang->line('dil')] ?></span>
                <span class="small"><?php echo $project['content'.$this->lang->line('dil')] ?></span>
                <?php $sql=$this->db->query("select * from projectvideos where project_id=".$project["id"]." order by id asc");
                foreach($sql->result() as $video) {
                ?>
                <a class="button fancybox-media" href="<?php echo $video->video_url ?>" rel="media-gallery">
                    VIDEO
                </a>
                <?php } ?>
            </div>
            <div class="gallery">
            <?php 
            $sql=$this->db->query("select * from project_images where project_id=".$project["id"]." order by id asc");
            foreach($sql->result() as $image) {
            ?>
                <a class="fancybox" href="<?php echo base_url('uploads/'.$image->image) ?>" data-fancybox-group="gallery" title="Photo1">
                    <div class="tint"></div>
                    <img src="<?php echo base_url('uploads/'.$image->image) ?>" alt="">
                </a>
            <?php }?>
            </div>
        </div>
        <div class="projects_box">
            <div class="title_box">
                <div class="line"></div>
                <div><span class="pink_txt">DİĞER</span> FİLMLER</div>
            </div>
            <?php 
            $sql=$this->db->query("select 
                projects.title,projects.id,projects.image,projects.project_year,projects.publish,projects.reorder,projects.subbrand,
                customers.id as cid,customers.title as customer_name,customers.image_gray
             from projects,customers where customers.id=projects.customer and projects.publish=1 and projects.customer=".$project['customer']." order by projects.reorder desc");
            foreach($sql->result() as $p) {
            ?>
            <a href="<?php echo base_url($this->lang->line('lang').'projects/detail/'.$p->id) ?>">
            <div class="prev_next" style="background-image: url(<?php echo base_url('uploads/thumb_'.$p->image) ?>);">            
                <div class="icon"></div>
                <div class="text">
                    <span><?php echo $p->project_year.' '.$p->customer_name.' '.$p->subbrand ?></span>
                    <span class="alt_title"><?php echo $p->title ?></span>
                </div>            
            </div>
            </a>
            <?php } ?>
            <div class="more_box">
                <a href="<?php echo base_url($this->lang->line('lang').'projects') ?>" class="button">TÜM İŞLER</a>
            </div>

            <div class="logo_box">
                <div>
                    <div class="logo"></div>
                </div>
            </div> 
        </div>       
    </div>
    

<?php $this->load->view('includes/scripts.php') ?>
</body>
</html>

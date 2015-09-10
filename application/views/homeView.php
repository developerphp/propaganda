<?php $this->load->view('includes/head') ?>

</head>

<body>
   <?php $this->load->view('includes/header') ?>

    <div class="fixed_top"></div>
    <div class="fixed_bottom"></div>

    <div class="home_box">
        <div class="home_video">
            <video id="HomeVideo" autoplay muted>
                 <source src="<?php echo base_url() ?>assets/showreel.mp4" type="video/mp4">
            </video>
            <a class="logo fancybox-media" href="https://vimeo.com/137252466" rel="media-gallery"></a>
            <div class="v_placeholder">
                <img src="<?php echo base_url() ?>assets/img/video.jpg" width="100%" alt="video">
            </div>
        </div>
        <div class="project_boxes">
            <?php 
            $sql=$this->db->query("select 
                projects.title,projects.id,projects.image,projects.project_year,projects.publish,projects.reorder,projects.subbrand,
                customers.id as cid,customers.title as customer_name,customers.image_gray
             from projects,customers where customers.id=projects.customer and projects.publish=1 order by projects.reorder desc limit 0,3");
            foreach($sql->result() as $project) {
            ?>
            <a href="<?php echo base_url($this->lang->line('lang').'projects/detail/'.$project->id) ?>">
                <div class="box" style="background-image: url(<?php echo base_url('uploads/'.$project->image) ?>);">
                    <div class="txt">
                        <span><?php echo $project->project_year.' '.$project->customer_name.' '.$project->subbrand ?></span>
                        <span class="desc"><?php echo $project->title ?></span>
                    </div>
                     <div class="logo"><img src="<?php echo base_url('uploads/'.$project->image_gray) ?>" alt="logo"></div>
                </div>
            </a>
            <?php }?>
        </div>
        <div class="news_boxes">
            <div class="title_box">
                <div class="logo"><img src="<?php echo base_url() ?>assets/img/logo.png" alt="logo"></div>
                <div class="title">
                    <span>HABERLER</span>
                </div>
            </div>
        <?php 
        $i=1;
        $sql=$this->db->query("select * from news where publish=1 order by reorder asc limit 0,3");
        foreach($sql->result() as $news) {
        ?>
        <a href="<?php echo $news->link ?>" target="_blank">
            <div class="box">
                <div class="bg" style="background-image: url(<?php echo base_url('uploads/'.$news->image) ?>);"></div>
                <div class="desc <?php if ($i%2<>0) { echo 'wh_back'; } else { echo 'dg_back'; } ?>">
                    <div class="txt<?php if ($i%2==0) { echo ' darkGrey_txt'; } ?>">
                        <span class="pink_txt title_color"><?php echo $news->title ?></span>
                        <span><?php echo $news->subtitle ?></span>
                    </div>
                </div>
            </div>
        </a>
        <?php $i++; }?>
        </div>        
        <div class="footer_box">
            <div class="footer">
                 <div class="box">
                    İSTİKLAL CAD.<br>
                    <span class="grey_txt">MISIR APT.</span><br>
                    NO.311 KAT 6<br>
                    BEYOĞLU 34100<br>
                    İSTANBUL<br><br>
                    <span class="grey_txt">TEL:</span> <a href="tel:902122526500">+90<br> 212 252 6500</a><br><br>
                    <span class="grey_txt">FAX:</span> <a href="tel:902122521666">+90<br> 212 252 1666</a>
                 </div>
                 <a class="email" href="mailto:merhaba@propaganda.com.tr">MERHABA@<span class="grey_txt">PROPAGANDA.COM.TR</span></a>
            </div>
            <div class="about_us">
                <div class="box">
                    <div class="title">BİZ</div>
                    PROPAGANDAYI GÖRMEK İÇİN BUTONA BASIN!
                    <a href="<?php echo base_url($this->lang->line('lang').'team') ?>" class="email">DAHA FAZLA</a>
                </div>
            </div> 
        </div>
    </div>

<?php $this->load->view('includes/scripts.php') ?>

<script type="text/javascript">
var vid = document.getElementById("HomeVideo");
vid.loop = true;
</script>

</body>
</html>

<?php $this->load->view('includes/head') ?>

</head>

<body>
   <?php $this->load->view('includes/header') ?>

   <div class="fixed_top"></div>
    <div class="fixed_bottom"></div>

    <div class="fixed_box">
        <div class="bg" style="background-image: url('<?php echo base_url(); ?>assets/img/banners/p<?php echo rand(1,43) ?>.jpg');">
        </div>
    </div>
    
    <div class="right_box team_box">

        <div class="boxes">
            <div class="logo"></div>
            <div class="members">
                <?php 
                $sql=$this->db->query("select * from peoples where publish=1 order by reorder asc");
                foreach($sql->result() as $people) {
                ?>
                <div class="member" style="background-image: url(<?php echo base_url('uploads/thumb_'.$people->image) ?>)">
                    <span class="name"><?php echo $people->title ?>
                        <?php if (strlen($people->instagram)>0) { ?>
                        <a href="<?php echo $people->instagram ?>" target="_blank" class="icon"></a>
                        <?php }?>
                    </span>
                </div>
                <?php }?>
            </div>
        <div class="footer detail">
            <div class="right">
                <div class="r_left">
                    ŞAHKULU MAH. İLK BELEDİYE CAD.<br>
                    <span class="grey_txt">VURAL ARIKAN APT.</span><br>
                    NO 5 DAİRE 4-5<br>
                    ŞİŞHANE BEYOĞLU<br>
                    İSTANBUL<br><br>
                    <span class="grey_txt">TEL:</span> <a href="tel:+902122526500">+90<br> 212 252 6500</a><br><br>
                    <span class="grey_txt">FAX:</span>+90<br> 212 252 1666
                </div>
                <div class="r_right">
                    <a class="email" href="mailto:merhaba@propaganda.com.tr">MERHABA@<span class="grey_txt">PROPAGANDA.COM.TR</span></a><br><br>
                </div>
            </div>
        </div>
            
    </div>
    

<?php $this->load->view('includes/scripts.php') ?>
</body>
</html>

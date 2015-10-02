<?php $this->load->view('includes/head') ?>
<link href="<?php echo base_url() ?>assets/css/homepage.css" type="text/css" rel="stylesheet">

</head>

<body>
   <?php $this->load->view('includes/header') ?>

   <div class="fixed_top"></div>
    <div class="fixed_bottom"></div>
    
    <div class="fixed_box">
        <div class="bg" style="background-image: url('<?php echo base_url(); ?>assets/img/banners/p<?php echo rand(1,43) ?>.jpg');">
        </div>
    </div>
    
    <div class="right_box team_box brands_box">

        <div class="boxes">
            <div class="logo"></div>
            <div class="members">
                <?php 
     //            $sql=$this->db->query("select 
     //    projects.title,projects.id as pid,projects.customer,
     //    customers.id as cid,customers.title as customer_name,customers.image_gray as logo , customers.reorder
     // from projects,customers where customers.id=projects.customer and customers.publish=1  group by projects.customer order by customers.reorder desc");
                $sql=$this->db->query("select * from customers where publish=1 order by reorder desc");
                foreach($sql->result() as $customer) {
                ?>
                <div class="member">
                    <img src="<?php echo base_url('uploads/'.$customer->image_gray); ?>" alt="logo">
                </div>
                <?php }?>
            </div>
        <div class="footer detail">
            <div class="right">
                <div class="r_left">
                    İSTİKLAL CAD.<br>
                    <span class="grey_txt">MISIR APT.</span><br>
                    NO.311 KAT 6<br>
                    BEYOĞLU 34100<br>
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
    </div>

<?php $this->load->view('includes/scripts.php') ?>
</body>
</html>

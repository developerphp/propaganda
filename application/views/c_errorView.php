<?php $this->load->view('includes/head') ?>

</head>

<body>
   <?php $this->load->view('includes/header') ?>

    <div class="fixed_top"></div>
    <div class="fixed_bottom"></div>

    <div class="fourofour" style="background-image: url(<?php echo base_url() ?>/assets/img/404_bg.jpg)">
        <a href="<?php echo base_url() ?>">
        	<img src="<?php echo base_url() ?>assets/img/404.png" alt="404">
        </a>
    </div>

<?php $this->load->view('includes/scripts.php') ?>

</body>
</html>

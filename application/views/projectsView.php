<?php $this->load->view('includes/head') ?>
<link href="<?php echo base_url() ?>assets/css/homepage.css" type="text/css" rel="stylesheet">

</head>

<body class="projects_page">
   <?php $this->load->view('includes/header') ?>

   <div class="fixed_bottom"></div>
   <div class="fixed_top"></div>

    <?php 
    $i=1;
    $renk1="dark";
    $renk2="dark";
    $class="dark";
    $sql=$this->db->query("select 
        projects.title,projects.id,projects.cover_image as image,projects.project_year,projects.publish,projects.reorder,projects.subbrand,projects.content,
        customers.id as cid,customers.title as customer_name,customers.image as customer_logo
     from projects,customers where customers.id=projects.customer and projects.publish=1 order by projects.reorder desc");
    foreach($sql->result_array() as $project) {
        if ($i<=3) {
            if ($i%2==0) { ?>
                <div class="works_row">
                    <a href="<?php echo base_url($this->lang->line('lang').'projects/detail/'.$project['id']) ?>">
                        <div class="boxes dark square desktop_box">
                             <div class="txt">
                                <span class="year"><?php echo $project["project_year"] ?></span>
                                <span class="title">
                                    <?php echo $project["customer_name".$this->lang->line('dil')] ?>
                                </span>
                                <span class="alt_title"><?php echo $project["title".$this->lang->line('dil')] ?></span>
                                <span class="button">İNCELE</span>
                            </div>
                         </div>
                         <div class="boxes square">
                            <div class="bg" style="background-image: url(<?php echo base_url('uploads/'.$project['image']) ?>);">
                            </div>
                        </div>
                        <div class="boxes dark square mobile_box">
                             <div class="txt">
                                <span class="year"><?php echo $project["project_year"] ?></span>
                                <span class="title">
                                    <?php echo $project["customer_name".$this->lang->line('dil')] ?>
                                </span>
                                <span class="alt_title"><?php echo $project["title".$this->lang->line('dil')] ?></span>
                                <span class="button">İNCELE</span>
                            </div>
                         </div>
                    </a>
                </div>
            <?php } else { ?>	
                <div class="works_row">
                    <a href="<?php echo base_url($this->lang->line('lang').'projects/detail/'.$project['id']) ?>">
                        <div class="boxes square">
                            <div class="bg" style="background-image: url(<?php echo base_url('uploads/'.$project['image']) ?>);">
                            </div>
                        </div>
                        <div class="boxes dark square">
                             <div class="txt">
                                <span class="year"><?php echo $project["project_year"] ?></span>
                                <span class="title">
                                    <?php echo $project["customer_name".$this->lang->line('dil')] ?>
                                </span>
                                <span class="alt_title"><?php echo $project["title".$this->lang->line('dil')] ?></span>
                                <span class="button">İNCELE</span>
                            </div>
                         </div>
                    </a>
                </div>
            <?php }?>
        <?php } else {

            if ($i>4) {
                if (($renk1=="pink") && ($renk2=="pink")) { $class="dark"; $renk1="dark"; $renk2="pink"; }
                elseif (($renk1=="dark") && ($renk2=="pink")) { $class="dark"; $renk1="dark"; $renk2="dark"; }
                elseif (($renk1=="dark") && ($renk2=="dark")) { $class="pink"; $renk1="pink"; $renk2="dark"; }
                elseif (($renk1=="pink") && ($renk2=="dark")) { $class="pink"; $renk1="pink"; $renk2="pink"; }
            }            

            ?>
            <div class="works_row_mini blurit">
                <a href="<?php echo base_url($this->lang->line('lang').'projects/detail/'.$project['id']) ?>">
                    <div class="boxes square">
                        <div class="bg" style="background-image: url(<?php echo base_url('uploads/'.$project['image']) ?>);"></div>
                    </div>
                    <div class="boxes <?php echo $class ?> square">
                         <div class="txt">
                            <span class="year"><?php echo $project["project_year"] ?></span>
                            <span class="title"><?php echo $project["customer_name".$this->lang->line('dil')] ?></span>
                            <span class="alt_title"><?php echo $project["title".$this->lang->line('dil')] ?></span>
                            <span class="button">İNCELE</span>
                        </div>
                     </div>
                 </a>
            </div>            
        <?php }?>
    <?php $i++; }?>

    <div class="page_bottom"></div>

<?php $this->load->view('includes/scripts.php') ?>
</body>
</html>

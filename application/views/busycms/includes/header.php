<div id="header">
        <ul class="con">
            <li class="dashboard">
                <a href="#home">Busy Cms</a>
            </li>
            <li class="count indicator" style="display:none;">
                <span data-count="8">Notifications</span>
            </li>
            <li class="messages" style="display:none;">
                <span>Messages</span>
            </li>
        </ul>
	</div>
	<div id="stream">
	    <div class="con">
	        <div class="tile" id="hello" style="position:relative;">
	            <h2><span>Hi,</span> <?php echo $this->session->userdata('busynamesurname') ?></h2>
	            <ul class="nav">
                        <li>
	                    <a href="#" onclick="location.href='http://www.facebook.com/pages/aisha/20077223504'" title="Add Pages" target="_blank">
                                <img src="<?php echo base_url() ?>busycms/static/images/64-Facebook-Like.png" height="30" style="margin-top:10px;" />
                            </a>
	                </li>
	                <li>
	                    <a href="#" onclick="location.href='http://twitter.com/AyseTolga'" target="_blank">
                                <img src="<?php echo base_url() ?>busycms/static/images/64-Twitter.png" height="30" style="margin-top:10px;" />
                            </a>
	                </li>
	                <li class="adminbutton">
	                    <a href="<?php echo base_url() ?>busycms/users/" onclick="location.href='<?php echo base_url() ?>busycms/users/'" class="tTip" title="Add New User">a</a>
	                </li>                        
	                <li>
	                    <a href="<?php echo base_url() ?>busycms/logout" onclick="location.href='<?php echo base_url() ?>busycms/logout'"  class="tTip" title="Logout BusyCms">I</a>
	                </li>
	            </ul>
	        </div>
                <!--<a class="tile<?php if ($page=="dashboard") { echo " selected"; } ?>" href="<?php echo base_url() ?>busycms/dashboard">
	            <span class="vector">1</span>
	            <span class="title"><strong>DASHBOARD</strong></span>
	            <span class="desc"><strong>View Dashboard</strong></span>
	        </a>-->
	        <!-- <a class="tile<?php if ($page=="pages") { echo " selected"; } ?>" href="<?php echo base_url() ?>busycms/pages/" onclick="location.href='<?php echo base_url() ?>busycms/pages/'">
	            <span class="vector">C</span>
	            <span class="title"><strong>PAGES</strong></span>
	            <span class="desc"><strong>Manage Content</strong></span>
	        </a> -->
	        <a class="tile<?php if ($page=="customers") { echo " selected"; } ?>" href="<?php echo base_url() ?>busycms/customers/" onclick="location.href='<?php echo base_url() ?>busycms/customers/'">
	            <span class="vector">C</span>
	            <span class="title"><strong>CUSTOMERS</strong></span>
	            <span class="desc"><strong>Manage Customers</strong></span>
	        </a>
                <a class="tile<?php if ($page=="projects") { echo " selected"; } ?>" href="<?php echo base_url() ?>busycms/projects/" onclick="location.href='<?php echo base_url() ?>busycms/projects/'">
	            <span class="vector">C</span>
	            <span class="title"><strong>PROJECTS</strong></span>
	            <span class="desc"><strong>Manage Projects</strong></span>
	        </a>
	        <a class="tile<?php if ($page=="people") { echo " selected"; } ?>" href="<?php echo base_url() ?>busycms/people/" onclick="location.href='<?php echo base_url() ?>busycms/people/'">
	            <span class="vector">C</span>
	            <span class="title"><strong>PEOPLE</strong></span>
	            <span class="desc"><strong>Manage People</strong></span>
	        </a>
                <a class="tile<?php if ($page=="news") { echo " selected"; } ?>" href="<?php echo base_url() ?>busycms/news/" onclick="location.href='<?php echo base_url() ?>busycms/news/'">
	            <span class="vector">C</span>
	            <span class="title"><strong>NEWS</strong></span>
	            <span class="desc"><strong>Manage News</strong></span>
	        </a>
                <!--
	        <a class="tile<?php if ($page=="events") { echo " selected"; } ?>" href="<?php echo base_url() ?>busycms/events/" onclick="location.href='<?php echo base_url() ?>busycms/events/'">
	            <span class="vector">a</span>
	            <span class="title"><strong>EVENTS</strong></span>
	            <span class="desc"><strong>Events Manage</strong></span>
	        </a>-->
<!--                <a class="tile<?php if ($page=="products") { echo " selected"; } ?>" href="<?php echo base_url() ?>busycms/products/" onclick="location.href='<?php echo base_url() ?>busycms/products/'">
	            <span class="vector">L</span>
	            <span class="title"><strong>PRODUCTS</strong></span>
	            <span class="desc"><strong>Manage Products</strong></span>
	        </a>-->
                <!--<a class="tile" href="http://mail.earthproductions.com" target="_blank">
	            <span class="vector">A</span>
	            <span class="title"><strong>E-MAIL</strong></span>
	            <span class="desc"><strong>Email Manage</strong></span>
	        </a>-->
<!--	        <a class="tile<?php if ($page=="orders") { echo " selected"; } ?>" href="<?php echo base_url() ?>busycms/orders/" onclick="location.href='<?php echo base_url() ?>busycms/orders/'">
	            <span class="vector">D</span>
	            <span class="title"><strong>ORDERS</strong></span>
	            <span class="desc"><strong>Manage Orders</strong></span>
	        </a>
                <a class="tile<?php if ($page=="members") { echo " selected"; } ?>" href="<?php echo base_url() ?>busycms/products/" onclick="location.href='<?php echo base_url() ?>busycms/members/'">
	            <span class="vector">a</span>
	            <span class="title"><strong>MEMBERS</strong></span>
	            <span class="desc"><strong>Manage Members</strong></span>
	        </a>
                <a class="tile<?php if ($page=="designers") { echo " selected"; } ?>" href="<?php echo base_url() ?>busycms/designers/?tip=1" onclick="location.href='<?php echo base_url() ?>busycms/designers/?tip=1'">
	            <span class="vector">a</span>
	            <span class="title"><strong>DESIGNERs & BRANDS</strong></span>
	            <span class="desc"><strong>Manage Designer & Brands</strong></span>
	        </a>-->
	    </div>
	</div>
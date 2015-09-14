<?php $this->load->view('includes/head') ?>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>

    <script type="text/javascript">
            // When the window has finished loading create our google map below
            google.maps.event.addDomListener(window, 'load', init);
        
            function init() {
                // Basic options for a simple Google Map
                // For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
                var mapOptions = {
                    // How zoomed in you want the map to start at (always required)
                    zoom: 15,
                    scrollwheel: false,
                    // The latitude and longitude to center the map (always required)
                    center: new google.maps.LatLng(41.032523, 28.976629), // New York

                    // How you would like to style the map. 
                    // This is where you would paste any style found on Snazzy Maps.
                    styles: [{"featureType":"all","elementType":"all","stylers":[{"hue":"#ff0000"},{"saturation":-100},{"lightness":-30}]},{"featureType":"all","elementType":"labels.text.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"color":"#353535"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#656565"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"color":"#505050"}]},{"featureType":"poi","elementType":"geometry.stroke","stylers":[{"color":"#808080"}]},{"featureType":"road","elementType":"all","stylers":[{"color":"#454545"}]},{"featureType":"transit","elementType":"labels","stylers":[{"hue":"#000000"},{"saturation":100},{"lightness":-40},{"invert_lightness":true},{"gamma":1.5}]}]
                };

                // Get the HTML DOM element that will contain your map 
                // We are using a div with id="map" seen below in the <body>
                var mapElement = document.getElementById('map');

                // Create the Google Map using our element and options defined above
                var map = new google.maps.Map(mapElement, mapOptions);

                // Let's also add a marker while we're at it
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(41.032523, 28.976629),
                    map: map,
                    title: 'propaganda'
                });
            }
    </script>

</head>

<body>
   <?php $this->load->view('includes/header') ?>

   <div class="fixed_top"></div>
    <div class="fixed_bottom"></div>
    
    <div class="fixed_box">
        <div class="bg" style="background-image: url(<?php echo base_url() ?>assets/img/contact.jpg);">
        </div>
    </div>
    
    <div class="right_box contact_box">

        <div class="boxes">
            <div class="title_box">
                <div>
                    <span class="title">İLETİŞİM</span>
                    <a class="email" href="mailto:merhaba@propaganda.com.tr">MERHABA@<span class="grey_txt">PROPAGANDA.COM.TR</span></a>
                </div>
            </div>
            <div id="map" class="map_box">
            </div>
            <div class="footer detail">
                <div class="right">
                    <div class="r_left">
                        <div class="line"></div>
                        <span class="grey_txt title">BİZE ULAŞIN</span>
                        İSTİKLAL CAD.<br>
                        <span class="grey_txt">MISIR APT.</span><br>
                        NO.311 KAT 6<br>
                        BEYOĞLU 34100<br>
                        İSTANBUL<br><br>
                    </div>
                    <div class="r_right">
                        <span class="grey_txt">TEL:</span> <a href="tel:902122526500">+90<br> 212 252 6500</a><br><br>
                        <span class="grey_txt">FAX:</span> <a href="tel:902122521666">+90<br> 212 252 1666</a>
                    </div>
                </div>
            </div> 
            <div class="logo_box">
                <div>
                    <img src="<?php echo base_url('assets/img/logo.png') ?>">
                </div>
            </div>
        </div>
    </div>

<?php $this->load->view('includes/scripts.php') ?>
</body>
</html>

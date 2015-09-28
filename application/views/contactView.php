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
                    styles: [{"featureType":"administrative","elementType":"geometry","stylers":[{"saturation":"2"},{"visibility":"simplified"}]},{"featureType":"administrative","elementType":"labels","stylers":[{"saturation":"-28"},{"lightness":"-10"},{"visibility":"on"}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"landscape","elementType":"geometry.fill","stylers":[{"saturation":"-1"},{"lightness":"-12"}]},{"featureType":"landscape.natural","elementType":"labels.text","stylers":[{"lightness":"-31"}]},{"featureType":"landscape.natural","elementType":"labels.text.fill","stylers":[{"lightness":"-74"}]},{"featureType":"landscape.natural","elementType":"labels.text.stroke","stylers":[{"lightness":"65"}]},{"featureType":"landscape.natural.landcover","elementType":"geometry","stylers":[{"lightness":"-15"}]},{"featureType":"landscape.natural.landcover","elementType":"geometry.fill","stylers":[{"lightness":"0"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road","elementType":"geometry","stylers":[{"visibility":"on"},{"saturation":"0"},{"lightness":"-9"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"lightness":"-14"}]},{"featureType":"road","elementType":"labels","stylers":[{"lightness":"-35"},{"gamma":"1"},{"weight":"1.39"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"lightness":"-19"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"lightness":"46"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"labels.icon","stylers":[{"lightness":"-13"},{"weight":"1.23"},{"invert_lightness":true},{"visibility":"simplified"},{"hue":"#ff0000"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#adadad"},{"visibility":"on"}]}]
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

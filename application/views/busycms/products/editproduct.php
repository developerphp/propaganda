<?php $this->load->view('busycms/includes/head') ?>
<script type="text/javascript" src="<?php echo base_url() ?>busycms/editor/ckeditor.js"></script>
<script src="<?php echo base_url() ?>busycms/static/scripts/app.js"></script>
<script>
    $(document).ready(function() {
        photos(<?php echo $product->id ?>);
        $.initialize();
        $.demo();        
    });
    
    function newcolorshow() {
        $('.colorsavebutton').fadeOut(1);
        $('#editcolor').fadeOut(500);        
        $('#editcontent').fadeOut(500,function(){ $('.eventbutton').fadeOut(1); $('.newcolorsavebutton').fadeIn(1); $('#addnewcolor').fadeIn(500); })
    }
    
    function eventinfo() {
        $('.colorsavebutton').fadeOut(1);
        $('ul.colorlist li b').css('font-weight','normal');
        $('.newcolorsavebutton').fadeOut(1);
        $('.eventbutton').fadeIn(1); 
        $('#addnewcolor').fadeOut(500);
        $('#editcolor').fadeOut(500);
        $('#editcontent').fadeIn(500);
        $('.leftmenu ul li a').css('font-weight','normal');
        $('#eventinfolink a').css('font-weight','bold');
    }
    
    function deleteimageapproval(id) {
        $('.notification .hide').click();
        txt='<br/>Delete is image <br/><br/><a style="color:#000;" href="javascript:void(0)" onclick="deleteimage('+id+')">Yes</a> or \n\
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
    
    function deleteimage(id) {
        $.ajax({
        url: '<?php echo base_url() ?>busycms/deleteimage/'+id,
		  success: function(data) { 
                       $('#sezgin').html(data);
              }
		});
    }
    
    function photos(id) {
        $.ajax({
        url: '<?php echo base_url() ?>busycms/productphotos/'+id,
		  success: function(data) {
                       $('#gallery').html(data);
              }
        });
    }
</script>
</head>
<body>
    <?php $this->load->view('busycms/includes/header') ?>
    <div id="dashboard">
        <div class="scroll con eventslist" style="background:none;">
            <table style="display:none;">
                <tr>
                    <td>

                    </td>
                </tr>
            </table>
            <div class="top-title">    
                <div class="detail-title">
                    <?php
                    echo "<span>" . $product->title . "</span>";
                    ?>
                </div>
            </div>
            <div id="editproductform_back"></div>
            <div class="editevent-left" style="height:1500px;">
                <div id="eventimage">
                    <?php if (strlen($product->image) == 0) { ?>
                        <img id="bigimage" src="<?php echo base_url() ?>busycms/images/add-product-picture.jpg" width="300" onclick="$('#myfile').click();" style="cursor:pointer;" />
                    <?php } else { ?>
                        <div class="eventmainimage" onclick="$('#myfile').click();" style="cursor:pointer;">
                            <img id="bigimage" src="<?php echo base_url() ?>uploads/<?php echo $product->image ?>" width="300" />                            
                        </div>
                    <?php } ?>
                    <div style="width:0px;height:0px;overflow:hidden;">
                        <form id="uploadimage" action="<?php echo base_url() ?>busycms/productmainimageupload/<?php echo $product->id ?>" method="post" enctype="multipart/form-data" target="ajax">
                            <input name="resim" type="file" id="myfile" style="height:30px;" onchange="$('#uploadimage').submit();" />
                        </form>
                        <iframe id="ajax"></iframe>     
                    </div>
                </div>
                <!--                <div class="leftmenu">
                                    <ul>
                                        <li id="eventinfolink">
                                            <a href="javascript:void(0);" onclick="eventinfo();" style="font-weight:bold;">Product Info</a>
                                        </li>
                                        <li id="editcolorlink">
                                            <a href="javascript:void(0);">Colors</a>
                                            <span onclick="newcolorshow()">+ Add color</span>
                                        </li>
                                    </ul>
                                </div>
                                <div id="colors">                    
                                </div>-->
            </div>
            <div class="editevent-right">
                <div id="editcontent">
                    <button onclick="location.href='<?php echo base_url() ?>busycms/copyproduct/<?php echo $product->id ?>'" style="padding:10px;position:absolute;right:10px;top:15px;">
                                Copy Product
                            </button>
                    <form name="editproductform" id="editproductform" onSubmit="return false;">
                        <h1>General</h1>
                        <div class="relative" style="height:70px;z-index:99999;">
                            <div style="position: absolute;right:0;top:50px;">
                                Listede Göster&nbsp;&nbsp;&nbsp;<input type="checkbox" name="view_list"<?php if ($product->view_list==1) { echo " checked"; } ?> />
                            </div>                            
                            <div style="position:absolute;">
                        <b>Product Category</b><br/>
                        <span>Select the Product Category</span>
                        <select name="catid">
                            <option value="0">Select Product Category</option>
                            <?php
                            $sql = $this->db->query("select * from product_categories where catid=0");
                            foreach ($sql->result() as $category) {
                                ?>

                                <optgroup label="<?php echo $category->title ?>">
                                    <?php
                                    $sql2 = $this->db->query("select * from product_categories where catid=" . $this->db->escape($category->id) . "");
                                    if ($sql2->num_rows() > 0) {
                                        echo "<ul>";
                                    }
                                    foreach ($sql2->result() as $subcategory) {
                                        ?>
                                        <option value="<?php echo $subcategory->id ?>" <?php if ($product->catid == $subcategory->id) {
                                    echo "selected=\"selected\"";
                                } ?>><?php echo $subcategory->title ?></option>
    <?php }
} 
?>
                        </select>
                            </div>
                        </div>
                        <br/><br/>
                        <b>Name of Product</b><br/>
                        <span>Please enter the name of the product </span>
                        <input type="text" name="title" value="<?php echo $product->title ?>" />
                        <input type="hidden" name="id" value="<?php echo $product->id ?>" />
                        <input type="hidden" name="saveclose" id="saveclose" value="0">
                        <br/>
                        <b>Tags of Product</b><br/>
                        <span>Please enter the tags of the product </span>
                        <input type="text" name="tags" value="<?php echo $product->tags ?>" />
                        <br/>
                        <div class="relative" style="height:80px;">
                            <div style="width:220px;z-index: 9999;position:absolute;">
                                <b>Designer of Product</b><br/>
                                <span>Select designer of the product</span>
                                <select name="designer">
                                    <option value="0">DESIGNER</option>
                                    <?php
                                    $sql = $this->db->query("select * from designers_brands where tip=1 order by title asc");
                                    foreach ($sql->result() as $designer) {
                                        ?>
                                        <option <?php if ($product->designer==$designer->id) { echo 'selected="selected"'; } ?> value="<?php echo $designer->id ?>"><?php echo $designer->title ?></option>
<?php } ?>
                                </select>
                            </div>
                            <div style="z-index: 9999;position:absolute;left:240px;">
                                <b>Brand of Product</b><br/>
                                <span>Select brand of the product</span>
                                <select name="brand">
                                    <option value="0">BRAND</option>
<?php
$sql = $this->db->query("select * from designers_brands where tip=2 order by title asc");
foreach ($sql->result() as $brand) {
    ?>
                                        <option <?php if ($product->brand==$brand->id) { echo 'selected="selected"'; } ?> value="<?php echo $brand->id ?>"><?php echo $brand->title ?></option>
<?php } ?>
                                </select>
                            </div>
                            
                                <div style="margin-left:500px;">
                            <b>Product Code</b><br/>
                            <span>product code of the product</span><br/>
                            <input type="text" name="product_code" value="<?php echo $product->product_code ?>" />
                            </div>
                            
                        </div><br/>
                        <div>
                            <div style="width:170px;float:left;margin-right:20px;position:relative;">
                            <b>Color</b><br/>
                            <span>Color of the product</span><br/>
                            <div style="position:absolute;z-index:999;width:170px;top:45px;">
                            <select name="color">
                                <option>Select Color</option>
                                <?php $sql=$this->db->query("select * from colors order by reorder asc");
                                foreach($sql->result() as $color) {?>
                                <option <?php if ($color->id==$product->color) { echo 'selected="selected"'; } ?> value="<?php echo $color->id ?>" style="border-left:solid 5px <?php echo $color->color_code; ?>"><?php echo $color->color_name ?></option>
                                <?php }?>
                            </select>
                            </div>
                            </div>
                            <div style="width:130px;float:left;margin-right:20px;">
                            <b>Material</b><br/>
                            <span>Material of the product</span><br/>
                            <input type="text" name="material" value="<?php echo $product->material ?>" />
                            </div>                            
                            <div style="width:100px;float:left;margin-right:20px;">
                            <b>Weight</b><br/>
                            <span>Weight product</span><br/>
                            <input type="text" name="weight" value="<?php echo $product->weight ?>" />
                            </div>
                            <div style="width:100px;float:left;margin-right:20px;">
                            <b>Size name</b><br/>
                            <span>Size name</span><br/>
                            <input type="text" name="size_name" value="<?php echo $product->size_name ?>" />
                            </div>
                            <div style="width:160px;float:left;">
                            <b>Size</b><br/>
                            <span>Size of the product</span><br/>
                            <input type="text" name="size" value="<?php echo $product->size ?>" />
                            </div>
                            <div class="clear"></div>
                        </div>
                        <br/>
                        <div style="height:80px;" class="relative">
                            <div style="position:absolute;z-index:10;width:100%;">
                        <b>Categories</b><br/>
                        <span>Select the Categories</span>
                        <select name="categories[]" multiple="multiple">
                            <option selected value="0">Select category</option>
                                <?php
                                $categories=explode(',',$product->categories);
                                $sql2 = $this->db->query("select * from product_categories order by id asc");
                                if ($sql2->num_rows() > 0) { echo "<ul>"; }
                                foreach ($sql2->result() as $subcategory) { ?>
                                <option value="<?php echo $subcategory->id ?>" <?php if (in_array($subcategory->id,$categories)) { echo "selected=\"selected\""; } ?>>
                                    <?php echo $subcategory->title ?>
                                </option>
                                <?php }  ?>
                        </select>
                            </div>
                        </div>
                        <br/><br/>
                        <b>Related Products</b><br/>
                        <span>Select the Related Products</span>
                        <select name="related[]" multiple="multiple">
                            <option selected value="0">Select Products</option>
<?php
$related=explode(',',$product->related_products);
$sql2 = $this->db->query("select * from products order by id asc");
if ($sql2->num_rows() > 0) {
    echo "<ul>";
}
foreach ($sql2->result() as $subcategory) {
    ?>
                                <option value="<?php echo $subcategory->id ?>" <?php if (in_array($subcategory->id,$related)) {
        echo "selected=\"selected\"";
    } ?>><?php echo $subcategory->title ?></option>
<?php }  ?>
                        </select>
                        <br/><br/>
                        <div class="clear"></div><br/>
                        <h1>Sale Information</h1>
                        <div style="width:120px;float:left;"> <b>Price</b><br/>
                            <span>Price of Product </span>
                            <input type="text" name="price" value="<?php echo $product->price ?>" style="width:100px;" placeholder="TL" />
                        </div>
                        <div style="width:150px;float:left;"> <b>Cargo</b><br/>
                            <div style="position:absolute;width:150px;z-index:-1;">
                            <span>Cargo of Product </span>
                            <select name="kargo" style="width:150px;">
                                <option value="5" <?php if ($product->kargo_bedeli==5) { echo 'selected="selected"'; } ?>>Kategori 1 (5 TL)</option>
                                <option value="8" <?php if ($product->kargo_bedeli==8) { echo 'selected="selected"'; } ?>>Kategori 2 (8 TL)</option>
                                <option value="10" <?php if ($product->kargo_bedeli==10) { echo 'selected="selected"'; } ?>>Kategori 3 (10 TL)</option>
                            </select>                            
                            </div>
                        </div>
                        <div style="width:100px;float:left;margin-left:20px;"> <b>Stock</b><br/>
                            <span>Stock of Product </span>
                            <input type="text" name="stock" value="<?php echo $product->stock ?>" style="width:100px;" placeholder=" Adet" />
                        </div>
                        <div style="width:150px;float:left;margin-left:20px;"> <b>Discount</b><br/>
                            <span>Discount of Product </span>
                            <input type="text" name="percentage" value="<?php echo $product->percentage ?>" style="width:100px;" placeholder='%' />
                        </div>                    
                        <div class="clear"></div><br/><br/>
                        <div style="width:150px;float:left;"> <b>Delivery Date</b><br/>
                            <span>Delivery Date of Product </span>
                            <input type="text" name="delivery_date" value="<?php echo $product->teslimat_suresi ?>" style="width:100px;" placeholder=" Gün" />
                        </div>
                        <div class="clear"></div><br/><br/>
                        <b>Content of Product</b><br/>
                        <span>Please enter the product content</span>
                        <textarea id="editor2" name="content"><?php echo $product->content ?></textarea>
                        <script type="text/javascript">
                            CKEDITOR.replace( 'editor2',
                            {
                                extraPlugins : 'uicolor',
                                uiColor: '#dee9e9',
                                toolbar :
                                    [
                                    [ 'Format', 'Bold', 'Italic', '-', 'NumberedList' , 'BulletedList', '-', 'Link', 'Unlink' ,'-', 'PageBreak', 'RemoveFormat']
                                ]
                            });
                        </script>
                        <div class="clear"></div>
                    </form><br/><br/>
                    <div align="right">
                        <button style="margin-right:20px;" onclick="location.href='<?php echo base_url() ?>busycms/products/?catid=<?php echo $product->catid ?>'">Close</button>
                        <button class="eventbutton" onClick="CKEDITOR.instances['editor2'].updateElement();submitform('busycms/','editproductform'); return false;" style="right:150px;">Update</button>
                        <button class="eventbutton" onClick="CKEDITOR.instances['editor2'].updateElement();$('#saveclose').val(1);submitform('busycms/','editproductform'); return false;" style="right:150px;">Update and Close</button>
                    </div>
                </div>
                <div id="gallery">
                </div>
                <div id="addnewcolor" style="display:none;">
                    <h1>Select Color</h1>
                    <form name="addnewcolorform" id="addnewcolorform" onSubmit="return false;">
                        <b>Color</b><br/>
                        <span>Please select the Color</span>
                        <select name="color_name">
<?php $sql = $this->db->query("select * from colors order by color_name asc");
foreach ($sql->result() as $color) {
    ?>
                                <option value="<?php echo $color->id ?>"><?php echo $color->color_name ?></option>
<?php } ?>
                        </select>
                        <input type="hidden" name="id" value="<?php echo $product->id ?>" />
                    </form><br/>
                    <div align="right">
                        <button style="display:none;" class="newcolorsavebutton" onClick="submitform('busycms/','addnewcolorform'); return false;">Add</button>                    
                    </div>
                    <div id="addnewcolorform_back"></div>
                </div>
                <div id="editcolor" style="display:none;">

                </div>
            </div>
            <div class="clear"></div>
            <div id="sezgin"></div>
        </div>     
    </div>
    <div id="sezgin"></div>
<?php $this->load->view('busycms/includes/footer') ?>
</body>
</html>
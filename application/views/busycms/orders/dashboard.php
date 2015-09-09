<?php $this->load->view('busycms/includes/head') ?>
<script>
    var base_url='<?php echo base_url()?>';
    
    function havalecomplete(id)
    {
        $.ajax({
            url: '<?php echo base_url() ?>busycms/havalecomplete/'+id,
            success: function(data) { $('#sezgin').html(data); }
        });
    }
    
    function iptalet(id)
    {
        $.ajax({
            url: '<?php echo base_url() ?>busycms/siparisiptal/'+id,
            success: function(data) { $('#sezgin').html(data); }
        });
    }
    
    function kuryecomplete(id) {
        $('#kurye'+id).fadeIn(250);
    }
    
    function teslimcomplete(id) {
        $.ajax({
            url: '<?php echo base_url() ?>busycms/teslimcomplete/'+id,
            success: function(data) { $('#sezgin').html(data); }
        });
    }
</script>

<script>
    function takipformsubmit(linki,form){
        $.ajax({
                type: 'POST',
                url: base_url+'/'+linki+'kuryecompleteform_do',
                data: $('#'+form).serialize(),
                error:function(){ $('#'+form+'_back').html("Hata Var."); }, 
                success: function(veri) { 
                $('#'+form+'_back').html(veri);
                }
                });
                return false;
    } 
</script>

</head>
<body>
    <?php $this->load->view('busycms/includes/header') ?>
    <div id="dashboard">
        <div class="scroll con eventslist" style="background:none;">
            <div class="editevent-left">
                <div class="top-title">
                    <h2><span>Siparişler</span></h2>                        
                </div>
                <div class="product-category-list">
                    <div id="productcategory">
                        <div class="title">Siparişler
                        </div>
                        <ul>
                                <li>
                                    <a<?php if ($sipdurum==1) {?> class="selected"<?php }?> href="javascript:void(0);" onclick="location.href='<?php echo base_url() ?>busycms/orders/1'">Havale Onayı Bekleyenler (<?php echo $havalesay ?>)</a>
                                </li>
                                <li>
                                    <a<?php if ($sipdurum==5) {?> class="selected"<?php }?> href="javascript:void(0);" onclick="location.href='<?php echo base_url() ?>busycms/orders/5'">Kapıda Ödeme (<?php echo $kapidaodemesay ?>)</a>
                                </li>
                                <li>
                                    <a<?php if ($sipdurum==2) {?> class="selected"<?php }?> href="javascript:void(0);" onclick="location.href='<?php echo base_url() ?>busycms/orders/2'">Kuryeye Verilecekler (<?php echo $kuryesay ?>)</a>
                                </li>
                                <li>
                                    <a<?php if ($sipdurum==3) {?> class="selected"<?php }?> href="javascript:void(0);" onclick="location.href='<?php echo base_url() ?>busycms/orders/3'">Kuryeye Verilenler (<?php echo $gonderilensay ?>)</a>
                                </li>
                                <li>
                                    <a<?php if ($sipdurum==4) {?> class="selected"<?php }?> href="javascript:void(0);" onclick="location.href='<?php echo base_url() ?>busycms/orders/4'">Teslim Edilenler (<?php echo $teslimedilensay ?>)</a>
                                </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="editevent-right" style="padding:4px 0px;width:789px;">
                <div class="product-section" style="display:block;" id="list">
                    <div class="top-title" style="height:25px;">
                        &nbsp;
                    </div>
                    <div id="productslist">
                        <ul>
                        <?php 
                        $where="";
                        if ($sipdurum==1) {
                            $where="and user_orders.tur=1  and user_orders.durum=1";
                        }
                        elseif ($sipdurum==5) {
                            $where="and user_orders.tur=3 and user_orders.durum=1 and user_orders.admin_action=0";
                        }
                        elseif ($sipdurum==2) {
                            $where="and user_orders.durum=5 and user_orders.admin_action=0";
                        }
                        elseif ($sipdurum==3) {
                            $where="and user_orders.durum=5 and user_orders.admin_action=5";
                        }
                        elseif ($sipdurum==4) {
                            $where="and user_orders.durum=5 and user_orders.admin_action=6";
                        }
                        
                        $sql=$this->db->query("
                            select
                            users.id as uid,users.name_surname,users.email
                            ,user_orders.id
                            ,user_orders.user_id
                            ,user_orders.t_adresname
                            ,user_orders.t_adressurname
                            ,user_orders.t_adres
                            ,user_orders.t_il
                            ,user_orders.t_ilce
                            ,user_orders.t_pk
                            ,user_orders.t_adresturu
                            ,user_orders.t_telefon
                            ,user_orders.t_mobile
                            ,user_orders.t_sirket
                            ,user_orders.t_tckimlik
                            ,user_orders.t_vergino
                            ,user_orders.t_sirketmi
                            ,user_orders.fatura_ayni
                            ,user_orders.f_adresname
                            ,user_orders.f_adressurname
                            ,user_orders.f_adres
                            ,user_orders.f_il
                            ,user_orders.f_ilce
                            ,user_orders.f_pk
                            ,user_orders.f_adresturu
                            ,user_orders.f_telefon
                            ,user_orders.f_mobile
                            ,user_orders.f_sirket
                            ,user_orders.f_tckimlik
                            ,user_orders.f_vergino
                            ,user_orders.f_sirketmi
                            ,user_orders.hediyemi
                            ,user_orders.hediyetext
                            ,user_orders.siparis_notu
                            ,user_orders.urunsayisi
                            ,user_orders.toplamfiyat
                            ,user_orders.tur
                            ,user_orders.durum
                            ,user_orders.order_code
                            ,user_orders.update_date
                            ,user_orders.admin_action 
                            ,user_orders.kapidaodemetutar
                            ,user_orders.kargo
                            from user_orders,users
                            where
                           user_orders.user_id=users.id $where order by user_orders.id desc 
                        ");
                        foreach($sql->result() as $order) {?>                        
                            <li style="padding:0px;cursor:pointer;">
                                <div class="order_preview">
                                    <div onclick="$('#order<?php echo $order->id ?>').slideToggle(0)">
                                        <div style="width:300px;float:left;">
                                        <b><?php echo $order->name_surname ?></b> ( <?php echo strftime("%d", strtotime($order->update_date)).".".strftime("%m", strtotime($order->update_date)).".".strftime("%Y", strtotime($order->update_date))." ".strftime("%H", strtotime($order->update_date)).":".strftime("%M", strtotime($order->update_date)); ?> )<br/>
                                        Sipariş kodu : <b><?php echo $order->order_code ?></b>
                                        </div>
                                        <div style="width:200px;float:left;">
                                            <b>Adres</b><br/>
                                            <?php echo $order->t_adres ?><br/><?php echo $order->t_il?> / <?php echo $order->t_ilce ?>
                                            <?php if (strlen($order->t_mobile)>0) {?><br/><br/>
                                            <?php echo $order->t_mobile ?>
                                            <?php }?>
                                        </div>
                                        <div style="width:200px;float:left;margin-left:30px;">
                                            <b>Ödeme Tipi</b><br/>
                                            <?php 
                                            if ($order->tur==1) { echo "Havale"; }
                                            elseif($order->tur==2) { echo "Kredi Kartı"; }
                                            elseif($order->tur==3) { echo "Kapıda Ödeme"; }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                    <a title="Mail Gonder" class="sendmail" href="mailto:<?php echo $order->email ?>?subject=<?php echo $order->order_code ?> nolu siparişinizle ilgili"><img src="<?php echo base_url() ?>busycms/images/sendmessage.jpg" /></a>
                                    <a href="javascript:void(0)" class="havale_alindi" style="top:45px;" onclick="iptalet(<?php echo $order->id ?>)">iptal et</a>
                                    <?php if ($sipdurum==1) {?>
                                    <a href="javascript:void(0)" class="havale_alindi" onclick="havalecomplete(<?php echo $order->id ?>)">havaleyi aldım</a>
                                    <?php } elseif (($sipdurum==2) || ($sipdurum==5)) { ?>
                                    
                                    <a href="javascript:void(0)" class="havale_alindi" onclick="kuryecomplete(<?php echo $order->id ?>)">kuryeye verildi</a>
                                    <div id="kurye<?php echo $order->id?>" style="display:none;background:#dee9e9;padding:20px;position:absolute;width:92px;position:absolute;right:0;top:0px;">
                                        <form id="kuryecompleteform<?php echo $order->id?>" name="kuryecompleteform<?php echo $order->id?>" onsubmit="return false">Takip No<br/>
                                            <input type="hidden" name="id" value="<?php echo $order->id?>" />
                                            <input type="text" name="takipno" />
                                            <button onclick="takipformsubmit('busycms/','kuryecompleteform<?php echo $order->id?>')" style="margin-top:5px;padding:5px 20px;">Gönder</button>
                                            <div id="kuryecompleteform<?php echo $order->id?>_back"></div>
                                        </form>
                                    </div>
                                    <?php }elseif ($sipdurum==3) { ?>
                                    <a href="javascript:void(0)" class="havale_alindi" onclick="teslimcomplete(<?php echo $order->id ?>)">teslim edildi</a>
                                    <?php }?>
                                </div>
                                <div class="box" id="order<?php echo $order->id ?>">
                                <h1>SİPARİŞLER</h1>
                                <?php
                                $toplamfiyat=0;
                                $kargo=1;
                                $enucuz=1000;
                                $indir=0;
                                $toplamadet=0;
                                $sql=$this->db->query("select * from order_products where order_id=".$order->id."");
                                foreach ($sql->result_array() as $basket) {
                                    if (!in_array($basket["product_id"], $this->config->item('nokargo'))) {
                                        $toplamadet=$toplamadet+$basket["qty"];
                                    }
                                }
                                foreach($sql->result() as $product) {?>
                                <div class="product">
                                    <div class="name">
                                    <b><?php echo $product->name ?></b>
                                    </div>
                                    <div class="qty">
                                        <?php echo $product->qty ?> X
                                    </div>
                                    <div class="fiyat">
                                        <?php echo $product->price ?> TL
                                    </div>
                                    <div class="total">
                                        <?php 
                                        $total=$product->price*$product->qty;
                                        echo money_format('%.2n', $total);
                                        $toplamfiyat=$toplamfiyat+$total;
                                        ?> TL
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <?php 
                                if (in_array($product->product_id, $this->config->item('nokargo'))) { $kargo=0; } 
//                                if ($toplamadet>=3) {
//                                    if ($enucuz>$product->price) { $enucuz=$product->price; $indir=$product->price; }
//                                }
                                }?>
                                <div class="totalprice">
                                    <div align="right">
                                    <hr/>
                                    </div>
                                    <?php echo money_format('%.2n', $toplamfiyat); ?> TL
                                    <?php if ($order->tur==3) {?>
                                    <br/><span style="font-size:12px;">KAPIDA ODEME</span>&nbsp; <?php echo $order->kapidaodemetutar; $toplamfiyat=$toplamfiyat+$order->kapidaodemetutar; ?> TL
                                    <?php } ?>                                    
                                    <br/><span style="font-size:12px;">KARGO</span>&nbsp;<?php echo $order->kargo ?>&nbsp; TL
                                    <br/>                                    
                                    <?php echo $toplamfiyat+$order->kargo ?> TL                                    
                                </div>
                                <hr/>
                                <div class="clear"></div>
                                <div class="adres" <?php if ($order->fatura_ayni==0) {?>style="width:300px;float:left;"<?php }?>>
                                <h1>TESLİMAT ADRESİ</h1>
                                <b><?php echo $order->t_adresname." ".$order->t_adressurname ?></b><br/>
                                <?php echo $order->t_adres ?><br/>
                                <?php echo $order->t_ilce." / ".$order->t_il ?><br/>
                                <b>M:</b> <?php echo $order->t_mobile ?> - <b>T:</b> <?php echo $order->t_telefon?><br/>
                                </div>
                                <?php if ($order->fatura_ayni==0) {?>
                                <div class="adres" style="width:300px;float:left;margin-left:50px;">
                                <h1>FATURA ADRESİ</h1>
                                <b><?php echo $order->f_adresname." ".$order->f_adressurname ?></b><br/>
                                <?php echo $order->f_adres ?><br/>
                                <?php echo $order->f_ilce." / ".$order->f_il ?><br/>
                                <b>M:</b> <?php echo $order->f_mobile ?> - <b>T:</b> <?php echo $order->f_telefon?><br/>
                                </div>
                                <?php }?>
                                <div class="clear"></div>
                                <hr/>
                                <?php
                                if ($order->hediyemi==1) {?>
                                <h1>HEDİYE YAZISI</h1>
                                <p><?php echo $order->hediyetext ?></p>
                                <?php }
                                if (strlen($order->siparis_notu)) { ?>
                                <hr/>
                                <h1>SİPARİŞ NOTU</h1>
                                <?php echo $order->siparis_notu ?>
                                <?php }?>
                            </div>
                            </li>
                        <?php }?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <div id="sezgin"></div>
            <input type="hidden" name="orderkatid" value="" id="orderkatid" />
        </div>
    </div>
    <?php $this->load->view('busycms/includes/footer') ?>
</body>
</html>
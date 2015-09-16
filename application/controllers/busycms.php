<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Busycms extends CI_Controller {
    
    public function copyproduct($id) {
        $login = $this->session->userdata('busylogin');        
        if ($login == 1) {
            $sql=$this->db->query("select 
            maincat,
            catid,
            title,
            price,
            stock,
            related_products,
            description,
            usage_content,
            content,
            volume,
            percentage,
            publish,
            detail_description,
            designer,
            brand,
            material,
            size,
            size_name,
            weight,
            product_code,
            tags
            from products where id=".$id."");
            $product=$sql->result_array();
            if ($this->db->insert('products',$product[0])) {
                ?>
                <script>
                    location.href='<?php echo base_url() ?>busycms/editproduct/<?php echo $this->db->insert_id() ?>';
                </script>
                <?php
            }
        }
    }
    
    public function designerimageupload($id) {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            $this->load->model('busycms_model');

            $sql = $this->db->query("select * from designers_brands where id=" . $this->db->escape($id) . "");
            foreach ($sql->result() as $uye) {

                $name = $_FILES['resim']['name'];
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '6000';
                /* $config['max_width']  = '1024';
                  $config['max_height']  = '768'; */
                $config['file_name'] = $this->busycms_model->seo_url($name);
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('resim')) {
                    ?>
                    <script>
                        alert('<?php echo $this->upload->dispaly_errors() ?>');
                    </script>
                    <?php
                } else {
                    $this->load->library('image_lib');

                    $data = array('upload_data' => $this->upload->data());
                    $resimadi = $this->upload->file_name;




                    unset($config);
                    //Create 250px version
                    $config = array();
                    $config['source_image'] = "./uploads/" . $this->upload->file_name;
                    $config['image_library'] = 'gd2';
                    $config['maintain_ratio'] = TRUE;
                    $config['new_image'] = './uploads/' . $this->upload->file_name;
                    $config['master_dim'] = 'height';
                    $config['width'] = 600;
                    $config['height'] = 600;
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();
                    $this->image_lib->clear();
                    $width = $this->image_lib->width;
                    $height = $this->image_lib->height;
                    unset($config);

                    $array = array('image' => $this->upload->file_name);
                    $this->db->where('id', $id);
                    if ($this->db->update('designers_brands', $array)) {
                        ?>
                        <script>
                            parent.$('#poimage').attr('src','<?php echo base_url() ?>uploads/<?php echo $this->upload->file_name ?>')
//                            parent.location.href='<?php echo base_url() ?>busycms/eventcropposterimage/<?php echo $id ?>';
                        </script>
                        <?php
                    }
                }
            }
        }
    }
    
    public function designerpublish($id) {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            if (is_numeric($id)) {
                $sql = $this->db->query("select * from designers_brands where id=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $event) {
                    if ($event->publish == 1) {
                        $this->db->where('id', $id);
                        if ($this->db->update('designers_brands', array('publish' => 0))) {
                            ?>
                            <script>
                                $('#publish<?php echo $id ?>').removeClass('blackpublish');
                                $('#publish<?php echo $id ?>').addClass('graypublish');
                            </script>
                            <?php
                        }
                    } else {
                        $this->db->where('id', $id);
                        if ($this->db->update('designers_brands', array('publish' => 1))) {
                            ?>
                            <script>
                                $('#publish<?php echo $id ?>').removeClass('graypublish');
                                $('#publish<?php echo $id ?>').addClass('blackpublish');
                            </script>
                            <?php
                        }
                    }
                }
            }
        } else {
            ?> <script>alert('please login')</script> <?php
        }
    }
    
    public function designerdelete($id) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            ?> <script>alert('Please login')</script>
        <?php
        } else {    
            if (is_numeric($id)) {
                $this->db->where('id', $id);
                $this->db->delete('designers_brands');
                ?>

                <script>
                    $('.pagecol<?php echo $id ?>').fadeOut(1);
                    $('#recordsArray_<?php echo $id ?>').slideUp(250);
                    $('.notification .hide').click();
                </script>

            <?php
            }
        }
    }
    
    public function editdesigner_do() {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            ?> <script>alert('Please login')</script>
        <?php
        } else {            
            $title = $this->input->post('title');                      
            $style = $this->input->post('style');
            $website = $this->input->post('website');
            $email = $this->input->post('email');
            $pinterest = $this->input->post('pinterest');
            $facebook = $this->input->post('facebook');
            $twitter = $this->input->post('twitter');
            $instagram = $this->input->post('instagram');
            $content = $this->input->post('content');
            $saveclose = $this->input->post('saveclose');
            $id = $this->input->post('id');            
            
            $hata = 0;

            if (strlen($title) <= 2) {
                $error = $error . "<br/>Please enter the title";
                $hata = 1;
            }

            if ($hata == 1) {
                ?>
                <script>
                    $.notification ( 
                    {
                        title:      'Error',
                        content:    '<?php echo $error ?>',
                        icon:       '!',
                        color:      '#fff',
                        error : true,
                        timeout: 3000
                    }
                )
                </script>
                <?php
                exit();
            }
            $sql=$this->db->query("select * from designers_brands where id=".$this->db->escape($id)."");
            foreach($sql->result() as $designer) {
                $data = array(                
                    'title' => $title,
                    'style' => $style,
                    'website' => $website,
                    'email' => $email,
                    'pinterest' => $pinterest,
                    'facebook' => $facebook,
                    'twitter' => $twitter,
                    'instagram' => $instagram,
                    'content' => $content
                );
                $this->db->where("id", $designer->id);
                if ($this->db->update('designers_brands', $data)) {
                    ?>
                    <script>                      
                        /* $.notification ( 
                            {
                                title:      'Saved',
                                content:    '<br/>Save Changes',
                                icon:       '=',
                                color:      'green',
                                //error : true,
                                timeout: 3000
                            }
                        )*/
                    <?php if ($saveclose == 1) { ?>
                                parent.location.href='<?php echo base_url() ?>busycms/designers/?tip=<?php echo $designer->tip ?>';
                    <?php } else { ?>
                                parent.location.href='<?php echo base_url() ?>busycms/editdesigner/<?php echo $id ?>';
                    <?php } ?>
                    </script>
                    <?php
                }
            }
        }
    }
    
    public function editdesigner($id) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            $data["page"] = "hof";
            $data["page_title"] = "Busycms Events ";
            $data["page_desc"] = "Busycms Events ";
            $data["id"] = $id;
            if (is_numeric($id)) {
                $sql=$this->db->query("select * from designers_brands where id=".$this->db->escape($id)."");
                foreach($sql->result() as $designer) {
                    $data["designer"]=$designer;
                    $this->load->view('busycms/designers/editdesigner', $data);
                }
            }            
        }
    }
    
    public function addnewdesigner_do() {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            ?> <script>alert('Please login')</script>
        <?php
        } else {            
            $title = $this->input->post('title');
            $tip = $this->input->post('tip');
            $hata = 0;

            if (strlen($title) <= 2) {
                $error = $error . "<br/>Please enter the title";
                $hata = 1;
            }

            if ($hata == 1) {
                ?>
                <script>
                    $.notification ( 
                    {
                        title:      'Error',
                        content:    '<?php echo $error ?>',
                        icon:       '!',
                        color:      '#fff',
                        error : true,
                        timeout: 3000
                    }
                )
                </script>
                <?php
                exit();
            }
            //echo $year."-".$month."-".$day; exit();
            
            $data = array(
                'tip' => $tip,
                'title' => $title
            );

            if ($this->db->insert('designers_brands', $data)) {
                ?>
                <script>
                    parent.location.href='<?php echo base_url() ?>busycms/editdesigner/<?php echo $this->db->insert_id() ?>';
                </script>
            <?php
            }
        }
    }
    
    public function addnewdesigner($tip) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            $id="";
            $data["page"] = "hof";
            $data["page_title"] = "Busycms Events ";
            $data["page_desc"] = "Busycms Events ";
            $data["tip"] = $tip;
            $this->load->view('busycms/designers/addnewdesigner', $data);
        }
    }
    
    public function designers() {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {            
            $data["page"] = "designers";            
            $data["page_title"] = "Busycms Events ";
            $data["page_desc"] = "Busycms Events ";
            $data["tip"]=$this->input->get('tip');
            $this->load->view('busycms/designers/dashboard', $data);
        }
    }
    
    public function teslimcomplete($id) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            if (is_numeric($id)) {
                $this->db->where("id",$id);
                if ($this->db->update("user_orders",array("admin_action"=>6))) {
                    ?>
                        <script>parent.history.go(0);</script>
                    <?php
                }
            }
        }
    }
    
    public function siparisiptal($id) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            if (is_numeric($id)) {
                $this->db->where("order_id",$id);
                $this->db->delete("order_products");
                $this->db->where("id",$id);
                if ($this->db->delete("user_orders")) {
                    ?>
                        <script>parent.history.go(0);</script>
                    <?php
                }
            }
        }
    }
    
    
    
    public function havalecomplete($id) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            if (is_numeric($id)) {
                $this->db->where("id",$id);
                if ($this->db->update("user_orders",array("durum"=>5))) {
                    ?>
                        <script>parent.history.go(0);</script>
                    <?php
                }
            }
        }
    }
    
    public function kuryecompleteform_do() {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            $id=$this->input->post('id');
            $takipno=$this->input->post('takipno');
            if (is_numeric($id)) {
                if (strlen($takipno)==0) {
                    echo "Takip No ?";
                }
                else {
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
                            from user_orders,users
                            where
                           user_orders.user_id=users.id and user_orders.id=".$id." order by user_orders.id desc ");
                            foreach($sql->result() as $order) {
                                $this->db->where("id",$id);
                                if ($this->db->update("user_orders",array("admin_action"=>5,'durum'=>5,'takipno'=>$takipno))) {
                                    $this->load->model('email_model','emailmodel');
                                    $config['protocol']    = 'smtp';
                                    $config['smtp_host']    = 'mail.collektif.com';
                                    //$config['smtp_port']    = '465';
                                    //$config['smtp_timeout'] = '30';
                                    $config['smtp_user']    = 'noreply@collektif.com';
                                    $config['smtp_pass']    = 'Busy6512';
                                    $config['charset']    = 'utf-8';
                                    $config['newline']    = "\r\n";
                                    $config['mailtype'] = 'html'; // or html
                                    $config['validation'] = TRUE; // bool whether to validate email or not  

                                    $this->load->library('email',$config);
                                    $this->email->from("noreply@collektif.com", "Collektif");
                                    $this->email->reply_to("noreply@collektif.com", "Collektif");
                                    $this->email->to($order->email);
                                    $this->email->subject("Collektif Siparişiniz Kargoya Verildi");
                                    $content="
                                        collektif adresinden vermis olduğunuz <b>".$order->order_code."</b> no lu siparişiniz kargoya verilmiştir.<br/><br/>
                                            Kargonuzu <a href=\"http://www.ups.com.tr/\" style=\"color:#000;\">UPS</a> websitesinden <b>".$takipno."</b> kargo takip numarası ile takip edebilirsiniz.
                                    ";
                                    $this->email->message($this->emailmodel->content($content));

                                    if ( ! $this->email->send()){

                                    }
                                    else {
                                        ?><script>parent.history.go(0)</script><?php
                                    }
                                }
                            }
                }
            }
        }
    }
    
    public function kuryecomplete($id) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            if (is_numeric($id)) {
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
                            from user_orders,users
                            where
                           user_orders.user_id=users.id and user_orders.id=".$id." order by user_orders.id desc ");
                            foreach($sql->result() as $order) {
                                $this->db->where("id",$id);
                                if ($this->db->update("user_orders",array("admin_action"=>5,'durum'=>5))) {
                                    $this->load->model('email_model','emailmodel');
                                    $config['protocol']    = 'smtp';
                                    $config['smtp_host']    = 'mail.collektif.com';
                                    //$config['smtp_port']    = '465';
                                    //$config['smtp_timeout'] = '30';
                                    $config['smtp_user']    = 'noreply@collektif.com';
                                    $config['smtp_pass']    = 'Busy6512';
                                    $config['charset']    = 'utf-8';
                                    $config['newline']    = "\r\n";
                                    $config['mailtype'] = 'html'; // or html
                                    $config['validation'] = TRUE; // bool whether to validate email or not  

                                    $this->load->library('email',$config);
                                    $this->email->from("noreply@collektif.com", "Collektif");
                                    $this->email->reply_to("noreply@collektif.com", "Collektif");
                                    $this->email->to($order->email);
                                    $this->email->subject("Collektif Siparişiniz Kargoya Verildi");
                                    $content="
                                        collektif adresinden vermis olduğunuz <b>".$order->order_code."</b> no lu siparişiniz kargoya verilmiştir. 
                                    ";
                                    $this->email->message($this->emailmodel->content($content));

                                    if ( ! $this->email->send()){

                                    }
                                    else {
                                        ?><script>parent.history.go(0)</script><?php
                                    }
                                }
                            }
                           
            }
        }
    }


    public function orders($sipdurum=1) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            
            
                    $sql=$this->db->query("select id,tur,durum,admin_action from user_orders where tur=1 and durum=1");
                    $data["havalesay"]=$sql->num_rows();
                    
                    $sql=$this->db->query("select id,tur,durum,admin_action from user_orders where tur=3 and durum=1  and admin_action=0");
                    $data["kapidaodemesay"]=$sql->num_rows();
                    
                    $sql=$this->db->query("select id,tur,durum,admin_action from user_orders where durum=5 and admin_action=0");
                    $data["kuryesay"]=$sql->num_rows();
                    
                    $sql=$this->db->query("select id,tur,durum,admin_action from user_orders where durum=5 and admin_action=5");
                    $data["gonderilensay"]=$sql->num_rows();
                    
                    $sql=$this->db->query("select id,tur,durum,admin_action from user_orders where durum=5 and admin_action=6");
                    $data["teslimedilensay"]=$sql->num_rows();
                    
                    $data["page"] = "orders";
                    $data["sipdurum"]=$sipdurum;
                    $data["page_title"] = "Busycms Page Gallery";
                    $data["page_desc"] = "Busycms Page Gallery";
                    $this->load->view('busycms/orders/dashboard', $data);
            
        }
    }
    
    /* Orders */
    
    
    /* Members */
    
    public function members() {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            
                    $data["page"] = "members";
                    $data["page_title"] = "Busycms Page Gallery";
                    $data["page_desc"] = "Busycms Page Gallery";
                    $this->load->view('busycms/members/dashboard', $data);
            
        }
    }
    
    /* Members */
    
    
    
    
    
    
    
    
    
    public function uploadpageimage($id) {
        $this->load->library('upload');
        $this->load->model('busycms_model');
        $images = $_FILES['images']['name'];
        $resimsay = count($images);
        $error = 0;
        for ($i = 0; $i < $resimsay; $i++) {

            $_FILES['userfile']['name'] = $_FILES['images']['name'][$i];
            $_FILES['userfile']['type'] = $_FILES['images']['type'][$i];
            $_FILES['userfile']['tmp_name'] = $_FILES['images']['tmp_name'][$i];
            $_FILES['userfile']['error'] = $_FILES['images']['error'][$i];
            $_FILES['userfile']['size'] = $_FILES['images']['size'][$i];

            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'jpg|jpeg|gif|png';
            /* $config['max_size'] = '3200';
              $config['max_width'] = '2000';
              $config['max_height'] = '3000'; */
            $config['file_name'] = $this->busycms_model->seo_url($_FILES['images']['name'][$i]);
            $config['max_size'] = '0';
            $config['overwrite'] = FALSE;

            $this->upload->initialize($config);



            if ($this->upload->do_upload()) {

                $this->load->library('image_lib');

                $data = array('upload_data' => $this->upload->data());
                $resimadi = $this->upload->file_name;


                unset($config);
                $config = array();
                $config['source_image'] = "./uploads/" . $resimadi;
                $config['image_library'] = 'gd2';
                $config['maintain_ratio'] = TRUE;
                $config['new_image'] = './uploads/' . $resimadi;
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $width = $this->image_lib->width;
                $this->image_lib->clear();
                unset($config);

                if ($width > 1100) {
                    unset($config);
                    $config = array();
                    $config['source_image'] = "./uploads/" . $resimadi;
                    $config['image_library'] = 'gd2';
                    $config['maintain_ratio'] = TRUE;
                    $config['new_image'] = './uploads/' . $resimadi;
                    $config['master_dim'] = 'width';
                    $config['width'] = 1100;
                    $config['height'] = 1000;
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();

                    $this->image_lib->clear();
                    unset($config);
                }

                unset($config);
                //Create 250px version
                $config = array();
                $config['source_image'] = "./uploads/" . $this->upload->file_name;
                $config['image_library'] = 'gd2';
                $config['maintain_ratio'] = TRUE;
                $config['new_image'] = './uploads/thumb_' . $this->upload->file_name;
                $config['width'] = 250;
                $config['height'] = 250;
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $this->image_lib->clear();
                unset($config);

                $reorder = 0;
                $sql = $this->db->query("select max(reorder) as enbuyuk from images where pageid=" . $this->db->escape($id) . " and nedir=1");
                foreach ($sql->result() as $son) {
                    $reorder = $son->enbuyuk + 1;
                }
                if (!$reorder > 0) {
                    $reorder = 1;
                }

                $array = array('pageid' => $id, 'image' => $this->upload->file_name, 'reorder' => $reorder, 'nedir' => 1);
                $this->db->insert('images', $array);
            } else {
                $error = 1;
            }
        }
        ?>
        <script type="text/javascript">
            parent.$.ajax({
                url: '<?php echo base_url() ?>busycms/pagephotos/<?php echo $id ?>',
                success: function(data) { 
                    parent.$('#pagephotos').html(data);
                }
            });
        </script>
        <?php
        if ($error > 0) {
            echo "resimler yüklenemedi";
        } else {
            //echo "yükledim valla";
        }
        unset($config);
    }
    
    public function deleteitem($id) {
        $sql = $this->db->query("select * from items where id=" . $this->db->escape($id) . "");
        foreach ($sql->result() as $resim) {
            $this->db->where('id', $resim->id);
            if ($this->db->delete('items')) {
                ?>
                <script>
                    $('#recordsArray_<?php echo $resim->id ?>').fadeOut(500);
                    $('.notification .hide').click();
                </script>
            <?php
            }
        }
    }
    
    public function pageitem($id) {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            if (is_numeric($id)) {
                $sql = $this->db->query("select * from pages where id=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $event) {
                    if ($event->items == 1) {
                        $this->db->where('id', $id);
                        if ($this->db->update('pages', array('items' => 0))) {
                            ?>
                            <script>
                                
                            </script>
                            <?php
                        }
                    } else {
                        $this->db->where('id', $id);
                        if ($this->db->update('pages', array('items' => 1))) {
                            ?>
                            <script>
                            </script>
                            <?php
                        }
                    }
                }
            }
        } else {
            ?> <script>alert('please login')</script> <?php
        }
    }
    
    public function itemadd($id) {
        ?>
            <script>
                $('#pageaddform input:first').focus();
            </script>
            <form id="itemaddform" name="itemaddform" onsubmit="return false">
                <table>
                    <tr>
                        <td width="220" style="vertical-align:top;">
                            <h2 style="font-weight:normal;margin-bottom:0px;">Item Photo</h2>
                            <div style="clear:both"></div>
                            <img src="<?php echo base_url() ?>busycms/images/add-photo.png" onclick="$('#itemimageinput').click();" id="uploaditemphoto" style="width:200px;height:auto;" />
                        </td>
                        <td align="left" style="vertical-align:top;">
                <div style="float:left;width:500px;">
                <h2 style="font-weight:normal;margin-bottom:5px;">Title</h2>
                <input type="text" name="title" /><br/>
                </div>
                <div style="float:left;width:240px;margin-left:10px;display:none;">
                <h2 style="font-weight:normal;margin-bottom:5px;">Item Title English</h2>
                <input type="text" name="title2" /><br/>    
                </div>
                <div style="clear:both;"></div>
                <div style="float:left;width:500px;">
                <h2 style="font-weight:normal;margin-bottom:5px;">Link</h2>
                <input type="text" name="text" /><br/>
                </div>
                <div style="float:left;width:240px;margin-left:10px;display:none;">
                <h2 style="font-weight:normal;margin-bottom:5px;">Item Text English</h2>
                <input type="text" name="text2" /><br/>
                </div>
                <div style="clear:both;"></div>
                <?php if ($id==15) { ?>
                <div style="float:left;width:240px;">
                <h2 style="font-weight:normal;margin-bottom:5px;">Item Link</h2>
                <input type="text" name="link" />
                </div>
                <div style="float:left;width:240px;margin-left:10px;">
                <h2 style="font-weight:normal;margin-bottom:5px;">Item Link English</h2>
                <input type="text" name="link2" />
                </div>
                <div style="clear:both"></div>
                <?php }?>
                <input type="hidden" name="itemimage" id="itemimagehidden" />
                <input type="hidden" name="pageid" value="<?php echo $id ?>" />
                <br/>
                <div align="right">
                    <span id="itemaddform_back"></span><button onclick="submitform('busycms/','itemaddform')">Add New Item</button>
                </div>
                        </td>
                    </tr>
                </table>
            </form>
        <?php
    }
    
    public function itemaddform_do() {
        $title = $this->input->post('title');
        $title2 = $this->input->post('title2');
        $pageid = $this->input->post('pageid');
        $text = $this->input->post('text');
        $text2 = $this->input->post('text2');
        $image=$this->input->post('itemimage');
        $link=$this->input->post('link');
        $link2 = $this->input->post('link2');
        if (strlen($title) <= 2) {
            ?> 
            <script>
                $.notification ( 
                {
                    title:      'Error',
                    content:    'Please Enter Item Title',
                    icon:       '!',
                    color:      '#fff',
                    error : true,
                    timeout: 3000
                }
            )
            </script>
            <?php
            exit();
        }
        
        if (strlen($text) <= 2) {
            ?> 
            <script>
                $.notification ( 
                {
                    title:      'Error',
                    content:    'Please Enter Item Text',
                    icon:       '!',
                    color:      '#fff',
                    error : true,
                    timeout: 3000
                }
            )
            </script>
            <?php
            exit();
        }

        $data = array(
            'pageid' => $pageid,
            'title' => $title,
            'title2' => $title2,
            'content'=> $text,
            'content2' => $text2,
            'image' => $image,
            'link' => $link,
            'link2' => $link2,
            'itemdate' => $this->input->post('year')."-".$this->input->post('month')."-".$this->input->post('day')
        );
        
        $this->db->insert('items',$data);
        ?>
            <script>
            $("#overlays .modal").remove();
            $("#overlays").removeClass();
            $(document).unbind("keyup");
                $.ajax({
                url: '<?php echo base_url() ?>busycms/items/<?php echo $pageid ?>',
                        success: function(data) { 
                            $('#pageitems').html(data);
                    }
                });
            </script>
        <?php
    }
    
    public function uploaditemimage($id) {
        $this->load->library('upload');
        $this->load->model('busycms_model');
        $images = $_FILES['images']['name'];
        $resimsay = count($images);
        $error = 0;
        for ($i = 0; $i < $resimsay; $i++) {

            $_FILES['userfile']['name'] = $_FILES['images']['name'][$i];
            $_FILES['userfile']['type'] = $_FILES['images']['type'][$i];
            $_FILES['userfile']['tmp_name'] = $_FILES['images']['tmp_name'][$i];
            $_FILES['userfile']['error'] = $_FILES['images']['error'][$i];
            $_FILES['userfile']['size'] = $_FILES['images']['size'][$i];

            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'jpg|jpeg|gif|png';
            /* $config['max_size'] = '3200';
              $config['max_width'] = '2000';
              $config['max_height'] = '3000'; */
            $config['file_name'] = $this->busycms_model->seo_url($_FILES['images']['name'][$i]);
            $config['max_size'] = '0';
            $config['overwrite'] = FALSE;

            $this->upload->initialize($config);



            if ($this->upload->do_upload()) {

                $this->load->library('image_lib');

                $data = array('upload_data' => $this->upload->data());
                $resimadi = $this->upload->file_name;


                unset($config);
                $config = array();
                $config['source_image'] = "./uploads/" . $resimadi;
                $config['image_library'] = 'gd2';
                $config['maintain_ratio'] = TRUE;
                $config['new_image'] = './uploads/' . $resimadi;
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $width = $this->image_lib->width;
                $this->image_lib->clear();
                unset($config);

                if ($width > 1100) {
                    unset($config);
                    $config = array();
                    $config['source_image'] = "./uploads/" . $resimadi;
                    $config['image_library'] = 'gd2';
                    $config['maintain_ratio'] = TRUE;
                    $config['new_image'] = './uploads/' . $resimadi;
                    $config['master_dim'] = 'width';
                    $config['width'] = 1100;
                    $config['height'] = 1000;
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();

                    $this->image_lib->clear();
                    unset($config);
                }

                unset($config);
                //Create 250px version
                $config = array();
                $config['source_image'] = "./uploads/" . $this->upload->file_name;
                $config['image_library'] = 'gd2';
                $config['maintain_ratio'] = TRUE;
                $config['new_image'] = './uploads/thumb_' . $this->upload->file_name;
                $config['master_dim'] = 'height';
                $config['width'] = 250;
                $config['height'] = 250;
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $this->image_lib->clear();
                unset($config);
                ?>
                    <script>
                        parent.$('#itemimagehidden').val('<?php echo $this->upload->file_name ?>');
                        parent.$('#uploaditemphoto').attr('src','<?php echo base_url() ?>uploads/thumb_<?php echo $this->upload->file_name ?>');
                    </script>
                <?php
            } else {
                $error = 1;
            }
        }
        ?>
        <?php
        if ($error > 0) {
            echo "resimler yüklenemedi";
        } else {
            //echo "yükledim valla";
        }
        unset($config);
    }
    
    public function edititem($id="") {
        $login = $this->session->userdata('busylogin');
        if ($login==1) {
            $sql=$this->db->query("select * from items where id=".$this->db->escape($id)."");
            foreach($sql->result() as $item) {
        ?>
            <script>
                $('#pageaddform input:first').focus();
            </script>
            <form id="itemeditform" name="itemeditform" onsubmit="return false">
                <table>
                    <tr>
                        <td width="220" style="vertical-align:top;">
                            <h2 style="font-weight:normal;margin-bottom:0px;">Item Photo</h2>
                            <div style="clear:both"></div>
                            <?php if (strlen($item->image)==0) { ?>
                            <img src="<?php echo base_url() ?>busycms/images/add-photo.png" onclick="$('#itemimageinput').click();" id="uploaditemphoto" style="width:200px;height:auto;" />
                            <?php } else {?>
                            <img src="<?php echo base_url() ?>uploads/thumb_<?php echo $item->image ?>" onclick="$('#itemimageinput').click();" id="uploaditemphoto" style="width:200px;height:auto;" />
                            <?php }?>
                        </td>
                        <td align="left" style="vertical-align:top;">
                            
                                <div style="float:left;width:500px;">
                <h2 style="font-weight:normal;margin-bottom:5px;">Title</h2>
                <input type="text" name="title" value="<?php echo $item->title ?>" /><br/>
                </div>
                <div style="float:left;width:240px;margin-left:10px;display:none;">
                <h2 style="font-weight:normal;margin-bottom:5px;">Item Title English</h2>
                <input type="text" name="title2" value="<?php echo $item->title2 ?>" /><br/>
                </div>
                <div style="clear:both;"></div>
                <div style="float:left;width:500px;">
                <h2 style="font-weight:normal;margin-bottom:5px;">Link</h2>
                <input type="text" name="text" value="<?php echo $item->content ?>" />
                </div>
                <div style="float:left;width:240px;margin-left:10px;display:none;">
                    <h2 style="font-weight:normal;margin-bottom:5px;">Item Text English</h2>
                    <input type="text" name="text2" value="<?php echo $item->content2 ?>" />
                </div>
                <div style="clear:both;"></div>
                <?php if ($item->pageid==15) { ?><br/>
                <div style="float:left;width:240px;margin-left:0px;">
                <h2 style="font-weight:normal;margin-bottom:5px;">Item Link</h2>
                <input type="text" name="link" value="<?php echo $item->link ?>" />
                </div>
                <div style="float:left;width:240px;margin-left:10px;">
                    <h2 style="font-weight:normal;margin-bottom:5px;">Item Link English</h2>
                <input type="text" name="link2" value="<?php echo $item->link2 ?>" />
                </div>
                <div style="clear:both;"></div>
                <?php }?>
                <input type="hidden" name="itemimage" id="itemimagehidden" value="<?php echo $item->image ?>" />
                <input type="hidden" name="id" value="<?php echo $item->id ?>" />
                <input type="hidden" name="pageid" value="<?php echo $item->pageid ?>" />
                <br/>
                <div align="right">
                    <span id="itemeditform_back"></span><button onclick="submitform('busycms/','itemeditform')">Update Item</button>
                </div>
                        </td>
                    </tr>
                </table>
                </form>
        <?php 
        } } 
    }
    
    public function itemeditform_do() {
        $title = $this->input->post('title');
        $title2 = $this->input->post('title2');
        $id = $this->input->post('id');
        $text = $this->input->post('text');
        $text2 = $this->input->post('text2');
        $image=$this->input->post('itemimage');
        $pageid=$this->input->post('pageid');
        $link=$this->input->post('link');
        $link2 = $this->input->post('link2');
        if (strlen($title) <= 2) {
            ?> 
            <script>
                $.notification ( 
                {
                    title:      'Error',
                    content:    'Please Enter Item Title',
                    icon:       '!',
                    color:      '#fff',
                    error : true,
                    timeout: 3000
                }
            )
            </script>
            <?php
            exit();
        }
        
        if (strlen($text) <= 2) {
            ?> 
            <script>
                $.notification ( 
                {
                    title:      'Error',
                    content:    'Please Enter Item Text',
                    icon:       '!',
                    color:      '#fff',
                    error : true,
                    timeout: 3000
                }
            )
            </script>
            <?php
            exit();
        }
               
        $data = array(
            'title' => $title,
            'title2' => $title2,
            'content' => $text,
            'content2' => $text2, 
            'image' => $image,
            'link' => $link,
            'link2' => $link2,
            'itemdate' => $this->input->post('year')."-".$this->input->post('month')."-".$this->input->post('day')
        );
        
        $this->db->where('id',$id);
        $this->db->update('items',$data);
        ?>
            <script>
                $("#overlays .modal").remove();
                $("#overlays").removeClass();
                $(document).unbind("keyup");
                $.ajax({
                url: '<?php echo base_url() ?>busycms/items/<?php echo $pageid ?>',
                        success: function(data) { 
                            $('#pageitems').html(data);
                    }
                });
            </script>
        <?php
    }
    
    public function priceform_do() {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            $sql=$this->input->post('sql');
            $psql=$this->db->query($sql);
            foreach($psql->result() as $product) {
                $data=array(
                    'price'=>$this->input->post('price'.$product->id),
                    'stock'=>$this->input->post('stock'.$product->id),
                    'percentage'=>$this->input->post('percentage'.$product->id),
                    'product_code'=>$this->input->post('product_code'.$product->id)
                );
                $this->db->where('id',$product->id);
                $this->db->update('products',$data);
                
            }
            ?>
                <script>
                    $.ajax({
                        url: '<?php echo base_url() ?>busycms/productslist/<?php echo $this->input->post('catid') ?>/<?php echo $this->input->post('sort') ?>',
                        success: function(data) { parent.$('#productslist').html(data);
                            parent.$('#fiyat-duzenle-check input').click();
                            parent.$('.product-menus').fadeIn(0);
                            parent.$('.product-edits').fadeOut(0);
                            parent.$('#pricebuttons').fadeOut(0);
                        }
                    });
                </script>
            <?php
        }
        else {
            echo "please login";
        }
    }
    
    public function productslist($catid="",$sort="")
    {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            $addsql="";
            if ($sort=="a") { $orderby="order by title asc"; }
            elseif ($sort=="p") { $orderby="order by price desc"; }
            elseif ($sort=="s") { $orderby="order by stock desc"; }
            else { $orderby="order by title asc"; }
                       
            if (is_numeric($catid)) { $addsql=" where catid=$catid"; }
            $sql="select * from products $addsql $orderby";
                        
            $productssql=$this->db->query($sql);
            $data["listsql"]=$sql;
            $data["productssql"]=$productssql;
            $data["catid"]=$catid;
            $data["sort"]=$sort;
            $this->load->view('busycms/products/list',$data);
        }
        else {
            echo "please login";
        }
    }
    
    public function addnewproductform_do() {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            $title = $this->input->post('title');
            $catid = $this->input->post('catid');
            $error=0;
            
            if ($catid==0) {
                $error_text = "<br/>Please select the product category";
                $error = 1;
            }
            
            if (strlen($title) < 2) {
                $error_text = $error_text."<br/>Please enter the product name";
                $error = 1;
            }

            if ($error == 1) {
                ?>
                <script>
                    $.notification ( 
                    {
                        title:      'Error',
                        content:    '<?php echo $error_text ?>',
                        icon:       '!',
                        color:      '#fff',
                        error : true,
                        timeout: 5000
                    }
                )
                </script>
                <?php
                exit();
            } else {
                
                $sql=$this->db->query("select * from product_categories where id=".$catid."");
                foreach($sql->result() as $c) {
                    $maincat=$c->catid;
                }
                
                $data = array('maincat'=>$maincat,'catid' => $catid, 'title' => $title);
                if ($this->db->insert('products', $data)) {
                    ?> <script>parent.location.href='<?php echo base_url() ?>busycms/editproduct/<?php echo $this->db->insert_id() ?>'</script> <?php
                }
            }
        } else {
            echo "Please login";
            exit();
        }
    }
    
    public function addnewproductcategoryform_do() {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            $error=0;
            $title = $this->input->post('title');
            $catid = $this->input->post('catid');
            if (strlen($title) < 2) {
                $error_text = "<br/>Please enter the Category name";
                $error = 1;
            }

            if ($error == 1) {
                ?>
                <script>
                    $.notification ( 
                    {
                        title:      'Error',
                        content:    '<?php echo $error_text ?>',
                        icon:       '!',
                        color:      '#fff',
                        error : true,
                        timeout: 5000
                    }
                )
                </script>
                <?php
                exit();
            } else {
                $data = array('catid' => $catid, 'title' => $title);
                if ($this->db->insert('product_categories', $data)) {
                    ?> <script>parent.location.href='<?php echo base_url() ?>busycms/editproductcategory/<?php echo $this->db->insert_id() ?>'</script> <?php
                }
            }
        } else {
            echo "Please login";
            exit();
        }
    }

    public function addproductcategory($id) {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            ?>
            <div style="position:relative;">
                <div style="background:url(<?php echo base_url() ?>busycms/images/video-icon.png) no-repeat center left;color:#000;font-weight:bold;font-size:20px;padding-left:50px;">
                    Add New Product Category
                </div>
                <div style="position:absolute;right:0;font-family:Icons;top:0;font-size:20px;display:none;">
                    X
                </div><br/>
                <hr/>
            </div><br/>
            <div style="line-height:44px;">
                <form id="addvideoform" name="addvideoform">
                    <b style="font-size:24px;font-weight:normal;">Main Category</b><br/>
                    <select name="category" style="padding:20px;height:50px;">
                        <option value="0">Main Category</option>
                    </select>
                    <div style="padding-top:20px;">
                        <b style="font-size:24px;font-weight:normal;">Category Title</b><br/>
                        <input type="text" name="videolink" style="padding:25px 10px;font-size:16px;" />
                    </div><br/>
                    <div align="center">
                        <button onclick="submitform('busycms/','addvideoform');" style="height: 45px;
                                font: bold 14px/45px HelveticaNeue, Helvetica, Arial;
                                width: 100%;
                                cursor: pointer;
                                -moz-border-radius: 4px;
                                -webkit-border-radius: 4px;
                                border-radius: 4px;
                                border: 1px solid rgba(0,0,0,0.4);
                                margin: 0px;
                                box-shadow: white 0px 1px 0px, 0px 1px 0px rgba(255,255,255,0.5) inset;
                                background-image: -webkit-linear-gradient(transparent, rgba(0,0,0,0.2));
                                background-image: -moz-linear-gradient(transparent, rgba(0,0,0,0.2));
                                background-color: #FE4365;
                                color: white;
                                text-shadow: rgba(0,0,0,0.3) 0px 1px 1px;
                                padding: 0px;width:200px;">Add</button>
                    </div>
                    <div id="addvideoform_back">
                    </div>
                </form>
            </div>
            <?php
        } else {
            echo "please login";
        }
    }

    public function eventpublish($id) {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            if (is_numeric($id)) {
                $sql = $this->db->query("select * from events where id=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $event) {
                    if ($event->publish == 1) {
                        $this->db->where('id', $id);
                        if ($this->db->update('events', array('publish' => 0))) {
                            ?>
                            <script>
                                $('#publish<?php echo $id ?>').removeClass('blackpublish');
                                $('#publish<?php echo $id ?>').addClass('graypublish');
                            </script>
                            <?php
                        }
                    } else {
                        $this->db->where('id', $id);
                        if ($this->db->update('events', array('publish' => 1))) {
                            ?>
                            <script>
                                $('#publish<?php echo $id ?>').removeClass('graypublish');
                                $('#publish<?php echo $id ?>').addClass('blackpublish');
                            </script>
                            <?php
                        }
                    }
                }
            }
        } else {
            ?> <script>alert('please login')</script> <?php
        }
    }
    
    public function productpublish($id) {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            if (is_numeric($id)) {
                $sql = $this->db->query("select * from products where id=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $event) {
                    if ($event->publish == 1) {
                        $this->db->where('id', $id);
                        if ($this->db->update('products', array('publish' => 0))) {
                            ?>
                            <script>
                                $('#publish<?php echo $id ?>').removeClass('blackpublish');
                                $('#publish<?php echo $id ?>').addClass('graypublish');
                            </script>
                            <?php
                        }
                    } else {
                        $this->db->where('id', $id);
                        if ($this->db->update('products', array('publish' => 1))) {
                            ?>
                            <script>
                                $('#publish<?php echo $id ?>').removeClass('graypublish');
                                $('#publish<?php echo $id ?>').addClass('blackpublish');
                            </script>
                            <?php
                        }
                    }
                }
            }
        } else {
            ?> <script>alert('please login')</script> <?php
        }
    }

    public function videoadd($id) {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            if (is_numeric($id)) {
                ?>
                <div style="position:relative;">
                    <div style="background:url(<?php echo base_url() ?>busycms/images/video-icon.png) no-repeat center left;color:#000;font-weight:bold;font-size:20px;padding-left:50px;">
                        Add New Video
                    </div>
                    <hr/>
                </div><br/>
                <div style="line-height:44px;">
                    <form id="addvideoform" name="addvideoform">
                        <b style="font-size:24px;font-weight:normal;">Video Title</b><br/>                        
                        <input type="text" name="title" />
                        <input type="hidden" name="id" value="<?php echo $id ?>" />
                        <div style="padding-top:20px;">
                            <b style="font-size:24px;font-weight:normal;">Video Link</b><br/>
                            <input type="text" name="videolink" />
                        </div><br/>
                        <div align="center">
                            <button onclick="submitform('busycms/','addvideoform');" style="height: 45px;
                                    font: bold 14px/45px HelveticaNeue, Helvetica, Arial;
                                    width: 100%;
                                    cursor: pointer;
                                    -moz-border-radius: 4px;
                                    -webkit-border-radius: 4px;
                                    border-radius: 4px;
                                    border: 1px solid rgba(0,0,0,0.4);
                                    margin: 0px;
                                    box-shadow: white 0px 1px 0px, 0px 1px 0px rgba(255,255,255,0.5) inset;
                                    background-image: -webkit-linear-gradient(transparent, rgba(0,0,0,0.2));
                                    background-image: -moz-linear-gradient(transparent, rgba(0,0,0,0.2));
                                    background-color: #FE4365;
                                    color: white;
                                    text-shadow: rgba(0,0,0,0.3) 0px 1px 1px;
                                    padding: 0px;width:200px;">Add</button>
                        </div>
                        <div id="addvideoform_back">
                        </div>
                    </form>
                </div>
                <?php
            }
        } else {
            echo "please login";
        }
    }

    public function soundadd($id) {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            if (is_numeric($id)) {
                ?>
                <div style="position:relative;">
                    <div style="background:url(<?php echo base_url() ?>busycms/images/sound-icon.jpg) no-repeat center left;color:#000;font-weight:bold;font-size:20px;padding-left:50px;">
                        Add New Sound
                    </div>
                    <div style="position:absolute;right:0;font-family:Icons;top:0;font-size:20px;">
                        X
                    </div><br/>
                    <hr/>
                </div><br/>
                <div style="line-height:44px;">
                    <form id="addsoundform" name="addsoundform">
                        <b style="font-size:24px;font-weight:normal;">Sound Title</b><br/>
                        <input type="text" name="title"/>
                        <input type="hidden" name="id" value="<?php echo $id ?>" />
                        <div style="padding-top:20px;">
                            <b style="font-size:24px;font-weight:normal;">Sound Embed Code</b><br/>
                            <textarea name="soundembed" style="padding-left:10px;height:80px;"></textarea>
                        </div><br/>
                        <div align="center">
                            <button onclick="submitform('busycms/','addsoundform');" style="height: 45px;
                                    font: bold 14px/45px HelveticaNeue, Helvetica, Arial;
                                    width: 100%;
                                    cursor: pointer;
                                    -moz-border-radius: 4px;
                                    -webkit-border-radius: 4px;
                                    border-radius: 4px;
                                    border: 1px solid rgba(0,0,0,0.4);
                                    margin: 0px;
                                    box-shadow: white 0px 1px 0px, 0px 1px 0px rgba(255,255,255,0.5) inset;
                                    background-image: -webkit-linear-gradient(transparent, rgba(0,0,0,0.2));
                                    background-image: -moz-linear-gradient(transparent, rgba(0,0,0,0.2));
                                    background-color: #FE4365;
                                    color: white;
                                    text-shadow: rgba(0,0,0,0.3) 0px 1px 1px;
                                    padding: 0px;width:200px;">Add</button>
                        </div>
                        <div id="addsoundform_back">
                        </div>
                    </form>
                </div>
                <?php
            }
        } else {
            echo "please login";
        }
    }

    public function eventphotos($id, $eventid) {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            if (is_numeric($id)) {
                $sql = $this->db->query("select * from artists where id=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $artist) {
                    $data["artist"] = $artist;
                    $data["eventid"] = $eventid;
                    $this->load->view('busycms/events/eventphotos', $data);
                }
            }
        } else {
            echo "please login";
        }
    }
    
    public function productphotos($id) {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            if (is_numeric($id)) {
                $sql = $this->db->query("select * from products where id=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $product) {
                    $data["product"] = $product;
                    $this->load->view('busycms/products/photos', $data);
                }
            }
        } else {
            echo "please login";
        }
    }
    
    public function projectphotos($id) {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            if (is_numeric($id)) {
                $sql = $this->db->query("select * from projects where id=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $product) {
                    $data["project"] = $product;
                    $this->load->view('busycms/projects/photos', $data);
                }
            }
        } else {
            echo "please login";
        }
    }
    
    public function newsphotos($id) {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            if (is_numeric($id)) {
                $sql = $this->db->query("select * from news where id=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $news) {
                    $data["news"] = $news;
                    $this->load->view('busycms/news/photos', $data);
                }
            }
        } else {
            echo "please login";
        }
    }

    public function editartistform_do() {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            $id = $this->input->post('id');
            $name = $this->input->post('name');
            $artistfrom = $this->input->post('artistfrom');
            $relatedartists = $this->input->post('relatedartists');
            $website = $this->input->post('website');
            $facebook = $this->input->post('facebook');
            $twitter = $this->input->post('twitter');
            $soundcloud = $this->input->post('soundcloud');
            $description = $this->input->post('description');
            $myspace=$this->input->post('myspace');
            if (strlen($name) < 2) {
                $error_text = "<br/>Please enter the Artist/Band name";
                $error = 1;
            }


            if ($error == 1) {
                ?>
                <script>
                    $.notification ( 
                    {
                        title:      'Error',
                        content:    '<?php echo $error_text ?>',
                        icon:       '!',
                        color:      '#fff',
                        error : true,
                        timeout: 5000
                    }
                )
                </script>
                <?php
                exit();
            } else {
                $array = array('name' => $name,
                    'artistfrom' => $artistfrom,
                    'relatedartists' => $relatedartists,
                    'website' => $website,
                    'facebook' => $facebook,
                    'twitter' => $twitter,
                    'soundcloud' => $soundcloud,
                    'description' => $description,
                    'myspace' => $myspace
                );
                $this->db->where('id', $id);
                if ($this->db->update('artists', $array)) {
                    ?>
                    <script>
                        $('#artist<?php echo $id ?> b').html('<?php echo $name ?>');
                        $('#editartist').fadeOut(500,function(){
                            $('#editartist').fadeIn(500)
                        });
                    </script>
                <?php
                }
            }
        } else {
            echo "please login";
        }
    }

    public function editartist($id, $eventid) {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            if (is_numeric($id)) {
                $sql = $this->db->query("select * from artists where id=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $artist) {
                    $data["artist"] = $artist;
                    $data["eventid"] = $eventid;
                    $this->load->view('busycms/events/editartist', $data);
                }
            }
        } else {
            echo "please login";
        }
    }

    public function deleteartist($id, $event) {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            $array = array('artistid' => $id, 'eventid' => $event);
            if ($this->db->delete('eventartists', $array)) {
                ?>
                <script>
                    parent.$('#artist<?php echo $id ?>').slideUp(500);
                    $('.notification .hide').click();
                                    
                    $('.artistsavebutton').fadeOut(1);
                    $('.newartistsavebutton').fadeOut(1);
                    $('.eventbutton').fadeIn(1); 
                    $('#addnewartist').fadeOut(500);
                    $('#editartist').fadeOut(500);
                    $('#editcontent').fadeIn(500);
                </script>
            <?php
            }
        } else {
            echo "please login";
        }
    }

    public function eventartists($id="") {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            $sql = $this->db->query("
            select 
            eventartists.eventid , eventartists.artistid,
            artists.id , artists.name 
            from 
            artists,eventartists
            where
            artists.id=eventartists.artistid and eventartists.eventid=" . $this->db->escape($id) . "
            ");
            echo "<ul class=\"artistlist\">";
            foreach ($sql->result() as $artist) {
                ?>
                <li id="artist<?php echo $artist->id ?>"><span>a</span><b><?php echo $artist->name ?></b>
                    <label>
                        <a href="javascript:void(0)" onclick="editartist(<?php echo $artist->id . "," . $id ?>)">8</a>
                        <a href="javascript:void(0)" onclick="deleteartist(<?php echo $artist->id ?>)">X</a>
                    </label>
                </li>
            <?php
            }
            echo "</ul>";
        } else {
            echo "please login";
            exit();
        }
    }

    public function addnewcolorform_do() {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            $id = $this->input->post('id');
            $title = $this->input->post('color_name');
            $error = 0;

            if (strlen($title) < 2) {
                $error_text = "<br/>Please enter the Artist/Band name";
                $error = 1;
            }


            if ($error == 1) {
                ?>
                <script>
                    $.notification ( 
                    {
                        title:      'Error',
                        content:    '<?php echo $error_text ?>',
                        icon:       '!',
                        color:      '#fff',
                        error : true,
                        timeout: 5000
                    }
                )
                </script>
                <?php
                exit();
            } else {
                $array = array('name' => $title);
                $this->db->insert('artists', $array);
                $eklenenartist = $this->db->insert_id();
                $array = array('artistid' => $this->db->insert_id(),
                    'eventid' => $id);
                if ($this->db->insert('eventartists', $array)) {
                    ?>
                    <script>
                        artists(<?php echo $id ?>);
                        parent.$('#addnewartistform input:first').val('');
                        $('#addnewartist').fadeOut(500,function(){
                            editartist(<?php echo $eklenenartist ?>);    
                        });                
                    </script>
                    <?php
                }
            }
        } else {
            echo "please login";
        }
    }

    public function eventcropmainimage($id) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            if (is_numeric($id)) {
                $sql = $this->db->query("select * from events where id=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $page) {
                    $data["page"] = "events";
                    $data["page_title"] = "Busycms Event Gallery";
                    $data["page_desc"] = "Busycms Event Gallery";
                    $data["id"] = $id;
                    $data["imageid"] = $imageid;
                    $data["catid"] = $page->catid;
                    $data["event"] = $page;
                    $this->load->view('busycms/events/cropmainimage', $data);
                }
            }
        }
    }
    
    public function productcategoryimagecrop($id) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            if (is_numeric($id)) {
                $sql = $this->db->query("select * from product_categories where id=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $page) {
                    $data["page"] = "events";
                    $data["page_title"] = "Busycms Event Gallery";
                    $data["page_desc"] = "Busycms Event Gallery";
                    $data["id"] = $id;
                    $this->load->view('busycms/products/categorycropmainimage', $data);
                }
            }
        }
    }
    
    public function productcropmainimage($id) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            if (is_numeric($id)) {
                $sql = $this->db->query("select * from products where id=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $page) {
                    $data["page"] = "events";
                    $data["page_title"] = "Busycms Event Gallery";
                    $data["page_desc"] = "Busycms Event Gallery";
                    $data["id"] = $id;
                    $this->load->view('busycms/products/productcropmainimage', $data);
                }
            }
        }
    }

    public function eventmainimageupload($id) {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            $this->load->model('busycms_model');

            $sql = $this->db->query("select * from events where id=" . $this->db->escape($id) . "");
            foreach ($sql->result() as $uye) {

                $name = $_FILES['resim']['name'];
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '6000';
                /* $config['max_width']  = '1024';
                  $config['max_height']  = '768'; */
                $config['file_name'] = $this->busycms_model->seo_url($name);
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('resim')) {
                    ?>
                    <script>
                        alert('<?php echo $this->upload->dispaly_errors() ?>');
                    </script>
                    <?php
                } else {
                    $this->load->library('image_lib');

                    $data = array('upload_data' => $this->upload->data());
                    $resimadi = $this->upload->file_name;


                    unset($config);
                    $config = array();
                    $config['source_image'] = "./uploads/" . $resimadi;
                    $config['image_library'] = 'gd2';
                    $config['maintain_ratio'] = TRUE;
                    $config['new_image'] = './uploads/' . $resimadi;
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();
                    $width = $this->image_lib->width;
                    $this->image_lib->clear();
                    unset($config);

                    if ($width > 1000) {
                        unset($config);
                        $config = array();
                        $config['source_image'] = "./uploads/" . $resimadi;
                        $config['image_library'] = 'gd2';
                        $config['maintain_ratio'] = TRUE;
                        $config['new_image'] = './uploads/' . $resimadi;
                        $config['master_dim'] = 'width';
                        $config['width'] = 1000;
                        $config['height'] = 600;
                        $this->image_lib->initialize($config);
                        $this->image_lib->resize();
                        $this->image_lib->clear();
                        unset($config);
                    }

                    unset($config);
                    //Create 250px version
                    $config = array();
                    $config['source_image'] = "./uploads/" . $this->upload->file_name;
                    $config['image_library'] = 'gd2';
                    $config['maintain_ratio'] = TRUE;
                    $config['new_image'] = './uploads/thumb_' . $this->upload->file_name;
                    $config['master_dim'] = 'height';
                    $config['width'] = 300;
                    $config['height'] = 300;
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();
                    $this->image_lib->clear();
                    $width = $this->image_lib->width;
                    $height = $this->image_lib->height;
                    unset($config);

                    $array = array('image' => $this->upload->file_name);
                    $this->db->where('id', $id);
                    if ($this->db->update('events', $array)) {
                        ?>
                        <script>
                            parent.location.href='<?php echo base_url() ?>busycms/eventcropmainimage/<?php echo $id ?>';
                        </script>
                        <?php
                    }
                }
            }
        }
    }
    
    public function pagemainimageupload($id) {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            $this->load->model('busycms_model');

            $sql = $this->db->query("select * from pages where id=" . $this->db->escape($id) . "");
            foreach ($sql->result() as $uye) {

                $name = $_FILES['resim']['name'];
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '6000';
                /* $config['max_width']  = '1024';
                  $config['max_height']  = '768'; */
                $config['file_name'] = $this->busycms_model->seo_url($name);
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('resim')) {
                    ?>
                    <script>
                        alert('<?php echo $this->upload->dispaly_errors() ?>');
                    </script>
                    <?php
                } else {
                    $this->load->library('image_lib');

                    $data = array('upload_data' => $this->upload->data());
                    $resimadi = $this->upload->file_name;


                    unset($config);
                    $config = array();
                    $config['source_image'] = "./uploads/" . $resimadi;
                    $config['image_library'] = 'gd2';
                    $config['maintain_ratio'] = TRUE;
                    $config['new_image'] = './uploads/' . $resimadi;
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();
                    $width = $this->image_lib->width;
                    $this->image_lib->clear();
                    unset($config);

                    if ($width > 1000) {
                        unset($config);
                        $config = array();
                        $config['source_image'] = "./uploads/" . $resimadi;
                        $config['image_library'] = 'gd2';
                        $config['maintain_ratio'] = TRUE;
                        $config['new_image'] = './uploads/' . $resimadi;
                        $config['master_dim'] = 'width';
                        $config['width'] = 1000;
                        $config['height'] = 600;
                        $this->image_lib->initialize($config);
                        $this->image_lib->resize();
                        $this->image_lib->clear();
                        unset($config);
                    }

                    unset($config);
                    //Create 250px version
                    $config = array();
                    $config['source_image'] = "./uploads/" . $this->upload->file_name;
                    $config['image_library'] = 'gd2';
                    $config['maintain_ratio'] = TRUE;
                    $config['new_image'] = './uploads/thumb_' . $this->upload->file_name;
                    $config['master_dim'] = 'height';
                    $config['width'] = 300;
                    $config['height'] = 300;
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();
                    $this->image_lib->clear();
                    $width = $this->image_lib->width;
                    $height = $this->image_lib->height;
                    unset($config);

                    $array = array('image' => $this->upload->file_name);
                    $this->db->where('id', $id);
                    if ($this->db->update('pages', $array)) {
                        ?>
                        <script>
                            //parent.location.href='<?php echo base_url() ?>busycms/productcropmainimage/<?php echo $id ?>';
                            parent.$('#bigimage').attr('src', '<?php echo base_url() ?>uploads/<?php echo $this->upload->file_name ?>')
                        </script>
                        <?php
                    }
                }
            }
        }
    }
    
    public function productmainimageupload($id) {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            $this->load->model('busycms_model');

            $sql = $this->db->query("select * from products where id=" . $this->db->escape($id) . "");
            foreach ($sql->result() as $uye) {

                $name = $_FILES['resim']['name'];
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '6000';
                /* $config['max_width']  = '1024';
                  $config['max_height']  = '768'; */
                $config['file_name'] = $this->busycms_model->seo_url($name);
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('resim')) {
                    ?>
                    <script>
                        alert('<?php echo $this->upload->dispaly_errors() ?>');
                    </script>
                    <?php
                } else {
                    $this->load->library('image_lib');

                    $data = array('upload_data' => $this->upload->data());
                    $resimadi = $this->upload->file_name;


                    unset($config);
                    $config = array();
                    $config['source_image'] = "./uploads/" . $resimadi;
                    $config['image_library'] = 'gd2';
                    $config['maintain_ratio'] = TRUE;
                    $config['new_image'] = './uploads/' . $resimadi;
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();
                    $width = $this->image_lib->width;
                    $this->image_lib->clear();
                    unset($config);

                    if ($width > 1000) {
                        unset($config);
                        $config = array();
                        $config['source_image'] = "./uploads/" . $resimadi;
                        $config['image_library'] = 'gd2';
                        $config['maintain_ratio'] = TRUE;
                        $config['new_image'] = './uploads/' . $resimadi;
                        $config['master_dim'] = 'width';
                        $config['width'] = 1000;
                        $config['height'] = 600;
                        $this->image_lib->initialize($config);
                        $this->image_lib->resize();
                        $this->image_lib->clear();
                        unset($config);
                    }

                    unset($config);
                    //Create 250px version
                    $config = array();
                    $config['source_image'] = "./uploads/" . $this->upload->file_name;
                    $config['image_library'] = 'gd2';
                    $config['maintain_ratio'] = TRUE;
                    $config['new_image'] = './uploads/thumb_' . $this->upload->file_name;
                    $config['master_dim'] = 'height';
                    $config['width'] = 400;
                    $config['height'] = 400;
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();
                    $this->image_lib->clear();
                    $width = $this->image_lib->width;
                    $height = $this->image_lib->height;
                    unset($config);

                    $array = array('image' => $this->upload->file_name);
                    $this->db->where('id', $id);
                    if ($this->db->update('products', $array)) {
                        ?>
                        <script>
                            //parent.location.href='<?php echo base_url() ?>busycms/productcropmainimage/<?php echo $id ?>';
                            parent.$('#bigimage').attr('src', '<?php echo base_url() ?>uploads/<?php echo $this->upload->file_name ?>')
                        </script>
                        <?php
                    }
                }
            }
        }
    }
    
    public function newsmainimageupload($id) {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            $this->load->model('busycms_model');

            $sql = $this->db->query("select * from news where id=" . $this->db->escape($id) . "");
            foreach ($sql->result() as $news) {

                $name = $_FILES['resim']['name'];
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '6000';
                /* $config['max_width']  = '1024';
                  $config['max_height']  = '768'; */
                $config['file_name'] = $this->busycms_model->seo_url($name);
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('resim')) {
                    ?>
                    <script>
                        alert('<?php echo $this->upload->display_errors() ?>');
                    </script>
                    <?php
                } else {
                    $this->load->library('image_lib');

                    $data = array('upload_data' => $this->upload->data());
                    $resimadi = $this->upload->file_name;


                    unset($config);
                    $config = array();
                    $config['source_image'] = "./uploads/" . $resimadi;
                    $config['image_library'] = 'gd2';
                    $config['maintain_ratio'] = TRUE;
                    $config['new_image'] = './uploads/' . $resimadi;
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();
                    $width = $this->image_lib->width;
                    $this->image_lib->clear();
                    unset($config);

                    if ($width > 1000) {
                        unset($config);
                        $config = array();
                        $config['source_image'] = "./uploads/" . $resimadi;
                        $config['image_library'] = 'gd2';
                        $config['maintain_ratio'] = TRUE;
                        $config['new_image'] = './uploads/' . $resimadi;
                        $config['master_dim'] = 'width';
                        $config['quality']='100%';
                        $config['width'] = 1000;
                        $config['height'] = 600;
                        $this->image_lib->initialize($config);
                        $this->image_lib->resize();
                        $this->image_lib->clear();
                        unset($config);
                    }

                    unset($config);
                    //Create 250px version
                    $config = array();
                    $config['source_image'] = "./uploads/" . $this->upload->file_name;
                    $config['image_library'] = 'gd2';
                    $config['maintain_ratio'] = TRUE;
                    $config['new_image'] = './uploads/thumb_' . $this->upload->file_name;
                    $config['master_dim'] = 'width';
                    $config['width'] = 300;
                    $config['height'] = 200;
                    $config['quality']='100%';
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();
                    $this->image_lib->clear();
                    $width = $this->image_lib->width;
                    $height = $this->image_lib->height;
                    unset($config);

                    $array = array('image' => $this->upload->file_name);
                    $this->db->where('id', $id);
                    if ($this->db->update('news', $array)) {
                        ?>
                        <script>
                            //parent.location.href='<?php echo base_url() ?>busycms/productcropmainimage/<?php echo $id ?>';
                            parent.$('#bigimage').attr('src', '<?php echo base_url() ?>uploads/<?php echo $this->upload->file_name ?>')
                        </script>
                        <?php
                    }
                }
            }
        }
    }
    
    public function projectmainimageupload($id) {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            $this->load->model('busycms_model');

            $sql = $this->db->query("select * from projects where id=" . $this->db->escape($id) . "");
            foreach ($sql->result() as $project) {

                $name = $_FILES['resim']['name'];
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '6000';
                /* $config['max_width']  = '1024';
                  $config['max_height']  = '768'; */
                $config['file_name'] = $this->busycms_model->seo_url($name);
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('resim')) {
                    ?>
                    <script>
                        alert('<?php echo $this->upload->display_errors() ?>');
                    </script>
                    <?php
                } else {
                    $this->load->library('image_lib');

                    $data = array('upload_data' => $this->upload->data());
                    $resimadi = $this->upload->file_name;


                    unset($config);
                    $config = array();
                    $config['source_image'] = "./uploads/" . $resimadi;
                    $config['image_library'] = 'gd2';
                    $config['maintain_ratio'] = TRUE;
                    $config['new_image'] = './uploads/' . $resimadi;
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();
                    $width = $this->image_lib->width;
                    $this->image_lib->clear();
                    unset($config);

                    if ($width > 1000) {
                        unset($config);
                        $config = array();
                        $config['source_image'] = "./uploads/" . $resimadi;
                        $config['image_library'] = 'gd2';
                        $config['maintain_ratio'] = TRUE;
                        $config['new_image'] = './uploads/' . $resimadi;
                        $config['master_dim'] = 'width';
                        $config['quality']='100%';
                        $config['width'] = 1000;
                        $config['height'] = 600;
                        $this->image_lib->initialize($config);
                        $this->image_lib->resize();
                        $this->image_lib->clear();
                        unset($config);
                    }

                    unset($config);
                    //Create 250px version
                    $config = array();
                    $config['source_image'] = "./uploads/" . $this->upload->file_name;
                    $config['image_library'] = 'gd2';
                    $config['maintain_ratio'] = TRUE;
                    $config['new_image'] = './uploads/thumb_' . $this->upload->file_name;
                    $config['master_dim'] = 'width';
                    $config['width'] = 300;
                    $config['height'] = 200;
                    $config['quality']='100%';
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();
                    $this->image_lib->clear();
                    $width = $this->image_lib->width;
                    $height = $this->image_lib->height;
                    unset($config);

                    $array = array('image' => $this->upload->file_name);
                    $this->db->where('id', $id);
                    if ($this->db->update('projects', $array)) {
                        ?>
                        <script>
                            //parent.location.href='<?php echo base_url() ?>busycms/productcropmainimage/<?php echo $id ?>';
                            parent.$('#bigimage').attr('src', '<?php echo base_url() ?>uploads/<?php echo $this->upload->file_name ?>')
                        </script>
                        <?php
                    }
                }
            }
        }
    }

    public function projectcoverimageupload($id) {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            $this->load->model('busycms_model');

            $sql = $this->db->query("select * from projects where id=" . $this->db->escape($id) . "");
            foreach ($sql->result() as $project) {

                $name = $_FILES['resim']['name'];
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '6000';
                /* $config['max_width']  = '1024';
                  $config['max_height']  = '768'; */
                $config['file_name'] = $this->busycms_model->seo_url($name);
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('resim')) {
                    ?>
                    <script>
                        alert('<?php echo $this->upload->display_errors() ?>');
                    </script>
                    <?php
                } else {
                    $this->load->library('image_lib');

                    $data = array('upload_data' => $this->upload->data());
                    $resimadi = $this->upload->file_name;


                    unset($config);
                    $config = array();
                    $config['source_image'] = "./uploads/" . $resimadi;
                    $config['image_library'] = 'gd2';
                    $config['maintain_ratio'] = TRUE;
                    $config['new_image'] = './uploads/' . $resimadi;
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();
                    $width = $this->image_lib->width;
                    $this->image_lib->clear();
                    unset($config);

                    if ($width > 1024) {
                        unset($config);
                        $config = array();
                        $config['source_image'] = "./uploads/" . $resimadi;
                        $config['image_library'] = 'gd2';
                        $config['maintain_ratio'] = TRUE;
                        $config['new_image'] = './uploads/' . $resimadi;
                        $config['master_dim'] = 'width';
                        $config['quality']='100%';
                        $config['width'] = 1024;
                        $config['height'] = 576;
                        $this->image_lib->initialize($config);
                        $this->image_lib->resize();
                        $this->image_lib->clear();
                        unset($config);
                    }

                    unset($config);
                    //Create 250px version
                    $config = array();
                    $config['source_image'] = "./uploads/" . $this->upload->file_name;
                    $config['image_library'] = 'gd2';
                    $config['maintain_ratio'] = TRUE;
                    $config['new_image'] = './uploads/thumb_' . $this->upload->file_name;
                    $config['master_dim'] = 'width';
                    $config['width'] = 300;
                    $config['height'] = 200;
                    $config['quality']='100%';
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();
                    $this->image_lib->clear();
                    $width = $this->image_lib->width;
                    $height = $this->image_lib->height;
                    unset($config);

                    $array = array('cover_image' => $this->upload->file_name);
                    $this->db->where('id', $id);
                    if ($this->db->update('projects', $array)) {
                        ?>
                        <script>
                            //parent.location.href='<?php echo base_url() ?>busycms/productcropmainimage/<?php echo $id ?>';
                            parent.$('#cover_image').attr('src', '<?php echo base_url() ?>uploads/<?php echo $this->upload->file_name ?>')
                        </script>
                        <?php
                    }
                }
            }
        }
    }

    public function customermainimageupload($id,$gray) {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            $this->load->model('busycms_model');

            $sql = $this->db->query("select * from customers where id=" . $this->db->escape($id) . "");
            foreach ($sql->result() as $project) {

                $name = $_FILES['resim']['name'];
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '6000';
                /* $config['max_width']  = '1024';
                  $config['max_height']  = '768'; */
                $config['file_name'] = $this->busycms_model->seo_url($name);
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('resim')) {
                    ?>
                    <script>
                        alert('<?php echo $this->upload->display_errors() ?>');
                    </script>
                    <?php
                } else {
                    $this->load->library('image_lib');

                    $data = array('upload_data' => $this->upload->data());
                    $resimadi = $this->upload->file_name;


                    unset($config);
                    $config = array();
                    $config['source_image'] = "./uploads/" . $resimadi;
                    $config['image_library'] = 'gd2';
                    $config['maintain_ratio'] = TRUE;
                    $config['new_image'] = './uploads/' . $resimadi;
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();
                    $width = $this->image_lib->width;
                    $this->image_lib->clear();
                    unset($config);

                    if ($width > 1000) {
                        unset($config);
                        $config = array();
                        $config['source_image'] = "./uploads/" . $resimadi;
                        $config['image_library'] = 'gd2';
                        $config['maintain_ratio'] = TRUE;
                        $config['new_image'] = './uploads/' . $resimadi;
                        $config['master_dim'] = 'width';
                        $config['quality']='100%';
                        $config['width'] = 1000;
                        $config['height'] = 600;
                        $this->image_lib->initialize($config);
                        $this->image_lib->resize();
                        $this->image_lib->clear();
                        unset($config);
                    }

                    unset($config);
                    //Create 250px version
                    $config = array();
                    $config['source_image'] = "./uploads/" . $this->upload->file_name;
                    $config['image_library'] = 'gd2';
                    $config['maintain_ratio'] = TRUE;
                    $config['new_image'] = './uploads/thumb_' . $this->upload->file_name;
                    $config['master_dim'] = 'width';
                    $config['width'] = 300;
                    $config['height'] = 200;
                    $config['quality']='100%';
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();
                    $this->image_lib->clear();
                    $width = $this->image_lib->width;
                    $height = $this->image_lib->height;
                    unset($config);

                    if ($gray==2) {
                        $array = array('image_gray' => $this->upload->file_name);
                    } else {
                        $array = array('image' => $this->upload->file_name);
                    }                    
                    $this->db->where('id', $id);
                    if ($this->db->update('customers', $array)) {
                        ?>
                        <script>
                            //parent.location.href='<?php echo base_url() ?>busycms/productcropmainimage/<?php echo $id ?>';
                            <?php if ($gray==2) { ?>
                            parent.$('#bigimage2').attr('src', '<?php echo base_url() ?>uploads/<?php echo $this->upload->file_name ?>')
                            <?php } else {?>
                            parent.$('#bigimage').attr('src', '<?php echo base_url() ?>uploads/<?php echo $this->upload->file_name ?>')
                            <?php }?>
                        </script>
                        <?php
                    }
                }
            }
        }
    }

    public function peoplemainimageupload($id,$gray) {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            $this->load->model('busycms_model');

            $sql = $this->db->query("select * from peoples where id=" . $this->db->escape($id) . "");
            foreach ($sql->result() as $project) {

                $name = $_FILES['resim']['name'];
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '6000';
                /* $config['max_width']  = '1024';
                  $config['max_height']  = '768'; */
                $config['file_name'] = $this->busycms_model->seo_url($name);
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('resim')) {
                    ?>
                    <script>
                        alert('<?php echo $this->upload->display_errors() ?>');
                    </script>
                    <?php
                } else {
                    $this->load->library('image_lib');

                    $data = array('upload_data' => $this->upload->data());
                    $resimadi = $this->upload->file_name;


                    unset($config);
                    $config = array();
                    $config['source_image'] = "./uploads/" . $resimadi;
                    $config['image_library'] = 'gd2';
                    $config['maintain_ratio'] = TRUE;
                    $config['new_image'] = './uploads/' . $resimadi;
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();
                    $width = $this->image_lib->width;
                    $this->image_lib->clear();
                    unset($config);

                    if ($width > 1000) {
                        unset($config);
                        $config = array();
                        $config['source_image'] = "./uploads/" . $resimadi;
                        $config['image_library'] = 'gd2';
                        $config['maintain_ratio'] = TRUE;
                        $config['new_image'] = './uploads/' . $resimadi;
                        $config['master_dim'] = 'width';
                        $config['quality']='100%';
                        $config['width'] = 1000;
                        $config['height'] = 600;
                        $this->image_lib->initialize($config);
                        $this->image_lib->resize();
                        $this->image_lib->clear();
                        unset($config);
                    }

                    unset($config);
                    //Create 250px version
                    $config = array();
                    $config['source_image'] = "./uploads/" . $this->upload->file_name;
                    $config['image_library'] = 'gd2';
                    $config['maintain_ratio'] = TRUE;
                    $config['new_image'] = './uploads/thumb_' . $this->upload->file_name;
                    $config['master_dim'] = 'width';
                    $config['width'] = 300;
                    $config['height'] = 200;
                    $config['quality']='100%';
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();
                    $this->image_lib->clear();
                    $width = $this->image_lib->width;
                    $height = $this->image_lib->height;
                    unset($config);

                    
                    $array = array('image' => $this->upload->file_name);

                    $this->db->where('id', $id);
                    if ($this->db->update('peoples', $array)) {
                        ?>
                        <script>
                            //parent.location.href='<?php echo base_url() ?>busycms/productcropmainimage/<?php echo $id ?>';
                            parent.$('#bigimage').attr('src', '<?php echo base_url() ?>uploads/<?php echo $this->upload->file_name ?>')
                        </script>
                        <?php
                    }
                }
            }
        }
    }
    
    public function productcategoryimage($id) {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            $this->load->model('busycms_model');

            $sql = $this->db->query("select * from product_categories where id=" . $this->db->escape($id) . "");
            foreach ($sql->result() as $uye) {

                $name = $_FILES['resim']['name'];
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '6000';
                /* $config['max_width']  = '1024';
                  $config['max_height']  = '768'; */
                $config['file_name'] = $this->busycms_model->seo_url($name);
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('resim')) {
                    ?>
                    <script>
                        alert('<?php echo $this->upload->dispaly_errors() ?>');
                    </script>
                    <?php
                } else {
                    $this->load->library('image_lib');

                    $data = array('upload_data' => $this->upload->data());
                    $resimadi = $this->upload->file_name;


                    unset($config);
                    $config = array();
                    $config['source_image'] = "./uploads/" . $resimadi;
                    $config['image_library'] = 'gd2';
                    $config['maintain_ratio'] = TRUE;
                    $config['new_image'] = './uploads/' . $resimadi;
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();
                    $width = $this->image_lib->width;
                    $this->image_lib->clear();
                    unset($config);

                    if ($width > 1000) {
                        unset($config);
                        $config = array();
                        $config['source_image'] = "./uploads/" . $resimadi;
                        $config['image_library'] = 'gd2';
                        $config['maintain_ratio'] = TRUE;
                        $config['new_image'] = './uploads/' . $resimadi;
                        $config['master_dim'] = 'width';
                        $config['width'] = 1000;
                        $config['height'] = 600;
                        $this->image_lib->initialize($config);
                        $this->image_lib->resize();
                        $this->image_lib->clear();
                        unset($config);
                    }

                    unset($config);
                    //Create 250px version
                    $config = array();
                    $config['source_image'] = "./uploads/" . $this->upload->file_name;
                    $config['image_library'] = 'gd2';
                    $config['maintain_ratio'] = TRUE;
                    $config['new_image'] = './uploads/thumb_' . $this->upload->file_name;
                    $config['master_dim'] = 'height';
                    $config['width'] = 300;
                    $config['height'] = 300;
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();
                    $this->image_lib->clear();
                    $width = $this->image_lib->width;
                    $height = $this->image_lib->height;
                    unset($config);

                    $array = array('image' => $this->upload->file_name);
                    $this->db->where('id', $id);
                    if ($this->db->update('product_categories', $array)) {
                        ?>
                        <script>
                            parent.$('#eventimage img').attr('src','<?php echo base_url() ?>uploads/<?php echo $this->upload->file_name ?>');
//                            parent.location.href='<?php echo base_url() ?>busycms/productcategoryimagecrop/<?php echo $id ?>';
                        </script>
                        <?php
                    }
                }
            }
        }
    }

    public function videos($id) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            echo "Please Login...";
        } else {
            ?>
            <ul class="videos">
                <?php
                $sql = $this->db->query("select * from videos where pid=" . $id . " and type=2 order by id asc");
                foreach ($sql->result() as $video) {
                    ?>
                    <li id="video<?php echo $video->id ?>">
                        <?php
                        if (strpos($video->videolink, "youtube.com") <> 0) {
                            $videolink = str_replace('http://', '', $video->videolink);
                            $videolink = str_replace('www.', '', $videolink);
                            $videolink = str_replace('youtube.com/watch?v=', '', $videolink);
                            ?>
                                <!--<img src="http://i3.ytimg.com/vi/<?php echo $videolink ?>/default.jpg" width="120" />-->
                            <iframe width="200" height="200" src="http://www.youtube.com/embed/<?php echo $videolink ?>" frameborder="0" allowfullscreen></iframe>
                        <?php
                        } elseif (strpos($video->videolink, "vimeo.com") <> 0) {
                            $videolink = str_replace('http://', '', $video->videolink);
                            $videolink = str_replace('www.', '', $videolink);
                            $videolink = str_replace('vimeo.com/', '', $videolink);
                            ?>
                            <iframe src="http://player.vimeo.com/video/<?php echo $videolink ?>" width="200" height="200" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
                <?php }  ?>
                        <div class="menus"><br/>
                            <a href=""><?php echo $video->title ?></a><br/>
                            <a href="javascript:void(0);" onclick="deletevideobutton(<?php echo $video->id ?>)" class="delete">
                                <span style="font-size:12px;padding-right:0;">X</span> delete video
                            </a>
                        </div>
                    </li>
            <?php } ?>
                <li id="addartistvideo" onclick="videoadd(<?php echo $id ?>);">
                    <img src="<?php echo base_url() ?>busycms/images/add-video.jpg" width="200" height="200" />
                </li>
            </ul>
        <?php
        }
    }

    public function sounds($id) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            echo "Please Login...";
        } else {
            ?>
            <ul class="sounds">
                <?php
                $sql = $this->db->query("select * from sounds where pid=" . $id . " and type=2 order by id asc");
                foreach ($sql->result() as $sound) {
                    ?>
                    <li id="sound<?php echo $sound->id ?>">
                <?php echo $sound->soundembed ?>
                        <div class="menus"><br/>
                            <a href=""><?php echo $sound->title ?></a><br/>
                            <a href="javascript:void(0);" onclick="deletesoundbutton(<?php echo $sound->id ?>)" class="delete">
                                <span style="font-size:12px;padding-right:0;">X</span> delete sound
                            </a>
                        </div>
                    </li>
            <?php } ?>
                <li id="addartistsound" onclick="soundadd(<?php echo $id ?>);">
                    <img src="<?php echo base_url() ?>busycms/images/add-audio.jpg" />
                </li>
            </ul>
        <?php
        }
    }

    public function index() {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
           /* $year = $this->input->get('year');
            $month = $this->input->get('month');

            if (!is_numeric($year)) {
                $year = date('Y');
            }
            if (!is_numeric($month)) {
                $month = date('m');
            }

            $this->load->model('busycms_calendar');

            $data['calendar'] = $this->busycms_calendar->generate($year, $month);

            $data["page"] = "events";
            $data["year"] = $year;
            $data["month"] = $month;*/
            $data["page_title"] = "Busycms Events ";
            $data["page_desc"] = "Busycms Events ";
            $data["id"] = $id;
            $this->load->view('busycms/events/pages', $data);
        }
    }

    public function deletevideo($id) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            echo "Please login...";
        } else {

            $sql = $this->db->query("select * from videos where id=" . $this->db->escape($id) . "");
            foreach ($sql->result() as $video) {
                $this->db->where('id', $video->id);

                if ($this->db->delete('videos'))
                    
                    ?>  
                <script>
                    $('#video<?php echo $video->id ?>').fadeOut(500);
                    $('.notification .hide').click();
                </script>
                <?php
            }
        }
    }

    public function deletesound($id) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            echo "Please login...";
        } else {

            $sql = $this->db->query("select * from sounds where id=" . $this->db->escape($id) . "");
            foreach ($sql->result() as $sound) {
                $this->db->where('id', $sound->id);

                if ($this->db->delete('sounds'))
                    
                    ?>  
                <script>
                    $('#sound<?php echo $sound->id ?>').fadeOut(500);
                    $('.notification .hide').click();
                </script>
                <?php
            }
        }
    }

    public function addvideoform_do() {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            echo "plase login...";
        } else {
            $id = $this->input->post('id');
            $title = $this->input->post('title');
            $videolink = $this->input->post('videolink');
            $error = 0;

            if (strlen($title) < 2) {
                $error_text = "<br/>Please enter the video title";
                $error = 1;
            }

            if (strlen($videolink) < 2) {
                $error_text = $error_text . "<br/><br/>Please enter the video link";
                $error = 1;
            }

            if ($error == 1) {
                ?>
                <script>
                    $.notification ( 
                    {
                        title:      'Error',
                        content:    '<?php echo $error_text ?>',
                        icon:       '!',
                        color:      '#fff',
                        error : true,
                        timeout: 5000
                    }
                )
                </script>
                <?php
                exit();
            }

            $degiskenler = array(
                'title' => $title,
                'videolink' => $videolink,
                'pid' => $id,
                'type' => 2
            );

            if ($this->db->insert('videos', $degiskenler)) {
                ?>
                <script>
                    $("#overlays .modal").remove();
                    $("#overlays").removeClass();
                    $(document).unbind("keyup");
                    $.ajax({
                        url: '<?php echo base_url() ?>busycms/videos/<?php echo $id ?>',
                        success: function(data) { parent.$('#artistvideolist').html(data); }
                    });
                </script>
            <?php
            }
        }
    }

    public function addsoundform_do() {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            echo "plase login...";
        } else {
            $id = $this->input->post('id');
            $title = $this->input->post('title');
            $soundembed = $this->input->post('soundembed');
            $error = 0;

            if (strlen($title) < 2) {
                $error_text = "<br/>Please enter the video title";
                $error = 1;
            }

            if (strlen($soundembed) < 2) {
                $error_text = $error_text . "<br/><br/>Please enter the sound embed code";
                $error = 1;
            }

            if ($error == 1) {
                ?>
                <script>
                    $.notification ( 
                    {
                        title:      'Error',
                        content:    '<?php echo $error_text ?>',
                        icon:       '!',
                        color:      '#fff',
                        error : true,
                        timeout: 5000
                    }
                )
                </script>
                <?php
                exit();
            }

            $degiskenler = array(
                'title' => $title,
                'soundembed' => $soundembed,
                'pid' => $id,
                'type' => 2
            );

            if ($this->db->insert('sounds', $degiskenler)) {
                ?>
                <script>
                    $("#overlays .modal").remove();
                    $("#overlays").removeClass();
                    $(document).unbind("keyup");
                    $.ajax({
                        url: '<?php echo base_url() ?>busycms/sounds/<?php echo $id ?>',
                        success: function(data) { parent.$('#artistsoundslist').html(data); }
                    });
                </script>
            <?php
            }
        }
    }

    public function analytics() {
        error_reporting(0);
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {


            $bugun = date("Y-m-d");
            $yenitarih = strtotime('-10 day', strtotime($bugun));
            $yenitarih2 = date('Y-m-d', $yenitarih);

            $date1 = $yenitarih2;
            $date2 = date('Y-m-d');

            $this->load->library('googleanalytics');
            $ga = new GoogleAnalytics('sezginaltinoz@gmail.com', 'altinoz..2022');
            $ga->setProfile('ga:47660222');
            $ga->setDateRange($date1, $date2);

            // get the report for date and country filtered by Australia, showing pageviews and visits
            //,ga:country
            $report = $ga->getReport(
                    array('dimensions' => urlencode('ga:date'),
                        'metrics' => urlencode('ga:pageviews,ga:visits,ga:timeOnPage,ga:entrances,ga:bounces,ga:uniquePageviews,ga:exits,ga:newVisits'),
                        //'filters'=>urlencode('ga:country=@Turkey'),
                        'sort' => 'ga:date'
                    )
            );

            $data["report"] = $report;
            $data["page"] = "analytics";
            $data["page_title"] = "Busycms Google Analytics";
            $data["page_desc"] = "Busycms Google Analytics";
            $this->load->view('busycms/analytics/dashboard', $data);

            /* $report2 = $ga->getReport(
              array('dimensions'=>urlencode('ga:pagePath'),
              'metrics'=>urlencode('ga:pageviews,ga:visits,ga:timeOnPage,ga:entrances,ga:bounces,ga:uniquePageviews'),
              //'filters'=>urlencode('ga:country=@Turkey'),
              'sort'=>'-ga:pagePath'
              )
              );

              print "<pre>";
              print_r($report2);
              /*foreach($report as $r) {
              echo $report["ga:date"]."-".$r["ga:pageviews"]."-".$r["ga:visits"]."<br/>";
              }
              print "</pre>"; */
        } else {
            ?> 
            <script>
                location.href='<?php echo base_url() ?>busycms/login/';
            </script>
            <?php
            exit();
        }
    }

    public function usereditform_do() {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            $namesurname = $this->input->post('namesurname');
            $email = $this->input->post('email');
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $id = $this->input->post('id');
            $error = 0;
            $err = "";
            if (strlen($namesurname) <= 2) {
                $error = 1;
                $err = $err . "Please enter name surname<br/>";
            }
            if (strlen($email) <= 1) {
                $error = 1;
                $err = $err . "Please enter your email<br/>";
            }
            if (strlen($username) <= 2) {
                $error = 1;
                $err = $err . "Please enter username (min 5 character)<br/>";
            }
            if (strlen($password) <= 5) {
                $error = 1;
                $err = $err . "Please enter password (min 6 character)";
            }

            if ($error == 1) {
                ?> 
                <script>
                    $.notification ( 
                    {
                        title:      'Error',
                        content:    '<?php echo $err ?>',
                        icon:       '!',
                        color:      '#fff',
                        error : true,
                        timeout: 5000
                    }
                )
                </script>
                <?php
                exit();
            }

            if (is_numeric($id)) {
                $array = array("username" => $username,
                    "password" => $password,
                    "namesurname" => $namesurname,
                    "email" => $email);
                $this->db->where("id", $id);
                if ($this->db->update('admins', $array)) {
                    ?>
                    <script>
                        $.notification ( 
                        {
                            title:      'Saved',
                            content:    '<br/>Save Changes',
                            icon:       '=',
                            color:      'green',
                            //error : true,
                            timeout: 3000
                        }
                    )
                        $("#overlays .modal").remove();
                        $("#overlays").removeClass();
                        $(document).unbind("keyup");
                        $('#namesurnamelabel<?php echo $id ?>').html('<?php echo $namesurname ?>')
                    </script>
                    <?php
                }
            }
        } else {
            ?> 
            <script>
                $.notification ( 
                {
                    title:      'Error',
                    content:    'Please Login',
                    icon:       '!',
                    color:      '#fff',
                    error : true,
                    timeout: 3000
                }
            )
            </script>
            <?php
            exit();
        }
    }

    public function useraddform_do() {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            $namesurname = $this->input->post('namesurname');
            $email = $this->input->post('email');
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $error = 0;
            $err = "";
            if (strlen($namesurname) <= 2) {
                $error = 1;
                $err = $err . "Please enter name surname<br/>";
            }
            if (strlen($email) <= 1) {
                $error = 1;
                $err = $err . "Please enter your email<br/>";
            }
            if (strlen($username) <= 2) {
                $error = 1;
                $err = $err . "Please enter username (min 5 character)<br/>";
            }
            if (strlen($password) <= 5) {
                $error = 1;
                $err = $err . "Please enter password (min 6 character)";
            }

            if ($error == 1) {
                ?> 
                <script>
                    $.notification ( 
                    {
                        title:      'Error',
                        content:    '<?php echo $err ?>',
                        icon:       '!',
                        color:      '#fff',
                        error : true,
                        timeout: 5000
                    }
                )
                </script>
                <?php
                exit();
            }

            $sql = $this->db->query("select * from admins where username='" . $username . "'");
            if ($sql->num_rows() > 0) {
                ?>
                <script>
                    $.notification ( 
                    {
                        title:      'Error',
                        content:    'user name is being used',
                        icon:       '!',
                        color:      '#fff',
                        error : true,
                        timeout: 5000
                    }
                )
                </script>
                <?php
                exit();
            }

            $array = array("username" => $username,
                "password" => $password,
                "namesurname" => $namesurname,
                "email" => $email);
            if ($this->db->insert('admins', $array)) {
                ?>
                <script>
                    $.notification ( 
                    {
                        title:      'Saved',
                        content:    '<br/>Save Changes',
                        icon:       '=',
                        color:      'green',
                        //error : true,
                        timeout: 3000
                    }
                )
                    $("#overlays .modal").remove();
                    $("#overlays").removeClass();
                    $(document).unbind("keyup");
                    $('.userstabs .new').before('<li id="recordsArray_<?php echo $this->db->insert_id() ?>">\n\
                                <a href="javascript:void(0);" onclick="edituser(<?php echo $this->db->insert_id() ?>)">\n\
                                    <table>\n\
                                        <tr>\n\
                                            <td width="10"><span style="line-height:10px;font-size:30px;">d</span></td>\n\
                                            <td><label id="namesurnamelabel<?php echo $this->db->insert_id() ?>"><?php echo $namesurname ?></label></td>\n\
                                        </tr>\n\
                                    </table>\n\
                                </a>\n\
                            <div class="pagemenus">\n\
                                    <a href="javascript:void(0)" onclick="edituser(1)">.</a>\n\
                                    <a href="javascript:void(0)" onclick="$(\'#deletemenu<?php echo $this->db->insert_id() ?>\').fadeIn(1)">X</a></div>\n\
                                <div class="deletemenu" id="deletemenu<?php echo $this->db->insert_id() ?>">\n\
                                    <div>Delete User ?<br/>\n\
                                    <label onclick="deleteuser(<?php echo $this->db->insert_id() ?>)">Yes</label> or <label onclick="$(\'#deletemenu<?php echo $this->db->insert_id() ?>\').fadeOut(1)">No</label>&nbsp;&nbsp;\n\
                                    </div>\n\
                                </div>\n\
                            </li>')
                </script>
                <?php
            }
        } else {
            ?> 
            <script>
                $.notification ( 
                {
                    title:      'Error',
                    content:    'Please Login',
                    icon:       '!',
                    color:      '#fff',
                    error : true,
                    timeout: 3000
                }
            )
            </script>
            <?php
            exit();
        }
    }

    public function imageeditform() {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            $id = $this->input->post('id');
            $description = $this->input->post('description');

            $array = array('description' => $description);
            $this->db->where('id', $id);
            $this->db->update('images', $array);
            ?>
            <script>
                parent.$('#edit<?php echo $id ?>').fadeOut(200,function() {
                    parent.$('#menu<?php echo $id ?>').fadeIn(200);
                })
            </script>
            <?php
        } else {
            ?> 
            <script>
                $.notification ( 
                {
                    title:      'Error',
                    content:    'Please Login',
                    icon:       '!',
                    color:      '#fff',
                    error : true,
                    timeout: 3000
                }
            )
            </script>
            <?php
            exit();
        }
    }
    
    public function projectimageeditform() {
        $login = $this->session->userdata('busylogin');
        if ($login == 1) {
            $id = $this->input->post('id');
            $description = $this->input->post('description');
            $view = $this->input->post('view');
            
            if ($view=="on") { $view=1; } else { $view=0; }

            $array = array('description' => $description , 'view'=>$view);
            $this->db->where('id', $id);
            $this->db->update('project_images', $array);
            ?>
            <script>
                parent.$('#edit<?php echo $id ?>').fadeOut(200,function() {
                    parent.$('#menu<?php echo $id ?>').fadeIn(200);
                })
            </script>
            <?php
        } else {
            ?> 
            <script>
                $.notification ( 
                {
                    title:      'Error',
                    content:    'Please Login',
                    icon:       '!',
                    color:      '#fff',
                    error : true,
                    timeout: 3000
                }
            )
            </script>
            <?php
            exit();
        }
    }

    public function editpageform_do() {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            ?> 
            <script>
                $.notification ( 
                {
                    title:      'Error',
                    content:    'Please Login',
                    icon:       '!',
                    color:      '#fff',
                    error : true,
                    timeout: 3000
                }
            )
            </script>
            <?php
            exit();
        } else {
            $id = $this->input->post('id');
            $title = $this->input->post('title');
            $title2 = $this->input->post('title2');
            $description = $this->input->post('description');
            $saveclose = $this->input->post('saveclose');
            $content = $this->input->post('content');
            $content2 = $this->input->post('content2');
            $tags=$this->input->post('tags');
			$link=$this->input->post('link');
            if (strlen($title) <= 2) {
                ?> 
                <script>
                    $.notification ( 
                    {
                        title:      'Error',
                        content:    'Please Enter Page Title',
                        icon:       '!',
                        color:      '#fff',
                        error : true,
                        timeout: 3000
                    }
                )
                </script>
                <?php
                exit();
            }

            $array = array(
                'title' => $title, 
                'title2' => $title2, 
                'description'=>$description, 
                'content' => $content,
                'content2' => $content2,
                'tags'=>$tags,
                'link'=>$link);
            $this->db->where('id', $id);
            if ($this->db->update('pages', $array)) {
                if ($saveclose == 0) {
                    ?>
                    <script>
                        $.notification ( 
                        {
                            title:      'Saved',
                            content:    '<br/>Save Changes',
                            icon:       '=',
                            color:      'green',
                            //error : true,
                            timeout: 3000
                        }
                    )
                    </script>
                <?php } else { ?>
                    <script>
                        parent.location.href='<?php echo base_url() ?>busycms/pages/';
                    </script>
                <?php } ?>
            <?php
            }
        }
    }

    public function mainimage($id, $imageid) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            if (is_numeric($id)) {
                $sql = $this->db->query("select * from pages where id=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $page) {
                    $data["page"] = "pages";
                    $data["page_title"] = "Busycms Page Gallery";
                    $data["page_desc"] = "Busycms Page Gallery";
                    $data["id"] = $id;
                    $data["imageid"] = $imageid;
                    $data["catid"] = $page->catid;
                    $data["p"] = $page;
                    $this->load->view('busycms/pages/mainimage', $data);
                }
            }
        }
    }

    public function mainimageevent($id, $imageid) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            if (is_numeric($id)) {
                $sql = $this->db->query("select * from events where id=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $page) {
                    $data["page"] = "events";
                    $data["page_title"] = "Busycms Event Gallery";
                    $data["page_desc"] = "Busycms Event Gallery";
                    $data["id"] = $id;
                    $data["imageid"] = $imageid;
                    $data["catid"] = $page->catid;
                    $data["p"] = $page;
                    $this->load->view('busycms/events/mainimage', $data);
                }
            }
        }
    }

    public function cropimage($id, $imageid) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            if (is_numeric($id)) {
                $sql = $this->db->query("select * from pages where id=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $page) {
                    $data["page"] = "pages";
                    $data["page_title"] = "Busycms Page Gallery";
                    $data["page_desc"] = "Busycms Page Gallery";
                    $data["id"] = $id;
                    $data["imageid"] = $imageid;
                    $data["catid"] = $page->catid;
                    $data["p"] = $page;
                    $this->load->view('busycms/pages/cropimage', $data);
                }
            }
        }
    }

    public function cropimageevent($id, $imageid, $eventid) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            if (is_numeric($id)) {
                $sql = $this->db->query("select * from artists where id=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $page) {
                    $data["page"] = "events";
                    $data["page_title"] = "Busycms Event Gallery";
                    $data["page_desc"] = "Busycms Event Gallery";
                    $data["id"] = $id;
                    $data["imageid"] = $imageid;
                    $data["catid"] = $page->catid;
                    $data["eventid"] = $eventid;
                    $data["p"] = $page;
                    $this->load->view('busycms/events/cropimage', $data);
                }
            }
        }
    }

    public function pagegallery($id) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            if (is_numeric($id)) {
                $sql = $this->db->query("select * from pages where id=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $page) {
                    $data["page"] = "pages";
                    $data["page_title"] = "Busycms Page Gallery";
                    $data["page_desc"] = "Busycms Page Gallery";
                    $data["id"] = $id;
                    $data["catid"] = $page->catid;
                    $data["p"] = $page;
                    $this->load->view('busycms/pages/pagegallery', $data);
                }
            }
        }
    }

    public function eventgallery($id) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            if (is_numeric($id)) {
                $sql = $this->db->query("select * from events where id=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $page) {
                    $data["page"] = "events";
                    $data["page_title"] = "Busycms Event Gallery";
                    $data["page_desc"] = "Busycms Event Gallery";
                    $data["id"] = $id;
                    $data["catid"] = $page->catid;
                    $data["p"] = $page;
                    $this->load->view('busycms/events/eventgallery', $data);
                }
            }
        }
    }

    public function editevent($id) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            if (is_numeric($id)) {
                $sql = $this->db->query("select * from events where id=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $page) {
                    $data["page"] = "events";
                    $data["page_title"] = "Busycms Dashboard";
                    $data["page_desc"] = "Busycms Dashboard";
                    $data["id"] = $id;
                    $data["catid"] = $page->catid;
                    $data["event"] = $page;
                    $this->load->view('busycms/events/editevent', $data);
                }
            }
        }
    }

    public function editproductcategory($id) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            if (is_numeric($id)) {
                $sql = $this->db->query("select * from product_categories where id=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $page) {
                    $data["page"] = "products";
                    $data["page_title"] = "Busycms Dashboard";
                    $data["page_desc"] = "Busycms Dashboard";
                    $data["id"] = $id;
                    $data["category"] = $page;
                    $this->load->view('busycms/products/editcategory', $data);
                }
            }
        }
    }
    
    public function editproduct($id) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            if (is_numeric($id)) {
                $sql = $this->db->query("select * from products where id=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $page) {
                    $data["page"] = "products";
                    $data["page_title"] = "Busycms Dashboard";
                    $data["page_desc"] = "Busycms Dashboard";
                    $data["id"] = $id;
                    $data["product"] = $page;
                    $this->load->view('busycms/products/editproduct', $data);
                }
            }
        }
    }
    
    public function newsedit($id) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            if (is_numeric($id)) {
                $sql = $this->db->query("select * from news where id=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $project) {
                    $data["page"] = "news";
                    $data["page_title"] = "Edit News";
                    $data["page_desc"] = "Busycms Dashboard";
                    $data["id"] = $id;
                    $data["news"] = $project;
                    $this->load->view('busycms/news/editnews', $data);
                }
            }
        }
    }
    
    public function projectedit($id) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            if (is_numeric($id)) {
                $sql = $this->db->query("select * from projects where id=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $project) {
                    $data["page"] = "projects";
                    $data["page_title"] = "Edit Project";
                    $data["page_desc"] = "Busycms Dashboard";
                    $data["id"] = $id;
                    $data["project"] = $project;
                    $this->load->view('busycms/projects/editproject', $data);
                }
            }
        }
    }

    public function customeredit($id) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            if (is_numeric($id)) {
                $sql = $this->db->query("select * from customers where id=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $customer) {
                    $data["page"] = "customers";
                    $data["page_title"] = "Edit Customer";
                    $data["page_desc"] = "Busycms Dashboard";
                    $data["id"] = $id;
                    $data["customer"] = $customer;
                    $this->load->view('busycms/customers/editcustomer', $data);
                }
            }
        }
    }

    public function peopleedit($id) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            if (is_numeric($id)) {
                $sql = $this->db->query("select * from peoples where id=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $customer) {
                    $data["page"] = "people";
                    $data["page_title"] = "Edit People";
                    $data["page_desc"] = "Busycms Dashboard";
                    $data["id"] = $id;
                    $data["people"] = $customer;
                    $this->load->view('busycms/peoples/editpeople', $data);
                }
            }
        }
    }

    public function editproductcategory_do() {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            ?> <script>alert('Please login')</script>
        <?php
        } else {
            $title = $this->input->post('title');
            $content = $this->input->post('content');
            $id = $this->input->post('id');
            $hata = 0;

            if (strlen($title) < 2) {
                $error = $error . "<br/>Please enter the category name";
                $hata = 1;
            }

            if ($hata == 1) {
                ?>
                <script>
                    $.notification ( 
                    {
                        title:      'Error',
                        content:    '<?php echo $error ?>',
                        icon:       '!',
                        color:      '#fff',
                        error : true,
                        timeout: 3000
                    }
                )
                </script>
                <?php
                exit();
            }

            $data = array(
                'title' => $title,
                'description' => $content
            );
            $this->db->where("id", $id);
            if ($this->db->update('product_categories', $data)) {
                ?>
                <script>                      
                    parent.location.href='<?php echo base_url() ?>busycms/products';
                </script>
                <?php
            }
        }
    }
    
    public function editnewsform_do() {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            ?> <script>alert('Please login')</script>
        <?php
        } else {
            $id = $this->input->post('id');
            $publish = $this->input->post('publish');            
            $title = $this->input->post('title');
            $saveclose = $this->input->post('saveclose');            
            $content = $this->input->post('content');                        
            $date_view = $this->input->post('date_view');
            $left_image = $this->input->post('left_image');
                        
            $title2=$this->input->post('title2');
            $date_view2=$this->input->post('date_view2');
            $content2=$this->input->post('content2');

            $link=$this->input->post('link');
            $year=$this->input->post('year');
            $month=$this->input->post('month');

            $subtitle=$this->input->post('subtitle');
            
            $hata = 0;
            $error="";
            
            if ($publish=="on") { $publish=1; } else { $publish=0; }
            if ($left_image=="on") { $left_image=1; } else { $left_image=0; }
            
            if (strlen($title) <= 2) {
                $error = $error . "<br/>Please enter the news title";
                $hata = 1;
            }

            if ($hata == 1) {
                ?>
                <script>
                    $.notification ( 
                    {
                        title:      'Error',
                        content:    '<?php echo $error ?>',
                        icon:       '!',
                        color:      '#fff',
                        error : true,
                        timeout: 3000
                    }
                )
                </script>
                <?php
                exit();
            }

            $data = array(
                'title' => $title,                
                'content'=>$content,
                'date_view' =>$date_view,
                'publish'=>$publish,
                'left_image'=>$left_image,
                'title2'=>$title2,
                'content2'=>$content2,
                'date_view2'=>$date_view2,
                'link'=>$link,
                'year'=>$year,
                'month'=>$month,
                'subtitle'=>$subtitle
            );
            $this->db->where("id", $id);
            if ($this->db->update('news', $data)) {
                ?>
                <script>     
                    <?php if ($saveclose==1) { ?>
                    parent.location.href='<?php echo base_url() ?>busycms/news/';
                    <?php } else { ?>
                    parent.location.href='<?php echo base_url() ?>busycms/newsedit/<?php echo $id ?>';
                    <?php }?>
                </script>
                <?php
            }
        }
    }
    
    public function editprojectform_do() {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            ?> <script>alert('Please login')</script>
        <?php
        } else {
            $id = $this->input->post('id');
            $publish = $this->input->post('publish');
            $categories = $this->input->post('categories');
            $title = $this->input->post('title');
            $saveclose = $this->input->post('saveclose');            
            $year = $this->input->post('year');
            $content = $this->input->post('content');            
            $title2=$this->input->post('title2');            
            $content2=$this->input->post('content2');
            $customer=$this->input->post('customer');
            $subbrand=$this->input->post('subbrand');

            $videos=$this->input->post('videos');
            $sounds=$this->input->post('sounds');
            
            $kategoriler="";
            for($i=0;$i<=count($categories)-1;$i++) {
                $kategoriler=$kategoriler.",".$categories[$i].",";
            }            
            
            
            $hata = 0;
            $error="";
            
            if ($publish=="on") { $publish=1; } else { $publish=0; }
            
            if (strlen($title) <= 2) {
                $error = $error . "<br/>Please enter the project name";
                $hata = 1;
            }

            if ($hata == 1) {
                ?>
                <script>
                    $.notification ( 
                    {
                        title:      'Error',
                        content:    '<?php echo $error ?>',
                        icon:       '!',
                        color:      '#fff',
                        error : true,
                        timeout: 3000
                    }
                )
                </script>
                <?php
                exit();
            }

            $data = array(
                'categories'=>$kategoriler,
                'customer'=>$customer,
                'subbrand'=>$subbrand,
                'title' => $title,                
                'project_year'=>$year,
                'content'=>$content,
                'publish'=>$publish,
                'title2'=>$title2,
                'content2'=>$content2
            );

            $this->db->where('project_id',$id);
            $this->db->delete('projectvideos');

            foreach($videos as $video) {
                if (strlen($video)>0) {
                    $this->db->insert('projectvideos',array('project_id'=>$id,'video_url'=>$video)); 
                }
            }

            $this->db->where('project_id',$id);
            $this->db->delete('projectsounds');

            foreach($sounds as $sound) {
                if (strlen($sound)>0) {
                    $this->db->insert('projectsounds',array('project_id'=>$id,'sound_url'=>$sound)); 
                }
            }

            $this->db->where("id", $id);
            if ($this->db->update('projects', $data)) {
                ?>
                <script>     
                    <?php if ($saveclose==1) { ?>
                    parent.location.href='<?php echo base_url() ?>busycms/projects/';
                    <?php } else { ?>
                    parent.location.href='<?php echo base_url() ?>busycms/projectedit/<?php echo $id ?>';
                    <?php }?>
                </script>
                <?php
            }
        }
    }

    public function editcustomerform_do() {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            ?> <script>alert('Please login')</script>
        <?php
        } else {
            $id = $this->input->post('id');
            $publish = $this->input->post('publish');
            $categories = $this->input->post('categories');
            $title = $this->input->post('title');
            $saveclose = $this->input->post('saveclose');            
            $content = $this->input->post('content');
            $title2=$this->input->post('title2');
            $content2=$this->input->post('content2');
            
            $kategoriler="";
            for($i=0;$i<=count($categories)-1;$i++) {
                $kategoriler=$kategoriler.",".$categories[$i].",";
            }            
            
            
            $hata = 0;
            $error="";
            
            if ($publish=="on") { $publish=1; } else { $publish=0; }
            
            if (strlen($title) <= 2) {
                $error = $error . "<br/>Please enter the customer name";
                $hata = 1;
            }

            if ($hata == 1) {
                ?>
                <script>
                    $.notification ( 
                    {
                        title:      'Error',
                        content:    '<?php echo $error ?>',
                        icon:       '!',
                        color:      '#fff',
                        error : true,
                        timeout: 3000
                    }
                )
                </script>
                <?php
                exit();
            }

            $data = array(
                // 'categories'=>$kategoriler,
                'title' => $title,
                'content'=>$content,
                'publish'=>$publish,
                'title2'=>$title2,                
                'content2'=>$content2
            );
            $this->db->where("id", $id);
            if ($this->db->update('customers', $data)) {
                ?>
                <script>     
                    <?php if ($saveclose==1) { ?>
                    parent.location.href='<?php echo base_url() ?>busycms/customers/';
                    <?php } else { ?>
                    parent.location.href='<?php echo base_url() ?>busycms/customeredit/<?php echo $id ?>';
                    <?php }?>
                </script>
                <?php
            }
        }
    }

    public function editpeopleform_do() {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            ?> <script>alert('Please login')</script>
        <?php
        } else {
            $id = $this->input->post('id');
            $publish = $this->input->post('publish');
            $title = $this->input->post('title');
            $saveclose = $this->input->post('saveclose');            
            $instagram=$this->input->post('instagram');
            
            
            $hata = 0;
            $error="";
            
            if ($publish=="on") { $publish=1; } else { $publish=0; }
            
            if (strlen($title) <= 2) {
                $error = $error . "<br/>Please enter the people name";
                $hata = 1;
            }

            if ($hata == 1) {
                ?>
                <script>
                    $.notification ( 
                    {
                        title:      'Error',
                        content:    '<?php echo $error ?>',
                        icon:       '!',
                        color:      '#fff',
                        error : true,
                        timeout: 3000
                    }
                )
                </script>
                <?php
                exit();
            }

            $data = array(
                // 'categories'=>$kategoriler,
                'title' => $title,
                'instagram'=>$instagram,
                'publish'=>$publish
            );
            $this->db->where("id", $id);
            if ($this->db->update('peoples', $data)) {
                ?>
                <script>     
                    <?php if ($saveclose==1) { ?>
                    parent.location.href='<?php echo base_url() ?>busycms/people/';
                    <?php } else { ?>
                    parent.location.href='<?php echo base_url() ?>busycms/peopleedit/<?php echo $id ?>';
                    <?php }?>
                </script>
                <?php
            }
        }
    }
    
    public function editproductform_do() {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            ?> <script>alert('Please login')</script>
        <?php
        } else {
            $id = $this->input->post('id');
            $catid=$this->input->post('catid');
            $title = $this->input->post('title');
            $related=implode(',',$this->input->post('related'));
            $price=$this->input->post('price');
            $stock=$this->input->post('stock');
            $percentage=$this->input->post('percentage');
            $volume=$this->input->post('volume');
            $description=$this->input->post('description');
            $detail_description=$this->input->post('detail_description');
            $usage=$this->input->post('usage');
            $content = $this->input->post('content');  
            $saveclose = $this->input->post('saveclose');
            $color=$this->input->post('color');
            $material=$this->input->post('material');
            $size=$this->input->post('size');
            $weight=$this->input->post('weight');
            $designer=$this->input->post('designer');
            $brand=$this->input->post('brand');
            $product_code=$this->input->post('product_code');
            $tags=$this->input->post('tags');
            $size_name=$this->input->post('size_name');
            $view_list=$this->input->post('view_list');
            $kargo=$this->input->post('kargo');
            $delivery_date=$this->input->post('delivery_date');
            $categories=$this->input->post('categories');
            
            $kategoriler="";
            for($i=0;$i<=count($categories)-1;$i++) {
                $kategoriler=$kategoriler.",".$categories[$i].",";
            }            
            
            $hata = 0;
            $error="";
            if ($catid==0) {
                $error = $error . "<br/>Please select the product category";
                $hata = 1;
            }
            
            if ($view_list=="on") { $view_list=1; } else { $view_list=0; }
            
            if (strlen($title) <= 2) {
                $error = $error . "<br/>Please enter the product name";
                $hata = 1;
            }

            if ($hata == 1) {
                ?>
                <script>
                    $.notification ( 
                    {
                        title:      'Error',
                        content:    '<?php echo $error ?>',
                        icon:       '!',
                        color:      '#fff',
                        error : true,
                        timeout: 3000
                    }
                )
                </script>
                <?php
                exit();
            }
            
            $sql=$this->db->query("select * from product_categories where id=".$catid."");
            foreach($sql->result() as $c) {
                $maincat=$c->catid;
            }

            $data = array(
                'title' => $title,
                'maincat'=>$maincat,
                'catid'=>$catid,
                'related_products'=>$related,
                'price'=>$price,
                'percentage'=>$percentage,
                'volume'=>$volume,
                'stock'=>$stock,
                'description' => $description,
                'detail_description' => $detail_description,
                'usage_content'=>$usage,
                'content'=>$content,
                'color'=>$color,
                'material'=>$material,
                'size'=>$size,
                'weight'=>$weight,
                'designer'=>$designer,
                'brand'=>$brand,
                'product_code'=>$product_code,
                'tags'=>$tags,
                'size_name'=>$size_name,
                'view_list'=>$view_list,
                'kargo_bedeli'=>$kargo,
                'teslimat_suresi'=>$delivery_date,
                'categories'=>$kategoriler
            );
            $this->db->where("id", $id);
            if ($this->db->update('products', $data)) {
                ?>
                <script>     
                    <?php if ($saveclose==1) { ?>
                    parent.location.href='<?php echo base_url() ?>busycms/products/?catid=<?php echo $catid ?>';
                    <?php } else { ?>
                    parent.location.href='<?php echo base_url() ?>busycms/editproduct/<?php echo $id ?>';
                    <?php }?>
                </script>
                <?php
            }
        }
    }

    public function editevent_do() {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            ?> <script>alert('Please login')</script>
        <?php
        } else {
            $date = $this->input->post('date');
            $clock = $this->input->post('clock');
            $minute = $this->input->post('minute');
            $price = $this->input->post('price');
            $studentprice = $this->input->post('studentprice');
            $title = $this->input->post('title');
            $link = $this->input->post('link');
            $website = $this->input->post('website');
            $sponsor = $this->input->post('sponsor');
            $description = $this->input->post('description');
            $content = $this->input->post('content');
            $savepublish = $this->input->post('savepublish');
            $venue = $this->input->post('venue');
            $presale = $this->input->post('presale');
            $saveclose = $this->input->post('saveclose');
            $id = $this->input->post('id');
            $hata = 0;
            if (strlen($date) <= 2) {
                $error = "<br/>Please enter the date";
                $hata = 1;
            }
            if (strlen($title) <= 2) {
                $error = $error . "<br/>Please enter the title";
                $hata = 1;
            }

            if ($hata == 1) {
                ?>
                <script>
                    $.notification ( 
                    {
                        title:      'Error',
                        content:    '<?php echo $error ?>',
                        icon:       '!',
                        color:      '#fff',
                        error : true,
                        timeout: 3000
                    }
                )
                </script>
                <?php
                exit();
            }

            $data = array(
                'eventdate' => $date,
                'title' => $title,
                'price' => $price,
                'studentprice' => $studentprice,
                'link' => $link,
                'website' => $website,
                'sponsor' => $sponsor,
                'description' => $description,
                'content' => $content,
                'eventclock' => $clock,
                'eventminute' => $minute,
                'venue' => $venue,
                'presale' => $presale,
                'publish' => $savepublish
            );
            $this->db->where("id", $id);
            if ($this->db->update('events', $data)) {
                ?>
                <script>                      
                    /* $.notification ( 
                        {
                            title:      'Saved',
                            content:    '<br/>Save Changes',
                            icon:       '=',
                            color:      'green',
                            //error : true,
                            timeout: 3000
                        }
                    )*/
                <?php if ($saveclose == 1) { ?>
                            parent.location.href='<?php echo base_url() ?>busycms/events/?month=<?php echo strftime("%m", strtotime($date)) ?>&year=<?php echo strftime("%Y", strtotime($date)) ?>';
                <?php } else { ?>
                            parent.location.href='<?php echo base_url() ?>busycms/editevent/<?php echo $id ?>';
                <?php } ?>
                </script>
                <?php
            }
        }
    }

    public function addnewevent_do() {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            ?> <script>alert('Please login')</script>
        <?php
        } else {
            $date = $this->input->post('date');
            $clock = $this->input->post('clock');
            $minute = $this->input->post('minute');
            $title = $this->input->post('title');
            $hata = 0;
            if (strlen($date) <= 2) {
                $error = "Please enter the date";
                $hata = 1;
            }
            if (strlen($title) <= 2) {
                $error = $error . "<br/>Please enter the title";
                $hata = 1;
            }

            if ($hata == 1) {
                ?>
                <script>
                    $.notification ( 
                    {
                        title:      'Error',
                        content:    '<?php echo $error ?>',
                        icon:       '!',
                        color:      '#fff',
                        error : true,
                        timeout: 3000
                    }
                )
                </script>
                <?php
                exit();
            }

            $data = array(
                'eventdate' => $date,
                'title' => $title,
                'eventclock' => $clock,
                'eventminute' => $minute,
                'publish' => 0
            );

            if ($this->db->insert('events', $data)) {
                ?>
                <script>
                    parent.location.href='<?php echo base_url() ?>busycms/editevent/<?php echo $this->db->insert_id() ?>';
                </script>
            <?php
            }
        }
    }

    public function addnewevent() {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            $data["page"] = "events";
            $data["page_title"] = "Busycms Events ";
            $data["page_desc"] = "Busycms Events ";
            $data["id"] = $id;
            $this->load->view('busycms/events/addnewevent', $data);
        }
    }

    public function events() {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {

            $year = $this->input->get('year');
            $month = $this->input->get('month');

            if (!is_numeric($year)) {
                $year = date('Y');
            }
            if (!is_numeric($month)) {
                $month = date('m');
            }

            $this->load->model('busycms_calendar');

            $data['calendar'] = $this->busycms_calendar->generate($year, $month);

            $data["page"] = "events";
            $data["year"] = $year;
            $data["month"] = $month;
            $data["page_title"] = "Busycms Events ";
            $data["page_desc"] = "Busycms Events ";
            $data["id"] = $id;
            $this->load->view('busycms/events/dashboard', $data);
        }
    }

    public function products() {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            $catid=$this->input->get('catid');
            $sort=$this->input->get('sort');
            if (!is_numeric($catid)) {
                $sql=$this->db->query("select * from product_categories where catid>0 order by reorder asc,catid desc limit 0,1");
                foreach($sql->result() as $c) { $catid=$c->id; }
            }
                        
            $data["page"] = "products";
            $data["page_title"] = "Busycms Products ";
            $data["page_desc"] = "Busycms Products ";
            $data["catid"]=$catid;
            $data["sort"]=$sort;
            $this->load->view('busycms/products/dashboard', $data);
        }
    }

    public function editpage($id) {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            if (is_numeric($id)) {
                $sql = $this->db->query("select * from pages where id=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $page) {
                    $data["page"] = "pages";
                    $data["page_title"] = "Busycms Dashboard";
                    $data["page_desc"] = "Busycms Dashboard";
                    $data["id"] = $id;
                    $data["catid"] = $page->catid;
                    $data["p"] = $page;
                    $this->load->view('busycms/pages/editpage', $data);
                }
            }
        }
    }

    public function userdelete($id) {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            $this->db->delete('admins');
            ?>

            <script>
                $('.pagecol<?php echo $id ?>').fadeOut(1);
                $('#recordsArray_<?php echo $id ?>').slideUp(250);
            </script>

        <?php
        }
    }

    public function pagedelete($id) {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            $this->db->delete('pages');

            $this->db->where('catid', $id);
            $this->db->delete('pages');
            ?>

            <script>
                $('.pagecol<?php echo $id ?>').fadeOut(1);
                $('#recordsArray_<?php echo $id ?>').slideUp(250);
                $('.notification .hide').click();
            </script>

        <?php
        }
    }
    
    public function newsdelete($id) {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            $this->db->delete('news');
            ?>

            <script>
                $('#news_<?php echo $id ?>').fadeOut(1);                
                $('.notification .hide').click();
            </script>

        <?php
        }
    }
    
    public function projectdelete($id) {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            $this->db->delete('projects');
            ?>

            <script>
                $('.notification .hide').click();
                $('#project_<?php echo $id ?>').fadeOut(1);                
            </script>

        <?php
        }
    }

    public function customerdelete($id) {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            $this->db->delete('customers');
            ?>

            <script>
                $('.notification .hide').click();
                $('#customer_<?php echo $id ?>').fadeOut(1);                
            </script>

        <?php
        }
    }

    public function peopledelete($id) {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            $this->db->delete('peoples');
            ?>

            <script>
                $('.notification .hide').click();
                $('#people_<?php echo $id ?>').fadeOut(1);                
            </script>

        <?php
        }
    }
    
    public function deleteproduct($id) {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            $this->db->delete('products');
            ?>
            <script>
                $('.notification .hide').click();
                $('#product<?php echo $id ?>').slideUp(500);
            </script>
            <?php
        }
    }
    
    public function deleteproductcategory($id) {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            $this->db->delete('product_categories');

            $this->db->where('catid', $id);
            $this->db->delete('product_categories');
            ?>
            <script>
                $('.notification .hide').click();
                $('#productcategory<?php echo $id ?>').slideUp(500);
            </script>
            <?php
        }
    }
    
    public function deleteproductsubcategory($id) {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            $this->db->delete('product_categories');

            $this->db->where('catid', $id);
            $this->db->delete('products');
            ?>
            <script>
                $('.notification .hide').click();
                $('#subcategory<?php echo $id ?>').slideUp(500);
            </script>
            <?php
        }
    }

    public function eventdelete($id) {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            $this->db->delete('events');
            ?>

            <script>
                $('.pagecol<?php echo $id ?>').fadeOut(1);
                $('#recordsArray_<?php echo $id ?>').slideUp(250);
                $('.notification .hide').click();
            </script>

        <?php
        }
    }

    public function categoryorderupdate() {
        $action = $this->input->post('action');
        $updateRecordsArray = $this->input->post('recordsArray');
        $katid = $this->input->post('katid');

        if ($action == "updateRecordsListings") {

            $listingCounter = 1;
            foreach ($updateRecordsArray as $recordIDValue) {

                $query = "UPDATE pages SET reorder = " . $listingCounter . " WHERE id = " . $recordIDValue . " and catid=" . $katid;
                mysql_query($query) or die('Error, insert query failed');
                $listingCounter = $listingCounter + 1;
            }
        }
    }
    
    public function newsimageorder() {
        $action = $this->input->post('action');
        $updateRecordsArray = $this->input->post('image');
        $id=$this->input->post('id');

        if ($action == "updateRecordsListings") {
            $listingCounter = 1;
            foreach ($updateRecordsArray as $recordIDValue) {
                $query = "UPDATE news_images SET reorder = " . $listingCounter . " WHERE news_id=".$id." and id = " . $recordIDValue;
                mysql_query($query) or die('Error, insert query failed');
                $listingCounter = $listingCounter + 1;
            }
        }
    }
    
    public function projectimageorder() {
        $action = $this->input->post('action');
        $updateRecordsArray = $this->input->post('image');
        $id=$this->input->post('id');

        if ($action == "updateRecordsListings") {
            $listingCounter = 1;
            foreach ($updateRecordsArray as $recordIDValue) {
                $query = "UPDATE project_images SET reorder = " . $listingCounter . " WHERE project_id=".$id." and id = " . $recordIDValue;
                mysql_query($query) or die('Error, insert query failed');
                $listingCounter = $listingCounter + 1;
            }
        }
    }
    
    public function projectorder() {
        $action = $this->input->post('action');
        $updateRecordsArray = $this->input->post('project');

        $sql=$this->db->query("select * from projects");

        if ($action == "updateRecordsListings") {
            $listingCounter = 1;
            foreach ($updateRecordsArray as $recordIDValue) {
                $query = "UPDATE projects SET reorder = " . $listingCounter . " WHERE id = " . $recordIDValue;
                mysql_query($query) or die('Error, insert query failed');
                $listingCounter = $listingCounter - 1;
            }
        }
    }

    public function customerorder() {
        $action = $this->input->post('action');
        $updateRecordsArray = $this->input->post('customer');

        $sql=$this->db->query("select * from customers");

        if ($action == "updateRecordsListings") {
            $listingCounter = 1;
            foreach ($updateRecordsArray as $recordIDValue) {
                $query = "UPDATE customers SET reorder = " . $listingCounter . " WHERE id = " . $recordIDValue;
                mysql_query($query) or die('Error, insert query failed');
                $listingCounter = $listingCounter - 1;
            }
        }
    }

    public function peopleorder() {
        $action = $this->input->post('action');
        $updateRecordsArray = $this->input->post('people');

        if ($action == "updateRecordsListings") {
            $listingCounter = 1;
            foreach ($updateRecordsArray as $recordIDValue) {
                $query = "UPDATE peoples SET reorder = " . $listingCounter . " WHERE id = " . $recordIDValue;
                mysql_query($query) or die('Error, insert query failed');
                $listingCounter = $listingCounter + 1;
            }
        }
    }
    
    public function newsorder() {
        $action = $this->input->post('action');
        $updateRecordsArray = $this->input->post('news');                
        
        if ($action == "updateRecordsListings") {
            $listingCounter = 1;
            foreach ($updateRecordsArray as $recordIDValue) {
                $query = "UPDATE news SET reorder = " . $listingCounter . " WHERE id = " . $recordIDValue;
                mysql_query($query) or die('Error, insert query failed');
                $listingCounter = $listingCounter + 1;
            }
        }
    }

    public function pageeditform_do() {
        $title = $this->input->post('title');
        $catid = $this->input->post('catid');
        if (strlen($title) <= 2) {
            ?> 
            <script>
                $.notification ( 
                {
                    title:      'Error',
                    content:    'Please Enter Category Name',
                    icon:       '!',
                    color:      '#fff',
                    error : true,
                    timeout: 3000
                }
            )
            </script>
            <?php
            exit();
        } else {
            $array = array('title' => $title);
            $this->db->where('id', $catid);
            if ($this->db->update('pages', $array)) {
                ?>
                <script>
                    $('#recordsArray_<?php echo $catid ?> label').html("<?php echo $title ?>")
                    $("#overlays .modal").remove();
                    $("#overlays").removeClass();
                    $(document).unbind("keyup");
                </script>
                <?php
            }
        }
    }

    public function editcategory($catid) {
        if (is_numeric($catid)) {
            $sql = $this->db->query("select * from pages where id=" . $this->db->escape($catid) . "");
            foreach ($sql->result() as $page) {
                ?>
                <script>
                    $('#pageaddform input:first').focus();
                </script>
                <form id="pageeditform" onsubmit="return false">
                    <h2>Category Name</h2><br/>
                    <input type="text" name="title" value="<?php echo $page->title ?>" />
                    <input type="hidden" name="catid" value="<?php echo $catid ?>" />
                    <br/>
                    <div align="right">
                        <span id="pageeditform_back"></span><button onclick="submitform('busycms/','pageeditform')">Edit Category</button>
                    </div>
                </form>
            <?php
            }
        }
    }
    
    public function newsaddform_do() {
        $title = $this->input->post('title');        
        if (strlen($title) <= 2) {
            ?> 
            <script>
                $.notification ( 
                {
                    title:      'Error',
                    content:    'Please Enter News Title',
                    icon:       '!',
                    color:      '#fff',
                    error : true,
                    timeout: 3000
                }
            )
            </script>
            <?php
            exit();
        }
        $reorder = 0;
        $sql = $this->db->query("select max(reorder) as enbuyuk from news");
        foreach ($sql->result() as $son) {
            $reorder = $son->enbuyuk + 1;
        }
        if (!$reorder > 0) {
            $reorder = 1;
        }

        $data = array(
            'title' => $title,
            'reorder' => $reorder,
            'publish' => 0
        );

        $this->db->insert('news', $data);
        $id=$this->db->insert_id();
        ?>
        <script>
            parent.location.href='<?php echo base_url() ?>busycms/newsedit/<?php echo $id ?>';
        </script>
        <?php
    }
    
    public function projectaddform_do() {
        $title = $this->input->post('title');        
        if (strlen($title) <= 2) {
            ?> 
            <script>
                $.notification ( 
                {
                    title:      'Error',
                    content:    'Please Enter Project Name',
                    icon:       '!',
                    color:      '#fff',
                    error : true,
                    timeout: 3000
                }
            )
            </script>
            <?php
            exit();
        }
        $reorder = 0;
        $sql = $this->db->query("select max(reorder) as enbuyuk from projects");
        foreach ($sql->result() as $son) {
            $reorder = $son->enbuyuk + 1;
        }
        if (!$reorder > 0) {
            $reorder = 1;
        }

        $data = array(
            'title' => $title,
            'reorder' => $reorder,
            'publish' => 0
        );

        $this->db->insert('projects', $data);
        $id=$this->db->insert_id();
        ?>
        <script>
            parent.location.href='<?php echo base_url() ?>busycms/projectedit/<?php echo $id ?>';
        </script>
        <?php
    }

    public function customeraddform_do() {
        $title = $this->input->post('title');        
        if (strlen($title) <= 2) {
            ?> 
            <script>
                $.notification ( 
                {
                    title:      'Error',
                    content:    'Please Enter Project Name',
                    icon:       '!',
                    color:      '#fff',
                    error : true,
                    timeout: 3000
                }
            )
            </script>
            <?php
            exit();
        }
        $reorder = 0;
        $sql = $this->db->query("select max(reorder) as enbuyuk from customers");
        foreach ($sql->result() as $son) {
            $reorder = $son->enbuyuk + 1;
        }
        if (!$reorder > 0) {
            $reorder = 1;
        }

        $data = array(
            'title' => $title,
            'reorder' => $reorder,
            'publish' => 0
        );

        $this->db->insert('customers', $data);
        $id=$this->db->insert_id();
        ?>
        <script>
            parent.location.href='<?php echo base_url() ?>busycms/customeredit/<?php echo $id ?>';
        </script>
        <?php
    }

    public function peopleaddform_do() {
        $title = $this->input->post('title');        
        if (strlen($title) <= 2) {
            ?> 
            <script>
                $.notification ( 
                {
                    title:      'Error',
                    content:    'Please Enter People Name',
                    icon:       '!',
                    color:      '#fff',
                    error : true,
                    timeout: 3000
                }
            )
            </script>
            <?php
            exit();
        }
        $reorder = 0;
        $sql = $this->db->query("select max(reorder) as enbuyuk from peoples");
        foreach ($sql->result() as $son) {
            $reorder = $son->enbuyuk + 1;
        }
        if (!$reorder > 0) {
            $reorder = 1;
        }

        $data = array(
            'title' => $title,
            'reorder' => $reorder,
            'publish' => 0
        );

        $this->db->insert('peoples', $data);
        $id=$this->db->insert_id();
        ?>
        <script>
            parent.location.href='<?php echo base_url() ?>busycms/peopleedit/<?php echo $id ?>';
        </script>
        <?php
    }

    public function pageaddform_do() {
        $title = $this->input->post('title');
        $catid = $this->input->post('catid');
        $durum = $this->input->post('durum');
        if (strlen($title) <= 2) {
            ?> 
            <script>
                $.notification ( 
                {
                    title:      'Error',
                    content:    'Please Enter Category Name',
                    icon:       '!',
                    color:      '#fff',
                    error : true,
                    timeout: 3000
                }
            )
            </script>
            <?php
            exit();
        }
        $reorder = 0;
        $sql = $this->db->query("select max(reorder) as enbuyuk from pages where catid=" . $this->db->escape($catid) . "");
        foreach ($sql->result() as $son) {
            $reorder = $son->enbuyuk + 1;
        }
        if (!$reorder > 0) {
            $reorder = 1;
        }

        $data = array(
            'title' => $title,
            'catid' => $catid,
            'reorder' => $reorder,
            'tip' => $durum,
            'publish' => 0
        );

        $this->db->insert('pages', $data);
        ?>
        <script>
        <?php if ($durum == 1) { ?>
                $('.pagecol<?php echo $catid ?> .new').before('<li class="page" id="recordsArray_<?php echo $this->db->insert_id() ?>">\n\
                <a href="<?php echo base_url() ?>busycms/editpage/<?php echo $this->db->insert_id() ?>" ><span>C</span> <label><?php echo $title ?></label></a>\n\
                <div class="pagemenus">\n\
                <a href="<?php echo base_url() ?>busycms/editpage/<?php echo $this->db->insert_id() ?>">8</a>\n\
                <a href="javascript:void(0);" onclick="deletebutton(<?php echo $this->db->insert_id() ?>)">X</a></div>\n\
                </li>');
        <?php } else { ?>
                $('.pagecol<?php echo $catid ?> .new').before('<li class="category" id="recordsArray_<?php echo $this->db->insert_id() ?>">\n\
            <a href="<?php echo base_url() ?>busycms/pages/<?php echo $this->db->insert_id() ?>" >\n\
                <span>g</span> <label><?php echo $title ?></label>\n\
            </a>\n\
            <div class="pagemenus">\n\
            <a href="javascript:void(0)" onclick="editcategory(<?php echo $this->db->insert_id() ?>)">8</a>\n\
            <a href="javascript:void(0)" onclick="deletebutton(<?php echo $this->db->insert_id() ?>)">X</a></div>\n\
            </li>');
        <?php } ?>
            $("#overlays .modal").remove();
            $("#overlays").removeClass();
            $(document).unbind("keyup");
            $('.pagecol<?php echo $catid ?> #more').fadeOut(1);
        </script>
        <?php
    }
    
    public function newsadd() {        
            ?>
            <script>
                $('#pageaddform input:first').focus();
            </script>
            <form id="newsaddform" onsubmit="return false">
                <h2>News Title</h2><br/>
                <input type="text" name="title" />
                <br/>
                <div align="right">
                    <span id="newsaddform_back"></span><button onclick="submitform('busycms/','newsaddform')">Add News</button>
                </div>
            </form>
            <?php
    }
    
    public function projectadd() {        
            ?>
            <script>
                $('#pageaddform input:first').focus();
            </script>
            <form id="projectaddform" onsubmit="return false">
                <h2>Project Name</h2><br/>
                <input type="text" name="title" />
                <br/>
                <div align="right">
                    <span id="projectaddform_back"></span><button onclick="submitform('busycms/','projectaddform')">Add New Project</button>
                </div>
            </form>
            <?php
    }

    public function customeradd() {        
            ?>
            <script>
                $('#pageaddform input:first').focus();
            </script>
            <form id="customeraddform" name="customeraddform" onsubmit="return false">
                <h2>Customer Name</h2><br/>
                <input type="text" name="title" />
                <br/>
                <div align="right">
                    <span id="customeraddform_back"></span>
                    <button onclick="submitform('busycms/','customeraddform')">Add New Customer</button>
                </div>
            </form>
            <?php
    }

    public function peopleadd() {        
            ?>
            <script>
                $('#peopleaddform input:first').focus();
            </script>
            <form id="peopleaddform" name="peopleaddform" onsubmit="return false">
                <h2>People Name</h2><br/>
                <input type="text" name="title" />
                <br/>
                <div align="right">
                    <span id="peopleaddform_back"></span>
                    <button onclick="submitform('busycms/','peopleaddform')">Add New People</button>
                </div>
            </form>
            <?php
    }

    public function pageadd($page="", $catid) {
        if ($page == 0) {
            ?>
            <script>
                $('#pageaddform input:first').focus();
            </script>
            <form id="pageaddform" onsubmit="return false">
                <h2>Category Name</h2><br/>
                <input type="text" name="title" />
                <input type="hidden" name="catid" value="<?php echo $catid ?>" />
                <input type="hidden" name="durum" value="0" />
                <br/>
                <div align="right">
                    <span id="pageaddform_back"></span><button onclick="submitform('busycms/','pageaddform')">Add New Category</button>
                </div>
            </form>
        <?php } else { ?>
            <script>
                $('#pageaddform input:first').focus();
            </script>
            <form id="pageaddform" onsubmit="return false">
                <h2>Page Name</h2><br/>
                <input type="text" name="title" />
                <input type="hidden" name="catid" value="<?php echo $catid ?>" />
                <input type="hidden" name="durum" value="1" />
                <br/>
                <div align="right">
                    <span id="pageaddform_back"></span><button onclick="submitform('busycms/','pageaddform')">Add New Page</button>
                </div>
            </form>
        <?php
        }
    }

    public function edituser($userid="") {
        if (is_numeric($userid)) {
            $sql = $this->db->query("select * from admins where id=" . $this->db->escape($userid) . "");
            foreach ($sql->result() as $users) {
                ?>
                <script>
                    $('#usereditform input:first').focus();
                </script>

                <form id="usereditform" onsubmit="return false">
                    <table>
                        <tr>
                            <td>
                                <h2>Name Surname</h2><br/>
                                <input type="text" name="namesurname" value="<?php echo $users->namesurname ?>" />
                            </td>
                            <td width="50">&nbsp;</td>
                            <td>
                                <h2>Email</h2><br/>
                                <input type="text" name="email" value="<?php echo $users->email ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td style="height:10px;" colspan="3">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                                <h2>Username</h2><br/>
                                <input type="text" name="username" value="<?php echo $users->username ?>" />
                            </td>
                            <td width="50">&nbsp;</td>
                            <td>
                                <h2>Password</h2><br/>
                                <input type="text" name="password" value="<?php echo $users->password ?>" />
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="id" value="<?php echo $users->id ?>" />
                    <br/>
                    <div align="right">
                        <span id="usereditform_back"></span><button onclick="submitform('busycms/','usereditform')" style="margin-right:15px;margin-top:10px;">Save Changes</button>
                    </div>
                </form>
                <?php
            }
        }
    }

    public function adduser() {
        ?>
        <script>
            $('#usereditform input:first').focus();
        </script>

        <form id="useraddform" onsubmit="return false">
            <table>
                <tr>
                    <td>
                        <h2>Name Surname</h2><br/>
                        <input type="text" name="namesurname" />
                    </td>
                    <td width="50">&nbsp;</td>
                    <td>
                        <h2>Email</h2><br/>
                        <input type="text" name="email" />
                    </td>
                </tr>
                <tr>
                    <td style="height:10px;" colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <h2>Username</h2><br/>
                        <input type="text" name="username" />
                    </td>
                    <td width="50">&nbsp;</td>
                    <td>
                        <h2>Password</h2><br/>
                        <input type="text" name="password" />
                    </td>
                </tr>
            </table>
            <input type="hidden" name="id" />
            <br/>
            <div align="right">
                <span id="useraddform_back"></span><button onclick="submitform('busycms/','useraddform')" style="margin-right:15px;margin-top:10px;">Save Changes</button>
            </div>
        </form>
        <?php
    }

    public function pages($catid="", $catid2="", $catid3="") {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            if (is_numeric($catid)) {
                $data["catid"] = $catid;
            } else {
                $data["catid"] = "";
            }
            if (is_numeric($catid2)) {
                $data["catid2"] = $catid2;
            } else {
                $data["catid2"] = "";
            }
            if (is_numeric($catid3)) {
                $data["catid3"] = $catid3;
            } else {
                $data["catid3"] = "";
            }
            $data["page"] = "pages";
            $data["page_title"] = "Busycms Pages Management";
            $data["page_desc"] = "Busycms Dashboard";
            $this->load->view('busycms/pages/dashboard', $data);
        }
    }
    
    public function projects() {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {            
            $data["page"] = "projects";
            $data["page_title"] = "Busycms Projects Management";
            $data["page_desc"] = "Busycms Dashboard";
            $this->load->view('busycms/projects/dashboard', $data);
        }
    }

    public function customers() {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {            
            $data["page"] = "customers";
            $data["page_title"] = "Busycms Customers Management";
            $data["page_desc"] = "Busycms Dashboard";
            $this->load->view('busycms/customers/dashboard', $data);
        }
    }

    public function people() {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {            
            $data["page"] = "people";
            $data["page_title"] = "Busycms People Management";
            $data["page_desc"] = "Busycms Dashboard";
            $this->load->view('busycms/peoples/dashboard', $data);
        }
    }
    
    public function news() {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {            
            $data["page"] = "news";
            $data["page_title"] = "Busycms News Management";
            $data["page_desc"] = "Busycms Dashboard";
            $this->load->view('busycms/news/dashboard', $data);
        }
    }

    public function users() {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            $data["page"] = "users";
            $data["page_title"] = "Busycms Users Management";
            $data["page_desc"] = "Busycms Dashboard";
            $this->load->view('busycms/users/dashboard', $data);
        }
    }

    public function dashboard() {
          $login=$this->session->userdata('busylogin');
          if ($login<>1) {
          header('Location: '.base_url().'busycms/login');
          }
          else {
              
          $data["page"]="projects";
          $data["page_title"]="Busycms Dashboard";
          $data["page_desc"]="Busycms Dashboard";
          $this->load->view('busycms/projects/dashboard',$data);
          } 

        /*$login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            header('Location: ' . base_url() . 'busycms/login');
        } else {
            $year = $this->input->get('year');
            $month = $this->input->get('month');

            if (!is_numeric($year)) {
                $year = date('Y');
            }
            if (!is_numeric($month)) {
                $month = date('m');
            }

            $this->load->model('busycms_calendar');

            $data['calendar'] = $this->busycms_calendar->generate($year, $month);

            $data["page"] = "events";
            $data["year"] = $year;
            $data["month"] = $month;
            $data["page_title"] = "Busycms Events ";
            $data["page_desc"] = "Busycms Events ";
            $data["id"] = $id;
            $this->load->view('busycms/events/dashboard', $data);
        }*/
    }

    public function logout() {
        $data = array(
            'busylogin' => 0,
            'busyusername' => "",
            'userid' => ""
        );
        $this->session->set_userdata($data);
        header('Location: ' . base_url() . 'busycms/login');
    }

    public function loginform_do() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $err = "";
        if (strlen($username) <= 4) {
            $err = "Please enter your username<br/>";
        }
        if (strlen($password) <= 4) {
            $err = $err . "Please enter your password<br/>";
        }

        if (strlen($err) > 0) {
            ?>
            <script>
                $.notification ( 
                {
                    title:      'Wrong username , password',
                    content:    '<?php echo $err ?>',
                    icon:       '!',
                    color:      '#333',
                    timeout: 5000
                }
            )
            </script>
            <?php
            exit();
        } else {
            $sql = $this->db->query("select * from admins where username='" . $this->db->escape_like_str($username) . "' and password='" . $this->db->escape_like_str($password) . "'");
            if ($sql->num_rows() == 0) {
                ?>
                <script>
                    $.notification ( 
                    {
                        title:      'Wrong username , password',
                        content:    'Please check your username or password in Busy Cms',
                        icon:       '!',
                        color:      '#333',
                        timeout: 5000
                    }
                )
                </script>
            <?php
            } else {
                foreach ($sql->result() as $uye) {
                    $data = array(
                        'busylogin' => 1,
                        'busyusername' => $uye->username,
                        'busynamesurname' => $uye->namesurname,
                        'userid' => $uye->id
                    );
                    $this->session->set_userdata($data);
                    ?>
                    <script>parent.location.href='<?php echo base_url() ?>busycms/pages'</script>
                <?php
                }
            }
        }
    }

    public function login() {
        $login = $this->session->userdata('busylogin');
        if ($login <> 1) {
            $this->load->view('busycms/login');
        } else {
            header('Location: ' . base_url() . 'busycms/dashboard');
        }
    }

    public function deleteimage($id) {
        $sql = $this->db->query("select * from images where id=" . $this->db->escape($id) . "");
        foreach ($sql->result() as $resim) {
            unlink("./uploads/" . $resim->image);
            unlink("./uploads/thumb_" . $resim->image);
//            unlink("./uploads/crop_" . $resim->image);
            $this->db->where('id', $resim->id);
            if ($this->db->delete('images')) {
                ?>
                <script>
                    $('#recordsArray_<?php echo $resim->id ?>').fadeOut(500);
                    $('.notification .hide').click();
                </script>
            <?php
            }
        }
    }
    
    public function deleteimagenews($id) {
        $sql = $this->db->query("select * from news_images where id=" . $this->db->escape($id) . "");
        foreach ($sql->result() as $resim) {
            unlink("./uploads/" . $resim->image);
            unlink("./uploads/thumb_" . $resim->image);
//            unlink("./uploads/crop_" . $resim->image);
            $this->db->where('id', $resim->id);
            if ($this->db->delete('news_images')) {
                ?>
                <script>
                    $('#image_<?php echo $resim->id ?>').fadeOut(500);
                    $('.notification .hide').click();
                </script>
            <?php
            }
        }
    }
    
    public function deleteimageproject($id) {
        $sql = $this->db->query("select * from project_images where id=" . $this->db->escape($id) . "");
        foreach ($sql->result() as $resim) {
            unlink("./uploads/" . $resim->image);
            unlink("./uploads/thumb_" . $resim->image);
//            unlink("./uploads/crop_" . $resim->image);
            $this->db->where('id', $resim->id);
            if ($this->db->delete('project_images')) {
                ?>
                <script>
                    $('#image_<?php echo $resim->id ?>').fadeOut(500);
                    $('.notification .hide').click();
                </script>
            <?php
            }
        }
    }

    public function uploadimage($id) {
        $this->load->library('upload');
        $this->load->model('busycms_model');
        $images = $_FILES['images']['name'];
        $resimsay = count($images);
        $error = 0;
        $configthumb="";
        for ($i = 0; $i < $resimsay; $i++) {
            $config="";
            $_FILES['userfile']['name'] = $_FILES['images']['name'][$i];
            $_FILES['userfile']['type'] = $_FILES['images']['type'][$i];
            $_FILES['userfile']['tmp_name'] = $_FILES['images']['tmp_name'][$i];
            $_FILES['userfile']['error'] = $_FILES['images']['error'][$i];
            $_FILES['userfile']['size'] = $_FILES['images']['size'][$i];
            unset($config);
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'jpg|jpeg|gif|png';
            /* $config['max_size'] = '3200';
              $config['max_width'] = '2000';
              $config['max_height'] = '3000'; */
            $config['file_name'] = $this->busycms_model->seo_url($_FILES['images']['name'][$i]);
            $config['max_size'] = '0';
            $config['overwrite'] = FALSE;

            $this->upload->initialize($config);



            if ($this->upload->do_upload()) {

                $this->load->library('image_lib');

                $data = array('upload_data' => $this->upload->data());
                $resimadi = $this->upload->file_name;


                /*unset($config);
                $config = array();
                $config['source_image'] = "./uploads/" . $this->upload->file_name;
                $config['image_library'] = 'gd2';
                $config['maintain_ratio'] = TRUE;
                $config['new_image'] = './uploads/' . $this->upload->file_name;
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $width = $this->image_lib->width;
                $this->image_lib->clear();
                unset($config);

                if ($width > 1000) {
                    unset($config);
                    $config = array();
                    $config['source_image'] = "./uploads/" . $this->upload->file_name;
                    $config['image_library'] = 'gd2';
                    $config['maintain_ratio'] = TRUE;
                    $config['new_image'] = './uploads/' .$this->upload->file_name;
                    $config['master_dim'] = 'width';
                    $config['width'] = 1000;
                    $config['height'] = 600;
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();

                    $this->image_lib->clear();
                    unset($config);
                }*/

                unset($configthumb);
                //Create 250px version
                $configthumb = array();
                $configthumb['source_image'] = "./uploads/" . $this->upload->file_name;
                $configthumb['image_library'] = 'gd2';
                $configthumb['maintain_ratio'] = TRUE;
                $configthumb['quality'] = 75;
                $configthumb['new_image'] = './uploads/thumb_' . $this->upload->file_name;
                $configthumb['width'] = 250;
                $configthumb['height'] = 250;
                $this->image_lib->initialize($configthumb);
                $this->image_lib->resize();
                $this->image_lib->clear();
                unset($configthumb);

                $reorder = 0;
                $sql = $this->db->query("select max(reorder) as enbuyuk from images where pageid=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $son) {
                    $reorder = $son->enbuyuk + 1;
                }
                if (!$reorder > 0) {
                    $reorder = 1;
                }

                $array = array('pageid' => $id, 'image' => $this->upload->file_name, 'reorder' => $reorder, 'nedir' => 1);
                $this->db->insert('images', $array);
                ?>
                <script>
                    /*eklenen='<img src="<?php echo base_url() ?>uploads/<?php echo $this->upload->file_name ?>" height="100" />';
                    txt=parent.document.getElementById('imagelist').innerHTML;
                    //parent.document.getElementById('upload_wait').style.display='none';
                    parent.document.getElementById('imagelist').innerHTML=txt+eklenen;*/
                    parent.$('#lastimage').before('<li id="recordsArray_<?php echo $this->db->insert_id(); ?>">\n\
                            <div class="image">\n\
                            <img src="<?php echo base_url() ?>uploads/thumb_<?php echo $this->upload->file_name ?>" />\n\
                            </div>\n\
                            <div class="menus">\n\
                                <table width="100%" height="100%">\n\
                                    <tr>\n\
                                        <td style="vertical-align:middle;">\n\
                                            <div id="menu<?php echo $this->db->insert_id() ?>">\n\
                                                <a href="<?php echo base_url() ?>busycms/mainimage/<?php echo $id ?>/<?php echo $this->db->insert_id() ?>">Main Image</a><br/>\n\
                                                <a href="<?php echo base_url() ?>busycms/cropimage/<?php echo $id ?>/<?php echo $this->db->insert_id() ?>">Crop Image</a><br/>\n\
                                                <a href="javascript:void(0)" onclick="edit(<?php echo $this->db->insert_id() ?>)">Edit Image</a><br/>\n\
                                                <a href="javascript:void(0)" onclick="$(\'#menu<?php echo $this->db->insert_id() ?>\').fadeOut(100,function(){ $(\'#delete<?php echo $this->db->insert_id() ?>\').fadeIn(100)});">Delete Image</a>\n\
                                            </div>\n\
                                            <div id="edit<?php echo $this->db->insert_id() ?>" class="imageedit">\n\
                                            <form id="imageeditform<?php echo $this->db->insert_id() ?>" onsubmit="return false;">\n\
                                                <div id="imageeditform<?php echo $this->db->insert_id() ?>_back">\n\
                                                </div>\n\
                                                <input type="hidden" name="id" value="<?php echo $this->db->insert_id() ?>" />\n\
                                                <textarea name="description"></textarea>\n\
                                                <div align="right">\n\
                                                    <button onclick="submitform(\'busycms/imageeditform/\',\'imageeditform<?php echo $this->db->insert_id() ?>\')">Save</button>\n\
                                                </div>\n\
                                            </form>\n\
                                            </div> \n\
                                <div id="delete<?php echo $this->db->insert_id() ?>" style="display:none;" class="delete">\n\
                                    Delete image<br/>\n\
                                    <span onclick="deleteimage(<?php echo $this->db->insert_id() ?>)">yes</span> or <span onclick="$(\'#delete<?php echo $this->db->insert_id() ?>\').fadeOut(100,function(){ $(\'#menu<?php echo $this->db->insert_id() ?>\').fadeIn(100)});">no</span>\n\
                                </div>\n\
                                        </td>\n\
                                    </tr>\n\
                                </table>\n\
                                </div>\n\
                                <li>')
                                        //delay(2000);
                </script>
                <?php
            } else {
                $error = 1;
            }
        }

        if ($error > 0) {
            echo "resimler yüklenemedi";
        } else {
            //echo "yükledim valla";
        }
        unset($config);
    }

    public function uploadimageevent($id) {
        $this->load->library('upload');
        $this->load->model('busycms_model');
        $images = $_FILES['images']['name'];
        $resimsay = count($images);
        $error = 0;
        for ($i = 0; $i < $resimsay; $i++) {

            $_FILES['userfile']['name'] = $_FILES['images']['name'][$i];
            $_FILES['userfile']['type'] = $_FILES['images']['type'][$i];
            $_FILES['userfile']['tmp_name'] = $_FILES['images']['tmp_name'][$i];
            $_FILES['userfile']['error'] = $_FILES['images']['error'][$i];
            $_FILES['userfile']['size'] = $_FILES['images']['size'][$i];

            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'jpg|jpeg|gif|png';
            /* $config['max_size'] = '3200';
              $config['max_width'] = '2000';
              $config['max_height'] = '3000'; */
            $config['file_name'] = $this->busycms_model->seo_url($_FILES['images']['name'][$i]);
            $config['max_size'] = '0';
            $config['overwrite'] = FALSE;

            $this->upload->initialize($config);



            if ($this->upload->do_upload()) {

                $this->load->library('image_lib');

                $data = array('upload_data' => $this->upload->data());
                $resimadi = $this->upload->file_name;


                unset($config);
                $config = array();
                $config['source_image'] = "./uploads/" . $resimadi;
                $config['image_library'] = 'gd2';
                $config['maintain_ratio'] = TRUE;
                $config['new_image'] = './uploads/' . $resimadi;
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $width = $this->image_lib->width;
                $this->image_lib->clear();
                unset($config);

                if ($width > 1000) {
                    unset($config);
                    $config = array();
                    $config['source_image'] = "./uploads/" . $resimadi;
                    $config['image_library'] = 'gd2';
                    $config['maintain_ratio'] = TRUE;
                    $config['new_image'] = './uploads/' . $resimadi;
                    $config['master_dim'] = 'width';
                    $config['width'] = 1000;
                    $config['height'] = 600;
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();

                    $this->image_lib->clear();
                    unset($config);
                }

                unset($config);
                //Create 250px version
                $config = array();
                $config['source_image'] = "./uploads/" . $this->upload->file_name;
                $config['image_library'] = 'gd2';
                $config['maintain_ratio'] = TRUE;
                $config['new_image'] = './uploads/thumb_' . $this->upload->file_name;
                $config['width'] = 250;
                $config['height'] = 250;
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $this->image_lib->clear();
                unset($config);

                $reorder = 0;
                $sql = $this->db->query("select max(reorder) as enbuyuk from images where pageid=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $son) {
                    $reorder = $son->enbuyuk + 1;
                }
                if (!$reorder > 0) {
                    $reorder = 1;
                }

                $array = array('pageid' => $id, 'image' => $this->upload->file_name, 'reorder' => $reorder, 'nedir' => 2);
                $this->db->insert('images', $array);
            } else {
                $error = 1;
            }
        }
        ?>
        <script type="text/javascript">
            parent.$.ajax({
                url: '<?php echo base_url() ?>busycms/eventphotos/<?php echo $id ?>',
                success: function(data) { 
                    parent.$('#eventphotos').html(data);
                }
            });
        </script>
        <?php
        if ($error > 0) {
            echo "resimler yüklenemedi";
        } else {
            //echo "yükledim valla";
        }
        unset($config);
    }
    
    public function uploadimageproduct($id) {
        $this->load->library('upload');
        $this->load->model('busycms_model');
        $images = $_FILES['images']['name'];
        $resimsay = count($images);
        $error = 0;
        for ($i = 0; $i < $resimsay; $i++) {

            $_FILES['userfile']['name'] = $_FILES['images']['name'][$i];
            $_FILES['userfile']['type'] = $_FILES['images']['type'][$i];
            $_FILES['userfile']['tmp_name'] = $_FILES['images']['tmp_name'][$i];
            $_FILES['userfile']['error'] = $_FILES['images']['error'][$i];
            $_FILES['userfile']['size'] = $_FILES['images']['size'][$i];

            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'jpg|jpeg|gif|png';
            /* $config['max_size'] = '3200';
              $config['max_width'] = '2000';
              $config['max_height'] = '3000'; */
            $config['file_name'] = $this->busycms_model->seo_url($_FILES['images']['name'][$i]);
            $config['max_size'] = '0';
            $config['overwrite'] = FALSE;

            $this->upload->initialize($config);



            if ($this->upload->do_upload()) {

                $this->load->library('image_lib');

                $data = array('upload_data' => $this->upload->data());
                $resimadi = $this->upload->file_name;


                unset($config);
                $config = array();
                $config['source_image'] = "./uploads/" . $resimadi;
                $config['image_library'] = 'gd2';
                $config['maintain_ratio'] = TRUE;
                $config['new_image'] = './uploads/' . $resimadi;
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $width = $this->image_lib->width;
                $this->image_lib->clear();
                unset($config);

                if ($width > 1000) {
                    unset($config);
                    $config = array();
                    $config['source_image'] = "./uploads/" . $resimadi;
                    $config['image_library'] = 'gd2';
                    $config['maintain_ratio'] = TRUE;
                    $config['new_image'] = './uploads/' . $resimadi;
                    $config['master_dim'] = 'width';
                    $config['width'] = 1000;
                    $config['height'] = 600;
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();

                    $this->image_lib->clear();
                    unset($config);
                }

                unset($config);
                //Create 250px version
                $config = array();
                $config['source_image'] = "./uploads/" . $this->upload->file_name;
                $config['image_library'] = 'gd2';
                $config['maintain_ratio'] = TRUE;
                $config['new_image'] = './uploads/thumb_' . $this->upload->file_name;
                $config['width'] = 400;
                $config['height'] = 400;
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $this->image_lib->clear();
                unset($config);

                $reorder = 0;
                $sql = $this->db->query("select max(reorder) as enbuyuk from images where pageid=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $son) {
                    $reorder = $son->enbuyuk + 1;
                }
                if (!$reorder > 0) {
                    $reorder = 1;
                }

                $array = array('pageid' => $id, 'image' => $this->upload->file_name, 'reorder' => $reorder, 'nedir' => 3);
                $this->db->insert('images', $array);
            } else {
                $error = 1;
            }
        }
        ?>
        <script type="text/javascript">
            parent.$.ajax({
                url: '<?php echo base_url() ?>busycms/productphotos/<?php echo $id ?>',
                success: function(data) { 
                    parent.$('#gallery').html(data);
                }
            });
        </script>
        <?php
        if ($error > 0) {
            echo "resimler yüklenemedi";
        } else {
            //echo "yükledim valla";
        }
        unset($config);
    }
    
    public function uploadimagenews($id) {
        $this->load->library('upload');
        $this->load->model('busycms_model');
        $images = $_FILES['images']['name'];
        $resimsay = count($images);
        $error = 0;
        for ($i = 0; $i < $resimsay; $i++) {

            $_FILES['userfile']['name'] = $_FILES['images']['name'][$i];
            $_FILES['userfile']['type'] = $_FILES['images']['type'][$i];
            $_FILES['userfile']['tmp_name'] = $_FILES['images']['tmp_name'][$i];
            $_FILES['userfile']['error'] = $_FILES['images']['error'][$i];
            $_FILES['userfile']['size'] = $_FILES['images']['size'][$i];

            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'jpg|jpeg|gif|png';
            /* $config['max_size'] = '3200';
              $config['max_width'] = '2000';
              $config['max_height'] = '3000'; */
            $config['file_name'] = $this->busycms_model->seo_url($_FILES['images']['name'][$i]);
            $config['max_size'] = '0';
            $config['overwrite'] = FALSE;

            $this->upload->initialize($config);



            if ($this->upload->do_upload()) {

                $this->load->library('image_lib');

                $data = array('upload_data' => $this->upload->data());
                $resimadi = $this->upload->file_name;


                unset($config);
                $config = array();
                $config['source_image'] = "./uploads/" . $resimadi;
                $config['image_library'] = 'gd2';
                $config['maintain_ratio'] = TRUE;
                $config['new_image'] = './uploads/' . $resimadi;
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $width = $this->image_lib->width;
                $this->image_lib->clear();
                unset($config);

                if ($width > 1000) {
                    unset($config);
                    $config = array();
                    $config['source_image'] = "./uploads/" . $resimadi;
                    $config['image_library'] = 'gd2';
                    $config['maintain_ratio'] = TRUE;
                    $config['new_image'] = './uploads/' . $resimadi;
                    $config['master_dim'] = 'width';
                    $config['width'] = 1000;
                    $config['height'] = 600;
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();

                    $this->image_lib->clear();
                    unset($config);
                }

                unset($config);
                //Create 250px version
                $config = array();
                $config['source_image'] = "./uploads/" . $this->upload->file_name;
                $config['image_library'] = 'gd2';
                $config['maintain_ratio'] = TRUE;
                $config['new_image'] = './uploads/thumb_' . $this->upload->file_name;
                $config['width'] = 400;
                $config['height'] = 400;
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $this->image_lib->clear();
                unset($config);
                
                unset($config);
                $config = array();
                $config['source_image'] = "./uploads/" . $this->upload->file_name;
                $config['image_library'] = 'gd2';
                $config['maintain_ratio'] = TRUE;
                $config['new_image'] = './uploads/' . $this->upload->file_name;
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $nwidth = $this->image_lib->width;
                $nheight = $this->image_lib->height;
                $this->image_lib->clear();
                unset($config);

                $reorder = 0;
                $sql = $this->db->query("select max(reorder) as enbuyuk from news_images where news_id=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $son) {
                    $reorder = $son->enbuyuk + 1;
                }
                if (!$reorder > 0) {
                    $reorder = 1;
                }

                $array = array('news_id' => $id, 'image' => $this->upload->file_name, 'reorder' => $reorder, 'width'=>$nwidth , 'height'=>$nheight);
                $this->db->insert('news_images', $array);
            } else {
                $error = 1;
            }
        }
        ?>
        <script type="text/javascript">
            parent.$.ajax({
                url: '<?php echo base_url() ?>busycms/newsphotos/<?php echo $id ?>',
                success: function(data) { 
                    parent.$('#gallery').html(data);
                }
            });
        </script>
        <?php
        if ($error > 0) {
            echo "resimler yüklenemedi";
        } else {
            //echo "yükledim valla";
        }
        unset($config);
    }
    
    public function uploadimageproject($id) {
        $this->load->library('upload');
        $this->load->model('busycms_model');
        $images = $_FILES['images']['name'];
        $resimsay = count($images);
        $error = 0;
        for ($i = 0; $i < $resimsay; $i++) {

            $_FILES['userfile']['name'] = $_FILES['images']['name'][$i];
            $_FILES['userfile']['type'] = $_FILES['images']['type'][$i];
            $_FILES['userfile']['tmp_name'] = $_FILES['images']['tmp_name'][$i];
            $_FILES['userfile']['error'] = $_FILES['images']['error'][$i];
            $_FILES['userfile']['size'] = $_FILES['images']['size'][$i];

            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'jpg|jpeg|gif|png';
            /* $config['max_size'] = '3200';
              $config['max_width'] = '2000';
              $config['max_height'] = '3000'; */
            $config['file_name'] = $this->busycms_model->seo_url($_FILES['images']['name'][$i]);
            $config['max_size'] = '0';
            $config['overwrite'] = FALSE;

            $this->upload->initialize($config);



            if ($this->upload->do_upload()) {

                $this->load->library('image_lib');

                $data = array('upload_data' => $this->upload->data());
                $resimadi = $this->upload->file_name;


                unset($config);
                $config = array();
                $config['source_image'] = "./uploads/" . $resimadi;
                $config['image_library'] = 'gd2';
                $config['maintain_ratio'] = TRUE;
                $config['new_image'] = './uploads/' . $resimadi;
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $width = $this->image_lib->width;
                $this->image_lib->clear();
                unset($config);

                if ($width > 1000) {
                    unset($config);
                    $config = array();
                    $config['source_image'] = "./uploads/" . $resimadi;
                    $config['image_library'] = 'gd2';
                    $config['maintain_ratio'] = TRUE;
                    $config['new_image'] = './uploads/' . $resimadi;
                    $config['master_dim'] = 'width';
                    $config['width'] = 1000;
                    $config['height'] = 600;
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();

                    $this->image_lib->clear();
                    unset($config);
                }

                unset($config);
                //Create 250px version
                $config = array();
                $config['source_image'] = "./uploads/" . $this->upload->file_name;
                $config['image_library'] = 'gd2';
                $config['maintain_ratio'] = TRUE;
                $config['new_image'] = './uploads/thumb_' . $this->upload->file_name;
                $config['width'] = 400;
                $config['height'] = 400;
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $this->image_lib->clear();
                unset($config);
                
                unset($config);
                $config = array();
                $config['source_image'] = "./uploads/" . $this->upload->file_name;
                $config['image_library'] = 'gd2';
                $config['maintain_ratio'] = TRUE;
                $config['new_image'] = './uploads/' . $this->upload->file_name;
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $nwidth = $this->image_lib->width;
                $nheight = $this->image_lib->height;
                $this->image_lib->clear();
                unset($config);

                $reorder = 0;
                $sql = $this->db->query("select max(reorder) as enbuyuk from project_images where project_id=" . $this->db->escape($id) . "");
                foreach ($sql->result() as $son) {
                    $reorder = $son->enbuyuk + 1;
                }
                if (!$reorder > 0) {
                    $reorder = 1;
                }

                $array = array('project_id' => $id, 'image' => $this->upload->file_name, 'reorder' => $reorder, 'width'=>$nwidth , 'height'=>$nheight);
                $this->db->insert('project_images', $array);
            } else {
                $error = 1;
            }
        }
        ?>
        <script type="text/javascript">
            parent.$.ajax({
                url: '<?php echo base_url() ?>busycms/projectphotos/<?php echo $id ?>',
                success: function(data) { 
                    parent.$('#gallery').html(data);
                }
            });
        </script>
        <?php
        if ($error > 0) {
            echo "resimler yüklenemedi";
        } else {
            //echo "yükledim valla";
        }
        unset($config);
    }

    public function cropform_do() {
        $x = $this->input->post('x');
        $y = $this->input->post('y');
        $w = $this->input->post('w');
        $h = $this->input->post('h');

        $id = $this->input->post('id');
        $pageid = $this->input->post('pageid');

        $sql = $this->db->query("select * from images where id=" . $this->db->escape($id) . "");
        foreach ($sql->result() as $image) {
            $resimadi = $image->image;
        }


        $this->load->library('upload');
        $this->load->library('image_lib');
        //Create 600px version
        $config = array();
        $config['source_image'] = "./uploads/" . $resimadi;
        $config['image_library'] = 'gd2';
        $config['maintain_ratio'] = FALSE;
        $config['new_image'] = './uploads/crop_' . $resimadi;
        $config['x_axis'] = $x;
        $config['y_axis'] = $y;
        $config['width'] = $w;
        $config['height'] = $h;
        $this->image_lib->initialize($config);
        $this->image_lib->crop();
        $this->image_lib->clear();
        unset($config);

        unset($config);
        //Create 600px version
        $config = array();
        $config['source_image'] = "./uploads/crop_" . $resimadi;
        $config['image_library'] = 'gd2';
        $config['maintain_ratio'] = TRUE;
        $config['new_image'] = './uploads/crop_' . $resimadi;
        $config['width'] = 960;
        //$config['height'] = 600;
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
        $this->image_lib->clear();
        unset($config);

        $data = array('image' => 'crop_' . $resimadi);
        $this->db->where('id', $pageid);
        if ($this->db->update('pages', $data)) {
            ?>
            <script>
                parent.location.href='<?php echo base_url() ?>busycms/editpage/<?php echo $pageid ?>';
            </script>
        <?php
        }
    }

    public function cropformeventmainimage_do() {
        $x = $this->input->post('x');
        $y = $this->input->post('y');
        $w = $this->input->post('w');
        $h = $this->input->post('h');
        $id = $this->input->post('id');

        $sql = $this->db->query("select * from events where id=" . $this->db->escape($id) . "");
        foreach ($sql->result() as $image) {
            $resimadi = $image->image;
        }

        $this->load->library('upload');
        $this->load->library('image_lib');
        //Create 600px version
        $config = array();
        $config['source_image'] = "./uploads/" . $resimadi;
        $config['image_library'] = 'gd2';
        $config['maintain_ratio'] = FALSE;
        $config['new_image'] = './uploads/crop_' . $resimadi;
        $config['x_axis'] = $x;
        $config['y_axis'] = $y;
        $config['width'] = $w;
        $config['height'] = $h;
        $this->image_lib->initialize($config);
        $this->image_lib->crop();
        $this->image_lib->clear();
        unset($config);

        unset($config);
        //Create 600px version
        $config = array();
        $config['source_image'] = "./uploads/crop_" . $resimadi;
        $config['image_library'] = 'gd2';
        $config['maintain_ratio'] = TRUE;
        $config['new_image'] = './uploads/crop_' . $resimadi;
        $config['width'] = 300;
        //$config['height'] = 600;
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
        $this->image_lib->clear();
        unset($config);



        $data = array('image' => 'crop_' . $resimadi);
        $this->db->where('id', $id);
        if ($this->db->update('events', $data)) {
            ?>
            <script>
                parent.location.href='<?php echo base_url() ?>busycms/editevent/<?php echo $id ?>';
            </script>
        <?php
        }
    }

    public function cropformeventimage_do() {
        $x = $this->input->post('x');
        $y = $this->input->post('y');
        $w = $this->input->post('w');
        $h = $this->input->post('h');
        $id = $this->input->post('id');
        $pageid = $this->input->post('pageid');

        $sql = $this->db->query("select * from images where id=" . $this->db->escape($id) . "");
        foreach ($sql->result() as $image) {
            $resimadi = $image->image;
        }

        $this->load->library('upload');
        $this->load->library('image_lib');
        //Create 600px version
        $config = array();
        $config['source_image'] = "./uploads/" . $resimadi;
        $config['image_library'] = 'gd2';
        $config['maintain_ratio'] = FALSE;
        $config['new_image'] = './uploads/crop_' . $resimadi;
        $config['x_axis'] = $x;
        $config['y_axis'] = $y;
        $config['width'] = $w;
        $config['height'] = $h;
        $this->image_lib->initialize($config);
        $this->image_lib->crop();
        $this->image_lib->clear();
        unset($config);

        unset($config);
        //Create 600px version
        $config = array();
        $config['source_image'] = "./uploads/crop_" . $resimadi;
        $config['image_library'] = 'gd2';
        $config['maintain_ratio'] = TRUE;
        $config['new_image'] = './uploads/crop_' . $resimadi;
        $config['width'] = 300;
        //$config['height'] = 600;
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
        $this->image_lib->clear();
        unset($config);



        $data = array('image' => 'crop_' . $resimadi);
        $this->db->where('id', $pageid);
        if ($this->db->update('events', $data)) {
            ?>
            <script>
                parent.location.href='<?php echo base_url() ?>busycms/editevent/<?php echo $pageid ?>';
            </script>
        <?php
        }
    }

    public function cropimageform_do() {
        $x = $this->input->post('x');
        $y = $this->input->post('y');
        $w = $this->input->post('w');
        $h = $this->input->post('h');

        $id = $this->input->post('id');
        $pageid = $this->input->post('pageid');

        $sql = $this->db->query("select * from images where id=" . $this->db->escape($id) . "");
        foreach ($sql->result() as $image) {
            $resimadi = $image->image;
        }


        $this->load->library('upload');
        $this->load->library('image_lib');
        //Create 600px version
        $config = array();
        $config['source_image'] = "./uploads/" . $resimadi;
        $config['image_library'] = 'gd2';
        $config['maintain_ratio'] = FALSE;
        $config['new_image'] = './uploads/' . $resimadi;
        $config['x_axis'] = $x;
        $config['y_axis'] = $y;
        $config['width'] = $w;
        $config['height'] = $h;
        $this->image_lib->initialize($config);
        $this->image_lib->crop();
        $this->image_lib->clear();
        unset($config);

        unset($config);
        //Create 250px version
        $config = array();
        $config['source_image'] = "./uploads/" . $resimadi;
        $config['image_library'] = 'gd2';
        $config['maintain_ratio'] = TRUE;
        $config['new_image'] = './uploads/thumb_' . $resimadi;
        $config['width'] = 250;
        $config['height'] = 250;
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
        $this->image_lib->clear();
        unset($config);

        $data = array('image' => 'crop_' . $resimadi);
        $this->db->where('id', $pageid);
        if ($this->db->update('pages', $data)) {
            ?>
            <script>
                parent.location.href='<?php echo base_url() ?>busycms/cropimage/<?php echo $pageid . "/" . $id ?>';
            </script>
        <?php
        }
    }

    public function cropimageformevent_do() {
        $x = $this->input->post('x');
        $y = $this->input->post('y');
        $w = $this->input->post('w');
        $h = $this->input->post('h');

        $id = $this->input->post('id');
        $pageid = $this->input->post('pageid');
        $eventid = $this->input->post('eventid');

        $sql = $this->db->query("select * from images where id=" . $this->db->escape($id) . "");
        foreach ($sql->result() as $image) {
            $resimadi = $image->image;
        }


        $this->load->library('upload');
        $this->load->library('image_lib');
        //Create 600px version
        $config = array();
        $config['source_image'] = "./uploads/" . $resimadi;
        $config['image_library'] = 'gd2';
        $config['maintain_ratio'] = FALSE;
        $config['new_image'] = './uploads/' . $resimadi;
        $config['x_axis'] = $x;
        $config['y_axis'] = $y;
        $config['width'] = $w;
        $config['height'] = $h;
        $this->image_lib->initialize($config);
        $this->image_lib->crop();
        $this->image_lib->clear();
        unset($config);

        unset($config);
        //Create 250px version
        $config = array();
        $config['source_image'] = "./uploads/" . $resimadi;
        $config['image_library'] = 'gd2';
        $config['maintain_ratio'] = TRUE;
        $config['new_image'] = './uploads/thumb_' . $resimadi;
        $config['width'] = 250;
        $config['height'] = 250;
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
        $this->image_lib->clear();
        unset($config);

        $data = array('image' =>$resimadi);
        $this->db->where('id', $id);
        if ($this->db->update('images', $data)) {
            ?>
            <script>
                parent.location.href='<?php echo base_url() ?>busycms/cropimageevent/<?php echo $pageid . "/" . $id . "/" . $eventid ?>';
            </script>
        <?php
        }
    }
    
    public function cropimageformproductcategory_do() {
        $x = $this->input->post('x');
        $y = $this->input->post('y');
        $w = $this->input->post('w');
        $h = $this->input->post('h');

        $id = $this->input->post('id');

        $sql = $this->db->query("select * from product_categories where id=" . $this->db->escape($id) . "");
        foreach ($sql->result() as $image) {
            $resimadi = $image->image;
        }


        $this->load->library('upload');
        $this->load->library('image_lib');
        //Create 600px version
        $config = array();
        $config['source_image'] = "./uploads/" . $resimadi;
        $config['image_library'] = 'gd2';
        $config['maintain_ratio'] = FALSE;
        $config['new_image'] = './uploads/' . $resimadi;
        $config['x_axis'] = $x;
        $config['y_axis'] = $y;
        $config['width'] = $w;
        $config['height'] = $h;
        $this->image_lib->initialize($config);
        $this->image_lib->crop();
        $this->image_lib->clear();
        unset($config);

        unset($config);
        //Create 250px version
        $config = array();
        $config['source_image'] = "./uploads/" . $resimadi;
        $config['image_library'] = 'gd2';
        $config['maintain_ratio'] = TRUE;
        $config['new_image'] = './uploads/thumb_' . $resimadi;
        $config['width'] = 450;
        $config['height'] = 363;
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
        $this->image_lib->clear();
        unset($config);

        $data = array('image' => 'crop_' . $resimadi);
        $this->db->where('id', $pageid);
        if ($this->db->update('product_categories', $data)) {
            ?>
            <script>
                parent.location.href='<?php echo base_url() ?>busycms/productcategoryimagecrop/<?php echo $id ?>';
            </script>
        <?php
        }
    }
    
    public function cropimageformproduct_do() {
        $x = $this->input->post('x');
        $y = $this->input->post('y');
        $w = $this->input->post('w');
        $h = $this->input->post('h');

        $id = $this->input->post('id');

        $sql = $this->db->query("select * from products where id=" . $this->db->escape($id) . "");
        foreach ($sql->result() as $image) {
            $resimadi = $image->image;
        }


        $this->load->library('upload');
        $this->load->library('image_lib');
        //Create 600px version
        $config = array();
        $config['source_image'] = "./uploads/crop_" . $resimadi;
        $config['image_library'] = 'gd2';
        $config['maintain_ratio'] = FALSE;
        $config['new_image'] = './uploads/crop_' . $resimadi;
        $config['x_axis'] = $x;
        $config['y_axis'] = $y;
        $config['width'] = $w;
        $config['height'] = $h;
        $this->image_lib->initialize($config);
        $this->image_lib->crop();
        $this->image_lib->clear();
        unset($config);

        unset($config);
        //Create 250px version
        $config = array();
        $config['source_image'] = "./uploads/" . $resimadi;
        $config['image_library'] = 'gd2';
        $config['maintain_ratio'] = TRUE;
        $config['new_image'] = './uploads/thumb_' . $resimadi;
        $config['width'] = 300;
        $config['height'] = 250;
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
        $this->image_lib->clear();
        unset($config);

        $data = array('image' => '' . $resimadi);
        $this->db->where('id', $id);
        if ($this->db->update('products', $data)) {
            ?>
            <script>
                parent.location.href='<?php echo base_url() ?>busycms/editproduct/<?php echo $id ?>';
            </script>
        <?php
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
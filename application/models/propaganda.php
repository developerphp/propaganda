<?php 
class Propaganda extends CI_Model {   
    
    function news_list($news) 
    {
        ?>
                <a href="<?php echo base_url().$this->lang->line('lang') ?>news/detail/<?php echo $this->seo_url($news["title".$this->lang->line('dil')]) ?>/<?php echo $news['id'] ?>">
                    <div class="image">
                        <img src="<?php echo base_url() ?>uploads/<?php echo $news['image'] ?>" />
                    </div>
                    <div class="title">
                        <div>
                            <label>
                                <b><?php echo $news["title".$this->lang->line('dil')] ?></b>
                            </label>
                        </div>
                    </div>
                </a>
        <?php
    }
    
    function seo_url($text) 
    {
        $text = trim($text);
        $search = array(' ','\"','\'','Ç','ç','G','ğ','i','I','Ö','ö','S','s','Ü','ü','I','<','>','Ş','ş','İ','?','\\','ı','&','!',',','#39;',',','+','*','#');
        $replace = array('-','','','c','c','g','g','i','i','o','o','s','s','u','u','i','&#60','&#62','s','s','i','','','i','','','-','','','','','');
        $new_text = str_replace($search,$replace,$text);
        $new_text2 = str_replace("__","_",$new_text);
        return strtolower($new_text2);
    }
    
    function kirp($text,$adet)
    {
        $kelimeler="";
        $vars = explode(' ', $text); 
        if (count($vars)>$adet) 
        {
            for($j=1;$j<=$adet;$j++) {
                $kelimeler=$kelimeler.str_replace("\\","",str_replace("<p>","",$vars[$j-1]." "));
            } 
            return $kelimeler;
        }
        else 
        {
            for($j=1;$j<=count($vars);$j++) {
                 $kelimeler=$kelimeler.str_replace("\\","",str_replace("<p>","",$vars[$j-1]." "));
            } 
            return $kelimeler;
        }
    }
}
?>
<?php 
class Sezgin extends CI_Model {   
    
    function seo_url($text) 
    {
        $text = trim($text);
        $search = array(' ','\"','\'','Ç','ç','G','ğ','i','I','Ö','ö','S','s','Ü','ü','I','<','>','Ş','ş','İ','?','\\','ı','&','!',',','-','#39;',',','+','*','#');
        $replace = array('','','','c','c','g','g','i','i','o','o','s','s','u','u','i','&#60','&#62','s','s','i','','','i','','','-','','','','','','');
        $new_text = str_replace($search,$replace,$text);
        $new_text2 = str_replace("__","_",$new_text);
        return strtolower($new_text2);
    }
    
    function seo_nav_url($text) 
    {
        $text = trim($text);
        $search = array(' ','\"','\'','Ç','ç','G','ğ','i','I','Ö','ö','S','s','Ü','ü','I','<','>','Ş','ş','İ','?','\\','ı','&','!',',','-','#39;',',','+','*','\\');
        $replace = array('_','','','c','c','g','g','i','i','o','o','s','s','u','u','i','','','s','s','i','','','i','','','-','','','','','');
        $new_text = str_replace($search,$replace,$text);
        $new_text2 = str_replace("__","_",$new_text);
        return strtolower($new_text2);
    }
    
    
    
    function seo_link($text) 
    {
        $text = trim($text);
        $search = array(' ','\"','\'','Ç','ç','G','ğ','i','I','Ö','ö','S','s','Ü','ü','I','<','>','Ş','ş','İ','?','\\','ı','&','!',',','#39;',',','+','*');
        $replace = array('-','','','c','c','g','g','i','i','o','o','s','s','u','u','i','','','s','s','i','','','i','','','','','','','');
        $new_text = str_replace($search,$replace,$text);
        $new_text2 = str_replace("__","_",$new_text);
        return strtolower($new_text2);
    }
    
    function seo_title($title)
    {
        $titlekirp=$this->kirp($title,6);
        $titleurl=$this->satilik->seo_link($titlekirp);
        return $titleurl;
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
    
    function sadecetarih($tarih)
    {
        $tr_ay=array('Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık');
        $ay=strftime("%m",strtotime($tarih));
        return strftime("%d",strtotime($tarih))." ".$tr_ay[$ay-1]." ".strftime("%Y",strtotime($tarih));
    }
    
    function getLang($lang) {
        $langs=array("","Türkçe","English");
        return $langs[$lang];
    }
    
    function tarihformatla($tarih)
    {
        $tr_ay=array('Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık');
        $ay=strftime("%m",strtotime($tarih));
        return strftime("%d",strtotime($tarih))." ".$tr_ay[$ay-1]." ".strftime("%Y",strtotime($tarih))." ".date('H:i',strtotime($tarih));
    }
    
    function getHowLongAgo($date, $display = array('year', 'month', 'day', 'hour', 'minute', 'second'), $ago = 'ago')
    {
        $date = getdate(strtotime($date));
        $current = getdate();
        $p = array('year', 'mon', 'mday', 'hours', 'minutes', 'seconds');
        $factor = array(0, 12, 30, 24, 60, 60);

        for ($i = 0; $i < 6; $i++) {
            if ($i > 0) {
                $current[$p[$i]] += $current[$p[$i - 1]] * $factor[$i];
                $date[$p[$i]] += $date[$p[$i - 1]] * $factor[$i];
            }
            if ($current[$p[$i]] - $date[$p[$i]] > 1) {
                $value = $current[$p[$i]] - $date[$p[$i]];
                return $value . ' ' . $display[$i] . (($value != 1) ? 's' : '') . ' ' . $ago;
            }
        }

        return 'now';
    }
    
    function auto_link($str, $type = 'both', $attributes = '')
    {
            // MAKE THE THIRD ARGUMENT BACKWARD COMPATIBLE
            // here we deal with the original third argument $pop
            // which could be TRUE or FALSE, and was FALSE by default.
            $pop = '';
            if ($attributes === TRUE)
            {
                $pop = ' target="_blank" ';
                $attributes = '';
            }
            elseif ($attributes === FALSE)
            {
                $pop = $attributes = '';
            }
            
            $pop = ' target="_blank" ';




        if ($type != 'email')
        {

            if (preg_match_all("#(^|\s|\()((http(s?)://)|(www\.))(\w+[^\s\)\<]+)#i", $str, $matches))
            {
                                
                                if( $attributes != '' )
                                {
                                        $attributes = _parse_attributes($attributes);
                                }
                               

                for ($i = 0; $i < count($matches['0']); $i++)
                {
                    $period = '';
                    if (preg_match("|\.$|", $matches['6'][$i]))
                    {
                        $period = '.';
                        $matches['6'][$i] = substr($matches['6'][$i], 0, -1);
                    }





                    $str = str_replace($matches['0'][$i],
                                        $matches['1'][$i].'<a target="_blank" href="http'.
                                        $matches['4'][$i].'://'.
                                        $matches['5'][$i].
                                        $matches['6'][$i].'">http'.
                                        $matches['4'][$i].'://'.
                                        $matches['5'][$i].
                                        $matches['6'][$i].'</a>'.
                                        $period, $str);
                }
            }
        }

        if ($type != 'url')
        {
            if (preg_match_all("/([a-zA-Z0-9_\.\-\+]+)@([a-zA-Z0-9\-]+)\.([a-zA-Z0-9\-\.]*)/i", $str, $matches))
            {
                for ($i = 0; $i < count($matches['0']); $i++)
                {
                    $period = '';
                    if (preg_match("|\.$|", $matches['3'][$i]))
                    {
                        $period = '.';
                        $matches['3'][$i] = substr($matches['3'][$i], 0, -1);
                    }

                    $str = str_replace($matches['0'][$i], safe_mailto($matches['1'][$i].'@'.$matches['2'][$i].'.'.$matches['3'][$i]).$period, $str,$attributes);
                }
            }
        }

        return $str;
    }//EN
    
    function auto_get($str, $type = 'both', $attributes = '')
    {
            // MAKE THE THIRD ARGUMENT BACKWARD COMPATIBLE
            // here we deal with the original third argument $pop
            // which could be TRUE or FALSE, and was FALSE by default.
            $pop = '';
            if ($attributes === TRUE)
            {
                $pop = ' target="_blank" ';
                $attributes = '';
            }
            elseif ($attributes === FALSE)
            {
                $pop = $attributes = '';
            }
            
            $pop = ' target="_blank" ';




        if ($type != 'email')
        {

            if (preg_match_all("#(^|\s|\()((http(s?)://)|(www\.))(\w+[^\s\)\<]+)#i", $str, $matches))
            {
                                
                                if( $attributes != '' )
                                {
                                        $attributes = _parse_attributes($attributes);
                                }
                               

                for ($i = 0; $i < count($matches['0']); $i++)
                {
                    $period = '';
                    if (preg_match("|\.$|", $matches['6'][$i]))
                    {
                        $period = '.';
                        $matches['6'][$i] = substr($matches['6'][$i], 0, -1);
                    }
                    
                    
                    $title=$this->get_title_data($matches[0][0]);
                    $meta=$this->get_meta_data($matches[0][0]);
                    /*$image=$this->get_image($matches[0][0]);
                    echo $image["og:image"];*/
                    $desc=$meta["description"];
                    $image=$meta["image"];
                    $keywords=$meta["keywords"];
                    $str = str_replace($matches['0'][$i],
                                        $matches['1'][$i].'<a target="_blank" href="http'.
                                        $matches['4'][$i].'://'.
                                        $matches['5'][$i].
                                        $matches['6'][$i].'">'.
                                        $title.
                                        '</a>'.
                                        $period, $str);
                }
            }
            
            
        }

        if ($type != 'url')
        {
            if (preg_match_all("/([a-zA-Z0-9_\.\-\+]+)@([a-zA-Z0-9\-]+)\.([a-zA-Z0-9\-\.]*)/i", $str, $matches))
            {
                for ($i = 0; $i < count($matches['0']); $i++)
                {
                    $period = '';
                    if (preg_match("|\.$|", $matches['3'][$i]))
                    {
                        $period = '.';
                        $matches['3'][$i] = substr($matches['3'][$i], 0, -1);
                    }

                    $str = str_replace($matches['0'][$i], safe_mailto($matches['1'][$i].'@'.$matches['2'][$i].'.'.$matches['3'][$i]).$period, $str,$attributes);
                }
            }
        }
        
        $imgtxt="";
        $colspan="";
        if (strlen($image)>0) {
        $imgtxt="<td style=\"vertical-align:top;text-align:left;\" width=\"110\"><img src=\"".$image."\" width=\"100\" /></td>";
        $colspan=" colspan=\"2\"";
        }
        return $str."<div class=\"hiddentrend\"><table width=\"100%\"><tr><td ".$colspan.">
            <b>".$title."</b>
            </td></tr><tr>".$imgtxt."<td style=\"vertical-align:top;\">".$desc."</td></tr></table></div>";
    }//EN
    
    
    function get_title_data($url)
    {
        $title = "";

        urlencode($url);

        $cont = @file_get_contents($url);

        if ($cont != FALSE) {

            $title_exists = preg_match( "/<title>([^>]*)<\/title>/si", $cont, $match );

            if ($title_exists)
            {

                $title = strip_tags(@$match[ 1 ]);

                return $title;

             } else
             {

                return FALSE;

             }

        } else {

            return FALSE;

        }
            
    }

    function get_meta_data($url)
    {
        $meta = array();

        urlencode($url);

        $cont = @file_get_contents($url);
        if ($cont != FALSE) {
            
        $meta_exists = trim(preg_match_all("/<meta[^>]+(name|property|http-equiv)=\"([^\"]*)\"[^>]+content=\"([^\"]*)\"[^>]*>/i", $cont, $out, PREG_PATTERN_ORDER));

        if ( ($meta_exists != FALSE) AND ($meta_exists > 0) )
        {
            for ($i=0;$i < count($out[2]);$i++) {
                $meta[strtolower($out[2][$i])] = $out[3][$i];              
            }

            return $meta;
                
        } else {
                
            return FALSE;
                
        }

        } else {

            return FALSE;

        }
        
    } 
    
    function get_image($url)
    {
        $a = get_meta_tags($url);
        $cont=var_dump($a);
        $meta_exists = trim(preg_match_all('/<meta property="og:image" content="(.*?)" \/>/', $cont, $out, PREG_PATTERN_ORDER));
        if ( ($meta_exists != FALSE) AND ($meta_exists > 0) )
            {
                
                for ($i=0;$i < count($out[1]);$i++) {
                    if (strtolower($out[1][$i]) == "image") $meta['image'] = $out[2][$i];
                }


                foreach ($meta as $key => $value) {
                    $meta[$key] = ( !empty($meta[$key]) ) ? $meta[$key] : $url;
                }                
            }
    } 
    
    function addhttp($url) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        return $url;
    }
}
?>

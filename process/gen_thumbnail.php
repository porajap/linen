<?php
function createthumb($name,$filename,$new_w,$new_h,$color_bg,$output_type,$show,$crop){
    list($old_x,$old_y,$img_type)=getimagesize($name);
    switch($img_type) {
         case IMAGETYPE_JPEG:
            $src_img=imagecreatefromjpeg($name);
            $filename_base=basename($filename,".jpg");
            break;
        case IMAGETYPE_GIF:
            $src_img=imagecreatefromgif($name);
            $filename_base=basename($filename,".gif");
            break;          
        case IMAGETYPE_PNG:
            $src_img=imagecreatefrompng($name);
            $filename_base=basename($filename,".png");
            break;
    }   
 
    $src_ratio=$old_x/$old_y;
    $dst_ratio=$new_w/$new_h;
    if($crop==1){
        $thumb_w=($old_x*$new_h)/$old_y;  
        $thumb_h=$new_h;  
        $co_x=floor(($new_w-$thumb_w)/2);  
        $co_y=0;    
    }else{
        if($old_x<=$new_w && $old_y<=$new_h){
            $thumb_w=$old_x;
            $thumb_h=$old_y;
            $co_x=0;  
            $co_y=0;            
        }elseif($dst_ratio>$src_ratio){
            $thumb_w=(int)($new_h*$src_ratio);
            $thumb_h=$new_h;        
            $co_x=floor(($new_w-$thumb_w)/2);  
            $co_y=0;            
        }else{
            $thumb_w=$new_w;        
            $thumb_h=(int)($new_w/$src_ratio);      
            $co_x=0;  
            $co_y=floor(($new_h-$thumb_h)/2);                   
        }
    }
 
    if(strlen($color_bg)>=6){
        if(strlen($color_bg)==6){
            $R_color=hexdec(substr($color_bg,0,2));
            $G_color=hexdec(substr($color_bg,2,2));
            $B_color=hexdec(substr($color_bg,4,2));
        }else{
            $R_color=hexdec(substr($color_bg,1,2));
            $G_color=hexdec(substr($color_bg,3,2));
            $B_color=hexdec(substr($color_bg,5,2));         
        }
    }
     
    $dst_img=imagecreatetruecolor($new_w,$new_h);       
    if(strlen($color_bg)==0){
        imagecolortransparent($dst_img, $color_bg);
    }else{
        $color_bg=imagecolorallocate($dst_img,$R_color,$G_color,$B_color);
        imagefill($dst_img,0,0,$color_bg);              
    }
    imagecopyresampled($dst_img, $src_img, $co_x, $co_y, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);
 
    if($output_type!=""){
        if($output_type=="gif"){
            $img_type=IMAGETYPE_GIF;
            $filename=$filename_base.".gif";
        }elseif($output_type=="png"){
            $img_type=IMAGETYPE_PNG;
            $filename=$filename_base.".png";
        }else{
            $img_type=IMAGETYPE_JPEG;
            $filename=$filename_base.".jpg";
        }
    }
    switch($img_type) {
        case IMAGETYPE_JPEG:
            if($show==1){
            header('Content-Type: image/jpeg');             
            imagejpeg($dst_img, null, 90); 
            }else{
            imagejpeg($dst_img,$filename,90);   
            }
            break;
        case IMAGETYPE_GIF:
            if($show==1){
            header('Content-Type: image/gif');  
            imagegif($dst_img);             
            }else{      
            imagegif($dst_img,$filename); 
            }
            break;          
        case IMAGETYPE_PNG:
            if($show==1){
            header('Content-Type: image/png');  
            imagepng($dst_img);             
            }else{      
            imagepng($dst_img,$filename); 
            }
            break;
    }   
    imagedestroy($dst_img); 
    imagedestroy($src_img); 
}
?>
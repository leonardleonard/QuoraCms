<?php
// +----------------------------------------------------------------------
// | ThinkPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2008 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

/**
 +------------------------------------------------------------------------------
 * 图像操作类库
 +------------------------------------------------------------------------------
 * @category   ORG
 * @package  ORG
 * @subpackage  Util
 * @author    liu21st <liu21st@gmail.com>
 * @version   $Id$
 +------------------------------------------------------------------------------
 */
class Image
{//类定义开始

    /**
     +----------------------------------------------------------
     * 取得图像信息
     *
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param string $image 图像文件名
     +----------------------------------------------------------
     * @return mixed
     +----------------------------------------------------------
     */
    static function getImageInfo($img) {
        $imageInfo = getimagesize($img);
        if( $imageInfo!== false) {
            $imageType = strtolower(substr(image_type_to_extension($imageInfo[2]),1));
            $imageSize = filesize($img);
            $info = array(
                "width"=>$imageInfo[0],
                "height"=>$imageInfo[1],
                "type"=>$imageType,
                "size"=>$imageSize,
                "mime"=>$imageInfo['mime']
            );
            return $info;
        }else {
            return false;
        }
    }

    /**
     +----------------------------------------------------------
     * 显示服务器图像文件
     * 支持URL方式
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param string $imgFile 图像文件名
     * @param string $text 文字字符串
     * @param string $width 图像宽度
     * @param string $height 图像高度
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    static function showImg($imgFile,$text='',$width=80,$height=30) {
        //获取图像文件信息
        $info = Image::getImageInfo($imgFile);
        if($info !== false) {
            $createFun  =   str_replace('/','createfrom',$info['mime']);
            $im = $createFun($imgFile);
            if($im) {
                $ImageFun= str_replace('/','',$info['mime']);
                if(!empty($text)) {
                    $tc  = imagecolorallocate($im, 0, 0, 0);
                    imagestring($im, 3, 5, 5, $text, $tc);
                }
                if($info['type']=='png' || $info['type']=='gif') {
                imagealphablending($im, false);//取消默认的混色模式
                imagesavealpha($im,true);//设定保存完整的 alpha 通道信息
                }
                Header("Content-type: ".$info['mime']);
                $ImageFun($im);
                @ImageDestroy($im);
                return ;
            }
        }
        //获取或者创建图像文件失败则生成空白PNG图片
        $im  = imagecreatetruecolor($width, $height);
        $bgc = imagecolorallocate($im, 255, 255, 255);
        $tc  = imagecolorallocate($im, 0, 0, 0);
        imagefilledrectangle($im, 0, 0, 150, 30, $bgc);
        imagestring($im, 4, 5, 5, "NO PIC", $tc);
        Image::output($im);
        return ;
    }

    /**
     +----------------------------------------------------------
     * 生成缩略图
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param string $image  原图
     * @param string $type 图像格式
     * @param string $filename 缩略图文件名
     * @param string $maxWidth  宽度
     * @param string $maxHeight  高度
     * @param string $position 缩略图保存目录
     * @param boolean $interlace 启用隔行扫描
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    static function thumb($image,$type='',$filename='',$maxWidth=200,$maxHeight=50,$interlace=true,$suffix='_thumb')
    {

        // 获取原图信息
		
        $info  = Image::getImageInfo($image);
		if($info==false){
			file_put_contents('1.txt','wrong');
			}
         if($info !== false) {
            $srcWidth  = $info['width'];
            $srcHeight = $info['height'];
            $pathinfo = pathinfo($image);
            $type =  $pathinfo['extension'];
            $type = empty($type)?$info['type']:$type;
			$type = strtolower($type);
            $interlace  =  $interlace? 1:0;
            unset($info);
            $scale = min($maxWidth/$srcWidth, $maxHeight/$srcHeight); // 计算缩放比例

            // 缩略图尺寸
            $width  = (int)($srcWidth*$scale);
            $height = (int)($srcHeight*$scale);

            // 载入原图
            $createFun = 'ImageCreateFrom'.($type=='jpg'?'jpeg':$type);
            $srcImg     = $createFun($image);

            //创建缩略图
            if($type!='gif' && function_exists('imagecreatetruecolor'))
                $thumbImg = imagecreatetruecolor($width, $height);
            else
                $thumbImg = imagecreate($width, $height);

            // 复制图片
            if(function_exists("ImageCopyResampled"))
                ImageCopyResampled($thumbImg, $srcImg, 0, 0, 0, 0, $width, $height, $srcWidth,$srcHeight);
            else
                ImageCopyResized($thumbImg, $srcImg, 0, 0, 0, 0, $width, $height,  $srcWidth,$srcHeight);
            if('gif'==$type || 'png'==$type) {
                //imagealphablending($thumbImg, false);//取消默认的混色模式
                //imagesavealpha($thumbImg,true);//设定保存完整的 alpha 通道信息
                $background_color  =  imagecolorallocate($thumbImg,  0,255,0);  //  指派一个绿色
				imagecolortransparent($thumbImg,$background_color);  //  设置为透明色，若注释掉该行则输出绿色的图
            }

            // 对jpeg图形设置隔行扫描
            if('jpg'==$type || 'jpeg'==$type) 	imageinterlace($thumbImg,$interlace);

            // 生成图片
            $imageFun = 'image'.($type=='jpg'?'jpeg':$type);
            $filename  = empty($filename)? substr($image,0,strrpos($image, '.')).$suffix.'.'.$type : $filename;

            $imageFun($thumbImg,$filename);
            ImageDestroy($thumbImg);
            ImageDestroy($srcImg);
            return $filename;
         }
         return false;
    }

    static function output($im,$type='png')
    {
        header("Content-type: image/".$type);
        $ImageFun='Image'.$type;
        $ImageFun($im);
        imagedestroy($im);
    }

}//类定义结束
?>
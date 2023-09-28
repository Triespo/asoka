<?php

/*
 *  Helper class - Functions 
 *
 *  Version 0.0.1
 *
 * 	Created by: Gustavo Real
 * 	Date: 17/03/2014
 */

class Common{

    const CSS   = "css";
    const JS    = "js";
    const IMG   = "img";
    const WEB_SEPARATOR = "/";

    const DAY   = 86400;
    const MONTH = 2592000;
    const YEAR  = 946080000;
        

    static public function css($file) {
        return Yii::app()->request->baseUrl . self::WEB_SEPARATOR . self::CSS . self::WEB_SEPARATOR . $file;
    }

    static public function js($file) {
        return Yii::app()->request->baseUrl . self::WEB_SEPARATOR . self::JS . self::WEB_SEPARATOR . $file;
    }

    static public function img($file) {
        return Yii::app()->request->baseUrl . self::WEB_SEPARATOR . self::IMG . self::WEB_SEPARATOR . $file;
    }

    static public function preffixUrls($url) {
        if (preg_match("#https?://#", $url) === 0) {
            $url = 'http://' . $url;
        }
        return $url;
    }

    static public function logModelErros($model) {
        $ret = '';
        foreach ($model->getErrors() as $e){
            foreach ($e as $err){
                $ret .= $err;
                Yii::log($err, 'trace', 'devel');
            }
        }
        return $ret;
    }

    static public function check_email_address($email) {
        if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
            return false;
        }
        $email_array = explode("@", $email);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
                return false;
            }
        }
        if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) {
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                return false;
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
                    return false;
                }
            }
        }

        return true;
    }

    static public function getEdad($birth)
    {
        if (empty($birth)) {
            return null;
        }

        $fecha_nacimiento = date('d/m/Y', $birth);
        $fecha_actual = date('d/m/Y');

       // separamos en partes las fechas 
       $array_nacimiento = explode ( "/", $fecha_nacimiento ); 
       $array_actual = explode ( "/", $fecha_actual ); 

       $anos =  $array_actual[2] - $array_nacimiento[2]; // calculamos años 
       $meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses 
       $dias =  $array_actual[0] - $array_nacimiento[0]; // calculamos días 

       //ajuste de posible negativo en $días 
       if ($dias < 0) 
       { 
          --$meses; 

          //ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual 
          switch ($array_actual[1]) { 
             case 1: 
                $dias_mes_anterior=31;
                break; 
             case 2:     
                $dias_mes_anterior=31;
                break; 
             case 3:  
                if (bisiesto($array_actual[2])) 
                { 
                   $dias_mes_anterior=29;
                   break; 
                } 
                else 
                { 
                   $dias_mes_anterior=28;
                   break; 
                } 
             case 4:
                $dias_mes_anterior=31;
                break; 
             case 5:
                $dias_mes_anterior=30;
                break; 
             case 6:
                $dias_mes_anterior=31;
                break; 
             case 7:
                $dias_mes_anterior=30;
                break; 
             case 8:
                $dias_mes_anterior=31;
                break; 
             case 9:
                $dias_mes_anterior=31;
                break; 
             case 10:
                $dias_mes_anterior=30;
                break; 
             case 11:
                $dias_mes_anterior=31;
                break; 
             case 12:
                $dias_mes_anterior=30;
                break; 
          } 

          $dias=$dias + $dias_mes_anterior;

          if ($dias < 0)
          {
             --$meses;
             if($dias == -1)
             {
                $dias = 30;
             }
             if($dias == -2)
             {
                $dias = 29;
             }
          }
       }

       //ajuste de posible negativo en $meses 
       if ($meses < 0) 
       { 
          --$anos; 
          $meses=$meses + 12; 
       }

        $edad = "";

        $edad .= $anos . ' año';
        $edad .= ($anos > 1)?'s':'';

        $edad .= ', ';
        $edad .= $meses . ' mes';
        $edad .= ($meses > 1)?'es':'';;
        $edad .= ' y ';
        $edad .= $dias . ' día';
        $edad .= ($dias > 1)?'s':'';


       return $edad;
    }

    function bisiesto($anio_actual){ 
       $bisiesto=false; 
       //probamos si el mes de febrero del año actual tiene 29 días 
         if (checkdate(2,29,$anio_actual)) 
         { 
          $bisiesto=true; 
       } 
       return $bisiesto; 
    }

    static public function resizeOrCrop($photo_file,$newname,$folder,$size,$type='resize',$component='width',$quality=70) {
        try {
            $subdir = $type=='resize' ? 'resized' : 'cropped';
            $dominant = ($component == 'height') ? (Image::HEIGHT) : (Image::WIDTH);
            if (!is_dir($folder . '/'.$subdir)) {
                mkdir($folder . '/'.$subdir, 0777, true);
            }
            if (!is_dir($folder . '/'.$subdir.'/'.$size)) {
                mkdir($folder . '/'.$subdir.'/'.$size, 0777, true);
            }

            $image = Yii::app()->image->load($folder.'/'.$photo_file);
        

            if($type=='resize'){
                $image->resize($size, $size, $dominant)->quality($quality);
            }
            if($type=='crop'){
                $image->resize($size, $size, $dominant)->quality(100);
                $image->crop($size, $size)->quality($quality);
            }

            Common::saveImage($folder,$subdir,$size,$newname,array('image'=>$image));
        } catch (Exception $e) {
            throw $e;
        }
    }

    static public function saveImage($folder,$subdir,$size,$name,$params = null) {
        try {
            $file = null;
            $image = null;
            extract($params, EXTR_IF_EXISTS);

            $saved = false;
            
            $route=$folder;
            if(!empty($subdir)){
                $route .='/'.$subdir;    
            }
            if(!empty($size)){
                $route .='/'.$size;    
            }        
            
            if($file){
                $saved = $file->saveAs($route.'/'.$name);
            }
            if($image){
                $saved = $image->save($route.'/'.$name); 
            }
            return $saved;
        }catch (Exception $e) {
            throw $e;           
        }
    }
}

?>
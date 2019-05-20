<?php

class thumbnail
{
    private $debug= true;
    private $errflag= false;
    private $ext;
    private $origem;
    private $errormsg;
    
    function __construct($imagem, $aprox)
    {
        if (!file_exists($imagem))
        {
            $this->errormsg= "Arquivo não encontrado.";
            return false;
        }
        else
        {
            $this->origem= $imagem;
        }
        if (!$this->ext= $this->getExtension($imagem))
        {
            $this->errormsg= "Tipo de arquivo inválido.";
            return false;
        }
		
        $this->createThumbImg($aprox);
    }
    

    public function getThumbXY($x, $y, $aprox)
    {
         if ($x >= $y)
        {
            if ($x > $aprox)
            {
                $x1= (int)($x * ($aprox/$x));
                $y1= (int)($y * ($aprox/$x));
            }
            else
            {
                $x1= $x;
                $y1= $y;
            }
        }
        else
        {
            if ($y > $aprox)
            {
                $x1= (int)($x * ($aprox/$y));
                $y1= (int)($y * ($aprox/$y));
            }
            else
            {
                $x1= $x;
                $y1= $y;
            }
        }
        $vet= array("x" => $x1, "y" => $y1);
        return $vet;
    }
    

    private function createThumbImg($aprox)
    {
		header('Content-Type: image/png');
        $img_origem= $this->createImg();


        $origem_x= ImagesX($img_origem);
        $origem_y= ImagesY($img_origem);
        

        $vetor= $this->getThumbXY($origem_x, $origem_y, $aprox);
        $x= $vetor['x'];
        $y= $vetor['y'];
        

        $img_final = ImageCreateTrueColor($x, $y);
        ImageCopyResampled($img_final, $img_origem, 0, 0, 0, 0, $x+1, $y+1, $origem_x, $origem_y);
		
        if ($this->ext == "png")
            imagepng($img_final);
        elseif ($this->ext == "gif")
            imagegif($img_final);
        elseif ($this->ext == "jpg")
            imagejpeg($img_final);
			
		imagedestroy($img_final);
    }
    

    private function createImg()
    {

        if ($this->ext == "png")
            $img_origem= imagecreatefrompng($this->origem);
        elseif ($this->ext == "jpg" || $this->ext == "jpeg")
            $img_origem= imagecreatefromjpeg($this->origem);
        elseif ($this->ext == "gif")
            $img_origem= imagecreatefromgif($this->origem);
        elseif ($this->ext == "bmp")
            $img_origem= imagecreatefromwbmp($this->origem);
        return $img_origem;
    }
    

    private function getExtension($imagem)
    {

        $mime= getimagesize($imagem);

        if ($mime[2] == 2)
        {
           $ext= "jpg";
           return $ext;
        }
        else
        if ($mime[2] == 3)
        {
           $ext= "png";
           return $ext;
        }
        else
           return false;
    }
    

    public function getErrorMsg()
    {
        return $this->errormsg;
    }
    
    public function isError()
    {
        return $this->errflag;
    }
}
?>

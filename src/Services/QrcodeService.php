<?php

namespace App\Services;

use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;

class QrcodeService{
    /**
     * @var BuilderInterface
     */
    protected $builder;
    public function __construct(BuilderInterface $builder)
    {
        $this->builder = $builder;

    }
    public function qrcode($query){
        $result=$this-> builder
        ->data($query)
        ->encoding(new Encoding('UTF-8'))
        ->size(400)
        ->margin(10)
        ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
        ->build()
        
        ;
        $namePng = uniqid('','') . '.png';
        $result->saveToFile((\dirname(__DIR__,2).'/public/assets/qrCodes'.$namePng));
        return $result->getDataUri();
    }
}
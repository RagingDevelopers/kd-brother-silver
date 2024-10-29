<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require 'vendor/autoload.php';
use Picqer\Barcode\BarcodeGeneratorHTML;

function generate($barcodeValue = '')
{
	$generator = new BarcodeGeneratorHTML();
	$barcode = $generator->getBarcode($barcodeValue, $generator::TYPE_CODE_128, 2, 45, 'black', true);
	return $barcode;
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH . 'third_party/escpos/autoload.php';
// use Mike42\Escpos\PrintConnectors\FilePrintConnector;
// use Mike42\Escpos\Printer;
// use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
// use Mike42\Escpos\PrintConnectors\BluetoothPrintConnector;

use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;

class ThermalPrinter {

    private $printerMacAddress;

    public function __construct($params) {
        // $this->printerMacAddress = $params;
        $this->printerMacAddress = $params['printerMacAddress'];
    }

    public function printText($text) {
        try {
            // Fetch MAC address
            $macAddress = $this->getMacAddress();
            $printerIp = $this->convertMacToIp($macAddress);

            // Create a connector to the printer
            $connector = new NetworkPrintConnector($printerIp, 9100);
            $printer = new Printer($connector);
            $printer->initialize();
    
            // Print the text
            $printer->text($text);
            $printer->text("==========================\n");
            $printer->text("Doni Was Here!\n");
            $printer->cut();
            $printer->close();
    
            return ['status' => 'success', 'message' => 'Print job sent successfully!'];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
    
    // Function to get the MAC address
    private function getMacAddress() {
        $macAddress = null;
    
        if (file_exists('/proc/net/arp')) {
            $arpTable = file_get_contents('/proc/net/arp');
            $lines = explode("\n", $arpTable);
    
            foreach ($lines as $line) {
                if (strpos($line, '192.168.0.') !== false) { // Adjust based on your network range
                    $parts = preg_split('/\s+/', $line);
                    if (count($parts) >= 4) {
                        $macAddress = $parts[3];
                        break;
                    }
                }
            }
        }
    
        return $macAddress;
    }
    
    // Dummy function to convert MAC to IP, replace with actual implementation
    private function convertMacToIp($macAddress) {
        // You need to implement the actual logic to convert the MAC address to IP address
        return "192.168.0.100"; // Example IP address
    }

}

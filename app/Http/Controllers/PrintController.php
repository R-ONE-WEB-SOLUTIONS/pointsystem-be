<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class PrintController extends Controller
{
    public function printReceipt(Request $request) {
        // Get the content data URL from the request
        $dataUrl = $request->input('content');
    
        // Specify the printer name (replace with your actual printer name)
        $printerName = "XP-58";
    
        try {
            $connector = new WindowsPrintConnector($printerName);
            $printer = new Printer($connector);
    
            // Decode the data URL to get the raw PDF content
            $pdfContent = base64_decode(preg_replace('#^data:application/\w+;base64,#i', '', $dataUrl));
    
            // Send the PDF content to the printer
            $printer->graphics($pdfContent);
    
            // Cut the paper (optional)
            $printer->cut();
    
            // Close the printer connection
            $printer->close();
    
            return response()->json(['message' => 'Printing successful']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    

}

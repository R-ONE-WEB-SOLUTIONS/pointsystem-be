<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class PrintController extends Controller
{
    public function printReceipt(Request $request) {
        // Get the content you want to print from the request or generate it
        $content = $request->input('content');
    
        // Specify the printer name (replace with your actual printer name)
        $printerName = "XP-58";
    
        try {
            $connector = new WindowsPrintConnector($printerName);
            $printer = new Printer($connector);
    
            // Send the content to the printer
            $printer->text($content);
    
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

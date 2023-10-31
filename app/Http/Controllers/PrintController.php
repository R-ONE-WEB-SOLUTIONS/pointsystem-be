<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\Printer;
use Mike42\Escpos\ImagickEscposImage;

use Carbon\Carbon;

class PrintController extends Controller
{


    public function printReceipt(Request $request) {
        $currentDate = Carbon::now();

        // Get the content you want to print from the request or generate it
        // $content = $request->input('content');
        $name = $request->input('name');
        $accountNumber = $request->input('accountNumber');
        $reference_id = $request->input('reference_id');
        $transaction_points = $request->input('transaction_points');
        $transaction_type = $request->input('transaction_type');
        $reciept_amount = $request->input('reciept_amount');
        $previous_balance = $request->input('previous_balance');
        $new_balance = $request->input('new_balance');
        $current_date =  $request->input('current_date');

        // return response()->json(['date' => $current_date]);
        $inputs = [
            'name', 'accountNumber', 'reference_id', 'transaction_points', 'transaction_type',
            'reciept_amount', 'previous_balance', 'new_balance', 'current_date'
        ];
        // $imageUrl = "https://picsum.photos/200/300";

        // return response()->json(['reference id' =>  $reference_id]);
        // Specify the printer name (replace with your actual printer name)
        $printerName = "XP-58";
        try {
            $imagePath = public_path() . '\images\logo.png';
            $connector = new WindowsPrintConnector($printerName);
            $printer = new Printer($connector);
          
            
           
            // $printer->setJustification(Printer::JUSTIFY_CENTER);
            // $printer->bitImage($tux);
            // $imageData = file_get_contents($imageUrl);
            // // Convert the image to the ESC/POS format (this is a hypothetical method)
            // $escPosImage = convertToEscPosFormat($imageData); // 
            // $printer->bitImage($escPosImage); // Print the image using ESC/POS command

              // Loop through each input to print its value
            $printer->setEmphasis(true);
              // Increase font size (2x)
            $printer->setJustification(Printer::JUSTIFY_CENTER);
             $printer->setTextSize(1, 1);
            $printer->text("EUPHORIA"); 
            $printer->selectPrintMode(); // Reset

            $printer->text("\n");
            $printer->setEmphasis(false);
            $printer->feed(1);
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            foreach ($inputs as $input) {
                $value = $request->input($input);
                $printer->text($value);   
                $printer->feed(1);
                $printer->text(str_repeat('-', 32) . "\n");
            }
            $printer->feed(1); //Add space below the contents
            $printer->setEmphasis(true);
            $printer->setJustification(Printer::JUSTIFY_CENTER);

            $printer->text("THANK YOU SO MUCH !!!"); 
            $printer->setEmphasis(false);
            $printer->feed(5); //Add space below the contents
            $printer->cut();
            // Close the printer connection
            $printer->close();
            return response()->json(['message' => 'Printing successful']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
}

}

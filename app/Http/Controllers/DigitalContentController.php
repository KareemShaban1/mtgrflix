<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Omaralalwi\Gpdf\Gpdf;

class DigitalContentController extends Controller
{


    public function downloadInvoice($id, Gpdf $gpdf)
    {
        // Find the order by ID
        $order = Order::with(['items.product', 'options'])->findOrFail($id);
        // return $order;
        // Pass the order to the view
        $html = view('website.pages.invoice', compact('order'))->render();

        // Generate the PDF from the rendered HTML
        $pdfFile = $gpdf->generate($html);

        // Return the PDF as a response
        return response($pdfFile, 200, ['Content-Type' => 'application/pdf']);
    }



    public function generateSecondWayPdf(Gpdf $gpdf)
    {
        $html = view('pdf.example-2')->render();
        $pdfFile = $gpdf->generate($html);
        return response($pdfFile, 200, ['Content-Type' => 'application/pdf']);
    }


    public function downloadDigitalContent($id)
    {
        $order = Order::with(['items.product','user','items.selectedCode'])->findOrFail($id);
        // return $order;
        // Pass the order to the view
        return  view('website.pages.digital-content', compact('order'));
    }
}

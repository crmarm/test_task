<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();

        return view('shop')->with('products', $products);
    }

    public function update(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $price = $request->input('price');
        $quantity = $request->input('quantity');
        $expiration_date = $request->input('expiration_date');

        $errors=[];
       if($quantity >= 0){

           if(isset($id)){
               Product::query()
                   ->where('id',  $id)
                   ->update(['name'=>$name,'quantity'=>$quantity, 'price' =>$price]);

               if($quantity == 0){

                   Mail::send('mail',['name' => $name], function($message) {
                       $message->to('hovhan.hovhan1995@gmail.com');
                       $message->subject('Welcome Mail');
                   });
               }
           }else{
               $products = new Product();
               $products->name = $name;
               $products->price = $price;
               $products->quantity = $quantity;
               $products->expiration_date = $expiration_date;
               $products->save();
           }

       }else{
           $products =Product::all();
           $errors['message'] = 'quantity can not be negative';


           return view('shop', compact(['products','errors']));
       }


        $products =Product::all();

        return view('shop', compact(['products','errors']));
    }

    public function delete($id)
    {
        if($id){
            Product::find($id)->delete();
        }

        $products = Product::all();
        return view('shop')->with('products', $products);
    }

    public function search(Request $request){
        // Get the search value from the request
        $search = $request->input('search');

        // Search in the title and body columns from the posts table
        $products = Product::query()
            ->where('name', 'LIKE', "%{$search}%")
            ->orWhere('price', $search)
            ->orWhere('quantity',$search)
            ->get();

        // Return the search view with the resluts compacted
        return view('shop', compact(['products','search']));
    }


    public function productsExportCsv(Request $request)
    {

        $search = $request->search;
        if($search){
            $products = Product::query()
                ->where('name', 'LIKE', "%{$search}%")
                ->orWhere('price', $search)
                ->orWhere('quantity',$search)
                ->get();
        }else{
            $products = Product::all();;
        }

        $fileName = 'tasks.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Name', 'Price', 'Quantity', 'Expiration Date');

        $callback = function() use($products, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($products as $product) {
                $row['Name']  = $product->name;
                $row['Price']    = $product->price;
                $row['Quantity']    = $product->quantity;
                $row['Expiration_date']    = $product->expiration_date;

                fputcsv($file, array($row['Name'], $row['Price'], $row['Quantity'], $row['Expiration_date']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);

    }

}

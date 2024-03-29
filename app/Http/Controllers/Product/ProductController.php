<?php

namespace App\Http\Controllers\Product;

use Dompdf\Dompdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Access\Gate;
use App\Models\{ OrderProduct, Products, ProductsCategory, Settings };
use Illuminate\Support\Facades\{ Validator, Storage, DB};

class ProductController extends Controller
{
    
    public function __construct(Gate $gate) 
    {
        $gate->define('product', fn($user) => $user->role_id == 1 || $user->role_id == 2);

        $this->middleware('can:product')->except(['edit']);
    }

    public function index() 
    {
        //Variabel For Title Menu
        $store_information = Settings::findOrFail(1);
        $product = Products::with(['category'])->orderBy('id', 'ASC')->get();

        return view('product.inventory.index',compact('product', 'store_information'));
    }

    public function create()
    {
        // $table = DB::table('products')->select(DB::raw('MAX(RIGHT(code_product, 5)) AS code'));
        // $AutoNumber = "";
        // if($table->count()>0){
        //     foreach ($table->get() as $tbl ) {
        //         $tmp = ((int)$tbl->code)+1;
        //         $AutoNumber = sprintf("%05s", $tmp);
        //     }
        // }else{
        //     $AutoNumber = "00001";
        // }

        //Variabel For Title Menu
        $store_information = Settings::findOrFail(1);
        $categories = ProductsCategory::all();

        // Auto Number Function Start // 
        $generateCode = 'PDK-'.date('Ymd').rand(1111,9999);
        // Auto Number End //

        return view('product.inventory.create', compact(['store_information','categories', 'generateCode']));
    }

    public function store(Request $request) 
    {    
        $rules = [
        'code_product' => 'required|string|max:255|unique:products,code_product',
        'name' => 'required|string|max:255|unique:products,name',
        'category_id' => 'required|integer|exists:products_categories,id',
        'image' => 'file|image|mimes:jpg,png|max:1024',
        'stok' => 'required',
        'price_buy' => 'nullable|integer',
        'price_sell' => 'required|integer',
        'desc' => 'nullable'
        ];

        $eMessage = [
        'code_product.required' => 'Kolom Produk Tidak Boleh Kosong !',
        'code_product.unique' => 'Kode Produk Sudah Digunakan !',
        'name.required' => 'Nama Produk Harus Diisi !',
        'name.unique' => 'Nama Produk Sudah Ada !',
        'category_id.required' => 'Pilih Kategori !',
        'image.image' => 'Gambar Harus Berupa File Image !',
        'image.mimes' => 'Gambar Yang Diupload Hanya Berupa JPG & PNG !',
        'image.max' => 'Ukuran Gambar Max 1mb !',
        'stok.required' => 'Stok Tidak Boleh Kosong !',
        'price_sell.required' => 'Harga Jual Tidak Boleh Kosong ! ',
        ];

        $this->validate($request, $rules, $eMessage);

        // $validator = Validator::make($request->all(), $rules, $eMessage);

        // if ($validator->fails()){
        //     return redirect()->back()->with('warning', $validator->errors()->first());
        // }

        $product = new Products;
        $product->code_product = $request->code_product;
        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->stok = $request->stok;
        $product->price_buy = $request->price_buy ?? 0;
        $product->price_sell = $request->price_sell;
        $product->desc = $request->desc;
        
        if ($request->hasFile('image')){
            $product->image= Storage::disk('public')->putFile('product', $request->file('image'));
        }else{
            $product->image= 'product/default.png';
        }

        $product->save();

        return redirect()->route('product.index')
                    ->with('success', 'Berhasil Menambahkan Produk Baru !');
    }

    public function update(Request $request, Products $product)
    {
        if ($request->code_product != $product->code_product){
            $code_product = ['required|string|max:255|unique:products,code_product'];
        }else{
            $code_product = ['required'];
        }

        if ($request->name != $product->name){
            $name = ['required', 'string', 'max:255', 'unique:products,name'];
        }else{
            $name = ['required'];
        }

        $rules = [
        'code_product' => $code_product,
        'name' => $name,
        'category_id' => 'required|integer|exists:products_categories,id',
        'image' => 'file|image|mimes:jpg,png|max:1024',
        'stok' => 'required',
        'price_buy' => 'nullable|integer',
        'price_sell' => 'required|integer',
        'desc' => 'nullable'
        ];

        $eMessage = [
        'code_product.required' => 'Kolom Produk Tidak Boleh Kosong !',
        'code_product.unique' => 'Kode Produk Sudah Digunakan !',
        'name.required' => 'Nama Produk Harus Diisi !',
        'name.unique' => 'Nama Produk Sudah Ada !',
        'category_id.required' => 'Pilih Kategori !',
        'image.image' => 'Gambar Harus Berupa File Image !',
        'image.mimes' => 'Gambar Yang Diupload Hanya Berupa JPG & PNG !',
        'image.max' => 'Ukuran Gambar Max 1mb !',
        'stok.required' => 'Stok Tidak Boleh Kosong !',
        'price_buy' => 'Harga Beli Tidak Boleh Kosong !',
        'price_sell' => 'Harga Jual Tidak Boleh Kosong ! ',
        ];

        $validator = Validator::make($request->all(), $rules, $eMessage);

        if ($validator->fails()){
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $product->code_product = $request->code_product;
        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->stok = $request->stok;
        $product->price_buy = $request->price_buy ?? 0;
        $product->price_sell = $request->price_sell;
        $product->desc = $request->desc;

        if ($request->hasFile('image')){
            if ($product->image != 'product/default.png'){
                Storage::disk('public')->delete($product->image);
            }
            $product->image = Storage::disk('public')->putFile('product', $request->file('image'));
        }

        if (!$product->isDirty()){
            return redirect()->back()->with('warning', 'Tidak ada data yang diubah !');
        }

        $product->update();

        return redirect()->route('product.show', $product->id)->with('success', 'Produk berhasil diubah !');
    }

    public function show(Products $product)
    {
        //Variabel For Title Menu
        $store_information = Settings::findOrFail(1);
        $categories = ProductsCategory::all();

        return view('product.inventory.show', compact('store_information','product', 'categories'));
    }

    public function destroy(Products $product)
    {  
        $count = $product->orderProduct->count();
        
        if ($count > 0){
            return redirect()->back()->with('info', "Tidak dapat menghapus produk, terdapat {$count} produk di dalam order produk!");
        }

        if ($product->image != 'product/default.png') {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('product.index')->with('success', 'Produk berhasil dihapus !');
    }

    public function deleteSelected(Request $request)
    {
        $rules = ['id' => 'required'];
        $eMessage = ['id.required' => 'Pilih produk yang ingin dihapus!'];
        $validator = Validator::make($request->all(), $rules, $eMessage);

        if ($validator->fails()) {
            return redirect()->back()->with('info', $validator->errors()->first());
        }

        $products = Products::withCount('orderProduct')->whereIn('id', $request->id)->get();

        foreach ($products as $product) {
			if ($product->order_product_count > 0) {
				return redirect()->back()->with('info', "Tidak dapat menghapus produk {$product->name}, terdapat {$product->order_product_count} total order di dalam produk tersebut!");
			}
        }

        foreach ($products as $product) {
            if ($product->image != 'product/default.png') {
                Storage::disk('public')->delete($product->image);
            }
            $product->delete();
        }
        return redirect()->route('product.index')->with('success', 'Produk dipilih berhasil dihapus !');
    }

    public function print() 
    {
        //Variabel For Title Menu
        $store_information = Settings::findOrFail(1);

        $product = Products::with(['category'])->orderBy('id', 'desc')->get();
        
        return view('product.inventory.print', compact('store_information','product'));
    }

    // public function printPDF() 
    // {
    //     $store_information = Settings::findOrFail(1);
    //     $product = Products::with(['category'])->orderBy('id', 'desc')->get();
    //     $html = view('product.inventory.reports', compact('product','store_information'));

    //     // instantiate and use the dompdf class
    //     $dompdf = new Dompdf();
    //     $dompdf->loadHtml($html);

    //     // (Optional) Setup the paper size and orientation
    //     $dompdf->setPaper('A4', 'landscape');

    //     // Render the HTML as PDF
    //     $dompdf->render();

    //     // Output the generated PDF to Browser
    //     $dompdf->stream();
    // }
}

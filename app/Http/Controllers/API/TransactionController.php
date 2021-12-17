<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\CategoryDetail;
use App\Models\Transaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Validator;

class TransactionController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function getAllProduct() {
        $productAll = Product::all();
        return response()->json(['success' => true, 'hasil' => $productAll]);
    }

    public function getAllCategory() {
        $categoryAll = Category::all();
        return response()->json(['success' => true, 'hasil' => $categoryAll]);
    }

    public function getAllCategoryDetail() {
        $categoryDetailAll = CategoryDetail::all();
        return response()->json(['success' => true, 'hasil' => $categoryDetailAll]);
    }

    public function getAllTransactionOnUser(Request $request) {
        $transactionAll = Transaction::where('id_user_transaction', $request->id_user_transaction)->get();
        return response()->json(['success' => true, 'hasil' => $transactionAll]);
    }

    // public function getOneTransactionOnIDTrans(Request $request) {
    //     $transactionOne = Transaction::where('id_transaction', $request->id_transaction)->get();
    //     return response()->json(['success' => true, 'hasil' => $transactionOne]);
    // }

    public function addTransaction(Request $request) {
        date_default_timezone_set("Asia/Makassar");
        $transaction = new Transaction;
        $transaction->id_user_transaction = $request->id_user_transaction;
        $transaction->id_product_transaction = $request->id_product_transaction;
        $transaction->start_transaction = $request->start_transaction;
        $transaction->end_transaction = $request->end_transaction;
        $transaction->total_product = $request->total_product;
        $transaction->total_transaction = $request->total_transaction;
        $transaction->rating = $request->rating;
        $transaction->status_rating_transaction = $request->status_rating_transaction;
        $transaction->status_transaction = $request->status_transaction;
        $transaction->date_transaction = $request->date_transaction;
        $transaction->status_payment = $request->status_payment;
        $transaction->deadline_payment = $request->deadline_payment;
        $transaction->proof = $request->proof;
        $transaction->save();
        return response()->json(['success' => true, 'hasil' => $transaction]);
    }

    public function updateBuktiBayar(Request $request) {
        date_default_timezone_set("Asia/Makassar");
        $transaction = Transaction::find($request->id_transaction);

        if($request->img !=''){
            $image = time().'.jpg';
            if($transaction->proof){
                // unlink('images/'.$transaction->proof);
                Storage::delete('public/proof/'.$transaction->proof);
            }
            $gambardecode = str_replace('data:image/jpeg;base64,', '', $request->img);
            $gambardecode = str_replace('data:image/png;base64,', '', $gambardecode);
            $gambardecode = str_replace(' ', '+', $gambardecode);
            $gambardecode = base64_decode($gambardecode);
            // $path = $gambardecode->storeAs('images/', $image);
            Storage::disk('local')->put('public/proof/'.$image, ($gambardecode));
            // file_put_contents('images/'.$image, $gambardecode);
            $transaction->status_payment = "Proof Uploaded";
            $transaction->proof = $image;
            $transaction->save();
        }
        
        return response()->json(['success' => true, 'message' => 'Berhasil Mengupload Bukti Bayar',]);
    }

    public function transactionDelete(Request $request) {
        $transaction = Transaction::find($request->id_transaction);
        if($transaction->proof){
            Storage::delete('public/proof/'.$transaction->proof);
        }
        $transaction->delete();
        return response()->json(['success' => true, 'message' => 'Transaction successfully deleted.']);
    }

    public function getImageTransaction(Request $request) {
        $transaction = Transaction::find($request->id_transaction);
        $path = Storage::get('public/proof/'.$transaction->proof);
        return response()->json(['success' => true, 'hasil' => base64_encode($path)]);
    }

    public function getImageProduct(Request $request) {
        $pImage = ProductImage::where('id_products', $request->id_products)->get();
        foreach($pImage as $k => $v) {
            $a[] = base64_encode(Storage::get('public/product_image/'.$v->image));
        }
        return response()->json(['success' => true, 'hasil' => $a]);
    }

    public function getAllImageProduct() {
        DB::statement("SET SQL_MODE=''");
        $pImage = ProductImage::groupBy('id_products')->get();
        foreach($pImage as $k => $v) {
            $b[] = $v->id_products;
            $a[] = base64_encode(Storage::get('public/product_image/'.$v->image));
        }
        return response()->json(['success' => true, 'id' => $b, 'hasil' => $a]);
    }

    // public function getOneProduct(Request $request) {
    //     $productOne = Product::find($request->id_product);
    //     return response()->json(['success' => true, 'hasil' => $productOne]);
    // }

    // public function getProductOnCategory(Request $request) {
    //     $productCat = CategoryDetail::where('id_category', '=', $result->id_category);
    //     return response()->json(['success' => true, 'hasil' => $productCat]);
    // }

    // public function getProductPerCategory(Request $request) {
    //     $productOne = Product::where('id', '=', $result);
    //     return response()->json(['hasil' => $productOne]);
    // }

}
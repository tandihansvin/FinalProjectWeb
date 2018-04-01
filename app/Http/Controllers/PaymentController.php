<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Veritrans\Veritrans;
use Illuminate\Support\Facades\Auth;
use App\Cart;

class PaymentController extends Controller
{
    public function __construct()
    {
        Veritrans::$serverKey = "SB-Mid-server-J6qeaaIi6VWSSC4sq7i9tJc5";
        Veritrans::$isProduction = false;

        $this->middleware('auth:api');
    }


    public function createTransaction(Request $request){
        /*
        1. Get Cart
        2. Get SKU objects
        3. Validate
            - Qty
        4. Do the magic
        5. Decrese product qty
        */

        $cart_items = Cart::where('user_id', $request->user()->id)->get();

        foreach ($cart_items as $item) {
            $item->getSKU();
        }

        unset($sku);

        dd($cart_items);

        $vt = new Veritrans();

        $transaction_details = array(
            'order_id'          => uniqid(),
            'gross_amount'  => 200000
        );
        // Populate items
        $items = [
            array(
                'id'                => 'item1',
                'price'         => 100000,
                'quantity'  => 1,
                'name'          => 'Adidas f50'
            ),
            array(
                'id'                => 'item2',
                'price'         => 50000,
                'quantity'  => 2,
                'name'          => 'Nike N90'
            )
        ];
        // Populate customer's billing address
        $billing_address = array(
            'first_name'        => "Andri",
            'last_name'         => "Setiawan",
            'address'           => "Karet Belakang 15A, Setiabudi.",
            'city'                  => "Jakarta",
            'postal_code'   => "51161",
            'phone'                 => "081322311801",
            'country_code'  => 'IDN'
        );
        // Populate customer's shipping address
        $shipping_address = array(
            'first_name'    => "John",
            'last_name'     => "Watson",
            'address'       => "Bakerstreet 221B.",
            'city'              => "Jakarta",
            'postal_code' => "51162",
            'phone'             => "081322311801",
            'country_code'=> 'IDN'
        );
        // Populate customer's Info
        $customer_details = array(
            'first_name'            => "Andri",
            'last_name'             => "Setiawan",
            'email'                     => "andrisetiawan@asdasd.com",
            'phone'                     => "081322311801",
            'billing_address' => $billing_address,
            'shipping_address'=> $shipping_address
        );
        // Data yang akan dikirim untuk request redirect_url.
        // Uncomment 'credit_card_3d_secure' => true jika transaksi ingin diproses dengan 3DSecure.
        $transaction_data = array(
            'payment_type'          => 'vtweb',
            'vtweb'                         => array(
                //'enabled_payments'    => [],
                'credit_card_3d_secure' => true
            ),
            'transaction_details'=> $transaction_details,
            'item_details'           => $items,
            'customer_details'   => $customer_details
        );

        try
        {
            $vtweb_url = $vt->vtweb_charge($transaction_data);
            return response()->json([
                'success' => true,
                'redirect' => $vtweb_url
            ], 200);
        }
        catch (Exception $e)
        {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage
            ], 400);
        }
    }
}

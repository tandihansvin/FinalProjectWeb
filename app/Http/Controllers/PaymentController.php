<?php

namespace App\Http\Controllers;

use App\Address;
use App\StatusChangeHistory;
use Exception;
use Illuminate\Http\Request;
use App\Veritrans\Veritrans;
use Illuminate\Support\Facades\Auth;
use App\Cart;
use App\TransactionHeader;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PaymentController extends Controller
{
    public function __construct()
    {
        Veritrans::$serverKey = "SB-Mid-server-J6qeaaIi6VWSSC4sq7i9tJc5";
        Veritrans::$isProduction = false;

        $this->middleware('auth:api')->except('callback');
    }


    public function createTransaction(Request $request){
        /*
        1. Get Cart
        2. Get SKU objects
        3. Validate
            - Qty
        4. Do the magic
        5. Decrese product qty

        user_id, address_id, total, time
        request = address_id
        */
        $total = 0;
        $cart_items = Cart::where('user_id', auth('api')->user()->id)->get();

        foreach ($cart_items as $item) {
            $item->sku;
            $total += $item->qty * $item->sku['price'];
        }

        //bikin header
        $head = TransactionHeader::create([
            'user_id' => auth('api')->user()->id,
            'address_id' => $request->address_id,
            'total' => $total,
            'time' => date("Y-m-d H:i:s")
        ]);

        //bikin status changes
        //header, status, time
        $statusChange = StatusChangeHistory::create([
            'header_id' => $head->id,
            'status_id' => 1,
            'time' => date("Y-m-d H:i:s"),
            "desc" => ''
        ]);

        $vt = new Veritrans();

        $transaction_details = array(
            'order_id'          => $head->id,
            'gross_amount'  => $total
        );
        // Populate items
//        $items = [
//            array(
//                'id'                => 'item1',
//                'price'         => 100000,
//                'quantity'  => 1,
//                'name'          => 'Adidas f50'
//            ),
//            array(
//                'id'                => 'item2',
//                'price'         => 50000,
//                'quantity'  => 2,
//                'name'          => 'Nike N90'
//            )
//        ];
        $items = [];
        foreach($cart_items as $item){
            $tmp = [
                'id' => $item['id'],
                'price' => $item->sku['price'],
                'quantity' => $item['qty'],
                'name' => $item->sku['fullname']
            ];
            array_push($items,$tmp);
        }
        $address = Address::find($request->address_id);
//        return $address;
        // Populate customer's billing
        $billing_address = array(
            'first_name'        => auth('api')->user()->name,
            'last_name'         => "",
            'address'           => $address->address,
            'city'                  => "",
            'postal_code'   => "",
            'phone'                 => $address->phone,
            'country_code'  => 'IDN'
        );
        // Populate customer's shipping address
        $shipping_address = array(
            'first_name'        => auth('api')->user()->name,
            'last_name'         => "",
            'address'           => $address->address,
            'city'                  => "",
            'postal_code'   => "",
            'phone'                 => $address->phone,
            'country_code'  => 'IDN'
        );
        // Populate customer's Info
        $customer_details = array(
            'first_name'            => auth('api')->user()->name,
            'last_name'             => "",
            'email'                     => auth('api')->user()->email,
            'phone'                     => auth('api')->user()->phone,
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

        Cart::where('user_id', auth('api')->user()->id)->delete();

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

    public function callback(Request $request)
    {
        $good_status = ['authorize', 'capture', 'settlement'];
        $wait_status = ['pending'];
        $status = $request->transaction_status;

        try {
            $txn = TransactionHeader::where('id',$request->order_id)->get();
        } catch (Exception $e) {
            return response()->json([ 'error' => 'Order not found' ], 404);
        }

        try {
            $x = $txn[0]->statusChange()->latest('time')->firstOrFail();
            $last = $x->status->id;
        } catch (Exception $e) {
            return response()->json([ 'error' => 'Transaction is invalid' ], 404);
        }

        if($last == 1 and !in_array($status, $wait_status)){
            $statusid = 1;

            if(in_array($status, $good_status)) {
                $statusid = 2;
            } else {
                $statusid = 7;
            }

            StatusChangeHistory::create([
                'time' => date("Y-m-d H:i:s"),
                'header_id' => $txn[0]->id,
                'status_id' => $statusid,
                'desc' => $request->payment_type . ' ' . $status
            ]);
        }

        return response()->json([], 200);
    }
}

<?php

namespace App\Models\Front\Checkout\Payment;

require_once app_path('Helpers/Kekspay/autoload.php');

use App\Models\Back\Orders\Order;
use App\Models\Back\Orders\Transaction;
use Carbon\Carbon;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/**
 * Class Corvus
 * @package App\Models\Front\Checkout\Payment
 */
class Keks
{

    /**
     * @var Order
     */
    private $order;

    private $env;


    /**
     * @var string[]
     */
    private $url = [
        'test' => [
            'action' => 'https://ewa.erstebank.hr/eretailer',
            'deep_link' => 'https://kekspay.hr/pay'
        ],
        'live' => [
            'action' => 'https://kekspayuat.erstebank.hr/eretailer',
            'deep_link' => 'https://kekspay.hr/galebpay'
        ]
    ];


    /**
     * Keks constructor.
     *
     * @param $order
     */
    public function __construct($order = null)
    {
        $this->order = $order;
        $this->env   = (env('APP_ENV') == 'production') ? 'live' : 'test';
    }


    /**
     * @param Collection|null $payment_method
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function resolveFormView(Collection $payment_method = null, array $options = null)
    {
        if ( ! $payment_method) {
            return '';
        }

        Log::info($payment_method);
        Log::info($options);

        $payment_method = $payment_method->first();

        $order_id   = isset($options['order_number']) ? $options['order_number'] : $this->order->id;
        $total      = number_format(isset($options['total']) ? $options['total'] : $this->order->total, 2, '.', '');
        $store_name = rawurlencode(env('APP_NAME'));

        $data['action']      = $this->url[$this->env]['action'];
        $data['deep_link']   = $this->url[$this->env]['deep_link'];
        $data['logo']        = asset('media/img/keks-logo.svg');
        $data['success_url'] = route('checkout.success');
        $data['fail_url']    = route('checkout.error');

        $data['qr_code']     = 1;
        $data['cid']         = $payment_method->data->cid;
        $data['tid']         = $payment_method->data->tid;
        $data['bill_id']     = $payment_method->data->cid . time() . $order_id;
        $data['amount']      = $total;
        $data['store']       = $store_name;
        $data['order_id']    = $order_id;

        $qr_options = new QROptions([
            'version'          => 6,
            'quietzoneSize'    => 4,
            'eccLevel'         => QRCode::ECC_L,
            'imageTransparent' => false,
        ]);

        $qr_data = [
            "qr_type" => 1,
            "cid"     => $payment_method->data->cid,
            "tid"     => $payment_method->data->tid,
            "bill_id" => $payment_method->data->cid . time() . $order_id,
            "amount"  => $total,
            "store"   => $store_name,
        ];

        $qr_code = new QRCode($qr_options);

        $data['qr_img'] = $qr_code->render(json_encode($qr_data));

        Log::info($data);

        return view('front.checkout.payment.keks', compact('data'));
    }


    public function check(Request $request)
    {
        $request->validate([
            'order_id' => 'required'
        ]);

        $json['status'] = 0;

        $this->order = Order::query()->find($request->input('order_id'));

        if ($this->order) {
            if ($this->order['order_status_id'] != config('settings.order.status.unfinished')) {
                $json['redirect'] = route('kosarica');
                $json['status']   = 1;

                if ($this->order['order_status_id'] == config('settings.order.new_status')) {
                    $json['redirect'] = route('checkout.success');
                }
            }
        }

        Log::info($this->order);
        Log::info($json);

        return response()->json($json);
    }


    /**
     * @param Order $order
     * @param null  $request
     *
     * @return bool
     */
    public function finishOrder(Order $order, Request $request): bool
    {

        $status = ($request->has('approval_code') && $request->input('approval_code')!= null) ? config('settings.order.status.paid') : config('settings.order.status.declined');


        $order->update([
            'order_status_id' => $status
        ]);

        if ($request->has('approval_code')) {
            Transaction::insert([
                'order_id'        => $request->input('order_number'),
                'success'         => 1,
              /*  'amount'          => $request->input('Amount'),
                'signature'       => $request->input('Signature'),
                'payment_type'    => $request->input('PaymentType'),
                'payment_plan'    => $request->input('PaymentPlan'),
                'payment_partner' => $request->input('Partner'),
                'datetime'        => $request->input('DateTime'),
                'approval_code'   => $request->input('ApprovalCode'),
                'pg_order_id'     => $request->input('CorvusOrderId'),
                'lang'            => $request->input('Lang'),
                'stan'            => $request->input('STAN'),
                'error'           => $request->input('ErrorMessage'),*/
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now()
            ]);

            return true;
        }

        Transaction::insert([
            'order_id'        => $request->input('order_number'),
            'success'         => 0,
          /*  'amount'          => $request->input('Amount'),
            'signature'       => $request->input('Signature'),
            'payment_type'    => $request->input('PaymentType'),
            'payment_plan'    => $request->input('PaymentPlan'),
            'payment_partner' => null,
            'datetime'        => $request->input('DateTime'),
            'approval_code'   => $request->input('ApprovalCode'),
            'pg_order_id'     => null,
            'lang'            => $request->input('Lang'),
            'stan'            => null,
            'error'           => $request->input('ErrorMessage'),*/
            'created_at'      => Carbon::now(),
            'updated_at'      => Carbon::now()
        ]);

        return false;
    }

}

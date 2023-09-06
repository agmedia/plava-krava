<?php

namespace App\Http\Controllers\Front;

use App\Helpers\Session\CheckoutSession;
use App\Http\Controllers\Controller;
use App\Mail\OrderReceived;
use App\Mail\OrderSent;
use App\Models\Back\Settings\Settings;
use App\Models\Front\AgCart;
use App\Models\Front\Checkout\Order;
use App\Models\TagManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use SoapClient;
use \stdClass;

class CheckoutController extends Controller
{

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function cart(Request $request)
    {
        $gdl = TagManager::getGoogleCartDataLayer($this->shoppingCart()->get());

        return view('front.checkout.cart', compact('gdl'));
    }


    /**
     * @param Request $request
     * @param string  $step
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function checkout(Request $request)
    {
        $step = '';

        if ($request->has('step')) {
            $step = $request->input('step');
        }

        $is_free_shipping = (config('settings.free_shipping') < $this->shoppingCart()->get()['total']) ? true : false;

        return view('front.checkout.checkout', compact('step', 'is_free_shipping'));
    }


    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function view(Request $request)
    {
        $data = $this->checkSession();

        if (empty($data)) {
            if ( ! session()->has(config('session.cart'))) {
                return redirect()->route('kosarica');
            }

            return redirect()->route('naplata', ['step' => 'podaci']);
        }

        $data = $this->collectData($data, config('settings.order.status.unfinished'));

        $order = new Order();

        if (CheckoutSession::hasOrder()) {
            $data['id'] = CheckoutSession::getOrder()['id'];

            $order->updateData($data);
            $order->setData($data['id']);

        } else {
            $order->createFrom($data);
        }

        if ($order->isCreated()) {
            CheckoutSession::setOrder($order->getData());
        }

        $uvjeti = DB::table('pages')
            ->select('description')
            ->whereIn('id', [6])
            ->get();

        $data['payment_form'] = $order->resolvePaymentForm();

        return view('front.checkout.view', compact('data','uvjeti'));
    }


    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function order(Request $request)
    {
        $order = new Order();

        if ($request->has('provjera')) {
            $order->setData($request->input('provjera'));
        }

        if ($request->has('order_number')) {
            $order->setData($request->input('order_number'));
        }

        if ($order->finish($request)) {
            return redirect()->route('checkout.success');
        }

        return redirect()->route('checkout.error');
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function success(Request $request)
    {
        $data['order'] = CheckoutSession::getOrder();

        if ( ! $data['order']) {
            return redirect()->route('index');
        }

        $order = \App\Models\Back\Orders\Order::where('id', $data['order']['id'])->first();

        if ($order) {
            dispatch(function () use ($order) {
                Mail::to(config('mail.admin'))->send(new OrderReceived($order));
                Mail::to($order->payment_email)->send(new OrderSent($order));
            });

            foreach ($order->products as $product) {
                $real = $product->real;

                if ($real->decrease) {
                    $real->decrement('quantity', $product->quantity);

                    if ( ! $real->quantity) {
                        $real->update([
                            'status' => 0
                        ]);
                    }
                }
            }



            // Sent labels to gls
            try
            {

                if ($order['payment_code'] == 'cod') {

                        $mani = $order['total'];
                        $mani = number_format((float)$mani, 2, '.', '');

                } else {
                    $mani = 0;
                }

                //These parameters are needed to be optimalise depending on the environment:
                ini_set('memory_limit','1024M');
                ini_set('max_execution_time', 600);

                //Test ClientNumber:
                $clientNumber = 383013700; //!!!NOT FOR CUSTOMER TESTING, USE YOUR OWN, USE YOUR OWN!!!
                //Test username:
                $username = "marko@plavakrava.hr"; //!!!NOT FOR CUSTOMER TESTING, USE YOUR OWN, USE YOUR OWN!!!
                //Test password:
                $pwd = "PK13pk14#"; //!!!NOT FOR CUSTOMER TESTING, USE YOUR OWN, USE YOUR OWN!!!
                $password = hash('sha512', $pwd, true);

                $brojracuna = $order['id'];

                $parcels = [];
                $parcel = new StdClass();
                $parcel->ClientNumber = $clientNumber;
                $parcel->ClientReference = $brojracuna;
                $parcel->CODAmount = $mani;
                $parcel->CODReference = $brojracuna;
               // $parcel->Content = "CONTENT";
                $parcel->Count = 1;
                $deliveryAddress = new StdClass();
                $deliveryAddress->ContactEmail = $order['payment_email'];
                $deliveryAddress->ContactName = $order['payment_fname'].' '.$order['payment_lname'];
                $deliveryAddress->ContactPhone = $order['payment_phone'];
                $deliveryAddress->Name =  $order['payment_fname'].' '.$order['payment_lname'];
                $deliveryAddress->Street =  $order['payment_address'];
                $deliveryAddress->HouseNumber = "";
                $deliveryAddress->City = $order['payment_city'];
                $deliveryAddress->ZipCode = $order['payment_zip'];
                $deliveryAddress->CountryIsoCode = "HR";
                $deliveryAddress->HouseNumberInfo = "/b";
                $parcel->DeliveryAddress = $deliveryAddress;
                $pickupAddress = new StdClass();
                $pickupAddress->ContactName = "Ines Draganić";
                $pickupAddress->ContactPhone = "+38512132487";
                $pickupAddress->ContactEmail = "webshop@plavakrava.hr";
                $pickupAddress->Name = "Plava krava izdavaštvo";
                $pickupAddress->Street = "Nova cesta";
                $pickupAddress->HouseNumber = "150";
                $pickupAddress->City = "Zagreb";
                $pickupAddress->ZipCode = "10000";
                $pickupAddress->CountryIsoCode = "HR";
                $pickupAddress->HouseNumberInfo = "/a";
                $parcel->PickupAddress = $pickupAddress;
                $parcel->PickupDate = date('Y-m-d');
               /* $service1 = new StdClass();
                $service1->Code = "PSD";
                $parameter1 = new StdClass();
                $parameter1->StringValue = "2351-CSOMAGPONT";
                $service1->PSDParameter = $parameter1;
                $services = [];
                $services[] = $service1;
                $parcel->ServiceList = $services;*/

                $parcels[] = $parcel;

                //The service URL:
                $wsdl = "https://api.mygls.hr/SERVICE_NAME.svc?singleWsdl";

                $soapOptions = array('soap_version'   => SOAP_1_1, 'stream_context' => stream_context_create(array('ssl' => array('cafile' => 'cacert.pem'))));

                //Parcel service:
                $serviceName = "ParcelService";

                $this->PrepareLabels($username,$password,$parcels,str_replace("SERVICE_NAME",$serviceName,$wsdl),$soapOptions,$order);


            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }


            //gls kraj

            CheckoutSession::forgetOrder();
            CheckoutSession::forgetStep();
            CheckoutSession::forgetPayment();
            CheckoutSession::forgetShipping();
            $this->shoppingCart()->flush();

            $data['google_tag_manager'] = TagManager::getGoogleSuccessDataLayer($order);

            return view('front.checkout.success', compact('data'));
        }

        return redirect()->route('front.checkout.checkout', ['step' => '']);
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function error()
    {
        return view('front.checkout.error');
    }


    /*******************************************************************************
     *                                Copyright : AGmedia                           *
     *                              email: filip@agmedia.hr                         *
     *******************************************************************************/

    /**
     * @return array
     */
    private function checkSession(): array
    {
        if (CheckoutSession::hasAddress() && CheckoutSession::hasShipping() && CheckoutSession::hasPayment()) {
            return [
                'address'  => CheckoutSession::getAddress(),
                'shipping' => CheckoutSession::getShipping(),
                'payment'  => CheckoutSession::getPayment()
            ];
        }

        return [];
    }


    /**
     * @param array $data
     * @param int   $order_status_id
     *
     * @return array
     */
    private function collectData(array $data, int $order_status_id): array
    {
        $shipping = Settings::getList('shipping')->where('code', $data['shipping'])->first();
        $payment  = Settings::getList('payment')->where('code', $data['payment'])->first();

        $response                    = [];
        $response['address']         = $data['address'];
        $response['shipping']        = $shipping;
        $response['payment']         = $payment;
        $response['cart']            = $this->shoppingCart()->get();
        $response['order_status_id'] = $order_status_id;

        return $response;
    }


    /**
     * @return AgCart
     */
    private function shoppingCart(): AgCart
    {
        if (session()->has(config('session.cart'))) {
            return new AgCart(session(config('session.cart')));
        }

        return new AgCart(config('session.cart'));
    }

    /*
 * Label(s) generation by the service.
 */
    private function PrintLabels($username,$password,$parcels,$wsdl,$soapOptions)
    {
        //Test request:
        $printLabelsRequest = array('Username' => $username,
            'Password' => $password,
            'ParcelList' => $parcels);

        $request = array ("printLabelsRequest" => $printLabelsRequest);

        //Service client creation:
        $client = new SoapClient($wsdl,$soapOptions);

        //Service calling:
        $response = $client->PrintLabels($request);

        if($response != null && count((array)$response->PrintLabelsResult->PrintLabelsErrorList) == 0 && $response->PrintLabelsResult->Labels != "")
        {
            //Label(s) saving:

            $this->response->setOutput(json_encode('OK'));
        }
    }

    /*
    * Preparing label(s) by the service.
    */
    private  function PrepareLabels($username,$password,$parcels,$wsdl,$soapOptions,$order)
    {
        //Test request:
        $prepareLabelsRequest = array('Username' => $username,
            'Password' => $password,
            'ParcelList' => $parcels);

        $request = array ("prepareLabelsRequest" => $prepareLabelsRequest);

        //Service client creation:
        $client = new SoapClient($wsdl,$soapOptions);

        //Service calling:
        $response = $client->PrepareLabels($request);

        $parcelIdList = [];
        if($response != null && count((array)$response->PrepareLabelsResult->PrepareLabelsError) == 0 && count((array)$response->PrepareLabelsResult->ParcelInfoList) > 0)
        {
            $parcelIdList[] = $response->PrepareLabelsResult->ParcelInfoList->ParcelInfo->ParcelId;
            $order->update(['printed', 1]);

        }

        //Test request:
        $getPrintedLabelsRequest = array('Username' => $username,
            'Password' => $password,
            'ParcelIdList' => $parcelIdList,
            'PrintPosition' => 1,
            'ShowPrintDialog' => 0);

        return $getPrintedLabelsRequest;
    }

    /*
    * Get label(s) by the service.
    */
    private  function GetPrintedLabels($wsdl,$soapOptions,$getPrintedLabelsRequest)
    {
        $request = array ("getPrintedLabelsRequest" => $getPrintedLabelsRequest);

        //Service client creation:
        $client = new SoapClient($wsdl,$soapOptions);

        //Service calling:
        $response = $client->GetPrintedLabels($request);

        if($response != null && count((array)$response->GetPrintedLabelsResult->GetPrintedLabelsErrorList) == 0 && $response->GetPrintedLabelsResult->Labels != "")
        {
            //Label(s) saving:
            file_put_contents('php_soap_client_GetPrintedLabels.pdf', $response->GetPrintedLabelsResult->Labels);
        }
    }

    /*
    * Get parcel(s) information by date ranges.
    */
    private  function GetParcelList($username,$password,$wsdl,$soapOptions)
    {
        //Test request:
        $getParcelListRequest = array('Username' => $username,
            'Password' => $password,
            'PickupDateFrom' => '2020-04-16',
            'PickupDateTo' => '2020-04-16',
            'PrintDateFrom' => null,
            'PrintDateTo' => null);

        $request = array ("getParcelListRequest" => $getParcelListRequest);

        //Service client creation:
        $client = new SoapClient($wsdl,$soapOptions);

        //Service calling:
        $response = $client->GetParcelList($request);

        var_dump(count((array)$response->GetParcelListResult->GetParcelListErrors));
        var_dump(count((array)$response->GetParcelListResult->PrintDataInfoList));
    }

    /*
    * Get parcel statuses.
    */
    private  function GetParcelStatuses($username,$password,$wsdl,$soapOptions)
    {
        //Test request:
        $getParcelStatusesRequest = array('Username' => $username,
            'Password' => $password,
            'ParcelNumber' => 0,
            'ReturnPOD' => true,
            'LanguageIsoCode' => "HR");

        $request = array ("getParcelStatusesRequest" => $getParcelStatusesRequest);

        //Service client creation:
        $client = new SoapClient($wsdl,$soapOptions);

        //Service calling:
        $response = $client->GetParcelStatuses($request);

        if($response != null )
        {
            var_dump(count((array)$response->GetParcelStatusesResult->GetParcelStatusErrors));
            if(count((array)$response->GetParcelStatusesResult->GetParcelStatusErrors) == 0 && $response->GetParcelStatusesResult->POD != "")
            {
                //POD saving:
                file_put_contents('php_soap_client_GetParcelStatuses.pdf', $response->GetParcelStatusesResult->POD);
            }
        }
    }

}

<?php

namespace App\Http\Controllers\Payment_Methods;

use App\Http\Controllers\Controller;
use App\Models\PaymentRequest;
use App\Traits\Processor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AlfaPaymentController extends Controller
{
    use Processor;
    private $config_values;
    private PaymentRequest $payment;

    public function __construct(PaymentRequest $payment)
    {
        $config = $this->payment_config('ALFA', 'payment_config');
        if (!is_null($config) && $config->mode == 'live') {
            $this->config_values = json_decode($config->live_values);
        } elseif (!is_null($config) && $config->mode == 'test') {
            $this->config_values = json_decode($config->test_values);
        }
        $this->payment = $payment;
    }
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_id' => 'required|uuid'
        ]);

        if ($validator->fails()) {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_400, null, $this->error_processor($validator)), 400);
        }

        $data = $this->payment::where(['id' => $request['payment_id']])->where(['is_paid' => 0])->first();
        if (!isset($data)) {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_204), 200);
        }
        $config = $this->config_values;

        return view('payment.alfa', compact('data', 'config'));
    }

    public function success(Request $request)
    {
        if ($request->TS == 'P' && $request->RC == '00') {
            $this->payment::where(['id' => $request['O']])->update([
                'payment_method' => 'ALFA',
                'is_paid' => 1,
            ]);

            $data = $this->payment::where(['id' => $request['O']])->first();

            if (isset($data) && function_exists($data->success_hook)) {
                call_user_func($data->success_hook, $data);
            }

            return $this->payment_response($data, 'success');
        }
        $payment_data = $this->payment::where(['id' => $request['O']])->first();
        if (isset($payment_data) && function_exists($payment_data->failure_hook)) {
            call_user_func($payment_data->failure_hook, $payment_data);
        }
        return $this->payment_response($payment_data, 'fail');
    }

    public function get_token()
    {
        // generate random transaction/order number
        $transNum = rand(0, 17866120);

        // get AuthToken from AlfaPay API
        $alfa = new Alfapay();
        $response = $alfa->setTransactionReferenceNumber($transNum)->getToken();
        //
        if ($response != null && $response->success == 'true') {
            return $response->AuthToken;
        } else {
            // log error
            if ($response == null) {
                abort(403, 'Error: Timeout connection. Auth Token not generated.');
            } else {
                abort(403, 'Error: ' . $response->ErrorMessage . '. Auth Token does not generated.');
            }
        }
    }

}
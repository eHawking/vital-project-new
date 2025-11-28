<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DspFormulas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DspFormulasController extends Controller
{
    /**
     * Display the DSP Formula page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $formulas = DspFormulas::first();
        $pageTitle = 'DSP Formulas Management';
        return view('admin.dsp_formulas', compact('formulas', 'pageTitle'));
    }

    /**
     * Save or update the DSP Formula.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'dsp_price' => 'required|numeric|min:0',
            'dsp_formula' => 'required|numeric|min:0',
            'direct_reference_formula' => 'required|numeric|min:0',
            'pair_formula' => 'required|numeric|min:0',
            'reward_formula' => 'required|numeric|min:0',
            'weekly_promo_formula' => 'required|numeric|min:0',
            'budget_promo_formula' => 'required|numeric|min:0',
            'event_formula' => 'required|numeric|min:0',
            'visit_outside_formula' => 'required|numeric|min:0',
            'shipping_formula' => 'required|numeric|min:0',
            'office_formula' => 'required|numeric|min:0',
            'it_formula' => 'required|numeric|min:0',
            'pintapay_formula' => 'required|numeric|min:0',
            'products_formula' => 'required|numeric|min:0',
            'bbs_formula' => 'required|numeric|min:0',
            'student_formula' => 'required|numeric|min:0',
            'extra_formula' => 'required|numeric|min:0',
            'company_formula' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Calculate the total of all formulas
        $totalFormulas = $request->dsp_formula +
            $request->direct_reference_formula +
            $request->pair_formula +
            $request->reward_formula +
            $request->weekly_promo_formula +
            $request->budget_promo_formula +
            $request->event_formula +
            $request->visit_outside_formula +
            $request->shipping_formula +
            $request->office_formula +
            $request->it_formula +
            $request->pintapay_formula +
            $request->products_formula +
            $request->bbs_formula +
            $request->student_formula +
            $request->extra_formula +
            $request->company_formula;

        // Check if the total matches the DSP price
        if ($totalFormulas != $request->dsp_price) {
            $notify[] = ['error', 'The sum of all formulas does not match the DSP price.'];
            return back()->withNotify($notify)->withInput();
        }

        // Save or update the DSP Formula
        $formulas = DspFormulas::firstOrNew();
        $formulas->fill($request->all());
        $formulas->save();

        $notify[] = ['success', 'DSP Formulas saved successfully.'];
        return back()->withNotify($notify);
    }
}
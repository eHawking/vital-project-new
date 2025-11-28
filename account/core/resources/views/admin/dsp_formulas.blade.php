@extends('admin.layouts.app')

@section('panel')
    <div class="row gy-4">
        <div class="col-xxl-3 col-sm-6">
            <x-widget 
                value="{{ showAmount($formulas->dsp_price ?? 0) }}" 
                title="DSP Price" 
                style="6" 
                icon="las la-dollar-sign" 
                bg="primary" 
                outline="false" />
        </div>

        <div class="col-xxl-3 col-sm-6" id="total-formulas-card">
            <x-widget 
                value="{{ showAmount($totalFormulas ?? 0) }}" 
                title="Total Formulas" 
                style="6" 
                icon="las la-calculator" 
                bg="success" 
                outline="false" />
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <form action="{{ route('admin.dsp.formulas.save') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('DSP Price')</label>
                            <input type="number" step="any" name="dsp_price" class="form-control" value="{{ $formulas->dsp_price ?? 0 }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('DSP Formula')</label>
                            <input type="number" step="any" name="dsp_formula" class="form-control" value="{{ $formulas->dsp_formula ?? 0 }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Direct Reference Formula')</label>
                            <input type="number" step="any" name="direct_reference_formula" class="form-control" value="{{ $formulas->direct_reference_formula ?? 0 }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Pair Formula')</label>
                            <input type="number" step="any" name="pair_formula" class="form-control" value="{{ $formulas->pair_formula ?? 0 }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Reward Formula')</label>
                            <input type="number" step="any" name="reward_formula" class="form-control" value="{{ $formulas->reward_formula ?? 0 }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Weekly Promo Formula')</label>
                            <input type="number" step="any" name="weekly_promo_formula" class="form-control" value="{{ $formulas->weekly_promo_formula ?? 0 }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Budget Promo Formula')</label>
                            <input type="number" step="any" name="budget_promo_formula" class="form-control" value="{{ $formulas->budget_promo_formula ?? 0 }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Event Formula')</label>
                            <input type="number" step="any" name="event_formula" class="form-control" value="{{ $formulas->event_formula ?? 0 }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Visit Outside Formula')</label>
                            <input type="number" step="any" name="visit_outside_formula" class="form-control" value="{{ $formulas->visit_outside_formula ?? 0 }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Shipping Formula')</label>
                            <input type="number" step="any" name="shipping_formula" class="form-control" value="{{ $formulas->shipping_formula ?? 0 }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Office Formula')</label>
                            <input type="number" step="any" name="office_formula" class="form-control" value="{{ $formulas->office_formula ?? 0 }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('IT Formula')</label>
                            <input type="number" step="any" name="it_formula" class="form-control" value="{{ $formulas->it_formula ?? 0 }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('PintaPay Formula')</label>
                            <input type="number" step="any" name="pintapay_formula" class="form-control" value="{{ $formulas->pintapay_formula ?? 0 }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Products Formula')</label>
                            <input type="number" step="any" name="products_formula" class="form-control" value="{{ $formulas->products_formula ?? 0 }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('BBS Formula')</label>
                            <input type="number" step="any" name="bbs_formula" class="form-control" value="{{ $formulas->bbs_formula ?? 0 }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Student Formula')</label>
                            <input type="number" step="any" name="student_formula" class="form-control" value="{{ $formulas->student_formula ?? 0 }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Extra Formula')</label>
                            <input type="number" step="any" name="extra_formula" class="form-control" value="{{ $formulas->extra_formula ?? 0 }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Company Formula')</label>
                            <input type="number" step="any" name="company_formula" class="form-control" value="{{ $formulas->company_formula ?? 0 }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn--primary w-100 h-45">@lang('Save')</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script')
<script>
    (function($) {
        "use strict";

        // This function will run when the script loads and whenever an input changes.
        function calculateAndDisplayTotal() {
            let total = 0;

            // Select all number inputs inside the form, EXCEPT for the 'dsp_price' field.
            const formulaInputs = document.querySelectorAll('form input[type="number"]:not([name="dsp_price"])');

            // Loop through each formula input field.
            formulaInputs.forEach(input => {
                // Convert the input's value to a number. If it's empty or not a number, it becomes 0.
                const value = parseFloat(input.value) || 0;
                total += value;
            });

            // Find the element that displays the total value. 
            // We assume the x-widget component renders the value inside an <h3> tag.
            // This selector finds the h3 inside the card we identified earlier.
            const totalDisplayElement = document.querySelector('#total-formulas-card h3');

            // If the element is found, update its text with the new total.
            if (totalDisplayElement) {
                // .toFixed(2) formats the number to two decimal places.
                totalDisplayElement.innerText = total.toFixed(2);
            }
        }

        // --- Event Listeners ---

        // Find all the formula inputs again.
        const allFormulaInputs = document.querySelectorAll('form input[type="number"]:not([name="dsp_price"])');

        // Attach an event listener to each input.
        // The 'input' event fires immediately whenever the value changes.
        allFormulaInputs.forEach(input => {
            input.addEventListener('input', calculateAndDisplayTotal);
        });

        // Run the calculation once when the page loads to ensure the initial total is correct.
        document.addEventListener('DOMContentLoaded', calculateAndDisplayTotal);

    })(jQuery);
</script>
@endpush
@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.dsp.bonuses.update') }}" method="POST">
                    @csrf
                    <div class="row">
                        @foreach($bonuses as $bonus)
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label fw-bold">
                                        {{-- Format the bonus_type for display, ensuring DSP is uppercase --}}
                                        {{ __(str_replace('Dsp', 'DSP', Str::title(str_replace('_', ' ', $bonus->bonus_type)))) }}
                                    </label>
                                    <div class="input-group">
                                        <input class="form-control" type="number" step="any" name="bonuses[{{ $bonus->id }}][amount]" value="{{ getAmount($bonus->bonus_amount) }}" required />
                                        <span class="input-group-text">{{ __(gs()->cur_text) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

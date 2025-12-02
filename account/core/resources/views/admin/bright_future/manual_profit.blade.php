@extends('admin.layouts.app')

@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.bright.future.manual.profit.submit') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>@lang('Username')</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>@lang('Profit Amount')</label>
                                <div class="input-group">
                                    <input type="number" name="amount" step="any" class="form-control" required>
                                    <span class="input-group-text">{{ gs('cur_text') }}</span>
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>@lang('Date and Time')</label>
                                <input type="datetime-local" name="date" class="form-control" required>
                            </div>
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

@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.bright.future.manual.profit.submit') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="required">@lang('Username')</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="required">@lang('Profit Amount')</label>
                                <div class="input-group">
                                    <input type="number" step="any" name="amount" class="form-control" required>
                                    <span class="input-group-text">{{ gs('cur_text') }}</span>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="required">@lang('Date')</label>
                                <input type="date" name="date" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="required">@lang('Time')</label>
                                <input type="time" name="time" class="form-control" required>
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

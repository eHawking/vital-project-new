@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.bonus.management.update') }}" method="POST">
                    @csrf
                    <div class="row">
                        {{-- highlight-start --}}
                        @forelse($bonuses as $index => $bonus)
                            <div class="col-md-12">
                                <div class="card mb-4">
                                    <div class="card-header bg--primary">
                                        <h5 class="card-title text-white">{{ __(keyToTitle($bonus->bonus_type)) }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <input type="hidden" name="bonus_type[]" value="{{ $bonus->bonus_type }}">
                                        <div class="form-group">
                                            <label>@lang('Description')</label>
                                            <input type="text" class="form-control" name="description[]" value="{{ $bonus->description }}" placeholder="@lang('Enter bonus description')">
                                        </div>
                                        <div class="form-group">
                                            <label>@lang('Statement')</label>
                                            <input type="text" class="form-control" name="statement[]" value="{{ $bonus->statement }}" placeholder="@lang('Enter bonus statement')">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-md-12">
                                <p class="text-center">@lang('No bonus management records found.')</p>
                            </div>
                        @endforelse
                        {{-- highlight-end --}}
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
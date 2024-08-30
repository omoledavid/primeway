@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--lg table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('Name') | @lang('Calling Codes')</th>
                                    <th>@lang('ISO') | @lang('Continent')</th>
                                    <th>@lang('Currency Name') | @lang('Currency Code')</th>
                                    <th>@lang('Currency Symbol')</th>
                                    <th>@lang('Total Operator')</th>
                                    <th>@lang('Status')</th>
                                    @can(['admin.airtime.country.status', 'admin.airtime.operators'])
                                        <th>@lang('Action')</th>
                                    @endcan
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($countries as $country)
                                    <tr>
                                        <td>
                                            <div>
                                                {{ __($country->name) }} <br />
                                                {{ implode(', ', $country->calling_codes) }}
                                            </div>
                                        </td>
                                        <td>
                                            <div>{{ $country->iso_name }} <br> {{ __($country->continent) }}</div>
                                        </td>
                                        <td>
                                            <div>
                                                {{ __($country->currency_name) }} <br>
                                                {{ $country->currency_code }}
                                            </div>
                                        </td>
                                        <td>{{ $country->currency_symbol }}</td>
                                        <td>
                                            {{$country->operators_count}}
                                        </td>
                                        <td>
                                            @php echo $country->statusBadge @endphp
                                        </td>
                                        @can(['admin.airtime.country.status', 'admin.airtime.operators'])
                                            <td>
                                                <div class="button--group">
                                                    @can('admin.airtime.operators')
                                                        <a href="{{ route('admin.airtime.operators', $country->iso_name) }}" class="btn btn-outline--dark btn-sm"><i
                                                                class="las la-list"></i>@lang('Operators')</a>
                                                    @endcan
                                                    @can('admin.airtime.country.status')
                                                        @if ($country->status == Status::ENABLE)
                                                            <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                                                data-action="{{ route('admin.airtime.country.status', $country->id) }}"
                                                                data-question="@lang('Are you sure to disable this country?')">
                                                                <i class="las la-eye-slash"></i>@lang('Disable')
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-sm btn-outline--success confirmationBtn"
                                                                data-action="{{ route('admin.airtime.country.status', $country->id) }}"
                                                                data-question="@lang('Are you sure to enable this country?')">
                                                                <i class="las la-eye"></i> @lang('Enable')
                                                            </button>
                                                        @endif
                                                    @endcan
                                                </div>
                                            </td>
                                        @endcan

                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($countries->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($countries) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @can('admin.airtime.country.status')
        <x-confirmation-modal />
    @endcan
@endsection

@push('breadcrumb-plugins')
    @can('admin.airtime.fetch.countries')
        <a href="{{ route('admin.airtime.fetch.countries') }}" class="btn btn--dark"> <i class="lab la-telegram-plane"></i>
            @if ($countries->count())
                @lang('Fetch More Countries')
            @else
                @lang('Fetch Countries')
            @endif
        </a>
    @endcan
    <x-search-form placeholder="Name / ISO / Code" />
@endpush

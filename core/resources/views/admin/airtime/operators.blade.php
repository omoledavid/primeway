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
                                    <th>@lang('Name') | @lang('Country')</th>
                                    <th>@lang('Rate')</th>
                                    <th>@lang('Bundle')</th>
                                    <th>@lang('Data')</th>
                                    <th>@lang('Pin')</th>
                                    <th>@lang('Denomination Type')</th>
                                    <th>@lang('Commission')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($operators as $operator)
                                    <tr>
                                        <td>
                                            <div>
                                                {{ __($operator->name) }} <br>
                                                <b>{{ __($operator->country->name) }}</b>
                                            </div>
                                        </td>
                                        <td>1 {{ $operator->sender_currency_code }} = {{ getAmount($operator->fx->rate, 3) }} {{ $operator->fx->currencyCode }}</td>
                                        <td>@php echo showBadge($operator->bundle) @endphp</td>
                                        <td>@php echo showBadge($operator->data) @endphp</td>
                                        <td>@php echo showBadge($operator->pin) @endphp</td>
                                        <td>{{ $operator->denomination_type }}</td>
                                        <td>{{ getAmount($operator->commission) }}%</td>
                                        <td>
                                            @php echo $operator->statusBadge @endphp
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                <button class="btn btn-sm btn-outline--dark detailBtn" data-resource="{{ json_encode($operator) }}"><i class="las la-desktop"></i>@lang('Detail')</button>
                                                @can('admin.airtime.country.status')
                                                    @if ($operator->status == Status::ENABLE)
                                                        <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.airtime.operator.status', $operator->id) }}" data-question="@lang('Are you sure to disable this country?')">
                                                            <i class="las la-eye-slash"></i>@lang('Disable')
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-outline--success confirmationBtn" data-action="{{ route('admin.airtime.operator.status', $operator->id) }}" data-question="@lang('Are you sure to enable this country?')">
                                                            <i class="las la-eye"></i> @lang('Enable')
                                                        </button>
                                                    @endif
                                                @endcan

                                            </div>
                                        </td>
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
                @if ($operators->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($operators) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal" id="infoModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>@lang('Name')</span>
                            <span class="name"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>@lang('Bundle')</span>
                            <span class="bundle"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>@lang('Data')</span>
                            <span class="data"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>@lang('Pin')</span>
                            <span class="pin"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>@lang('Support Local Amount')</span>
                            <span class="supportsLocalAmounts"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>@lang('Support Geographical Recharge Plans')</span>
                            <span class="supportsGeographicalRechargePlans"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>@lang('Denomination Type')</span>
                            <span class="denominationType"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>@lang('Destination Currency Code')</span>
                            <span class="destinationCurrencyCode"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>@lang('Destination Currency Symbol')</span>
                            <span class="destinationCurrencySymbol"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>
                                @lang('Commission')
                                <i class="las la-info-circle text--info" title="@lang('Commissions (%) are automatically calculated and applied to your account using the formula: Balance - Sales + Commissions in Reloadly.')"></i>
                            </span>
                            <span class="commission"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>
                                @lang('International Discount')
                                <i class="las la-info-circle text--info" title="@lang('These are discounts applied when user are making a top-up to a mobile number registered in any country besides the country your Reloadly account.')"></i>
                            </span>
                            <span class="internationalDiscount"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>
                                @lang('Local Discount')
                                <i class="las la-info-circle text--info" title="@lang('These discounts are applicable to top-ups made to a mobile number that is registered in the same country of origin as your Reloadly account.')"></i>
                            </span>
                            <span class="localDiscount"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>
                                @lang('Most Popular Amount')
                                <i class="las la-info-circle text--info" title="@lang('The most popular international top-up amount for this specific operator.')"></i>
                            </span>
                            <span class="mostPopularAmount"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>
                                @lang('Most Popular Local Amount')
                                <i class="las la-info-circle text--info" title="@lang('The most popular local top-up amount for this specific operator.')"></i>
                            </span>
                            <span class="mostPopularLocalAmount"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>
                                @lang('Minimum Amount')
                                <i class="las la-info-circle text--info" title="@lang('If the denomination type is set to a range and users select different origin number from your Reloadly account, they will need to top up at least the minimum amount specified.')"></i>
                            </span>
                            <span class="minAmount"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>@lang('Maximum Amount')
                                <i class="las la-info-circle text--info" title="@lang('If the denomination type is set to a range and users select different origin number from your Reloadly account, they can top up the maximum amount specified.')"></i>
                            </span>
                            <span class="maxAmount"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>
                                @lang('Local Minimum Amount')
                                <i class="las la-info-circle text--info" title="@lang('If the denomination type is set to a range and users select the same origin number as your Reloadly account, they will need to top up at least the minimum amount specified.')"></i>
                            </span>

                            <span class="localMinAmount"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>@lang('Local Max Amount')
                                <i class="las la-info-circle text--info" title="@lang('If the denomination type is set to a range and users select the same origin number as your Reloadly account, they can top up the minimum amount specified.')"></i>
                            </span>
                            <span class="localMaxAmount"></span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>
                                @lang('Foreign Exchange Rate')
                                <i class="las la-info-circle text--info" title="@lang('This exchange rate will be applicable while user select different origin number.')"></i>
                            </span>
                            <span class="fx"></span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>@lang('International Fees')</span>
                            <span class="international_fees"></span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>@lang('International Fees Percentage')</span>
                            <span class="international_fees_percentage"></span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>@lang('Local Fees')</span>
                            <span class="local_fees"></span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>@lang('Local Fees Percentage')</span>
                            <span class="local_fees_percentage"></span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>@lang('Geographical Recharge Plans')</span>
                            <span class="geographicalRechargePlans"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap gap-1 px-0">
                            <span>@lang('Reloadly Status')</span>
                            <span class="status"></span>
                        </li>
                    </ul>

                    <div class="amount_descriptions">
                        <div class="heading">
                            <h6>@lang('Fixed Amounts')</h6>
                        </div>
                        <ul class="list-group list-group-flush fixedAmounts"></ul>
                    </div>
                    <div class="amount_descriptions">
                        <div class="heading">
                            <h6>@lang('Local Fixed Amounts')</h6>
                        </div>
                        <ul class="list-group list-group-flush localFixedAmounts"></ul>
                    </div>

                    <div class="amount_descriptions">
                        <div class="heading">
                            <h6>@lang('Suggested Amounts')</h6>
                        </div>
                        <ul class="list-group list-group-flush suggestedAmounts"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('admin.airtime.operator.status')
        <x-confirmation-modal />
    @endcan
@endsection

@push('breadcrumb-plugins')
    @can('admin.airtime.fetch.operators')
        @if ($iso)
            <a href="{{ route('admin.airtime.fetch.operators', $iso) }}" class="btn btn--dark"> <i class="lab la-telegram-plane"></i>
                @if ($operators->count())
                    @lang('Fetch More Operators')
                @else
                    @lang('Fetch Operators')
                @endif
            </a>
        @endif
    @endcan
    <x-search-form placeholder="Name" />
@endpush

@push('script')
    <script>
        "use strict";

        (function($) {
            $("#check-all").on('click', function() {
                if ($(this).is(':checked')) {
                    $(".operatorId").prop('checked', true);
                    $('.hidden-field').prop('checked', true);
                } else {
                    $(".operatorId").prop('checked', false);
                    $('.hidden-field').prop('checked', false);
                }

                updateDOM();
            });

            $(".operatorId").on('change', function() {
                let operatorId = $(this).data('operator_id');
                let hiddenField = $(`.hidden-field[data-operator_id="${operatorId}"]`);

                if ($(this).is(":checked")) {
                    hiddenField.prop('checked', true);
                } else {
                    hiddenField.prop('checked', false);
                }

                updateDOM();
            })

            function updateDOM() {
                if ($('.operatorId:checked').length > 0) {
                    $('.confirmationBtn').removeClass('d-none');
                } else {
                    $('.confirmationBtn').addClass('d-none');
                }
            }

            $('.detailBtn').on('click', function() {
                let resource = $(this).data('resource');

                let modal = $('#infoModal');
                let senderCur = resource.sender_currency_code;
                let destinationCur = resource.destination_currency_code;

                modal.find('.name').text(resource.name);

                modal.find('.bundle').html(showBadge(resource.bundle));
                modal.find('.data').html(showBadge(resource.data));
                modal.find('.pin').html(showBadge(resource.pin));

                modal.find('.supportsLocalAmounts').html(showBadge(resource.supports_local_amount));

                modal.find('.supportsGeographicalRechargePlans').html(showBadge(resource.supports_geographical_recharge_plans));

                modal.find('.denominationType').text(resource.denomination_type);

                modal.find('.destinationCurrencyCode').text(destinationCur);
                modal.find('.destinationCurrencySymbol').text(resource.destination_currency_symbol);

                modal.find('.commission').text(`${showAmount(resource.commission)}%`);

                modal.find('.internationalDiscount').text(`${showAmount(resource.international_discount)}%`);
                modal.find('.localDiscount').text(`${showAmount(resource.local_discount)}%`);
                modal.find('.mostPopularAmount').text(resource.most_popular_amount ? `${showAmount(resource.most_popular_amount)} ${senderCur}` : '--');
                modal.find('.mostPopularLocalAmount').text(resource.most_popular_local_amount ? `${showAmount(resource.most_popular_local_amount)} ${destinationCur}` : '--');

                modal.find('.minAmount').text(resource.min_amount ? `${showAmount(resource.min_amount)} ${senderCur}` : '--');
                modal.find('.maxAmount').text(resource.max_amount ? `${showAmount(resource.max_amount)} ${senderCur}` : '--');

                modal.find('.localMinAmount').text(resource.local_min_amount ? `${showAmount(resource.local_min_amount)} ${destinationCur}` : '--');
                modal.find('.localMaxAmount').text(resource.local_max_amount ? `${showAmount(resource.local_max_amount)} ${destinationCur}` : '--');

                modal.find('.fx').text(`1 ${senderCur} = ${showAmount(resource.fx.rate)} ${resource.fx.currencyCode}`);

                modal.find('.fixedAmounts').html(showAmountData(resource.fixed_amounts_descriptions, resource.fixed_amounts, senderCur));
                modal.find('.localFixedAmounts').html(showAmountData(resource.local_fixed_amounts_descriptions, resource.local_fixed_amounts, destinationCur));
                modal.find('.suggestedAmounts').html(showAmountData(resource.suggested_amounts_map, resource.suggested_amounts, senderCur));

                modal.find('.international_fees').text(resource.fees.international ? `${showAmount(resource.fees.international)} ${senderCur}` : '--');
                modal.find('.international_fees_percentage').text(resource.fees.internationalPercentage ? `${showAmount(resource.fees.internationalPercentage)}%` : '--');
                modal.find('.local_fees').text(resource.fees.local ? `${showAmount(resource.fees.local)} ${destinationCur}` : '--');
                modal.find('.local_fees_percentage').text(resource.fees.localPercentage ? `${showAmount(resource.fees.localPercentage)}%` : '--');

                modal.find('.geographicalRechargePlans').text(showArrayData(resource.geographical_recharge_plans));
                modal.find('.status').text(resource.reloadly_status);

                modal.find('.modal-title').text(resource.name);
                modal.modal('show');
            });

            function showAmountData(obj, arr, curText) {

                if (obj == null && arr == null) {
                    return '--';
                }

                var html = '';
                if (obj != null && !jQuery.isEmptyObject(obj)) {
                    html += `<li class="list-group-item px-0 d-flex justify-content-between flex-wrap gap-1">
                            <span>@lang('Amount')</span>    
                            <span>@lang('Description')</span>    
                        </li>`;

                    $.each(obj, function(key, value) {
                        html += `<li class="list-group-item px-0 d-flex justify-content-between flex-wrap gap-1">
                                <span>${showAmount(key)} ${curText}</span>    
                                <span>${value}</span>    
                            </li>`;
                    });
                } else if (arr != null && arr.length > 0) {
                    html += `<li class="list-group-item px-0"><span>${arr.join(` ${curText}, `)} ${curText}</span></li>`;

                } else {
                    html = '--';
                }

                return html;
            }


            function showArrayData(arr, curText = null) {
                if (arr == null || arr.length < 1) {

                    return '--';
                }

                var html = arr.join(` ${curText}, `);
                html += ' ' + curText;
                return html;
            }

            function showBadge(status) {
                var cls, badgeText;
                if (status) {
                    cls = 'badge badge--success';
                    badgeText = "@lang('Yes')";

                } else {
                    cls = 'badge badge--danger';
                    badgeText = "@lang('No')";
                }

                return `<span class="${cls}">${badgeText}</span>`;
            }

            function showAmount(amount, delimiter = 2) {
                amount = parseFloat(amount);
                if (amount < 1) {
                    return 0;
                }

                return amount.toFixed(delimiter);
            }
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .amount_descriptions {
            padding: 10px 0;
            border-top: 1px solid #ebebeb;
        }

        .amount_descriptions:last-child {
            border-bottom: none;
        }
    </style>
@endpush

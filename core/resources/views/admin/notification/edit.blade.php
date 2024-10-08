@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-md-12">
            <div class="card overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive table-responsive--sm">
                        <table class="align-items-center table--light table">
                            <thead>
                                <tr>
                                    <th>@lang('Short Code')</th>
                                    <th>@lang('Description')</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @forelse($template->shortcodes as $shortcode => $key)
                                    <tr>
                                        <th><span class="short-codes">@php echo "{{ ".$shortcode." }}"  @endphp</span></th>
                                        <td>{{ __($key) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- card end -->

            <h6 class="mt-4 mb-2">@lang('Global Short Codes')</h6>
            <div class="card overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive table-responsive--sm">
                        <table class="align-items-center table--light table">
                            <thead>
                                <tr>
                                    <th>@lang('Short Code') </th>
                                    <th>@lang('Description')</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @foreach ($general->global_shortcodes as $shortCode => $codeDetails)
                                    <tr>
                                        <td><span class="short-codes">@{{ @php echo $shortCode @endphp }}</span></td>
                                        <td>{{ __($codeDetails) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.setting.notification.template.update', $template->id) }}" method="post">
        @csrf
        <div class="row">
            <div class="@if ($template->push_notification_status == 1) col-md-12 @else col-md-6 @endif">
                <div class="card mt-4">
                    <div class="card-header bg--primary">
                        <h5 class="card-title text-white">@lang('Email Template')</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>@lang('Subject')</label>
                                    <input class="form-control" name="subject" type="text" value="{{ $template->subj }}" placeholder="@lang('Email subject')" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Status') <span class="text-danger">*</span></label>
                                    <input name="email_status" data-height="46px" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Send Email')" data-off="@lang("Don't Send")" type="checkbox" @if ($template->email_status) checked @endif>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Message') <span class="text-danger">*</span></label>
                                    <textarea class="form-control nicEdit" name="email_body" rows="10" placeholder="@lang('Your message using short-codes')">{{ $template->email_body }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="@if ($template->push_notification_status == 1) col-md-6 @else col-md-6 @endif">
                <div class="card mt-4">
                    <div class="card-header bg--primary">
                        <h5 class="card-title text-white">@lang('SMS Template')</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Status') <span class="text-danger">*</span></label>
                                    <input name="sms_status" data-height="46px" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Send SMS')" data-off="@lang("Don't Send")" type="checkbox" @if ($template->sms_status) checked @endif>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Message')</label>
                                    <textarea class="form-control" name="sms_body" rows="10" placeholder="@lang('Your message using short-codes')" required>{{ $template->sms_body }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($template->push_notification_status == 1)
                <div class="col-md-6">
                    <div class="card mt-4">
                        <div class="card-header bg--primary">
                            <h5 class="card-title text-white">@lang('Push Notification Template')</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('Status') <span class="text-danger">*</span></label>
                                        <input name="push_notification_status" data-height="46px" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Send')" data-off="@lang("Don't Send")" type="checkbox" @if ($template->push_notification_status) checked @endif>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('Message')</label>
                                        <textarea class="form-control" name="push_notification_body" rows="10" placeholder="@lang('Your message using short-codes')" required>{{ $template->push_notification_body }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        @can('admin.setting.notification.template.update')
            <button class="btn btn--primary w-100 h-45 mt-4" type="submit">@lang('Submit')</button>
        @endcan
    </form>
@endsection

@can('admin.setting.notification.templates')
    @push('breadcrumb-plugins')
        <x-back route="{{ route('admin.setting.notification.templates') }}" />
    @endpush
@endcan

@extends("emails.site.layout")
@section("email_content")
    @lang('email.hi'),
    <br/><br/>
    @lang('email.supportRequestReceived'):
    <br/><br/>
    <b>@lang('email.name'):</b> {{ $name }}<br/>
    <b>@lang('email.email'):</b> {{ $email }}<br/>
    <b>@lang('email.category'):</b> {{ $category }}<br/>
    <b>@lang('email.details'):</b> {{ $details }}<br/>
    <br/><br/>
@endsection

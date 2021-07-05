@extends("emails.site.layout")
@section("email_content")
    @lang('email.hi') {{ $name }},
    <br/><br/>
    <b>@lang('email.congratulations')!</b> @lang('email.accountCreateMessage'):<br/><br/>
    <b>@lang('email.email'):</b> {{ $email }}<br/>
    <b>@lang('email.password'): </b> (@lang('email.oneYouEnteredAtSignUp'))<br/><br/>
    <b>@lang('email.login'): </b><a href="{{ $login_url }}">@lang('email.clickHere')</a> @lang('email.goToLoginPage').<br/><br/>

    <br/><br/>
@endsection

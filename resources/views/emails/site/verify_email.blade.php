@extends("emails.site.layout")
@section("email_content")
    @lang('email.hi') {{ $name }},<br/>
    <br/>
    @lang('email.verifyEmailAddressText'): <br/><br/>
    <a href="{{ $verify_link }}">{{ $verify_link }}</a><br/><br/>
@endsection
@extends("emails.site.layout")
@section("email_content")
    @lang('email.hi') {{ $name }},
    <br/><br/>
    @lang('email.thankYouForContactText')
    <br/><br/>
@endsection

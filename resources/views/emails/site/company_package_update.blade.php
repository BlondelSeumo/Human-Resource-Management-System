@extends("emails.site.layout")
@section("email_content")
    @lang('email.hi') {{ $name }},
    <br/><br/>
    <b>{{ $company->company_name }}</b> @lang('email.hasChangedThePlanTo') <b>{{ $company->subscriptionPlan->plan_name }} ({{ ucwords($company->package_type) }})</b>:<br/><br/>

    <br/><br/>
@endsection

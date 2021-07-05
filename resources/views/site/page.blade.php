@extends("site.app")
@section("title")
    {{$page->title}} - {{ $setting->main_name }}
@endsection

@section("content")
    <section id="features">
        <div class="col-lg-10 col-lg-offset-1">
            <div class="container">
                <h2>{!!$page->title !!}</h2>

                <div class="row">
                    <div class="col-md-12">
                        {!!  $page->description !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>
    </section>
@endsection

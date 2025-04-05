{{-- resources/views/page.blade.php --}}
@extends('layouts.frontend')

@section('title', $page->title)

@section('meta_description', $page->meta_description)

@section('content')
    <!-- Page Header -->
    <div class="page-header" style="background-image: url('{{ asset('images/page-bg.jpg') }}');">
        <div class="container">
            <h1 data-aos="fade-up">{{ $page->title }}</h1>
            <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $page->title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Content Section -->
    <section class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10" data-aos="fade-up">
                    <div class="page-content">
                        {!! $page->content !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Appointment CTA Section -->
    <section class="section appointment">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Book Your Appointment Today</h2>
                <p>Experience our quality dental care</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8" data-aos="fade-up">
                    <div class="text-center">
                        <h3 class="text-white">Ready to visit Misri Khan Dental Clinic?</h3>
                        <p class="text-white mb-4">Schedule an appointment at any of our branches and let our expert team
                            take care of your oral health needs.</p>
                        <a href="{{ route('appointment.create') }}" class="btn btn-light btn-lg">Book an Appointment Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

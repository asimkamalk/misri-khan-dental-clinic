{{-- resources/views/service-detail.blade.php --}}
@extends('layouts.frontend')

@section('title', $service->name)

@section('meta_description', $service->short_description)

@section('content')
    <!-- Page Header -->
    <div class="page-header" style="background-image: url('{{ asset('images/services-bg.jpg') }}');">
        <div class="container">
            <h1 data-aos="fade-up">{{ $service->name }}</h1>
            <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('services') }}">Services</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $service->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Service Detail Section -->
    <section class="section service-detail">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div data-aos="fade-up">
                        <div class="service-img">
                            @if ($service->image)
                                <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}"
                                    class="img-fluid">
                            @else
                                <img src="{{ asset('images/service-placeholder.jpg') }}" alt="{{ $service->name }}"
                                    class="img-fluid">
                            @endif
                        </div>

                        <h2>{{ $service->name }}</h2>

                        <p>{{ $service->short_description }}</p>

                        <div class="service-description">
                            {!! $service->long_description !!}
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="service-info" data-aos="fade-left">
                        <h3>Service Information</h3>
                        <p>{{ $service->name }} is one of our specialties at Misri Khan Dental Clinic. Our experienced
                            dentists use the latest techniques and equipment to ensure the best results for our patients.
                        </p>
                        <p><strong>Duration:</strong> Varies depending on the complexity</p>
                        <p><strong>Recovery:</strong> Minimal to moderate</p>
                        <p><strong>Available at:</strong> All branches</p>
                    </div>

                    <div class="service-cta" data-aos="fade-left" data-aos-delay="100">
                        <h3>Interested in this service?</h3>
                        <p>Please book an appointment and visit our clinic for a comprehensive consultation. Our dentists
                            will provide you with personalized care and treatment options.</p>
                        <a href="{{ route('appointment.create') }}" class="btn btn-light">Book Appointment</a>
                    </div>

                    <div class="other-services" data-aos="fade-left" data-aos-delay="200">
                        <h3>Other Services</h3>
                        <ul class="list-group">
                            @foreach ($otherServices as $otherService)
                                <li class="list-group-item">
                                    <a
                                        href="{{ route('service.detail', $otherService->id) }}">{{ $otherService->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="section why-us">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Why Choose Our {{ $service->name }} Service</h2>
                <p>Our commitment to excellence ensures the best dental care experience</p>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6" data-aos="fade-up">
                    <div class="icon-box">
                        <div class="icon"><i class="fas fa-user-md"></i></div>
                        <h3 class="title">Expert Specialists</h3>
                        <p class="description">Our team includes specialists with extensive experience in providing
                            {{ $service->name }} treatments.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="icon-box">
                        <div class="icon"><i class="fas fa-cog"></i></div>
                        <h3 class="title">Advanced Techniques</h3>
                        <p class="description">We employ the latest techniques and technologies to ensure optimal results
                            for all our dental procedures.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="icon-box">
                        <div class="icon"><i class="fas fa-smile"></i></div>
                        <h3 class="title">Patient Comfort</h3>
                        <p class="description">We prioritize your comfort throughout the treatment process, offering a
                            relaxing environment and gentle care.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Appointment CTA Section -->
    <section class="section appointment">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="appointment-info">
                        <h3>Book Your {{ $service->name }} Appointment Today</h3>
                        <p>Take the first step towards a healthier smile by scheduling an appointment with our expert dental
                            team. We offer comprehensive dental care for patients of all ages.</p>
                        <ul>
                            <li><i class="fas fa-check-circle"></i> Quick and easy online booking</li>
                            <li><i class="fas fa-check-circle"></i> Choose your preferred branch location</li>
                            <li><i class="fas fa-check-circle"></i> Select your desired date and time</li>
                            <li><i class="fas fa-check-circle"></i> Confirmation sent to your email</li>
                        </ul>
                        <a href="{{ route('appointment.create') }}" class="btn btn-light mt-3">Book an Appointment</a>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <img src="{{ asset('images/appointment-cta.jpg') }}" alt="Book Appointment" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </section>
@endsection

{{-- resources/views/services.blade.php --}}
@extends('layouts.frontend')

@section('title', 'Our Services')

@section('content')
    <!-- Page Header -->
    <div class="page-header" style="background-image: url('{{ asset('images/services-bg.jpg') }}');">
        <div class="container">
            <h1 data-aos="fade-up">Our Dental Services</h1>
            <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Services</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Services Section -->
    <section class="section services">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Our Comprehensive Dental Services</h2>
                <p>We offer a wide range of dental services to take care of all your oral health needs</p>
            </div>

            <div class="row">
                @foreach ($services as $service)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($loop->iteration % 3) * 100 }}">
                        <div class="service-card">
                            <div class="service-img">
                                @if ($service->image)
                                    <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}">
                                @else
                                    <img src="{{ asset('images/service-placeholder.jpg') }}" alt="{{ $service->name }}">
                                @endif
                            </div>
                            <div class="service-content">
                                <h4>{{ $service->name }}</h4>
                                <p>{{ $service->short_description }}</p>
                                <a href="{{ route('service.detail', $service->id) }}"
                                    class="btn btn-sm btn-outline-primary">Learn More</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Our Approach Section -->
    <section class="section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <img src="{{ asset('images/dental-approach.jpg') }}" alt="Our Approach" class="img-fluid rounded">
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="content">
                        <h3>Our Approach to Dental Care</h3>
                        <p>At Misri Khan Dental Clinic, we believe in providing comprehensive and personalized dental care
                            to each patient. Our approach focuses on:</p>
                        <ul>
                            <li>Preventive care to maintain oral health and prevent dental issues</li>
                            <li>Patient education about proper oral hygiene practices</li>
                            <li>Minimally invasive treatments whenever possible</li>
                            <li>Comprehensive treatment plans tailored to individual needs</li>
                            <li>Regular follow-ups to monitor oral health progress</li>
                        </ul>
                        <p>We strive to make your dental visit as comfortable and stress-free as possible, ensuring you
                            receive the highest quality care in a friendly environment.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Our Services Section -->
    <section class="section why-us">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Why Choose Our Dental Services</h2>
                <p>We are committed to providing exceptional dental care that meets your needs</p>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6" data-aos="fade-up">
                    <div class="icon-box">
                        <div class="icon"><i class="fas fa-user-md"></i></div>
                        <h3 class="title">Expert Dental Team</h3>
                        <p class="description">Our team of skilled dentists and dental professionals are committed to
                            delivering exceptional care.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="icon-box">
                        <div class="icon"><i class="fas fa-procedures"></i></div>
                        <h3 class="title">Comprehensive Services</h3>
                        <p class="description">From preventive care to complex dental procedures, we offer all the services
                            you need under one roof.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="icon-box">
                        <div class="icon"><i class="fas fa-microscope"></i></div>
                        <h3 class="title">Advanced Technology</h3>
                        <p class="description">We use state-of-the-art dental equipment and technology to provide accurate
                            diagnoses and effective treatments.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="icon-box">
                        <div class="icon"><i class="fas fa-hand-holding-heart"></i></div>
                        <h3 class="title">Patient-Centered Care</h3>
                        <p class="description">We prioritize your comfort and satisfaction, tailoring our services to meet
                            your specific needs and preferences.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="icon-box">
                        <div class="icon"><i class="fas fa-clinic-medical"></i></div>
                        <h3 class="title">Modern Facilities</h3>
                        <p class="description">Our clinics are equipped with modern amenities and designed to provide a
                            comfortable and relaxing environment.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="icon-box">
                        <div class="icon"><i class="fas fa-calendar-check"></i></div>
                        <h3 class="title">Flexible Scheduling</h3>
                        <p class="description">We offer convenient appointment times, including evenings and weekends, to
                            accommodate your busy schedule.</p>
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
                <p>Take the first step towards a healthier smile</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8" data-aos="fade-up">
                    <div class="text-center">
                        <h3 class="text-white">Ready to experience our quality dental services?</h3>
                        <p class="text-white mb-4">Schedule an appointment at any of our branches and let our expert team
                            take care of your oral health needs.</p>
                        <a href="{{ route('appointment.create') }}" class="btn btn-light btn-lg">Book an Appointment
                            Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

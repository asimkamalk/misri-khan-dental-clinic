{{-- resources/views/home.blade.php --}}
@extends('layouts.frontend')

@section('title', 'Home')

@section('content')
    <!-- Hero Section -->
    <section class="hero" style="background-image: url('{{ asset('images/hero-bg.jpg') }}');">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-10">
                    <div class="hero-content" data-aos="fade-up">
                        <h1>{{ getSetting('site_title', 'Misri Khan Dental Clinic') }}</h1>
                        <p>{{ getSetting('site_description', 'Your Smile, Our Priority - Providing Quality Dental Care Services') }}
                        </p>
                        <div class="d-flex">
                            <a href="{{ route('appointment.create') }}" class="btn btn-primary me-3">Book Appointment</a>
                            <a href="{{ route('services') }}" class="btn btn-outline-primary">Our Services</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="section about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="about-img">
                        <img src="{{ asset('images/about.jpg') }}" alt="About Us" class="img-fluid">
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="content">
                        <h3>Welcome to Misri Khan Dental Clinic</h3>
                        <p>We are dedicated to providing you with the highest quality dental care in a comfortable and
                            friendly environment. Our team of experienced dentists and state-of-the-art facilities ensure
                            that you receive the best treatment for all your dental needs.</p>
                        <p>With multiple branches across the city, we aim to make quality dental care accessible to
                            everyone. Our services range from routine check-ups and cleanings to advanced cosmetic and
                            restorative procedures.</p>
                        <ul>
                            <li>Experienced dental professionals</li>
                            <li>State-of-the-art technology and equipment</li>
                            <li>Comfortable and relaxing environment</li>
                            <li>Affordable treatment options</li>
                            <li>Convenient locations and flexible hours</li>
                        </ul>
                        <a href="{{ route('about') }}" class="btn btn-primary">Learn More About Us</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="section services">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Our Services</h2>
                <p>We offer a wide range of dental services to meet all your oral health needs</p>
            </div>

            <div class="row">
                @foreach ($services as $service)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
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

            <div class="text-center mt-4" data-aos="fade-up">
                <a href="{{ route('services') }}" class="btn btn-primary">View All Services</a>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="section why-us">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Why Choose Us</h2>
                <p>We strive to provide the best dental care experience for our patients</p>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6" data-aos="fade-up">
                    <div class="icon-box">
                        <div class="icon"><i class="fas fa-tooth"></i></div>
                        <h3 class="title">Experienced Dentists</h3>
                        <p class="description">Our team consists of highly qualified and experienced dental professionals
                            committed to providing exceptional care.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="icon-box">
                        <div class="icon"><i class="fas fa-cog"></i></div>
                        <h3 class="title">Modern Technology</h3>
                        <p class="description">We utilize the latest dental technology and equipment to ensure accurate
                            diagnoses and effective treatments.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="icon-box">
                        <div class="icon"><i class="fas fa-smile"></i></div>
                        <h3 class="title">Patient Comfort</h3>
                        <p class="description">Your comfort is our priority. We strive to create a relaxing environment and
                            offer gentle dental care.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="icon-box">
                        <div class="icon"><i class="fas fa-clock"></i></div>
                        <h3 class="title">Flexible Hours</h3>
                        <p class="description">We offer convenient appointment times to fit your busy schedule, including
                            evening and weekend options.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="icon-box">
                        <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
                        <h3 class="title">Multiple Locations</h3>
                        <p class="description">With several branches across the city, quality dental care is always within
                            your reach.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="icon-box">
                        <div class="icon"><i class="fas fa-hand-holding-usd"></i></div>
                        <h3 class="title">Affordable Care</h3>
                        <p class="description">We believe in providing quality dental care at reasonable prices, with
                            various payment options available.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="section testimonials">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Testimonials</h2>
                <p>What our patients say about our dental services</p>
            </div>

            <div class="row">
                @foreach ($testimonials as $testimonial)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                        <div class="testimonial-item">
                            <div class="testimonial-stars">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $testimonial->rating ? '' : '-o' }}"></i>
                                @endfor
                            </div>
                            <div class="testimonial-content">
                                <p>{{ $testimonial->testimonial }}</p>
                            </div>
                            <div class="testimonial-author">
                                <div class="testimonial-img">
                                    @if ($testimonial->client_image)
                                        <img src="{{ asset('storage/' . $testimonial->client_image) }}"
                                            alt="{{ $testimonial->client_name }}">
                                    @else
                                        <img src="{{ asset('images/avatar-placeholder.jpg') }}"
                                            alt="{{ $testimonial->client_name }}">
                                    @endif
                                </div>
                                <div class="author-info">
                                    <h5>{{ $testimonial->client_name }}</h5>
                                    <span>Patient</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Branches Section -->
    <section class="section branches">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Our Branches</h2>
                <p>Visit one of our convenient locations near you</p>
            </div>

            <div class="row">
                @foreach ($branches as $branch)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                        <div class="branch-card">
                            <div class="branch-content">
                                <h4>{{ $branch->name }}</h4>
                                <ul>
                                    <li><i class="fas fa-map-marker-alt"></i> {{ $branch->address }}</li>
                                    <li><i class="fas fa-phone"></i> {{ $branch->phone }}</li>
                                    <li><i class="fas fa-clock"></i>
                                        {{ \Carbon\Carbon::parse($branch->opening_time)->format('h:i A') }} -
                                        {{ \Carbon\Carbon::parse($branch->closing_time)->format('h:i A') }}</li>
                                </ul>
                                <a href="{{ route('appointment.create') }}?branch_id={{ $branch->id }}"
                                    class="btn btn-primary">Book at this Branch</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Appointment CTA Section -->
    <section class="section appointment">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="appointment-info">
                        <h3>Book Your Dental Appointment Today</h3>
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
                    <img src="{{ asset('images/appointment-cta.jpg') }}" alt="Book Appointment"
                        class="img-fluid rounded">
                </div>
            </div>
        </div>
    </section>
@endsection

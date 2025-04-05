{{-- resources/views/about.blade.php --}}
@extends('layouts.frontend')

@section('title', 'About Us')

@section('content')
    <!-- Page Header -->
    <div class="page-header" style="background-image: url('{{ asset('images/about-bg.jpg') }}');">
        <div class="container">
            <h1 data-aos="fade-up">About Misri Khan Dental Clinic</h1>
            <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">About Us</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- About Section -->
    <section class="section about-page">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="about-img">
                        <img src="{{ asset('images/about-main.jpg') }}" alt="About Misri Khan Dental Clinic"
                            class="img-fluid rounded">
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="about-content">
                        <h2>Your Trusted Dental Care Partner</h2>
                        <p>Welcome to Misri Khan Dental Clinic, a leading dental care provider with multiple branches across
                            the city. Since our establishment, we have been committed to delivering exceptional dental
                            services in a comfortable and friendly environment.</p>
                        <p>Our mission is to promote oral health and create beautiful smiles by providing high-quality,
                            comprehensive dental care to patients of all ages. We combine expertise, advanced technology,
                            and genuine care to ensure the best possible experience for our patients.</p>
                        <p>At Misri Khan Dental Clinic, we believe in preventive care and patient education. Our team of
                            experienced dentists and staff work together to develop personalized treatment plans that
                            address each patient's unique needs and goals.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="section">
        <div class="container">
            <div class="about-stats" data-aos="fade-up">
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="stat-item">
                            <div class="stat-number">5+</div>
                            <div class="stat-text">Branches</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="stat-item">
                            <div class="stat-number">15+</div>
                            <div class="stat-text">Experienced Dentists</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="stat-item">
                            <div class="stat-number">10k+</div>
                            <div class="stat-text">Happy Patients</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="stat-item">
                            <div class="stat-number">10+</div>
                            <div class="stat-text">Years of Experience</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Vision and Mission -->
    <section class="section">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Our Vision & Mission</h2>
                <p>Guided by strong values and a commitment to excellence</p>
            </div>

            <div class="row">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="card h-100">
                        <div class="card-body">
                            <h3 class="card-title text-primary mb-4">Our Vision</h3>
                            <p class="card-text">To be the leading dental care provider known for excellence, innovation,
                                and compassionate patient care. We aim to set new standards in dental services by
                                continuously improving our skills, adopting new technologies, and expanding our reach to
                                serve more communities.</p>
                            <p class="card-text">We envision a future where everyone has access to quality dental care and
                                where oral health is recognized as an essential component of overall well-being.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="card h-100">
                        <div class="card-body">
                            <h3 class="card-title text-primary mb-4">Our Mission</h3>
                            <p class="card-text">Our mission is to provide exceptional dental care that improves the oral
                                health and enhances the smiles of our patients. We are committed to:</p>
                            <ul>
                                <li>Delivering high-quality dental services using advanced techniques and technologies</li>
                                <li>Creating a comfortable and welcoming environment for all patients</li>
                                <li>Promoting preventive care through education and regular check-ups</li>
                                <li>Building long-term relationships based on trust and mutual respect</li>
                                <li>Making quality dental care accessible to a wider population through our multiple
                                    branches</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Values -->
    <section class="section why-us">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Our Core Values</h2>
                <p>The principles that guide our practice and patient care</p>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6" data-aos="fade-up">
                    <div class="icon-box">
                        <div class="icon"><i class="fas fa-heart"></i></div>
                        <h3 class="title">Compassion</h3>
                        <p class="description">We treat every patient with kindness, empathy, and understanding, recognizing
                            that dental visits can be stressful for many.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="icon-box">
                        <div class="icon"><i class="fas fa-award"></i></div>
                        <h3 class="title">Excellence</h3>
                        <p class="description">We strive for excellence in everything we do, from the quality of our
                            treatments to the service we provide to our patients.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="icon-box">
                        <div class="icon"><i class="fas fa-lightbulb"></i></div>
                        <h3 class="title">Innovation</h3>
                        <p class="description">We embrace new technologies and techniques to improve our services and
                            provide the best possible care to our patients.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="icon-box">
                        <div class="icon"><i class="fas fa-handshake"></i></div>
                        <h3 class="title">Integrity</h3>
                        <p class="description">We are honest, transparent, and ethical in all our dealings, building trust
                            with our patients and the community.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="icon-box">
                        <div class="icon"><i class="fas fa-users"></i></div>
                        <h3 class="title">Teamwork</h3>
                        <p class="description">We work together collaboratively, combining our skills and expertise to
                            deliver the best possible patient care.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="icon-box">
                        <div class="icon"><i class="fas fa-book-reader"></i></div>
                        <h3 class="title">Continuous Learning</h3>
                        <p class="description">We are committed to ongoing education and professional development to stay
                            at the forefront of dental care.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Team Section -->
    <section class="section team">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Our Expert Team</h2>
                <p>Meet our team of experienced dental professionals</p>
            </div>

            <div class="row">
                @php
                    $doctors = \App\Models\Doctor::where('is_active', true)->orderBy('name')->limit(6)->get();
                @endphp

                @foreach ($doctors as $doctor)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($loop->iteration % 3) * 100 }}">
                        <div class="team-member">
                            <div class="team-img">
                                @if ($doctor->image)
                                    <img src="{{ asset('storage/' . $doctor->image) }}" alt="{{ $doctor->name }}">
                                @else
                                    <img src="{{ asset('images/doctor-placeholder.jpg') }}" alt="{{ $doctor->name }}">
                                @endif
                            </div>
                            <div class="team-info">
                                <h4>Dr. {{ $doctor->name }}</h4>
                                <span>{{ $doctor->specialization }}</span>
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
                        <a href="{{ route('appointment.create') }}" class="btn btn-light btn-lg">Book an Appointment
                            Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

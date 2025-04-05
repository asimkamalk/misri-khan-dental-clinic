{{-- resources/views/contact.blade.php --}}
@extends('layouts.frontend')

@section('title', 'Contact Us')

@section('content')
    <!-- Page Header -->
    <div class="page-header" style="background-image: url('{{ asset('images/contact-bg.jpg') }}');">
        <div class="container">
            <h1 data-aos="fade-up">Contact Us</h1>
            <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Contact</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Contact Section -->
    <section class="section">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Get in Touch</h2>
                <p>We'd love to hear from you. Contact us with any questions or to schedule an appointment.</p>
            </div>

            <div class="row">
                <div class="col-lg-4" data-aos="fade-right">
                    <div class="contact-info">
                        <h3>Contact Information</h3>
                        <p>Feel free to reach out to us through any of the following channels:</p>
                        <ul>
                            <li><i class="fas fa-envelope"></i> <a
                                    href="mailto:{{ getSetting('contact_email', 'info@misrikhandental.com') }}">{{ getSetting('contact_email', 'info@misrikhandental.com') }}</a>
                            </li>
                            <li><i class="fas fa-phone"></i> <a
                                    href="tel:{{ getSetting('contact_phone', '+1234567890') }}">{{ getSetting('contact_phone', '+1234567890') }}</a>
                            </li>
                            <li><i class="fas fa-map-marker-alt"></i>
                                {{ getSetting('contact_address', '123 Dental Street, Medical City, Country') }}</li>
                        </ul>

                        <h3 class="mt-4">Follow Us</h3>
                        <ul class="social-icons">
                            @if (getSetting('facebook'))
                                <li><a href="{{ getSetting('facebook') }}" target="_blank"><i
                                            class="fab fa-facebook-f"></i></a></li>
                            @endif
                            @if (getSetting('twitter'))
                                <li><a href="{{ getSetting('twitter') }}" target="_blank"><i class="fab fa-twitter"></i></a>
                                </li>
                            @endif
                            @if (getSetting('instagram'))
                                <li><a href="{{ getSetting('instagram') }}" target="_blank"><i
                                            class="fab fa-instagram"></i></a></li>
                            @endif
                            @if (getSetting('linkedin'))
                                <li><a href="{{ getSetting('linkedin') }}" target="_blank"><i
                                            class="fab fa-linkedin-in"></i></a></li>
                            @endif
                        </ul>
                    </div>

                    <div class="contact-info mt-4">
                        <h3>Working Hours</h3>
                        <p><strong>Monday - Friday:</strong> 9:00 AM - 7:00 PM</p>
                        <p><strong>Saturday:</strong> 9:00 AM - 5:00 PM</p>
                        <p><strong>Sunday:</strong> Closed</p>
                    </div>
                </div>

                <div class="col-lg-8" data-aos="fade-left">
                    <div class="contact-form">
                        <h3>Send Us a Message</h3>

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('contact.send') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Your Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Your Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('subject') is-invalid @enderror"
                                    id="subject" name="subject" value="{{ old('subject') }}" required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5"
                                    required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Send Message</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="section">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Our Branches</h2>
                <p>Visit one of our convenient locations near you</p>
            </div>

            <div class="row">
                @php
                    $branches = \App\Models\Branch::where('is_active', true)->orderBy('name')->get();
                @endphp

                @foreach ($branches as $branch)
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up"
                        data-aos-delay="{{ ($loop->iteration % 3) * 100 }}">
                        <div class="branch-card h-100">
                            <div class="branch-content">
                                <h4>{{ $branch->name }}</h4>
                                <ul>
                                    <li><i class="fas fa-map-marker-alt"></i> {{ $branch->address }}</li>
                                    <li><i class="fas fa-phone"></i> <a
                                            href="tel:{{ $branch->phone }}">{{ $branch->phone }}</a></li>
                                    @if ($branch->email)
                                        <li><i class="fas fa-envelope"></i> <a
                                                href="mailto:{{ $branch->email }}">{{ $branch->email }}</a></li>
                                    @endif
                                    <li><i class="fas fa-clock"></i>
                                        {{ \Carbon\Carbon::parse($branch->opening_time)->format('h:i A') }} -
                                        {{ \Carbon\Carbon::parse($branch->closing_time)->format('h:i A') }}</li>
                                </ul>
                                <a href="{{ route('appointment.create') }}?branch_id={{ $branch->id }}"
                                    class="btn btn-primary mt-3">Book Appointment</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div id="map" class="mt-4" data-aos="fade-up">
                <!-- Replace with your Google Maps embed code or map component -->
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d387193.305935303!2d-74.25986548248684!3d40.69714941932609!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2sin!4v1617611890541!5m2!1sen!2sin"
                    width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
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

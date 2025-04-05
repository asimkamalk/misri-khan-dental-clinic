{{-- resources/views/appointment/success.blade.php --}}
@extends('layouts.frontend')

@section('title', 'Appointment Booked')

@section('content')
    <!-- Page Header -->
    <div class="page-header" style="background-image: url('{{ asset('images/appointment-bg.jpg') }}');">
        <div class="container">
            <h1 data-aos="fade-up">Appointment Confirmation</h1>
            <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('appointment.create') }}">Appointment</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Confirmation</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Success Section -->
    <section class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card text-center" data-aos="fade-up">
                        <div class="card-body py-5">
                            <div class="mb-4">
                                <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                            </div>
                            <h2 class="card-title mb-4">Thank You for Your Appointment!</h2>
                            <p class="card-text fs-5 mb-4">Your appointment has been successfully booked. We have sent a
                                confirmation email with all the details. Our representative will contact you shortly to
                                confirm your appointment.</p>

                            <div class="alert alert-info mb-4">
                                <p class="mb-0">Please note: Your appointment status is currently <strong>pending</strong>
                                    until confirmed by our team.</p>
                            </div>

                            <div class="d-flex justify-content-center gap-3">
                                <a href="{{ route('home') }}" class="btn btn-primary">Return to Home</a>
                                <a href="{{ route('contact') }}" class="btn btn-outline-primary">Contact Us</a>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="card-header">
                            <h4 class="mb-0">What's Next?</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex">
                                    <div class="text-primary me-3">
                                        <i class="fas fa-envelope-open-text fa-lg"></i>
                                    </div>
                                    <div>
                                        <strong>Check Your Email</strong>
                                        <p class="mb-0">You'll receive a confirmation email with the details of your
                                            appointment.</p>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex">
                                    <div class="text-primary me-3">
                                        <i class="fas fa-phone-alt fa-lg"></i>
                                    </div>
                                    <div>
                                        <strong>Phone Confirmation</strong>
                                        <p class="mb-0">Our staff will call you to confirm your appointment time and
                                            answer any questions.</p>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex">
                                    <div class="text-primary me-3">
                                        <i class="fas fa-clipboard-list fa-lg"></i>
                                    </div>
                                    <div>
                                        <strong>Prepare for Your Visit</strong>
                                        <p class="mb-0">If this is your first visit, please bring any relevant medical
                                            history or previous dental records.</p>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex">
                                    <div class="text-primary me-3">
                                        <i class="fas fa-clock fa-lg"></i>
                                    </div>
                                    <div>
                                        <strong>Arrive 15 Minutes Early</strong>
                                        <p class="mb-0">Please arrive 15 minutes before your scheduled appointment to
                                            complete any necessary paperwork.</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Services Section -->
    <section class="section services bg-light">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Our Dental Services</h2>
                <p>Explore our comprehensive range of dental services</p>
            </div>

            <div class="row">
                @php
                    $services = \App\Models\Service::where('is_active', true)
                        ->orderBy('display_order')
                        ->limit(3)
                        ->get();
                @endphp

                @foreach ($services as $service)
                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
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
@endsection

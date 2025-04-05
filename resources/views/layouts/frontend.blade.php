{{-- resources/views/layouts/frontend.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ getSetting('site_title', 'Misri Khan Dental Clinic') }}</title>
    <meta name="description" content="@yield('meta_description', getSetting('meta_description', 'Your Smile, Our Priority - Providing Quality Dental Care Services'))">
    <meta name="keywords"
        content="{{ getSetting('meta_keywords', 'dental clinic, dentist, teeth, dental care, oral health, dental services') }}">

    <!-- Favicon -->
    @if (getSetting('favicon'))
        <link rel="icon" href="{{ asset('storage/' . getSetting('favicon')) }}" type="image/x-icon">
    @endif

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/frontend.css') }}" rel="stylesheet">

    <!-- Additional CSS -->
    @stack('styles')

    <!-- Google Analytics (if configured) -->
    @if (getSetting('google_analytics'))
        {!! getSetting('google_analytics') !!}
    @endif
</head>

<body>
    <!-- Header -->
    <header id="header" class="header">
        <div class="top-bar">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <ul class="top-info">
                            <li><i class="fas fa-envelope"></i> <a
                                    href="mailto:{{ getSetting('contact_email', 'info@misrikhandental.com') }}">{{ getSetting('contact_email', 'info@misrikhandental.com') }}</a>
                            </li>
                            <li><i class="fas fa-phone"></i> <a
                                    href="tel:{{ getSetting('contact_phone', '+1234567890') }}">{{ getSetting('contact_phone', '+1234567890') }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <ul class="social-icons">
                            @if (getSetting('facebook'))
                                <li><a href="{{ getSetting('facebook') }}" target="_blank"><i
                                            class="fab fa-facebook-f"></i></a></li>
                            @endif
                            @if (getSetting('twitter'))
                                <li><a href="{{ getSetting('twitter') }}" target="_blank"><i
                                            class="fab fa-twitter"></i></a></li>
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
                </div>
            </div>
        </div>

        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    @if (getSetting('logo'))
                        <img src="{{ asset('storage/' . getSetting('logo')) }}"
                            alt="{{ getSetting('site_title', 'Misri Khan Dental Clinic') }}" class="logo">
                    @else
                        <h1 class="text-logo">{{ getSetting('site_title', 'Misri Khan Dental Clinic') }}</h1>
                    @endif
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('services*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('services') }}">Services</a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('about') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('about') }}">About Us</a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('contact') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-appointment" href="{{ route('appointment.create') }}">Book
                                Appointment</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                        <div class="footer-info">
                            @if (getSetting('logo'))
                                <img src="{{ asset('storage/' . getSetting('logo')) }}"
                                    alt="{{ getSetting('site_title', 'Misri Khan Dental Clinic') }}"
                                    class="footer-logo">
                            @else
                                <h3 class="footer-title">{{ getSetting('site_title', 'Misri Khan Dental Clinic') }}
                                </h3>
                            @endif
                            <p class="mt-3">
                                {{ getSetting('site_description', 'Your Smile, Our Priority - Providing Quality Dental Care Services') }}
                            </p>
                            <ul class="footer-contact">
                                <li><i class="fas fa-map-marker-alt"></i>
                                    {{ getSetting('contact_address', '123 Dental Street, Medical City, Country') }}
                                </li>
                                <li><i class="fas fa-phone"></i> <a
                                        href="tel:{{ getSetting('contact_phone', '+1234567890') }}">{{ getSetting('contact_phone', '+1234567890') }}</a>
                                </li>
                                <li><i class="fas fa-envelope"></i> <a
                                        href="mailto:{{ getSetting('contact_email', 'info@misrikhandental.com') }}">{{ getSetting('contact_email', 'info@misrikhandental.com') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                        <h4 class="footer-title">Links</h4>
                        <ul class="footer-links">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('services') }}">Services</a></li>
                            <li><a href="{{ route('about') }}">About Us</a></li>
                            <li><a href="{{ route('contact') }}">Contact</a></li>
                            <li><a href="{{ route('appointment.create') }}">Book Appointment</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                        <h4 class="footer-title">Services</h4>
                        <ul class="footer-links">
                            @php
                                $services = \App\Models\Service::where('is_active', true)
                                    ->orderBy('display_order')
                                    ->limit(5)
                                    ->get();
                            @endphp

                            @foreach ($services as $service)
                                <li><a href="{{ route('service.detail', $service->id) }}">{{ $service->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <h4 class="footer-title">Opening Hours</h4>
                        <div class="opening-hours">
                            <p><strong>Monday - Friday:</strong> 9:00 AM - 7:00 PM</p>
                            <p><strong>Saturday:</strong> 9:00 AM - 5:00 PM</p>
                            <p><strong>Sunday:</strong> Closed</p>
                        </div>

                        <h4 class="footer-title mt-4">Follow Us</h4>
                        <ul class="social-icons">
                            @if (getSetting('facebook'))
                                <li><a href="{{ getSetting('facebook') }}" target="_blank"><i
                                            class="fab fa-facebook-f"></i></a></li>
                            @endif
                            @if (getSetting('twitter'))
                                <li><a href="{{ getSetting('twitter') }}" target="_blank"><i
                                            class="fab fa-twitter"></i></a></li>
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
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <p class="copyright">
                            &copy; {{ date('Y') }} {{ getSetting('site_title', 'Misri Khan Dental Clinic') }}. All
                            rights reserved.
                        </p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-end">
                            {!! getSetting('footer_text', '') !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top" id="backToTop"><i class="fas fa-chevron-up"></i></a>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Custom Script -->
    <script>
        $(document).ready(function() {
            // Initialize AOS animation
            AOS.init({
                duration: 1000,
                once: true
            });

            // Sticky Header
            $(window).scroll(function() {
                if ($(this).scrollTop() > 100) {
                    $('.navbar').addClass('sticky-nav');
                    $('#backToTop').fadeIn();
                } else {
                    $('.navbar').removeClass('sticky-nav');
                    $('#backToTop').fadeOut();
                }
            });

            // Back to top button
            $('#backToTop').click(function(e) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: 0
                }, 800);
                return false;
            });

            // Auto dismiss alerts after 5 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        });
    </script>

    <!-- Additional Scripts -->
    @stack('scripts')
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ekansh Global | Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Segoe UI', sans-serif;
        }

        .navbar-brand {
            color: #007f5f;
            font-weight: 600;
            font-size: 24px;
        }

        .category-icon img,
        .city-icon img {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }

        .category-icon p,
        .city-icon p {
            margin-top: 8px;
            font-weight: 500;
        }

        .testimonial-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            text-align: center;
            height: 100%;
        }

        .testimonial-card img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 12px;
        }

        .stars {
            color: #28a745;
            font-size: 18px;
        }

        .swiper-slide {
            height: auto;
        }

        .swiper-button-next,
        .swiper-button-prev {
            width: 36px;
            height: 36px;
            background-color: #198754;
            border-radius: 50%;
            color: #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 16px;
            font-weight: bold;
        }

        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            background-color: #145c41;
            transform: scale(1.1);
        }

        .custom-green-outline {
            border-color: #145c41;
            color: #145c41;
        }

        .custom-green-outline:hover {
            background-color: #145c41;
            color: white;
            border-color: #145c41;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">Ekansh Global</a>
            <div class="ms-auto">
                <a href="{{ route('reviews') }}" class="btn btn-outline-success me-2">Reviews</a>
                @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-outline-success me-2">Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary custom-green-outline me-2">Dashboard Login</a>
                <a href="{{ route('register') }}" class="btn btn-success">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container py-5 text-center">
        <h2 class="fw-bold text-success mb-4">The Leading B2B Ecommerce Platform For Global Trade</h2>
        <p class="text-muted">Explore millions of offerings tailored to your business needs.</p>
    </div>
    {{-- Trending Categories --}}
    <div class="container py-3">
        <h4 class="fw-bold text-dark mb-3">Trending Categories</h4>
        <div class="row text-center g-3">
            @php
            $categories = [
            ['label' => 'LED Products', 'img' => 'https://cdn-icons-png.flaticon.com/128/1699/1699869.png'],
            ['label' => 'T-Shirts', 'img' => 'https://cdn-icons-png.flaticon.com/128/892/892458.png'],
            ['label' => 'Medicines', 'img' => 'https://cdn-icons-png.flaticon.com/128/1046/1046784.png'],
            ['label' => 'Solar Panels', 'img' => 'https://cdn-icons-png.flaticon.com/128/1452/1452625.png'],
            ['label' => 'Agriculture', 'img' => 'https://cdn-icons-png.flaticon.com/128/3542/3542450.png'],
            ['label' => 'More', 'img' => 'https://cdn-icons-png.flaticon.com/128/1828/1828817.png'],
            ];
            @endphp
            @foreach ($categories as $category)
            <div class="col-6 col-sm-4 col-md-2 category-icon">
                <img src="{{ $category['img'] }}" alt="{{ $category['label'] }}">
                <p>{{ $category['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Find Suppliers From Top Cities --}}
    <div class="container py-4">
        <h4 class="fw-bold text-dark mb-3">Find Suppliers From Top Cities</h4>
        <div class="row text-center g-3">
            @php
            $cities = [
            ['label' => 'Delhi', 'img' => 'https://cdn-icons-png.flaticon.com/128/3771/3771408.png'],
            ['label' => 'Bangalore', 'img' => 'https://cdn-icons-png.flaticon.com/128/3771/3771417.png'],
            ['label' => 'Chennai', 'img' => 'https://cdn-icons-png.flaticon.com/128/3771/3771410.png'],
            ['label' => 'Gujarat', 'img' => 'https://cdn-icons-png.flaticon.com/128/3771/3771411.png'],
            ['label' => 'Pune', 'img' => 'https://cdn-icons-png.flaticon.com/128/3771/3771418.png'],
            ['label' => 'Mumbai', 'img' => 'https://cdn-icons-png.flaticon.com/128/3771/3771409.png'],
            ];
            @endphp
            @foreach ($cities as $city)
            <div class="col-6 col-sm-4 col-md-2 city-icon">
                <img src="{{ $city['img'] }}" alt="{{ $city['label'] }}">
                <p>{{ $city['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
    <div class="container py-5">
        <h2 class="text-center text-success mb-2">Testimonials From Our Customers</h2>
        <p class="text-center text-muted mb-4">Lorem Ipsum Dolor Sit Amet, Consectetuer Adipisicing Elit. Aenean</p>

        @if($testimonials->isEmpty())
        <div class="text-center py-5" style="font-size: 1.2rem; color: #666;">
            <i class="bi bi-exclamation-circle" style="font-size: 2rem; color: #ccc;"></i>
            <p class="mt-3">No testimonials found at the moment.</p>
            <p>Please check back later.</p>
        </div>
        @else
        <div class="swiper mySwiper position-relative">
            <div class="swiper-wrapper">
                @foreach ($testimonials as $item)
                <div class="swiper-slide">
                    <div class="testimonial-card">
                        <div class="stars mb-2">★★★★★</div>
                        <div class="testimonial-title mb-1">{{ $item->heading }}</div>
                        <p class="text-muted small">{{ $item->text }}</p>
                        <img src="{{ asset('images/' . $item->image) }}" alt="{{ $item->heading }}">
                        <div class="fw-bold mt-2">{{ $item->heading }}</div>
                        <small class="text-muted">Happy Client</small>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
        @endif
    </div>
    <footer class="bg-white border-top shadow-sm mt-5">
        <div class="container py-4 text-center">
            <h5 class="text-success mb-2">Ekansh Global</h5>
            <p class="text-muted small mb-3">
                Connecting businesses with trusted suppliers across India.
            </p>
            <div class="d-flex justify-content-center gap-3 mb-3">
                <a href="#" class="text-decoration-none text-success small">Privacy Policy</a>
                <a href="#" class="text-decoration-none text-success small">Terms of Service</a>
                <a href="#" class="text-decoration-none text-success small">Contact</a>
            </div>
            <p class="text-muted small mb-0">&copy; {{ date('Y') }} Ekansh Global. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.mySwiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                768: {
                    slidesPerView: 2
                },
                1024: {
                    slidesPerView: 3
                }
            }
        });
    </script>
</body>

</html>
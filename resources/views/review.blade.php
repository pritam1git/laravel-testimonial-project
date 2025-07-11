<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ekansh Global | Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        .card {
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }

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
                <a href="{{ route('home') }}" class="btn btn-outline-success me-2">Home</a>
                @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-outline-success me-2">Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary custom-green-outline me-2">Dashboard Login</a>
                <a href="{{ route('register') }}" class="btn btn-success">Register</a>
                @endauth
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row mb-5 align-items-center">
            <h2 class="text-center mt-4 mb-4" style="color: #145c41;">Tell Us What You Think – Leave a Review</h2>

            <div class="col-12 col-md-8">
                <form id="reviewForm" enctype="multipart/form-data" class="p-4 bg-light rounded border">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
                            <div class="text-danger small mt-1" id="error-first_name"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
                            <div class="text-danger small mt-1" id="error-last_name"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <textarea name="review_text" class="form-control" rows="4" placeholder="Your review here..." required></textarea>
                        <div class="text-danger small mt-1" id="error-review_text"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-block mb-2">Rating</label>
                        <div id="starRating" class="fs-4 text-warning">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fa fa-star-o star" data-value="{{ $i }}"></i>
                                @endfor
                        </div>
                        <input type="hidden" name="review_star" id="review_star" required>
                        <div class="text-danger small mt-1" id="error-review_star"></div>
                    </div>

                    <button type="submit" class="btn btn-success w-100" id="submitBtn">
                        <span id="submitText">Submit Review</span>
                        <span id="loader" class="spinner-border spinner-border-sm d-none ms-2" role="status" aria-hidden="true"></span>
                    </button>

                    <div class="mt-3 text-center text-success fw-bold d-none" id="thankYouMsg">
                        Thanks for your review 😊
                    </div>
                </form>
            </div>

            <div class="col-12 col-md-4 d-flex justify-content-center justify-content-md-end mt-4 mt-md-0">
                <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?auto=format&fit=crop&w=600&q=80"
                    alt="Wallpaper"
                    class="img-fluid rounded shadow"
                    style="max-height: 320px; object-fit: cover; width: 100%; max-width: 300px;">
            </div>
        </div>

        <!-- Review Cards Section -->
        <div class="container mt-5">
            <h2 class="text-center mb-4" style="color: #145c41;">What Our Clients Say</h2>

            <div class="row">
                <div class="col-12">
                    <div class="swiper mySwiper py-3">
                        <div class="swiper-wrapper">
                            @foreach($reviews as $review)
                            <div class="swiper-slide">
                                <div class="p-3 bg-white rounded shadow-sm h-100">
                                    <div class="d-flex flex-column flex-sm-row align-items-start gap-3">
                                        {{-- User Image --}}
                                        @if($review->user_image)
                                        <div class="flex-shrink-0">
                                            <img src="{{ asset('uploads/reviews/' . $review->user_image) }}"
                                                alt="{{ $review->username }}"
                                                class="rounded-circle"
                                                style="width: 60px; height: 60px; object-fit: cover;">
                                        </div>
                                        @else
                                        <div class="rounded-circle bg-secondary d-flex justify-content-center align-items-center flex-shrink-0"
                                            style="width: 60px; height: 60px;">
                                            <span class="text-white fw-bold">{{ strtoupper(substr($review->username, 0, 1)) }}</span>
                                        </div>
                                        @endif

                                        {{-- Review Content --}}
                                        <div class="flex-grow-1">
                                            <p class="text-warning mb-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fa {{ $i <= $review->review_star ? 'fa-star' : 'fa-star-o' }}"></i>
                                                    @endfor
                                            </p>
                                            <h6 class="fw-bold mb-1">{{ $review->username }}</h6>
                                            <small class="text-muted d-block mb-1">
                                                {{ \Carbon\Carbon::parse($review->review_date)->format('d M, Y') }}
                                            </small>
                                            @php
                                            $words = explode(' ', $review->review_text);
                                            $truncated = implode(' ', array_slice($words, 0, 15));
                                            $is_truncated = count($words) > 15;
                                            @endphp

                                            <p class="mb-0 text-muted" title="{{ $review->review_text }}">
                                                {{ $is_truncated ? $truncated . '...' : $review->review_text }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Swiper Arrows -->
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
            </div>
        </div>



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
    <!-- FontAwesome for stars -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/js/all.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- AlertifyJS -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css" />
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // ⭐ Handle star rating selection
            $('.star').on('click', function() {
                let rating = $(this).data('value');
                $('#review_star').val(rating);
                $('.star').removeClass('fa-star').addClass('fa-star-o');
                $('.star').each(function() {
                    if ($(this).data('value') <= rating) {
                        $(this).removeClass('fa-star-o').addClass('fa-star');
                    }
                });
            });

            // 📨 AJAX form submission
            $('#reviewForm').on('submit', function(e) {
                e.preventDefault();
                let form = this;

                // Clear old errors
                $('#error-review_text').text('');

                // Get textarea value
                const reviewText = $(form).find('textarea[name="review_text"]').val();
                const wordCount = reviewText.trim().split(/\s+/).length;

                if (wordCount < 50) {
                    $('#error-review_text').text('Please write at least 50 words in your review.');
                    return;
                }

                // Show loader
                $('#submitBtn').prop('disabled', true);
                $('#loader').removeClass('d-none');

                const formData = new FormData(form);

                $.ajax({
                    url: "{{ route('review.store') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function() {
                        alertify.success("Review submitted successfully!");
                        $('#reviewForm')[0].reset();
                        $('#thankYouMsg').removeClass('d-none');
                        $('#submitBtn').prop('disabled', false);
                        $('#loader').addClass('d-none');
                        setTimeout(() => location.reload(), 1500);
                    },
                    error: function(xhr) {
                        $('#submitBtn').prop('disabled', false);
                        $('#loader').addClass('d-none');
                        if (xhr.responseJSON?.errors) {
                            Object.entries(xhr.responseJSON.errors).forEach(([field, messages]) => {
                                $(`#error-${field}`).text(messages[0]);
                            });
                        } else {
                            alertify.error("Something went wrong!");
                        }
                    }
                });
            });
        });
    </script>
    <script>
        new Swiper(".mySwiper", {
            slidesPerView: 5,
            spaceBetween: 30,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev"
            },
            breakpoints: {
                0: {
                    slidesPerView: 1
                },
                768: {
                    slidesPerView: 2
                },
                992: {
                    slidesPerView: 3
                }
            }
        });
    </script>

    <style>
        .swiper-button-next,
        .swiper-button-prev {
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .mySwiper:hover .swiper-button-next,
        .mySwiper:hover .swiper-button-prev {
            opacity: 1;
        }

        .swiper-slide {
            width: auto;
        }
    </style>

</body>

</html>
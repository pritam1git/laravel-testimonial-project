<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Review Management') }}
            </h2>

            <a href="{{ route('testimonials.index') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-black text-sm font-medium rounded-md shadow-sm transition duration-150 ease-in-out">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Manage Testimonials</span>
            </a>
        </div>
    </x-slot>


    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4">
            <div class="d-flex justify-content-end mb-3">
                <button class="btn btn-success" onclick="openModal()">➕ Add Review</button>
            </div>

            <div class="bg-white shadow-sm p-4 rounded">
                @if($reviews->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-info-circle" style="font-size: 2rem;"></i>
                    <p class="mt-3">No reviews found.</p>
                </div>
                @else
                <table id="reviewsTable" class="table table-bordered table-striped">
                    <thead class="table-success">
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Review</th>
                            <th>Rating</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reviews as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->username }}</td>
                            <td>{{ $item->review_text }}</td>
                            <td>{{ $item->review_star }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-warning btn-sm me-1" onclick='editReview(@json($item))'>✏️</button>
                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $item->id }})">🗑️</button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>

    <!-- Review Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="reviewForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="reviewId" name="review_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Add / Edit Review</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <textarea name="review_text" class="form-control" rows="4" placeholder="Your review here..." required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block mb-2">Rating</label>
                            <div id="starRating" class="fs-4 text-warning">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fa fa-star-o star" data-value="{{ $i }}"></i>
                                    @endfor
                            </div>
                            <input type="hidden" name="review_star" id="review_star" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Submit Review</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Are you sure?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Do you really want to delete this review?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button class="btn btn-danger" id="confirmDeleteBtn">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" rel="stylesheet" />
    <link href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css" rel="stylesheet" />
    <style>
        .fa-star,
        .fa-star-o {
            cursor: pointer;
            font-size: 24px;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/js/all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

    <script>
        let currentId = null;

        $(document).ready(function() {
            $('#reviewsTable').DataTable();
            initStarEvents();
        });

        function initStarEvents() {
            $('.star').off('click').on('click', function() {
                const value = $(this).data('value');
                $('#review_star').val(value);
                $('.star').removeClass('fa-star').addClass('fa-star-o');
                $(this).removeClass('fa-star-o').addClass('fa-star');
                $(this).prevAll().removeClass('fa-star-o').addClass('fa-star');
            });
        }

        function openModal() {
            $('#reviewForm')[0].reset();
            $('#reviewId').val('');
            $('#review_star').val('');
            $('.star').removeClass('fa-star').addClass('fa-star-o');
            $('#modalLabel').text('Add Review');
            new bootstrap.Modal(document.getElementById('reviewModal')).show();
            initStarEvents();
        }

        function editReview(data) {
            $('#reviewId').val(data.id);
            const [first, ...last] = data.username.split(' ');
            $('input[name="first_name"]').val(first);
            $('input[name="last_name"]').val(last.join(' '));
            $('textarea[name="review_text"]').val(data.review_text);
            $('#review_star').val(data.review_star);

            $('.star').removeClass('fa-star').addClass('fa-star-o');
            $('.star').each(function() {
                if ($(this).data('value') <= data.review_star) {
                    $(this).removeClass('fa-star-o').addClass('fa-star');
                }
            });

            $('#modalLabel').text('Edit Review');
            new bootstrap.Modal(document.getElementById('reviewModal')).show();
            initStarEvents();
        }

        $('#reviewForm').on('submit', function(e) {
            e.preventDefault();
            const id = $('#reviewId').val();
            const formData = new FormData(this);
            const url = id ? `/dashboard/update/${id}` : '{{ route("review.admin.store") }}';
            if (id) formData.append('_method', 'PUT');

            $.ajax({
                url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: res => {
                    alertify.set('notifier', 'position', 'top-right');
                    alertify.success('Review saved successfully.');
                    location.reload();
                },
                error: err => {
                    alertify.set('notifier', 'position', 'top-right');
                    alertify.error('Something went wrong.');
                }
            });
        });

        function confirmDelete(id) {
            currentId = id;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        $('#confirmDeleteBtn').on('click', function() {
            $.ajax({
                url: `/review/${currentId}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                },
                success: res => {
                    alertify.set('notifier', 'position', 'top-right');
                    alertify.success(res.message);
                    location.reload();
                },
                error: () => alertify.error('Delete failed.')
            });
        });
    </script>
    @endpush
</x-app-layout>
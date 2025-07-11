<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Testimonials Management') }}
            </h2>

            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-black text-sm font-medium rounded-md shadow-sm transition duration-150 ease-in-out">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Manage Reviews</span>
            </a>
        </div>
    </x-slot>


    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4">
            <div class="d-flex justify-content-end mb-3">
                <button class="btn btn-success" onclick="openModal()">➕ Add Testimonial</button>
            </div>

            <div class="bg-white shadow-sm p-4 rounded">
                @if($testimonials->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-info-circle" style="font-size: 2rem;"></i>
                    <p class="mt-3">No testimonials found.</p>
                </div>
                @else
                <table id="testimonialTable" class="table table-bordered table-striped">
                    <thead class="table-success">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Heading</th>
                            <th>Text</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($testimonials as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td><img src="{{ asset('images/' . $item->image) }}" width="60"></td>
                            <td>{{ $item->heading }}</td>
                            <td>{{ $item->text }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm me-1" onclick='editTestimonial(@json($item))'>✏️</button>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $item->id }})">🗑️</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="testimonialModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="testimonialForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="testimonialId">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add/Edit Testimonial</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="text" name="heading" id="headingInput" class="form-control" placeholder="Heading" required>
                        </div>
                        <div class="mb-3">
                            <textarea name="text" id="textInput" class="form-control" placeholder="Description" required></textarea>
                        </div>
                        <div class="mb-3">
                            <input type="file" name="image" id="imageInput" class="form-control">
                            <small id="currentImage" class="text-muted"></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save</button>
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
                    This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" id="confirmDeleteBtn">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" rel="stylesheet" />
    <link href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css" rel="stylesheet" />
    @endpush

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

    <script>
        let currentId = null;

        $(document).ready(function() {
            $('#testimonialTable').DataTable();
        });

        function openModal() {
            $('#testimonialForm')[0].reset();
            $('#testimonialId').val('');
            $('#currentImage').text('');
            new bootstrap.Modal(document.getElementById('testimonialModal')).show();
        }

        function editTestimonial(data) {
            $('#testimonialId').val(data.id);
            $('#headingInput').val(data.heading);
            $('#textInput').val(data.text);
            $('#currentImage').text(`Current image: ${data.image}`);
            new bootstrap.Modal(document.getElementById('testimonialModal')).show();
        }

        $('#testimonialForm').on('submit', function(e) {
            e.preventDefault();
            const id = $('#testimonialId').val();
            const formData = new FormData(this);
            const url = id ? `/testimonials/update/${id}` : `/testimonials`;
            if (id) formData.append('_method', 'PUT');

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: res => {
                    alertify.set('notifier', 'position', 'top-right');
                    alertify.success(res.message);
                    location.reload();
                },
                error: err => {
                    alertify.error('Something went wrong');
                }
            });
        });

        function confirmDelete(id) {
            currentId = id;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        $('#confirmDeleteBtn').on('click', function() {
            $.ajax({
                url: `/testimonials/${currentId}`,
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
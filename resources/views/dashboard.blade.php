<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Testimonials List') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4">
            <div class="d-flex justify-content-end mb-3">
                <button class="btn btn-success" onclick="openModal()">➕ Add Testimonial</button>
            </div>

            <div class="bg-white shadow-sm p-4 rounded">
                @if($testimonials->isEmpty())
                <div class="text-center py-5" style="font-size: 1.2rem; color: #666;">
                    <i class="bi bi-info-circle" style="font-size: 2rem; color: #ccc;"></i>
                    <p class="mt-3">No testimonials found.</p>
                </div>
                @else
                <table id="testimonialsTable" class="table table-bordered table-striped">
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
                        <tr data-id="{{ $item->id }}">
                            <td>{{ $item->id }}</td>
                            <td><img src="{{ asset('images/' . $item->image) }}" width="60" height="60" class="rounded"></td>
                            <td>{{ $item->heading }}</td>
                            <td>{{ $item->text }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-warning btn-sm me-1" onclick="editTestimonial({{ $item }})">✏️</button>
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

    <div class="modal fade" id="testimonialModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="testimonialForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="testimonialId">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Add / Edit Testimonial</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" name="image" class="form-control" id="imageInput">
                            <small class="text-muted" id="currentImage"></small>
                            <div class="text-danger mt-1" id="imageError"></div> <!-- error div -->
                        </div>

                        <div class="mb-3">
                            <label for="heading">Heading</label>
                            <input type="text" name="heading" class="form-control" id="headingInput">
                            <div class="text-danger mt-1" id="headingError"></div> <!-- error div -->
                        </div>

                        <div class="mb-3">
                            <label for="text">Text</label>
                            <textarea name="text" class="form-control" id="textInput" rows="3"></textarea>
                            <div class="text-danger mt-1" id="textError"></div> <!-- error div -->
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

    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Are you sure?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Do you really want to delete this testimonial?
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
        .dataTables_wrapper .dataTables_paginate {
            justify-content: center;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <script>
        let currentId = null;

        $(document).ready(function() {
            $('#testimonialsTable').DataTable();
        });

        function openModal() {
            resetForm();
            $('#modalLabel').text('Add Testimonial');
            new bootstrap.Modal(document.getElementById('testimonialModal')).show();
        }

        function resetForm() {
            $('#testimonialId').val('');
            $('#imageInput').val('');
            $('#headingInput').val('');
            $('#textInput').val('');
            $('#currentImage').text('');
        }

        function editTestimonial(data) {
            $('#testimonialId').val(data.id);
            $('#headingInput').val(data.heading);
            $('#textInput').val(data.text);
            $('#currentImage').text(`Current image: ${data.image}`);
            $('#modalLabel').text('Edit Testimonial');
            new bootstrap.Modal(document.getElementById('testimonialModal')).show();
        }

        $('#testimonialForm').on('submit', function(e) {
            e.preventDefault();
            const id = $('#testimonialId').val();
            const formData = new FormData(this);

            const url = id ? `/update/${id}` : '{{ route("dashboard") }}';
            const method = id ? 'POST' : 'POST';
            if (id) formData.append('_method', 'PUT');

            $.ajax({
                url,
                method,
                data: formData,
                processData: false,
                contentType: false,
                success: res => {
                    alertify.set('notifier','position', 'top-right');
                    alertify.success(res.message);
                    location.reload();
                },
                error: err => {
                    alertify.set('notifier','position', 'top-right');
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
                url: `/dashboard/${currentId}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                },
                success: res => {
                    alertify.set('notifier','position', 'top-right');
                    alertify.success(res.message);
                    location.reload();
                },
                error: () => alertify.error('Delete failed.')
            });
        });
        document.getElementById('testimonialForm').addEventListener('submit', function(event) {
            event.preventDefault();

            // Clear previous errors
            document.getElementById('imageError').textContent = '';
            document.getElementById('headingError').textContent = '';
            document.getElementById('textError').textContent = '';

            let heading = document.getElementById('headingInput').value.trim();
            let text = document.getElementById('textInput').value.trim();
            let imageInput = document.getElementById('imageInput');
            let testimonialId = document.getElementById('testimonialId').value.trim();

            let hasError = false;

            // Heading required
            if (heading === '') {
                document.getElementById('headingError').textContent = 'Heading is required.';
                hasError = true;
            }

            // Text required
            if (text === '') {
                document.getElementById('textError').textContent = 'Text is required.';
                hasError = true;
            }

            // Image required only if adding new testimonial
            if (testimonialId === '' && imageInput.files.length === 0) {
                document.getElementById('imageError').textContent = 'Image is required when adding a new testimonial.';
                hasError = true;
            }

            if (hasError) {
                return false; 
            }
        });
    </script>

    @endpush
</x-app-layout>
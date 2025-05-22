@push('styles')
    <style>
        .card-list-of-verified-graduation-result {
            background-color: white; /* starting point */
            transition: background-color 0.6s ease-in-out;
        }

        .card-list-of-verified-graduation-result.fade-highlight {
            background-color: #28a745; /* strong green */
        }

        .card-list-of-verified-graduation-result.fade-out {
            background-color: white;
        }

        select.is-invalid {
            border-color: red;
        }
    </style>
@endpush
<form id="form-list-of-verified-graduation-result" action="{{ route('verified.final.graduation.result.store') }}"
      method="POST">
    @csrf
    <div class="row mb-5">
        <div class="col-md-12">
            <section class="card card-featured card-featured-primary ">
                <header class="card-header">
                    <h2 class="card-title">List of Teachers verified the final graduation results (@ 700/- per
                        student):</h2>
                </header>

                <div class="card-body card-list-of-verified-graduation-result">
                    {{--method call for find teacher name--}}
                    {{--this two column for heading--}}
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-4 gap-2 fw-bold">
                                    Name
                                </div>
                                <div class="col-md-6 fw-bold ms-2">
                                    No of Students
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-4 gap-2 fw-bold">
                                    Name
                                </div>
                                <div class="col-md-6 fw-bold">
                                    No of Students
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-4 gap-2 fw-bold">
                                    Name
                                </div>
                                <div class="col-md-6 fw-bold">
                                    No of Students
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- this for making chunk to divide advisors into two column--}}
                    @php
                        $chunks = isset($teachers)
                            ? $teachers->chunk(ceil($teachers->count() / 3))
                            : collect(); // fallback to empty collection
                    @endphp

                    <div class="row">
                        @foreach($chunks as $chunk)
                            <div class="col-md-4">
                                @foreach($chunk as $teacher)
                                    <div class="input-group mb-3">
                                        <!-- Checkbox -->
                                        <span class="input-group-text">
                                            <input type="checkbox"
                                                   name="verified_grade_teacher_ids[]"
                                                   value="{{ $teacher->id }}"
                                                   class="final-graduation-result-toggle-input"
                                                   data-id="{{ $teacher->id }}">
                                        </span>

                                        <!-- Teacher name and designation -->
                                        <span class="input-group-text">
                                            {{ $teacher->user->name }},<sub>{{ $teacher->designation->name }}</sub>
                                        </span>

                                        <!-- Amount input -->
                                        <input type="number"
                                               name="verified_grade_amounts[{{ $teacher->id }}]"
                                               class="form-control final-graduation-result-amount-input"
                                               placeholder="No of students"
                                               id="final-graduation-result-amount-{{ $teacher->id }}"
                                               disabled>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>

                    <div class="text-end mt-3">
                        <button id="submit-list-of-verified-graduation-result" type="submit" class="btn btn-primary">
                            Submit Final Graduation Result
                        </button>
                    </div>
                </div>


            </section>


        </div>
    </div>
</form>



@push('scripts')
    <!-- checkbox condition for final graduation result -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('.final-graduation-result-toggle-input');

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    const id = this.getAttribute('data-id');
                    const input = document.getElementById('final-graduation-result-amount-' + id);

                    if (this.checked) {
                        input.disabled = false;
                        input.required = true; // ✅ make required
                    } else {
                        input.disabled = true;
                        input.required = false; // ✅ remove required
                        input.value = ''; // Optional: clear input when unchecked
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('form-list-of-verified-graduation-result');

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                // ✅ Validate teacher checkboxes
                const checkedBoxes = form.querySelectorAll('.final-graduation-result-toggle-input:checked');
                if (checkedBoxes.length === 0) {
                    valid = false;
                    Swal.fire({
                        title: 'No Teachers Selected',
                        text: 'Please select at least one teacher and enter the number of students.',
                        icon: 'warning'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to save the committee data?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, save it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = new FormData(form);

                        fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: formData
                        })
                            .then(response => response.json())  // <== Must be here
                            .then(data => {
                                console.log("Server response:", data); // Debug log
                                Swal.fire({
                                    title: 'Success!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });

                                const submitBtn = document.getElementById('submit-list-of-verified-graduation-result');
                                submitBtn.textContent = 'Already Saved';             // ✅ Change text
                                submitBtn.disabled = true;                           // ✅ Disable button
                                submitBtn.classList.remove('btn-primary');           // ✅ Remove old style
                                submitBtn.classList.add('btn-success');              // ✅ Add success style

                                const cards = document.querySelectorAll('.card-list-of-verified-graduation-result');

                                cards.forEach(card => {
                                    card.classList.add('fade-highlight');

                                    setTimeout(() => {
                                        card.classList.add('fade-out');
                                    }, 1000);

                                    setTimeout(() => {
                                        card.classList.remove('fade-highlight', 'fade-out');
                                    }, 1900);
                                });


                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Something went wrong. Please try again.',
                                    icon: 'error'
                                });
                            });
                    }
                });
            });
        });
    </script>
@endpush


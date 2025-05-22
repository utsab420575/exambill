@push('styles')
    <style>
        .card-list-of-class-test-teacher {
            background-color: white;
            transition: background-color 0.6s ease-in-out;
        }

        .card-list-of-class-test-teacher.fade-highlight {
            background-color: #28a745;
        }

        .card-list-of-class-test-teacher.fade-out {
            background-color: white;
        }

        select.is-invalid, input.is-invalid {
            border-color: red;
        }
    </style>
@endpush
<form id="form-list-of-class-test-teacher" action="{{ route('class.test.teacher.store') }}" method="POST">
    @csrf
    <input type="hidden" id="sid" name="sid" value="{{$sid}}">
    <div class="row mb-5">
        <div class="col-md-12">
            <section class="card card-featured card-featured-primary">
                <header class="card-header">
                    <h2 class="card-title">Internal Assessment/Class Test @45/- per class test per student
                        (@3600/- per paper setter)</h2>
                </header>
                <div class="card-body">
                    <div class="row">
                        @if(isset($all_course_with_teacher['courses']))
                            @foreach($all_course_with_teacher['courses'] as $courseData)
                                @php
                                    $single_course = $courseData['courseObject'];
                                @endphp

                                <section class="card card-featured card-featured-secondary mb-4 w-100">
                                    <header class="card-header">
                                        <h2 class="card-title">
                                            Course: {{ $single_course['courseno'] }} - {{ $single_course['coursetitle'] }}
                                        </h2>
                                    </header>

                                    <div class="card-body card-list-of-class-test-teacher">
                                        <div class="row">
                                            <div class="col-md-8 ms-2">
                                                Teacher Name
                                            </div>
                                            <!-- Left Side: Paper Setter & Examiner -->
                                            <div class="col-md-8">
                                                <div class="p-2">
                                                    @foreach($single_course['teachers'] as $assignedTeacher)
                                                        <div class="row mb-3">
                                                            <!-- Paper Setter -->
                                                            <div class="col-md-12">
                                                                <select name="class_test_teachers_ids[{{ $single_course['id'] }}][]"
                                                                        id="class_test_teachers_ids{{ $single_course['id'] }}_{{ $loop->index }}"
                                                                        class="form-control" required>
                                                                    <option value="">-- Select Teacher --</option>
                                                                    @foreach($teachers as $teacherOption)
                                                                        <option value="{{ $teacherOption->id }}"
                                                                            {{ $assignedTeacher['id'] == $teacherOption->id ? 'selected' : '' }}>
                                                                            {{ $teacherOption->user->name }} - {{ $teacherOption->designation->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <!-- Right Side: No of Scripts -->
                                            <div class="col-md-4 d-flex align-items-center justify-content-center">
                                                <div class="form-group w-100">
                                                    <label for="no_of_students_ct_{{ $single_course['id'] }}">No of Students</label>
                                                    <input type="number"
                                                           id="no_of_students_ct_{{ $single_course['id'] }}"
                                                           name="no_of_students_ct[{{ $single_course['id'] }}]"
                                                           class="form-control"
                                                           min="0"
                                                           step="any"
                                                           value="{{ old('no_of_script.'.$single_course['id'], $courseData['registered_students_count']) }}"
                                                           required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            @endforeach
                        @endif
                    </div>

                    <div class="text-end mt-3">
                        <button id="submit-list-of-class-test-teacher" type="submit" class="btn btn-primary">
                            Submit Class Test Teacher
                        </button>
                    </div>
                </div>

            </section>
        </div>
    </div>
</form>


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('form-list-of-class-test-teacher');

            form.addEventListener('submit', function (e) {
                e.preventDefault();

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
                            .then(response => {
                                if (!response.ok) {
                                    // Return the error JSON and throw it
                                    return response.json().then(err => {
                                        throw new Error(err.message || 'Unknown error occurred.');
                                    });
                                }
                                return response.json(); // if response is OK
                            })
                            .then(data => {
                                console.log("Server response:", data); // Debug log
                                Swal.fire({
                                    title: 'Success!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });

                                const submitBtn = document.getElementById('submit-list-of-class-test-teacher');
                                submitBtn.textContent = 'Already Saved';             // ✅ Change text
                                submitBtn.disabled = true;                           // ✅ Disable button
                                submitBtn.classList.remove('btn-primary');           // ✅ Remove old style
                                submitBtn.classList.add('btn-success');              // ✅ Add success style

                                const cards = document.querySelectorAll('.card-list-of-class-test-teacher');
                                cards.forEach(card => {
                                    card.classList.add('fade-highlight');
                                    setTimeout(() => card.classList.add('fade-out'), 1000);
                                    setTimeout(() => card.classList.remove('fade-highlight', 'fade-out'), 1900);
                                });


                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    title: 'Error!',
                                    text: error.message || 'Something went wrong. Please try again.',
                                    icon: 'error'
                                });
                            });
                    }
                });
            });
        });
    </script>
@endpush


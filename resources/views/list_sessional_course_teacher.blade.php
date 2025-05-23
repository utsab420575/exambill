<form id="form-list-of-sessional-course-teacher" action="{{ route('sessional.course.teacher.store') }}" method="POST">
    @csrf
    <input type="hidden" id="sid" name="sid" value="{{$sid}}">
    <div class="row mb-5">
        <div class="col-md-12">
            <section class="card card-featured card-featured-primary">
                <header class="card-header">
                    <h2 class="card-title">Sessional (@ ***/- per contact hour per week; min ****/- per examiner)
                    </h2>
                </header>

                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-4 mb-4">
                            <div class="form-group">
                                <label for="sessional_per_hour_rate">Per Contact Hour Rate</label>
                                <input type="number"  name="sessional_per_hour_rate" step="any" class="form-control" placeholder="Enter per contact hour rate" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="form-group">
                                <label for="class_test_rate">Minimum Examineer Rate</label>
                                <input type="number"  name="sessional_examiner_min_rate" step="any" class="form-control" placeholder="Enter minimum examiner rate" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="total_week">Total Weeks in semester</label>
                                <input type="number"
                                       id="total_week"
                                       name="total_week"
                                       step="any"
                                       class="form-control"
                                       placeholder="Enter Total Weeks"
                                       required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @if(isset($all_sessional_course_with_teacher['courses']))
                                @foreach($all_sessional_course_with_teacher['courses'] as $courseData)
                                    @php
                                        $single_course = $courseData['courseObject'];
                                    @endphp
                                    <section class="card card-featured card-featured-secondary">
                                        <header class="card-header">
                                            <h2 class="card-title">
                                                Course: {{ $single_course['courseno'] }} - {{ $single_course['coursetitle'] }}
                                            </h2>
                                        </header>

                                        <div class="card-body">

                                            <table
                                                class="table-list-of-sessional-course-teacher table table-responsive-md table-striped mb-0">
                                                <thead>
                                                <tr>
                                                    <th style="width: 60%;">Name</th>
                                                    <th style="width: 40%;">Contact Hour/Week</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                {{-- Head Row --}}
                                                @foreach($single_course['teachers'] as $assignedTeacher)
                                                    <tr>
                                                        <td>
                                                            <select
                                                                name="sessional_course_teacher_ids[{{ $single_course['id'] }}][]"
                                                                class="form-control" required>
                                                                <option value="">-- Select Teacher --</option>
                                                                @foreach($teachers as $teacherOption)
                                                                    <option
                                                                        value="{{ $teacherOption->id }}" {{ $assignedTeacher['id'] == $teacherOption->id ? 'selected' : '' }}>
                                                                        {{ $teacherOption->user->name }}
                                                                        - {{ $teacherOption->designation->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>

                                                        <td>
                                                            <input
                                                                name="no_of_contact_hour[{{ $single_course['id'] }}][]"
                                                                type="number" min="1"
                                                                step="any" class="form-control"
                                                                value=""
                                                                required>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                    </section>
                                @endforeach
                            @endif


                            <div class="text-end mt-3">
                                <button id="submit-list-of-sessional-course-teacher" type="submit" class="btn btn-primary">
                                    Submit
                                    Sessional Examiner
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</form>


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('form-list-of-sessional-course-teacher');

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

                                const submitBtn = document.getElementById('submit-list-of-sessional-course-teacher');
                                submitBtn.textContent = 'Already Saved';             // ✅ Change text
                                submitBtn.disabled = true;                           // ✅ Disable button
                                submitBtn.classList.remove('btn-primary');           // ✅ Remove old style
                                submitBtn.classList.add('btn-success');              // ✅ Add success style

                                const cells = document.querySelectorAll('.table-list-of-sessional-course-teacher td');

                                cells.forEach(td => {
                                    td.classList.add('fade-green');

                                    // Start fade out after short delay
                                    setTimeout(() => {
                                        td.classList.add('fade-out');
                                    }, 1000);

                                    // Remove classes to reset
                                    setTimeout(() => {
                                        td.classList.remove('fade-green', 'fade-out');
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


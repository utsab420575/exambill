<form id="form-list-of-sessional-course-teacher" action="{{ route('sessional.course.teacher.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <section class="card card-featured card-featured-primary">
                <header class="card-header">
                    <h2 class="card-title">Sessional (@ 115/- per contact hour per week; min 1500/- per examiner)
                        </h2>
                </header>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            @if(isset($all_sessional_course_with_teacher['courses']))
                                @foreach($all_sessional_course_with_teacher['courses'] as $course)
                                    <section class="card card-featured card-featured-secondary">
                                        <header class="card-header">
                                            <h2 class="card-title">Course: {{$course['courseObject']['courseno']}}
                                                - {{$course['courseObject']['coursetitle']}}</h2>
                                        </header>

                                        <div class="card-body">

                                            <table
                                                class="table-list-of-sessional-course-teacher table table-responsive-md table-striped mb-0">
                                                <thead>
                                                <tr>
                                                    <th style="width: 60%;">Name</th>
                                                    <th style="width: 40%;">Contact Hour</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                {{-- Head Row --}}
                                                @foreach($course['courseObject']['teachers'] as $assignedTeacher)
                                                    <tr>
                                                        <td>
                                                            <select
                                                                name="sessional_course_teacher_ids[{{ $course['courseObject']['id'] }}][{{ $assignedTeacher['id'] }}]"
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
                                                                name="no_of_contact_hour[{{ $course['courseObject']['id'] }}][{{ $assignedTeacher['id'] }}]"
                                                                type="number" min="0"
                                                                step="any" class="form-control"
                                                                value="{{ old('no_of_contact_hour.'.$course['courseObject']['id'].'.'.$assignedTeacher['id']) }}"
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
                                    Sessional
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
                            .then(response => response.json())  // <== Must be here
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


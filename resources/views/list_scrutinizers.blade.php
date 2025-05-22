@push('styles')
    <style>
        .card-list-of-scrutinizers {
            background-color: white; /* starting point */
            transition: background-color 0.6s ease-in-out;
        }

        .card-list-of-scrutinizers.fade-highlight {
            background-color: #28a745; /* strong green */
        }

        .card-list-of-scrutinizers.fade-out {
            background-color: white;
        }
    </style>
@endpush
<form id="form-list-of-scrutinizers" action="{{ route('scrutinizers.store') }}" method="POST">
    @csrf
    <input type="hidden" id="sid" name="sid" value="{{$sid}}">
    <div class="row mb-5">
        <div class="col-md-12">
            <section class="card card-featured card-featured-primary">
                <header class="card-header">
                    <h2 class="card-title">List of Scrutinizers (@ 24/- per script,min 1000/- per scrutinizers)
                    </h2>
                </header>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            @if(isset($all_course_with_teacher['courses']))
                                @foreach($all_course_with_teacher['courses'] as $courseData)
                                    @php
                                        $single_course = $courseData['courseObject'];
                                    @endphp
                                    <section class="card card-featured card-featured-secondary">
                                        <header class="card-header">
                                            <h2 class="card-title">
                                                Course: {{ $single_course['courseno'] }} - {{ $single_course['coursetitle'] }}
                                            </h2>
                                        </header>

                                        <div class="card-body card-list-of-scrutinizers">
                                            <div class="form-group row pb-3">
                                                <label class="col-md-2 control-label text-lg-end pt-2 ">Select Teacher</label>
                                                <div class="col-md-6">
                                                    <div class="input-group input-group-select-append">
														<span class="input-group-text">
															<i class="fas fa-th-list"></i>
														</span>
                                                        <select class="form-control"
                                                                name="scrutinizer_teacher_ids[{{ $single_course['id'] }}][]"
                                                                multiple="multiple"
                                                                data-plugin-multiselect
                                                                data-plugin-options='{ "maxHeight": 300 }'
                                                                id="ms_example5"
                                                                required>
                                                            @foreach($teachers as $teacherOption)
                                                                <option
                                                                    value="{{ $teacherOption->id }}">
                                                                    {{ $teacherOption->user->name }}
                                                                    - {{ $teacherOption->designation->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <label class="col-md-2 control-label text-lg-end pt-2 ">Total Student</label>
                                                <div class="col-md-2">
                                                    <input
                                                        name="scrutinizers_no_of_students[{{ $single_course['id'] }}]"
                                                        type="number" min="1"
                                                        step="any" class="form-control"
                                                        value="{{$courseData['registered_students_count']}}"
                                                        required>
                                                </div>

                                            </div>

                                        </div>

                                    </section>
                                @endforeach
                            @endif


                            <div class="text-end mt-3">
                                <button id="submit-list-of-scrutinizers" type="submit" class="btn btn-primary">
                                    Submit
                                    Scrutinizers Committee
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
            const form = document.getElementById('form-list-of-scrutinizers');

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

                                const submitBtn = document.getElementById('submit-list-of-scrutinizers');
                                submitBtn.textContent = 'Already Saved';             // ✅ Change text
                                submitBtn.disabled = true;                           // ✅ Disable button
                                submitBtn.classList.remove('btn-primary');           // ✅ Remove old style
                                submitBtn.classList.add('btn-success');              // ✅ Add success style

                                const cards = document.querySelectorAll('.card-list-of-scrutinizers');

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
                                    text: error.message||'Something went wrong. Please try again.',
                                    icon: 'error'
                                });
                            });
                    }
                });
            });
        });
    </script>
@endpush


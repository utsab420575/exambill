@push('styles')
    <style>
        .card-list-of-supervision_under_chairman_exam_committee {
            background-color: white; /* starting point */
            transition: background-color 0.6s ease-in-out;
        }

        .card-list-of-supervision_under_chairman_exam_committee.fade-highlight {
            background-color: #28a745; /* strong green */
        }

        .card-list-of-supervision_under_chairman_exam_committee.fade-out {
            background-color: white;
        }

        select.is-invalid {
            border-color: red;
        }
    </style>
@endpush
<form id="form-list-of-supervision_under_chairman_exam_committee"
      action="{{ route('supervision.under.chairman.exam.committee.store') }}" method="POST">
    @csrf
    <input type="hidden" id="sid" name="sid" value="{{$sid}}">
    <div class="row mb-5">
        <div class="col-md-12">
            <section class="card card-featured card-featured-primary">
                <header class="card-header">
                    <h2 class="card-title">Work Done Under the Supervision of the Chairman(Exam Committee)
                    </h2>
                </header>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <section class="card card-featured card-featured-secondary ">
                                <header class="card-header">
                                    <h2 class="card-title">Stencil Cutting of Question paper (@ 115/- per stencil):</h2>
                                </header>

                                <div class="card-body card-list-of-supervision_under_chairman_exam_committee">
                                    <div class="form-group row pb-3">
                                        {{-- Select Teacher --}}
                                        <label class="col-md-2 control-label text-lg-end pt-2">Select Teacher(s)</label>
                                        <div class="col-md-6">
                                            <div class="input-group input-group-select-append">
                                                <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                {{--//for multiple value send : array is used teachers[]
                                                //if select two teacher id is[110,120]
                                                //than recive in controller 'teachers' => [110, 120]
                                                //$request->input('teachers');
                                                // or simply
                                                $request->teachers;--}}
                                                <select class="form-control"
                                                        name="teachersStencilCutting[]"
                                                        multiple
                                                        data-plugin-multiselect
                                                        data-plugin-options='{ "maxHeight": 300 }'
                                                        required>
                                                    @foreach($teachers as $teacherOption)
                                                        <option value="{{ $teacherOption->id }}">
                                                            {{ $teacherOption->user->name }}
                                                            - {{ $teacherOption->designation->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Total Students --}}
                                        <label class="col-md-2 control-label text-lg-end pt-2">Total No. of Stencils</label>
                                        <div class="col-md-2">
                                            <input type="number"
                                                   name="total_stencils_cutting"
                                                   min="0"
                                                   step="any"
                                                   class="form-control"
                                                   inputmode="decimal"
                                                   required>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <section class="card card-featured card-featured-secondary ">
                                <header class="card-header">
                                    <h2 class="card-title">Printing of question papers (@35/- per stencil)</h2>
                                </header>

                                <div class="card-body card-list-of-supervision_under_chairman_exam_committee">
                                    <div class="form-group row pb-3">
                                        {{-- Select Teacher --}}
                                        <label class="col-md-2 control-label text-lg-end pt-2">Select Teacher(s)</label>
                                        <div class="col-md-6">
                                            <div class="input-group input-group-select-append">
                                                <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                {{--//for multiple value send : array is used teachers[]
                                                //if select two teacher id is[110,120]
                                                //than recive in controller 'teachers' => [110, 120]
                                                //$request->input('teachers');
                                                // or simply
                                                $request->teachers;--}}
                                                <select class="form-control"
                                                        name="teachersPrinting[]"
                                                        multiple
                                                        data-plugin-multiselect
                                                        data-plugin-options='{ "maxHeight": 300 }'
                                                        required>
                                                    @foreach($teachers as $teacherOption)
                                                        <option value="{{ $teacherOption->id }}">
                                                            {{ $teacherOption->user->name }}
                                                            - {{ $teacherOption->designation->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Total Students --}}
                                        <label class="col-md-2 control-label text-lg-end pt-2">Total No. of Stencils</label>
                                        <div class="col-md-2">
                                            <input type="number"
                                                   name="total_stencils_printing"
                                                   min="0"
                                                   step="any"
                                                   class="form-control"
                                                   inputmode="decimal"
                                                   required>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <section class="card card-featured card-featured-secondary ">
                                <header class="card-header">
                                    <h2 class="card-title">Comparison,Correction,sketching and distribution of question papers (@ 1350/- per question) :</h2>
                                </header>

                                <div class="card-body card-list-of-supervision_under_chairman_exam_committee">
                                    <div class="form-group row pb-3">
                                        {{-- Select Teacher --}}
                                        <label class="col-md-2 control-label text-lg-end pt-2">Select Teacher(s)</label>
                                        <div class="col-md-6">
                                            <div class="input-group input-group-select-append">
                                                <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                {{--//for multiple value send : array is used teachers[]
                                                //if select two teacher id is[110,120]
                                                //than recive in controller 'teachers' => [110, 120]
                                                //$request->input('teachers');
                                                // or simply
                                                $request->teachers;--}}
                                                <select class="form-control"
                                                        name="teachersComparision[]"
                                                        multiple
                                                        data-plugin-multiselect
                                                        data-plugin-options='{ "maxHeight": 300 }'
                                                        required>
                                                    @foreach($teachers as $teacherOption)
                                                        <option value="{{ $teacherOption->id }}">
                                                            {{ $teacherOption->user->name }}
                                                            - {{ $teacherOption->designation->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Total Students --}}
                                        <label class="col-md-2 control-label text-lg-end pt-2">Number of Questions</label>
                                        <div class="col-md-2">
                                            <input type="number"
                                                   name="total_question_comparison"
                                                   min="0"
                                                   step="any"
                                                   class="form-control"
                                                   inputmode="decimal"
                                                   required>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <div class="text-end mt-3">
                                <button id="submit-list-of-supervision_under_chairman_exam_committee" type="submit"
                                        class="btn btn-primary">
                                    Submit Cutting Printing Comparison
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
            const form = document.getElementById('form-list-of-supervision_under_chairman_exam_committee');

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                // ✅ Validate teacher selections
                const teacherSelects = form.querySelectorAll('select[name^="teachers"]');
                let allSelected = true;

                teacherSelects.forEach(select => {
                    if (select.selectedOptions.length === 0) {
                        allSelected = false;
                        select.classList.add('is-invalid'); // red border if invalid
                    } else {
                        select.classList.remove('is-invalid');
                    }
                });

                if (!allSelected) {
                    Swal.fire({
                        title: 'Missing Teacher',
                        text: 'Please select at least one teacher for each course.',
                        icon: 'warning'
                    });
                    return; // ❌ stop form submission
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

                                const submitBtn = document.getElementById('submit-list-of-supervision_under_chairman_exam_committee');
                                submitBtn.textContent = 'Already Saved';             // ✅ Change text
                                submitBtn.disabled = true;                           // ✅ Disable button
                                submitBtn.classList.remove('btn-primary');           // ✅ Remove old style
                                submitBtn.classList.add('btn-success');              // ✅ Add success style

                                const cards = document.querySelectorAll('.card-list-of-supervision_under_chairman_exam_committee');

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


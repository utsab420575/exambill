@push('styles')
    <style>
        .card-list-of-conducted_oral_exam {
            background-color: white;
            transition: background-color 0.6s ease-in-out;
        }

        .card-list-of-conducted_oral_exam.fade-highlight {
            background-color: #28a745;
        }

        .card-list-of-conducted_oral_exam.fade-out {
            background-color: white;
        }

        select.is-invalid, input.is-invalid {
            border-color: red;
        }
    </style>
@endpush

<form id="form-list-of-conducted_oral_exam" action="{{ route('conducted.oral.exam.store') }}" method="POST">
    @csrf
    <input type="hidden" name="sid" value="{{$sid}}">
    <div class="row mb-5">
        <div class="col-md-12">
            <section class="card card-featured card-featured-primary">
                <header class="card-header">
                    <h2 class="card-title">List of Teachers conducted central oral examination of thesis/projects (@ 150/- per student):</h2>
                </header>

                <div class="card-body card-list-of-conducted_oral_exam">
                    <div class="row mb-2 fw-bold">
                        <div class="col-md-1 text-center">Select</div>
                        <div class="col-md-6">Teacher</div>
                        <div class="col-md-4">No of Students</div>
                    </div>

                    <div id="dynamic-conducted-oral-exam-container"></div>

                    <div class="mt-3 text-end">
                        <button type="button" id="add-conducted-oral-teacher-row" class="btn btn-sm btn-success me-2">+ Add Teacher</button>
                        <button type="button" id="remove-conducted-oral-teacher-row" class="btn btn-sm btn-danger">- Remove Last</button>
                    </div>

                    <div class="text-end mt-3">
                        <button id="submit-list-of-conducted_oral_exam" type="submit" class="btn btn-primary">
                            Submit Oral Examination Committee
                        </button>
                    </div>
                </div>
            </section>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        let rowCount = 0;
        const teachers = @json($teachers);

        function createTeacherRow() {
            rowCount++;

            const container = document.getElementById('dynamic-conducted-oral-exam-container');
            const row = document.createElement('div');
            row.classList.add('row', 'align-items-center', 'mb-2');
            row.setAttribute('data-row', rowCount);

            row.innerHTML = `
            <div class="col-md-1 text-center">
                <input type="checkbox" class="form-check-input conducted-oral-exam-toggle-input" data-row="${rowCount}">
            </div>
            <div class="col-md-6">
                <select name="conducted_oral_exam_teacher_ids[]" class="form-select teacher-select" data-row="${rowCount}" disabled required>
                    <option value="">-- Select Teacher --</option>
                    ${teachers.map(t => `<option value="${t.id}">${t.user.name}, ${t.designation.name}</option>`).join('')}
                </select>
            </div>
            <div class="col-md-4">
                <input type="number" name="conducted_oral_exam_amounts[]" class="form-control amount-input" placeholder="No of students" disabled required min="1">
            </div>
        `;

            container.appendChild(row);

            const checkbox = row.querySelector('.conducted-oral-exam-toggle-input');
            checkbox.addEventListener('change', function () {
                const isChecked = this.checked;
                const rowIndex = this.getAttribute('data-row');
                const select = row.querySelector(`.teacher-select[data-row="${rowIndex}"]`);
                const amountInput = row.querySelector('.amount-input');

                select.disabled = !isChecked;
                amountInput.disabled = !isChecked;

                if (!isChecked) {
                    select.value = '';
                    amountInput.value = '';
                    select.classList.remove('is-invalid');
                    amountInput.classList.remove('is-invalid');
                }
            });
        }

        document.getElementById('add-conducted-oral-teacher-row').addEventListener('click', createTeacherRow);

        document.getElementById('remove-conducted-oral-teacher-row').addEventListener('click', function () {
            const container = document.getElementById('dynamic-conducted-oral-exam-container');
            if (container.lastElementChild) {
                container.removeChild(container.lastElementChild);
                rowCount--;
            }
        });

        document.getElementById('form-list-of-conducted_oral_exam').addEventListener('submit', function (e) {
            e.preventDefault();

            const form = this;
            const checkedRows = form.querySelectorAll('.conducted-oral-exam-toggle-input:checked');

            if (checkedRows.length === 0) {
                Swal.fire('No Teachers Selected', 'Please select at least one teacher and fill all required fields.', 'warning');
                return;
            }

            // Validation
            let valid = true;
            let teacherIds = [];

            checkedRows.forEach(checkbox => {
                const row = checkbox.closest('.row');
                const select = row.querySelector('.teacher-select');
                const input = row.querySelector('.amount-input');

                select.classList.remove('is-invalid');
                input.classList.remove('is-invalid');

                const teacherId = select.value;
                const amount = input.value;

                if (!teacherId) {
                    select.classList.add('is-invalid');
                    valid = false;
                }

                if (!amount || amount <= 0) {
                    input.classList.add('is-invalid');
                    valid = false;
                }

                if (teacherIds.includes(teacherId)) {
                    select.classList.add('is-invalid');
                    valid = false;
                }

                teacherIds.push(teacherId);
            });

            if (!valid) {
                Swal.fire('Validation Failed', 'Make sure each selected row is complete and teachers are not duplicated.', 'error');
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
                            Swal.fire('Success!', data.message, 'success');

                            const submitBtn = document.getElementById('submit-list-of-conducted_oral_exam');
                            submitBtn.textContent = 'Already Saved';
                            submitBtn.disabled = true;
                            submitBtn.classList.remove('btn-primary');
                            submitBtn.classList.add('btn-success');

                            const cards = document.querySelectorAll('.card-list-of-conducted_oral_exam');
                            cards.forEach(card => {
                                card.classList.add('fade-highlight');
                                setTimeout(() => card.classList.add('fade-out'), 1000);
                                setTimeout(() => card.classList.remove('fade-highlight', 'fade-out'), 1900);
                            });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
                        });
                }
            });
        });
    </script>
@endpush

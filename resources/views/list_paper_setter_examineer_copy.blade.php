<form id="form-list-of-examiner-paper-setter" action="{{ route('examiner.paper.setter.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <section class="card card-featured card-featured-primary">
                <header class="card-header">
                    <h2 class="card-title">List of Examiners (@ 200/- per script,min 1000/- per examiner) &Paper Setters
                        (@3600/- per paper setter)</h2>
                </header>

                <div class="card-body">


                    <section class="card card-featured card-featured-info">
                        <header class="card-header">
                            <h2 class="card-title">Course: Arch-5821</h2>
                        </header>
                        <div class="card-body">
                            <table id="table-list-of-examiner-paper-setter"
                                   class="table table-responsive-md table-striped mb-0">
                                <thead>
                                <tr>
                                    <th style="width: 30%;">Paper Setter</th>
                                    <th style="width: 30%;">Examiner</th>
                                    <th style="width: 20%;">No of scrips</th>
                                    <th style="width: 20%;">Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                {{-- Head Row --}}
                                <tr>
                                    <td>
                                        <select name="member_ids[]" class="form-control" required>
                                            <option value="">-- Select Teacher --</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}">
                                                    {{ $teacher->user->name }} - {{ $teacher->designation->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="member_ids[]" class="form-control" required>
                                            <option value="">-- Select Teacher --</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}">
                                                    {{ $teacher->user->name }} - {{ $teacher->designation->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="head_amount" class="form-control" required>
                                    </td>
                                    <td>
                                        <input type="number" name="head_amount" class="form-control" required>
                                    </td>
                                </tr>

                                {{-- Member Row --}}
                                <tr>
                                    <td>
                                        <select name="member_ids[]" class="form-control" required>
                                            <option value="">-- Select Teacher --</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}">
                                                    {{ $teacher->user->name }} - {{ $teacher->designation->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="member_ids[]" class="form-control" required>
                                            <option value="">-- Select Teacher --</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}">
                                                    {{ $teacher->user->name }} - {{ $teacher->designation->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="member_amounts[]" class="form-control" required>
                                    </td>
                                    <td>
                                        <input type="number" name="member_amounts[]" class="form-control" required>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </section>

                    <div class="text-end mt-3">
                        <button id="submit-list-of-examiner-paper-setter" type="submit" class="btn btn-primary">Submit
                            Committee
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
            const form = document.getElementById('form-list-of-examiner-paper-setter');

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

                                const submitBtn = document.getElementById('submit-list-of-examiner-paper-setter');
                                submitBtn.textContent = 'Already Saved';             // ✅ Change text
                                submitBtn.disabled = true;                           // ✅ Disable button
                                submitBtn.classList.remove('btn-primary');           // ✅ Remove old style
                                submitBtn.classList.add('btn-success');              // ✅ Add success style

                                const cells = document.querySelectorAll('#table-list-of-examiner-paper-setter td');

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


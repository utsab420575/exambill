<form id="form-list-of-examination-committee"   action="{{ route('examination.moderation.committee.store') }}" method="POST">
    @csrf
    <div class="row mb-5">
        <div class="col-md-12">
            <section class="card card-featured card-featured-primary">
                <header class="card-header">
                    <h2 class="card-title">List of Examination Committee/Moderation Committee Members @ min 1500</h2>
                </header>
                <div class="card-body">
                    <table id="table-list-of-examination-committee" class="table table-responsive-md table-striped  mb-0">
                        <thead>
                        <tr>
                            <th  style="width: 50%;">Name</th>
                            <th style="width: 30%;">Position</th>
                            <th style="width: 20%;">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        {{-- Head Row --}}
                        <tr>
                            <td>
                                {{ $teacher_head->user->name ?? 'N/A' }},<small>{{ $teacher_head->designation->name ?? '' }}</small>
                                <input type="hidden" name="head_id" value="{{ $teacher_head->id }}">
                            </td>
                            <td>Chairman</td>
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
                                        @if($teacher_head->id != $teacher->id)
                                            <option value="{{ $teacher->id }}">
                                                {{ $teacher->user->name }} - {{ $teacher->designation->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                            <td>Member</td>
                            <td>
                                <input type="number" name="member_amounts[]" class="form-control" required>
                            </td>
                        </tr>



                        </tbody>
                    </table>

                    <div class="text-end mt-3">
                        <button type="submit" id="submit-list-of-examination-committee" class="btn btn-primary">Submit Examination Committee</button>
                    </div>
                </div>
            </section>
        </div>
    </div>
</form>


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        //This selects the form by its ID.
        const form = document.getElementById('form-list-of-examination-committee');


       /* This adds a submit event listener to the form. When the form is submitted:

            e.preventDefault(); stops the normal form submission.

            Instead, a confirmation alert (using SweetAlert2) is shown.*/
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
                    //Creates a FormData object with all input data from the form (including hidden fields and arrays).
                    const formData = new FormData(form);

                    //This sends an AJAX POST request to the form's action URL using the form data.
                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    })
                        //When the server replies, it reads the response as JSON.
                        .then(response => response.json())  // <== Must be here
                        .then(data => {
                            console.log("Server response:", data); // Debug log
                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });

                            //submit button color and name changed
                            const submitBtn = document.getElementById('submit-list-of-examination-committee');
                            submitBtn.textContent = 'Already Saved';             // ✅ Change text
                            submitBtn.disabled = true;                           // ✅ Disable button
                            submitBtn.classList.remove('btn-primary');           // ✅ Remove old style
                            submitBtn.classList.add('btn-success');              // ✅ Add success style

                            //each row of table changed
                            const cells = document.querySelectorAll('#table-list-of-examination-committee td');

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

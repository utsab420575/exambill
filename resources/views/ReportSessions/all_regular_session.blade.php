@extends('layouts.app')

@section('content')
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>All Sessions</h2>

            <div class="right-wrapper text-end">
                <ol class="breadcrumbs">
                    <li>
                        <a href="index.html">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>

                    <li><span>Layouts</span></li>

                    <li><span>Light Sidebar</span></li>

                </ol>

                <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fas fa-chevron-left"></i></a>
            </div>
        </header>

        <!-- start: page -->
        <div class="row">

            <div class="row">
                <div class="col">
                    <section class="card">
                        <header class="card-header">
                            <h2 class="card-title">Select Regular Session</h2>
                        </header>
                        <div class="card-body">
                            <form id="sessionForm" class="form-horizontal form-bordered" method="get">
                                <div class="form-group row pb-1">
                                    <label class="col-lg-3 control-label text-lg-end pt-2">Select Session</label>
                                    <div class="col-lg-6">
                                        @php use Illuminate\Support\Facades\Crypt; @endphp
                                        <select class="form-control mb-3" id="sessionSelect">
                                            <option value="">-- Select Session --</option>
                                            @foreach($sessions as $session)
                                               {{-- <option value="{{ Crypt::encryptString($session['id']) }}">--}}
                                                <option value="{{$session['id']}}">
                                                    {{ $session['session'] }} - Year {{ $session['year'] }}, Semester {{ $session['semester'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="mb-1 justify-content-center btn btn-success btn-lg btn-block">Submit Session</button>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>




        <!-- end: page -->
    </section>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('sessionForm');
        const select = document.getElementById('sessionSelect');

        if (form && select) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const selectedId = select.value;

                if (selectedId) {
                    const routeBase = "{{ url('/report/sessions/regular/generate/') }}";
                    window.location.href = `${routeBase}/${selectedId}`;
                } else {
                    alert('Please select a session.');
                }
            });
        }
    });
</script>

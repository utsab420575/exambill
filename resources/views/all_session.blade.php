@extends('layouts.app')

@section('content')
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Light Sidebar Layout</h2>

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
                            <div class="card-actions">
                                <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                                <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                            </div>

                            <h2 class="card-title">Multi-select</h2>
                        </header>
                        <div class="card-body">
                            <form class="form-horizontal form-bordered" action="#">
                                <div class="form-group row pb-3">
                                    <label class="col-lg-3 control-label text-lg-end pt-2">Basic Multi-select</label>
                                    <div class="col-lg-6">
													<span class="multiselect-native-select"><select class="form-control" multiple="multiple" data-plugin-multiselect="" data-plugin-options="{ &quot;maxHeight&quot;: 200 }" id="ms_example0">
														<option value="cheese">Cheese</option>
														<option value="tomatoes" selected="">Tomatoes</option>
														<option value="mozarella" selected="">Mozzarella</option>
														<option value="mushrooms">Mushrooms</option>
														<option value="pepperoni">Pepperoni</option>
														<option value="onions">Onions</option>
													</select><div class="btn-group"><button type="button" class="multiselect dropdown-toggle form-select text-center" data-bs-toggle="dropdown" title="Tomatoes, Mozzarella"><span class="multiselect-selected-text text-1">Tomatoes, Mozzarella</span></button><div class="multiselect-container dropdown-menu" style="max-height: 200px; overflow: hidden auto;"><button type="button" class="multiselect-option dropdown-item" title="Cheese"><span class="form-check"><input class="form-check-input" type="checkbox" value="cheese"><label class="form-check-label">Cheese</label></span></button><button type="button" class="multiselect-option dropdown-item active" title="Tomatoes"><span class="form-check"><input class="form-check-input" type="checkbox" value="tomatoes"><label class="form-check-label">Tomatoes</label></span></button><button type="button" class="multiselect-option dropdown-item active" title="Mozzarella"><span class="form-check"><input class="form-check-input" type="checkbox" value="mozarella"><label class="form-check-label">Mozzarella</label></span></button><button type="button" class="multiselect-option dropdown-item" title="Mushrooms"><span class="form-check"><input class="form-check-input" type="checkbox" value="mushrooms"><label class="form-check-label">Mushrooms</label></span></button><button type="button" class="multiselect-option dropdown-item" title="Pepperoni"><span class="form-check"><input class="form-check-input" type="checkbox" value="pepperoni"><label class="form-check-label">Pepperoni</label></span></button><button type="button" class="multiselect-option dropdown-item" title="Onions"><span class="form-check"><input class="form-check-input" type="checkbox" value="onions"><label class="form-check-label">Onions</label></span></button></div></div></span>
                                    </div>
                                </div>
                                <div class="form-group row pb-3">
                                    <label class="col-lg-3 control-label text-lg-end pt-2">Basic Multi-select (Only One)</label>
                                    <div class="col-lg-6">
													<span class="multiselect-native-select"><select class="form-control" data-plugin-multiselect="" data-plugin-options="{ &quot;maxHeight&quot;: 200 }" id="ms_example1">
														<option value="cheese" selected="">Cheese</option>
														<option value="tomatoes">Tomatoes</option>
														<option value="mozarella">Mozzarella</option>
														<option value="mushrooms">Mushrooms</option>
														<option value="pepperoni">Pepperoni</option>
														<option value="onions">Onions</option>
													</select><div class="btn-group"><button type="button" class="multiselect dropdown-toggle form-select text-center" data-bs-toggle="dropdown" title="Cheese"><span class="multiselect-selected-text text-1">Cheese</span></button><div class="multiselect-container dropdown-menu" style="max-height: 200px; overflow: hidden auto;"><button type="button" class="multiselect-option dropdown-item active" title="Cheese"><span class="form-check"><input class="form-check-input" type="radio" value="cheese"><label class="form-check-label">Cheese</label></span></button><button type="button" class="multiselect-option dropdown-item" title="Tomatoes"><span class="form-check"><input class="form-check-input" type="radio" value="tomatoes"><label class="form-check-label">Tomatoes</label></span></button><button type="button" class="multiselect-option dropdown-item" title="Mozzarella"><span class="form-check"><input class="form-check-input" type="radio" value="mozarella"><label class="form-check-label">Mozzarella</label></span></button><button type="button" class="multiselect-option dropdown-item" title="Mushrooms"><span class="form-check"><input class="form-check-input" type="radio" value="mushrooms"><label class="form-check-label">Mushrooms</label></span></button><button type="button" class="multiselect-option dropdown-item" title="Pepperoni"><span class="form-check"><input class="form-check-input" type="radio" value="pepperoni"><label class="form-check-label">Pepperoni</label></span></button><button type="button" class="multiselect-option dropdown-item" title="Onions"><span class="form-check"><input class="form-check-input" type="radio" value="onions"><label class="form-check-label">Onions</label></span></button></div></div></span>
                                    </div>
                                </div>
                                <div class="form-group row pb-3">
                                    <label class="col-lg-3 control-label text-lg-end pt-2">With Preselected Options</label>
                                    <div class="col-lg-6">
													<span class="multiselect-native-select"><select class="form-control" multiple="multiple" data-plugin-options="{ &quot;maxHeight&quot;: 200 }" data-plugin-multiselect="" id="ms_example2">
														<option value="cheese" selected="">Cheese</option>
														<option value="tomatoes" selected="">Tomatoes</option>
														<option value="mozarella" selected="">Mozzarella</option>
														<option value="mushrooms">Mushrooms</option>
														<option value="pepperoni">Pepperoni</option>
														<option value="onions">Onions</option>
													</select><div class="btn-group"><button type="button" class="multiselect dropdown-toggle form-select text-center" data-bs-toggle="dropdown" title="Cheese, Tomatoes, Mozzarella"><span class="multiselect-selected-text text-1">Cheese, Tomatoes, Mozzarella</span></button><div class="multiselect-container dropdown-menu" style="max-height: 200px; overflow: hidden auto;"><button type="button" class="multiselect-option dropdown-item active" title="Cheese"><span class="form-check"><input class="form-check-input" type="checkbox" value="cheese"><label class="form-check-label">Cheese</label></span></button><button type="button" class="multiselect-option dropdown-item active" title="Tomatoes"><span class="form-check"><input class="form-check-input" type="checkbox" value="tomatoes"><label class="form-check-label">Tomatoes</label></span></button><button type="button" class="multiselect-option dropdown-item active" title="Mozzarella"><span class="form-check"><input class="form-check-input" type="checkbox" value="mozarella"><label class="form-check-label">Mozzarella</label></span></button><button type="button" class="multiselect-option dropdown-item" title="Mushrooms"><span class="form-check"><input class="form-check-input" type="checkbox" value="mushrooms"><label class="form-check-label">Mushrooms</label></span></button><button type="button" class="multiselect-option dropdown-item" title="Pepperoni"><span class="form-check"><input class="form-check-input" type="checkbox" value="pepperoni"><label class="form-check-label">Pepperoni</label></span></button><button type="button" class="multiselect-option dropdown-item" title="Onions"><span class="form-check"><input class="form-check-input" type="checkbox" value="onions"><label class="form-check-label">Onions</label></span></button></div></div></span>
                                    </div>
                                </div>
                                <div class="form-group row pb-3">
                                    <label class="col-lg-3 control-label text-lg-end pt-2">Link button</label>
                                    <div class="col-lg-6">
													<span class="multiselect-native-select"><select class="form-control" multiple="multiple" data-plugin-multiselect="" data-plugin-options="{ &quot;maxHeight&quot;: 200, &quot;buttonClass&quot;: &quot;btn btn-link ps-0 pe-0&quot; }" id="ms_example3">
														<option value="cheese">Cheese</option>
														<option value="tomatoes">Tomatoes</option>
														<option value="mozarella">Mozzarella</option>
														<option value="mushrooms">Mushrooms</option>
														<option value="pepperoni">Pepperoni</option>
														<option value="onions">Onions</option>
													</select><div class="btn-group"><button type="button" class="multiselect dropdown-toggle btn btn-link ps-0 pe-0 text-center" data-bs-toggle="dropdown" title="None selected"><span class="multiselect-selected-text text-1">None selected</span></button><div class="multiselect-container dropdown-menu" style="max-height: 200px; overflow: hidden auto;"><button type="button" class="multiselect-option dropdown-item" title="Cheese"><span class="form-check"><input class="form-check-input" type="checkbox" value="cheese"><label class="form-check-label">Cheese</label></span></button><button type="button" class="multiselect-option dropdown-item" title="Tomatoes"><span class="form-check"><input class="form-check-input" type="checkbox" value="tomatoes"><label class="form-check-label">Tomatoes</label></span></button><button type="button" class="multiselect-option dropdown-item" title="Mozzarella"><span class="form-check"><input class="form-check-input" type="checkbox" value="mozarella"><label class="form-check-label">Mozzarella</label></span></button><button type="button" class="multiselect-option dropdown-item" title="Mushrooms"><span class="form-check"><input class="form-check-input" type="checkbox" value="mushrooms"><label class="form-check-label">Mushrooms</label></span></button><button type="button" class="multiselect-option dropdown-item" title="Pepperoni"><span class="form-check"><input class="form-check-input" type="checkbox" value="pepperoni"><label class="form-check-label">Pepperoni</label></span></button><button type="button" class="multiselect-option dropdown-item" title="Onions"><span class="form-check"><input class="form-check-input" type="checkbox" value="onions"><label class="form-check-label">Onions</label></span></button></div></div></span>
                                    </div>
                                </div>
                                <div class="form-group row pb-3">
                                    <label class="col-lg-3 control-label text-lg-end pt-2">With icon</label>
                                    <div class="col-lg-6">
                                        <div class="input-group input-group-select-append">
														<span class="input-group-text">
															<i class="fas fa-th-list"></i>
														</span>
                                            <span class="multiselect-native-select"><select class="form-control" multiple="multiple" data-plugin-multiselect="" data-plugin-options="{ &quot;maxHeight&quot;: 200 }" id="ms_example4">
															<option value="cheese">Cheese</option>
															<option value="tomatoes">Tomatoes</option>
															<option value="mozarella">Mozzarella</option>
															<option value="mushrooms">Mushrooms</option>
															<option value="pepperoni">Pepperoni</option>
															<option value="onions">Onions</option>
														</select><div class="btn-group"><button type="button" class="multiselect dropdown-toggle form-select text-center" data-bs-toggle="dropdown" title="None selected"><span class="multiselect-selected-text text-1">None selected</span></button><div class="multiselect-container dropdown-menu" style="max-height: 200px; overflow: hidden auto;"><button type="button" class="multiselect-option dropdown-item" title="Cheese"><span class="form-check"><input class="form-check-input" type="checkbox" value="cheese"><label class="form-check-label">Cheese</label></span></button><button type="button" class="multiselect-option dropdown-item" title="Tomatoes"><span class="form-check"><input class="form-check-input" type="checkbox" value="tomatoes"><label class="form-check-label">Tomatoes</label></span></button><button type="button" class="multiselect-option dropdown-item" title="Mozzarella"><span class="form-check"><input class="form-check-input" type="checkbox" value="mozarella"><label class="form-check-label">Mozzarella</label></span></button><button type="button" class="multiselect-option dropdown-item" title="Mushrooms"><span class="form-check"><input class="form-check-input" type="checkbox" value="mushrooms"><label class="form-check-label">Mushrooms</label></span></button><button type="button" class="multiselect-option dropdown-item" title="Pepperoni"><span class="form-check"><input class="form-check-input" type="checkbox" value="pepperoni"><label class="form-check-label">Pepperoni</label></span></button><button type="button" class="multiselect-option dropdown-item" title="Onions"><span class="form-check"><input class="form-check-input" type="checkbox" value="onions"><label class="form-check-label">Onions</label></span></button></div></div></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row pb-3">
                                    <label class="col-lg-3 control-label text-lg-end pt-2">Select All Option</label>
                                    <div class="col-lg-6">
													<span class="multiselect-native-select"><select class="form-control" multiple="multiple" data-plugin-multiselect="" data-plugin-options="{ &quot;maxHeight&quot;: 200, &quot;includeSelectAllOption&quot;: true }" id="ms_example5">
														<optgroup label="Mathematics">
															<option value="analysis">Analysis</option>
															<option value="algebra">Linear Algebra</option>
															<option value="discrete">Discrete Mathematics</option>
															<option value="numerical">Numerical Analysis</option>
															<option value="probability">Probability Theory</option>
														</optgroup>
														<optgroup label="Computer Science">
															<option value="programming">Introduction to Programming</option>
															<option value="automata">Automata Theory</option>
															<option value="complexity">Complexity Theory</option>
															<option value="software">Software Engineering</option>
														</optgroup>
													</select><div class="btn-group"><button type="button" class="multiselect dropdown-toggle form-select text-center" data-bs-toggle="dropdown" title="None selected"><span class="multiselect-selected-text text-1">None selected</span></button><div class="multiselect-container dropdown-menu" style="max-height: 200px; overflow: hidden auto;"><li title=" Select all" class="multiselect-all"><span class="form-check"><input class="form-check-input" type="checkbox" value="multiselect-all"><label class="form-check-label font-weight-bold"> Select all</label></span><a class="dropdown-item" tabindex="0"><label style="display: block;"></label></a></li><span class="multiselect-group dropdown-item-text"> Mathematics</span><button type="button" class="multiselect-option dropdown-item multiselect-group-option-indented" title="Analysis"><span class="form-check"><input class="form-check-input" type="checkbox" value="analysis"><label class="form-check-label">Analysis</label></span></button><button type="button" class="multiselect-option dropdown-item multiselect-group-option-indented" title="Linear Algebra"><span class="form-check"><input class="form-check-input" type="checkbox" value="algebra"><label class="form-check-label">Linear Algebra</label></span></button><button type="button" class="multiselect-option dropdown-item multiselect-group-option-indented" title="Discrete Mathematics"><span class="form-check"><input class="form-check-input" type="checkbox" value="discrete"><label class="form-check-label">Discrete Mathematics</label></span></button><button type="button" class="multiselect-option dropdown-item multiselect-group-option-indented" title="Numerical Analysis"><span class="form-check"><input class="form-check-input" type="checkbox" value="numerical"><label class="form-check-label">Numerical Analysis</label></span></button><button type="button" class="multiselect-option dropdown-item multiselect-group-option-indented" title="Probability Theory"><span class="form-check"><input class="form-check-input" type="checkbox" value="probability"><label class="form-check-label">Probability Theory</label></span></button><span class="multiselect-group dropdown-item-text"> Computer Science</span><button type="button" class="multiselect-option dropdown-item multiselect-group-option-indented" title="Introduction to Programming"><span class="form-check"><input class="form-check-input" type="checkbox" value="programming"><label class="form-check-label">Introduction to Programming</label></span></button><button type="button" class="multiselect-option dropdown-item multiselect-group-option-indented" title="Automata Theory"><span class="form-check"><input class="form-check-input" type="checkbox" value="automata"><label class="form-check-label">Automata Theory</label></span></button><button type="button" class="multiselect-option dropdown-item multiselect-group-option-indented" title="Complexity Theory"><span class="form-check"><input class="form-check-input" type="checkbox" value="complexity"><label class="form-check-label">Complexity Theory</label></span></button><button type="button" class="multiselect-option dropdown-item multiselect-group-option-indented" title="Software Engineering"><span class="form-check"><input class="form-check-input" type="checkbox" value="software"><label class="form-check-label">Software Engineering</label></span></button></div></div></span>
                                    </div>
                                </div>
                                <div class="form-group row pb-3">
                                    <label class="col-lg-3 control-label text-lg-end pt-2">With Search</label>
                                    <div class="col-lg-6">
													<span class="multiselect-native-select"><select class="form-control" multiple="multiple" data-plugin-multiselect="" data-plugin-options="{ &quot;maxHeight&quot;: 200, &quot;enableCaseInsensitiveFiltering&quot;: true }" id="ms_example6">
														<optgroup label="Mathematics">
															<option value="analysis">Analysis</option>
															<option value="algebra">Linear Algebra</option>
															<option value="discrete">Discrete Mathematics</option>
															<option value="numerical">Numerical Analysis</option>
															<option value="probability">Probability Theory</option>
														</optgroup>
														<optgroup label="Computer Science">
															<option value="programming">Introduction to Programming</option>
															<option value="automata">Automata Theory</option>
															<option value="complexity">Complexity Theory</option>
															<option value="software">Software Engineering</option>
														</optgroup>
													</select><div class="btn-group"><button type="button" class="multiselect dropdown-toggle form-select text-center" data-bs-toggle="dropdown" title="None selected"><span class="multiselect-selected-text text-1">None selected</span></button><div class="multiselect-container dropdown-menu" style="max-height: 200px; overflow: hidden auto;"><div class="input-group"><span class="input-group-prepend"><span class="input-group-text"><i class="fas fa-search"></i></span></span><input class="form-control multiselect-search" type="text" placeholder="Search"></div><span class="multiselect-group dropdown-item-text"> Mathematics</span><button type="button" class="multiselect-option dropdown-item multiselect-group-option-indented" title="Analysis"><span class="form-check"><input class="form-check-input" type="checkbox" value="analysis"><label class="form-check-label">Analysis</label></span></button><button type="button" class="multiselect-option dropdown-item multiselect-group-option-indented" title="Linear Algebra"><span class="form-check"><input class="form-check-input" type="checkbox" value="algebra"><label class="form-check-label">Linear Algebra</label></span></button><button type="button" class="multiselect-option dropdown-item multiselect-group-option-indented" title="Discrete Mathematics"><span class="form-check"><input class="form-check-input" type="checkbox" value="discrete"><label class="form-check-label">Discrete Mathematics</label></span></button><button type="button" class="multiselect-option dropdown-item multiselect-group-option-indented" title="Numerical Analysis"><span class="form-check"><input class="form-check-input" type="checkbox" value="numerical"><label class="form-check-label">Numerical Analysis</label></span></button><button type="button" class="multiselect-option dropdown-item multiselect-group-option-indented" title="Probability Theory"><span class="form-check"><input class="form-check-input" type="checkbox" value="probability"><label class="form-check-label">Probability Theory</label></span></button><span class="multiselect-group dropdown-item-text"> Computer Science</span><button type="button" class="multiselect-option dropdown-item multiselect-group-option-indented" title="Introduction to Programming"><span class="form-check"><input class="form-check-input" type="checkbox" value="programming"><label class="form-check-label">Introduction to Programming</label></span></button><button type="button" class="multiselect-option dropdown-item multiselect-group-option-indented" title="Automata Theory"><span class="form-check"><input class="form-check-input" type="checkbox" value="automata"><label class="form-check-label">Automata Theory</label></span></button><button type="button" class="multiselect-option dropdown-item multiselect-group-option-indented" title="Complexity Theory"><span class="form-check"><input class="form-check-input" type="checkbox" value="complexity"><label class="form-check-label">Complexity Theory</label></span></button><button type="button" class="multiselect-option dropdown-item multiselect-group-option-indented" title="Software Engineering"><span class="form-check"><input class="form-check-input" type="checkbox" value="software"><label class="form-check-label">Software Engineering</label></span></button></div></div></span>
                                    </div>
                                </div>
                                <div class="form-group row pb-2">
                                    <label class="col-lg-3 control-label text-lg-end pt-2">Toggle All Button</label>
                                    <div class="col-lg-6">
                                        <div class="btn-group">
														<span class="multiselect-native-select"><select class="form-control" multiple="multiple" data-plugin-multiselect="" data-plugin-options="{ &quot;maxHeight&quot;: 200 }" data-multiselect-toggle-all="true" id="ms_example7">
															<option value="cheese">Cheese</option>
															<option value="tomatoes">Tomatoes</option>
															<option value="mozarella">Mozzarella</option>
															<option value="mushrooms">Mushrooms</option>
															<option value="pepperoni">Pepperoni</option>
															<option value="onions">Onions</option>
														</select><div class="btn-group"><button type="button" class="multiselect dropdown-toggle form-select text-center" data-bs-toggle="dropdown" title="None selected"><span class="multiselect-selected-text text-1">None selected</span></button><div class="multiselect-container dropdown-menu" style="max-height: 200px; overflow: hidden auto;"><button type="button" class="multiselect-option dropdown-item" title="Cheese"><span class="form-check"><input class="form-check-input" type="checkbox" value="cheese"><label class="form-check-label">Cheese</label></span></button><button type="button" class="multiselect-option dropdown-item" title="Tomatoes"><span class="form-check"><input class="form-check-input" type="checkbox" value="tomatoes"><label class="form-check-label">Tomatoes</label></span></button><button type="button" class="multiselect-option dropdown-item" title="Mozzarella"><span class="form-check"><input class="form-check-input" type="checkbox" value="mozarella"><label class="form-check-label">Mozzarella</label></span></button><button type="button" class="multiselect-option dropdown-item" title="Mushrooms"><span class="form-check"><input class="form-check-input" type="checkbox" value="mushrooms"><label class="form-check-label">Mushrooms</label></span></button><button type="button" class="multiselect-option dropdown-item" title="Pepperoni"><span class="form-check"><input class="form-check-input" type="checkbox" value="pepperoni"><label class="form-check-label">Pepperoni</label></span></button><button type="button" class="multiselect-option dropdown-item" title="Onions"><span class="form-check"><input class="form-check-input" type="checkbox" value="onions"><label class="form-check-label">Onions</label></span></button></div></div></span>
                                        </div>
                                        <div class="btn-group me-2">
                                            <button id="ms_example7-toggle" class="btn btn-primary ms-2">Select All</button>
                                        </div>
                                    </div>
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

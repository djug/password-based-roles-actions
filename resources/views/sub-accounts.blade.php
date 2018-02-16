@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-default">
                <div class="card-header">Sub Accounts</div>

                <div class="card-body">
                    <form method="POST" action="/sub-accounts">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Type</label>

                            <div class="col-md-6">
                                <input id="name" type="text" name="name" value="Restricted Account" class="form-control" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Password</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="restricted-password" required>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Type</label>

                            <div class="col-md-6">
                                <input id="name" type="text" name="name" value="Trigger (deactivate all accounts)" class="form-control" disabled>
                            </div>
                        </div>



                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Password</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="trigger-password" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

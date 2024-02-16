<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .register-container {
            margin-top: 5%;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center register-container">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Register</div>
                <div class="card-body">
                    <form action="{{ route('registerPost') }}" method="POST">
                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                @foreach ($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="name">Name</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="surname">Surname</label>
                                <input type="text" id="surname" name="surname" class="form-control" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Register</button>
                    </form>
                    <div class="mt-3">
                        Already have an account? <a href="{{ route('login') }}">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

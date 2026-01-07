<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="d-flex align-items-center min-vh-100 py-5 bg-light">

    <div class="container">
        <div class="mb-5 text-center text-lg-start">
            <a href="{{ url('/register') }}" class="btn btn-sm btn-outline-dark rounded-pill px-4">
                &larr; Home
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-9">

                <div class="card card-profile p-4 p-md-5">

                    <div class="d-flex align-items-center mb-5 border-bottom pb-4">
                        <div class="flex-shrink-0">
                            <img src=""
                                class="rounded-circle profile-avatar" alt="Avatar">
                        </div>
                        <div class="flex-grow-1 ms-4">
                            <h3 class="serif-text fs-2 mb-0">{{ $user->name }}</h3>
                            <p class="text-muted fs-5 mb-0" style="font-family: Georgia;">User</p>
                        </div>
                    </div>

                    <form>
                        <div class="row g-5">

                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="label-custom">Username</label>
                                    <input type="text" class="form-control form-control-pill"
                                        value="{{ $user->name }}" readonly>
                                </div>

                                <div class="mb-4">
                                    <label class="label-custom">Email</label>
                                    <input type="text" class="form-control form-control-pill"
                                        value="{{ $user->email }}" readonly>
                                </div>

                                <div class="mb-4">
                                    <label class="label-custom">Favorite Place</label>
                                    <input type="text" class="form-control form-control-pill"
                                        value="" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="label-custom">Password</label>
                                    <input type="password" class="form-control form-control-pill"
                                        value="{{$user->password}}" readonly>
                                </div>

                                <div class="mb-4">
                                    <label class="label-custom">Favorite Friend</label>
                                    <input type="text" class="form-control form-control-pill"
                                        value="" readonly>
                                </div>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
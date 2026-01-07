<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Friend List</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f5f5;
        }

        .friend-card {
            background-color: #e0e0e0;
            padding: 20px;
            text-align: center;
            border-radius: 4px;
        }

        .avatar {
            width: 80px;
            height: 80px;
            background-color: black;
            border-radius: 50%;
            margin: 0 auto 15px;
        }

        .friend-name {
            font-weight: bold;
        }

        .times {
            font-size: 14px;
            margin-bottom: 15px;
        }

        .btn-custom {
            background-color: #a8d5b5;
            border: none;
            font-size: 14px;
        }

        .btn-custom:hover {
            background-color: #95c9a5;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <h1 class="text-center mb-5">Friend List</h1>

    <div class="row g-4">
        @forelse ($friends as $f)
            <div class="col-md-3">
                <div class="friend-card">
                    <div class="avatar"></div>

                    <div class="friend-name">
                        {{ $f->friend->name }}
                    </div>

                    <div class="times">
                        Went out together {{ $f->times_together ?? 0 }} times
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="#" class="btn btn-custom btn-sm">History</a>
                        <a href="#" class="btn btn-custom btn-sm">Invites</a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">No friends found.</p>
        @endforelse
    </div>
</div>

</body>
</html>

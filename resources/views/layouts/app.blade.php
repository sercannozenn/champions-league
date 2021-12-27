<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <style>
        .card-header:first-child {
            border-radius: unset !important;
        }
        .fade-enter-active,
        .fade-leave-active {
            transition: opacity 1s
        }

        .fade-enter,
        .fade-leave-to
            /* .fade-leave-active in <2.1.8 */

        {
            opacity: 0
        }
        .fadeInLeft-leave-active,
        .fadeInLeft-enter-active {
            transition: 1s;
        }
        .fadeInLeft-enter {
            transform: translate(100%, 0);
        }
        .fadeInLeft-leave-to {
            transform: translate(-100%, 0);
        }

    </style>
</head>
<body>
<div id="app"></div>
<script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>

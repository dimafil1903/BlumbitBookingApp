<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
{{--        <link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    </head>
<body>
<header>
    <div class="container">
        <div class="logo">
            <img src="img/logo.png">
            <p>Blumbit</p>
        </div>
        <nav>
            <ul>
                <li><a href="#">Home</a></li>
                @guest

                    <li><a  href="{{ route('login') }}">Login</a></li>
                @else
                    <li>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                    </li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>

                @endguest

            </ul>
        </nav>
        <div class="todo-button">
            <a href="#send-task">Send Task</a>
        </div>
    </div>
</header>

<!-- BOOKING MODAL WINDOW -->
<div id="send-task" class="task-window">
    <div class="task-form-window">
        <div class="close-button">
            <a href="#">x</a>
        </div>
        <div class="task-form-content">
            <h2>Send a task</h2>
            <div class="task-area">
                <form>
                    <label for="title">Title</label>
                    <input type="text" name="title" placeholder="Enter booking title">
                    <label for="name">Name</label>
                    <input type="text" name="name" placeholder="Enter your name">
                    <label for="email">Email</label>
                    <input type="email" name="email" placeholder="Enter your email">
                    <label for="phone">Phone</label>
                    <input type="tel" name="phone" placeholder="Enter your phone number">
                    <label for="deadline">Deadline</label>
                    <input type="text" name="deadline" placeholder="Enter deadline date or time">
                    <label for="description">Description</label>
                    <textarea rows="5" name="description" maxlength="1000"></textarea>
                </form>
            </div>
            <div class="submit-button">
                <a href="#">Submit</a>
            </div>
        </div>
    </div>
</div>
    <div id="app">
        <welcome></welcome>

    </div>
    <script type="text/javascript" src="{{asset('js/app.js')}}"></script>

    </body>
</html>


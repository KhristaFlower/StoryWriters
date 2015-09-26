<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Story Writers</title>
    {{ HTML::style('css/style.css') }}
</head>
<body>
    <div class="wrapper">
        <!-- Navigation bar -->
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    {{ HTML::link('/', 'StoryWriters', ['class' => 'navbar-brand']) }}
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <?php
                        // Key => Value / Link => Display Text
                        $links = array(
                            //'/' => 'Home',
                            'about' => 'About',
                            'stories' => 'Stories',
                            'writers' => 'Writers'
                        );
                        ?>
                        {{-- Render all of the links --}}
                        @foreach ($links as $route => $text)
                            <li @if ($nav == $text) class="active" @endif>
                                {{ HTML::link($route, $text) }}
                            </li>
                        @endforeach
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        @if (Auth::check())
                            @if (false)
                                {{-- TODO: Convert this to environments, it is useful on dev but bad on production --}}
                                <?php
                                    $users = User::all();
                                ?>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        SA <b class="caret"></b>
                                    </a>
                                    <ul class="dropdown-menu">
                                        @foreach($users as $user)
                                            @if($user->id == Auth::user()->id)
                                                <li class="disabled"><a href="#">{{ $user->username }}</a></li>
                                            @else
                                                <li>{{ $user->become() }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </li>
                            @endif

                            <p class="navbar-text">
                                <a href="{{ URL::route('profile.index') }}">Logged in as {{ Auth::user()->username }}</a>
                            </p>
                            <li>{{ HTML::link('/logout', 'Logout') }}</li>
                        @else
                            <li @if($nav == 'Login'){{ 'class="active"' }} @endif>{{ HTML::link('/login', 'Login') }}</li>
                            <li @if($nav == 'Register'){{ 'class="active"' }} @endif>{{ HTML::link('/register', 'Register') }}</li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Page head -->
        @yield('pagehead_section')
        <!-- Main content -->
        <div class="sub-wrapper">
            <div class="container">
                @if(Session::get('flash_message'))
                    <?php $type = Session::get('flash_type'); ?>
                    <div class="alert alert-{{ $type or 'default' }} alert-flash">
                        {{ Session::get('flash_message') }}
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                            &times;
                        </button>
                    </div>
                @endif
                @yield('content')
                {{-- SQL LOG --}}
                {{--@include('partials.sqllog')--}}
            </div>
        </div>
    </div>

    <footer>
        <div class="static-center">
            <div class="container">
                <p>Copyright &copy; Christopher Sharman 2014</p>
            </div>
        </div>
    </footer>

    <!-- Load the Javascript files last -->
    {{ HTML::script('js/live.js') }}
    {{ HTML::script('js/jquery-1.10.2.min.js') }}
    {{ HTML::script('js/bootstrap.min.js') }}
    {{ HTML::script('js/custom.js') }}
</body>
</html>

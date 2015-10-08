<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="nav-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="/" class="navbar-brand">StoryWriters</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <?php
                    $links = [
                        'about' => 'About',
                        'stories' => 'Stories',
                        'writers' => 'Writers'
                    ];
                ?>
                @foreach ($links as $route => $text)
                    <li @if ($nav == $text) class="active" @endif>
                        {!! Html::link($route, $text) !!}
                    </li>
                @endforeach
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::check())
                    @if (false)
                        <?php
                            $users = User::all();
                        ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                SA <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                @foreach ($users as $user)
                                    @if ($user->id == Auth::user()->id)
                                        <li class="disabled">
                                            <a href="#">{{ $user->username }}</a>
                                        </li>
                                    @else
                                        <li>{{ $user->become() }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @endif

                    <li>
                        <a href="{{ URL::route('profile') }}">Logged in as {{ Auth::user()->username }}</a>
                    </li>
                    <li>{!! link_to_route('logout', 'Logout') !!}</li>
                @else
                    <li @if ($nav == 'Login') {!! 'class="active"' !!} @endif>{!! link_to_route('login', 'Login') !!}</li>
                    <li @if ($nav == 'Register') {!! 'class="active"' !!} @endif>{!! link_to_route('register', 'Register') !!}</li>
                @endif
            </ul>
        </div>
    </div>
</nav>

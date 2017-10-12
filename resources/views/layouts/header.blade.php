<?php
$appEnvironment = App::environment();
$user           = Auth::user();
?>

<div class="row border-bottom">
    <div class="col-xs-12">

        <ul class="nav navbar-top-links navbar-right">
            <li>
                <span class="m-r-sm text-muted welcome-message">
                    Welcome to Platform Science, {{ Auth::user()->first_name }}
                </span>
            </li>
            <li>
                <a href="">
                    <i class="fa fa-sign-out"></i>
                    Log out
                </a>
            </li>
        </ul>

        <div class="navbar-header">

            @if (!empty($topBarLogoURL))
                <div id="top-bar-logo-section" class="inline-section">
                    {!!
                        HTML::image(
                            StorageUrl::publicUrl('permanent', $topBarLogoURL),
                            null,
                            [
                                'class' => 'top-bar-logo',
                            ]
                        )
                    !!}
                </div>
            @endif

            @if ($appEnvironment != 'production')
                <div class="inline-section">
                    <div class="version-env">
                        <span class="badge badge-warning {{ $appEnvironment }}">
                            Environment: {{ strtoupper(trans($appEnvironment)) }}
                        </span>
                    </div>
                </div>
            @endif

        </div>

        @if ($user->can(['web.full_access', 'web.drivers.view']))
            <form class="navbar-form-custom navbar-search top-search">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-search"></i>
                    </span>
                    {!! Form::select('top_search', [], null, array(
                        'class' => 'form-control', 'multiple' => true, 'id' => 'top_search', 'size' => 1
                     )) !!}
                </div>
            </form>
        @endif

    </div>
</div>

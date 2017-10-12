<div class="app-footer">
    @include('layouts.copyright-notice')
    @if (!empty($_ENV['APP_REVISION']))
        <div>rev {{ $_ENV['APP_REVISION'] or '' }}</div>
    @endif
</div>

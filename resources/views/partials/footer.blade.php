<!-- Main Footer -->
<footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
        @if(config('admin.show_environment'))
            <strong>Env</strong>&nbsp;&nbsp; {!! config('app.env') !!}
        @endif

        &nbsp;&nbsp;&nbsp;&nbsp;

        @if(config('admin.show_version'))
        <strong>Version</strong>&nbsp;&nbsp; {!! \Nicelizhi\Admin\Admin::VERSION !!}
        @endif

    </div>
    <!-- Default to the left -->
    @if(config('admin.show_version'))
    <strong>Powered by <a href="https://github.com/nicelizhi/laravel-admin" target="_blank">laravel-admin</a></strong>
    @endif
</footer>
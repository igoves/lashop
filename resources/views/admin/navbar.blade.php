@if ($user)
    <li>
        <a href="/" target="_blank">
            <i class="fa fa-btn fa-globe"></i> @lang('sleeping_owl::lang.links.index_page')
        </a>
    </li>
    <li class="dropdown user user-menu" style="margin-right: 20px;">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
            <span class="hidden-xs"><i class="fa fa-btn fa-user"></i> {{ $user->name }}</span>
        </a>
        <ul class="dropdown-menu">
            <li class="user-footer">
                <a href="{{ url('/logout') }}">
                    <i class="fa fa-btn fa-sign-out"></i> @lang('sleeping_owl::lang.auth.logout')
                </a>
            </li>
        </ul>
    </li>
@endif

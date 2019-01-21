<div class="header">
    <div class="userinfo_block">Hi {{ Auth::user()->name }}</div>
    <div class="logout_block"><a href="{{ url('logout') }}">Logout</a></div>
</div>

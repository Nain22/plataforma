<li class="nav-item dropdown">
    <a
        class="nav-link dropdown-toggle"
        href="#"
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false"
    >
        {{ auth::user()->name }} <span class="caret"></span>
    </a>

    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a
            class="dropdown-item"
            href="{{ route('logout') }}"
            onclick="event.preventDefault();
            document.getElementById('logout-form').submit();"
        >
            {{ __("Cerrar sessión") }}
        </a>

        <form action="{{ route('logout') }}" id="logout-form" method="POST" style="display:none">
            @csrf
        </form>
    </div>
</li>

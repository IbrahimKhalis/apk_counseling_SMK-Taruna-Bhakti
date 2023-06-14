<nav class="navbar" id="nv">
    <div class="logo-nav">
        <h1>C<span>O</span>UNSELING</h1>
    </div>
    <div class="menu-nav">
        <ul>
            <li><a href="{{ route('index') }}">Beranda</a></li>
            <li><a href="#">Tentang</a></li>
            @auth
            @hasrole('user')
            <li><a href="{{ route('layanan') }}">Konseling</a></li>
            <li>
                <div class="profile-hoverable">
                    <a href="">Profile</a>
                    <div>
                        <a href="{{ route('profile') }}">Add Email</a>
                        <form action="/logout" method="post" id="logoutform">
                            @csrf
                            <a onclick="document.getElementById('logoutform').submit(); return false;" >Logout</a>
                        </form>
                    </div>
                </div>
            </li>
            @endhasrole
            @endif
            @guest
            <li><a href="/login">Login</a></li>
            @endguest

            @auth
            @hasrole('admin')
            <li><a href="/login">Dashboard</a></li>
            @endhasrole
            @hasrole('guru bk')
            <li><a href="/login">Dashboard</a></li>
            @endhasrole
            @hasrole('wali kelas')
            <li><a href="/login">Dashboard</a></li>
            @endhasrole
            @endif

            @hasrole('user')
            <li>
                <button class="notif-button" id="notif-button" onclick="notifOpen()">
                    <i class="fa fa-bell"></i>
                </button>
                <div class="notif-popup" id="notif">
                    <div>
                        <h3>Pesan Baru</h3>
                        <p class="message">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quo minima dicta
                            aspernatur, repellat minus ipsum, molestias, deleniti ratione ipsam non expedita. Autem
                            labore asperiores expedita sequi, cupiditate omnis ipsam nam!</p>
                    </div>

                    <button onclick="notifClose()">
                        TUTUP
                    </button>
                </div>
            </li>
            @endhasrole
        </ul>
    </div>
</nav>
<div class="ui stackable secondary pointing menu">
    <a class="item active">
        Login
    </a>
    <a class="item" href="register">
        Register
    </a>
    <a class="item" href="bantuan">
        Bantuan
    </a>
    <div class="right menu">
        <a class="ui item" href="about">
            <i class="food icon"></i>
        </a>
    </div>
</div>
<!-- <div class="ui segment" style="background-image: url(images/bg.jpeg);">
        </div> -->
<div class="ui middle aligned center aligned grid">
    <div class="column">
        <form class="ui large form">
            <div class="ui stackable segment">
                <h2 class="ui teal image header">
                    <i class="users icon"></i>
                    <div class="content">
                        Login seSendok
                        <div class="sub header">
                            Manage government
                        </div>
                    </div>
                </h2>
                <div class="field">
                    <div class="ui left icon input">
                        <i class="user icon"></i>
                        <input type="text" name="username" placeholder="username or E-mail address">
                    </div>
                </div>
                <div class="field">
                    <div class="ui right labeled left icon input">
                        <i class="lock icon"></i>
                        <input type="password" name="password" placeholder="Password">
                        <a onclick="changePassView()" class="ui tag label">
                            <i class="eye icon"></i>
                        </a>
                    </div>
                </div>
                <div class="ui primary labeled icon button" name="login" value="anggaran">
                    <i class="user icon"></i>
                    Login
                </div>
            </div>
            <div class="ui error message"></div>
        </form>
        <div class="ui message">
            Buat akun baru ?
            <a href="register">Buat akun</a>
        </div>
    </div>
</div>
<!-- modal pemberitahuan notif-->
<div class="ui basic modal info">
    <div class="ui icon header" id="kop_notifikasi">
        <i class="archive icon"></i>
        Archive Old Messages
    </div>
    <div class="content">
        <div class="ui center aligned stackable container grid" id="conten_notifikasi">
            <p>ini di isi oleh ajax</p>
        </div>
    </div>
    <div class="actions">
        <div class="ui green ok inverted button center aligned">
            <i class="checkmark icon"></i>
            OK
        </div>
    </div>
</div>
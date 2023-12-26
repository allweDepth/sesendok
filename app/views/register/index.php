<div class="ui teal stackable secondary pointing menu"> <a class="item" href="login"> Login </a> <a class="item active" href="register"> Register </a> <a class="item" href="bantuan"> Bantuan </a>
    <div class="right menu"> <a class="ui item" href="about"> <i class="food icon"></i> </a> </div>
</div>
<div class="ui middle aligned center aligned grid">
    <div class="column">
        <form class="ui form" method="POST">
            <div class="ui stacked segment">
                <h2 class="ui teal image header"><i class="home icon"></i>
                    <div class="content"> Register seSendok
                        <div class="sub header"> Manage project </div>
                    </div>
                </h2>
                <div class="field">
                    <div class="ui left icon input"> <i class="user icon"></i>
                        <input type="text" name="username" placeholder="username">
                    </div>
                </div>
                <div class="field">
                    <div class="ui left icon input"> <i class="envelope open icon"></i>
                        <input type="text" name="email" placeholder="E-mail address">
                    </div>
                </div>
                <div class="field">
                    <div class="ui left icon input"> <i class="user icon"></i>
                        <input type="text" name="nama" placeholder="Nama Lengkap">
                    </div>
                </div>

                <div class="field">
                    <div class="ui left icon input"> <i class="fax icon"></i>
                        <input type="text" name="kontak_person" placeholder="kontak person">
                    </div>
                </div>
                <div class="field">
                    <div class="ui left icon input"> <i class="lock icon"></i>
                        <input type="password" name="password" placeholder="Password">
                    </div>
                </div>
                <div class="field">
                    <div class="ui left icon input"> <i class="home icon"></i>
                        <input type="text" name="organisasi" placeholder="Organisasi">
                    </div>
                </div>

                <div class="inline field">
                    <div class="ui checkbox">
                        <input type="checkbox" name="setuju" checked>
                        <label href="dok/Basic Price 1 Header.xlsx" target="_blank">Saya setuju dengan <a href="dok/syarat_dan_ketentuan_AHSP.pdf" target="_blank">ketentuan</a> penggunaan aplikasi ini</label>
                    </div>
                </div>
                <div class="ui fluid large primary submit button" name="register" value="Daftar"> Register </div>
            </div>
            <div class="ui error message"></div>
        </form>
        <div class="ui message"> Already have an account ? <a href="login"> Login </a> </div>
    </div>
</div>
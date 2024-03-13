
<!-- Following Menu -->
<div class="ui large top fixed menu">
    <div class="ui container">
        <a class="item inayah" href="login">Home</a>
        <a class="active item inayah" href="data_teknis">Data Teknis</a>
        <a class="item inayah" href="organisasi">Organisasi</a>
        <a class="item inayah" href="anggaran">Anggaran</a>
        <div class="right menu">
            <div class="item">
                <a class="ui button" name="modal">Log in</a>
            </div>
            <div class="item">
                <a class="ui primary button" name="modal-register">Sign Up</a>
            </div>
        </div>
    </div>
</div>
<!-- Sidebar Menu -->
<div class="ui vertical inverted sidebar menu">
<a class="item inayah" href="login">Home</a>
    <a class="active item inayah">Data Teknis</a>
    <a class="item inayah" href="organisasi">Organisasi</a>
    <a class="item inayah" href="anggaran">Anggaran</a>
    <a class="item inayah" name="modal">Login</a>
    <a class="item inayah" name="modal-register">Signup</a>
</div>

<!-- Page Contents -->
<div class="pusher">
    <div class="ui vertical stripe segment">
    <div class="ui container">
        <table class="ui celled padded table">
            <thead>
                <tr>
                    <th class="single line">Evidence Rating</th>
                    <th>Effect</th>
                    <th>Efficacy</th>
                    <th>Consensus</th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <h2 class="ui center aligned header">A</h2>
                    </td>
                    <td class="single line">
                        Power Output
                    </td>
                    <td>
                        <div class="ui yellow rating" data-rating="3" data-max-rating="3"></div>
                    </td>
                    <td class="right aligned">
                        80% <br>
                        <a href="#">18 studies</a>
                    </td>
                    <td>Creatine supplementation is the reference compound for increasing muscular creatine levels; there is variability in this increase, however, with some nonresponders.</td>
                </tr>
                <tr>
                    <td>
                        <h2 class="ui center aligned header">A</h2>
                    </td>
                    <td class="single line">
                        Weight
                    </td>
                    <td>
                        <div class="ui yellow rating" data-rating="3" data-max-rating="3"></div>
                    </td>
                    <td class="right aligned">
                        100% <br>
                        <a href="#">65 studies</a>
                    </td>
                    <td>Creatine is the reference compound for power improvement, with numbers from one meta-analysis to assess potency</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5">
                        <div class="ui right floated pagination menu">
                            <a class="icon item">
                                <i class="left chevron icon"></i>
                            </a>
                            <a class="item">1</a>
                            <a class="item">2</a>
                            <a class="item">3</a>
                            <a class="item">4</a>
                            <a class="icon item">
                                <i class="right chevron icon"></i>
                            </a>
                        </div>
                    </th>
                </tr>
            </tfoot>
        </table>
        </div>
    </div>
    <div class="ui inverted vertical footer segment">
        <div class="ui container">
            <div class="ui stackable inverted divided equal height stackable grid">
                <div class="three wide column">
                    <h4 class="ui inverted header">About</h4>
                    <div class="ui inverted link list">
                        <a href="#" class="item">Sitemap</a>
                        <a href="#" class="item">Contact Us</a>
                        <a href="#" class="item">Religious Ceremonies</a>
                        <a href="#" class="item">Gazebo Plans</a>
                    </div>
                </div>
                <div class="three wide column">
                    <h4 class="ui inverted header">Services</h4>
                    <div class="ui inverted link list">
                        <a href="#" class="item">Banana Pre-Order</a>
                        <a href="#" class="item">DNA FAQ</a>
                        <a href="#" class="item">How To Access</a>
                        <a href="#" class="item">Favorite X-Men</a>
                    </div>
                </div>
                <div class="seven wide column">
                    <h4 class="ui inverted header">Footer Header</h4>
                    <p>Extra space for a call to action inside the footer that could help re-engage users.</p>
                </div>
            </div>
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

<!-- modal login-->
<div class="ui basic tiny modal login">
    <div class="ui middle aligned center aligned grid">
        <div class="column">
            <form class="ui large form login content">
                <div class="ui stacked segment">
                    <h2 class="ui teal image header"><i class="home icon"></i>
                        <div class="content"> Login seSendok
                            <div class="sub header"> Manajemen Anggaran</div>
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
                    <div class="ui error message"></div>
                </div>
            </form>
            <div class="ui message"> Buat akun baru ? <a href="register" target=”_blank”>Buat akun</a></div>
            <div class="actions">
                <button class="ui black deny button">
                    Batal
                </button>
                <button class="ui positive right labeled icon button" type="submit" name="login" value="anggaran">
                    Login
                    <i class="checkmark icon"></i>
                </button>
            </div>

        </div>
    </div>
</div>
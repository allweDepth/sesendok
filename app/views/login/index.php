<!-- Following Menu -->
<div class="ui large top fixed hidden menu">
    <div class="ui container">
        <a class="active item">Home</a>
        <a class="item">Work</a>
        <a class="item">Company</a>
        <a class="item">Careers</a>
        <div class="right menu">
            <div class="item">
                <a class="ui button" name="modal">Log in</a>
            </div>
            <div class="item">
                <a class="ui primary button" href="register" target=”_blank”>Sign Up</a>
            </div>
        </div>
    </div>
</div>

<!-- Sidebar Menu -->
<div class="ui vertical inverted sidebar menu">
    <a class="active item">Home</a>
    <a class="item">Work</a>
    <a class="item">Company</a>
    <a class="item">Careers</a>
    <a class="item" name="modal">Login</a>
    <a class="item" href="register" target=”_blank”>Signup</a>
</div>


<!-- Page Contents -->
<div class="pusher">
    <div class="ui inverted vertical masthead center aligned segment">

        <div class="ui container">
            <div class="ui large secondary inverted pointing menu">
                <a class="toc item">
                    <i class="sidebar icon"></i>
                </a>
                <a class="active item">Home</a>
                <a class="item">Work</a>
                <a class="item">Company</a>
                <a class="item">Careers</a>
                <div class="right item">
                    <a class="ui inverted button" name="modal">Log in</a>
                    <a class="ui inverted button" href="register" target=”_blank”>Sign Up</a>
                </div>
            </div>
        </div>

        <div class="ui text container">
            <h1 class="ui inverted header">
                D P U P R
            </h1>
            <h2>kabupaten pasangkayu</h2>
            <div class="ui huge primary button">Data Teknis <i class="right arrow icon"></i></div>
        </div>

    </div>

    <div class="ui vertical stripe segment">
        <div class="ui middle aligned stackable grid container">
            <div class="row">
                <div class="eight wide column">
                    <h3 class="ui header">Kami Membantu Membangun Infrastruktur</h3>
                    <p>dengan berkomiten membantu kepala daerah mencapai visi misi pembangunan.</p>
                    <h3 class="ui header">Dengan Motto</h3>
                    <p>Bekerja Keras, Bergerak Cepat, Bertindak Tepat.</p>
                </div>
                <div class="six wide right floated column">
                    <img src="img/logo.png" class="ui large image">
                </div>
            </div>
            <div class="row">
                <div class="center aligned column">
                    <a class="ui huge button">Check Them Out</a>
                </div>
            </div>
        </div>
    </div>


    <div class="ui vertical stripe quote segment">
        <div class="ui equal width stackable internally celled grid">
            <div class="center aligned row">
                <div class="column">
                    <h3>"What a Company"</h3>
                    <p>That is what they all say about us</p>
                </div>
                <div class="column">
                    <h3>"I shouldn't have gone with their competitor."</h3>
                    <p>
                        <img src="img/avatar/default.jpeg" class="ui avatar image"> <b>Nan</b> Chief Fun Officer Acme Toys
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="ui vertical stripe segment">
        <div class="ui text container">
            <h3 class="ui header">Breaking The Grid, Grabs Your Attention</h3>
            <p>Instead of focusing on content creation and hard work, we have learned how to master the art of doing nothing by providing massive amounts of whitespace and generic content that can seem massive, monolithic and worth your attention.</p>
            <a class="ui large button">Read More</a>
            <h4 class="ui horizontal header divider">
                <a href="#">Case Studies</a>
            </h4>
            <h3 class="ui header">Did We Tell You About Our Bananas?</h3>
            <p>Yes I know you probably disregarded the earlier boasts as non-sequitur filler content, but its really true. It took years of gene splicing and combinatory DNA research, but our bananas can really dance.</p>
            <a class="ui large button">I'm Still Quite Interested</a>
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

<!-- modal pemberitahuan notif-->
<div class="ui modal login">
    <i class="close icon"></i>
    <div class="header">
        Login
    </div>
    <form class="ui large form content">
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
    </form>
    <div class="ui message">
        Buat akun baru ?
        <a href="register" target=”_blank”>Buat akun</a>
    </div>
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
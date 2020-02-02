<div>
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>

    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                <form name="login_form" action="<?php echo base_url() . _login_uri; ?>/verify" method="post">
                    <?php
                        if (isset($redirect_url)) {
                            if (!empty($redirect_url)) {
                                echo '<input type="hidden" name="redirect_url" value="'.$_SESSION['redirect_url'].'">';
                            }
                        }
                    ?>
                    <h1>Login Administrator<br></h1>
                    <div>
                        <?php
                        if (isset($_SESSION['confirmation'])) {
                            echo '<div class="alert alert-danger">' . $_SESSION['confirmation'] . '</div>';
                        }
                        ?>
                    </div>
                    <div>
                        <input type="text" class="form-control" placeholder="Username" required="" name="username" value="<?php echo $this->session->flashdata('username') != NULL ? $this->session->flashdata('username') : ''; ?>" id="loginUsername" autocomplete="off" aria-haspopup="true" role="textbox"/>
                    </div>
                    <div>
                        <input type="password" class="form-control" placeholder="Password" required="" name="password" id="loginPassword" autocomplete="off" aria-haspopup="true" role="textbox"/>
                    </div>

                    <div>
                        <div>
                            <img id="captcha_image" src="<?php echo site_url(_login_uri . '/captcha'); ?>" style="width:100%" />
                        </div>
                        <a id="captcha_reload" title="Change Unique Code">
                            <i class="fa fa-refresh" style="position: absolute; bottom: 120px; right:7px; cursor:pointer;"></i>
                        </a>
                        <br>
                        <input type="text" class="form-control" placeholder="Unique Code" required="" name="kode_unik" id="kodeunik" autocomplete="off" aria-haspopup="true" role="textbox"/>
                    </div>

                    <div>
                        <button class="btn btn-default submit" name="submit" id="submit">Login</button>
                    </div>

                    <div class="clearfix"></div>

                    <div class="separator">
                        <div class="clearfix"></div>
                        <div>
                            <p><?php echo $this->site_configuration['footer']; ?></p>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
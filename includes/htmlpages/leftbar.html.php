
<style>
    .headiconsdiv{
        padding: 20px;
        text-align:center;
        transition: font-size 4s;
    }
    .headiconsdiv:hover{
        font-size: 1.9em;
    }
    .headicons{
        color: #00ffff;
        font-size : 1.6em;
    }
</style>
<div>
    <div>
        <h4 style="padding: 10px; color: yellow;">
            <button class="nav navbar-toggle btn-block" data-toggle="collapse" data-target="#servicesdiv"
                style="border: 4px solid green; border-radius: 10px; ">
                <b>Enjoy These Services<span class="caret"></span> </b>
            </button>
        </h4>
    </div>
    <div class="nav navbar-collapse collapse" id="servicesdiv">

        <div class='row'>
            <div class='col-xs-12 headiconsdiv'>
                <a href="/bona/product/productform.html.php">
                    <span class='glyphicon glyphicon-share headicons'></span>
                    <br>
                    <b>Sell Product <i>Free!</i></b>
                </a>
            </div>
        </div>

        <div class='row'>
            <div class='col-xs-12 headiconsdiv'>
                <a href="/bona/product/?getproducts">
                    <span class='glyphicon glyphicon-download headicons'></span>
                    <br>
                    <b>Buy Products / Shop </b>
                </a>
            </div>
        </div>

        <div class='row'>
            <div class='col-xs-12 headiconsdiv'>
                <a href="/bona/user">
                    <span class='glyphicon glyphicon-briefcase headicons'></span>
                    <br>
                    <b></span> Your Business Profile</b>
                </a>
            </div>
        </div>

        <div class='row'>
            <div class='col-xs-12 headiconsdiv'>
                <a href="/bona/hub/hub.html.php">
                    <span class='glyphicon glyphicon-calendar headicons'></span>
                    <br>
                    <b>Market Hub</b><br>
                    <small>
                        (Meet Product Buyers And Sellers In Your Area)
                    </small>
                </a>
            </div>
        </div>

        <div class='row'>
            <div class='col-xs-4 headiconsdiv'>
            </div>
        </div>

        <div class='row'>
            <div class='col-xs-12 headiconsdiv'>
                <a href="/bona/hub/hub.html.php">
                    <span class='glyphicon glyphicon-calendar headicons'></span>
                    <br>
                    <b></span> Find Agro-Facilities In Your Area</b>
                </a>
            </div>
        </div>

        <?php if(!isset($_SESSION['userid'])):?>
            <div class='row'>
                <div class='col-xs-12 headiconsdiv'>
                    <a href='/bona/user/'>
                        <span class='glyphicon glyphicon-log-in headicons'></span>
                        <br>Log In
                    </a>
                </div>
            </div>

            <div class='row'>
                <div class='col-xs-12 headiconsdiv'>

                    <?php

                    $app_id = '1886093921631150';
                    $redirect_url = 'https://www.agmall.com.ng/bona/user/facebooklogin.php';

                    $reqt = '';

                    ?>
                    <a href="https://www.facebook.com/dialog/oauth?client_id=<?php echo $app_id; ?>&redirect_uri=<?php echo $redirect_url ; ?>&scope=email">
                        <span class='headicons' style='padding:5px 15px 5px 15px; background: blue;'>f</span><br>
                        <b>Login From <span style='color: blue;'>Facebook</span>
                        </b>

                    </a>
                </div>
            </div>

            <div class='row'>
                <div class='col-xs-12 headiconsdiv'>
                    <a href='/bona/user/registration.html.php'>
                        <span class='glyphicon glyphicon-off headicons'></span>
                        <br>
                        Register, Easy
                    </a>
                </div>
            </div>

        <?php else: ?>
            <div class='row'>
                <div class='col-xs-12 headiconsdiv'>
                </div>
            </div>

            <div class='row'>
                <div class='col-sm-12 headiconsdiv'>
                    <a href="/bona/user/?logout" style="color: #ffffff;">
                        <button type="button" style="border: 0; background-color: transparent;">
                            <b><span class="glyphicon glyphicon-off headicons" style='color: darkred;'></span>
                                <br>Log Out
                            </b>
                        </button>
                    </a>
                </div>
            </div>
            <div class='row'>
                <div class='col-xs-12 headiconsdiv'>
                </div>
            </div>

        <?php endif; ?>

        <?php if(!empty($_SESSION["mycart"])):?>
            <?php $mycartitems = count($_SESSION["mycart"]);?>
            <div class='row'>
                <div class='col-xs-12 headiconsdiv'>
                    <a href="/bona/transaction/?emptycart" style="color: #ffffff;">
                        <button type="button" style="border: 0; background-color: transparent;">
                            <b><span class="glyphicon glyphicon-shopping-cart headicons" style="color: red;"></span>
                                <br>Empty Cart</b>
                        </button>
                    </a>
                </div>
            </div>

            <div class='row'>
                <div class='col-xs-12 headiconsdiv'>
                </div>
            </div>
            <div class='row'>
                <div class='col-xs-12 headiconsdiv'>
                    <a href="/bona/transaction/?getcart" style="color: #ffffff;">
                        <button type="button" style="border: 0; background-color: transparent;">
                            <b><span class="glyphicon glyphicon-shopping-cart headicons"></span>
                                <br>Cart
                                <span class="badge text-superscript"><?php echo $mycartitems; ?></span>
                            </b>
                        </button>
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class='row'>
                <div class='col-xs-12 headiconsdiv'>
                </div>
            </div>
            <div class='row'>
                <div class='col-sm-12 headiconsdiv'>
                    <a href="/bona/transaction/?getcart" style="color: #ffffff;">
                        <button type="button" style="border: 0; background-color: transparent;">
                            <b><span class="glyphicon glyphicon-shopping-cart headicons"></span>
                                <br>Cart
                            </b>
                        </button>
                    </a>
                </div>
            </div>
            <div class='row'>
                <div class='col-xs-12 headiconsdiv'>
                </div>
            </div>

        <?php endif; ?>
    </div>
</div>
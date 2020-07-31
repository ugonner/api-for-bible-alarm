<!DOCTYPE html 5.0>
<html>

<?php include_once $_SERVER['DOCUMENT_ROOT'].'/bona/inc/htmls/header.html.php'?>

<div class="container-fluid">
    <div class="row">
        
    </div>

    <div class="row">
    <div class="col-sm-3">
        <div class="row">
            <div class="col-sm-12">
                <?php include_once $_SERVER['DOCUMENT_ROOT'].'/bona/includes/htmlpages/leftbar.html.php';?>
            </div>
        </div>
    </div>

    <div class='col-sm-6'>
        <div class="row" style="border: 30px solid #224422;">

            <div class="text-center">
                <img src="/bona/img/iconic/handwaver.gif" class="img-responsive" />
                    <h6><b>EEIYAA...!</b>, This Little Notice For You, But Not To Worry, <br>
                    You Can Use The Button Below And Head Back To What Was Up Before This, <br></h6>
                <h5><i>OKAY?</i></h5>
            </div>
        </div>
        <div class="row" style="border: 15px solid #224422;">
         <h5 class="text-center text-capitalize">
            <?php if(isset($error)){
               echo $error;
            }?>
         </h5>
         </div>
        <div>
            <div style="margin: 10px;">
                <button id="backbtn" type="button" class="btn" style="border: 10px solid #224422; border-radius: 50%; background: transparent;">
                    <b> Hit <br>Back!</b>
                </button>
            </div>
        </div>
 </div>
    <div class="col-sm-3">
        <div class="row">
            <div class="col-sm-12">
                <?php include_once $_SERVER['DOCUMENT_ROOT'].'/bona/includes/htmlpages/rightbar.html.php';?>
            </div>

        </div>
    </div>
</div>
</div>
<script type="text/javascript">
    $("#backbtn").click(function(){
        window.history.go(-1);
    })
</script>

<?php include $_SERVER['DOCUMENT_ROOT'].'/bona/includes/htmlpages/footer.html.php'?>
</body>
</html>
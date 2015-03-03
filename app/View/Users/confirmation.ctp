<div class="user-confirmation page-content small-centered">
    <div class="page-header">
        <h1>Hey, is this you?</h1>
    </div>
    <p>Please confirm the details below. If the details below aren't correct, please update them in HRMS.</p>
        <div class="row">
            <div class="col-xs-4">
                <span class="glyphicon glyphicon-flash heading"></span>
                <span class="heading">HRMS</span>:
            </div>
            <div class="col-xs-8">
                <span class="info"><?php echo $userprofile['User']['username']; ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <span class="glyphicon glyphicon-user heading"></span>
                <span class="heading">Name</span>:
            </div>
            <div class="col-xs-8">
                <span class="info"><?php echo $userprofile['Profile']['first_name'] .' '. $userprofile['Profile']['surname']; ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4"> 
                <span class="glyphicon glyphicon-home heading"></span>
                <span class="heading">Region/Store</span>:
            </div>
            <div class="col-xs-8">
                <span class="info"><?php echo $userprofile['Profile']['Store']['Region']['name']; ?> /
                <?php echo $userprofile['Profile']['Store']['name']; ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <span class="glyphicon glyphicon-phone-alt heading"></span>
                <span class="heading">Phone</span>:
            </div>
            <div class="col-xs-8">
                <?php 
                    if(!empty($userprofile['Profile']['phonenumber'])){
                        echo '<span class="info">'.$userprofile['Profile']['phonenumber'].'</span>';
                    }else{
                        echo '<span class="info alert-warning-text">Please add your phone number</span>';
                    }
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <span class="glyphicon glyphicon-envelope heading"></span>
                <span class="heading">Email</span>:
            </div>
            <div class="col-xs-8">
                <?php 
                    if(!empty($userprofile['Profile']['email'])){
                        echo '<span class="info">'.$userprofile['Profile']['email'].'</span>';
                    }else{
                        echo '<span class="info alert-warning-text">Please add your email</span>';
                    }
                ?>            </div>
        </div>
        <div class="row">
            <?php echo $this->Form->create('User');?>
                <div class="col-xs-12 button-group">
                    <button class="btn btn-danger col-xs-5" type="submit" name="route" value="logout">
                        <span class="glyphicon glyphicon-log-out"></span> No, log me out
                    </button>
                    <button class="btn btn-success col-xs-5 pull-right" type="submit" name="route" value="confirm">
                        <span class="glyphicon glyphicon-thumbs-up"></span> Yes, That's me
                    </button>
                </div>
            <?php echo $this->Form->end();?>
        </div>
    </div>
</div>
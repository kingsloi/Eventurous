<section class="main-login page-content small-centered">
    <?php echo $this->Session->flash(); ?>
    <div class="page-header">
        <h1>Course Booking</h1>
    </div>
    <?php echo $this->Form->create('User', array('url' => '/login','class' => 'main-login-form form-horizontal', 'role'=>'form'));?>
        <h2 class="h4">Login</h2>
        <div class="form-group">
            <?php echo $this->Form->input('username', array('div'=>'col-lg-12', 'label' => false,'class'=>'form-control input-lg','placeholder'=>'HRMS#', 'autofocus'=>'autofocus'));?> 
        </div>
        <div class="form-group">
            <?php echo $this->Form->input('password', array('div'=>'col-lg-12','placeholder'=>'firstname.surname', 'label' => false,'class'=>'form-control input-lg'));?>
        </div>
        <div class="form-group">
            <div class="col-lg-12">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" value="remember-me"> Remember me
                        <span class="pull-right"> <a href="/password-reset">Forgot Password?</a></span>
                    </label>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-lg btn-block">Sign in</button>
    <?php echo $this->Form->end(); ?>
</section>
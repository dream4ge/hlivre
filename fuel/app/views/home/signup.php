<h2>Sign Up</h2>
<p>To sign up for a new account, please fill the form below with your account information.</p>
<p> //todo modify this view to use a Filedset: app/views/home/signup.php</p>
<p> //todo add js validation</p>

<?php //echo isset($errors) ? $errors : false; ?>
<?php echo $val->show_errors(); ?>
<?php echo Form::open('home/signup'); ?>
<fieldset> 
<legend>Account details</legend> 
<div class="input text required">
    <?php echo Form::label('* Username <em class="validation-info">(3 to 20 caracters long)</em>', 'username_input'); ?>
    <?php echo Form::input('username_input', NULL, array('id' => 'username_input')); ?>
</div>

<div class="input password required">
    <?php echo Form::label('* Password <em class="validation-info">(3 to 20 caracters long)</em>', 'password_input'); ?>
    <?php echo Form::password('password_input', NULL, array('id' => 'password_input')); ?>
</div>

<div class="input text required">
    <?php echo Form::label('* Email Address <em class="validation-info">(valid)</em>', 'email_input'); ?>
    <?php echo Form::input('email_input', NULL, array('id' => 'email_input')); ?>
</div>
<div><em class="validation-note">* required fields</em></div>
<div class="input submit">
    <?php echo Form::submit('signup', 'Sign Up'); ?>
</div>
</fieldset>
<?php echo Form::close(); ?>
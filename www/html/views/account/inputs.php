<label for="inputEmail" class="sr-only">ユーザID</label>
<input type="email" id="inputEmail" class="form-control" value="<?php echo $this->escape($email); ?>"
       name="email" placeholder="Email address" required autofocus>

<label for="inputPassword" class="sr-only">Password</label>
<input type="password" id="inputPassword" class="form-control" value="<?php echo $this->escape($password); ?>"
       name="password" placeholder="Password" required>

<button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo $this->escape($submit); ?></button>

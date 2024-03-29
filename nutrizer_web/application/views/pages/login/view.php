<div class="section-body">
	<div class="container mt-5">
		<div class="row">
			<div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
				<div class="card card-primary">
					<div class="card-header">
						<h4>Nutrizer Admin</h4>
					</div>
					<div class="card-body">
						<form id="loginForm" method="POST">
						<!-- class="needs-validation"  -->
							<div class="form-group">
								<label for="username">Username</label>
								<input id="username" type="text" class="form-control" name="username" tabindex="1" required autofocus>
								<div class="invalid-feedback">
									Please fill in your Username
								</div>
							</div>
							<div class="form-group">
								<label for="password" class="control-label">Password</label>
								<input id="password" type="password" class="form-control" name="password" tabindex="2" required>
								<div class="invalid-feedback">
									please fill in your password
								</div>
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="3">
									Login
								</button>
							</div>
						</form>
					</div>
				</div>
				<!-- <div class="mt-5 text-muted text-center">
					Don't have an account? <a href="#">Please Ask Administrator</a>
				</div> -->
			</div>
		</div>
	</div>
</div>
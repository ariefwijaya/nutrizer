<div class="section-body">
	<div class="card">
		<form class="" id="formData" method="POST">
			<div class="card-header">
				<h4>Edit Profile</h4>
				<div class="card-header-action">
					<button id="apply-filter" type="button" class="btn btn-light">Refresh</button>
				</div>
			</div>
			<div class="card-body">
				<div class="form-group row">
					<label class="col-sm-3 col-form-label">Username</label>
					<div class="col-sm-9">
						<input type="text" name="username" class="form-control" placeholder="Must not contain spaces, special characters, or emoji." disabled>
						<div class="invalid-feedback"></div>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-3 col-form-label">Full Name</label>
					<div class="col-sm-9">
						<input type="text" name="nickname" class="form-control" placeholder="" required>
						<div class="invalid-feedback"></div>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-3 col-form-label">Email</label>
					<div class="col-sm-9">
						<input type="email" name="email" class="form-control" placeholder="Eg. ****@gmail.com" >
						<div class="invalid-feedback"></div>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-3 col-form-label">Password</label>
					<div class="col-sm-9">
						<input type="password" name="password" class="form-control pwstrength" placeholder="" data-indicator="pwindicator">
						<div class="invalid-feedback"></div>
						<div id="pwindicator" class="pwindicator">
                            <div class="bar"></div>
                            <div class="label"></div>
                        </div>
                        <small class="password_block-text block-text form-text text-muted">
                        </small>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-3 col-form-label">Password Confirmation</label>
					<div class="col-sm-9">
						<input type="password" name="password_confirm" class="form-control" placeholder="">
						<div class="invalid-feedback"></div>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-3 col-form-label">Privilege</label>
					<div class="col-sm-9">
						<input type="text" name="privilege_name" class="form-control" placeholder="" disabled>
						<div class="invalid-feedback"></div>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-3 col-form-label">Avatar</label>
					<div class="col-sm-9">
						<div id="avatar_img_image-preview" class="image-preview">
							<label for="image-upload" id="image-label">Choose Image</label>
							<input type="file" name="avatar_img" id="image-upload" accept=".jpg,.jpeg,.png" />
							<div class="invalid-feedback">

							</div>
						</div>
						<small class="avatar_image_block-text block-text form-text text-muted">
							<span></span>Width and Height must greater than 100px . And Recommended to upload Square Image Size. Maximum size is 2 Megabytes(MB).
						</small>
					</div>
				</div>
			</div>
			<div class="card-footer">
				<button type="submit" class="btn btn-primary">Save Changes</button>
			</div>
		</form>
	</div>
</div>
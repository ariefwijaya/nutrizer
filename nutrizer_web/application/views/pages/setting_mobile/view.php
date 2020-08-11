<div class="section-body">
	<div class="card">
		<form class="" id="formData" method="POST">
			<div class="card-header">
				<h4>Setting Mobile</h4>
				<div class="card-header-action">
					<button id="apply-filter" type="button" class="btn btn-light">Refresh</button>
				</div>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-2">
					</div>
					<div class="col-md-8">
						<figure class="figure-img"><img class="p-b-10 img-fluid"  src= "<?php echo base_url(); ?>loader/images/guide/guidebanner.png">
							<figcaption class="text-center">Advertisement Covid Nutrizer</figcaption>
						</figure>
					</div>
					<div class="col-md-2">
					</div>
				</div>

				<div class="form-group row">
					<label class="col-sm-3 col-form-label">Ads Title</label>
					<div class="col-sm-9">
						<input type="text" name="title"  maxlength="60" class="form-control" placeholder="Eg. Ayo Perangi Covid!">
						<div class="invalid-feedback"></div>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-3 col-form-label">Ads Subtitle</label>
					<div class="col-sm-9">
						<input type="text" name="subtitle"  maxlength="120" class="form-control" placeholder="Eg. Dengan menjaga kebersihan diri secara rutin">
						<div class="invalid-feedback"></div>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-3 col-form-label">Ads Hyperlink</label>
					<div class="col-sm-9">
						<div class="input-group">
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fas fa-globe"></i>
								</div>
							</div>
							<input type="text" name="linkUrl" class="form-control" placeholder="http://link_to_url">
							<div class="invalid-feedback"></div>
						</div>
						<small class="news_url_block-text block-text form-text text-muted">
							<span></span>Fill this if you want to add hyperlink or leave empty to disable it.
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
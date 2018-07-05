<?php
ob_start();
?>
<div id="tmm-form-wizard" class="container substrate">

					<div class="row">
						<div class="col-xs-12">
							<h2 class="form-login-heading">Install SotaCloud</h2>
						</div>

					</div><!--/ .row-->

					<div class="row stage-container">

						<div class="stage tmm-current col-md-3 col-sm-3">
							<div class="stage-header head-icon head-icon-lock"></div>
							<div class="stage-content">
								<h3 class="stage-title">Account Information</h3>
								<div class="stage-info">
									Enter your first time username
									passworddetails
								</div> 
							</div>
						</div><!--/ .stage-->

						<div class="stage col-md-3 col-sm-3">
							<div class="stage-header head-icon head-icon-user"></div>

							<div class="stage-content">
								<h3 class="stage-title">Personal Information</h3>
								<div class="stage-info">
									Enter your first time username
									passworddetails
								</div>
							</div>
						</div><!--/ .stage-->

						<div class="stage col-md-3 col-sm-3">
							<div class="stage-header head-icon head-icon-payment"></div>
							<div class="stage-content">
								<h3 class="stage-title">Payment Information</h3>
								<div class="stage-info">
									Enter your first time username
									passworddetails
								</div>
							</div>
						</div><!--/ .stage-->

						<div class="stage col-md-3 col-sm-3">
							<div class="stage-header head-icon head-icon-details"></div>
							<div class="stage-content">
								<h3 class="stage-title">Confirm Your Details</h3>
								<div class="stage-info">
									Enter your first time username
									passworddetails
								</div> 
							</div>
						</div><!--/ .stage-->

					</div><!--/ .row-->

					<div class="row">

						<div class="col-xs-12">

							<div class="form-header">
								<div class="form-title form-icon title-icon-lock">
									<b>Account</b> Information
								</div>
								<div class="steps">
									Steps 1 - 5
								</div>
							</div><!--/ .form-header-->

						</div>

					</div><!--/ .row-->

					<form action="/" role="form">

						<div class="form-wizard">

							<div class="row">
								
								<div class="col-md-8 col-sm-7">

									<fieldset class="input-block">
										<label for="username">Username</label>
										<input type="text" id="username" class="form-icon form-icon-user" placeholder="Login" required="" style="cursor: auto;">
										<div class="tooltip">
											<p>
												<b>Why do we need this info?</b>
												Lorem Ipsum is simply dummy text of the printing and
												typesetting industry. Lorem Ipsum has been the industry's
												standard dummy text ever since the 1500s
											</p>
											<span>Your information is Safe here &amp; never shared.</span> 
										</div><!--/ .tooltip-->	
									</fieldset><!--/ .input-login-->
									
									<fieldset class="input-block">
										<label for="password">Password</label>
										<input type="password" id="password" placeholder="Password" required="" style="cursor: auto; background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACIUlEQVQ4EX2TOYhTURSG87IMihDsjGghBhFBmHFDHLWwSqcikk4RRKJgk0KL7C8bMpWpZtIqNkEUl1ZCgs0wOo0SxiLMDApWlgOPrH7/5b2QkYwX7jvn/uc//zl3edZ4PPbNGvF4fC4ajR5VrNvt/mo0Gr1ZPOtfgWw2e9Lv9+chX7cs64CS4Oxg3o9GI7tUKv0Q5o1dAiTfCgQCLwnOkfQOu+oSLyJ2A783HA7vIPLGxX0TgVwud4HKn0nc7Pf7N6vV6oZHkkX8FPG3uMfgXC0Wi2vCg/poUKGGcagQI3k7k8mcp5slcGswGDwpl8tfwGJg3xB6Dvey8vz6oH4C3iXcFYjbwiDeo1KafafkC3NjK7iL5ESFGQEUF7Sg+ifZdDp9GnMF/KGmfBdT2HCwZ7TwtrBPC7rQaav6Iv48rqZwg+F+p8hOMBj0IbxfMdMBrW5pAVGV/ztINByENkU0t5BIJEKRSOQ3Aj+Z57iFs1R5NK3EQS6HQqF1zmQdzpFWq3W42WwOTAf1er1PF2USFlC+qxMvFAr3HcexWX+QX6lUvsKpkTyPSEXJkw6MQ4S38Ljdbi8rmM/nY+CvgNcQqdH6U/xrYK9t244jZv6ByUOSiDdIfgBZ12U6dHEHu9TpdIr8F0OP692CtzaW/a6y3y0Wx5kbFHvGuXzkgf0xhKnPzA4UTyaTB8Ph8AvcHi3fnsrZ7Wore02YViqVOrRXXPhfqP8j6MYlawoAAAAASUVORK5CYII=); background-attachment: scroll; background-position: 100% 50%; background-repeat: no-repeat;">
										<div class="tooltip">
											<p>
												<b>Why do we need this info?</b>
												Lorem Ipsum is simply dummy text of the printing and
												typesetting industry. Lorem Ipsum has been the industry's
												standard dummy text ever since the 1500s
											</p>
											<span>Your information is Safe here &amp; never shared.</span> 
										</div><!--/ .tooltip-->	
									</fieldset><!--/ .input-password-->
									
									<fieldset class="input-block">
										<label for="pass-confirm">Confirm password</label>
										<input type="password" id="pass-confirm" placeholder="Password" required="" style="cursor: auto; background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACIUlEQVQ4EX2TOYhTURSG87IMihDsjGghBhFBmHFDHLWwSqcikk4RRKJgk0KL7C8bMpWpZtIqNkEUl1ZCgs0wOo0SxiLMDApWlgOPrH7/5b2QkYwX7jvn/uc//zl3edZ4PPbNGvF4fC4ajR5VrNvt/mo0Gr1ZPOtfgWw2e9Lv9+chX7cs64CS4Oxg3o9GI7tUKv0Q5o1dAiTfCgQCLwnOkfQOu+oSLyJ2A783HA7vIPLGxX0TgVwud4HKn0nc7Pf7N6vV6oZHkkX8FPG3uMfgXC0Wi2vCg/poUKGGcagQI3k7k8mcp5slcGswGDwpl8tfwGJg3xB6Dvey8vz6oH4C3iXcFYjbwiDeo1KafafkC3NjK7iL5ESFGQEUF7Sg+ifZdDp9GnMF/KGmfBdT2HCwZ7TwtrBPC7rQaav6Iv48rqZwg+F+p8hOMBj0IbxfMdMBrW5pAVGV/ztINByENkU0t5BIJEKRSOQ3Aj+Z57iFs1R5NK3EQS6HQqF1zmQdzpFWq3W42WwOTAf1er1PF2USFlC+qxMvFAr3HcexWX+QX6lUvsKpkTyPSEXJkw6MQ4S38Ljdbi8rmM/nY+CvgNcQqdH6U/xrYK9t244jZv6ByUOSiDdIfgBZ12U6dHEHu9TpdIr8F0OP692CtzaW/a6y3y0Wx5kbFHvGuXzkgf0xhKnPzA4UTyaTB8Ph8AvcHi3fnsrZ7Wore02YViqVOrRXXPhfqP8j6MYlawoAAAAASUVORK5CYII=); background-attachment: scroll; background-position: 100% 50%; background-repeat: no-repeat;">
										<div class="tooltip">
											<p>
												<b>Why do we need this info?</b>
												Lorem Ipsum is simply dummy text of the printing and
												typesetting industry. Lorem Ipsum has been the industry's
												standard dummy text ever since the 1500s
											</p>
											<span>Your information is Safe here &amp; never shared.</span> 
										</div><!--/ .tooltip-->	
									</fieldset><!--/ .input-conf-password-->
									
									<fieldset class="input-block">
										<label for="email">Email</label>
										<input type="text" id="email" class="form-icon form-icon-mail" placeholder="Please enter your email ID" required="">
										<div class="tooltip">
											<p>
												<b>Why do we need this info?</b>
												Lorem Ipsum is simply dummy text of the printing and
												typesetting industry. Lorem Ipsum has been the industry's
												standard dummy text ever since the 1500s
											</p>
											<span>Your information is Safe here &amp; never shared.</span> 
										</div><!--/ .tooltip-->	
									</fieldset><!--/ .input-email-->

								</div>

							</div><!--/ .row-->
							
						</div><!--/ .form-wizard-->
						
							<div class="prev" style="display: none;">
								<button class="button button-control" type="button"><span>Prev Step <b>Account Information</b></span></button>
								<div class="button-divider"></div>
							</div>
						
							<div class="next">
								<button class="button button-control" type="button" onclick="window.location.href='form-wizard-with-icon1.html'"><span>Next Step <b>Personal Information</b></span></button>
								<div class="button-divider"></div>
							</div>
						
					</form><!--/ .form-wizard-->

				</div>
<?php
$c=ob_get_contents();
ob_end_clean();

@session_start();
return array(
  'stylesheets'=>[
    \Router::url('html/stylesheet/main/install.css'),
  ],
  'scripts'=>[
  ],
  [
    'class'=>'panel',
    'layout'=>'border',
    'items'=>array(
      [
        'class'=>'navbar',
        'region'=>'north',
        'cssClass'=>'mainnavbar',
        'items'=>[['class'=>'html','html'=>'Sotacloud'],],
        'menu'=>[
        ],
      ],
      [
        'class'=>'panel',
        'region'=>'center',
        'width'=>'9',
        'html'=>  $c
      ],
    ),
  ]
);
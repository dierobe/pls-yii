<div id="slides" class="swiper" aria-live="polite">
	<div class="swiper-wrapper">
		<div class="swiper-slide">
			<div class="overlay"></div>
			<div class="content">
				<i class="fal fa-question"></i>
				<h3>Need help?<br/>Our support desk is here for you.</h3>
				<div class="row">
					<div class="col-md-12 bubble">
						Email us at <a href="mailto:support@pls3rdlearning.com">support@pls3rdlearning.com</a> or
						<br/>
						call us toll-free at <a href="tel:1-888-201-EVAL">1-888-201-EVAL</a>.
					</div>
				</div>
			</div>
		</div>
		<div class="swiper-slide">
			<div class="overlay"></div>
			<div class="content">
				<i class="fal fa-user-friends"></i>
				<h3>Did you know that SuperEval offers multiple leadership team evaluations?</h3>
				<a href="https://supereval.com/our-evaluations/overview/" target="_blank" class="btn btn-primary">Learn
					More</a>
			</div>
		</div>
		<?php if (isset($slide_three)) { ?>
    		<div class="swiper-slide">
    			<div class="overlay"></div>
    			<div class="content">
    				<i class="fal fa-comment-alt-edit"></i>
    				<h3><?php echo $slide_three->title; ?></h3>
    				<div class="row is-flex">
    					<div class="col-md-12">
    						<div class="bubble"><?php echo $slide_three->description; ?></div>
    					</div>
    				</div>
    			</div>
    		</div>
		<?php } ?>
		<?php if (isset($slide_four)) { ?>
    		<div class="swiper-slide">
    			<div class="overlay"></div>
    			<div class="content">
    				<i class="fal fa-comment-alt-edit"></i>
    				<h3><?php echo $slide_four->title; ?></h3>
    				<div class="row is-flex">
    					<div class="col-md-12">
    						<div class="bubble"><?php echo $slide_four->description; ?></div>
    					</div>
    				</div>
    			</div>
    		</div>
		<?php } ?>
		
	</div>
	<div class="swiper-pagination"></div>
<div class="swiper-button-prev"></div>
<div class="swiper-button-next"></div>
</div>

<?php
Yii::app()->clientScript->registerScript('slides', /** @lang JavaScript */ "
	$(function () {
		new Swiper('.swiper', {
			speed: 400,
			autoplay: {
				delay: 8000,
			},
			loop: true,
			pagination: {
				el: '.swiper-pagination',
				clickable: true,
			},
			navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			},
		});
	});
	");
?>
<?php get_header(); ?>
<?php include_once('page_components/menu.php'); ?>


<?php 
    $page_posts = new Page("post");
		$articles = $page_posts->posts;

		// var_dump($articles[0]);
		// var_dump(get_the_post_thumbnail(13));
?>

<div class="page_index">
	<div class="container">
		<div class="row">
			<div class="header col-12">
				<div class="header_titre">
					<div class="titre">
						<h1 class="titre_1">4 mois au</h1>
						<h1 class="titre_2">Brésil</h1>
					</div>
					<div class="photo_fond_header">
						<img class="fond_header" src="https://www.riotgames.com/darkroom/1440/b8e42e6519e7a8ad386549c7dc1c6d12:04256fd0494bb434da141d700b4bd158/sp-city01.jpg" alt="">
						<div class="fond_image_header"></div>
					</div>
					<div class="logo-isen">
						<img class="fond_header" src='<?= get_uri(); ?>/logos/logo-isen.png' alt="">
					</div>
					<div class="logo-ai">
						<img class="fond_header" src='<?= get_uri(); ?>/logos/logo-ai.png' alt="">
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="content col-12">
				<div class="photo_grid">
						<div class="grid">
								<?php 
									foreach($articles as $index => $article){
										echo'
											<div class="grid-item">
												<a href="'.$article->getPermalink().'" class="link_single">
													<div class="englobe_img_grid">
														<div class="info_img_grid">
															<h3>'.$article->post_title.'</h3>
															<div class="underscore">
															</div>
															<h4 class="trois_petits_points">'.$article->post_content.'</h4>
														</div>
														<img class="image_de_la_grid" src="'.get_the_post_thumbnail_url($article->ID).'" style="width:100%">
														<div class="fond_img_grid_hover"></div>
													</div>
												</a>
											</div>';
									}
								?>
							
							<!-- <div class="grid-item grid-item--width2">
								<div class="englobe_img_grid">
									<div class="info_img_grid">column 2</div>
									<img class="image_de_la_grid" src="wp-content/themes/theme-stage-ingenieur-bresil/photos/3.jpg" style="width:100%">
									<div class="fond_img_grid_hover"></div>
								</div>
							</div>
							<div class="grid-item">
								<div class="englobe_img_grid">
									<div class="info_img_grid">column 2</div>
									<img class="image_de_la_grid" src="wp-content/themes/theme-stage-ingenieur-bresil/photos/5.jpg" style="width:100%">
									<div class="fond_img_grid_hover"></div>
								</div>
							</div>
						</div>
						<!-- ferme grid -->
						<!-- </div>

						<div class="column">
							<div class="englobe_img_grid">
								<div class="info_img_grid">column 2</div>
								<img class="image_de_la_grid" src="wp-content/themes/theme-stage-ingenieur-bresil/photos/7.jpg" style="width:100%">
								<div class="fond_img_grid_hover"></div>
							</div>
						</div>  
						<div class="column">
							<div class="englobe_img_grid">
								<div class="info_img_grid">column 3</div>
								<img class="image_de_la_grid" src="wp-content/themes/theme-stage-ingenieur-bresil/photos/1.jpg" style="width:100%">
								<div class="fond_img_grid_hover"></div>
							</div>
						</div> -->
				</div>
			</div>
		</div>
	</div>

</div>

<?php include_once('page_components/footer.php'); ?>
<?php get_footer(); ?>
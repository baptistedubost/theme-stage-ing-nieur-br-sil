<?php get_header(); ?>
<?php include_once('page_components/menu.php'); ?>

<?php 
    $article = new Post(get_post());
		$photo = $article->getMeta("photo1");
		$photo2 = $article->getMeta("photo2");
		$galerie = $article->getMeta("galerie");
?>

<div class="page_single">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<a href="javascript:history.back()">
					<div class="logo_retour">
						<img class="fond_header" src='<?= get_uri(); ?>/logos/icon_close_arrow.svg' alt="">
					</div>
				</a>
				<div class="header">
					<img class="img_article" src="<?= get_the_post_thumbnail_url($article->ID) ?>"/>
					<div class="content_global_single">
						<h2><?=$article->post_title?></h2>
						<h4><?=$article->getMeta("date")?></h4>
						<h3><?=$article->getMeta("sous_titre")?></h3>
						<div class="content_single">
							<?= $article->getMeta("contenu") ?>
						</div>
					</div>
				</div>
				<div class="suite">
					<div class="content_global_single">
						<h3><?=$article->getMeta("sous_titre_2")?></h3>
						<div class="content_single">
							<?= $article->getMeta("contenu_2") ?>
						</div>
					</div>
					<img class="img_article" src="<?=$photo2['url']?>"/>
				</div>
				<div class="galerie">
					<div class="grid">
						<?php 
							if($galerie !== false && $galerie !== null){
								foreach($galerie as $img_galerie){
									echo'
										<div class="grid-item">
												<div class="englobe_img_grid">
													<img class="image_de_la_grid_galerie" src="'.$img_galerie['url'].'" style="width:100%">
												</div>
										</div>';
								}
							}	
						?>
						}
					</div>
				</div>
			</div>
		</div>
	</div>

</div>

<?php include_once('page_components/footer.php'); ?>
<?php get_footer(); ?>
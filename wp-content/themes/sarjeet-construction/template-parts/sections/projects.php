<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$projects     = sarjeet_projects();
$total        = count( $projects );
$visible      = 3;
// Render one dot per slide; JS hides the trailing ones based on current visible count per breakpoint.
$dot_count    = $total;

$status_class = static function ( $year ) {
	$y = strtolower( trim( (string) $year ) );
	if ( $y === 'completed' ) return 'completed';
	if ( $y === 'ongoing' )   return 'ongoing';
	return '';
};
?>
<section id="projects" class="section projects">
	<div class="container">
		<div class="reveal section-head">
			<span class="eyebrow">03 — Selected Works · 2020 — 2026</span>
			<h2>The civic spine,<br>built one km at a time.</h2>
			<p class="lede">Eight headline projects across five states — currently on the books or in defect-liability. The carousel slides every few seconds; click any card for full specs.</p>
		</div>

		<div class="proj-carousel reveal" data-autoplay="4000" data-visible="<?php echo (int) $visible; ?>" aria-roledescription="carousel" aria-label="<?php esc_attr_e( 'Featured projects', 'sarjeet' ); ?>">
			<div class="proj-carousel__progress" aria-hidden="true"><span></span></div>
			<div class="proj-carousel__viewport">
				<div class="proj-carousel__track">
					<?php foreach ( $projects as $i => $p ) :
						$href   = ! empty( $p->permalink ) && $p->permalink !== '#'
							? $p->permalink
							: home_url( '/?project_view=' . rawurlencode( $p->slug ?? '' ) );
						$status = $status_class( $p->year );
					?>
						<article
							class="proj-slide"
							data-index="<?php echo (int) $i; ?>"
							aria-roledescription="slide"
							aria-label="<?php echo esc_attr( sprintf( __( '%1$d of %2$d', 'sarjeet' ), $i + 1, $total ) ); ?>">
							<div class="proj-slide__body">
								<div class="proj-slide__head">
									<span class="eyebrow proj-slide__eyebrow">
										<span><?php echo esc_html( $p->cat ); ?></span>
										<span aria-hidden="true">·</span>
										<span>P/<?php echo esc_html( $p->n ); ?></span>
									</span>
									<?php if ( $status ) : ?>
										<span class="proj-slide__status proj-slide__status--<?php echo esc_attr( $status ); ?>">
											<span class="proj-slide__status-dot" aria-hidden="true"></span>
											<?php echo esc_html( $p->year ); ?>
										</span>
									<?php endif; ?>
								</div>
								<h3 class="proj-slide__title"><?php echo esc_html( $p->title ); ?></h3>
								<p class="proj-slide__loc">
									<svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true"><path d="M6 11s4-3.7 4-6.5A4 4 0 0 0 2 4.5C2 7.3 6 11 6 11Z"/><circle cx="6" cy="4.5" r="1.4"/></svg>
									<?php echo esc_html( $p->loc ); ?>
								</p>
								<p class="proj-slide__desc"><?php echo esc_html( $p->desc ); ?></p>
								<div class="proj-slide__divider" aria-hidden="true"></div>
								<div class="proj-slide__meta">
									<div>
										<span class="eyebrow"><?php esc_html_e( 'Project Value', 'sarjeet' ); ?></span>
										<strong><?php echo esc_html( $p->value ?: '—' ); ?></strong>
									</div>
									<div>
										<span class="eyebrow"><?php esc_html_e( 'Client', 'sarjeet' ); ?></span>
										<strong><?php echo esc_html( $p->client ?: '—' ); ?></strong>
									</div>
								</div>
								<a class="btn proj-slide__cta" href="<?php echo esc_url( $href ); ?>">
									<?php esc_html_e( 'View project', 'sarjeet' ); ?> <span class="arrow">→</span>
								</a>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="proj-carousel__dots" role="tablist" aria-label="<?php esc_attr_e( 'Project slides', 'sarjeet' ); ?>">
				<?php for ( $i = 0; $i < $dot_count; $i++ ) : ?>
					<button
						type="button"
						class="proj-carousel__dot<?php echo $i === 0 ? ' is-active' : ''; ?>"
						role="tab"
						data-index="<?php echo (int) $i; ?>"
						aria-label="<?php echo esc_attr( sprintf( __( 'Go to slide %d', 'sarjeet' ), $i + 1 ) ); ?>"
						aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"
					></button>
				<?php endfor; ?>
			</div>
		</div>

		<div class="proj-view-all">
			<a class="btn proj-view-all__btn" href="<?php echo esc_url( home_url( '/?view=all-projects' ) ); ?>">
				<?php esc_html_e( 'View all projects', 'sarjeet' ); ?> <span class="arrow">→</span>
			</a>
		</div>
	</div>
</section>

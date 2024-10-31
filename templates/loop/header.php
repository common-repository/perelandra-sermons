<?php
$layout_class = '';
$layout = get_option( 'pl_sermons_archive_layout' );
if ( ! empty( $layout ) ) {
    $layout_class = 'grid-is-' . $layout;
}
?>
<div class="pl-sermons-grid <?php echo $layout_class ?>">
    <div class="pl-sermons-wrap">

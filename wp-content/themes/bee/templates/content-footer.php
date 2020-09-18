        </div> <!-- /site-content -->
        <footer>
            
            <?php

            $args = array(
                'menu' => 'secondary',
                'container' => ''
            );

            wp_nav_menu( $args );

            ?>

            <p>Copyright &copy; StudentBee <?php echo date( 'Y' ); ?>. All rights reserved.</p>
        </footer>
    </div> <!-- /site-wrapper -->

    <?php

    wp_footer();

    ?>

</body>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <?php

    wp_head();

    ?>

</head>

<body>
    <div class="site-wrapper">
        <header>
            <div class="container header__container">
                <div class="logo-container">
                    <span class="logo-container__logo-text"><a href="<?php echo get_bloginfo( 'url' ); ?>">StudentBee</a></span>
                </div>
                <div id="header__open-nav-button">
                    <i class="fas fa-bars"></i>
                </div>
                <div id="header__nav-container" class="is-not-visible">
                    <div class="container">
                        <nav>

                            <?php

                            $args = array(
                                'menu' => 'primary',
                                'container' => '',
                                'items_wrap' => '<ul><li id="header__close-nav-button"><i class="fas fa-times"></i></li>%3$s</ul>'
                            );

                            wp_nav_menu( $args );

                            ?>

                        </nav>
                    </div>
                </div>
            </div>
        </header>
        <div class="site-content">
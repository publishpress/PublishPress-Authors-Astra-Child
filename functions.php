<?php
/**
 * Astra Child theme
 */

define('PUBLISHPRESS_AUTHORS_ASTRA_CHILD_THEME_VERSION', '1.0.0');

/**
 * Enqueue styles
 */
function child_enqueue_styles()
{
    wp_enqueue_style(
        'brownstone-child-theme-theme-css',
        get_stylesheet_directory_uri() . '/style.css',
        array('astra-theme-css'),
        PUBLISHPRESS_AUTHORS_ASTRA_CHILD_THEME_VERSION,
        'all'
    );
}

add_action('wp_enqueue_scripts', 'child_enqueue_styles', 15);

*****************************************************/
/**
 * Function to get Author of Post
 *
 * @return html
 * @since 1.0.0
 */
if (!function_exists('astra_post_author')) {
    /**
     * Function to get Author of Post
     *
     * @param string $output_filter Filter string.
     *
     * @return html                Markup.
     */
    function astra_post_author($output_filter = '')
    {
        ob_start();

        echo '<span ';
        echo astra_attr(
            'post-meta-author',
            array(
                'class' => 'posted-by vcard author',
            )
        );
        echo '>';

        $authors = get_multiple_authors();

        foreach ($authors as $index => $author) {
            if ($index > 0) {
                echo ', ';
            }

            echo '<a title="' . sprintf(esc_attr__('View all posts by %1$s', 'astra'), $author->display_name) . '" href="' . esc_url($author->link) . '" rel="author" ' . astra_attr(
                    'author-url',
                    array(
                        'class' => 'url fn n',
                    )
                ) . '>';

            echo '<span ' . astra_attr(
                    'author-name',
                    array(
                        'class' => 'author-name',
                    )
                ) . '>' . $author->display_name . '</span>';

            echo '</a>';
        }
        ?>
        </span>

        <?php

        $output = ob_get_clean();

        return apply_filters('astra_post_author', $output, $output_filter);
    }
}

/**
 * Archive Page Title
 */
if (!function_exists('astra_archive_page_info')) {
    /**
     * Wrapper function for the_title()
     *
     * Displays title only if the page title bar is disabled.
     */
    function astra_archive_page_info()
    {
        if (apply_filters('astra_the_title_enabled', true)) {
            // Author.
            if (is_author()) { ?>

                <section class="ast-author-box ast-archive-description">
                    <div class="ast-author-bio">
                        <?php
                        $authors = get_multiple_authors(null, false, true);
                        $author = $authors[0];

                        do_action('astra_before_archive_title'); ?>
                        <h1 class='page-title ast-archive-title'><?php
                            echo $author->display_name; ?></h1>
                        <?php
                        do_action('astra_after_archive_title'); ?>
                        <p><?php
                            echo wp_kses_post($author->description); ?></p>
                        <?php
                        do_action('astra_after_archive_description'); ?>
                    </div>
                    <div class="ast-author-avatar">
                        <?php
                        echo $author->get_avatar(120); ?>
                    </div>
                </section>

                <?php
                // Category.
            } elseif (is_category()) {
                ?>

                <section class="ast-archive-description">
                    <?php
                    do_action('astra_before_archive_title'); ?>
                    <h1 class="page-title ast-archive-title"><?php
                        echo single_cat_title(); ?></h1>
                    <?php
                    do_action('astra_after_archive_title'); ?>
                    <?php
                    echo wp_kses_post(wpautop(get_the_archive_description())); ?>
                    <?php
                    do_action('astra_after_archive_description'); ?>
                </section>

                <?php
                // Tag.
            } elseif (is_tag()) {
                ?>

                <section class="ast-archive-description">
                    <?php
                    do_action('astra_before_archive_title'); ?>
                    <h1 class="page-title ast-archive-title"><?php
                        echo single_tag_title(); ?></h1>
                    <?php
                    do_action('astra_after_archive_title'); ?>
                    <?php
                    echo wp_kses_post(wpautop(get_the_archive_description())); ?>
                    <?php
                    do_action('astra_after_archive_description'); ?>
                </section>

                <?php
                // Search.
            } elseif (is_search()) {
                ?>

                <section class="ast-archive-description">
                    <?php
                    do_action('astra_before_archive_title'); ?>
                    <?php
                    /* translators: 1: search string */
                    $title = apply_filters(
                        'astra_the_search_page_title',
                        sprintf(
                            __('Search Results for: %s', 'astra'),
                            '<span>' . get_search_query() . '</span>'
                        )
                    );
                    ?>
                    <h1 class="page-title ast-archive-title"> <?php
                        echo $title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> </h1>
                    <?php
                    do_action('astra_after_archive_title'); ?>
                </section>

                <?php
                // Other.
            } else {
                ?>

                <section class="ast-archive-description">
                    <?php
                    do_action('astra_before_archive_title'); ?>
                    <?php
                    the_archive_title('<h1 class="page-title ast-archive-title">', '</h1>'); ?>
                    <?php
                    do_action('astra_after_archive_title'); ?>
                    <?php
                    echo wp_kses_post(wpautop(get_the_archive_description())); ?>
                    <?php
                    do_action('astra_after_archive_description'); ?>
                </section>

                <?php
            }
        }
    }

    add_action('astra_archive_header', 'astra_archive_page_info');
}

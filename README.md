# Anva Post Types

A WordPress plugin to help with theme development content.

## Post Types List

The plugin content a list of useful post types names and taxonomies.

- portfolio
- galleries
- slideshows
- testimonials
- clients
- services
- team
- anime: include `anime_episode`
- events

## How to Use

First in your theme, add the code below to add the post types to the admin panel.

```php
/**
 * Post types to be used in the theme.
 *
 * @return array
 */
function theme_post_types() {

    $post_types = array(
        'portfolio',
        'galleries',
        'slideshows',
        'testimonials',
        'clients',
        'team',
    );

    return $post_types;
}
add_filter( 'anva_post_types_list', 'theme_post_types', 1 );
```

In this case the post types added will be `portfolio`, `galleries`, etc. The taxonomies are registered automatically.

If you want to add just one post type:

```php
/**
 * Post types to be used in the theme.
 *
 * @return array
 */
function theme_post_type_portfolio() {

    $post_types = array( 'portfolio' );

    return $post_types;
}
add_filter( 'anva_post_types_list', 'theme_post_type_portfolio', 1 );
```

# Relevant Recent Posts
Adds WordPress shortcodes for recent posts lists.

## Purpose
The Relevant Recent Posts plugin enhances your WordPress site by providing a simple yet flexible way to display lists of recent posts. It's particularly useful for bloggers and content-focused websites, where showcasing related or recent content can improve SEO, user engagement, and site navigation.

## Installation
1. Download the latest release.
2. Upload and install the plugin zip file under Plugins > Add New Plugin > Upload Plugin.
3. Activate the plugin.

## Available Shortcodes
- **[recentposts]**: Displays the 10 most recent posts from the same category as the current post. 
- **[recentposts count=5]**: Changes the number of displayed posts. Replace `5` with your desired number.
- **[recentposts scope=global]**: Displays recent posts from all categories.
- **[recentposts date=modified]**: Sorts posts by the last modified date instead of the published date.
- **[recentposts class=example-class]**: Applies a custom CSS class (`example-class`) to the list for styling. Replace `example-class` with your own class name.
- **[recentposts nonav]**: Removes `<nav>` tags from the posts list.
- You can combine multiple parameters, like `[recentposts date=modified scope=global count=5 class=example-class]`.

## Usage
Add the shortcode `[recentposts]` to any post, page, or widget where you want to display the list of recent posts. Customize its behavior using the available parameters.

## Support
For support, feature requests, or bug reports, please visit the [GitHub issues page](https://github.com/infinitnet/relevant-recent-posts/issues).

## Contributions
Contributions to the plugin are welcome. Please fork the GitHub repository and submit a pull request.

## Changelog
- **1.2**: Fix: cache key has to include category ID.
- **1.1**: Transient caching logic and cache clearing improved.
- **1.0**: Initial release.

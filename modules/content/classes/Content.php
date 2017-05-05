<? defined('SYSPATH') or die('No direct script access.');


class Content
{
    public static function breadcrumbs($slug)
    {
        /** @var $contentModel Model_Content */
        $contentModel = Model::factory('Content');

        $mainMenu = $contentModel->findMenuByPageSlug('main');
        $rootMenu = $contentModel->findMenuByPageSlug($slug);

        $links = '<a href="/">' . $mainMenu['title'] . '</a>';

        if ($mainMenu !== $rootMenu) {
            $links .= ' &nbsp;/&nbsp; <span>' . $rootMenu['title'] . '</span>';
        }

        $html = '<div class="breadcrumbs">';
        $html .= $links;
        $html .= '</div>';

        return $html;
    }
}
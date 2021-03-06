<?php namespace Anomaly\PagesModule\Page;

use Anomaly\PagesModule\Page\Contract\PageInterface;
use Anomaly\Streams\Platform\Support\Authorizer;
use Anomaly\UsersModule\User\Contract\UserInterface;
use Illuminate\Auth\Guard;

/**
 * Class PageAuthorizer
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\PagesModule\Page
 */
class PageAuthorizer
{

    /**
     * The authorization utility.
     *
     * @var Guard
     */
    protected $guard;

    /**
     * The authorizer utility.
     *
     * @var Authorizer
     */
    protected $authorizer;

    /**
     * Create a new PageAuthorizer instance.
     *
     * @param Guard      $guard
     * @param Authorizer $authorizer
     */
    public function __construct(Guard $guard, Authorizer $authorizer)
    {
        $this->guard      = $guard;
        $this->authorizer = $authorizer;
    }

    /**
     * Authorize the page.
     *
     * @param PageInterface $page
     */
    public function authorize(PageInterface $page)
    {
        /* @var UserInterface $user */
        $user = $this->guard->user();

        /**
         * If the page is not live and we
         * are not logged in then 404.
         */
        if (!$page->isLive() && !$user) {
            abort(404);
        }

        /**
         * If the page is not live and we are
         * logged in then make sure we have permission.
         */
        if (!$page->isLive()) {
            $this->authorizer->authorize('anomaly.module.pages::view_drafts');
        }

        /**
         * If the page is restricted to specific
         * roles then make sure our user is one of them.
         */
        $allowed = $page->getAllowedRoles();

        if (!$allowed->isEmpty() && (!$user || !$user->hasAnyRole($allowed))) {
            abort(403);
        }
    }
}

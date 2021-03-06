<?php namespace Anomaly\PagesModule\Page;

use Anomaly\PagesModule\Page\Contract\PageInterface;
use Anomaly\SettingsModule\Setting\Contract\SettingRepositoryInterface;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class PageHttp
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\PagesModule\Page
 */
class PageHttp
{

    /**
     * The setting repository.
     *
     * @var SettingRepositoryInterface
     */
    protected $settings;

    /**
     * Create a new PageHttp instance.
     *
     * @param SettingRepositoryInterface $settings
     */
    public function __construct(SettingRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Cache the page's HTTP response.
     *
     * @param PageInterface|Arrayable $page
     */
    public function cache(PageInterface $page)
    {
        if (!$page->isLive()) {
            return;
        }

        $ttl      = $page->getTtl();
        $response = $page->getResponse();

        // if no page TTL use the page type TTL.
        if ($ttl === null && $type = $page->getType()) {
            $ttl = $type->getTtl();
        }

        if ($ttl && $seconds = $ttl * 60) {

            $response->headers->set('Content-Type', 'text/html');
            $response->headers->set('Etag', $page->etag());
            $response->headers->set(
                'Last-Modified',
                $page->lastModified()->setTimezone('GMT')->format('D, d M Y H:i:s T')
            );

            $response->setTtl($seconds);
        }
    }
}

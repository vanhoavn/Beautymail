<?php

namespace Snowfire\Beautymail;

class CssInlinerPlugin implements \Swift_Events_SendListener
{
    /**
     * Initialize the CSS inliner.
     */
    public function __construct()
    {
    }

    /**
     * Inline the CSS before an email is sent.
     *
     * @param \Swift_Events_SendEvent $evt
     */
    public function beforeSendPerformed(\Swift_Events_SendEvent $evt)
    {
        $message = $evt->getMessage();

        $properTypes = [
            'text/html',
            'multipart/alternative',
            'multipart/mixed',
        ];

        if ($message->getBody() && in_array($message->getContentType(), $properTypes)) {
            $message->setBody(\Pelago\Emogrifier\CssInliner::fromHtml($message->getBody()));
        }

        foreach ($message->getChildren() as $part) {
            if (strpos($part->getContentType(), 'text/html') === 0) {
                $message->setBody(\Pelago\Emogrifier\CssInliner::fromHtml($part->getBody()));
            }
        }
    }

    /**
     * Do nothing.
     *
     * @param \Swift_Events_SendEvent $evt
     */
    public function sendPerformed(\Swift_Events_SendEvent $evt)
    {
        // Do Nothing
    }
}


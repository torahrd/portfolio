<?php

namespace App\Policies;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policies\Policy;

class GoogleMapsPolicy extends Policy
{
    public function configure()
    {
        $this
            ->addDirective(Directive::BASE, Keyword::SELF)
            ->addDirective(Directive::CONNECT, Keyword::SELF, 'https://*.googleapis.com', 'https://*.cloudinary.com')
            ->addDirective(Directive::DEFAULT, Keyword::SELF)
            ->addDirective(Directive::FORM_ACTION, Keyword::SELF)
            ->addDirective(Directive::IMG, Keyword::SELF, 'data:', 'https://maps.gstatic.com', 'https://*.googleapis.com', 'https://*.cloudinary.com', 'https://res.cloudinary.com')
            ->addDirective(Directive::MEDIA, Keyword::SELF)
            ->addDirective(Directive::OBJECT, Keyword::NONE)
            ->addDirective(Directive::SCRIPT, Keyword::SELF, Keyword::UNSAFE_INLINE, 'https://maps.googleapis.com', 'https://maps.googleapis.com/maps/api/js')
            ->addDirective(Directive::STYLE, Keyword::SELF, Keyword::UNSAFE_INLINE, 'https://fonts.googleapis.com')
            ->addDirective(Directive::FONT, 'https://fonts.gstatic.com')
            ->addNonceForDirective(Directive::SCRIPT)
            ->addNonceForDirective(Directive::STYLE);
    }
} 
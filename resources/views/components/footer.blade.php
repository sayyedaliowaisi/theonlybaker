{{-- THE ONLY BAKER - PREMIUM FOOTER --}}
<footer class="tob-footer">
    <div class="tob-footer-container">
        <div class="tob-footer-main">
            {{-- BRAND --}}
            <div class="tob-footer-brand tob-footer-animate">
                <a href="{{ route('home') }}" class="tob-footer-logo">
                    <img src="{{ asset('images/logo/footer-logo.png') }}" alt="The Only Baker">
                </a>
                <div class="tob-footer-tagline-wrap">
                    <p class="tob-footer-tagline">Small Bites. Big Happiness.</p>
                    <svg class="tob-footer-tagline-heart" viewBox="0 0 35 32" aria-hidden="true">
                        <path d="M17 28C14 24 5 18 5 11C5 6 11 4 15 8L17 11L20 8C24 4 30 6 30 11C30 18 21 24 17 28"/>
                    </svg>
                </div>
                <span class="tob-footer-tagline-line"></span>
                {{-- Social profile URLs have not been provided; don't publish dead # links. --}}
                <div class="tob-footer-socials" aria-label="Social profiles">
                    <a aria-label="Instagram (link pending)" title="Instagram link pending">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1"/></svg>
                    </a>
                    <a aria-label="Facebook (link pending)" title="Facebook link pending"><span class="tob-footer-facebook">f</span></a>
                    <a aria-label="Pinterest (link pending)" title="Pinterest link pending"><span class="tob-footer-pinterest">p</span></a>
                    <a aria-label="YouTube (link pending)" title="YouTube link pending">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="6" width="18" height="12" rx="4"/><path d="M10 9L15 12L10 15Z"/></svg>
                    </a>
                </div>
            </div>

            {{-- QUICK LINKS --}}
            <div class="tob-footer-column tob-footer-animate">
                <h3>Quick Links</h3>
                <span class="tob-footer-heading-line"></span>
                <ul>
                    @foreach ([
                        ['home', 'Home'],
                        ['shop', 'Shop'],
                        ['collections', 'Collections'],
                        ['gifting', 'Gifting'],
                        ['custom.orders', 'Custom Orders'],
                        ['about', 'Our Story'],
                        ['contact', 'Contact'],
                    ] as [$routeName, $label])
                        <li>
                            <a href="{{ route($routeName) }}">
                                <span>{{ $label }}</span><span class="tob-footer-link-arrow">›</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            
{{-- HELP --}}
<div class="tob-footer-column tob-footer-animate">
    <h3>Help</h3>
    <span class="tob-footer-heading-line"></span>

    <ul>
        <li>
            <a href="{{ route('faq') }}">
                <span>FAQ</span>
                <span class="tob-footer-link-arrow">›</span>
            </a>
        </li>

        {{-- TRACK ORDER: ONLY FOR LOGGED-IN CUSTOMERS --}}
        @auth
            <li>
                <a href="{{ route('orders.track') }}">
                    <span>Track Your Order</span>
                    <span class="tob-footer-link-arrow">›</span>
                </a>
            </li>
        @endauth

        <li>
            <a href="{{ route('shipping.delivery') }}">
                <span>Shipping &amp; Delivery</span>
                <span class="tob-footer-link-arrow">›</span>
            </a>
        </li>

        <li>
            <a href="{{ route('refund.policy') }}">
                <span>Return &amp; Refund</span>
                <span class="tob-footer-link-arrow">›</span>
            </a>
        </li>

        <li>
            <a href="{{ route('terms.conditions') }}">
                <span>Terms &amp; Conditions</span>
                <span class="tob-footer-link-arrow">›</span>
            </a>
        </li>

        <li>
            <a href="{{ route('privacy.policy') }}">
                <span>Privacy Policy</span>
                <span class="tob-footer-link-arrow">›</span>
            </a>
        </li>
    </ul>
</div>


            {{-- CONTACT --}}
            <div class="tob-footer-contact tob-footer-animate">
                <h3>Get in Touch</h3>
                <span class="tob-footer-heading-line"></span>
                <a href="tel:+916206490079" class="tob-footer-contact-item">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M7 3H4.5C3.7 3 3 3.7 3 4.5C3 13.6 10.4 21 19.5 21C20.3 21 21 20.3 21 19.5V17L16.7 16L15.4 18C11.3 16.3 7.7 12.7 6 8.6L8 7.3L7 3Z"/>
                    </svg>
                    <span>+91 6206490079</span>
                </a>
                <a href="mailto:theonlybaker@gmail.com" class="tob-footer-contact-item">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <rect x="3" y="5" width="18" height="14" rx="2"/>
                        <path d="M4 7L12 13L20 7"/>
                    </svg>
                    <span>theonlybaker@gmail.com</span>
                </a>
                <div class="tob-footer-contact-item">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 21C12 21 18 15.5 18 10C18 6.7 15.3 4 12 4C8.7 4 6 6.7 6 10C6 15.5 12 21 12 21Z"/>
                        <circle cx="12" cy="10" r="2"/>
                    </svg>
                    <span>India</span>
                </div>
                <div class="tob-footer-connect">
                    <span class="tob-footer-connect-line"></span>
                    <p>Let's Stay Connected</p>
                    <div class="tob-footer-contact-social" aria-label="Social profiles">
                        <a aria-label="Instagram (link pending)" title="Instagram link pending">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1"/></svg>
                    </a>
                    <a aria-label="Facebook (link pending)" title="Facebook link pending"><span class="tob-footer-facebook">f</span></a>
                    <a aria-label="Pinterest (link pending)" title="Pinterest link pending"><span class="tob-footer-pinterest">p</span></a>
                    <a aria-label="YouTube (link pending)" title="YouTube link pending">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="6" width="18" height="12" rx="4"/><path d="M10 9L15 12L10 15Z"/></svg>
                    </a>
                    </div>
                </div>
            </div>

            {{-- NEWSLETTER: awaiting actual subscription endpoint --}}
            <div class="tob-footer-newsletter tob-footer-animate">
                <h3>Subscribe to Our Newsletter</h3>
                <span class="tob-footer-heading-line"></span>
                <p class="tob-footer-newsletter-description">Get updates on new flavours, offers, and more.</p>
                <form class="tob-footer-newsletter-form" action="#" method="post" onsubmit="return false;">
                    <input type="email" name="email" placeholder="Enter your email" aria-label="Enter your email" autocomplete="email" required>
                    <button type="submit" aria-label="Subscribe" title="Newsletter signup coming soon" disabled>
                        <svg viewBox="0 0 30 20" aria-hidden="true">
                            <path d="M2 10H25"/><path d="M19 4L25 10L19 16"/>
                        </svg>
                    </button>
                </form>
                <p class="tob-footer-newsletter-description">Newsletter signup coming soon.</p>
                <div class="tob-footer-benefits">
                    <div class="tob-footer-benefit">
                        <svg viewBox="0 0 40 40" aria-hidden="true">
                            <rect x="8" y="15" width="24" height="19" rx="1"/>
                            <path d="M20 15V34M6 15H34V21H6Z"/>
                            <path d="M20 15C16 14 12 12 12 9C12 7 14 6 16 7C19 8 20 12 20 15"/>
                            <path d="M20 15C24 14 28 12 28 9C28 7 26 6 24 7C21 8 20 12 20 15"/>
                        </svg>
                        <span>Exclusive<br>Offers</span>
                    </div>
                    <span class="tob-footer-benefit-divider"></span>
                    <div class="tob-footer-benefit">
                        <svg viewBox="0 0 40 40" aria-hidden="true">
                            <circle cx="20" cy="20" r="13"/>
                            <circle cx="16" cy="14" r="2"/><circle cx="25" cy="19" r="2"/><circle cx="18" cy="26" r="2"/>
                            <path d="M27 10C25 13 26 16 30 17"/>
                        </svg>
                        <span>New<br>Launches</span>
                    </div>
                    <span class="tob-footer-benefit-divider"></span>
                    <div class="tob-footer-benefit">
                        <svg viewBox="0 0 40 40" aria-hidden="true">
                            <path d="M20 5L24 9L30 8L32 14L36 18L32 23L31 29L25 30L20 35L15 31L9 32L7 26L3 21L7 16L8 10L14 9Z"/>
                            <path d="M15 25L25 15"/><circle cx="15" cy="15" r="2"/><circle cx="25" cy="25" r="2"/>
                        </svg>
                        <span>Special<br>Discounts</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="tob-footer-bottom">
            <p class="tob-footer-copyright">© {{ date('Y') }} The Only Baker. All rights reserved.</p>
            <div class="tob-footer-handwritten">
                <span>Baked to make you smile</span>
                <svg viewBox="0 0 35 32" aria-hidden="true">
                    <path d="M17 28C14 24 5 18 5 11C5 6 11 4 15 8L17 11L20 8C24 4 30 6 30 11C30 18 21 24 17 28"/>
                </svg>
                <span class="tob-footer-handwritten-line"></span>
            </div>
        </div>
    </div>
</footer>

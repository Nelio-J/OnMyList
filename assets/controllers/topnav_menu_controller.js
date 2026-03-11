import { Controller } from '@hotwired/stimulus';
import { enableBodyScroll, disableBodyScroll } from "https://cdn.jsdelivr.net/gh/rick-liruixin/body-scroll-lock-upgrade@v1.1.0/lib/index.esm.js";

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://symfony.com/bundles/StimulusBundle/current/index.html#lazy-stimulus-controllers
*/

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ["button"]

    initialize() {
        // Called once when the controller is first instantiated (per element)

        // Here you can initialize variables, create scoped callables for event
        // listeners, instantiate external libraries, etc.
        // this._fooBar = this.fooBar.bind(this)

        this._media = window.matchMedia('(width < 1050px)');
        this._boundSetupTopNav = this.setupTopNav.bind(this);
    }

    connect() {
        // Called every time the controller is connected to the DOM
        // (on page load, when it's added to the DOM, moved in the DOM, etc.)

        // Here you can add event listeners on the element or target elements,
        // add or remove classes, attributes, dispatch custom events, etc.
        // this.fooTarget.addEventListener('click', this._fooBar)

        // const media = window.matchMedia('(width < 1050px)');
        this._media.addEventListener('change', this._boundSetupTopNav);
        this.setupTopNav({ matches: this._media.matches });
    }

    setupTopNav(e) {
        const topNavMenu = document.querySelector('.topnav_menu');

        if (e.matches) {
            console.log('Is mobile');
            // Only set inert if menu is not currently open
            if (this.buttonTarget.getAttribute('aria-expanded') !== 'true') {
                topNavMenu.setAttribute('inert', '');
            }
            topNavMenu.style.transition = 'none';
        }
        else {
            console.log('Is desktop');
            topNavMenu.removeAttribute('inert');
        }
    }

    openMenu(event) {
        const topNavMenu = document.querySelector('.topnav_menu'); 
        const main = document.querySelector('main');

        const button = event.currentTarget;
        const expanded = button.getAttribute('aria-expanded') === 'true' || false;
        button.setAttribute('aria-expanded', !expanded);
        topNavMenu.removeAttribute('inert');
        topNavMenu.removeAttribute('style');
        main.setAttribute('inert', '');
        disableBodyScroll(main);
    }

    closeMenu() {        
        const topNavMenu = document.querySelector('.topnav_menu');
        const main = document.querySelector('main');

        this.buttonTarget.setAttribute('aria-expanded', 'false');
        topNavMenu.setAttribute('inert', '');
        main.removeAttribute('inert');
        enableBodyScroll(main);

        setTimeout(() => {
            topNavMenu.style.transition = 'none';
        }, 500);
    }

    // Add custom controller actions here
    // fooBar() { this.fooTarget.classList.toggle(this.bazClass) }

    disconnect() {
        // Called anytime its element is disconnected from the DOM
        // (on page change, when it's removed from or moved in the DOM, etc.)

        // Here you should remove all event listeners added in "connect()" 
        // this.fooTarget.removeEventListener('click', this._fooBar)

        this._media.removeEventListener('change', this._boundSetupTopNav);
    }
}

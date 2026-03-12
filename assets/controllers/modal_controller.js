import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://symfony.com/bundles/StimulusBundle/current/index.html#lazy-stimulus-controllers
*/

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['dialog', 'type', 'name', 'spotifyId', 'image', 'releaseDate', 'artists'];

    connect() {
        // Called every time the controller is connected to the DOM
        // (on page load, when it's added to the DOM, moved in the DOM, etc.)

        // Here you can add event listeners on the element or target elements,
        // add or remove classes, attributes, dispatch custom events, etc.
        // this.fooTarget.addEventListener('click', this._fooBar)
    }

    open(event) {
        const button = event.currentTarget;

        // Fill the modal's hidden fields
        if (this.hasTypeTarget) this.typeTarget.value = button.dataset.type || '';
        if (this.hasNameTarget) this.nameTarget.value = button.dataset.name || '';
        if (this.hasSpotifyIdTarget) this.spotifyIdTarget.value = button.dataset.spotifyId || '';
        if (this.hasImageTarget) this.imageTarget.value = button.dataset.image || '';
        if (this.hasReleaseDateTarget) this.releaseDateTarget.value = button.dataset.releaseDate || '';
        if (this.hasArtistsTarget) this.artistsTarget.value = button.dataset.artists || '';

        // show the modal
        if (this.hasDialogTarget) {
            this.dialogTarget.showModal();
        }
    }

    close() {
        if (this.hasDialogTarget) {
            this.dialogTarget.close();
        }
    }
}

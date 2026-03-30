import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://symfony.com/bundles/StimulusBundle/current/index.html#lazy-stimulus-controllers
*/

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    // static values = {
    //   config: {type: Object, default: {input: {wait: 800}}}
    // };

    debounce(fn, delay){
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => { fn.apply(this, args); }, delay);
        };
    }
    
    initialize() {
        // Called once when the controller is first instantiated (per element)

        // Here you can initialize variables, create scoped callables for event
        // listeners, instantiate external libraries, etc.
        // this._fooBar = this.fooBar.bind(this)
    }

    connect() {
        // Called every time the controller is connected to the DOM
        // (on page load, when it's added to the DOM, moved in the DOM, etc.)

        // Here you can add event listeners on the element or target elements,
        // add or remove classes, attributes, dispatch custom events, etc.
        // this.fooTarget.addEventListener('click', this._fooBar)
        this.debouncedSaveNote = this.debounce((textarea) => {
            this.saveItemChange(textarea.dataset.itemId, { note: textarea.value });
        }, 800);
    }

    // Add custom controller actions here
    // fooBar() { this.fooTarget.classList.toggle(this.bazClass) }
    flipCard(event) {
        // ignore clicks on interactive elements or anything with .no-flip
        if (event.target.closest('a, button, input, textarea, select, label, .no-flip')) {
            return;
        }
        this.element.classList.toggle("is-flipped");
    }

    async saveItemChange(itemId, data) {
        try {
            const response = await fetch(`/backlog/item/edit-meta`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ item_id: itemId, ...data }),
            });

            const result = await response.json();

            if (result.success) {
                console.log('Success:', result);
            }
        } catch (error) {
            console.error('Error:', error);
        }

    }

    saveCardStatus(event) {
        const select = event.target;
        this.saveItemChange(select.dataset.itemId, { status: select.value });
    }

    saveCardNote(event) {
        const textarea = event.target;
        this.debouncedSaveNote(textarea);
    }


    disconnect() {
        // Called anytime its element is disconnected from the DOM
        // (on page change, when it's removed from or moved in the DOM, etc.)

        // Here you should remove all event listeners added in "connect()" 
        // this.fooTarget.removeEventListener('click', this._fooBar)
    }
}

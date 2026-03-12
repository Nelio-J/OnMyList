import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://symfony.com/bundles/StimulusBundle/current/index.html#lazy-stimulus-controllers
*/

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ["grid", "checkbox", "input"]

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
    }

    // Add custom controller actions here
    // fooBar() { this.fooTarget.classList.toggle(this.bazClass) }
    sortItems(event) {
        const value = event.target.value
        const grid = this.gridTarget;
        const nodes = Array.from(grid.querySelectorAll('.item-card'));

        const itemsArray = nodes.map(node => {
            const name = node.dataset.name.toLowerCase();
            const dateAdded = node.dataset.dateAdded;
            return { node, name, dateAdded };
        });

        if (value === 'name') {
            itemsArray.sort((a, b) => { return a.name.localeCompare(b.name); });
        }

        if (value === 'nameDesc') {
            itemsArray.sort((a, b) => { return b.name.localeCompare(a.name); });
        }

        if (value === 'dateAdded') {
            itemsArray.sort((a, b) => { return new Date(b.dateAdded) - new Date(a.dateAdded); });
        }

        if (value === 'dateAddedDesc') {
            itemsArray.sort((a, b) => { return new Date(a.dateAdded) - new Date(b.dateAdded); });
        }

        grid.innerHTML = '';
        itemsArray.forEach(item => { grid.appendChild(item.node); });
    }

    filterItems() {
        // Collect the IDs of all checked filter checkboxes under the .filter-items container and store them in an array
        // const checkedItems = document.querySelectorAll('.filter-items input[type="checkbox"]:checked');
        // const itemsArray = Array.from(checkedItems).map(cb => cb.id);
        const itemsArray = this.checkboxTargets
            .filter(cb => cb.checked)
            .map(cb => cb.id)

        console.log('Checked items:', itemsArray);

        const grid = this.gridTarget;
        const nodes = Array.from(grid.querySelectorAll('.item-card'));

        nodes.forEach(node => {
            const itemType = node.dataset.type;

            // Show item if no filters are checked or if its type is among the checked filters
            if (itemsArray.length === 0 || itemsArray.includes(itemType)) {
                node.style.display = '';
            }
            // Else if the item's type is not in the checked filters, hide it
            else {
                node.style.display = 'none';
            }
        });
    }

    searchItems(e) {
        const searchString = e.target.value.toLowerCase();

        const grid = this.gridTarget;
        const nodes = Array.from(grid.querySelectorAll('.item-card'));

        nodes.forEach(node => {
            const name = node.dataset.name.toLowerCase();
            const subtitle = node.dataset.subtitle ? node.dataset.subtitle.toLowerCase() : '';

            const matches = name.includes(searchString) || subtitle.includes(searchString);

            node.style.display = matches ? '' : 'none';
        });
    }

    disconnect() {
        // Called anytime its element is disconnected from the DOM
        // (on page change, when it's removed from or moved in the DOM, etc.)

        // Here you should remove all event listeners added in "connect()" 
        // this.fooTarget.removeEventListener('click', this._fooBar)
    }
}

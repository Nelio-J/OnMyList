// Functionality for sorting and filtering items in the backlog

// Sort items
const grid = document.querySelector('.items-grid');

document.querySelectorAll('.sort-items-select').forEach(select => {
  select.addEventListener('change', () => {
    sortItems(select.value);
  });
});

function sortItems(value) {
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
        itemsArray.sort((a, b) => { return new Date(a.dateAdded) - new Date(b.dateAdded); });
    }

    if (value === 'dateAddedDesc') {
        itemsArray.sort((a, b) => { return new Date(b.dateAdded) - new Date(a.dateAdded); });
    }

    grid.innerHTML = '';
    itemsArray.forEach(item => { grid.appendChild(item.node); });
}


// Filter items
document.querySelectorAll('.filter-items input[type="checkbox"]').forEach(checkbox => {
  checkbox.addEventListener('change', filterItems);
});

function filterItems() {
    // Collect the IDs of all checked filter checkboxes under the .filter-items container and store them in an array
    const checkedItems = Array.from(document.querySelectorAll('.filter-items input[type="checkbox"]:checked')).map(cb => cb.id);

    console.log('Checked items:', checkedItems);

    const nodes = Array.from(grid.querySelectorAll('.item-card'));

    nodes.forEach(node => {
        const itemType = node.dataset.type;

        // Show item if no filters are checked or if its type is among the checked filters
        if (checkedItems.length === 0 || checkedItems.includes(itemType)) {
            node.style.display = '';
        }
        // Else if the item's type is not in the checked filters, hide it
        else {
            node.style.display = 'none';
        }
    });
}
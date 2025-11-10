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
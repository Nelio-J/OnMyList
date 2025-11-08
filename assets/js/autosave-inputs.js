function debounce(fn, delay){
  let timer;
  return (...args) => {
    clearTimeout(timer);
    timer = setTimeout(() => { fn.apply(this, args); }, delay);
  };
}

document.querySelectorAll('.item-note').forEach(textarea => {
  textarea.addEventListener('input', debounce(() => {
    saveItemChange(textarea.dataset.itemId, { note: textarea.value });
  }, 800));
});

document.querySelectorAll('.status-select').forEach(select => {
  select.addEventListener('change', () => {
    saveItemChange(select.dataset.itemId, { status: select.value });
  });
});

async function saveItemChange(itemId, data) {
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
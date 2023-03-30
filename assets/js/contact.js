const addFormToCollection = (e) => {
    const collectionHolder = document.getElementById(e.currentTarget.getAttribute('data-collection-holder-id'));
    console.log(collectionHolder);
    if (!collectionHolder.hasAttribute('data-index') ) {
        collectionHolder.setAttribute('data-index', 0);
    }

    const item = document.createElement('div');

    item.innerHTML = collectionHolder
    .getAttribute('data-prototype')
    .replace(
        /__name__/g,
        collectionHolder.getAttribute('data-index')
    );

    collectionHolder.appendChild(item);

    collectionHolder.setAttribute('data-index', collectionHolder.getAttribute('data-index')+1);
};

document.addEventListener('DOMContentLoaded', function() {
    document
        .querySelectorAll('.add_item_link')
        .forEach(btn => {
        btn.addEventListener("click", addFormToCollection)
    });
});

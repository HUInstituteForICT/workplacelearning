<script>

const itemsLimit = 3;

function chooseItem(sli_id) {
    document.getElementById('sl-item_id').value = sli_id;
}

function getSelectedItems() {
    var check_list = document.getElementsByName('check_list[]');
    var selectedItems = Object.values(check_list).filter(function(item) {
        return item.checked;
    });
    return selectedItems;
}

function countSelectedItems() {
    var lenSelectedItems = getSelectedItems().length;
    document.getElementById('selected-items-count').innerHTML = `${lenSelectedItems}/${itemsLimit}`;
    document.getElementById('selected-items-count').style.color = '#333';
    document.getElementById('addItemsButton').classList.remove('disabled');

    if (lenSelectedItems > itemsLimit) {
        document.getElementById('addItemsButton').classList.add('disabled');
        document.getElementById('selected-items-count').style.color = 'red';
    }
}

</script>

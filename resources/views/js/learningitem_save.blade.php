<script>

const itemsLimit = 3;

function chooseItem(id) {
    document.getElementById('sl-item_id').value = id;
}

function getSelectedItems() {
    var check_list = document.getElementsByName('check_list[]');
    var selectedItems = Object.values(check_list).filter(function(item) {
        return item.checked;
    });
    return selectedItems;
}

function setSelectedFolder(folder) {
    document.getElementById('selected-items-count').innerHTML = `${getSelectedItems().length}/${itemsLimit}`;
    document.getElementById('folder-title').innerHTML = folder.title;
    document.getElementById('folder-created-at').innerHTML = folder.created_at;
    document.getElementById('selected_folder_id').value = folder.folder_id;
}

function countSelectedItems() {
    var lenSelectedItems = getSelectedItems().length;
    document.getElementById('selected-items-count').innerHTML = `${lenSelectedItems}/${itemsLimit}`;
    document.getElementById('selected-items-count').style.color = '#333';

    if (lenSelectedItems >= itemsLimit) {
        document.getElementById('selected-items-count').style.color = 'red';
    }
}

</script>
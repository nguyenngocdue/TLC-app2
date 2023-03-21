const radioOrCheckboxSelectAll = (id) => {
    queryStr = "input:checkbox[name='" + id + "[]']"
    $(queryStr).prop('checked', true)
}

const radioOrCheckboxDeselectAll = (id) => {
    queryStr = "input:checkbox[name='" + id + "[]']"
    $(queryStr).prop('checked', false)
}

const toNodeList = function (arrayOfNodes) {
    var fragment = document.createDocumentFragment();
    arrayOfNodes.forEach(function (item) {
        fragment.appendChild(item)//.cloneNode());
    });
    console.log(fragment.childNodes)
    return fragment.childNodes;
};

const radioOrCheckboxChangeOrder = (id) => {
    queryStr = "div[id='" + id + "']"
    console.log(queryStr)
    const allItems = Array.from($(queryStr)[0].childNodes)

    allItems.sort((a, b) => a.getAttribute('item_description').localeCompare(b.getAttribute('item_description')))
    const nodeList = toNodeList(allItems)
    // $(queryStr)[0] = nodeList
    for (let i = 0; i < nodeList.length; i++) {
        $(queryStr)[0].appendChild(nodeList[i])
    }
    console.log('Change Order', nodeList, allItems)
}
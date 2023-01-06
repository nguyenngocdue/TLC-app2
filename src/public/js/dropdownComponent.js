const onChangedItem = (value, colName) => {
    colName = colName.getAttribute("name");
    objListener = Object.values(listenersJson).find((item) => item.triggers === colName);
    if (typeof objListener === 'undefined') return false;
    const {
        listen_to_attrs
        , listen_to_fields
        , column_name
    } = objListener

    let dataListenTo = k[k2[listen_to_fields]];
    itemsDB = [];
    // console.log(dataListenTo, listen_to_fields, column_name);
    if (listen_to_fields === column_name) {
        itemsDB = dataListenTo.filter(ele => {
            return ele[listen_to_attrs] === value;
        })
    } else {
        dataListenTo.forEach(ele => {
            if (ele.id === value) {
                idListener = ele[listen_to_attrs];
                itemsDB = k[k2[column_name]].filter(u => u.id === idListener);
            }
        })
    }
    strHtmlRender = itemsDB.map((item, index) => {
        return ` <option value="${item.id}">${item.name}</option>`
    })
    let eles = document.getElementById("select-dropdown-" + column_name);
    let headOption = listen_to_fields !== column_name ? [] : [`<option class="py-10" value="" selected>Select your option...</option>`]
    eles.innerHTML = strHtmlRender + headOption;
}

function renderSelect({ id, name }) {
    // console.log('renderSelect', id)
    const strHTML = ` 
    <select name='${name}' id="${id}" onchange="onChangedItem(value*1, ${name})" class=" bg-white border border-gray-300 text-sm rounded-lg block mt-1 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
        <option class="py-10" value="" selected>Select your option...</option>
    </select>`;

    eleTriggers = document.getElementById('add-' + name);
    if (eleTriggers !== null) eleTriggers.innerHTML += strHTML;

}


function dropdownComponent({ id, name, dataSource, selected }) {
    renderSelect({ id, name });
    // console.log('renderOption', id);
    strHtmlTrigger = dataSource.map(item => {
        checkSelected = selected * 1 === item.id * 1 || dataSource.length < 1 ? "selected" : "";
        return `
                <option ${checkSelected} value=${item.id}>
                        <span>prefix: ${item.name}</span>
                        <span>suffix: ${item.id}</span>            
                </option>
    `})

    eleTriggers = document.getElementById("select-dropdown-" + name);
    if (eleTriggers !== null) eleTriggers.innerHTML += strHtmlTrigger;
}










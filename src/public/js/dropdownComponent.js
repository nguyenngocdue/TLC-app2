function getFieldNameInK(name, array) {
    let _name = name.substring(0, name.indexOf("_"));
    const fieldName = array.find((item) => {
        if (item.includes(_name)) {
            return item;
        }
    })
    return fieldName;
}

const onChangedItem = (value, colName) => {
    colName = colName.getAttribute("name");
    objListener = Object.values(listenersJson).find((item) => item.triggers === colName);
    if (typeof objListener === 'undefined') return false;
    const {
        listen_to_attrs
        , listen_to_fields
        , column_name
    } = objListener

    let fieldName = getFieldNameInK(listen_to_fields, arrayKeysK);
    let dataListenTo = k[fieldName];
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
                let _fieldName = getFieldNameInK(column_name, arrayKeysK);
                itemsDB = k[_fieldName].filter(u => u.id === idListener);
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

function renderSelect({ name }) {
    const strHTML = ` 
    <select name='${name}' id="select-dropdown-${name}" onchange="onChangedItem(value*1, ${name})" class=" bg-white border border-gray-300 text-sm rounded-lg block mt-1 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
        <option class="py-10" value="" selected>Select your option...</option>
    </select>`;

    eleTriggers = document.getElementById('add-' + name);
    if (eleTriggers !== null) eleTriggers.innerHTML += strHTML;
    return eleTriggers;
}


function dropdownComponent({ idDOM, name, dataSource, selected }) {
    renderSelect({ name });

    strHtmlTrigger = dataSource.map((item, index) => {
        checkSelected = selected * 1 === item.id * 1 || dataSource.length < 1 ? "selected" : "";
        return `
                <option ${checkSelected} value=${item.id}>
                        <span>prefix: ${item.name}</span>
                        <span>suffix: ${item.id}</span>            
                </option>
    `})

    eleTriggers = document.getElementById("select-dropdown-" + name);
    if (eleTriggers !== null) eleTriggers.innerHTML += strHtmlTrigger;
    return eleTriggers;

}










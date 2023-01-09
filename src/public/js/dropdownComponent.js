const onChangedItem = (value, colName) => {
    colName = colName.getAttribute("name");
    objListener = Object.values(listenersJson).find((item) => item.triggers === colName);
    if (typeof objListener === 'undefined') return false;
    const {
        listen_to_attrs
        , listen_to_fields
        , column_name
    } = objListener

    let dataListenTo = k[k2[listen_to_fields]][listen_to_fields];
    // console.log(dataListenTo);
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
                itemsDB = k[k2[column_name]][column_name].filter(u => u.id === idListener);
            }
        })
    }
    strHtmlRender = itemsDB.map((item, index) => {
        return ` <option value="${item.id}">${item.name}</option>`
    })
    let eles = document.getElementById("select-dropdown-" + column_name);
    let headOption = listen_to_fields !== column_name ? [] : [`<option class="py-10" value="" selected>Select your option...</option>`]
    eles.innerHTML = strHtmlRender + headOption;
    fixValueElement(colName)
}

function renderSelect({ id, name, disabled }) {
    let disabledSelect = disabled ? 'disabled' : '';
    const strHTML = ` 
    <select name='${name}' id="${id}" onchange="onChangedItem(value*1, ${name})" ${disabledSelect} class=" js-example-basic-multiple bg-white border border-gray-300 text-sm rounded-lg block mt-1 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
        <option class="py-10" value="" selected>Select your option...</option>
    </select>`;

    eleTriggers = document.getElementById('add-' + name);
    if (eleTriggers !== null) eleTriggers.innerHTML += strHTML;

}


function dropdownComponent({ id, name, dataSource, selected, disabled = false, title_field_name = 'name', disabled_field_name }) {
    renderSelect({ id, name, disabled });

    // Reduce data source when in edit mode
    objListener = Object.values(listenersJson).find((item) => item.column_name === name);
    if (typeof objListener !== 'undefined' && action === 'edit') {
        const {
            listen_to_attrs
            , listen_to_fields
        } = objListener

        if (colNamesListener.includes(name)) {
            itemsDB = [];
            var idTrigger = idEntities[triggers_colNames[name]]
            if (listen_to_fields === name) {
                itemsDB = dataSource.filter(ele => {
                    if (typeof [triggers_colNames[name]] !== 'undefined') {
                        if (typeof idTrigger !== 'undefined') {
                            return ele[listen_to_attrs] === idTrigger;
                        }
                    }
                })
            } else {
                itemsDB = [dataSource.find(ele => ele.id === idTrigger)]
            }
            dataSource = itemsDB
        }

    };

    // Render data source
    strHtmlTrigger = dataSource.map(item => {
        let checkSelected = selected * 1 === item.id * 1 || dataSource.length < 2 ? "selected" : "";
        let title = item[title_field_name];
        let disabledLine = item[disabled_field_name] ? 'disabled' : '';
        return `
                <option ${checkSelected} value=${item.id}   title="${title}" ${disabledLine} >
                        ${item.name}#@#${item.id}        
                </option>
    `})

    eleTriggers = document.getElementById("select-dropdown-" + name);
    if (eleTriggers !== null) eleTriggers.innerHTML += strHtmlTrigger;
}

function fixValueElement(colName) {
    var eleSelected = document.getElementById("select2-select-dropdown-" + colName + "-container");
    var value = eleSelected.innerText;
    var newValue = value.substring(0, value.indexOf('#@#'))
    eleSelected.innerHTML = newValue;
}










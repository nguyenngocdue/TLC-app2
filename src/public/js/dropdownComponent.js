
const renderSelect = ({ id, name, disabled, onChangedItems }) => {
    let disabledSelect = disabled ? 'disabled' : '';
    let strHTML = ` 
    <select name='${name}' id="${id}" onchange="onChangedItems(value*1, ${name})" ${disabledSelect} class=" js-example-basic-multiple bg-white border border-gray-300 text-sm rounded-lg block mt-1 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
        <option class="py-10" value="" selected >Select your option...</option>
    </select>`;
    eleTriggers = document.getElementById('add-' + name);
    if (eleTriggers !== null) eleTriggers.innerHTML += strHTML;

}


const dropdownComponent = ({
    id,
    name,
    dataSource,
    selected,
    disabled = false,
    title_field_name = 'name',
    disabled_field_name,
    onChanged,
    onLoad,
}) => {
    const renderHTML = ({ dataSource, selected, name, title_field_name, disabled_field_name }) => {
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
        // console.log(eleTriggers, strHtmlTrigger)
        if (eleTriggers !== null) eleTriggers.innerHTML += strHtmlTrigger;
    }
    renderSelect({ id, name, disabled, onChangedItems: onChanged });
    // Reduce data source when in edit mode
    dataSource = onLoad({ name, dataSource })
    renderHTML({ dataSource, selected, name, title_field_name, disabled_field_name })
}

const fixValueElement = (colName) => {
    var eleSelected = document.getElementById("select2-select-dropdown-" + colName + "-container");
    var value = eleSelected.innerText;
    var newValue = value.includes('#@#') ? value.substring(0, value.indexOf('#@#')) : value;
    eleSelected.innerHTML = newValue;
}


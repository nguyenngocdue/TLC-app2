"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.registerOnClickMasterCB = exports.renderMasterCB = void 0;
const renderMasterCB = (tableName, column) => {
    var _a;
    const tmpCol = column;
    if (!((_a = tmpCol.rendererAttrs) === null || _a === void 0 ? void 0 : _a.hasMasterCheckbox))
        return ``;
    return `<button
        id="${tableName}__${column.dataIndex}__master_checkbox" 
        class="hover:bg-gray-300 active:bg-gray-400 rounded cursor-pointer px-2 py-1"
        type="button"
        >
        <i id="${tableName}__${column.dataIndex}__master_checkbox_icon" class="fa fa-square-check"></i>
        </button>`;
};
exports.renderMasterCB = renderMasterCB;
const masterCbState = {};
const storedCbValues = {};
const backupCbValues = (tableName, dataIndex, renderer) => {
    const dataSource = tableData[tableName];
    const count = dataSource.data.length;
    // console.log(dataSource.data, count)
    const key = `${tableName}__${dataIndex}__${renderer}`;
    storedCbValues[key] = {};
    for (let rowIndex = 0; rowIndex < count; rowIndex++) {
        const cbId = `${tableName}__${dataIndex}__${renderer}__${rowIndex}`;
        const cb = dataSource.data[rowIndex][dataIndex];
        storedCbValues[key][cbId] = cb;
    }
    // console.log('storedCbValues', key, storedCbValues[key])
};
const onClickMasterCB = (tableName, column) => {
    var _a, _b;
    const dataSource = tableData[tableName];
    const rowCount = dataSource.data.length;
    // console.log('rowCount', rowCount, dataSource.data)
    const { dataIndex, renderer } = column;
    // Create a unique key to track state
    const key = `${tableName}__${dataIndex}__${renderer}`;
    // Backup checkbox values if not already stored
    if (!masterCbState[key])
        backupCbValues(tableName, dataIndex, renderer);
    // Initialize or cycle through the 3 states (1: on, 2: off, 0: current)
    masterCbState[key] = masterCbState[key] === undefined ? 1 : (masterCbState[key] + 1) % 3;
    const masterCbId = `${tableName}__${dataIndex}__master_checkbox`;
    let cb = null;
    for (let rowIndex = 0; rowIndex < rowCount; rowIndex++) {
        const cbId = `${tableName}__${dataIndex}__${renderer}__${rowIndex}`;
        // Determine the checkbox value based on the state
        let value = false;
        let iconClass = 'fa fa-square';
        switch (masterCbState[key]) {
            case 1: // On: set to true
                value = true;
                iconClass = `fa fa-square`;
                break;
            case 2: // Off: set to false
                value = false;
                iconClass = `fa fa-minus-square`;
                break;
            case 0: // Current: restore original value
                value = (_b = (_a = storedCbValues[key]) === null || _a === void 0 ? void 0 : _a[cbId]) !== null && _b !== void 0 ? _b : false;
                iconClass = `fa fa-check-square`;
                break;
        }
        // Update the data source and checkbox state
        dataSource.data[rowIndex][dataIndex] = value;
        cb = document.getElementById(cbId);
        if (cb)
            cb.checked = value;
        // console.log(`update ${cbId} to ${value}`)
        // Update the checkbox icon
        const icon = document.getElementById(`${masterCbId}_icon`);
        if (icon)
            icon.className = iconClass;
    }
    //trigger change jQuery doesn't work here
    //trigger change for the last checkbox to show/hide the master button group
    if (cb)
        cb.dispatchEvent(new Event('change'));
};
const registerOnClickMasterCB = (tableName) => {
    const columns = tableColumns[tableName];
    columns.forEach((column) => {
        var _a;
        const tmpCol = column;
        if (!((_a = tmpCol.rendererAttrs) === null || _a === void 0 ? void 0 : _a.hasMasterCheckbox))
            return;
        const masterId = `${tableName}__${column.dataIndex}__master_checkbox`;
        const masterCB = document.getElementById(masterId);
        if (masterCB)
            masterCB.addEventListener('click', () => onClickMasterCB(tableName, tmpCol));
    });
};
exports.registerOnClickMasterCB = registerOnClickMasterCB;
//# sourceMappingURL=MasterCheckbox.js.map
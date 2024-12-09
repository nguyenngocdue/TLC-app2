"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.onClickTrashAnItem = void 0;
// declare let tableColumns: { [tableName: string]: TableColumn[] }
const onClickTrashAnItem = (params, rowIndex) => {
    const dataSource = tableData[params.tableName];
    const dataLine = dataSource.data[rowIndex];
    let isDeleting = false;
    if (!dataLine['DESTROY_THIS_LINE']) {
        dataLine['DESTROY_THIS_LINE'] = true;
        isDeleting = true;
    }
    else {
        delete dataLine['DESTROY_THIS_LINE'];
    }
    // get the tr element
    const tr = document.getElementById(`${params.tableName}__${rowIndex}`);
    if (!tr)
        return;
    if (isDeleting) {
        tr.classList.add('bg-red-600');
    }
    else {
        tr.classList.remove('bg-red-600');
    }
};
exports.onClickTrashAnItem = onClickTrashAnItem;
//# sourceMappingURL=onClickTrashAnItem.js.map
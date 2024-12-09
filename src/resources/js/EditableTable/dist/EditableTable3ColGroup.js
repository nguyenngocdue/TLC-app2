"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.calTableTrueWidth = exports.makeColGroup = void 0;
const makeColGroup = ({ tableName }) => {
    const columns = tableColumns[tableName];
    return columns
        .map((column) => {
        if (column.invisible)
            return null;
        if (column.width)
            return `<col name="${column.dataIndex}" style="width:${column.width}px;" />`;
        return `<col name="${column.dataIndex}" />`;
    })
        .filter((col) => col !== null)
        .join('');
};
exports.makeColGroup = makeColGroup;
const calTableTrueWidth = ({ tableName }) => {
    const columns = tableColumns[tableName];
    return columns
        .map((column) => {
        if (column.invisible)
            return 0;
        if (column.width)
            return column.width * 1;
        return 0;
    })
        .reduce((acc, cur) => acc + cur, 0);
};
exports.calTableTrueWidth = calTableTrueWidth;
//# sourceMappingURL=EditableTable3ColGroup.js.map
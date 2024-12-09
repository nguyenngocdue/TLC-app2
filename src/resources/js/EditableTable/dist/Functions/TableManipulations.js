"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.replaceDivWith = exports.addTrAfterTopSpacer = exports.addTrBeforeBtmSpacer = exports.addClassToTr = exports.scrollToBottom = void 0;
const tailwind_merge_1 = require("tailwind-merge");
const scrollToBottom = (tableName) => {
    const tableId = `${tableName}__container`;
    const table = document.getElementById(`${tableId}`);
    if (table) {
        console.log('scrolling to the bottom', table);
        table.scrollTop = table.scrollHeight;
    }
};
exports.scrollToBottom = scrollToBottom;
const addClassToTr = (tableName, rowIndex, classList) => {
    const tr = document.getElementById(`${tableName}__${rowIndex}`);
    if (tr) {
        const currentClassList = tr.classList.value;
        tr.classList.add((0, tailwind_merge_1.twMerge)(currentClassList, classList));
    }
};
exports.addClassToTr = addClassToTr;
const addTrBeforeBtmSpacer = (tableName, emptyRow) => {
    const spacerId = `#${tableName} tbody>tr#spacer-bottom`;
    $(spacerId).before(emptyRow);
};
exports.addTrBeforeBtmSpacer = addTrBeforeBtmSpacer;
const addTrAfterTopSpacer = (tableName, emptyRow) => {
    const spacerId = `#${tableName} tbody>tr#spacer-top`;
    $(spacerId).after(emptyRow);
};
exports.addTrAfterTopSpacer = addTrAfterTopSpacer;
const replaceDivWith = (tableName, groupName, newDiv) => {
    const controlButtonGroup = newDiv;
    const controlButtonGroupDiv = document.getElementById(`${tableName}__${groupName}`);
    controlButtonGroupDiv && controlButtonGroupDiv.replaceWith(controlButtonGroup);
};
exports.replaceDivWith = replaceDivWith;
//# sourceMappingURL=TableManipulations.js.map
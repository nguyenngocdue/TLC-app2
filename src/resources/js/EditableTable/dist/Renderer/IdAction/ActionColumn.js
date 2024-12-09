"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.ActionColumn = void 0;
const onClickDuplicateAnItem_1 = require("../../ControlButtonGroup/onClickDuplicateAnItem");
const onClickTrashAnItem_1 = require("../../ControlButtonGroup/onClickTrashAnItem");
const Renderer4View_1 = require("../Renderer4View");
class ActionColumn extends Renderer4View_1.Renderer4View {
    constructor() {
        super(...arguments);
        this.tdClass = `text-center`;
    }
    applyPostRenderScript() {
        const { tableParams, rowIndex } = this;
        const { tableName } = tableParams;
        const idDup = `#${tableName} button#btnDuplicate__${rowIndex}`;
        const idTrash = `#${tableName} button#btnTrash__${rowIndex}`;
        const btnCopy = document.querySelector(idDup);
        const btnTrash = document.querySelector(idTrash);
        if (btnCopy) {
            btnCopy.addEventListener('click', () => {
                (0, onClickDuplicateAnItem_1.onDuplicateAnItem)(tableParams, rowIndex);
            });
        }
        if (btnTrash) {
            btnTrash.addEventListener('click', () => {
                (0, onClickTrashAnItem_1.onClickTrashAnItem)(tableParams, rowIndex);
            });
        }
    }
    control() {
        var _a;
        const { tableConfig, rowIndex } = this;
        const classList = (_a = tableConfig.classList) === null || _a === void 0 ? void 0 : _a.button;
        const iconCopy = `<i class="fa fa-copy text-xs"></i>`;
        const iconTrash = `<i class="fa fa-trash text-xs"></i>`;
        const btnCopy = `<button id="btnDuplicate__${rowIndex}" type="button" class="${classList} px-1.5 py-0.5 rounded bg-blue-500 hover:bg-blue-700 text-white" title="Duplicate">${iconCopy}</button>`;
        const btnTrash = `<button id="btnTrash__${rowIndex}" type="button" class="${classList} px-1.5 py-0.5 rounded bg-red-500 hover:bg-red-700 text-white" title="Delete">${iconTrash}</button>`;
        const rendered = `
        ${tableConfig.duplicatable ? btnCopy : ''}
        ${tableConfig.deletable ? btnTrash : ''}
        `;
        const actionBox = `<div class="flex justify-center gap-0.5">${rendered}</div>`;
        return actionBox;
    }
}
exports.ActionColumn = ActionColumn;
//# sourceMappingURL=ActionColumn.js.map
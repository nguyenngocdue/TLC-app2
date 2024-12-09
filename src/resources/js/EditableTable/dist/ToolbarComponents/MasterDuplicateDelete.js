"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.MasterDuplicateDelete = void 0;
const tailwind_merge_1 = require("tailwind-merge");
const ToolbarComponentParent_1 = require("./ToolbarComponentParent");
const sweetalert_1 = __importDefault(require("sweetalert"));
class MasterDuplicateDelete extends ToolbarComponentParent_1.ToolbarComponentParent {
    applyPostRenderScript() {
        // add event listener
        const { tableName } = this.params;
        $(`#${tableName}__btnMasterDuplicate`).on('click', this.onClickDuplicate.bind(this));
        $(`#${tableName}__btnMasterTrash`).on('click', this.onClickDelete.bind(this));
    }
    getSelectedIds() {
        return this.dataSource.data.filter((line) => line._checkbox_for_line_);
    }
    onClickDuplicate() {
        const listOfId = this.getSelectedIds();
        console.log('onClickDuplicate', listOfId);
        (0, sweetalert_1.default)({
            icon: 'info',
            title: 'Duplicating',
            text: `Are you sure you want to duplicate the ${listOfId.length} selected item${listOfId.length == 1 ? '' : 's'}?`,
            buttons: ['Cancel', 'Duplicate'],
            dangerMode: false,
        });
    }
    onClickDelete() {
        const listOfId = this.getSelectedIds();
        console.log('onClickDelete', listOfId);
        (0, sweetalert_1.default)({
            icon: 'warning',
            title: 'Deleting',
            text: `Are you sure you want to delete the ${listOfId.length} selected item${listOfId.length == 1 ? '' : 's'}?`,
            buttons: ['Cancel', 'Delete'],
            dangerMode: true,
        });
    }
    render() {
        var _a;
        const { tableConfig } = this.params;
        const classList = (0, tailwind_merge_1.twMerge)(((_a = tableConfig.classList) === null || _a === void 0 ? void 0 : _a.button) || '', 'px-2 py-1 rounded text-white gap-0.5 text-xs');
        return `<div id="${this.params.tableName}__master_button_group" class="hidden">
            <button id="${this.params.tableName}__btnMasterDuplicate" class="${classList} bg-blue-500 hover:bg-blue-700" type="button">
                <i class="fa fa-copy mr-1"></i>
                Duplicate
            </button>
            <button id="${this.params.tableName}__btnMasterTrash" class="${classList} bg-red-500 hover:bg-red-700" type="button">
                <i class="fa fa-trash mr-1"></i>
                Trash
            </button>
        </div>`;
    }
}
exports.MasterDuplicateDelete = MasterDuplicateDelete;
//# sourceMappingURL=MasterDuplicateDelete.js.map
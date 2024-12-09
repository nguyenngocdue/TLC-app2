"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.PerPage = void 0;
const tailwind_merge_1 = require("tailwind-merge");
const ToolbarComponentParent_1 = require("./ToolbarComponentParent");
class PerPage extends ToolbarComponentParent_1.ToolbarComponentParent {
    constructor() {
        super(...arguments);
        this.options = [10, 15, 20, 30, 40, 50, 100];
    }
    render() {
        var _a;
        const { per_page } = this.dataSource;
        // Build the `select` element as a string
        const selectHtml = `
            <select class="${(0, tailwind_merge_1.twMerge)((_a = this.params.tableConfig.classList) === null || _a === void 0 ? void 0 : _a.dropdown, `w-30`)}">
                ${this.options
            .map((option) => {
            const selected = option === per_page ? 'selected' : '';
            return `<option value="${option}" ${selected}>${option} / page</option>`;
        })
            .join('')}
            </select>
        `;
        return selectHtml.trim(); // Trim to remove any unnecessary whitespace
    }
}
exports.PerPage = PerPage;
//# sourceMappingURL=PerPage.js.map
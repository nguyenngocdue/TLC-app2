"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.TotalItems = void 0;
const ToolbarComponentParent_1 = require("./ToolbarComponentParent");
const Functions_1 = require("../Functions");
class TotalItems extends ToolbarComponentParent_1.ToolbarComponentParent {
    render() {
        const { total } = this.dataSource;
        const totalStr = Functions_1.Str.humanReadable(total);
        const itemStr = total === 1 ? 'item' : 'items';
        return `Total <span class="font-bold px-0.5" title="${total}">${totalStr}</span> ${itemStr}`;
    }
}
exports.TotalItems = TotalItems;
//# sourceMappingURL=TotalItems.js.map
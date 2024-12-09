"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.TotalItems_Paginator = void 0;
const ToolbarComponentParent_1 = require("./ToolbarComponentParent");
const Paginator_1 = require("./Paginator");
const TotalItems_1 = require("./TotalItems");
class TotalItems_Paginator extends ToolbarComponentParent_1.ToolbarComponentParent {
    render() {
        const totalItems = new TotalItems_1.TotalItems(this.params);
        const paginator = new Paginator_1.Paginator(this.params);
        return `<div class="flex items-center gap-1">
            ${totalItems.render()}
            ${paginator.render()}
        </div>`;
    }
}
exports.TotalItems_Paginator = TotalItems_Paginator;
//# sourceMappingURL=TotalItems_Paginator.js.map
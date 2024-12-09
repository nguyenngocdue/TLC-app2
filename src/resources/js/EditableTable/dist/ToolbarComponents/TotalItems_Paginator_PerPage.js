"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.TotalItems_Paginator_PerPage = void 0;
const Paginator_1 = require("./Paginator");
const PerPage_1 = require("./PerPage");
const TotalItems_1 = require("./TotalItems");
const ToolbarComponentParent_1 = require("./ToolbarComponentParent");
class TotalItems_Paginator_PerPage extends ToolbarComponentParent_1.ToolbarComponentParent {
    render() {
        const totalItems = new TotalItems_1.TotalItems(this.params);
        const paginator = new Paginator_1.Paginator(this.params);
        const perPage = new PerPage_1.PerPage(this.params);
        return `<div class="flex items-center gap-1">
            ${totalItems.render()}
            ${paginator.render()}
            ${perPage.render()}
        </div>`;
    }
}
exports.TotalItems_Paginator_PerPage = TotalItems_Paginator_PerPage;
//# sourceMappingURL=TotalItems_Paginator_PerPage.js.map